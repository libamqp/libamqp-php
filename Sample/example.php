<?php

require_once('libamqp.php');

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
libamqp\send(new libamqp\AmqpUshort(456));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an arbitary amqp-value, in this case, amqp-value(ushort(456))
*/
libamqp\send(new libamqp\AmqpValueApplicationDataSection(new libamqp\AmqpUshort(456)));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends a data(message)
*/
$binary_data = "message";
libamqp\send(new libamqp\DataApplicationDataSection($binary_data));

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends an amqp-sequence(boolean(TRUE), null(), string("hello"))
*/
$amqp_sequence = new libamqp\AmqpSequenceApplicationDataSection();
$amqp_sequence->set(0, libamqp\AmqpBoolean::TRUE());
$amqp_sequence->set(2, new libamqp\AmqpString("hello"));
libamqp\send($amqp_sequence);

/*
	1 (Restablishes connection, session or link if necessary)
	2 Sends multiple data sections
*/
$section0 = new libamqp\DataApplicationDataSection("hello");
$section1 = new libamqp\DataApplicationDataSection("world");
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
$section0 = new libamqp\AmqpSequenceApplicationDataSection();
$section0->set(0, libamqp\AmqpBoolean::TRUE());
$section0->set(2, new libamqp\AmqpString("hello"));
$section1 = new libamqp\AmqpSequenceApplicationDataSection();
$section1->set(0, libamqp\AmqpBoolean::FALSE());
$section1->set(2, new libamqp\AmqpString("world"));
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
libamqp\send("messsage", new libamqp\Header(true, 6));

?>
