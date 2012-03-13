<?php

namespace libamqp;

use \OutOfRangeException, \BadMethodCallException, \InvalidArgumentException;
use \ArrayAccess;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type array
 *
 * Named with a leading underscore because of a collision with PHP's definitions
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class _array implements Value, ArrayAccess
{
	private $array = array();

	/**
	 *
	 */
	public function __construct()
	{
	}

	/**
	 * @static
	 * @param array $value keys are ignored, values are assumed to be of type Value
	 * @return _array
	 */
	public static function instance_from_php_value(array $value)
	{
		$amqpArray = new _array();
		$index = 0;
		foreach ($value as $entryValue)
		{
			$amqpArray->offsetSet($index, $entryValue);
			$index++;
		}
		return $amqpArray;
	}

	/**
	 * @return int length of the list
	 */
	public function length()
	{
		return count($this->array);
	}

	/**
	 * @param int $index
	 * @return bool not in the list
	 */
	public function does_not_have($index)
	{
		return !$this->offsetExists($index);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Whether a offset exists
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean Returns true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		if (!is_int($offset))
		{
			throw new InvalidArgumentException("Only integer index keys are permitted, not $offset");
		}
		return array_key_exists($offset, $this->array);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to unset
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		if (!is_int($offset))
		{
			throw new InvalidArgumentException("Only integer index keys are permitted, not $offset");
		}
		if (!$this->offsetExists($offset))
		{
			return;
		}
		throw new BadMethodCallException('Not yet fully supported - does unset mean to set to libamqp\null?');
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to retrieve
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		if (!is_int($offset))
		{
			throw new InvalidArgumentException("Only integer index keys are permitted, not $offset");
		}
		if ($offset < 0)
		{
			throw new InvalidArgumentException("Negative integer index keys, $offset, are not permitted");
		}
		if (!array_key_exists($offset, $this->array))
		{
			throw new OutOfRangeException("Integer index key, $offset, does not exist - check length() first");
		}
		return $this->array[$offset];
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to set
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (!is_int($offset))
		{
			throw new InvalidArgumentException("Only integer index keys are permitted, not $offset");
		}
		if ($offset < 0)
		{
			throw new InvalidArgumentException("Negative integer index keys, $offset, are not permitted");
		}

		$length = count($this->array);
		if ($length == 0)
		{
			$this->array[0] = $value;
			return;
		}
		$expectedClass = get_class($this->array[0]);
		$actualClass = get_class($value);
		if ($expectedClass != $actualClass)
		{
			throw new InvalidArgumentException("Arrays can only have one class of Value (currently $expectedClass, provided with $actualClass)");
		}

		// Replacement
		if (array_key_exists($offset, $this->array))
		{
			$this->array[$offset] = $value;
			return;
		}

		// New value - previous index must exist
		if ($this->does_not_have($offset - 1))
		{
			throw new InvalidArgumentException("Array index previous ($offset - 1) to insertion ($offset) has no value - arrays must be assigned contiguously)");
		}
		$this->array[$offset] = $value;
	}

	/**
	 * @param \string $className
	 * @return string
	 */
	public function toString($className)
	{
		$string = $className . "(";
		$afterFirst = FALSE;
		foreach ($this->array as $value)
		{
			if ($afterFirst)
			{
				$string .= ", ";
			}
			else
			{
				$afterFirst = TRUE;
			}
			$string .= sprintf("%s", $value);
		}
		$string .= ")";
		return $string;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString(__CLASS__);
	}
}

?>
