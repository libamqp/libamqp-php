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

use \InvalidArgumentException;

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

require_once('fields.php');

/**
 * Use an instance of this as a callback for at-least-once and exactly-once messaging
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
abstract class sender_settlement
{
	const sender_settle_mode_unsettled = 0;
	const sender_settle_mode_settled = 1;
	const sender_settle_mode_mixed = 2;

	const receiver_settle_mode_first = TRUE;
	const receiver_settle_mode_second = FALSE;

	private $exactly_once;

	/**
	 * @param bool $exactly_once if specified, exactly-once messaging is used and (for simplicity) the send mirrors the receiver's outcome
	 */
	public function __construct($exactly_once = FALSE)
	{
		if (!is_bool($exactly_once))
		{
			throw new InvalidArgumentException("exactly_once must be a boolean");
		}
		$this->exactly_once = $exactly_once;
	}

	/**
	 * @param uint $section_number
	 * @param ulong $section_offset
	 */
	public function received(uint &$section_number, ulong &$section_offset)
	{
	}

	/**
	 *
	 */
	public abstract function accepted();

	/**
	 * @abstract
	 * @param symbol $condition
	 * @param \string|NULL $condition
	 * @param fields|NULL $fields
	 */
	public abstract function rejected(&$condition, &$description, fields &$fields = NULL);

	/**
	 *
	 */
	public abstract function released();

	/**
	 * @abstract
	 * @param bool $delivery_failed
	 * @param bool $undeliverable_here
	 * @param fields|NULL $fields
	 */
	public abstract function modified($delivery_failed, $undeliverable_here, fields &$fields = NULL);

}

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
 */
function send($application_data, header &$header = NULL, delivery_annotations &$delivery_annotations = NULL, message_annotations &$message_annotations = NULL, properties &$properties = NULL, application_properties &$application_properties = NULL, sender_settlement &$sender_settlement = NULL)
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
		throw new InvalidArgumentException("application_data must be either NULL (maps to libamqp\null), int (maps to AmqpLong) bool (maps to AmqpBoolean,) string (a byte array), Value or an array of application_data");
	}

	/*
	 * Link settings negotiation:-
	 *  - link is negotiated as mixed
	 *  - presence of settlement object is used as hint to whether to use first or second
	 */

	//XXXXXX Implement this XXXXXX

	/*
	 * Settlement:-
	 * 	- presence of settlement object is used
	 *    - if link does not support settlement object, then throw an exception
	 */

	echo "Sending Message\n";
	echo "header: $header\n";
	echo "delivery-annotations: $delivery_annotations\n";
	echo "message-annotations: $message_annotations\n";
	echo "properties: $properties\n";
	echo "application-properties: $application_properties\n";
	foreach ($applicationDataSections as $applicationDataSection)
	{
		echo "application-data: $applicationDataSection\n";
	}
	echo "footer: \n\n";
}

?>
