<html>
<head>
    <link rel="stylesheet" href="/regman/jquery/jquery-ui.css" />
	<link href="/regman/jquery/jquery.datepick.css" rel="stylesheet">
    <link rel="stylesheet" href="/resources/demos/style.css" />
</head>
<body>

<?php
	if ($_POST['action'] = 'hide') {
		$event_urls_textarea = $_POST['event_list'];
		$event_urls_list = preg_split('/[\n\r]+/', $event_urls_textarea);
		
		foreach ($event_urls_list as $event_url) {
			$url_parts = explode('/', $event_url);
			$last_four_url_parts = array_slice($url_parts, -4, 4);

			$form_target = '';
			$unixtime = '';
			unset($post_data);
			
			$event_type = $last_four_url_parts[0];
			$state = $last_four_url_parts[1];
			$region = $last_four_url_parts[2];
			$datestamp = $last_four_url_parts[3];
			
			$date_parts['month'] = substr($datestamp, 4, 2);
			$date_parts['date'] = substr($datestamp,6, 2);
			$date_parts['year'] = substr($datestamp, 0, 4);
			
			$date = join('/', $date_parts);
			$unixtime = strtotime("$date" . ", 12:00 AM");
			
			if ($event_type == "classes" || $event_type == "dances") {
				$form_target = "http://www.lindylist.com/cgi-bin/submit.cgi/"
					. "${event_type}/${state}/${region}"
					. "/?epoc=$unixtime";
			} else {
				echo "Unrecognized event URL: $event_url ...skipped.<br/>\n";
				continue;
			}
			
			$location ='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> <script type="text/javascript"> $(document).ready(function(){	$("a[href*=\"';
			$location .= $event_type. '\/' . $state . '\/' . $region . '\/' . $datestamp;
			$location .= '\"]").parent().parent().remove();	}); </script>';

			$post_data['LOCATION'] = $location;
			$post_data['EMAIL'] = "admin@lindylist.com";
			$post_data['EPOCH'] = $unixtime;
			$post_data['ACTION'] = 'commit';
			
			if ($event_type == "dances") {
				$post_data['AGE'] = 'all.ages.gif';
			}
			if ($event_type == "classes") {
				$post_data['CLASS_TYPE'] = 'drop-in.class.gif';
			}
			
			//traverse array and prepare data for posting (key1=value1)
			foreach ( $post_data as $key => $value) {
				$post_items[] = $key . '=' . rawurlencode($value);
			}

			//create the final string to be posted using implode()
			$post_string = implode ('&', $post_items);
			
	
			echo "EVENT: $event_url<br/>\n";
			echo "FORM TARGET: $form_target<br/>\n";
	
			$curl_connection = curl_init($form_target);
			
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
			
			//close the connection
			curl_close($curl_connection);
			
			$curl_scraped_lines = preg_split('/[\n\r]+/', $curl_scraped_page);

			$print = 0;
			foreach ($curl_scraped_lines as $line) {
				if ($print == 1) { echo $line . "\n"; }
				if (preg_match('/^<BODY/', $line)) { $print = 1; }
			}
	
/* 			if (strpos($curl_scraped_page, "Write to file system <B>OK", -1024)) {
				echo "$event_url: hidden<br/>\n";
			} else {
				echo "$event_url: error hiding event<br/>\n";
			}	 */
	
			echo "<hr/>\n";
			
			
			
			
		}
	}
?>
</ul>

<hr>
<h1>Pseudo-delete LindyList.com Event Postings</h1>

<form method="post" name="submit" action="">
	Enter list of lindylist.com event URLs to hide, one per line:<br/>
	<br/>
	Example:<br/>
	http://www.lindylist.com/cgi-bin/view.cgi/classes/ca/SFBay/2017071800000<br/>
	http://www.lindylist.com/cgi-bin/view.cgi/dances/ca/SFBay/2017071800000<br/>
	<textarea rows="5" cols="60" WRAP name="event_list"></textarea>
	<input type="submit" name="action" value="hide"/>
</form>


</body>