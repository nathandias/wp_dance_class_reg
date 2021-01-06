<?php

if (empty($newvals['reg_open_date']) && !empty($newvals['start_date'])) {
	$newvals['reg_open_date'] = date("Y-m-d H:i:s", strtotime("-28 days", strtotime($newvals['start_date'])));
}

if (empty($newvals['reg_close_date']) && !empty($newvals['start_date'])) {
	$newvals['reg_close_date'] = date("Y-m-d H:i:s", strtotime("-1 days", strtotime($newvals['start_date'])));
}


?>
