<?php

require_once('libamqp/libamqp.php');

use \libamqp\ushort, \libamqp\boolean, \libamqp\string, \libamqp\binary, \libamqp\timestamp, \libamqp\ubyte, \libamqp\uint, \libamqp\byte, \libamqp\char;
use \libamqp\header, \libamqp\delivery_annotations, \libamqp\message_annotations, \libamqp\properties, \libamqp\application_properties;
use \libamqp\data, \libamqp\amqp_value, \libamqp\amqp_sequence;

/*
	1 Creates a connection shared globally if none exists
	2 Creates a session shared globally if none exists
	3 Creates a new link suitable for a synchronous send
		- This draft API does not address exactly-once messaging, etc, yet
		- This draft API does not address any error handling, eg link-redirect
		- Link recovery is not supported (and partial message recovery would be exceedingly hard to do)
	4 Sends a data(message)
*/
libamqp\send("message");

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-value(null)
*/
libamqp\send(NULL);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-value(boolean(TRUE))
*/
libamqp\send(TRUE);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-value(long(56789))
*/
libamqp\send(56789);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-value(double(14.56))
*/
libamqp\send(14.56);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-value(ushort(456))
*/
libamqp\send(new ushort(456));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an arbitary amqp-value, in this case, amqp-value(ushort(456))
*/
libamqp\send(new amqp_value(new ushort(456)));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends a data(message)
*/
$binary_data = "message";
libamqp\send(new data($binary_data));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-sequence(boolean(TRUE), null(), string("hello"))
*/
$amqp_sequence = new amqp_sequence();
$amqp_sequence[0] = boolean::TRUE();
$amqp_sequence[2] = new string("hello");
libamqp\send($amqp_sequence);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends multiple data sections
*/
$section0 = new data("hello");
$section1 = new data("world");
$amqp_data_sections = array
(
	$section0,
	$section1
);
libamqp\send($amqp_data_sections);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends multiple amqp-sequence sections
*/
$section0 = new amqp_sequence();
$section0[0] = boolean::TRUE();
$section0[2] = new string("hello");
$section1 = new amqp_sequence();
$section1[0] = boolean::FALSE();
$section1[2] = new string("world");
$amqp_data_sections = array
(
	$section0,
	$section1
);
libamqp\send($amqp_data_sections);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends header(durable=true, priority=6) and binary message
*/
libamqp\send("messsage", new header(true, 6));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends header(durable=true, priority=6), delivery-annotations, message-annotations, properties, application-properties and binary message
*/
libamqp\send
(
	"messsage",
	new header
	(
		FALSE,
		3,
		NULL,
		TRUE,
		2
	),
	new delivery_annotations(array
	(
		"x-opt-delivery-something" => boolean::TRUE(),
		"x-opt-delivery-whatever"  => char::instance_from_php_value(56789)
	)),
	new message_annotations(array
	(
		"x-opt-message-somesuch" => byte::instance_from_php_value(-1),
		"x-opt-message-somesuch" => ubyte::instance_from_php_value(15)
	)),
	new properties
	(
		"message-id-56",
		"somebinarydataforuserid",
		"to",
		"subject",
		"reply-to",
		binary::instance_from_php_value("correlation-id"),
		"text/plain;charset=utf-8",
		NULL,
		123456,
		new timestamp(123456),
		"group-id",
		90,
		string::instance_from_php_value("reply-to-group-id")
	),
	new application_properties(array
	(
		"my-key-1" => boolean::TRUE(),
		"my-key-2" => boolean::FALSE()
	))
);
?>
