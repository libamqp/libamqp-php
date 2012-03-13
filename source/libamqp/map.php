<?php

namespace libamqp;

use \InvalidArgumentException, \BadMethodCallException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type map
 *
 * Uses a double-indirection method to allow key types to be mixed
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class map implements Value
{
	private $keys = array();
	private $values = array();

	/**
	 *
	 */
	public function __construct()
	{
	}

	// This is limited to creating Symbol keys or Ulong keys. Given that PHP auto-casts strings that look like ints to ints, it's a disaster waiting to happen
	/**
	 * @static
	 * @param array $value
	 * @return map
	 */
	public static function instance_from_php_value(array $value)
	{
		$amqpMap = new map();
		$index = 0;
		foreach ($value as $key => $entryValue)
		{
			if (is_int($key))
			{
				$amqpMap->keys[$index] = new ulong($key);
			}
			else
			{
				$amqpMap->keys[$index] = new symbol($key);
			}
			if (!($entryValue instanceof Value))
			{
				throw new InvalidArgumentException("$value must be an Value");
			}
			$amqpMap->values[$index] = $entryValue;
			$index++;
		}
		return $amqpMap;
	}

	/**
	 * @param Value $name
	 * @return Value value or NULL (returns libamqp\null as well)
	 **/
	public function get(Value $name)
	{
		// Naive and slow but effective. PHP arrays can not have objects as keys
		for ($index = 0; $index < count($this->keys); $index++)
		{
			if ($this->keys[$index] == $name)
			{
				return $this->values[$index];
			}
		}
		return NULL;
	}

	/**
	 * @param Value $name
	 * @param Value $value
	 * @return mixed
	 */
	public function set(Value $name, Value $value)
	{
		for ($index = 0; $index < count($this->keys); $index++)
		{
			if ($this->keys[$index] == $name)
			{
				$this->values[$index] = $value;
				return;
			}
		}
		$indexInValues = count($this->keys);
		$this->keys[$indexInValues] = $name;
		$this->values[$indexInValues] = $value;
	}

	/**
	 * @param Value $name
	 * @return bool
	 */
	public function is_set(Value $name)
	{
		for ($index = 0; $index < count($this->keys); $index++)
		{
			if ($this->keys[$index] == $name)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		return $this->toString(__CLASS__);
	}

	/**
	 * @param \string $className
	 * @return string
	 */
	public function toString($className)
	{
		$result = $className."(";
		$afterFirst = FALSE;
		$length = count($this->keys);
		for($index = 0; $index < $length; $index++)
		{
			if ($afterFirst)
			{
				$result .= ", ";
			}
			else
			{
				$afterFirst = TRUE;
			}
			$result .= sprintf("%s=>%s", $this->keys[$index], $this->values[$index]);
		}
		$result .= ")";
		return $result;
	}
}

?>
