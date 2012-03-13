<?php

namespace libamqp;

use \InvalidArgumentException;

require_once('CompositeType.php');
require_once('annotations.php');
require_once('map.php');
require_once('Value.php');
require_once('map.php');
require_once('_array.php');
require_once('_list.php');

/**
 * Represents an AMQP Message Format message-annotations section
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class message_annotations extends annotations
{
	protected static $descriptor_name;
	protected static $descriptor_code;

	/**
	 * @param array $annotations An associative array of string keys pointing to simple Value instances
	 */
	public function __construct(array $annotations)
	{
		parent::__construct($annotations);
	}
}


require_once('symbol.php');
require_once('ulong.php');

message_annotations::init(new symbol("amqp:message-annotations:map"), ulong::instance_from_domain(0x00000000, 0x00000072));

?>
