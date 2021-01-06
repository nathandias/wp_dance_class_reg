<html>
<head>
    <link rel="stylesheet" href="/regman/jquery/jquery-ui.css" />
	<link href="/regman/jquery/jquery.datepick.css" rel="stylesheet">

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="/regman/jquery/jquery.plugin.js"></script>
	<script src="/regman/jquery/jquery.datepick.js"></script>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>jQuery UI Tabs - Default functionality</title>
	

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	
	<script src="https://code.jquery.com/jquery-3.1.1.js"
		integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
		crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script>
	$( function() {
	$( "#tabs" ).tabs();
	} );
	</script>
	


    <link rel="stylesheet" href="/resources/demos/style.css" />
     
    <script>
	
			$(function() {
			$('#multi2Picker').datepick({ 
				multiSelect: 2, showTrigger: '#calImg'});
			$('#multi3Picker').datepick({ 
				multiSelect: 3, showTrigger: '#calImg'});
			$('#multi5Picker').datepick({ 
				multiSelect: 5, showTrigger: '#calImg'});
				
			$('#form1datePicker').datepick({
				multiSelect: 5, showTrigger: '#calImg'});
			$('#form2datePicker').datepick({
				multiSelect: 5, showTrigger: '#calImg'});
			$('#form3datePicker').datepick({
				multiSelect: 5, showTrigger: '#calImg'});
			
		});

    </script>
</head>
<body>

<?php

if ($_POST['form_select'] == '2' && ($_POST['action'] == 'preview' || $_POST['action'] == 'commit')) {
	
	// handle the Cats Corner Classes form (2)...this opens in a new tab/window
	
	$the_main_post = <<<EOT
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
<a href="http://www.thisdomain.com/classes"><img src="{$_POST['IconURL']}" style="float:left; margin-right:5px; width:150px; vertical-align:text-top"></a>
<div style="font-family:verdana,arial,sans-serif; color:#000066;font-size:14px">
<strong><a href="http://www.thisdomain.com/classes/">Cats Corner: Lindy Hop & Swing Dance Classes</a> @ Balancoire Supper Club, SF</a></strong>
<table class="gridtable">
<tr><th width="150px">Time</th><th width="300px">Main Floor</td><th width="300px">Upstairs Floor</th></tr>
<tr><th>7-8pm<br/>(monthly&nbsp;series)</th>
<td>{$_POST['Main7pmClass']}<br/>with {$_POST['Main7pmTeachers']}</td>
<td>{$_POST['Upstairs7pmClass']}<br/>with {$_POST['Upstairs7pmTeachers']}</td>
<tr><th>8-9pm<br/>(monthly&nbsp;series)</th>
<td>{$_POST['Main8pmClass']}<br/>with {$_POST['Main8pmTeachers']}</td>
<td>{$_POST['Upstairs8pmClass']}<br/>with {$_POST['Upstairs8pmTeachers']}</td>
</tr>
<tr><th>9-9:30pm<br/>(weekly drop-in)</th><td colspan="2">Beginning Swing Drop-in with Nathan, Alyssa & guest teachers</td></tr>
</table>
<em>Live Music Dance Party, Different Bands Each Week, 9:30pm-midnight</em>
</div>
<div style="clear:both"></div>
EOT;

$the_main_post = str_replace(array("\r", "\n"), '', $the_main_post);


	$schedule = <<<EOT
<strong>9-9:30pm Beginning Swing Weekly Drop-in Class, $10/week</strong><br/>We teach the basics of swing dancing starting from scratch each week! We teach different steps including East Coast Swing, Jig Walks and Charleston.<br/><em>No partner needed. No experience needed. FREE for Cat's Corner Lindy Hop students.</em>

<strong>7-8pm Beginning Lindy Hop {$_POST['NumWeeks']} Week Series Class, {$_POST['EarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['FullRegPrice']} full price. 20 students max.</strong><br/>Once you're familiar with the basics of swing, move up to our Beginning Lindy Hop month-long class! Lindy Hop is often called the "mother of all swing dances" and originated in the Savoy Ballroom in 1930s and 40s. It's an energetic dance filled with fun, rhythmic footwork and flashy styling. In this class we cover the swingout, lindy circle and fundamental lindy hop steps and lead-follow technique. This is a progressive series class, which means we review and build on previous weeks' material.<br/><em>No partner needed. Swing dance experience recommended but not required.</em>

<strong>8-9pm Intermediate Lindy Hop {$_POST['NumWeeks']} Week Series Class, {$_POST['EarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['FullRegPrice']} full price. 20 students max.</strong><br/>The best part of Lindy Hop is all the moves, variations and styling options. The possibilities are endless! In this intermediate level class, we cover intermediate level steps and focus on solid lead-follow technique and transitions. This is a progressive series class, which means we review and build on previous weeks' material.<br/><em>No partner needed. 4+ months of beginning lindy hop and regular social dancing required.</em>

<strong>7 & 8pm Intermediate/Advanced Lindy & Special Topics {$_POST['UpstairsNumWeeks']} Week Series Classes, {$_POST['UpstairsEarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['UpstairsFullRegPrice']} full price. 20 students max.</strong><br/>These classes taught by a variety of guest instructors will help you move beyond the basics and connect the dots in your dancing. Topics include related dance forms such Balboa, Charleston, Shag, Blues, Solo Jazz as well as thematic  classes on Rhythm & Flow, Fast & Slow Tempos, Musicality, Advanced Technique, and more...see website for specific monthly offerings<br/><em>No partner needed. 10+ months of beginning & intermediate lindy hop and regular social dancing required.</em>


<a href="http://www.thisdomain.com/classes/">Register online TODAY</a> and get a $10 discount on a Lindy Hop Class!
<strong style="color:#660000">All class prices INCLUDE dance party admission at 9:30pm. Dance to LIVE MUSIC every Wednesday!</a></strong>

<strong>Class location:</strong>
Balancoire Supper Club, 2565 Mission Street, SF
between 21st & 22nd Streets, Near the 24th & Mission BART Station, Good Street Parking

More info at:
<strong><a href="http://www.thisdomain.com/classes/">http://www.thisdomain.com/classes/</a></strong>

Cat's Corner - Wednesday Night Swing Dance Party
LIVE MUSIC, Dance Party & Lessons at the classiest little night club, bar & restaurant in San Francisco!
EOT;

//$schedule = str_replace(array("\n", "\r"), '', $schedule);


	//create array of data to be posted
	$dates = explode(',', $_POST['date']);
	foreach ($dates as &$date) {
		$date = strtotime($date . ", 7:00 PM");
	}
	$unixtime = implode("+", $dates);

	$post_data['DURATION'] = "9:30 PM";
	$post_data['CLASS_TYPE'] = "pre-register.class.gif";
	$post_data['LOCATION'] = $the_main_post;
	$post_data['INSTRUCTORS'] = "";
	$post_data['FEE'] = "Month-long series: $60-80 / Weekly Drop-in: $10-20 (see website for details)";
	$post_data['SCHEDULE'] = $schedule;
	$post_data['CONTACT'] = "";
	$post_data['REFERENCES']="";
	$post_data['EMAIL']="POSTER_EMAIL_ADDRESS";
	$post_data['EPOCH'] = $unixtime;
	$post_data['ACTION'] = $_POST['action']; // 'preview' or 'commit'


	//traverse array and prepare data for posting (key1=value1)
	foreach ( $post_data as $key => $value) {
	$post_items[] = $key . '=' . rawurlencode($value);
	}

	//create the final string to be posted using implode()
	$post_string = implode ('&', $post_items);

	//create cURL connection

	$curl_connection = 
	curl_init("http://www.lindylist.com/cgi-bin/submit.cgi/classes/ca/SFBay/?epoch=$unixtime");

	//set options
	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl_connection, CURLOPT_USERAGENT, 
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);

	//set data to be posted
	curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

	//perform our request
	$curl_scraped_page = curl_exec($curl_connection);
	
	$url = "http://www.lindylist.com";
	$curl_scraped_page = preg_replace("/<head>/i", "<head><base href='$url' />", $curl_scraped_page, 1);

	//show information regarding the request
//	print_r(curl_getinfo($curl_connection));
//	echo curl_errno($curl_connection) . '-' . 
//				curl_error($curl_connection);
				
	print $curl_scraped_page;

	//close the connection
	curl_close($curl_connection);
}

if ($_POST['form_select'] == '3' && ($_POST['action'] == 'preview' || $_POST['action'] == 'commit')) {

	// handle the General Events Form (3)...opens in a new tab/window
	$event_description = '';
	if ($_POST['info_line1'] || $_POST['info_line2'] || $_POST['info_line3'] || $_POST['info_line4'] ||$_POST['address']) {
		$event_description .= "<ul style='style='font-family:verdana,arial,sans-serif; color:#000066;font-size:12px'>\n";
		if ($_POST['info_line1']) {
			$event_description .= "\t<li style='margin-left:20px;'>{$_POST['info_line1']}</li>\n";
		}
		if ($_POST['info_line2']) {
			$event_description .= "\t<li style='margin-left:20px;'>{$_POST['info_line2']}</li>\n";
		}
		if ($_POST['info_line3']) {
			$event_description .= "\t<li style='margin-left:20px;'>{$_POST['info_line3']}</li>\n";
		}
		if ($_POST['info_line4']) {
			$event_description .= "\t<li style='margin-left:20px;'>{$_POST['info_line4']}</li>\n";
		}
		if ($_POST['address']) {
			$event_description .= "\t<li style='margin-left:20px;'>{$_POST['address']}</li>\n";
		}
		$event_description .= "</ul>\n";
	}

	if ($_POST['simple_info_line']) {
		$_POST['simple_info_line'] = "<br/>" . $_POST['simple_info_line'];
	}

	$the_main_post = <<<EOT
<table>
<tbody>
<tr>
<td style="vertical-align:top">
<a href="{$_POST['event_url']}"><img src="{$_POST['img_url']}" style="width:{$_POST['img_width']}"></a>
</td>
<td style="vertical-align:top;">
<strong><a href="{$_POST['event_url']}" style="font-family:verdana,arial,sans-serif; color:#000066;font-size:14px">{$_POST['event_title']}</a></strong>
{$_POST['simple_info_line']}
{$event_description}
</td>
</tr>
</tbody>
</table>
EOT;

	$the_main_post = str_replace(array("\r", "\n"), '', $the_main_post);


	if ($_POST['schedule']) {
		$schedule = $_POST['schedule'];
	} else {
		$schedule = <<<EOT
<a href="{$_POST['event_url']}">Full event information at the website!</a>
EOT;
	}

//$schedule = str_replace(array("\n", "\r"), '', $schedule);


	//create array of data to be posted
	$dates = explode(',', $_POST['date']);
	foreach ($dates as &$date) {
		$date = strtotime($date . ", {$_POST['event_start']}");
	}
	$unixtime = implode("+", $dates);

	$post_data['DURATION'] = $_POST['event_end'];
	$post_data['CLASS_TYPE'] = "pre-register.class.gif";
	$post_data['LOCATION'] = $the_main_post;
	$post_data['INSTRUCTORS'] = "";
	$post_data['FEE'] = $_POST['fee'];
	$post_data['SCHEDULE'] = $schedule;
	$post_data['CONTACT'] = "";
	$post_data['REFERENCES']="";
	$post_data['EMAIL']=$_POST['email'];
	$post_data['EPOCH'] = $unixtime;
	$post_data['ACTION'] = $_POST['action']; // 'preview' or 'commit'


	//traverse array and prepare data for posting (key1=value1)
	foreach ( $post_data as $key => $value) {
		$post_items[] = $key . '=' . rawurlencode($value);
	}

	//create the final string to be posted using implode()
	$post_string = implode ('&', $post_items);

	//create cURL connection

	$curl_connection = 
	curl_init("http://www.lindylist.com/cgi-bin/submit.cgi/dances/ca/SFBay/?epoch=$unixtime");

	//set options
	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl_connection, CURLOPT_USERAGENT, 
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);

	//set data to be posted
	curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

	//perform our request
	$curl_scraped_page = curl_exec($curl_connection);
	
	$url = "http://www.lindylist.com";
	$curl_scraped_page = preg_replace("/<head>/i", "<head><base href='$url' />", $curl_scraped_page, 1);

	//show information regarding the request
//	print_r(curl_getinfo($curl_connection));
//	echo curl_errno($curl_connection) . '-' . 
//				curl_error($curl_connection);
				
	print $curl_scraped_page;

	//close the connection
	curl_close($curl_connection);
}
?>


<?php
	if (!isset($_POST['form_select'])) {
?>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Cats Corner Bands</a></li>
    <li><a href="#tabs-2">Cats Corner Classes</a></li>
    <li><a href="#tabs-3">General Events</a></li>
  </ul>
  <div id="tabs-1">

  </div>
  <div id="tabs-2">
  
<?php
	$form2['default_iconURL'] = "http://www.thisdomain.com/wp-content/uploads/2014/01/Cats-Corner-Balancoire-Square-Icon-150x150.jpg";
?>

<h1>Cats Corner LindyList.com CLASS Poster</h1>

<form action="" method="post" target="_new">
<input type="hidden" name="form_select" value="2">


Date: <input type="text" name="date" id="form2datePicker" size="40"/><br/>
Image/Icon URL (150x150px) <input type="text" name="IconURL" size="60" value="<?= $form2['default_iconURL'] ?>"/><br/>

<strong>Main Floor Classes:</strong><br/>
Number of Weeks <input type="text" name="NumWeeks" size="1" value="4"/><br/>
Early Registration Price: <input type='text' name="EarlyRegPrice" size="7" value="$60"/>
Full Registration Price: <input type='text' name="FullRegPrice" size="7" value="$70"/><br/>
<strong>Upstairs Floor Classes:</strong><br/>
Number of Weeks <input type="text" name="UpstairsNumWeeks" size="1" value="4"/><br/>
Early Registration Price: <input type='text' name="UpstairsEarlyRegPrice" size="7" value="$60"/>
Full Registration Price: <input type='text' name="UpstairsFullRegPrice" size="7" value="$70"/><br/>

<br/>

<strong>7pm Main Floor Class</strong><br/>
Class Title <input type="text" name="Main7pmClass" size="60" value="Beginning Lindy Hop"><br/>
Teachers <input type="text" name="Main7pmTeachers" size="60" value="Nathan Dias &amp Alyssa Glanville"><br/>
<strong>7pm Upstairs Floor Class</strong><br/>
Class Title <input type="text" name="Upstairs7pmClass" size="60" value=""><br/>
Teachers <input type="text" name="Upstairs7pmTeachers" size="60" value=""><br/>
<strong>8pm Main Floor Class</strong><br/>
Class Title <input type="text" name="Main8pmClass" size="60" value="Intermediate Lindy Hop"><br/>
Teachers <input type="text" name="Main8pmTeachers" size="60" value="Nathan Dias &amp Alyssa Glanville"><br/>
<strong>8pm Upstairs Floor Class</strong><br/>
Class Title <input type="text" name="Upstairs8pmClass" size="60" value=""><br/>
Teachers <input type="text" name="Upstairs8pmTeachers" size="60" value=""><br/>

<input type="submit" name="action" value="preview"/>
<input type="submit" name="action" value="commit"/>

</form>
 
  </div>
  <div id="tabs-3">
<?php
$form3['default_img_url'] = "http://www.thisdomain.com/wp-content/uploads/2014/01/Cats-Corner-Balancoire-Square-Icon-150x150.jpg";
$form3['default_img_width'] = "100px";
$form3['default_email'] = "POSTER_EMAIL_ADDRESS";
?>
  
<h1>Cats Corner LindyList.com GENERIC EVENT Poster</h1>

<form action="" method="post" target="_new">
<input type="hidden" name="form_select" value="3">

Event Title: <input type="text" name="event_title" size="80" value=""/><br/>
Event URL: <input type="text" name="event_url" size="80" value="http://www.thisdomain.com"/><br/>
Date: <input type="text" name="date" id="form3datePicker" size="40"/><br/>
Start Time: <input type="text" name="event_start" size="10" value="0:00 AM"/> End Time: <input type="text" name="event_end" size="10" value="23:59 PM"/><br/>
Image/Icon URL: <input type="text" name="img_url" size="60" value="<?= $form3['default_img_url'] ?>"/><br/>
Image/Icon Width (default=150px): <input type="text" name="img_width" size="6" value="<?= $form3['default_img_width'] ?>"/><br/> 
Price/Fee: <input type="text" name="fee" size="60" value=""/><br/>
Email: <input type="text" name="email" size="60" value="<?= $form3['default_email'] ?>"/><br/>

<br/>

Non-UL Info Line: <input type="text" name="simple_info_line" size="60" value=""/><br/>
<br/>

Info Line 1: <input type="text" name="info_line1" size="60" value="optional text here..."/><br/>
Info Line 2: <input type="text" name="info_line2" size="60" value="optional text here..."/><br/>
Info Line 3: <input type="text" name="info_line3" size="60" value="optional text here..."/><br/>
Info Line 4: <input type="text" name="info_line4" size="60" value="optional text here..."/><br/>
Address: <input type="text" name="address" size="60" value="optional address here..."/><br/>

<br/>

Schedule:
<textarea name="schedule" rows="5" cols="60">
</textarea>

<br/>


<input type="submit" name="action" value="preview"/>
<input type="submit" name="action" value="commit"/>

</form>
  
  

  </div>
</div>

<?php
}
?>






	

</body>
</html>
