<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');

/**
* Represents an AMQP Primitive Type ubyte
*
* @category Networking
* @package libamqp
* @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class ubyte implements Value
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
		if ($value < 0 || $value > 255)
		{
			throw new InvalidArgumentException("$value is not an ubyte (0 to 255)");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param int $value
	 * @return ubyte
	 */
	public static function instance_from_php_value($value)
	{
		return new ubyte($value);
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
