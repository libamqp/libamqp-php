<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type float
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 * @todo: Conversion operations to/from a PHP 64-bit float (a Java double)
 */
class float implements Value
{
	public $value;

	/**
	 * @param string $value a 4-byte network byte order (big endian) binary string
	 */
	public function __construct($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value is not an double");
		}
		if (strlen($value) != 4)
		{
			throw new InvalidArgumentException("$value must be a binary string of exactly 4 bytes");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param float $value a 4-byte network byte order (big endian) binary string
	 * @return double
	 */
	public static function instance_from_php_value($value)
	{
		return new float($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	}
}

?>
