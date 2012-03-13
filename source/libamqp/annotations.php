<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('KnownCompositeType.php');
require_once('map.php');
require_once('section.php');
require_once('Value.php');
require_once('ulong.php');
require_once('symbol.php');

/**
 * Represents an AMQP Message Format delivery-annotations, message-annotations or footer section
 *
 * Problem: PHP 'auto-magically' converts strings that 'look like' integers to integers...
 * Problem: PHP does not always support 64-bit integers as keys, even in arrays!
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
abstract class annotations extends KnownCompositeType implements section
{
	/**
	 * @param array $annotations
	 */
	public function __construct(array $annotations = array())
	{
		parent::__construct();
		$this->value = new map();

		foreach ($annotations as $key => $value)
		{
			if (is_int($key))
			{
				$amqpKey = new ulong($key);
			}
			else if (is_string($key))
			{
				$amqpKey = new symbol($key);
			}
			else
			{
				throw new InvalidArgumentException('Only ulong (int) or symbolic (string) annotation keys are permitted');
			}
			if (!($value instanceof Value))
			{
				throw new InvalidArgumentException('Only Value object annotation values are permitted');
			}
			$this->value->set($amqpKey, $value);
		}
	}

	/**
	 * @param int|string|ulong|symbol $name
	 * @return Value value or NULL (returns libamqp\null if actually null)
	 **/
	public function get($name)
	{
		if (is_int($name))
		{
			$amqpKey = new ulong($name);
		}
		else if (is_string($name))
		{
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof ulong || $name instanceof symbol)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only ulong (int) or symbolic (string) annotation keys are permitted');
		}
		return $this->value->get($amqpKey);
	}

	/**
	 * @param int|string|ulong|symbol $name
	 * @param Value $value
	 */
	public function set($name, Value $value)
	{
		if (is_int($name))
		{
			$amqpKey = new ulong($name);
		}
		else if (is_string($name))
		{
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof ulong || $name instanceof symbol)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only ulong (int) or symbolic (string) annotation keys are permitted');
		}
		$this->value->set($amqpKey, $value);
	}

	/**
	 * @param int|\string|ulong|symbol $name
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function is_set($name)
	{
		if (is_int($name))
		{
			$amqpKey = new ulong($name);
		}
		else if (is_string($name))
		{
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof ulong || $name instanceof symbol)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only ulong (int) or symbolic (string) annotation keys are permitted');
		}
		return $this->value->is_set($amqpKey);
	}
}

?>
