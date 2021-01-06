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
	$main_floor_leader_teacher = "Leader Teacher Name";
	$main_floor_leader_teacher_fname = "Leader Teacher Name";
	$main_floor_follower_teacher = "Follower Teacher Name";
	$main_floor_follower_teacher_fname = "Follower Teacher Name";
?>


<?php
$default_iconURL = "http://www.thisdomain.com/wp-content/uploads/2016/12/Swing-Lindy-Hop-Classes-at-Cats-Corner-200x200.jpg";

if ($_POST['action'] == 'preview' || $_POST['action'] == 'commit') {
	
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
<strong><a href="http://www.thisdomain.com/classes/">Cats Corner: Lindy Hop & Swing Dance Classes</a> @ Swedish American Hall, SF</a></strong>
<table class="gridtable">
<tr><th width="150px">Time</th><th width="175px">Main Floor</td><th width="175px">Side Floor</th></tr>
<tr><th>7-8pm<br/>(monthly&nbsp;series)</th>
<td>{$_POST['Main7pmClass']}<br/>{$_POST['Main7pmTeachers']}</td>
<td>{$_POST['Upstairs7pmClass']}<br/>{$_POST['Upstairs7pmTeachers']}</td>
<tr><th>8-9pm<br/>(monthly&nbsp;series)</th>
<td>{$_POST['Main8pmClass']}<br/>{$_POST['Main8pmTeachers']}</td>
<td>{$_POST['Upstairs8pmClass']}<br/>{$_POST['Upstairs8pmTeachers']}</td>
</tr>
<tr><th>9-9:30pm<br/>(weekly drop-in)</th><td colspan="2">Beginning Swing Drop-in with {$main_floor_leader_teacher_fname}, {$main_floor_follower_teacher_fname} &amp; guest teachers</td></tr>
</table>
<em>Live Music Dance Party, Different Bands Each Week, 9:30pm-midnight</em>
</div>
<div style="clear:both"></div>
EOT;

$the_main_post = str_replace(array("\r", "\n"), '', $the_main_post);


	$schedule = <<<EOT
<strong>9-9:30pm Beginning Swing Weekly Drop-in Class, $10/week</strong><br/>We teach the basics of swing dancing starting from scratch each week! We teach different steps including East Coast Swing, Jig Walks and Charleston.<br/><em>No partner needed. No experience needed. FREE for Cat's Corner Lindy Hop students.</em>

<strong>8-9pm Beginning Lindy Hop {$_POST['NumWeeks']} Week Series Class, {$_POST['EarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['FullRegPrice']} full price. 50 students max.</strong><br/>Once you're familiar with the basics of swing, move up to our Beginning Lindy Hop month-long class! Lindy Hop is often called the "mother of all swing dances" and originated in the Savoy Ballroom in 1930s and 40s. It's an energetic dance filled with fun, rhythmic footwork and flashy styling. In this class we cover the swingout, lindy circle and fundamental lindy hop steps and lead-follow technique. This is a progressive series class, which means we review and build on previous weeks' material.<br/><em>No partner needed. Swing dance experience recommended but not required.</em>

<strong>7-8pm Intermediate Lindy Hop {$_POST['NumWeeks']} Week Series Class, {$_POST['EarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['FullRegPrice']} full price. 50 students max.</strong><br/>The best part of Lindy Hop is all the moves, variations and styling options. The possibilities are endless! In this intermediate level class, we cover intermediate level steps and focus on solid lead-follow technique and transitions. This is a progressive series class, which means we review and build on previous weeks' material.<br/><em>No partner needed. 4+ months of beginning lindy hop and regular social dancing required.</em>

<strong>7 & 8pm Intermediate/Advanced Lindy & Special Topics {$_POST['UpstairsNumWeeks']} Week Series Classes, {$_POST['UpstairsEarlyRegPrice']} <a href="http://www.thisdomain.com/classes/">online registration</a>, {$_POST['UpstairsFullRegPrice']} full price. 26 students max.</strong><br/>These classes taught by a variety of guest instructors will help you move beyond the basics and connect the dots in your dancing. Topics include related dance forms such Balboa, Charleston, Shag, Blues, Solo Jazz as well as thematic  classes on Rhythm & Flow, Fast & Slow Tempos, Musicality, Advanced Technique, and more...see website for specific monthly offerings<br/><em>No partner needed. 10+ months of beginning & intermediate lindy hop and regular social dancing required.</em>


<a href="http://www.thisdomain.com/classes/">Register online TODAY</a> and get a $15 discount on a Lindy Hop Class!
<strong style="color:#660000">All class prices INCLUDE dance party admission at 9:30pm. Dance to LIVE MUSIC every Wednesday!</a></strong>

<strong>Class location:</strong>
Swedish American Hall, 2174 Market Street, SF
between 14th & 15th Streets, near the Church St. MUNI station

More info at:
<strong><a href="http://www.thisdomain.com/classes/">http://www.thisdomain.com/classes/</a></strong>

Cat's Corner - Wednesday Night Swing Dance Party
LIVE MUSIC, Dance Party & Lessons at the classiest dance hall, bar & restaurant in San Francisco!
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
	$post_data['FEE'] = "Month-long series: $50-85 / Weekly Drop-in: $10-22 (see website for details)";
	$post_data['SCHEDULE'] = $schedule;
	$post_data['CONTACT'] = "";
	$post_data['REFERENCES']="";
	$post_data['EMAIL']="admin@thisdomain.com";
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
} else {
	
?>

<h1>Cats Corner LindyList.com CLASS Poster</h1>

<form action="" method="post" target="_new">


Date: <input type="text" name="date" id="multi5Picker" size="40"/><br/>
Image/Icon URL (150x150px) <input type="text" name="IconURL" size="60" value="<?= $default_iconURL ?>"/><br/>

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
Teachers <input type="text" name="Main7pmTeachers" size="60" value="with <?= $main_floor_leader_teacher ?> &amp <?= $main_floor_follower_teacher ?>"><br/>
<strong>7pm Side Floor Class</strong><br/>
Class Title <input type="text" name="Upstairs7pmClass" size="60" value=""><br/>
Teachers <input type="text" name="Upstairs7pmTeachers" size="60" value=""><br/>
<strong>8pm Main Floor Class</strong><br/>
Class Title <input type="text" name="Main8pmClass" size="60" value="Intermediate Lindy Hop"><br/>
Teachers <input type="text" name="Main8pmTeachers" size="60" value="with <?= $main_floor_leader_teacher ?> &amp <?= $main_floor_follower_teacher ?>"><br/>
<strong>8pm Side Floor Class</strong><br/>
Class Title <input type="text" name="Upstairs8pmClass" size="60" value=""><br/>
Teachers <input type="text" name="Upstairs8pmTeachers" size="60" value=""><br/>

<input type="submit" name="action" value="preview"/>
<input type="submit" name="action" value="commit"/>

</form>

<?php

}

?>

	

</body>
</html>
