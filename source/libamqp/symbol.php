<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type symbol
 *
 * Maps to a PHP string assumed to be ASCII encoded (validation performed)
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class symbol implements Value
{
	public $value;

	/**
	 * @param string $value
	 */
	public function __construct($value)
	{
		self::guard_is_us_ascii($value);
		$this->value = $value;
	}

	/**
	 * @static
	 * @param $value
	 * @throws \InvalidArgumentException
	 */
	private static function guard_is_us_ascii($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value must be an ASCII string");
		}
		$length = strlen($value);
		for ($index = 0; $index < $length; $index++)
		{
			$byte = ord($value[$index]);
			if ($byte >= 0x80)
			{
				throw new InvalidArgumentException("$value must be an ASCII string, byte $byte at index $index is not");
			}
		}
	}

	/**
	 * @static
	 * @param string $value
	 * @return symbol
	 */
	public static function instance_from_php_value($value)
	{
		return new symbol($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value);
	}

	/**
	 * @return bool
	 */
	public function starts_with_x()
	{
		return $this->starts_with("x-");
	}

	/**
	 * @return bool
	 */
	public function starts_with_x_opt()
	{
		return $this->starts_with("x-opt-");
	}

	/**
	 * @return bool
	 */
	public function starts_with_amqp()
	{
		return $this->starts_with("amqp:");
	}

	/**
	 * @param string $start starts with
	 * @return bool
	 */
	public function starts_with($start)
	{
		return strpos($this->value, $start, 0) === 0;
	}
}


?>
