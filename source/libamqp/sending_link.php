<?php

namespace libamqp;

use \BadFunctionCallException;
use \InvalidArgumentException;

define('LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE', 0);
define('LIBAMQP_DELIVERY_MODE_AT_LEAST_ONCE', 1);
define('LIBAMQP_DELIVERY_MODE_EXACTLY_ONCE', 2);

define('LIBAMQP_SENDER_SETTLE_MODE_UNSETTLED', 0);
define('LIBAMQP_SENDER_SETTLE_MODE_SETTLED', 1);
define('LIBAMQP_SENDER_SETTLE_MODE_MIXED', 2);

define('LIBAMQP_RECEIVER_SETTLE_MODE_FIRST', 0);
define('LIBAMQP_RECEIVER_SETTLE_MODE_SECOND', 1);

/**
 * Represents an AMQP sending_link
 *
 * Used in preference to PHP's NULL to allow differentiation of a key that is null and a missing in key when interacting with AMQP's Primitive Type map
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class sending_link
{
	public $name;
	private $target;
	private $snd_settle_mode;
	private $rcv_settle_mode;

	public function __construct($name, $target, $preferred_rcv_settle_mode = 0)
	{
		if (!is_string($name))
		{
			throw new InvalidArgumentException("link name $name is not a string");
		}
		$this->name = $name;

		// TODO: implement targets
		$this->target = $target;

		// We always attempt to negotiate mixed for now
		$snd_settle_mode = constant('LIBAMQP_SENDER_SETTLE_MODE_MIXED');
		if (!is_int($snd_settle_mode))
		{
			throw new InvalidArgumentException("link snd_settle_mode $snd_settle_mode is not an int");
		}
		if ($snd_settle_mode < 0 || $snd_settle_mode > 2)
		{
			throw new InvalidArgumentException("link snd_settle_mode $snd_settle_mode is not between 0 and 2");
		}
		$this->snd_settle_mode = $snd_settle_mode;

		if (!is_int($preferred_rcv_settle_mode))
		{
			throw new InvalidArgumentException("link preferred_rcv_settle_mode $preferred_rcv_settle_mode is not an int");
		}
		if ($preferred_rcv_settle_mode < 0 || $preferred_rcv_settle_mode > 1)
		{
			throw new InvalidArgumentException("link preferred_rcv_settle_mode $preferred_rcv_settle_mode is not 0 or 1");
		}
		$this->rcv_settle_mode = $preferred_rcv_settle_mode;
	}

	/**
	 * @param $rcv_settle_mode
	 */
	public function received_attach($rcv_settle_mode)
	{
		$this->rcv_settle_mode = $rcv_settle_mode;
	}

	/**
	 * @param NULL|bool|int|float|string|application_data|Value|array $application_data Primitive PHP value, application_data or array(application_data). NULL is converted to libamqp\amqp_value(libamqp\null)
	 * @param header|NULL $header
	 * @param \libamqp\annotations|\libamqp\delivery_annotations|null $delivery_annotations
	 * @param \libamqp\annotations|\libamqp\message_annotations|null $message_annotations
	 * @param properties|NULL $properties
	 * @param application_properties|NULL $application_properties
	 * @param array|NULL $footer_callbacks An array of callbacks
	 * @param int $delivery_mode
	 * @param callback|NULL $delivery_state_callback
	 * @internal param bool|constant("LIBAMQP_SEND_AT_MOST_ONCE") $exactly_once
	 */
	function send($application_data, header $header = NULL, delivery_annotations $delivery_annotations = NULL, message_annotations $message_annotations = NULL, properties $properties = NULL, application_properties $application_properties = NULL, array $footer_callbacks = array(), $delivery_mode = 0, $delivery_state_callback = NULL)
	{
		if (is_null($application_data))
		{
			$applicationDataSections = array(new amqp_value(\libamqp\null::NULL()));
		}
		else if (is_bool($application_data))
		{
			$applicationDataSections = array(new amqp_value(\libamqp\boolean::instance_from_php_value($application_data)));
		}
		else if (is_int($application_data))
		{
			$applicationDataSections = array(new amqp_value(long::instance_from_php_value($application_data)));
		}
		else if (is_float($application_data))
		{
			$applicationDataSections = array(new amqp_value(double::instance_from_php_value($application_data)));
		}
		else if (is_string($application_data))
		{
			$applicationDataSections = array(new data($application_data));
		}
		else if ($application_data instanceof application_data )
		{
			$applicationDataSections = array($application_data);
		}
		else if ($application_data instanceof Value)
		{
			$applicationDataSections = array(new amqp_value($application_data));
		}
		else if (is_array($application_data))
		{
			$length = count($application_data);
			if ($length == 0)
			{
				throw new InvalidArgumentException("application_data can not be an empty array()");
			}
			if (!($application_data[0] instanceof application_data))
			{
				throw new InvalidArgumentException("application_data must be of type application_data");
			}

			if ($length > 1)
			{
				$firstObjectsClass = get_class($application_data[0]);
				for ($index = 1; $index < $length; $index++)
				{
					if (get_class($application_data[$index]) != $firstObjectsClass)
					{
						throw new InvalidArgumentException("array(application_data) must all be of the same instance of application_data, eg $firstObjectsClass");
					}
				}
			}
			$applicationDataSections = $application_data;
		}
		else
		{
			throw new InvalidArgumentException('application_data must be either NULL (maps to libamqp\null), int (maps to long) bool (maps to boolean) string (a byte array), Value or an array of application_data');
		}

		c_attach_sending_link_if_necessary($this);

		$this->validate_settlement_choice($delivery_mode, $delivery_state_callback);

		if (count($footer_callbacks) > 0)
		{
			$fake_enconded_binary_string = c_footer_encode($properties, $application_properties, $applicationDataSections);
			$footer = new footer();

			foreach($footer_callbacks as $footer_callback)
			{
				if (!is_callable($footer_callback))
				{
					throw new InvalidArgumentException("a footer_callback is not a callback but $footer_callback");
				}
				call_user_func($footer_callback, &$footer, $fake_enconded_binary_string);
			}
		}
		else
		{
			$footer = NULL;
		}

		/*
			 * Link settings negotiation:-
			 *  - link is negotiated as mixed
			 *  - presence of settlement object is used as hint to whether to use first or second
			 */

		echo "Sending Message\n";
		echo "\theader: $header\n";
		echo "\tdelivery-annotations: $delivery_annotations\n";
		echo "\tmessage-annotations: $message_annotations\n";
		echo "\tproperties: $properties\n";
		echo "\tapplication-properties: $application_properties\n";
		foreach ($applicationDataSections as $applicationDataSection)
		{
			echo "\tapplication-data: $applicationDataSection\n";
		}
		echo "\tfooter: $footer\n";

		if (isset($delivery_state_callback))
		{
			$delivery_state = new accepted();
			echo "Receiving delivery-state $delivery_state\n";
			$result = call_user_func($delivery_state_callback, &$delivery_state);
			if ($delivery_mode == constant('LIBAMQP_DELIVERY_MODE_EXACTLY_ONCE'))
			{
				if (is_null($result))
				{
					$result = new accepted();
				}
				c_settle_exactly_once($result);
			}
			else
			{
				if (isset($result))
				{
					throw new BadFunctionCallException("delivery_mode is LIBAMQP_DELIVERY_MODE_AT_LEAST_ONCE but callback $delivery_state_callback returned $result and not NULL");
				}
			}
		}
	}

	private function validate_settlement_choice($delivery_mode, $delivery_state_callback)
	{
		if (is_null($delivery_state_callback))
		{
			if ($delivery_mode != constant("LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE"))
			{
				throw new InvalidArgumentException("delivery_mode is $delivery_mode but there is no delivery_state_callback");
			}
			if ($this->rcv_settle_mode == constant('LIBAMQP_RECEIVER_SETTLE_MODE_SECOND'))
			{
				throw new InvalidArgumentException("rcv_settle_mode is $this->rcv_settle_mode but there is no delivery_state_callback");
			}
		}
		else
		{
			if (!is_callable($delivery_state_callback))
			{
				throw new InvalidArgumentException("delivery_state_callback is not a callback but $delivery_state_callback");
			}
			if ($delivery_mode == constant('LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE'))
			{
				throw new InvalidArgumentException("delivery_mode is LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE but there is a delivery_state_callback, $delivery_state_callback");
			}
		}

		switch ($this->snd_settle_mode)
		{
			case constant('LIBAMQP_SENDER_SETTLE_MODE_UNSETTLED'):
				if ($delivery_mode == constant('LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE'))
				{
					throw new InvalidArgumentException('Settlement mode mismatch');
				}
				break;

			case constant('LIBAMQP_SENDER_SETTLE_MODE_SETTLED'):
				if ($delivery_mode != constant('LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE'))
				{
					throw new InvalidArgumentException('Settlement mode mismatch');
				}
				break;

			case constant('LIBAMQP_SENDER_SETTLE_MODE_MIXED'):
				break;

			default:
				throw new InvalidArgumentException("Unknown sender-settle-mode $this->snd_settle_mode");
		}
	}
}

/**
 * @param sending_link $sending_link
 */
function c_attach_sending_link_if_necessary(sending_link &$sending_link)
{
	echo "\nAttaching sending link $sending_link->name if necessary\n";
	//$sending_link->received_attach(constant('LIBAMQP_RECEIVER_SETTLE_MODE_FIRST'));
}

/**
 * @param properties|null $properties
 * @param application_properties|null $application_properties
 * @param array $applicationDataSections
 * @return string
 */
function c_footer_encode(properties $properties = NULL, application_properties $application_properties = NULL, array $applicationDataSections)
{
	echo "Fake Encoding of Message\n";
	return "fake encoded data";
}

/**
 * @param outcome $outcome
 */
function c_settle_exactly_once(outcome &$outcome)
{
	echo "Settled with outcome: $outcome\n";
}

?>
