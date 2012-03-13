<?php

namespace libamqp;

use \BadMethodCallException, \InvalidArgumentException, \OutOfBoundsException;

require_once('Value.php');
require_once('symbol.php');
require_once('ulong.php');

/**
 * Represents a described list with a known mapping
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
abstract class KnownCompositeType implements Value
{
	protected $value;

	/**
	 * @param symbol $descriptor_name
	 * @param ulong $descriptor_code
	 */
	public static function init(symbol &$descriptor_name, ulong &$descriptor_code)
	{
		static::$descriptor_name = $descriptor_name;
		static::$descriptor_code = $descriptor_code;
	}

	/**
	 *
	 */
	protected function __construct()
	{
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s(%s)", static::$descriptor_name->value, $this->value);
	}
}

?>
