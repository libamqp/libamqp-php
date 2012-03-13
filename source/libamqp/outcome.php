<?php

namespace libamqp;

require_once('delivery_state.php');

/**
 * Represents an AMQP Message Format delivery-state
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
abstract class outcome extends KnownListCompositeType implements delivery_state
{
	/**
	 * @static
	 * @return bool
	 */
	public static function isTerminal()
	{
		return TRUE;
	}
}

?>
