<?php 	include("include/header.php"); ?>

<div style="clear:both">
<h2>Upcoming Classes</h2>

<table border=1 width="100%">
<tr><td colspan=2><td colspan=3>Paypal</td><td colspan=3>Roles</td></tr>
<tr><td>Date</td><td>Name</td><td>Gross</td><td>Fees</td><td>Net</td><td>Leaders</td><td>Followers</td><td>Solos</td></tr>

<?php

$result = $mysqli->query("SELECT name, id, partnered, max_students, max_leaders, max_followers, start_date, end_date, class_type, day_of_week FROM classes WHERE start_date >= CURDATE() ORDER BY start_date ASC");
for ($classes = array(); $tmp = $result->fetch_array();) $classes[] = $tmp;
$result->free();

foreach ($classes as $class) {
	$formatted_start_date = date("F j, Y", strtotime($class[start_date]));
	echo "<tr><td>$formatted_start_date</td><td><a href=\"roster.php?show_class_id=$class[id]\">$class[name]</a></td>";
	
	$result = $mysqli->query("SELECT role, payment_type, payment_amount FROM registrations WHERE confirmed = 'true' AND class_id = '$class[id]'");
	for ($registrations = array(); $tmp = $result->fetch_array();) $registrations[] = $tmp;
	
	$roles['Leader'] = $roles['Follower'] = $roles['Solo'] = 0;
	$payments['all'] = $payments['paypal_gross'] = $payments['paypal_net'] = $payments['paypal_fees'] = $payments['cash'] = $payments['check'] = $payments['other'] = 0;
	$num_payments['all'] = $num_payments['paypal'] = $num_payments['cash'] = $num_payments['check'] = $num_payments['other'] = 0;

	foreach($registrations as $registration) {			
		// add up payments
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
		
		// count leaders, followers and solos
		$roles[$registration[role]]++;
	}
	
	echo "<td>$payments[paypal_gross]</td><td>$payments[paypal_fees]</td><td>$payments[paypal_net]</td><td>$roles[Leader]</td><td>$roles[Follower]</td><td>$roles[Solo]</td></tr>\n";
	
	
}
?>
</table>

<h2>Past Classes</h2>
<table border=1 width="100%">
<tr><td colspan=2><td colspan=3>Paypal</td><td colspan=3>Roles</td><td>Actions</td></tr>
<tr><td>Date</td><td>Name</td><td>Gross</td><td>Fees</td><td>Net</td><td>Leaders</td><td>Followers</td><td>Solos</td><td></td></tr>

<?php

$result = $mysqli->query("SELECT name, id, partnered, max_students, max_leaders, max_followers, start_date, end_date, class_type, day_of_week FROM classes WHERE start_date < CURDATE() ORDER BY start_date DESC");
for ($classes = array(); $tmp = $result->fetch_array();) $classes[] = $tmp;
$result->free();

foreach ($classes as $class) {
	$formatted_start_date = date("F j, Y", strtotime($class[start_date]));
	echo "<tr><td>$formatted_start_date</td><td><a href=\"roster.php?show_class_id=$class[id]\">$class[name]</a></td>";
	
	$result = $mysqli->query("SELECT role, payment_type, payment_amount FROM registrations WHERE confirmed = 'true' AND class_id = '$class[id]'");
	for ($registrations = array(); $tmp = $result->fetch_array();) $registrations[] = $tmp;
	
	$roles['Leader'] = $roles['Follower'] = $roles['Solo'] = 0;
	$payments['all'] = $payments['paypal_gross'] = $payments['paypal_net'] = $payments['paypal_fees'] = $payments['cash'] = $payments['check'] = $payments['other'] = 0;
	$num_payments['all'] = $num_payments['paypal'] = $num_payments['cash'] = $num_payments['check'] = $num_payments['other'] = 0;

	foreach($registrations as $registration) {			
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
		
		$roles[$registration[role]]++;
	}
	
	
echo "<td>$payments[paypal_gross]</td><td>$payments[paypal_fees]</td><td>$payments[paypal_net]</td><td>$roles[Leader]</td><td>$roles[Follower]</td><td>$roles[Solo]</td><td><a href=\"/regman/export_students.php?class_id=$class[id]\">Export Students</a></td></td></tr>\n";
	
}
?>
</table>

<?php include("include/footer.php"); ?>