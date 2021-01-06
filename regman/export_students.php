<?php include("include/header.php"); ?>

<div style="clear:both">
<?php
	/* if (isset($_POST['show_inactive']) && $_POST['show_inactive'] == 'Show Roster') {
		$class_id = $_POST['inactive_class_id'];
	} elseif (isset($_POST['show_active']) && $_POST['show_active'] == 'Show Roster') {
		$class_id = $_POST['active_class_id'];
	}
	*/
	
	if (isset($_GET['class_id'])) {
		$class_id = $_GET['class_id'];
	}
	
	// fetch the selected class information (name, id, etc.)
	$result = $mysqli->query("SELECT name, id, partnered, max_students, max_leaders, max_followers,
		start_date, end_date, class_type, day_of_week, start_time, end_time
		FROM classes WHERE id = '$class_id'");
	$class = $result->fetch_assoc();
	$result->free();
	
	// fetch all the results for that class
	$result = $mysqli->query("SELECT first_name, last_name, email, role, payment_type, payment_amount,
		waiver FROM registrations WHERE confirmed = 'true' AND class_id = '$class_id' AND email_list_subscribe = '1'");
	for ($registrations = array(); $tmp = $result->fetch_array();) $registrations[] = $tmp;
	$result->free();
	
?>
	
	<h1><?= $class[name] ?> (<?= $class[id] ?>)</h1>
<?php
	if ($class[class_type] == "weekly group" || $class[type] == "children's group") {
?>
	<h2><?= date("F Y", strtotime($class[start_date])) ?></h2>
	<h2><?= $class[day_of_week] ?>s, <?= date("g:ia", strtotime($class[start_time])) ?> - <?= date("g:ia", strtotime($class[end_time])) ?></h2>
<?php
    } elseif ($class[class_type] == "workshop") {
?>
	<h2><?= date("l, F j, Y", strtotime($class[start_date])) ?>, <?= date("g:ia", strtotime($class[start_time])) ?> - <?= date("g:ia", strtotime($class[end_time])) ?></h2>
<?php
	}
	
	if (strpos($class[name], "Beginning") !== FALSE) {
		$class_level = "Beginner";
	} elseif (strpos($class[name], "Intermediate") !== FALSE) {
		$class_level = "Intermediate";
	}

    $roles = array("Leader", "Follower", "Solo");
	foreach ($roles as $role) {
?>

<?php	
		foreach ($registrations as $registration) {
             if ($registration[role] == $role) {
				//if ($registration[email] == "") $registration[email] = ' ';
				//if ($registration[first_name] == "") $registration[first_name] = ' ';
				//if ($registration[last_name] == "") $registration[last_name] = ' ';
			
			 
?>
		<?= join(",", array($registration[email], $registration[first_name], $registration[last_name], $class_level, $registration[role])) ?><br />
<?php			 
			 }
		}
	}
?>

<hr />

		<form action="/regman/export_students.php" method="post">
		<strong>Active Classes:</strong>
		<select name="active_class_id">
<?php
		$result = $mysqli->query("SELECT name, id, start_date FROM classes WHERE active = 'yes'");
		for ($active_classes = array(); $tmp = $result->fetch_array();) $active_classes[] = $tmp;
		$result->free();
		
		$result = $mysqli->query("SELECT name, id, start_date FROM classes WHERE active = 'no'");
		for ($inactive_classes = array(); $tmp = $result->fetch_array();) $inactive_classes[] = $tmp;
		$result->free();

		foreach($active_classes as $active_class) {
			echo $active_class[name];
			if (!isset($_POST['active_class_id'])) {
				$_POST['active_class_id'] = $active_class[id]; // show the first class roster by default
			}	
?>
		<option name="active_class_id" value="<?= $active_class[id] ?>" <?= ($_POST['active_class_id'] == $active_class[id]) ? 'selected="selected"' : '' ?> ><?= $active_class[name] ?> (<?= date("F Y", strtotime($active_class[start_date])) ?>)</option>
<?php
		}
?>
		</select><input type="submit" name="show_active" value="Show Roster" /><br/>

		<strong>Inactive Classes:</strong><select name="inactive_class_id">
<?php
		foreach ($inactive_classes as $inactive_class) {
			echo $inactive_class[name];
			if (!isset($_POST['inactive_class_id'])) {
				$_POST['inactive_class_id'] = $inactive_class[id]; // show the first class roster by default
			}	
?>
		<option name="inactive_class_id" value="<?= $inactive_class[id] ?>" <?= ($_POST['inactive_class_id'] == $inactive_class[id]) ? 'selected="selected"' : '' ?> ><?= $inactive_class[name] ?> (<?= date("F Y", strtotime($inactive_class[start_date])) ?>)</option>
<?php
		}
?>
		</select><input type="submit" name="show_inactive" value="Show Roster" /><br/>
		
		<input type="checkbox" name="extras" <?= $_POST['extras'] == 'on' ? 'checked' : '' ?>/>Show Extras<br/>
		
				
		</form>
</body>
</html>								