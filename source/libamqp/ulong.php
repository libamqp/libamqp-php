<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('Value.php');
require_once('message_id.php');

/**
 * Represents an AMQP Primitive Type long
 *
 * Models it as a PHP 64-bit signed integer; if you need to do math on the value, you can but it's confusing.
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 * @todo: 32-bit PHP support using either GMP, binary strings or PHP_MAX_INT
 */
class ulong implements Value, message_id
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
		if ($value < -9223372036854775808 || $value > 9223372036854775807)
		{
			throw new InvalidArgumentException("$value is not a ulong (-9223372036854775808 to 9223372036854775807)");
		}
		$this->value = $value;
	}

	/**
	 * @static
	 * @param int $value
	 * @return ulong
	 */
	public static function instance_from_php_value($value)
	{
		return new ulong($value);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%u)", __CLASS__, $this->value);
	}

	/**
	 * @static
	 * @param $domain
	 * @param $id
	 * @return \libamqp\ulong
	 * @throws \InvalidArgumentException
	 */
	public static function instance_from_domain($domain, $id)
	{
		if (!is_int($domain))
		{
			throw new InvalidArgumentException("domain $domain must be an integer");
		}
		if (!is_int($id))
		{
			throw new InvalidArgumentException("id $id must be an integer");
		}
		return new ulong($domain << 32 | $id);
	}
}

?>
