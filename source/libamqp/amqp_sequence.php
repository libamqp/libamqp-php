<?php

namespace libamqp;

use \ArrayAccess;

require_once('CompositeType.php');
require_once('application_data.php');
require_once('_list.php');

/**
 * Represents an AMQP Message Format amqp-sequence application-data section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class amqp_sequence extends CompositeType implements application_data, ArrayAccess
{
	protected static $descriptor_name;
	protected static $descriptor_code;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->value = new _list();
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Whether a offset exists
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean Returns true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return $this->value->offsetExists($offset);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to retrieve
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		return $this->value->offsetGet($offset);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to set
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->value->offsetSet($offset, $value);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Offset to unset
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		$this->value->offsetUnset($offset);
	}

}

require_once('symbol.php');
require_once('ulong.php');

amqp_sequence::init(new symbol("amqp:amqp-sequence:list"), ulong::instance_from_domain(0x00000000, 0x00000076));

?>
