<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('KnownCompositeType.php');
require_once('section.php');
require_once('map.php');
require_once('Value.php');
require_once('map.php');
require_once('_array.php');
require_once('_list.php');

/**
 * Represents an AMQP Message Format application-properties section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class application_properties extends KnownCompositeType implements section
{
	protected static $descriptor_name;
	protected static $descriptor_code;

	/**
	 * @param array $application_properties An associative array of string keys pointing to simple Value instances
	 */
	public function __construct(array $application_properties)
	{
		parent::__construct();
		$this->value = new map();

		foreach ($application_properties as $key => $value)
		{
			if (!is_string($key))
			{
				throw new InvalidArgumentException('Only string keys are permitted');
			}
			if (!($value instanceof Value))
			{
				throw new InvalidArgumentException('Only Value object values are permitted');
			}
			if ($value instanceof map || $value instanceof _array || $value instanceof _list)
			{
				throw new InvalidArgumentException('Only simple Value object values are permitted (ie not map, list or _array)');
			}
			$amqpKey = new string($key);
			$this->value->set($amqpKey, $value);
		}
	}

	/**
	 * @param string $name
	 * @return Value value or NULL (returns libamqp\null if actually null)
	 **/
	public function __get($name)
	{
		if (is_string($name))
		{
			$amqpKey = new string($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only string keys are permitted');
		}
		return $this->value->get($amqpKey);
	}

	/**
	 * @param string|libamqp\string $name
	 * @param Value $value simple value object
	 * @return NULL null
	 */
	public function __set($name, $value)
	{
		if (is_string($name))
		{
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only string keys are permitted');
		}
		if (!($value instanceof Value))
		{
			throw new InvalidArgumentException('Only simple Value object values are permitted (ie not AmqpMap, _list or _array)');
		}
		if ($value instanceof map || $value instanceof _array || $value instanceof _list)
		{
			throw new InvalidArgumentException('Only simple Value object values are permitted (ie not AmqpMap, _list or _array)');
		}
		return $this->value->set($amqpKey, $value);
	}

	/**
	 * @param string|\string $name
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	public function __isset($name)
	{
		if (is_string($name))
		{
			$amqpKey = new string($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only string keys are permitted');
		}
		return $this->value->is_set($amqpKey);
	}

	/**
	 * @param string|\string $name
	 * @throws \InvalidArgumentException
	 */
	public function __unset($name)
	{
		if (is_string($name))
		{
			$amqpKey = new string($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only string keys are permitted');
		}
		$this->value->__unset($amqpKey);
	}

}


require_once('symbol.php');
require_once('ulong.php');

application_properties::init(new symbol("amqp:application-properties:map"), ulong::instance_from_domain(0x00000000, 0x00000074));

?>
