<?php 	include("include/header.php"); ?>

<?php
if ($_POST['submit'] == "Add Students to Class Roster") {

	if (!($stmt = $mysqli->prepare("INSERT INTO registrations (first_name, last_name, email, class_id, role,
	          waiver, payment_type, payment_amount, notes, confirmed, pp_txn_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
		die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
	}
	
	if (!$stmt->bind_param("sssisssdsss", $first_name, $last_name, $email, $class_id, $role, $waiver,
		$payment_type, $payment_amount, $note, $confirmed, $pp_txn_id)) {
		die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
	}
	
	$class_id = $_POST['class_id'];
	$waiver = 'accept';
	$confirmed = 'true';
	
	$roles = array("Leader", "Follower", "Solo");
	foreach ($roles as $role) {
	
		for($i = 0; $i <= 10; $i++) {
			$first_name = $_POST[$role . '_fn'][$i];
			$last_name = $_POST[$role . '_ln'][$i];
			$email = $_POST[$role . '_email'][$i];
			$payment_type = $_POST[$role . '_method'][$i];
			$payment_amount = $_POST[$role . '_amount'][$i];
			$note = $_POST[$role . '_note'][$i];
			$pp_txn_id = $_POST[$role . '_ref'][$i];
			$mailchimp_add = $_POST[$role . '_mailchimp_add'][$i];
			if (!strcmp($first_name, "") && !strcmp($last_name, "") && !strcmp($email, "")) { break; }
				
			echo "inserting $first_name, $last_name, $email, $class_id, $role, 'accept', $method, payment_amount, $note, 'true', $pp_txn_id<br/>\n";
		

			if (!$stmt->execute()) {
				//die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
			}
			
			if ($mailchimp_add == 1) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					require_once '/home/thisdomain_user/thisdomain.com/inc/MCAPI.class.php';
					require_once '/home/thisdomain_user/thisdomain.com/inc/mailchimp_config.inc.php'; //contains apikey
	 
					$api = new MCAPI($apikey);
	 
					$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name);
	 
					// By default this sends a confirmation email - you will not see NEW members
					// until the link contained in it is clicked!
					$retval = $api->listSubscribe( $listId, $email, $merge_vars, 'html', false );
	 
					if ($api->errorCode){
						echo "Mailchimp: Unable to load listSubscribe()!";
						echo " Code=".$api->errorCode ."<br/>\n";
						echo " Msg=".$api->errorMessage."<br/>\n";
					} else {
						echo "Mailchimp: sucessfully subscribed ($first_name, $last_name, $email)<br/>\n";
					}					
				} else {
					echo "Mailchimp: invalid email address ($email); skipping subscription attempt.<br/>\n";
				}		
			}
		}
	}
	echo "<a href='roster.php?show_class_id=$class_id'>View Updated Roster</a><br/>\n";
}
?>



<div style="clear:both">
<?php

	function display_form_fields_for_role ($role = 'Solo') {
?>

<strong><?= $role ?>s<br/></strong>
<table>
<tbody>
<tr><td>First Name</td><td>Last Name</td><td>Email</td><td>Amount</td><td>Method</td><td>Ref</td><td>Note</td><td>MC Subscribe</tr>
<?php
	for($i=0; $i<=10; $i++) {
?>
	<tr><td><input name="<?= $role ?>_fn[<?= $i ?>]" size="20" /></td>
		<td><input name="<?= $role ?>_ln[<?= $i ?>]" size="20" /></td>
		<td><input name="<?= $role ?>_email[<?= $i ?>]" size="40" /></td>
		<td><input name="<?= $role ?>_amount[<?= $i ?>]" size="6"/></td>
		<td><select name="<?= $role ?>_method[<?= $i ?>]">
			<option name="<?= $role ?>_method[<?= $i ?>]" value="paypal">paypal</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="cash">cash</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="check">check</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="trade">trade</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="square">square</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="free">free</option>
			<option name="<?= $role ?>_method[<?= $i ?>]" value="other">other</option>

			</select>
		</td>
		<td><input name="<?= $role ?>_ref[<?= $i ?>]" size="20"/></td>
		<td><input name="<?= $role ?>_note[<?= $i ?>]" size="60"/></td>
		<td><input type="checkbox" name="<?= $role ?>_mailchimp_add[<?= $i ?>]" value="1" checked="checked" /></td>
	</tr>
		
<?php
}	
?>


</tbody>
</table>

<?php
	
	}



	//load info about this class
	if (isset($_GET['class_id'])) {
		$class_id =& $_GET['class_id'];
		$result = $mysqli->query("SELECT name, partnered, start_date, day_of_week, start_time FROM classes"
			. " WHERE id = '$class_id'");
		
		if ($result->num_rows == 1) {
			
			$class = $result->fetch_assoc();
			$result->free();
?>
			<h1><?= $class['name'] ?> (<?= $class_id ?>) starts <?= $class['day_of_week'] ?>, <?= $class['start_date'] ?>, <?= $class['start_time'] ?></h1>
			
			<form action="/regman/add_students.php" method="post">

<?php
			if ($class['partnered'] == 1) {

				$role = "Leader";
				display_form_fields_for_role($role);
				
				$role = "Follower";
				display_form_fields_for_role($role);
			
			} elseif ($class['partnered'] == 0 ) {
				$role = "Solo";
				display_form_fields_for_role($role);
			}
?>
				<input type="hidden" name="class_id" value="<?= $class_id ?>"/>
				<input type="submit" name="submit" value="Add Students to Class Roster" />

			</form>
<?php
			
		} elseif ($result->num_rows == 0) {
			$error_str .= "No result rows found for class_id.<br/>";
		} elseif ($result->num_rows > 1) {
			$error_str .= "Multiple result rows found for class_id.<br/>";
		} elseif ($result->num_rows < 0) {
			$error_str .= "Negative number of result rows returned for class_id.<br/>";
		}
	}

	if ($error_str) {
		echo "$error_str";
	}	
?>

<?php include("include/footer.php"); ?>