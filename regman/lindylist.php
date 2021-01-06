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

<div id="tabs">

  <div id="tabs-1">
    <?php
	$band_urls = array("Fil Lorenz Orchestra" => "http://www.fillorenz.com",
						"Lavay Smith and Her Red Hot Skillet Lickers" => "http://www.lavaysmith.com/",
						"Steve Lucky & the Rhumba Bums" => "http://www.luckylounge.com/",
						"SF Medicine Ball Band" => "http://www.medicineballband.com/",
						"Si Perkoff's Fantastic Swingtime Band featuring Kally Price" => "https://www.facebook.com/si.perkoff",
						"California Honeydrops" => "http://www.cahoneydrops.com/",
						"Harley White Jr. Orchestra" => "",
						"The Rhythm Roustabouts" => "http://www.rhythmroustabouts.com/",
						"Macy Blackman & the Mighty Fines" => "http://www.macyblackman.com/",
						"Clint Baker's Golden Gate Jazz Band" => "http://clintbakerjazz.com/",
						"Le Jazz Hot" => "http://www.hcsf.com",
						"Tin Cup Serenade" => "http://www.tincupserenade.com",
						"The Cosmo Alleycats" => "http://www.cosmoalleycats.com",
						"Josh &amp; the Klipptones" => "http://www.klipptones.com",
						"The Cottontails" => "http://www.thecottontails.com",
						"Slim Jenkins" => "http://www.slimjenkins.com",
						"Rob Reich's Quintet Swings Left" => "http://www.robreich.com",
						"The Hot Baked Goods" => "http://www.thehotbakedgoods.com",
						"Johnny Bones &amp; the Palace of Jazz" => "http://www.thisdomain.com/music-event/bands/johnny-bones-the-palace-of-jazz/",
						"The Alpha Rhythm Kings" => "http://www.alpharhythmkings.com",
						"Sam Rocha and his Cool Friends" => "https://www.thisdomain.com/staff-members/sam-rocha-and-his-cool-friends/",
						"Evelyn and Her Vintage Ties" => "http://www.vintagetiesjazz.com/",
						);
						
	ksort($band_urls);
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

		
?>
	<form method="post" name="submit" action="http://www.lindylist.com/cgi-bin/submit.cgi/dances/ca/SFBay/?epoch=<?= $unixtime ?>" target="_NEW">
	End Time:<input type="text" name="DURATION" value="late"/><br/>
	Ages: <input type="text" name="AGE" value="all.ages.gif"/><br/>
	Event Name: 
	<textarea rows="5" cols="48" WRAP name="LOCATION">
	<span style="font-family:verdana,arial,sans-serif; color:#000066;font-size:12px"><strong><?= $hyperlinked_band ?> @ <a href="http://www.thisdomain.com">Cat's Corner</a> at The Valencia Room, 647 Valencia Street, SF</strong>
Live Music, Dance Party & Lessons at SF's Hip Spot in the Mission!
</span>
	</textarea><br/>
	Schedule/Description: 
	<TEXTAREA ROWS="10" COLS="48" WRAP name="SCHEDULE">
6:45pm Doors open
7pm Intermediate Lindy Hop Series (<a href="http://www.thisdomain.com/classes">registration required</a>)
8pm Beginning Lindy Hop Series (<a href="http://www.thisdomain.com/classes">registration required</a>)
9pm Drop-in Beginning Swing Class
9:30pm Cat's Corner Swing Dance Party featuring live music with <strong><?= $hyperlinked_band ?></strong>

$10 regular admission <strong>includes</strong> drop-in beginning swing lesson at 9pm!
$5 "Happy Hour" discount admission before 9pm (dance only)

No partner needed! Beginners welcome! All ages welcome!

Location:
The Valencia Room
647 Valencia Street, SF, 94110
at Sycamore between 17th and 18th Street, near 16th  Mission BART

More info:
<strong><a href="http://www.thisdomain.com">http://www.thisdomain.com</a></strong>

Cat's Corner at the Valencia Room
LIVE MUSIC, Dance Party & Lessons at SF's Hip Spot in the Mission
	</TEXTAREA><br/>
	
	<input type="text" name="MAPS" value='<a href="http://www.thisdomain.com/venue/">Map</a>'/><br/>
	<input type="text" name="EMAIL" value="admin@thisdomain.com"/><br/>
	<input type="text" name="EPOCH" value="<?= $unixtime ?>"/><br/>
	<input type="submit" name="action" value="preview"/>
	<input type="submit" name="action" value="commit"/>
	</form>
<?php	
	}
?>
  </div>
 
</div>

</body>
</html>
