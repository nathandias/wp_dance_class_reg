<?php
/*
* Template Name: ClassRoster
*/
	date_default_timezone_set("America/Los_Angeles");

	require "include/regdb.php";

	$mysqli = new mysqli($db_host, $db_user, $db_pass, $database);
	if ($mysqli->connect_errno) {
		die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	}
?>

<html>
<head>
<!--	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css" integrity="sha384-PDle/QlgIONtM1aqA2Qemk5gPOE7wFq8+Em+G/hmo5Iq0CCmYZLv3fVRDJ4MMwEA" crossorigin="anonymous"> -->
	<link href="/regman/css/roster.css" rel="stylesheet" type="text/css" />
	<link href="/regman/css/roster.css" rel="stylesheet" type="text/css" media="print"/>
	<link rel="icon" href="https://www.thisdomain.com/wp-content/uploads/2017/10/cropped-Cats-Corner-favicon-1-32x32.png" sizes="32x32" />
<link rel="icon" href="https://www.thisdomain.com/wp-content/uploads/2017/10/cropped-Cats-Corner-favicon-1-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon-precomposed" href="https://www.thisdomain.com/wp-content/uploads/2017/10/cropped-Cats-Corner-favicon-1-180x180.png" />
<meta name="msapplication-TileImage" content="https://www.thisdomain.com/wp-content/uploads/2017/10/cropped-Cats-Corner-favicon-1-270x270.png" />
	
	
    <script>
    $(function() {
       
		$( "#datepicker" ).multiDatesPicker( "option", "dateFormat", 'yy-mm-dd');
    });
    </script>
</head>
<script type="text/javascript">
	function showStuff(id) {
		document.getElementById(id).style.display = 'block';
	}
	function hideStuff(id) {
		document.getElementById(id).style.display = 'none';
	}
</script>
<body>

<?php
	if (isset($_GET['printable'])) {
		$printable = true;
	} else {
		$printable = false;
	}
?>
<?php include("include/logo_menu.php"); ?>
