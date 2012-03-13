<?php

namespace libamqp;

use \BadMethodCallException;

require_once('delivery_state.php');
require_once('KnownListCompositeType.php');
require_once('uint.php');
require_once('ulong.php');

/**
 * received
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
class received extends KnownListCompositeType implements delivery_state
{
	protected static $descriptor_name;
	protected static $descriptor_code;
	protected static $listMappings = array
	(
		"section_number" => array(0, NULL, '\libamqp\uint',  '\libamqp\uint'),
		"section_offset" => array(1, NULL, '\libamqp\ulong', '\libamqp\ulong')
	);

	/**
	 * @param uint|NULL $section_number
	 * @param ulong|NULL $section_offset
	 */
	public function __construct(uint &$section_number = NULL, ulong &$section_offset = NULL)
	{
		parent::__construct();

		// Yes, this uses fall-through, but does so to make sure trailing null suppression is possible
		$numberOfArgumentsSpecified = func_num_args();
		switch ($numberOfArgumentsSpecified)
		{
			case 2:
				$this->section_offset = $section_offset;

			case 1:
				$this->section_number = $section_number;

			case 0:
				break;

			default:
				throw new BadMethodCallException("too many function arguments");
		}
	}

	/**
	 * @static
	 * @return bool
	 */
	public static function isTerminal()
	{
		return FALSE;
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

received::init(new symbol("amqp:received:list"), ulong::instance_from_domain(0x00000000, 0x00000023));

?>
