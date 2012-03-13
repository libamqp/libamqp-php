<?php

namespace libamqp;

use \BadMethodCallException, \InvalidArgumentException, \LogicException;

require_once('Value.php');

/**
* Represents an AMQP Primitive Type null
*
* Used in preference to PHP's NULL to allow differentiation of a key that is null and a missing in key when interacting with AMQP's Primitive Type map
*
* @category Networking
* @package libamqp
* @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class null implements Value
{
	private static $instance;

	public static function init()
	{
		self::$instance = new null();
	}

	/**
	 *
	 */
	private function __construct()
	{
	}

	/**
	 * @static
	 * @param string $value
	 * @return libamqp\null
	 * @throws \InvalidArgumentException
	 */
	public static function instance_from_php_value($value)
	{
		if (isset($value))
		{
			throw new InvalidArgumentException("value must be NULL not $value");
		}
		return self::$instance;
	}

	/**
	 * @static
	 * @return libamqp\null
	 */
	public static function NULL()
	{
		return self::$instance;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return __CLASS__ . "()";
	}

	/**
	 * @throws \LogicException
	 */
	public function __clone()
	{
		throw new LogicException('Clone is not allowed.');
	}

	/**
	 * @throws \LogicException
	 */
	public function __wakeup()
	{
		throw new LogicException('Unserializing is not allowed.');
	}

	/**
	 * @param $name
	 * @return NULL
	 * @throws \BadMethodCallException
	 */
	public function __get($name)
	{
		if ($name == "value")
		{
			return NULL;
		}
		throw new BadMethodCallException('Not allowed.');
	}

	/**
	 * @param string $name
	 * @param NULL $value
	 * @throws \BadMethodCallException
	 */
	public function __set($name, $value)
	{
		throw new BadMethodCallException('Not allowed.');
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $name == "value";
	}

	/**
	 * @param string $name
	 * @throws \BadMethodCallException
	 */
	public function __unset($name)
	{
		throw new BadMethodCallException('Not allowed.');
	}
}

null::init();

?>
