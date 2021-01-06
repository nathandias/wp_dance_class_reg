<html>
<head>
    <link rel="stylesheet" href="/regman/jquery/jquery-ui.css" />
	<link href="/regman/jquery/jquery.datepick.css" rel="stylesheet">

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="/regman/jquery/jquery.plugin.js"></script>
	<script src="/regman/jquery/jquery.datepick.js"></script>

    <link rel="stylesheet" href="/resources/demos/style.css" />
     
    <script>

		$(function() {
			$('#multi2Picker').datepick({ 
				multiSelect: 2, showTrigger: '#calImg'});
			$('#multi3Picker').datepick({ 
				multiSelect: 3, showTrigger: '#calImg'});
							$('#multi5Picker').datepick({ 
				multiSelect: 5, showTrigger: '#calImg'});
		});

    </script>
</head>
<body>

<?php
$default_img_url = "http://www.thisdomain.com/wp-content/uploads/2014/01/Cats-Corner-Balancoire-Square-Icon-150x150.jpg";
$default_img_width = "100px";
$default_email = "ADMIN_EMAIL_ADDRESS";

if ($_POST['action'] == 'preview' || $_POST['action'] == 'commit') {

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
	$post_data['LOCATION'] = $the_main_post;
	$post_data['SCHEDULE'] = $schedule;
	$post_data['CONTACT'] = "";
	$post_data['REFERENCES']="";
	$post_data['EMAIL']=$_POST['email'];
	$post_data['EPOCH'] = $unixtime;
	
	if ($_POST['event_type'] == 'classes') {
		$post_data['CLASS_TYPE'] = $_POST['class_type'];
		$post_data['FEE'] = $_POST['fee'];
		$post_data['INSTRUCTORS'] = ""; // keep it blank
	} else {
		// default: assume dance event type
		$post_data['AGE'] = $_POST['age'];
		echo $_POST['ages'] . "<br/>\n";
		$post_data['BAND'] = ""; // keep it blank
		$post_data['MAPS'] = ""; // keep it blank
	}
	
	$post_data['ACTION'] = $_POST['action']; // 'preview' or 'commit'


	//traverse array and prepare data for posting (key1=value1)
	foreach ( $post_data as $key => $value) {
	$post_items[] = $key . '=' . rawurlencode($value);
	}

	//create the final string to be posted using implode()
	$post_string = implode ('&', $post_items);

	//create cURL connection
	
	$submit_dance_url = "http://www.lindylist.com/cgi-bin/submit.cgi/dances/ca/SFBay/";
	$submit_class_url = "http://www.lindylist.com/cgi-bin/submit.cgi/classes/ca/SFBay/";
	
	$submit_url = $submit_dance_url;
	if ($_POST['event_type'] == 'classes') {
		$submit_url = $submit_class_url;
	}
	
	$submit_url .= "?epoch=$unixtime";
	
	$curl_connection = 
	curl_init($submit_url);

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
} else {
	
?>

<h1>Cats Corner LindyList.com GENERIC EVENT Poster</h1>

<form action="" method="post" target="_new" id="event_poster">

Event Type:
  <input type="radio" name="event_type" value="dances" checked>Dance/Event
  <input type="radio" name="event_type" value="classes">Class
  <input type="radio" name="event_type" value="workshops">Workshop

<table border="1">
<tr>
<td>Dance Only Fields</td>
<td>
  Ages:
  <select name="age">
  <option value="all.ages.gif">All Ages</option>
  <option value="18.ages.gif">18+</option>
  <option value="21.ages.gif">21+</option>
  </select>
</td>
</tr>
<tr>
<td>Class Only Fields</td>
<td>
  Class Type:
  <select name="class_type">
  <option value="pre-register.class.gif" checked>Registration Required</option>
  <option value="drop-in.class.gif">Drop-in</option>

  </select><br/>
  Price/Fee: <input type="text" name="fee" size="60" value=""/><br/>
  
  
</td>
</tr>
</table> 


Event Title: <input type="text" name="event_title" size="80" value=""/><br/>
Event URL: <input type="text" name="event_url" size="80" value="http://www.thisdomain.com"/><br/>
Date: <input type="text" name="date" id="multi5Picker" size="40"/><br/>
Start Time: <input type="text" name="event_start" size="10" value="0:00 AM"/> End Time: <input type="text" name="event_end" size="10" value="23:59 PM"/><br/>
Image/Icon URL: <input type="text" name="img_url" size="60" value="<?= $default_img_url ?>"/><br/>
Image/Icon Width (default=150px): <input type="text" name="img_width" size="6" value="<?= $default_img_width ?>"/><br/> 

Email: <input type="text" name="email" size="60" value="<?= $default_email ?>"/><br/>

<br/>

Non-UL Info Line: <input type="text" name="simple_info_line" size="60" value=""/><br/>
<br/>

Info Line 1: <input type="text" name="info_line1" size="60" value="optional text here..."/><br/>
Info Line 2: <input type="text" name="info_line2" size="60" value="optionaltext here..."/><br/>
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

<?php

}

?>

	

</body>
</html>
