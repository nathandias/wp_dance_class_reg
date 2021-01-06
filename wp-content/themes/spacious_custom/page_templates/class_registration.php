<?php
/*
* Template Name: ClassRegistration
*/

$valid_tag = 'default';

date_default_timezone_set("America/Los_Angeles");
	
include "class_registration_settings.inc";

if ($production) {
	$paypal_server = "https://www.paypal.com/cgi-bin/webscr";
} else {
	$paypal_server = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}

// open a connection to the classregistration database, crdb		
if ( ! isset($crdb) ) {
    $crdb = new wpdb($db_user, $db_pass, $database, $db_host);
}


function space_available($class_id) {

	global $crdb;
	
	$class = $crdb->get_row($crdb->prepare("SELECT partnered, max_students, max_leaders, max_followers FROM classes WHERE id = %s", $class_id));

	if ($class->partnered) {
		$leaders = $crdb->get_var($crdb->prepare("SELECT COUNT(id) FROM registrations WHERE class_id = %d AND confirmed = 'true' AND role = %s AND payment_type IN ('paypal', 'cash', 'check')", $class_id, 'Leader'));
		$followers = $crdb->get_var($crdb->prepare("SELECT COUNT(id) FROM registrations WHERE class_id = %d AND confirmed = 'true' AND role = %s  AND payment_type IN ('paypal', 'cash', 'check')", $class_id, 'Follower'));

		
		if ($leaders >= $class->max_leaders && $followers >= $class->max_followers)
			return 'false';
		if ($leaders >= $class->max_leaders)
			return 'followers_only';
		if ($followers >= $class->max_followers)
			return 'leaders_only';
		
		return 'true';
			
	} else {
		$students = $crdb->get_var($crdb->prepare("SELECT COUNT(id) FROM registrations WHERE class_id = %d AND confirmed = 'true'", $class_id));	
		if ($students >= $class->max_students) {
			return 'false';
		}
		return 'true';
	}			
}

function display_steps_icon($step_number) {

	$step = array(
		1 => array(
			'image' => 'http://www.thisdomain.com/wp-content/uploads/2016/12/select-e1483382910736.png',
			'description' => 'Select classes',
			'style' => '',
		),
		2 => array(
			'image' => 'http://www.thisdomain.com/wp-content/uploads/2016/12/confirm-e1483382927754.png',
			'description' => 'Review details',
			'style' => '',
		),
		3 => array(
			'image' => 'http://www.thisdomain.com/wp-content/uploads/2016/12/payment-e1483382941850.png',
			'description' => 'Pay online',
			'style' => '',
		),
	);
	
	$step[$step_number]['style'] = "border: solid 3px";
?>


<?php
	for ($i = 1; $i <= 3; $i++) {
?>
			<figure class='gallery-steps' style='<?= $step[$i]['style'] ?>; float:left; padding:5px; margin: 0px 50px 20px 50px' >
			<div class='gallery-icon landscape'>
				<img width="100" height="100" src="<?= $step[$i]['image'] ?>"  class="attachment-medium size-medium" alt="" aria-describedby="gallery-1-<?= $i ?>" />
			</div>
				<figcaption class='wp-caption-text gallery-caption' id='gallery-1-<?= $i ?>'>
				<?= $i ?>. <?= $step[$i]['description'] ?>
				</figcaption></figure>

<?php	
	}
?>

<script>
fbq('track', 'InitiateCheckout');
</script>



<div style="clear:both"></div>
<?php
}


?>

<?php get_header(); ?>

	<?php do_action( 'spacious_before_body_content' ); ?>

	<div id="primary">
		<div id="content" class="clearfix">
		
		<?php if (!$production) { ?>
		<h1>Testing</h1>
		<?php } ?>

				
<p><small style="color:blue;">Please report any issues you encounter with online registration to <a href="mailto:admin_email_address">our webmaster.</a></small></p>


<?php
	if ($_POST['action'] != 'submit') {

		// just starting the registration process, clear all form variables
		unset($first_name, $last_name, $email, $email_confirm, $class_id, $class_name, $role, $amount, $waiver, $email_list_subscribe);
	}

	if ($_POST['action'] == 'submit') {
		// VALIDATE DATA
		// 1. check that all required fields are supplied
		// 2. check that email & email_confirm match
		// 3. if partnered class, check that leader/follower status supplied
		// 4. double check whether there is space in all classes requested
		//       if not, display a "sorry message" and redisplay the form without the offending class
		$required_fields = array("first_name", "last_name", "email", "email_confirm");
		
		foreach ($required_fields as $field) {
			if ($debug) { error_log("$field: " . $_POST[$field] . "\n"); }
			if (!isset($_POST[$field]) || $_POST[$field] == '') {
				$formError .= "'$field' field was not set.<br/>\n";
			}
		}
		if ($_POST['email'] != $_POST['email_confirm']) {
			$formError .= "Email &amp; confirmation email address do not match.<br/>\n";
		}
		
		if (!isset($_POST['class_ids']) || $_POST['class_ids'] == '') {
			$formError .= "No classes selected. You must register for at least one class.<br/>\n";
		}

 		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$email_confirm = $_POST['email_confirm'];
        $role = $_POST['role'];
		$class_ids = $_POST['class_ids'];
		$class_name = $_POST['class_name'];
		$waiver = $_POST['waiver'];
		$email_list_subscribe = ($_POST['email_list_subscribe'] == 'accepted') ? 1 : 0;
		
		if (isset($class_ids)) {		
			foreach ($class_ids as $class_id => $value) {
				if  (!isset($class_name[$class_id]) || $class_name[$class_id] == '') {
					$formError .= "Class name missing for class_id = $class_id.<br\>\n";
				}

				if (!isset($role[$class_id]) || $role[$class_id] == '' ||
					!($role[$class_id] == 'Leader' || $role[$class_id] == 'Follower' || $role[$class_id] == 'Solo')) {
					$formError .= "No role specified for $class_name[$class_id].<br/>\n";
			
				}
			}
		}
		
		if (!isset($waiver) || $waiver != 'accepted') {
			$formError .= "You must accept the Liability Waiver to register for classes.<br/>\n";
		}
		
	}
	
	if (!isset($_POST['action']) || $_POST['action'] == '' ||(($_POST['action'] == 'submit') && (isset($formError) && $formError != ''))) {
		//i.e. just starting out (non-submit) or submit with form errors
?>
<h1>Register for Classes (Step 1 of 3)</h1>

<?php

	display_steps_icon(1);

	if (isset($formError)) {
		echo "<div style='color:red'>Please correct the following form submission errors and resubmit:<br/><br/>$formError</div>\n";
	}
?>



<h2>Enter student information:</h2>
(*) = required field


<form method="post" action="/register/">
<h3>Student</h3>
<table>
<tbody>
<tr><td>First Name:</td><td><input type="text" name="first_name" value="<?= $first_name ?>" size="40"/></td></tr>
<tr><td>Last Name:</td><td><input type="text" name="last_name" value="<?= $last_name ?>" size="40"/></td></tr>
<tr><td>Email Address:</td><td><input type="text" name="email" value="<?= $email ?>" size="40"/></td></tr>
<tr><td>Confirm Email Address:</td><td><input type="text" name="email_confirm" value="<?= $email_confirm ?>" size="40"/></td></tr>
</tbody>
</table>

<h2>Select Classes:</h2>

<?php
	
	foreach ($class_types as $class_type) {
		
		$classes = $crdb->get_results($crdb->prepare("SELECT * FROM classes WHERE class_type = %s AND ((CURRENT_DATE >= reg_open_date AND CURRENT_DATE <= reg_close_date) AND active='yes' AND tag LIKE '%%%s%%') ORDER BY start_date ASC, start_time ASC, upstairs_floor ASC ", $class_type, $valid_tag));
		
		if (count($classes) <= 0) { continue; }

		
?>

<table border="1" class="gridtable" width="100%">
<tbody>
<tr><th width="70%">Class</th><th>Register</th><th>Role</th></tr>
<?php
		//for($partnered = 0; $partnered <= 1; $partnered++) {
		
			//$classes = $crdb->get_results($crdb->prepare("SELECT * FROM classes WHERE partnered = %d AND class_type = %s AND ((CURRENT_DATE >= reg_open_date AND CURRENT_DATE <= reg_close_date) AND active='yes') ORDER BY upstairs_floor ASC, start_date ASC, start_time ASC", $partnered, $class_type));
			
			foreach ($classes as $class) {
				$space_available = space_available($class->id);
				$partnered = $class->partnered;

?>
<tr>
<td><!-- Class name, id, description, dates, etc. -->
<strong><?= $class->name ?><input type="hidden" name="class_name[<?= $class->id ?>]" value="<?= $class->name ?>"/></strong><br/>
<?php
				if ($class->class_type == 'weekly group' && $class->override_price_description== '') {
?>
<em><?= $class->day_of_week . (($class->num_classes > 1) ? 's' : '') ?>,
<?php
	if ($class->ongoing == 1) {
?>
 ongoing
<?php
	} else {
?>
<?= date("n/j", strtotime($class->start_date)) ?> - <?= date("n/j", strtotime($class->end_date)) ?> (<?= $class->num_classes ?> weeks)
<?php
	}
?>
</em><br/>

<?php
				}
				if ($class->class_type == 'workshop') {
?>
					<em><?= date("l, F j, Y", strtotime($class->start_date)) ?></em><br/>
<?php
				}	

				$mysqldate = date('Y-m-d');

				if ($class->override_price_description != '') {
					echo sprintf("<small>%s</small>", $class->override_price_description);
				} else {

					if ($mysqldate <= $class->early_reg_date) {
					
						if ($class->first_n > 0) {
							if ($class->partnered  == 1) {
								echo sprintf("<small>$%s for first %s leaders and %s followers to register.</small><br/>", $class->first_n_price, $class->first_n, $class->first_n);
							} else {
								echo sprintf("<small>$%s for first %s students to register.</small><br/>", $class->first_n_price, $class->first_n);
							}
					   }
				
						echo sprintf("<small>$%s early registration by %s.<br/>\n$%s when registering for multiple classes.<br/>\n$%s full price online / $%s at the door.</small>", $class->early_reg_price, date("l, F j", strtotime($class->early_reg_date)), $class->multiple_reg_price, $class->full_reg_price, $class->door_price);
					} else {
						echo sprintf("<small>$%s full price online / $%s at the door.<br/>\n$%s when registering for multiple classes.</small>", $class->full_reg_price, $class->door_price, $class->multiple_reg_price);		
					}
				}
?>
</td>

<td><!-- checkbox to register for the class, or "sold out" message -->
<?php
				if ($space_available != 'false') {
?>
	<input type="checkbox" name="class_ids[<?= $class->id ?>]" <?= ($class_ids[$class->id] == 'on') ? 'checked' : '' ?> />
<?php
				} else {
?>
	<strong style="color:red">Sold out.</strong>
<?php
				}
?>
</td>

<td><!-- option Leader/Follower selection -->
<?php
				if ($partnered) {
					// display leader checkbox if appropriate
					if (($space_available == 'true' || $space_available == 'leaders_only') && $class->temp_hold_leaders != 1) {
						if ($space_available == 'leaders_only') {
?>
							<input type="radio" name="role[<?= $class->id ?>]" value="Leader" checked="checked"/> Leader<br/><br />
							<small style="color:red">Sold out for followers.</small>
<?php
						} else {
?>
						<input type="radio" name="role[<?= $class->id ?>]" value="Leader" <?= (($role[$class->id] == 'Leader') ? 'checked="checked"' : '')?> /> Leader<br/>					
<?php	
						}
					}
								
					// display follower checkbox if appropriate
					if (($space_available == 'true' || $space_available == 'followers_only') && $class->temp_hold_followers != 1) {
						if ($space_available == 'followers_only') {
?>
							<input type="radio" name="role[<?= $class->id ?>]" value="Follower" checked="checked"/> Follower<br/><br/>
							<small style="color:red">Sold out for leaders.</small>						
<?php
						} else {
?>
					   	<input type="radio" name="role[<?= $class->id ?>]" value="Follower" <?= (($role[$class->id] == 'Follower') ? 'checked="checked"' : '')?> /> Follower<br/>
<?php	
						}
					}
					if ($class->temp_hold_followers == 1) {
?>
							<p style="color:green; font-size:10px; line-height:1.15;">
							Follower online sign up is paused until more leaders register.
							Followers and couples may still be able to join in person at the door if spots are available.</p></p>
<?php
					}
					if ($class->temp_hold_leaders == 1) {
?>
							<p style="color:green; font-size:10px; line-height:1.15;">
							Leader online sign up is paused until more followers register.
							Leaders and couples may still be able to join in person at the door if spots are available.</p>

<?php	
					}
				

				} else {
?>
	<input type="hidden" name="role[<?= $class->id ?>]" value="Solo"/>
<?php				
				}
?>
</td>

</tr>
<?php
			} // foreach ($classes as $class)
//		} // for ($partnered = 0;
?>
</tbody>
</table>
<?php
	} // foreach ($class_types as $class_type)
?>

<!--
<div style="padding: 15px; margin: 12px; background-color: #ccc;">
<strong>NOTE:</strong> Cat's Corner usually takes place at Swedish American Hall except for a few Wednesdays noted here, due to private events:
<ul style="padding:6px;">
<li>February 21: Ukrainian Hall</li>
<li>March 7: Ukrainian Hall</li>
</ul>
Classes at the alternate location will be drop-in style and discounted for students enrolled in main classes at Swedish American Hall.
</div>
-->

<?= $post_class_listing_message ?>

<?= $payment_and_refunds_message ?>

<?= $liability_waiver_message ?>


<p><input type="checkbox" name="waiver" value="accepted" <?= ($waiver == 'accepted') ? 'checked' : '' ?> /><strong>I HAVE READ AND FULLY UNDERSTAND AND ACCEPT THE ABOVE WAIVER AND RELEASE OF ALL CLAIMS FOR MYSELF.</strong></p>

<?php
	if (!isset($email_list_subscribe)) {
		$email_list_subscribe = 1;
	}
?>

<p><input type="checkbox" name="email_list_subscribe" value="accepted" <?= ($email_list_subscribe) ? 'checked' : '' ?> />Yes, sign me up to receive the <?= $business_name ?> <?= $newsletter_frequency ?> newsletter</p>

<input type="hidden" name="action" value="submit" />
<input type="submit" value="Register for classes" /><br />
(You will be prompted to pay on the next screen)
</form>

<?php
} elseif ($_POST['action'] == 'submit') {

	// and a

	$num_classes = count($class_ids);
    foreach ($class_ids as $class_id => $registered) {
	
		$class = $crdb->get_row($crdb->prepare("SELECT early_reg_price, full_reg_price, multiple_reg_price, early_reg_date, first_n_price, first_n FROM classes WHERE id = %d", $class_id));
		
		$actual_price[$class_id] = $class->full_reg_price;
		$mysqldate = date('Y-m-d');
		if ($mysqldate <= $class->early_reg_date) {
			$actual_price[$class_id] = $class->early_reg_price;
		}
		if ($num_classes > 1) {
			$actual_price[$class_id] = $class->multiple_reg_price;
		}
		if ($class->first_n > 0 ) {			
			$num_registrations = $crdb->get_var($crdb->prepare("SELECT COUNT(id) FROM registrations WHERE class_id = '%d' AND confirmed = 'true' AND role = '%s'", $class_id, $role[$class_id]));
			
//			echo "num registrations: $num_registrations, first_n = $class->first_n<br/>\n";
			if ($num_registrations < $class->first_n) {
				$actual_price[$class_id] = $class->first_n_price;
			}	
		}
	
		$crdb->insert('registrations',
			array(first_name => $first_name,
				last_name => $last_name,
				email => $email,
				class_id => $class_id,
				role => $role[$class_id],
				payment_type => 'paypal',
				payment_amount => $actual_price[$class_id],
				confirmed => 'false',
				waiver => 'accepted',
				email_list_subscribe => $email_list_subscribe,
				class_name => $class_name[$class_id]));
				
		$reg_id[$class_id] = $crdb->insert_id;
		// add the new registration, but mark it unconfirmed	
	}
	
	// and then present the payment option(s)
?>
<h1>Register for classes (step 2 of 3)</h1>

<?= display_steps_icon(2); ?>

<p>Review your registration and selected classes, then click "Proceed to check out" to pay for your class(es). Online payments, including credit and debit cards, are processed securely through PayPal. A PayPal account is <em>not</em> required.</p>

<h2>Student Information</h2>
Name: <?= "$first_name $last_name" ?><br />
Email: <?= $email  ?>
</p>

<h2>Selected Classes</h2>
<form action="<?= $paypal_server ?>" method="post">
<input type="hidden" name="cmd" value="_cart"/>
<input type="hidden" name="upload" value="1"/>
<input type="hidden" name="currency_code" value="USD" />
<input type="hidden" name="business" value="<?= $merchant_email ?>" />
<input type="hidden" name="lc" value="US"/>
<input type="hidden" name="no_note" value="1"/>
<input type="hidden" name="no_shipping" value="1"/>
<input type="hidden" name="return" value="<?= $return_url ?>" />
<input type="hidden" name="cancel_return" value="<?= $cancel_return_url ?>"/>
<input type="hidden" name="notify_url" value="<?= $notify_url ?>" />

<table border='1'>
<tbody>
<tr><th>Class</th><th>Role</th><th>Cost</th></tr>
<?php
$n = 1;

foreach ($class_ids as $class_id => $registered) {
	if (!strcmp($registered, "on")) {
?>
<tr><td><?= $class_name[$class_id] ?></td><td><?= $role[$class_id] ?></td><td>$<?= $actual_price[$class_id]  ?>
       <input type="hidden" name="item_name_<?= $n ?>" value="<?= $class_name[$class_id] ?> (<?= $business_name ?>)"/>
	   <input type="hidden" name="item_number_<?= $n ?>" value="<?= $class_id ?>" />
	   <input type="hidden" name="amount_<?= $n ?>" value="<?= $actual_price[$class_id] ?>" />
	   <input type="hidden" name="on0_<?= $n ?>" value="reg_id" />
	   <input type="hidden" name="os0_<?= $n ?>" value="<?= $reg_id[$class_id] ?>" />
</td></tr>
	   
<?php
    	$n++;
	}
}
?>
</tbody>
</table>
<br />

<input type="submit" value="Proceed to checkout" name="submit">
</form>



<p>For information about mailing cash or check payments, please <a href="mailto:<?= $business_email ?>.">email <?= $business_email ?></a>.</p>
<?php
}
?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->
	
	<?php spacious_sidebar_select(); ?>

	<?php do_action( 'spacious_after_body_content' ); ?>

<?php get_footer(); ?>
