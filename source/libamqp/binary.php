<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');
require_once('message_id.php');

/**
 * Represents an AMQP Primitive Type binary
 *
 * Maps to a PHP 'binary' string of 0 or more bytes
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class binary implements Value, message_id
{
	public $value;

	/**
	 * @param string $value a 16-byte binary string
	 */
	public function __construct($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value must be a string representing a byte array");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param string $value
	 * @return binary
	 */
	public static function instance_from_php_value($value)
	{
		return new binary($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString(__CLASS__);
	}

	/**
	 * @param string $className
	 * @return string
	 */
	protected function toString($className)
	{
		return sprintf("%s(%s)", $className, bin2hex($this->value));
	}
}

?>
