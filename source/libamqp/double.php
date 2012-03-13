<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type double
 *
 * In PHP, the built-in type 'float' is 64-bits and is equivalent to Java's double
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class double implements Value
{
	public $value;

	/**
	 * @param float $value
	 */
	public function __construct($value)
	{
		if (!is_float($value))
		{
			throw new InvalidArgumentException("$value is not an double");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param float $value
	 * @return double
	 */
	public static function instance_from_php_value($value)
	{
		return new double($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%F)", __CLASS__, $this->value);
	}
}

?>
