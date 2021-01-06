if (empty($newvals['reg_open_date']) {
	if (!empty($newvals['start_date']) {
		$newvals['reg_open_date'] = date ("Y-m-d H:i:s", strtotime("-28 days", strtotime($newvals['start_date'])));
		//default 28 days before class start date
	}
}

if (empty($newvals['reg_close_date']) {
	if (!empty($newvals['start_date']) {
		$newvals['reg_close_date'] = date("Y-m-d H:i:s", strtotime($newvals['start_date']));
	}
}
