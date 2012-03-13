<?php

namespace libamqp;

/**
* All AMQP Types implement this interface
*
* All implementing types have a concrete or 'dynamic' property value, eg:-
* echo $instance_of_interface->value;
*
* All implementing types have a static constructor function, instance_from_php_value($value)
*
* @category Networking
* @package libamqp
* @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 */
interface Value
{
}

?>
