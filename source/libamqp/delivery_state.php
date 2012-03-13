<?php

namespace libamqp;

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
interface delivery_state
{
	/**
	 * @static
	 * @return bool
	 */
	public static function isTerminal();
}

?>
