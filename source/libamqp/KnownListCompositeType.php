<?php

namespace libamqp;

use \BadMethodCallException, \InvalidArgumentException, \OutOfBoundsException;

require_once('KnownCompositeType.php');
require_once('symbol.php');
require_once('ulong.php');
require_once('_list.php');
require_once('null.php');

/**
 * Represents a described list with a known mapping
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
abstract class KnownListCompositeType extends KnownCompositeType
{
	/**
	 *
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->value = new _list();
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (!array_key_exists($name, static::$listMappings))
		{
			throw new OutOfBoundsException("no such property $name");
		}
		$listMapping = static::$listMappings[$name];
		$index = $listMapping[0];
		if ($this->value->does_not_have($index))
		{
			return $listMapping[1][$name];
		}
		$underlyingValue = $this->value[$index];
		if ($underlyingValue instanceof null)
		{
			return $listMapping[1][$name];
		}

		// Assumes simplistic implementations
		return $underlyingValue->value;
	}

	/**
	 * @param string $name
	 * @param mixed $value Can be a primitive PHP value or a Value
	 */
	public function __set($name, $value)
	{
		if (!array_key_exists($name, static::$listMappings))
		{
			throw new OutOfBoundsException("no such property $name");
		}
		$listMapping = static::$listMappings[$name];
		$index = $listMapping[0];

		if ($value == NULL)
		{
			$amqpValue = \libamqp\null::NULL();
		}
		else if ($value instanceof Value)
		{
			$interfaceClass = $listMapping[2];
			if (!($value instanceof $interfaceClass))
			{
				throw new InvalidArgumentException("$value is not an instance of $interfaceClass");
			}
			$amqpValue = $value;
		}
		else
		{
			$constructorClass = $listMapping[3];
			$amqpValue = call_user_func(array($constructorClass, 'instance_from_php_value'), $value);
		}

		$this->value[$index] = $amqpValue;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		if (!array_key_exists($name, static::$listMappings))
		{
			throw new OutOfBoundsException("no such property $name");
		}
		$listMapping = static::$listMappings[$name];
		$index = $listMapping[0];
		return !$this->value->does_not_have($index);
	}

	/**
	 * @param string $name
	 * @throws \BadMethodCallException
	 */
	public function __unset($name)
	{
		throw new BadMethodCallException("Not yet supported - rather hard to do!");
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
		$string = $className . "(";
		$afterFirst = FALSE;
		foreach (static::$listMappings as $propertyName => $listMapping)
		{
			if ($afterFirst)
			{
				$string .= ", ";
			}
			else
			{
				$afterFirst = TRUE;
			}
			$propertyValue = $this->__get($propertyName);
			if (is_null($propertyValue))
			{
				$propertyValueString = "NULL";
			}
			else if (is_bool($propertyValue))
			{
				$propertyValueString = sprintf("%s", $propertyValue ? "TRUE" : "FALSE");
			}
			else
			{
				$propertyValueString = sprintf("%s", $propertyValue);
			}
			$string .= sprintf("%s", $propertyValueString);
		}
		$string .= ")";
		return $string;
	}
}

?>
