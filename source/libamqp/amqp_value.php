<?php

namespace libamqp;

require_once('CompositeType.php');
require_once('application_data.php');
require_once('Value.php');

/**
 * Represents an AMQP Message Format amqp-value application-data section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class amqp_value extends CompositeType implements application_data
{
	protected static $descriptor_name;
	protected static $descriptor_code;

	/**
	 * @param Value $value
	 */
	public function __construct(Value &$value)
	{
		parent::__construct();
		$this->value = $value;
	}
}

require_once('symbol.php');
require_once('ulong.php');

amqp_value::init(new symbol("amqp:amqp-value:*"), ulong::instance_from_domain(0x00000000, 0x00000077));

?>
