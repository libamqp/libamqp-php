<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');
require_once('message_id.php');

/**
 * Represents an AMQP Primitive Type string
 *
 * Maps to a PHP string assumed to be UTF-8 encoded (validation not performed)
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class string implements Value, message_id
{
	public $value;

	/**
	 * @param string $value
	 */
	public function __construct($value)
	{
		if (!is_string($value))
		{
			throw new InvalidArgumentException("$value must be a UTF-8 string without a BOM");
		}
		// Writing a comprehensive UTF-8 checking function is not trivial and very slow in PHP
		// It needs to handle non-transmission characters
		$this->value = $value;
	}

	/**
	 * @static
	 * @param string $value
	 * @return string
	 */
	public static function instance_from_php_value($value)
	{
		return new string($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%s)", __CLASS__, $this->value);
	}
}

?>
