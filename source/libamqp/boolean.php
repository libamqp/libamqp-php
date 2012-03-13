<?php

namespace libamqp;

use \LogicException, \BadMethodCallException;

require_once('Value.php');

/**
* Represents an AMQP Primitive Type boolean
*
* @category Networking
* @package libamqp
* @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */

class boolean implements Value
{
	private static $instance_true;
	private static $instance_false;

	public static function init()
	{
		self::$instance_true = new boolean(true);
		self::$instance_false = new boolean(false);
	}

	private $value;

	/**
	 * @param bool $value
	 */
	private function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * @static
	 * @param bool $value
	 * @return libamqp\boolean
	 */
	public static function instance_from_php_value($value)
	{
		return ($value == TRUE) ? self::TRUE() : self::FALSE();
	}

	/**
	 * @static
	 * @return libamqp\boolean
	 */
	public static function TRUE()
	{
		return self::$instance_true;
	}

	/**
	 * @static
	 * @return libamqp\boolean
	 */
	public static function FALSE()
	{
		return self::$instance_false;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value ? "TRUE" : "FALSE");
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
	 * @return bool
	 * @throws \BadMethodCallException
	 */
	public function __get($name)
	{
		if ($name == "value")
		{
			return $this->value;
		}
		throw new BadMethodCallException('Not allowed.');
	}

	/**
	 * @param string $name
	 * @param bool $value
	 * @throws \BadMethodCallException
	 */
	public function __set($name, $value)
	{
		throw new BadMethodCallException('Not allowed.');
	}

	/**
	 * @param \string $name
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

boolean::init();

?>
