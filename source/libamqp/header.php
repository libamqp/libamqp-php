<?php

namespace libamqp;

use \BadMethodCallException;

require_once('KnownListCompositeType.php');
require_once('section.php');
require_once('boolean.php');
require_once('ubyte.php');
require_once('uint.php');

/**
 * Represents an AMQP Message Format header section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class header extends KnownListCompositeType implements section
{
	protected static $descriptor_name;
	protected static $descriptor_code;
	protected static $listMappings = array
	(
		"durable"        => array(0, FALSE, '\libamqp\boolean', '\libamqp\boolean'),
		"priority"       => array(1, 4,     '\libamqp\ubyte',   '\libamqp\ubyte'),
		"ttl"            => array(2, NULL,  '\libamqp\uint',    '\libamqp\uint'),
		"first_acquirer" => array(3, FALSE, '\libamqp\boolean', '\libamqp\boolean'),
		"delivery_count" => array(4, 0,     '\libamqp\uint',    '\libamqp\uint')
	);

	/**
	 * @param bool|NULL $durable
	 * @param int|NULL $priority
	 * @param int|NULL $ttl
	 * @param bool|NULL $first_acquirer
	 * @param int|NULL $delivery_count
	 * @return header header suitable for sending as a client
	 **/
	public function __construct($durable = NULL, $priority = NULL, $ttl = NULL, $first_acquirer = NULL, $delivery_count = NULL)
	{
		parent::__construct();

		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch ($numberOfArgumentsSpecified)
		{
			case 5:
				$this->delivery_count = $delivery_count;

			case 4:
				$this->first_acquirer = $first_acquirer;

			case 3:
				$this->ttl = $ttl;

			case 2:
				$this->priority = $priority;

			case 1:
				$this->durable = $durable;

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

header::init(new symbol("amqp:header:list"), ulong::instance_from_domain(0x00000000, 0x00000070));

?>
