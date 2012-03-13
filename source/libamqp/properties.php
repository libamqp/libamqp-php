<?php

namespace libamqp;

use \BadMethodCallException;

require_once('KnownListCompositeType.php');
require_once('section.php');
require_once('message_id.php');
require_once('binary.php');
require_once('string.php');
require_once('timestamp.php');
require_once('uint.php');
require_once('null.php');

/**
 * Represents an AMQP Message Format properties section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 *
 * @todo: Needs refactoring with header into a generic described type / known list
 * @todo: Allow specification of message_id conversion func
 */
class properties extends KnownListCompositeType implements section
{
	protected static $descriptor_name;
	protected static $descriptor_code;
	protected static $listMappings = array
	(
		"message_id"           => array(0,  NULL, '\libamqp\message_id', '\libamqp\string'),
		"user_id"              => array(1,  NULL, '\libamqp\binary',     '\libamqp\binary'),
		"to"                   => array(2,  NULL, '\libamqp\string',     '\libamqp\string'),
		"subject"              => array(3,  NULL, '\libamqp\string',     '\libamqp\string'),
		"reply_to"             => array(4,  NULL, '\libamqp\string',     '\libamqp\string'),
		"correlation_id"       => array(5,  NULL, '\libamqp\message_id', '\libamqp\string'),
		"content_type"         => array(6,  NULL, '\libamqp\string',     '\libamqp\string'),
		"content_encoding"     => array(7,  NULL, '\libamqp\string',     '\libamqp\string'),
		"absolute_expiry_time" => array(8,  NULL, '\libamqp\timestamp',  '\libamqp\timestamp'),
		"creation_time"        => array(9,  NULL, '\libamqp\timestamp',  '\libamqp\timestamp'),
		"group_id"             => array(10, NULL, '\libamqp\string',     '\libamqp\string'),
		"group_sequence"       => array(11, NULL, '\libamqp\uint',       '\libamqp\uint'),
		"reply_to_group_id"    => array(12, NULL, '\libamqp\string',     '\libamqp\string'),
	);

	/**
	 * @static
	 * @param string $message_id_constructor_class
	 * @param string $correlation_id_constructor_class
	 */
	public static function change_message_id_constructor_class($message_id_constructor_class = '\libamqp\string', $correlation_id_constructor_class = '\libamqp\string')
	{
		self::$listMappings["message_id"][3] = $message_id_constructor_class;
		self::$listMappings["correlation_id"][3] = $correlation_id_constructor_class;
	}

	/**
	 * @param string|message_id|NULL $message_id
	 * @param string|binary|NULL $user_id
	 * @param string|NULL $to
	 * @param string|NULL $subject
	 * @param string|NULL $reply_to
	 * @param message_id|NULL $correlation_id
	 * @param string|NULL $content_type
	 * @param string|NULL $content_encoding
	 * @param int|timestamp|NULL $absolute_expiry_time
	 * @param int|timestamp|NULL $creation_time
	 * @param string|NULL $group_id
	 * @param uint|NULL $group_sequence
	 * @param string|NULL $reply_to_group_id
	 */
	public function __construct($message_id = NULL, $user_id = NULL, $to = NULL, $subject = NULL, $reply_to = NULL, message_id $correlation_id = NULL, $content_type = NULL, $content_encoding = NULL, $absolute_expiry_time = NULL, $creation_time = NULL, $group_id = NULL, $group_sequence = NULL, $reply_to_group_id = NULL)
	{
		parent::__construct();

		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch ($numberOfArgumentsSpecified)
		{
			case 13:
				$this->reply_to_group_id = $reply_to_group_id;

			case 12:
				$this->group_sequence = $group_sequence;

			case 11:
				$this->group_id = $group_id;

			case 10:
				$this->creation_time = $creation_time;

			case 9:
				$this->absolute_expiry_time = $absolute_expiry_time;

			case 8:
				$this->content_encoding = $content_encoding;

			case 7:
				$this->content_type = $content_type;

			case 6:
				$this->correlation_id = $correlation_id;

			case 5:
				$this->reply_to = $reply_to;

			case 4:
				$this->subject = $subject;

			case 3:
				$this->to = $to;

			case 2:
				$this->user_id = $user_id;

			case 1:
				$this->message_id = $message_id;

			case 0:
				break;

			default:
				throw new BadMethodCallException("too many function arguments");
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return parent::toString(__CLASS__);
	}
}

require_once('symbol.php');
require_once('ulong.php');

properties::init(new symbol("amqp:properties:list"), ulong::instance_from_domain(0x00000000, 0x00000073));

?>
