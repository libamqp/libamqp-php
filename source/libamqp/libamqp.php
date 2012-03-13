<?php

/**
 * libamqp PHP wrapper
 *
 * Provides PHP classes to use with libamqp to access AMQP 1-0 brokers
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 2 of the Apache License
 * that is availabe through the world-wide-web at the following URI:
 * http://www.apache.org/licenses/LICENSE-2.0.html
 * If you did not receive a copy of the Apache License and are unable to
 * obtain it through the web, please send an e-mail to license
 * @stormmq.com so we can mail you a copy immediately.
 *
 * @category Networking
 * @package libamqp
 * @author Raphael Cohn <raphael.cohn@stormmq.com>
 * @author Eamon Walshe <eamon.walshe@stormmq.com>
 * @copyright 2012 Raphael Cohn and Eamon Walshe
 * @license http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @version Release: @package_version@
 * @link http:// <INSERT PECL HERE>
 */

namespace libamqp;

use \InvalidArgumentException, \BadFunctionCallException;

require_once('Value.php');
require_once('null.php');
require_once('boolean.php');
require_once('ubyte.php');
require_once('ushort.php');
require_once('uint.php');
require_once('ulong.php');
require_once('byte.php');
require_once('short.php');
require_once('int.php');
require_once('long.php');
require_once('float.php');
require_once('double.php');
require_once('decimal32.php');
require_once('decimal64.php');
require_once('decimal128.php');
require_once('char.php');
require_once('timestamp.php');
require_once('uuid.php');
require_once('binary.php');
require_once('string.php');
require_once('symbol.php');
require_once('_list.php');
require_once('map.php');
require_once('_array.php');

require_once('section.php');
require_once('header.php');
require_once('annotations.php');
require_once('delivery_annotations.php');
require_once('message_annotations.php');
require_once('application_properties.php');
require_once('application_data.php');
require_once('data.php');
require_once('amqp_sequence.php');
require_once('amqp_value.php');
require_once('properties.php');
require_once('footer.php');

require_once('error.php');
require_once('fields.php');

require_once('delivery_state.php');
require_once('outcome.php');
require_once('received.php');
require_once('accepted.php');
require_once('rejected.php');
require_once('released.php');
require_once('modified.php');
require_once('modified.php');

require_once('sending_link.php');

?>
