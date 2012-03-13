<?php

namespace libamqp;

use \BadMethodCallException;

require_once('outcome.php');

/**
 * modified
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class modified extends outcome
{
	protected static $descriptor_name;
	protected static $descriptor_code;
	protected static $listMappings = array
	(
		"delivery_failed"     => array(0, NULL, '\libamqp\boolean', '\libamqp\boolean'),
		"undeliverable_here"  => array(1, NULL, '\libamqp\boolean', '\libamqp\boolean'),
		"message_annotations" => array(2, NULL, '\libamqp\fields',  '\libamqp\fields')
	);

	/**
	 * @param bool|boolean|NULL $delivery_failed
	 * @param bool|boolean|NULL $undeliverable_here
	 * @param fields|NULL $message_annotations
	 */
	public function __construct($delivery_failed = NULL, $undeliverable_here = NULL, fields &$message_annotations = NULL)
	{
		parent::__construct();

		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch ($numberOfArgumentsSpecified)
		{
			case 3:
				$this->message_annotations = $message_annotations;

			case 2:
				$this->undeliverable_here = $undeliverable_here;

			case 1:
				$this->delivery_failed = $delivery_failed;

			case 0:
				break;

			default:
				throw new BadMethodCallException("too many function arguments");
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return parent::toString(__CLASS__);
	}
}

require_once('symbol.php');
require_once('ulong.php');

modified::init(new symbol("amqp:modified:list"), ulong::instance_from_domain(0x00000000, 0x00000027));

?>
