<?php

	$to = 'admin@thisdomain.com';
	$subject = 'Confirming your registration at thisdomain.com';
	
	$body = 'New The message.';
	$from = "From: Sender Name <sender@thisdomain.com>";
	
	mail($to, $subject, $body, $from);
	
	phpinfo();

?>