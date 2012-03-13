<?php

namespace libamqp;

require_once('KnownCompositeType.php');
require_once('application_data.php');
require_once('binary.php');

/**
 * Represents an AMQP Message Format data application-data section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class data extends KnownCompositeType implements application_data
{
	protected static $descriptor_name;
	protected static $descriptor_code;

	/**
	 * @param string $value
	 */
	public function __construct($value)
	{
		parent::__construct();
		$this->value = new binary($value);
	}
}

require_once('symbol.php');
require_once('ulong.php');

data::init(new symbol("amqp:data:binary"), ulong::instance_from_domain(0x00000000, 0x00000075));

?>
