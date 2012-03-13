<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
 * Represents an AMQP Primitive Type int
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class int implements Value
{
	public $value;

	/**
	 * @param int $value
	 */
	public function __construct($value)
	{
		if (!is_int($value))
		{
			throw new InvalidArgumentException("$value is not an int");
		}
		if ($value < -2147483648 || $value > 2147483647)
		{
			throw new InvalidArgumentException("$value is not an int (-2147483648 to 2147483647)");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param int $value
	 * @return int
	 */
	public static function instance_from_php_value($value)
	{
		return new int($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}
}

?>
