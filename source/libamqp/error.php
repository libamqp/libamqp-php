<?php

namespace libamqp;

use \BadMethodCallException;

require_once('KnownListCompositeType.php');
require_once('symbol.php');
require_once('string.php');
require_once('fields.php');

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
class error extends KnownListCompositeType
{
	protected static $descriptor_name;
	protected static $descriptor_code;
	protected static $listMappings = array
	(
		"condition"   => array(0, NULL, '\libamqp\symbol', '\libamqp\symbol'),
		"description" => array(1, NULL, '\libamqp\string', '\libamqp\string'),
		"info"        => array(2, NULL, '\libamqp\fields', '\libamqp\fields')
	);

	/**
	 * @param string|\libamqp\symbol $condition
	 * @param null $description
	 * @param fields|null $info
	 */
	public function __construct($condition, $description = NULL, fields &$info = NULL)
	{
		parent::__construct(static::$listMappings);

		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch ($numberOfArgumentsSpecified)
		{
			case 3:
				$this->info = $info;

			case 2:
				$this->description = $description;

			case 1:
				$this->condition = $condition;
				break;

			case 0:
				throw new BadMethodCallException("too few function arguments");

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

error::init(new symbol("amqp:error:list"), ulong::instance_from_domain(0x00000000, 0x0000001d));

?>
