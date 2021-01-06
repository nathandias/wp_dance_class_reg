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
			$('#multi5Picker').datepick({ 
				multiSelect: 5, showTrigger: '#calImg'});
		});

    </script>
</head>
<body>
<?php
	$band_urls = array("Fil Lorenz Orchestra" => "http://www.fillorenz.com",
						"Lavay Smith & Her Red Hot Skillet Lickers" => "http://www.lavaysmith.com/",
						"Steve Lucky & the Rhumba Bums" => "http://www.luckylounge.com/",
						"SF Medicine Ball Band" => "http://www.medicineballband.com/",
						"Si Perkoff's Fantastic Swingtime Band" => "http://www.siperkoff.com/",
						"California Honeydrops" => "http://www.cahoneydrops.com/",
						"Harley White Jr. Orchestra" => "",
						"The Rhythm Roustabouts" => "http://www.rhythmroustabouts.com/",
						"Macy Blackman & the Mighty Fines" => "http://www.macyblackman.com/",
						"Clint Baker's New Orleans Jazz Band" => "http://clintbakerjazz.com/",
						"Le Jazz Hot" => "http://www.hcsf.com",
						"Tin Cup Serenade" => "http://www.tincupserenade.com",
						"The Cosmo Alleycats" => "http://www.cosmoalleycats.com",
						"Josh &amp; the Klipptones" => "http://www.klipptones.com",
						"The Cottontails" => "http://www.thecottontails.com",
						"Slim Jenkins" => "http://www.slimjenkins.com",
						"Rob Reich's Quintet Swings Left" => "http://www.robreich.com",
						"The Hot Baked Goods" => "http://www.thehotbakedgoods.com",
						"Johnny Bones and the Palace of Jazz" => "https://www.facebook.com/johnnybonesandthepalaceofjazz/",
						);
						
	ksort($band_urls);
?>

<?php
	$venue_addresses_long = array("Ukrainian American Hall" => "345 7th Street, SF, 94103 (near Folsom)",
							"Swedish American Hall" => "2174 Market Street, SF, 94114 (near 14th Street)",
							"Slovenian Hall" => "2101 Mariposa Street, SF, 94107 (near Vermont)"
							"Cafe du Nord (downstairs Swedish American Hall)" => '2174 Market Street, SF, 94114 (near 14th Street)',
							);
	
	$venue_addresses_short = array("Ukrainian American Hall" => "345 7th Street, SF",
							"Swedish American Hall" => "2174 Market Street, SF",
							"Slovenian Hall" => "2101 Mariposa Street, SF",
							"Cafe du Nord" => '2174 Market Street, SF",
							);
							
	$venue_descriptions = array("Ukrainian American Hall" => "at the friendliest dance hall in town!",
							"Swedish American Hall" => "at the swankiest historic dance hall in town!",
							"Slovenian Hall" => "at the cutest historic dance hall in town!",
							"Cafe du Nord" => "at the gorgeous Art Deco lounge downstairs Swedish American Hall!",
							);
							
	ksort($venue_addresses_long);
	ksort($venue_addresses_short);
	ksort($venue_descriptions);
?>

<h1>LindList Helper: Cats Corner Bands</h1>

<h2>Instructions</h2>
<ol>
<li>Click the date field and select the date(s) the band is playing. Multiple date selections are allowed.</li>
<li>Select band name from the drop-down. If band is not listed, contact admin@thisdomain.com and request the addition.</li>
<li>Click the "generate" button to produce the HTML code for the LindyList post (updates this page).</li>
<li>Click "preview" to open a new tab/window and check the LindyList.com post formatting.<br/>
<strong style="color:red">IMPORTANT: Do NOT "commit" the post from the new preview window...it will add unwanted/extra blank lines to the post.</strong><br/>
<strong>Instead, come back to THIS page and:</strong></li>
<li>Click the "commit" button on THIS page to finalize and post the listing to LindyList.com</li>
</ol>


<form action="" method="post">
Date: <input type="text" name="date" id="multi5Picker" size="80"/><br/>
Band: <select name="band">
<?php
	foreach ($band_urls as $band => $url) {
		echo "<option value=\"$band\">$band</option>\n";
	}
?>
</select><br/>
Venue: <select name="venue">
<?php
	foreach ($venue_addresses_short as $venue => $address_short) {
		echo "<option value=\"$venue\">$venue</option>\n";
	}
?>
</select><br/>


<input type="submit" name="action" value="generate"/>
</form>


<?php
	if ($_POST['action'] == 'generate') {
	
		$dates = explode(',', $_POST['date']);
		foreach ($dates as &$date) {
			$date = strtotime($date . ", 9:30 PM");
		}
		$unixtime = implode("+", $dates);
		
		$hyperlinked_band = $_POST['band'];
		if ($band_urls[$hyperlinked_band] != '') {
			$hyperlinked_band = "<a href=\"$band_urls[$hyperlinked_band]\">$hyperlinked_band</a>";
		}

		$venue = $_POST['venue'];
		if ($venue_addresses_short[$venue] != '') {
			$venue_address_short = $venue_addresses_short[$venue];
			$venue_address_long = $venue_addresses_long[$venue];
			$venue_description = $venue_descriptions[$venue];
		}
		
		$location_string = "<span style='font-family:verdana,arial,sans-serif; color:#000066;font-size:12px'><strong>$hyperlinked_band @ <a href=\"http://www.thisdomain.com\">Cat's Corner</a> Savanna Jazz Club, 2937 Mission St., SF</strong><br/>Live Music, Dance Party &amp; Lessons at the classiest little jazz club, bar & restaurant in SF!</span>";
		
?>
	<form method="post" name="submit" action="http://www.lindylist.com/cgi-bin/submit.cgi/dances/ca/SFBay/?epoch=<?= $unixtime ?>" target="_NEW">
	End Time:<input type="text" name="DURATION" value="late"/><br/>
	Ages: <input type="text" name="AGE" value="all.ages.gif"/><br/>
	Event Name: 
	<textarea rows="5" cols="48" WRAP name="LOCATION">
	<span style="font-family:verdana,arial,sans-serif; color:#000066;font-size:12px"><strong><?= $hyperlinked_band ?> @ <a href="http://www.thisdomain.com">Cat's Corner</a> at <?= $venue ?>, <?= $venue_address_short ?></strong>
	Live Music, Swing Dancing and Cocktails at <?= $venue_description ?>
</span>
	</textarea><br/>
	Schedule/Description: 
	<TEXTAREA ROWS="10" COLS="48" WRAP name="SCHEDULE">
6pm Restaurant & Bar Opens
7pm Beginning Lindy Hop Series (<a href="http://www.thisdomain.com/classes">registration required</a>)
8pm Intermediate Lindy Hop Series (<a href="http://www.thisdomain.com/classes">registration required</a>)
9pm Drop-in Beginning Swing Class
9:30pm Cat's Corner Swing Dance Party featuring live music with <strong><?= $hyperlinked_band ?></strong>

$10 regular admission <strong>includes</strong> drop-in beginning swing lesson at 9pm!
$5 "Happy Hour" discount admission before 8:30PM (dance only)

No partner needed! Beginners welcome! All ages welcome!

Location:
<?= $venue ?>

<?= $venue_address_long ?>

More info:
<strong><a href="http://www.thisdomain.com">http://www.thisdomain.com</a></strong>

Organization at <?= $venue ?>

Live Music, Swing Dancing and Cocktails <?= $venue_description ?>
	</TEXTAREA><br/>
	
	<input type="text" name="MAPS" value='<a href="http://www.thisdomain.com/venue">Map</a>'/><br/>
	<input type="text" name="EMAIL" value="admin@thisdomain.com"/><br/>
	<input type="text" name="EPOCH" value="<?= $unixtime ?>"/><br/>
	<input type="submit" name="action" value="preview"/>
	<input type="submit" name="action" value="commit"/>
	</form>
<?php	
	}
?>
	

</body>
</html>
