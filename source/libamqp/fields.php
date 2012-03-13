<?php

namespace libamqp;

use \InvalidArgumentException;


require_once('map.php');

/**
 * Represents an AMQP Error's or an AMQP Modified's fields
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class fields extends map
{
	/**
	 * @param array $application_properties An associative array of string keys pointing to simple Value instances
	 */
	public function __construct(array $application_properties)
	{
		parent::__construct();
		foreach ($application_properties as $key => $value)
		{
			if (!is_string($key))
			{
				throw new InvalidArgumentException('Only symbol keys are permitted');
			}
			if (!($value instanceof Value))
			{
				throw new InvalidArgumentException('Only Value object values are permitted');
			}
			parent::__set(new symbol($key), $value);
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
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only symbol keys are permitted');
		}
		return parent::__get($amqpKey);
	}

	/**
	 * @param string|libamqp\string $name
	 * @param Value $value simple value object
	 * @return NULL null
	 */
	public function __set($name, Value $value)
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
			throw new InvalidArgumentException('Only symbol keys are permitted');
		}
		return parent::__set($amqpKey, $value);
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
			$amqpKey = new symbol($name);
		}
		else if ($name instanceof string)
		{
			$amqpKey = $name;
		}
		else
		{
			throw new InvalidArgumentException('Only symbol keys are permitted');
		}
		return parent::__isset($amqpKey);
	}

	/**
	 * @param string|\string $name
	 * @throws \InvalidArgumentException
	 */
	public function __unset($name)
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
			throw new InvalidArgumentException('Only symbol keys are permitted');
		}
		parent::__unset($amqpKey);
	}

}

?>
