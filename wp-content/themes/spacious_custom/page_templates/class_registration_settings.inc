<?php
//custom settings for this organization

$business_name = "Organization Name";
$business_email = "register@thisdomain.com";
$newsletter_frequency = "weekly";

$production = true;
$debug = false;

$return_url = "http://www.thisdomain.com/signup-thank-you";
$cancel_return_url = "http://www.thisdomain.com/register";
$notify_url = "http://www.thisdomain.com/ipn/ipn.php";

$class_types = array('Workshop', 'Weekly Group');

	
if ($production) {
	$merchant_email = "live_merchant_email_address";
	$db_host = "database_hostname";
	$db_user = "database_username";
	$db_pass = "database_password";
	$database = "thisdomain_classregistration";

} else {
	$debug = true;
	$merchant_email = "testing_merchant_email_address";
	$db_host = "database_hostname";
	$db_user = "database_username";
	$db_pass = "database_password";
	$database = "thisdomain_classregistration_test";
}

$post_class_listing_message = <<< EOT
    <p>Can't make Wednesdays? Are classes sold out? Check out
	<a href="http://www.swingornothing.com/mondays/">Monday Lindy Hop classes in the Mission</a>
	at the Womens' Building...as well information about private lessons and special classes with Nathan
	<a href="http://www.swingornothing.com">SwingorNothing.com!</a></p>
EOT;
	
$payment_and_refunds_message = <<< EOT
	<h2>Review Payment and Refund  Policy</h2>
	<p>There will be a $20 charge for all returned checks.</p>
	<p><span class="style1">No refunds on missed classes &amp; sorry, no make-ups for the classes.</span></p>
	<p>  Credit can be given for a future month to students who are going to miss an entire month if you inform us by no later than the 2nd class of the series.</p>
	<p>If you miss a week of class, don't worry: we always review the previous week's class material before moving on. You may also consider a private lesson or arriving early to ask your instructors and fellow students about missed material. </p>
EOT;

$liability_waiver_message = <<< EOT
<h2>Read &amp; Accept Liability Waiver:</h2>
<p><em>As a participant in any program or class at Cat's Corner I recognize and acknowledge there are certain risks of physical injury, and I agree to assume the full risk of injuries, including death, damages or loss that I may sustain as a result of participating in any activities connected with or associated with such program or class.</em></p>
<p><em>I agree to waive and relinquish all claims I or my minor child may have as a result of participating in any program or class against Cat's Corner and its officers, agents, servants, employees and independent contractors.</em></p>
<p><em>I further agree to indemnify and hold harmless and defend Cat's Corner and its officers, agents, servants, employees and independent contractors from my claims resulting from  injuries including death, damages and losses sustained by me or my minor child that arise out of, in connection with, or in any way associated with the activities of this program.</em></p>
EOT;

?>