<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type decimal128
 *
 * Maps to a PHP 'binary' string of 16 bytes
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class decimal128 implements Value
{
	public $value;

	/**
	 * @param string $value a 16-byte network byte order (big endian) binary string
	 */
	public function __construct($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value must be a binary string");
		}
		if (strlen($value) != 16)
		{
			throw new InvalidArgumentException("$value must be a binary string of exactly 16 bytes");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param string $value a 16-byte network byte order (big endian) binary string
	 * @return decimal128
	 */
	public static function instance_from_php_value($value)
	{
		return new decimal128($value);
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
