<?php 	include("include/header.php"); ?>

<div style="">
<?php
	$numRows = 0;
	if (isset($_GET['show_class_id'])) {
		$search_class_id =& $_GET['show_class_id'];
		
		$result = $mysqli->query("SELECT name, id, partnered, max_students, max_leaders, max_followers, start_date, end_date,
							class_type, day_of_week, start_time, end_time FROM classes WHERE id = '$search_class_id'");
	}
	if (isset($_POST['show_class_type_month']) && $_POST['show_class_type_month'] == 'Show Roster') {
		$class_year = $_POST['class_year'];
		$class_month = $_POST['class_month'];
		$class_name = $_POST['class_name'];
		
		$result = $mysqli->query("SELECT name, id, partnered, max_students, max_leaders, max_followers, start_date, end_date,
		                       class_type, day_of_week, start_time, end_time FROM classes
							   WHERE name LIKE '%$class_name%' AND MONTHNAME(start_date) = '$class_month' AND YEAR(start_date) = '$class_year'");
	}
	
	$numRows = $result->num_rows;
		
		switch($numRows) {
			case (0): // no results returned
?>
			<strong>Sorry, no matching class rosters found.</strong>
<?php
				break;
			
			case (1):   // found exactly 1 roster
				$class = $result->fetch_assoc();
				$class_id = $class['id'];
				$result->free();

				$result = $mysqli->query("SELECT id, first_name, last_name, email, role, payment_type, payment_amount, waiver, email_list_subscribe FROM registrations WHERE confirmed = 'true' AND class_id = '$class_id' ORDER BY first_name ASC, last_name ASC");
				for ($registrations = array(); $tmp = $result->fetch_array();) $registrations[] = $tmp;
				$result->free();
?>

<h1 style="font-size:14px; padding-bottom:0px; margin-bottom:0px;"><?= $class[name] ?> (<?= $class[id] ?>)</h1>

<?php
				if ($class[class_type] == "weekly group" || $class[type] == "children's group") {
?>
<h2 style="font-size:12px; padding-top:0px; margin-top:0px"><?= date("F Y", strtotime($class[start_date])) ?> / <?= $class[day_of_week] ?>s, <?= date("g:ia", strtotime($class[start_time])) ?> - <?= date("g:ia", strtotime($class[end_time])) ?></h2>

<?php
				} elseif ($class[class_type] == "workshop") {
?>

<h2 style="font-size:12px;"><?= date("l, F j, Y", strtotime($class[start_date])) ?>, <?= date("g:ia", strtotime($class[start_time])) ?> - <?= date("g:ia", strtotime($class[end_time])) ?></h2>

<?php
				}


				$student_count['Solo'] = $student_count['Leader'] = $student_count['Follower'] = 0;
?>

<?php

				if ($class[partnered] == '1') {
					$roles = array("Leader", "Follower");
				} else {
					$roles = array("Solo");
				}
	
				$emails['all'] = $emails['Leader'] = $emails['Follower'];
				$okay_to_emails['all'] = $okay_to_emails['Leader'] = $okay_to_emails['Follower'];
				$payments['all'] = $payments['paypal_gross'] = $payments['paypal_net'] = $payments['paypal_fees'] = $payments['cash'] = $payments['check'] = $payments['other'] = 0;
				$num_payments['all'] = $num_payments['paypal'] = $num_payments['cash'] = $num_payments['check'] = $num_payments['other'] = 0;

				foreach ($roles as $role) {
					$number = 0;
					if ($role == 'Leader') { $max_role_string = 'max_leaders'; }
					if ($role == 'Follower') { $max_role_string = 'max_followers'; }
					if ($role == 'Solo') { $max_role_string = 'max_students'; }
?>

<table border="1" width="100%" class="gridtable">
<tbody>
<tr><th width="0">#</th><th width="30%"><?= $role . 's' ?></th><th width="45%">Email</th>

<?php
					if ($DISPLAY_SETTINGS[show_payment_info]) {
?>

<th width="0%">Method</th>

<?php
					}
?>

<th width="5%">1</th><th width="5%">2</th><th width="5%">3</th><th width="5%">4</th><th width="5%">5</th>

<?php
					if ($DISPLAY_SETTINGS[show_payment_info]) {
?>

<th width="0%">Tot</th>

<?php
					}
?>

</tr>

<?php
							
					
					
					
				foreach($registrations as $registration) {			
					if ($registration[role] == $role) {
							$number++;
							$emails[$registration[role]] .= ($emails[$registration[role]] == '') ? $registration[email] : ", $registration[email]";
							$emails['all'] .= ($emails['all'] == '') ? $registration[email] : ", $registration[email]";
						
							if ($registration[email_list_subscribe] == 1) {
								$okay_to_emails[$registration[role]] .= ($okay_to_emails[$registration[role]] == '') ? $registration[email] : ", $registration[email]";
								$okay_to_emails['all'] .= ($okay_to_emails['all'] == '') ? $registration[email] : ", $registration[email]";
							}
							
				
							if ($registration[payment_type] == 'paypal') {
								$payment_gross = $registration[payment_amount];
								$paypal_fee = round(0.029 * $payment_gross + 0.30, 2);
								if ($payment_gross > 0) {
									$payment_net = $payment_gross - $paypal_fee;
								} else {
									$payment_net = $payment_gross;
								}
								$payments['all'] += $payment_net;
								$payments['paypal_gross'] += $payment_gross;
								$payments['paypal_fees'] += $paypal_fee;
								$payments['paypal_net'] += $payment_net;
							} else {
								$payments['all'] += $registration[payment_amount];
								$payments[$registration[payment_type]] += $registration[payment_amount];
							}
							$num_payments['all']++;
							$num_payments[$registration[payment_type]]++;

?>
<tr class="<?= (($number % 2) == 0) ? 'even' : 'odd' ?>"><td><a href="registrations.php?PME_sys_operation=View&PME_sys_rec=<?= $registration[id] ?>"><?= $number ?></a></td><td><?= "$registration[first_name]  $registration[last_name]" ?></td><td><a href="registrations.php?PME_sys_operation=View&PME_sys_rec=<?= $registration[id] ?>"><?= $registration[email] ?></a></td>

<?php
							if ($DISPLAY_SETTINGS[show_payment_info]) {
?>		
<td><?= $registration[payment_type] ?></td>
<?php
							}
?>
		
		<td></td><td></td><td></td><td></td><td></td>

<?php
							if ($DISPLAY_SETTINGS[show_payment_info]) {
?>		
<td><?= $registration[payment_amount] ?></td>
<?php
							}
?>		

</tr>

<?php
						}
					}
					$student_limit = $class[$max_role_string] + 5;
					while($number++ < $student_limit) {
?>
<tr class="<?= (($number % 2) == 0) ? 'even' : 'odd' ?>"><td><?= $number ?></td><td></td><td></td>

<?php 					if ($DISPLAY_SETTINGS[show_payment_info]) { ?>		
		<td></td>
<?php
						}
?>



<td></td><td></td><td></td><td></td><td></td>

<?php 					if ($DISPLAY_SETTINGS[show_payment_info]) { ?>		
		<td></td>
<?php
						}
?>

</tr>

<?php					
					}
?>
		</tbody>
		</table>
		<br/>

<?php
				}
?>
</div>

<?php if (!$printable) { ?>

<div style="text-align:right;">[
<a href="classes.php?PME_sys_operation=View&PME_sys_rec=<?= $class_id ?>">View Class Details</a> |
<a href="classes.php?PME_sys_operation=Change&PME_sys_rec=<?= $class_id ?>">Edit Class Details</a> |
<a href="add_students.php?class_id=<?= $class_id ?>">Add Students</a> |
<a href="export_students.php?class_id=<?= $class_id ?>">Export Students</a>]</div>
<div style="clear:both"></div>



<strong>Additional Info (<a href="#" onclick="showStuff('extras'); return false;">Show</a>/<a href="#" onclick="hideStuff('extras'); return false;">Hide</a>)</strong>
<span id="extras" style="display:none">

<h2>Payment Summary</h2>
<table border="1">
<tbody>
<tr><th>Payment Type</th><th>Amount Received</th></tr>
<tr><th>PayPal</th><td>$ <?= $payments['paypal_gross'] ?> / <?= $payments['paypal_fees'] ?> / <?= $payments['paypal_net'] ?></td></tr>
<tr><th>Cash</th><td>$ <?= $payments['cash'] ?></td></tr>
<tr><th>Check</th><td>$ <?= $payments['check'] ?></td></tr>
<tr><th>Other</th><td>$ <?= $payments['other'] ?></td></tr>
<tr><th>Total</th><td>$ <?= $payments['all'] ?></td></tr>
</tbody>
</table>


<h2>All Emails (Okay to Email)</h2>
<?= $okay_to_emails['all'] ?>


<?php
					if ($class[partnered] == 1) {
?>
<h2>Leader Emails (Okay to Email):</h2>
<?= $okay_to_emails['Leader'] ?>
<h2>Follower Emails (Okay to Email):</h2>
<?= $okay_to_emails['Follower'] ?>
<?php
					}
?>

<h2>All Emails:</h2>
<?= $emails['all'] ?>
	
<?php
					if ($class[partnered] == 1) {
?>
<h2>Leader Emails:</h2>
<?= $emails['Leader'] ?>
<h2>Follower Emails:</h2>
<?= $emails['Follower'] ?>

<?php
					}
?>

<h2>Class Dates:</h2>


Start Date: <?= date("l, M j, Y", strtotime($class['start_date'])) ?><br/>
End Date: <?= date("l, M j, Y", strtotime($class['end_date'])) ?><br/>
Reg Open Date: <?= date("l, M j, Y", strtotime($class['reg_open_date'])) ?><br/>
Reg Close Date: <?= date("l, M j, Y", strtotime($class['reg_close_date'])) ?><br/>
Early Reg Date: <?= date("l, M j, Y", strtotime($class['early_reg_date'])) ?><br/>
Num Classes: <?= $class['num_classes'] ?><br/>

</span>

<?php } ?>

<?php

				break;
			default:
?>
<strong>Multiple Results Found.</strong>
<?php
				break;
	}
?>


<?php if (!$printable) { ?>
<hr/>
	
<form action="/regman/roster.php" method="post">
		
<strong>Select Class:</strong>
		
<select name="class_name">
	<option name="class_name" value="Beginning Lindy Hop" selected="selected">Beginning Lindy Hop</option>
	<option name="class_name" value="Intermediate Lindy Hop">Intermediate Lindy Hop</option>
</select>
		
<select name="class_month">
<?php
	$months = array("January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December");
	$this_month_first_monday_t = strtotime("first Monday of this month +7 days", $test_time);
	$now_t = time();
	if ($now_t < $this_month_first_monday_t) {
		$current_month = date("F");
	} else {
		$current_month = date("F", strtotime("first day of next month"));
	}
					
					
	//$current_month = date("F");
	foreach ($months as $month) {
?>
	<option name="class_month" value="<?= $month ?>" <?= !strcmp($month, $current_month) ? 'selected=""' : '' ?>><?= $month ?></option>
<?php
	}
?>
</select>
		
<select name="class_year">
<?php
	$current_year = date("Y");
	for ($year = $current_year + 1; $year > 2000; $year--) {
?>
	<option name="class_year" value="<?= $year ?>" <?= ($year == $current_year) ? 'selected=""' : '' ?>><?= $year ?></option>
<?php
	}
?>
</select>

<input type="submit" name="show_class_type_month" value="Show Roster" />
</form>

<?php } ?>

<?php 	include("include/footer.php"); ?>