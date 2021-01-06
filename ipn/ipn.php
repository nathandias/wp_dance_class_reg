<?php

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 1);

// Set to 0 once you're ready to go live
define("USE_SANDBOX", 0);

define("LOG_FILE", "./ipn.log");

$db_host = "mysql.thisdomain.com";
$db_user = "database_username";
$db_pass = "database_password";

if (USE_SANDBOX == true) {
   	$database = "thisdomain_classregistration_test";
	$primary_merchant_email = "primary_test_email@thisdomain.com"
	$secondary_merchant_email = "secondary_test_email@thisdomain.com";

} else {
	$database = "thisdomain_classregistration";
	$primary_merchant_email = "register@primarydomain.com";
	$secondary_merchant_email = "register@secondarydomain.com";
}

// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

if (DEBUG == true) {
	error_log(PHP_EOL . "<<< START NEW IPN PROCESSING >>>" . PHP_EOL, 3, LOG_FILE);
}


$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:','Connection: Close'));

// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.

//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);

$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {	
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

			
			//error_log("<<< A >>>" . PHP_EOL, 3, LOG_FILE);
			// Split response headers and payload
			list($headers, $res) = explode("\r\n\r\n", $res, 2);
			//error_log("<<< B >>>" . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
		//error_log("<<< C >>>" . PHP_EOL, 3, LOG_FILE);
}

// Inspect IPN validation result and act accordingly
//error_log("<<< D >>>" . PHP_EOL, 3, LOG_FILE);
if (strcmp ($res, "VERIFIED") == 0) {

	//error_log("<<< E >>>" .  PHP_EOL, 3, LOG_FILE);
	// check whether the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment and mark item as paid.
	
	// assign posted variables to local variables
	$payment_status = $_POST['payment_status'];
	$total_payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$business = $_POST['business'];
	$payer_email = $_POST['payer_email'];
	$payer_last_name  = $_POST['last_name'];
	$payer_first_name = $_POST['first_name'];
	$num_cart_items = $_POST['num_cart_items'];

	// continue assigning POSTed variables to locals
	for ($i = 1; $i <= $num_cart_items; $i++) {
		$item_name[$i] = $_POST['item_name' . $i];
		$item_number[$i] = $_POST['item_number' . $i];
		$payment_amount[$i] = $_POST['mc_gross_' . $i];
		$option_name[$i] = $_POST['option_name1_' . $i];
		$option_selection[$i] = $_POST['option_selection1_' . $i];
	}
	
	//error_log("<<< F >>>" .  PHP_EOL, 3, LOG_FILE);

	
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
	
	
	//error_log("<<< G >>>" .  PHP_EOL, 3, LOG_FILE);
    
	
	$db = new mysqli($db_host, $db_user, $db_pass, $database);
	if ($db->connect_errno > 0) {
		$debug_msg = "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
		error_log($debug_msg . PHP_EOL, 3, LOG_FILE);
		die($debug_msg);
	}
	
	//error_log("<<< H >>>" .  PHP_EOL, 3, LOG_FILE);

	
	function is_previously_used_txn_id ($txn_id) {
		global $db;
		if (!$result = $db->query("SELECT id FROM registrations WHERE pp_txn_id = '$txn_id'")) {
			if (DEBUG == true) { error_log(($msg = "Error running query: $sql") . PHP_EOL, 3, LOG_FILE); }
			die($msg);
		}
		return $result->num_rows;
	}	
	
	//error_log("<<< I >>>" .  PHP_EOL, 3, LOG_FILE);

	
	// Check that IPN info matches expected values
	if ($payment_status != 'Completed') {
		error_log("Payment status was not 'Completed': $payment_status" . PHP_EOL, 3, LOG_FILE);
	} elseif (is_previously_used_txn_id($txn_id)) {
		error_log("Transaction ID has already been processed: $txn_id" . PHP_EOL, 3, LOG_FILE);
	} elseif ($receiver_email != $primary_merchant_email) {
		error_log("Receiver Email was not $primary_merchant_email: $receiver_email" . PHP_EOL, 3, LOG_FILE);
//			} elseif ($business != $secondary_merchant_email) {
//				$body .= "Business was not $secondary_merchant_email: $business\n";
	} elseif ($payment_currency != 'USD') {
		error_log("Payment currency was not USD: $payment_currency" . PHP_EOL, 3, LOG_FILE);
	} else {
		if (DEBUG == true) { error_log("<<< GOT THROUGH MOST CHECKS >>>" . PHP_EOL, 3, LOG_FILE); }
		$correct_amounts = true;
		$email_list_subscribe = false;
			
		for ($i = 1; $i <= $num_cart_items; $i++) {
			$id = $option_selection[$i];
			$sql = "SELECT registrations.id, first_name, last_name, email, payment_amount, email_list_subscribe, class_name, role, classes.confirm_registration_email_message as 'email_message' FROM registrations, classes WHERE registrations.id = '$id' AND registrations.class_id = classes.id";
			if (!$result = $db->query($sql)) {
				if (DEBUG == true) { error_log(($msg = "Error running query: $sql"). PHP_EOL, 3, LOG_FILE); }
				die ($msg);
			}
			$registration = $result->fetch_assoc();

			if ($registration['payment_amount'] != $payment_amount[$i]) {
				$correct_amounts = false;
				error_log("Payment amount $payment_amount[$i] did not match value from registrations table: $registration[payment_amount]." . PHP_EOL, 3, LOG_FILE);
			}
			if ($registration['email_list_subscribe'] == 1) {
				// if at least one of the registrations corresponding to this payment wants to subscribe
				// then do it. Assume that the name, email
				$email_list_subscribe = true;
				$first_name = $registration['first_name'];
				$last_name = $registration['last_name'];
				$email = $registration['email'];
			}
			
			$confirmation_email_item_list .= "\tItem: " . $registration['class_name'] . ", Amount: " . $registration['payment_amount'] . ", RegId: " . $registration['id'] . "\n";

			
			if (!empty($registration['email_message'])) {
				$special_notes .= "Special Notes for " . $registration['class_name'] . "\n";
				$special_notes .= "\n";
				$special_notes .= $registration['email_message'];
				$special_notes .= "\n\n";
			}	
		}
				
		if ($correct_amounts) {
		
			// update the database: mark all items purchased as confirmed
		
            for ($i = 1; $i <= $num_cart_items; $i++) {
				$p = $payment_amount[$i];
				$o = $option_selection[$i];
				$sql = "UPDATE registrations SET  payment_type = 'paypal', payment_amount = '$p', "
					   . "pp_txn_id = '$txn_id', confirmed = 'true', purchase_date = NOW() WHERE id = '$o'";
				if (!$result = $db->query($sql)) {
					if (DEBUG == true) { error_log(($msg = "Error running query: $sql"). PHP_EOL, 3, LOG_FILE); }
					die ($msg);
				}
			}
					
			if ($email_list_subscribe) {
				require_once 'inc/MCAPI.class.php';
				require_once 'inc/mailchimp_config.inc.php'; //contains apikey
 
				$api = new MCAPI($apikey);
 
				$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name);
 
				// By default this sends a confirmation email - you will not see NEW members
				// until the link contained in it is clicked!
				$retval = $api->listSubscribe( $listId, $email, $merge_vars, 'html', false );
 
				if ($api->errorCode){
					$emailtext .= "Unable to load listSubscribe()!\n";
					$emailtext .= "\tCode=".$api->errorCode."\n";
					$emailtext .= "\tMsg=".$api->errorMessage."\n";
				} else {
					$emailtext .= " sucessfully subscribed ($first_name, $last_name, $email)\n";
				}
			}
			
			// send a confirmation email to the customer
			$to = $registration['email'];
			$subject = "We received your registration and payment!";
			$body = "";
			$from = "From: Organization Name <register@thisdomain.com>";
			
			$body .= "Dear $first_name $last_name,\n";
			$body .= "\n";
			$body .= "Thanks for registering! We received your payment for the following items:\n";
			$body .= "\n";
			$body .= $confirmation_email_item_list;
			$body .= "\n";
			$body .= $special_notes;
			$body .= "\n";
			$body .= "You're all set. Email $receiver_email if you have any questions.\n";
			$body .= "\n";
			$body .= "Thanks!\n";
			$body .= "\n";
			$body .= "Organization Name\n";
			
			mail($to, $subject, $body, $from);
			if (DEBUG == true) { error_log("Mailed: $to" . PHP_EOL, 3, LOG_FILE); }
				
		}
	}
	if (DEBUG == true) { error_log("<<< END OF VERIFIED SECTION >>>" . PHP_EOL, 3, LOG_FILE); }
	
} else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}

?>