<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type decimal64
 *
 * Maps to a PHP 'binary' string of 8 bytes
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class decimal64 implements Value
{
	public $value;

	/**
	 * @param string $value a 8-byte network byte order (big endian) binary string
	 */
	public function __construct($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value must be a binary string");
		}
		if (strlen($value) != 8)
		{
			throw new InvalidArgumentException("$value must be a binary string of exactly 8 bytes");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param string $value a 8-byte network byte order (big endian) binary string
	 * @return decimal64
	 */
	public static function instance_from_php_value($value)
	{
		return new decimal64($value);
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
