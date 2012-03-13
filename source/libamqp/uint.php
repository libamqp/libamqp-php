<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');
require_once('milliseconds.php');

/**
 * Represents an AMQP Primitive Type uint
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 * @todo: Support on 32-bit PHP platforms
 */
class uint implements Value, milliseconds
{
	public $value;

	/**
	 * @param int $value
	 */
	public function __construct($value)
	{
		if (!is_int($value))
		{
			throw new InvalidArgumentException("$value is not an int");
		}
		if ($value < 0 || $value > 4294967295)
		{
			throw new InvalidArgumentException("$value is not an uint (0 to 4294967295)");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param int $value
	 * @return uint
	 */
	public static function instance_from_php_value($value)
	{
		return new uint($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

?>
