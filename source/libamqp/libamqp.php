<?php

/**
 * libamqp PHP wrapper
 *
 * Provides PHP classes to use with libamqp to access AMQP 1-0 brokers
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 2 of the Apache License
 * that is availabe through the world-wide-web at the following URI:
 * http://www.apache.org/licenses/LICENSE-2.0.html
 * If you did not receive a copy of the Apache License and are unable to
 * obtain it through the web, please send an e-mail to license
 * @stormmq.com so we can mail you a copy immediately.
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 * @link http:// <INSERT PECL HERE>
 */

namespace libamqp;

use \InvalidArgumentException, \BadFunctionCallException;

require_once('Value.php');
require_once('null.php');
require_once('boolean.php');
require_once('ubyte.php');
require_once('ushort.php');
require_once('uint.php');
require_once('ulong.php');
require_once('byte.php');
require_once('short.php');
require_once('int.php');
require_once('long.php');
require_once('float.php');
require_once('double.php');
require_once('decimal32.php');
require_once('decimal64.php');
require_once('decimal128.php');
require_once('char.php');
require_once('timestamp.php');
require_once('uuid.php');
require_once('binary.php');
require_once('string.php');
require_once('symbol.php');
require_once('_list.php');
require_once('map.php');
require_once('_array.php');

require_once('section.php');
require_once('header.php');
require_once('annotations.php');
require_once('delivery_annotations.php');
require_once('message_annotations.php');
require_once('application_properties.php');
require_once('application_data.php');
require_once('data.php');
require_once('amqp_sequence.php');
require_once('amqp_value.php');
require_once('properties.php');
require_once('footer.php');

require_once('error.php');
require_once('fields.php');

require_once('delivery_state.php');
require_once('outcome.php');
require_once('received.php');
require_once('accepted.php');
require_once('rejected.php');
require_once('released.php');
require_once('modified.php');
require_once('modified.php');

define('LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE', 0);
define('LIBAMQP_DELIVERY_MODE_AT_LEAST_ONCE', 1);
define('LIBAMQP_DELIVERY_MODE_EXACTLY_ONCE', 2);

/**
 * Represents an AMQP Message Format data application-data section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 *
 * @param NULL|bool|int|float|string|application_data|Value|array $application_data Primitive PHP value, application_data or array(application_data). NULL is converted to libamqp\amqp_value(libamqp\null)
 * @param header|NULL $header
 * @param annotations|NULL $delivery_annotations
 * @param annotations|NULL $message_annotations
 * @param properties|NULL $properties
 * @param application_properties|NULL $application_properties
 * @param array|NULL $footer_callbacks An array of callbacks
 * @param bool|constant("LIBAMQP_SEND_AT_MOST_ONCE") $exactly_once
 * @param callback|NULL $delivery_state_callback
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

	if (is_null($delivery_state_callback))
	{
		if ($delivery_mode != constant("LIBAMQP_DELIVERY_MODE_AT_MOST_ONCE"))
		{
			throw new InvalidArgumentException("delivery_mode is $delivery_mode but there is no delivery_state_callback");
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

	echo "\nSending Message\n";
	echo "header: $header\n";
	echo "delivery-annotations: $delivery_annotations\n";
	echo "message-annotations: $message_annotations\n";
	echo "properties: $properties\n";
	echo "application-properties: $application_properties\n";
	foreach ($applicationDataSections as $applicationDataSection)
	{
		echo "application-data: $applicationDataSection\n";
	}
	echo "footer: $footer\n";

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
