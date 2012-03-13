<?php

namespace libamqp;

interface AmqpValue
{
}

// Whilst it is tempting to use PHP's NULL in practice it is very difficult because of a need in an AmqpMap to distinguish a null key or value from an absent value
class AmqpNull implements AmqpValue
{
	private static $instance;
	
	private function __construct()
	{
	}
	
	public static function instance_from_php_value($value)
	{
		if ($value != NULL)
		{
			trigger_error("value must be NULL not $value", E_USER_ERROR);
		}
		return self::$NULL();
	}

	public static function NULL()
	{
		if (!isset(self::$instance))
		{
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
	
	public function __toString()
	{
		return __CLASS__ . "()";
	}
	
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}
}

class AmqpBoolean implements AmqpValue
{
	private static $instance_true;
	private static $instance_false;
	
	private $value;
	
	private function __construct($value)
	{
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return ($value == TRUE) ? self::TRUE() : self::FALSE();
	}
	
	public static function TRUE()
	{
		if (!isset(self::$instance_true))
		{
			self::$instance_true = new AmqpBoolean(TRUE);
		}
		return self::$instance_true;
	}
	
	public static function FALSE()
	{
		if (!isset(self::$instance_false))
		{
			self::$instance_false = new AmqpBoolean(FALSE);
		}
		return self::$instance_false;
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value ? "TRUE" : "FALSE");
	}
	
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}
	
	public function __get($name)
	{
		if ($name == "value")
		{
			return $this->value;
		}
		trigger_error('Not allowed.', E_USER_ERROR);
	}
}

class AmqpUbyte implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < 0 || $value > 255)
		{
			trigger_error("$value is not an ubyte (0 to 255)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpUbyte($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

class AmqpUshort implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < 0 || $value > 65535)
		{
			trigger_error("$value is not an ushort (0 to 65535)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpUshort($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

class AmqpUint implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < 0 || $value > 4294967295)
		{
			// TODO: PHP is restricted to a 2^31 - 1 PHP_MAX_INT on 32-bit platforms
			trigger_error("$value is not an uint (0 to 4294967295)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpUint($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

// On 32-bit platforms, PHP can not support 64-bit long / ulong -> ? store byte encoded but expanded and as network byte order (so gotcha with PHP's unpack) ?
// Or store as a string suitable for gmp_add, etc

// class AmqpUlong implements AmqpValue
// {
// 	PHP_INT_SIZE
// 	PHP_INT_MAX
// 	
// 	public $value;
// 	
// 	public function __construct($value)
// 	{
// 		if (is_int($value))
// 		{
// 			if ($value < 0 || $value > 0xFFFF)
// 			{
// 				trigger_error("$value is not an uint (0-4294967295)", E_USER_ERROR);
// 			}
// 		}
// 		$this->value = $value;
// 	}
// 	
// 	public static function instance_from_php_value($value)
// 	{
// 		return new AmqpUint($value);
// 	}
// 	
// 	public function __toString()
// 	{
// 		return sprintf("%s(%u)", __CLASS__, $this->value);
// 	}
// 	
// 	
// 	
// 	public function __toString()
// 	{
// 		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
// 		// hex2bin in PHP < 5.4.0, but most be an even number of characters in the string
// 		// $z = pack("H*", "6578616d706c65206865782064617461");
// 	}
// }



class AmqpByte implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < -128 || $value > 127)
		{
			trigger_error("$value is not a byte (-128 to 127)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpByte($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

class AmqpShort implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < -32768 || $value > 32767)
		{
			trigger_error("$value is not a short (-32768 to 32767)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpShort($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

class AmqpInt implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < -2147483648 || $value > 2147483647)
		{
			trigger_error("$value is not an int (-2147483648 to 2147483647)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpInt($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

// On 32-bit platforms, PHP can not support 64-bit long / ulong -> ? store byte encoded but expanded and as network byte order (so gotcha with PHP's unpack) ?
// Or store as a string suitable for gmp_add, etc

class AmqpLong implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < -9223372036854775808 || $value > 9223372036854775807)
		{
			trigger_error("$value is not a long (-9223372036854775808 to 9223372036854775807)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpLong($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

// Gotcha: is_float() is an alias of is_double
// Gotcha: PHP only has 64-bit floats...
// So it would seem prudent to store the value byte-encoded...
class AmqpFloat implements AmqpValue
{
	// public $value;
	// 
	// public function __construct($value)
	// {
	// 	if (!is_double($value))
	// 	{
	// 		trigger_error("$value is not an double", E_USER_ERROR);
	// 	}
	// 	$this->value = $value;
	// }
	// 
	// public static function instance_from_php_value($value)
	// {
	// 	return new AmqpFloat($value);
	// }
	// 
	// public function __toString()
	// {
	// 	return sprintf("%s(%u)", __CLASS__, $this->value);
	// }
}

class AmqpDouble implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_double($value))
		{
			trigger_error("$value is not an double", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpDouble($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%F)", __CLASS__, $this->value);
	}
}

class AmqpDecimal32 implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a binary string");
		}
		if (strlen($value) != 4)
		{
			trigger_error("$value must be a binary string of exactly 4 bytes");
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpDecimal32($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	}
}

class AmqpDecimal64 implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a binary string");
		}
		if (strlen($value) != 8)
		{
			trigger_error("$value must be a binary string of exactly 8 bytes");
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpDecimal64($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	}
}

class AmqpDecimal128 implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a binary string");
		}
		if (strlen($value) != 16)
		{
			trigger_error("$value must be a binary string of exactly 16 bytes");
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpDecimal128($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	}
}

class AmqpChar implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_int($value))
		{
			trigger_error("$value is not an int", E_USER_ERROR);
		}
		if ($value < -2147483648 || $value > 2147483647)
		{
			trigger_error("$value is not an int (-2147483648 to 2147483647)", E_USER_ERROR);
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpChar($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

// 64-bit weirdness to deal with
// class AmqpTimestamp implements AmqpValue
// {
// 
// }

// See the PECL package uuid (Wraps libuuid) or http://www.shapeshifter.se/2008/09/29/uuid-generator-for-php/ for ways to generate UUIDs
class AmqpUuid implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a binary string");
		}
		if (strlen($value) != 16)
		{
			trigger_error("$value must be a binary string of exactly 16 bytes");
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpUuid($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	}
}

class AmqpString implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a UTF-8 string without a BOM");
		}
		// Writing a comprehensive UTF-8 checking function is not trivial and very slow in PHP
		// It needs to handle non-transmission characters
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpString($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value);
	}
}

class AmqpSymbol implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be an ASCII string");
		}
		$length = strlen($value);
		for($index = 0; $index < $length; $index++)
		{
			$byte = ord($value[$index]);
			if ($byte >= 0x80)
			{
				trigger_error("$value must be an ASCII string; byte at index $index is not");
			}
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpSymbol($value);
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value);
	}
}

class AmqpBinary implements AmqpValue
{
	public $value;
	
	public function __construct($value)
	{
		if (!is_string($value))
		{
			trigger_error("$value must be a string representing a byte array");
		}
		$this->value = $value;
	}
	
	public static function instance_from_php_value($value)
	{
		return new AmqpBinary($value);
	}
	
	public function __toString()
	{
		return toString(__CLASS__);
	}
	
	protected function toString($className)
	{
		return sprintf("%s(%s)", $className, bin2hex($this->value));
	}
}

// Note: Uses a double-indirection method to allow key types to be mixed
class AmqpMap implements AmqpValue
{
	private $keys = array();
	private $values = array();
	
	public function __construct()
	{
	}
	
	// This is limited to creating Symbol keys or Ulong keys. Given that PHP auto-casts strings that look like ints to ints, it's a disaster waiting to happen
	public static function instance_from_php_value(array $value)
	{
		$amqpMap = new AmqpMap();
		$index = 0;
		foreach($value as $key => $entryValue)
		{
			if (is_int($key))
			{
				$amqpMap->keys[$index] = new AmqpUlong($key);
			}
			else
			{
				$amqpMap->keys[$index] = new AmqpSymbol($key);
			}
			if (!($entryValue instanceof AmqpValue))
			{
				trigger_error("$value must be an AmqpValue");
			}
			$amqpMap->values[$index] = $entryValue;
			$index++;
		}
		return $amqpMap;
	}
	
	/**
	* @param AmqpValue key
	* @return AmqpValue value or NULL (returns AmqpNull if actually null)
	**/
	public function get(AmqpValue &$key)
	{
		// Naive and slow but effective. PHP arrays can not have objects as keys
		for ($index = 0; $index < count($this->keys); $index++)
		{
			if ($this->keys[$index] == $key)
			{
				return $this->values[$index];
			}
		}
		return NULL;
	}
	
	public function put(AmqpValue &$key, AmqpValue &$value)
	{	
		for ($index = 0; $index < count($this->keys); $index++)
		{	
			if ($this->keys[$index] == $key)
			{
				$this->values[$index] = $value;
				return;
			}
		}
		$indexInValues = count($this->keys);
		$this->keys[$indexInValues] = $key;
	}
	
	// public function __toString()
	// {
	// 	return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	// 	// hex2bin in PHP < 5.4.0, but most be an even number of characters in the string
	// 	// $z = pack("H*", "6578616d706c65206865782064617461");
	// }
}

class AmqpList implements AmqpValue
{
	private $list = array();
	
	public function __construct()
	{
	}
	
	public static function instance_from_php_value(array $value)
	{
		$amqpList = new AmqpList();
		$index = 0;
		foreach($value as $entryValue)
		{
			$amqpList->set($index, $entryValue);
			$index++;
		}
		return $amqpList;
	}
	
	/**
	* @return int length of the list
	*/
	public function length()
	{
		return count($this->list);
	}
	
	/**
	* @param int index
	* @return bool not in the list
	*/
	public function does_not_have($index)
	{
		return !array_key_exists($index, $this->list);
	}
	
	/**
	* @param int index
	* @return AmqpValue value
	*/
	public function get($index)
	{
		if (!is_int($index))
		{
			trigger_error("Only integer index keys are permitted, not $index", E_USER_ERROR);
		}
		if ($index < 0)
		{
			trigger_error("Negative integer index keys, $index, are not permitted", E_USER_ERROR);
		}
		if (!array_key_exists($index, $this->list))
		{
			trigger_error("Integer index key, $index, does not exist - check length() first", E_USER_ERROR);
		}
		return $this->list[$index];
	}
	
	/**
	* @param int index
	* @param AmqpValue value
	* @return NULL null
	*/
	public function set($index, AmqpValue &$value)
	{
		if (!is_int($index))
		{
			trigger_error("Only integer index keys are permitted, not $index", E_USER_ERROR);
		}
		if ($index < 0)
		{
			trigger_error("Negative integer index keys, $index, are not permitted", E_USER_ERROR);
		}
		
		// Fill in any lesser indices with AmqpNull if not set
		for($previousIndex = $index - 1; $previousIndex >= 0; $previousIndex--)
		{
			if (array_key_exists($previousIndex, $this->list))
			{
				break;
			}
			$this->list[$previousIndex] = AmqpNull::NULL();
		}
		$this->list[$index] = $value;
	}
	
	public function __toString()
	{
		return toString(__CLASS__);
	}
	
	public function toString($className)
	{
		$string = $className."(";
		$afterFirst = FALSE;
		foreach($this->list as $value)
		{
			if ($afterFirst)
			{
				$string .= ", ";
			}
			else
			{
				$afterFirst = TRUE;
			}
			$string .= $value->__toString();
		}
		$string .= ")";
		return $string;
	}
}

class AmqpArray implements AmqpValue
{
	private $array = array();
	
	public function __construct()
	{
	}
	
	public static function instance_from_php_value(array $value)
	{
		$amqpArray = new AmqpArray();
		$index = 0;
		foreach($value as $entryValue)
		{
			$amqpArray->set($index, $entryValue);
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
	* @param int index
	* @return bool not in the list
	*/
	public function does_not_have($index)
	{
		return !array_key_exists($index, $this->array);
	}
	
	/**
	* @param int index
	* @return AmqpValue value
	*/
	public function get($index)
	{
		if (!is_int($index))
		{
			trigger_error("Only integer index keys are permitted, not $index", E_USER_ERROR);
		}
		if ($index < 0)
		{
			trigger_error("Negative integer index keys, $index, are not permitted", E_USER_ERROR);
		}
		if (!array_key_exists($index, $this->array))
		{
			trigger_error("Integer index key, $index, does not exist - check length() first", E_USER_ERROR);
		}
		return $this->list[$index];
	}
	
	/**
	* @param int index
	* @param AmqpValue value
	* @return NULL null
	*/
	public function set($index, AmqpValue &$value)
	{
		if (!is_int($index))
		{
			trigger_error("Only integer index keys are permitted, not $index", E_USER_ERROR);
		}
		if ($index < 0)
		{
			trigger_error("Negative integer index keys, $index, are not permitted", E_USER_ERROR);
		}
		
		$length = count($this->array);
		if ($length == 0)
		{
			$this->list[0] = $value;
			return;
		}
		$expectedClass = get_class($this->list[0]);
		$actualClass = get_class($value);
		if ($expectedClass != $actualClass)
		{
			trigger_error("Arrays can only have one class of AmqpValue (currently $expectedClass, provided with $actualClass)", E_USER_ERROR);
		}
		
		// Replacement
		if (array_key_exists($index, $this->array))
		{
			$this->array[$index] = $value;
			return;
		}
		
		// New value - previous index must exist
		if ($this->does_not_have($index - 1))
		{
			trigger_error("Array index previous ($index - 1) to insertion ($index) has no value - arrays must be assigned contiguously)", E_USER_ERROR);
		}
		$this->list[$index] = $value;
	}
	
	// public function __toString()
	// {
	// 	return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	// 	// hex2bin in PHP < 5.4.0, but most be an even number of characters in the string
	// 	// $z = pack("H*", "6578616d706c65206865782064617461");
	// }
}

class Header
{
	private static $listMappings = array
	(
		"durable" => array(0, FALSE, 'AmqpBoolean'),
		"priority" => array(1, 4, 'AmqpUbyte'),
		"ttl" => array(2, NULL, 'AmqpUint'),
		"first_acquirer" => array(3, FALSE, 'AmqpBoolean'),
		"delivery_count" => array(4, 0, 'AmqpUint')
	);
	
	private $value;
	
	/**
	* @param bool durable
	* @param bool priority
	* @param bool ttl
	* @param bool first_acquirer
	* @param bool delivery_count
	* @return Header header suitable for sending as a client
	**/
	function __construct($durable = NULL, $priority = NULL, $ttl = NULL, $first_acquirer = NULL, $delivery_count = NULL)
	{
		$this->value = new AmqpList();
		
		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch($numberOfArgumentsSpecified)
		{
			case 5:
				$this->delivery_count = $delivery_count;
			
			case 4:
				$this->first_acquirer = $first_acquirer;
			
			case 3:
				$this->ttl = $ttl;
			
			case 2:
				$this->priority = $priority;
			
			case 1:
				$this->durable = $durable;
			
			case 0:
				break;
			
			default:
				trigger_error("too many function arguments");
		}
	}
	
	public function __get($name)
	{
		if (!array_key_exists($name, self::$listMappings))
		{
			trigger_error("no such property $name");
		}
		$listMapping = self::$listMappings[$name];
		$index = $listMapping[0];
		if ($this->value->does_not_have($index))
		{
			return $listMapping[1][$name];
		}
		$underlyingValue = $this->value->get($index);
		if ($underlyingValue instanceof AmqpNull)
		{
			return $listMapping[1][$name];
		}
		
		// Assumes simplistic implementations
		return $underlyingValue->value;
	}
	
	public function __set($name, $value)
	{
		if (!array_key_exists($name, self::$listMappings))
		{
			trigger_error("no such property $name");
		}
		$listMapping = self::$listMappings[$name];
		$index = $listMapping[0];
		
		if ($value == NULL)
		{
			$amqpValue = AmqpNull::NULL();
		}
		else
		{
			$constructorClass = $listMapping[2];
			$amqpValue = call_user_func(array("libamqp\\$constructorClass", 'instance_from_php_value'), $value);
		}
		
		$this->value->set($index, $amqpValue);
	}
	
	public function __toString()
	{
		$string = __CLASS__."(";
		$afterFirst = FALSE;
		foreach(self::$listMappings as $propertyName => $listMapping)
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
			$string .= sprintf("%s", $propertyValue);
		}
		$string .= ")";
		return $string;
	}
	// public function __toString()
	// {
	// 	return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	// 	// hex2bin in PHP < 5.4.0, but most be an even number of characters in the string
	// 	// $z = pack("H*", "6578616d706c65206865782064617461");
	// }
}

// Needs refactoring to remove duplication
class Properties
{
	private static $listMappings = array
	(
		"message_id" => array(0, NULL, 'AmqpMessageId'), // Wrong!
		"user_id" => array(1, NULL, 'AmqpBinary'),
		"to" => array(2, NULL, 'AmqpString'),
		"subject" => array(3, NULL, 'AmqpString'),
		"reply_to" => array(4, NULL, 'AmqpString'),
		"correlation_id" => array(5, NULL, 'AmqpMessageId'), // Wrong!
		"content_type" => array(6, NULL, 'AmqpString'),
		"content_encoding" => array(7, NULL, 'AmqpString'),
		"absolute_expiry_time" => array(8, NULL, 'AmqpTimestamp'),
		"creation_time" => array(9, NULL, 'AmqpTimestamp'),
		"group_id" => array(10, NULL, 'AmqpString'),
		"group_sequence" => array(11, NULL, 'AmqpUint'),
		"reply_to_group_id" => array(12, NULL, 'AmqpString'),
	);
	
	private $value;
	
	/**
	* @param bool durable
	* @param bool priority
	* @param bool ttl
	* @param bool first_acquirer
	* @param bool delivery_count
	* @return Header header suitable for sending as a client
	**/
	function __construct($message_id = NULL, $user_id = NULL, $to = NULL, $subject = NULL, $reply_to = NULL, $correlation_id = NULL, $content_type = NULL, $content_encoding = NULL, $absolute_expiry_time = NULL, $creation_time = NULL, $group_id = NULL, $group_sequence = NULL, $reply_to_group_id = NULL)
	{
		$this->value = new AmqpList();
		
		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch($numberOfArgumentsSpecified)
		{
			case 13:
				$this->reply_to_group_id = $reply_to_group_id;
			
			case 12:
				$this->group_sequence = $group_sequence;
			
			case 11:
				$this->group_id = $group_id;
			
			case 10:
				$this->creation_time = $creation_time;
			
			case 9:
				$this->absolute_expiry_time = $absolute_expiry_time;
			
			case 8:
				$this->content_encoding = $content_encoding;
			
			case 7:
				$this->content_type = $content_type;
			
			case 6:
				$this->correlation_id = $correlation_id;
			
			case 5:
				$this->reply_to = $reply_to;
			
			case 4:
				$this->subject = $subject;
			
			case 3:
				$this->to = $to;
			
			case 2:
				$this->user_id = $user_id;
			
			case 1:
				$this->message_id = $message_id;
			
			case 0:
				break;
			
			default:
				trigger_error("too many function arguments");
		}
	}
	
	public function __get($name)
	{
		if (!array_key_exists($name, self::$listMappings))
		{
			trigger_error("no such property $name");
		}
		$listMapping = self::$listMappings[$name];
		$index = $listMapping[0];
		if ($this->value->does_not_have($index))
		{
			return $listMapping[1][$name];
		}
		$underlyingValue = $this->value->get($index);
		if ($underlyingValue instanceof AmqpNull)
		{
			return $listMapping[1][$name];
		}
		
		// Assumes simplistic implementations
		return $underlyingValue->value;
	}
	
	public function __set($name, $value)
	{
		if (!array_key_exists($name, self::$listMappings))
		{
			trigger_error("no such property $name");
		}
		$listMapping = self::$listMappings[$name];
		$index = $listMapping[0];
		
		if ($value == NULL)
		{
			$amqpValue = AmqpNull::NULL();
		}
		else
		{
			$constructorClass = $listMapping[2];
			$amqpValue = call_user_func(array("libamqp\\$constructorClass", 'instance_from_php_value'), $value);
		}
		
		$this->value->set($index, $amqpValue);
	}
	
	// public function __toString()
	// {
	// 	return sprintf("%s(%s)", __CLASS__, bin2hex($this->value));
	// 	// hex2bin in PHP < 5.4.0, but most be an even number of characters in the string
	// 	// $z = pack("H*", "6578616d706c65206865782064617461");
	// }
}

interface ApplicationDataSection
{
}

class DataApplicationDataSection extends AmqpBinary implements ApplicationDataSection
{
	public function __construct($value)
	{
		parent::__construct($value);
	}
	
	public function __toString()
	{
		return parent::toString(__CLASS__);
	}
}

class AmqpValueApplicationDataSection implements ApplicationDataSection
{
	public $value;
	
	public function __construct(AmqpValue &$value)
	{
		$this->value = $value;
	}
	
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value);
	}
}

class AmqpSequenceApplicationDataSection extends AmqpList implements ApplicationDataSection
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __toString()
	{
		return parent::toString(__CLASS__);
	}
}

class Annotations extends AmqpMap
{
	// Problem: PHP 'auto-magically' converts strings that 'look like' integers to integers...
	// Problem: PHP does not always support 64-bit integers as keys, even in arrays!
	public function __construct(array &$annotations)
	{
		parent::__construct();
		foreach($annotations as $key => $value)
		{
			if (is_int($key))
			{
				$amqpKey = new AmqpUlong($key);
			}
			else if (is_string($key))
			{
				$amqpKey = new AmqpSymbol($key);
			}
			else
			{
				trigger_error('Only ulong (int) or symbolic (string) annotation keys are permitted', E_USER_ERROR);
			}
			if (!($value instanceof AmqpValue))
			{
				trigger_error('Only AmqpValue object annotation values are permitted', E_USER_ERROR);
			}
			parent::put($amqpKey, $value);
		}
	}
	
	/**
	* @param int|string|AmqpUlong|AmqpSymbol key
	* @return AmqpValue value or NULL (returns AmqpNull if actually null)
	**/
	public function get(&$key)
	{
		if (is_int($key))
		{
			$amqpKey = new AmqpUlong($key);
		}
		else if (is_string($key))
		{
			$amqpKey = new AmqpSymbol($key);
		}
		else if ($key instanceof AmqpUlong || $key instanceof AmqpSymbol)
		{
			$amqpKey = $key;
		}
		else
		{
			trigger_error('Only ulong (int) or symbolic (string) annotation keys are permitted', E_USER_ERROR);
		}
		return parent::get($key);
	}
	
	/**
	* @param int|string|AmqpUlong|AmqpSymbol key
	* @param AmqpValue value
	* @return NULL null
	*/
	public function put(&$key, AmqpValue &$value)
	{
		if (is_int($key))
		{
			$amqpKey = new AmqpUlong($key);
		}
		else if (is_string($key))
		{
			$amqpKey = new AmqpSymbol($key);
		}
		else if ($key instanceof AmqpUlong || $key instanceof AmqpSymbol)
		{
			$amqpKey = $key;
		}
		else
		{
			trigger_error('Only ulong (int) or symbolic (string) annotation keys are permitted', E_USER_ERROR);
		}
		return parent::put($key, $value);
	}
}

class ApplicationProperties extends AmqpMap
{
	public function __construct(array &$application_properties)
	{
		parent::__construct();
		foreach($application_properties as $key => $value)
		{
			if (!is_string($key))
			{
				trigger_error('Only string keys are permitted', E_USER_ERROR);
			}
			if (!($value instanceof AmqpValue))
			{
				trigger_error('Only AmqpValue object values are permitted', E_USER_ERROR);
			}
			if ($value instanceof AmqpMap || $value instanceof AmqpArray || $value instanceof AmqpList)
			{
				trigger_error('Only simple AmqpValue object values are permitted (ie not AmqpMap, AmqpList or AmqpArray)', E_USER_ERROR);
			}
			// TODO: Derived types / described types checking
			parent::put(new AmqpString($key), $value);
		}
	}
	
	/**
	* @param string|AmqpString key
	* @return AmqpValue value or NULL (returns AmqpNull if actually null)
	**/
	public function get(&$key)
	{
		if (is_string($key))
		{
			$amqpKey = new AmqpString($key);
		}
		else if ($key instanceof AmqpString)
		{
			$amqpKey = $key;
		}
		else
		{
			trigger_error('Only ulong (int) or symbolic (string) annotation keys are permitted', E_USER_ERROR);
		}
		return parent::get($key);
	}
	
	/**
	* @param string|AmqpString key
	* @param AmqpValue value
	* @return NULL null
	*/
	public function put(&$key, AmqpValue &$value)
	{
		if (is_string($key))
		{
			$amqpKey = new AmqpSymbol($key);
		}
		else if ($key instanceof AmqpString)
		{
			$amqpKey = $key;
		}
		else
		{
			trigger_error('Only ulong (int) or symbolic (string) annotation keys are permitted', E_USER_ERROR);
		}	
		if ($value instanceof AmqpMap || $value instanceof AmqpArray || $value instanceof AmqpList)
		{
			trigger_error('Only simple AmqpValue object values are permitted (ie not AmqpMap, AmqpList or AmqpArray)', E_USER_ERROR);
		}
		return parent::put($key, $value);
	}
}

// Note 1: I have reversed the signature slightly as PHP does not support named positioned arguments or defaults for omitted arguments
function send($application_data, Header &$header = NULL, Annotations &$delivery_annotations = NULL, Annotations &$message_annotations = NULL, Properties &$properties = NULL, ApplicationProperties &$application_properties = NULL)
{
	if (is_null($application_data))
	{
		$applicationDataSections = array(new AmqpValueApplicationDataSection(AmqpNull::NULL()));
	}
	else if (is_bool($application_data))
	{
		$applicationDataSections = array(new AmqpValueApplicationDataSection(AmqpBoolean::instance_from_php_value($application_data)));
	}
	else if (is_int($application_data))
	{
		$applicationDataSections = array(new AmqpValueApplicationDataSection(AmqpLong::instance_from_php_value($application_data)));
	}
	else if (is_float($application_data))
	{
		$applicationDataSections = array(new AmqpValueApplicationDataSection(AmqpDouble::instance_from_php_value($application_data)));
	}
	else if (is_string($application_data))
	{
		$applicationDataSections = array(new DataApplicationDataSection($application_data));
	}
	else if ($application_data instanceof DataApplicationDataSection || $application_data instanceof AmqpValueApplicationDataSection || $application_data instanceof AmqpSequenceApplicationDataSection)
	{
		$applicationDataSections = array($application_data);
	}
	else if ($application_data instanceof AmqpValue)
	{
		$applicationDataSections = array(new AmqpValueApplicationDataSection($application_data));
	}
	else if (is_array($application_data))
	{
		$length = count($application_data);
		if ($length == 0)
		{
			trigger_error("application_data can not be an empty array()");
		}
		if (!($application_data[0] instanceof ApplicationDataSection))
		{
			trigger_error("application_data must be of type ApplicationDataSection");
		}
		
		if ($length > 1)
		{
			$firstObjectsClass = get_class($application_data[0]);
			for ($index = 1; $index < $length; $index++)
			{
				if (get_class($application_data[$index]) != $firstObjectsClass)
				{
					trigger_error("application_data must all be of the same instance of ApplicationDataSection");
				}
			}
		}
		$applicationDataSections = $application_data;
	}
	else
	{
		trigger_error("application_data must be either NULL (maps to AmqpNull), int (maps to AmqpLong) bool (maps to AmqpBoolean,) string (a byte array), AmqpValue or an array of ApplicationDataSection");
	}
	
	echo "Sending Message\n";
	echo "header: $header\n";
	echo "delivery-annotations: $delivery_annotations\n";
	echo "message-annotations: $message_annotations\n";
	echo "properties: $properties\n";
	echo "application-properties: $application_properties\n";
	foreach($applicationDataSections as $applicationDataSection)
	{
		echo "application-data: $applicationDataSection\n";
	}
	echo "footer: \n\n";
}

?>