<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type byte
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class byte implements Value
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
		if ($value < -128 || $value > 127)
		{
			throw new InvalidArgumentException("$value is not a byte (-128 to 127)");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param int $value
	 * @return byte
	 */
	public static function instance_from_php_value($value)
	{
		return new byte($value);
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
