<?php 

	// $result_array = get_target_weekday_dropdownlist_datasource();
	// print_r($result_array);
	// echo(sha1("123456"));
	// echo '</ br>';
	// echo(sha1("123456"));

	current_notes_data_clean_everyday('yanggl');


// Get target date by weekday value.
function get_date_by_weekday($weekday) {
	// Today wday value.
	$curdate = getdate();
	$wday = $curdate["wday"];

	$target_wday = 0;
	switch (strtolower($weekday)) {
		case "sunday":
			$target_wday = 0;
			break;
		case "monday":
			$target_wday = 1;
			break;
		case "tuesday":
			$target_wday = 2;
			break;
		case "wednesday":
			$target_wday = 3;
			break;
		case "thursday":
			$target_wday = 4;
			break;
		case "friday":
			$target_wday = 5;
			break;
		case "saturday":
			$target_wday = 6;
			break;
	}

	$day_interval = 0;
	if ($wday > $target_wday)
		$day_interval = $target_wday + 6 - $wday;
	else
		$day_interval = $target_wday - $wday;

	$result_date = mktime(0, 0, 0, date("m") , date("d") + $day_interval, date("Y"));
	return date("Y-m-d H:i:s", $result_date);
}

function get_target_weekday_dropdownlist_datasource() {
	$data_array = array(
		array("name"=>"sunday", "value"=>"星期日"), 
		array("name"=>"monday", "value"=>"星期一"), 
		array("name"=>"tuesday", "value"=>"星期二"), 
		array("name"=>"wednesday", "value"=>"星期三"), 
		array("name"=>"thursday", "value"=>"星期四"), 
		array("name"=>"friday", "value"=>"星期五"), 
		array("name"=>"saturday", "value"=>"星期六"));

	$index = 0;
	$temp_array = array();
	foreach ($data_array as $row) {
		if ($row["name"] == strtolower(date("l"))) {
			break;
		} else {
			$temp_array[] = $row;
			$index++;
		}
	}
	
	$sorted_array = array();
	for ($i = $index; $i < 7; $i++)
		$sorted_array[] = $data_array[$i];

	foreach ($temp_array as $item)
		$sorted_array[] = $item;

	$sorted_array[] = array("name"=>"unspecified", "value"=>"未设定");

	return $sorted_array;
}

// 每天执行一次的数据清理操作，必须首先执行
function current_notes_data_clean_everyday($userid){
    $conn = new mysqli('dmn-01.hichina.com', 'dmn011620', 'd4g8e1s6j7', 'dmn011620_db');
	$conn->query("set names 'gbk'");
	$query = "select (LastAutoTaskExecuteTime is null || LastAutoTaskExecuteTime < curdate()) as needclean from piggynote_authorised_users where username='".$userid."'";
	$result = @$conn->query($query);

	if (!$result)
		echo 'error';

	$result_array = array();
   for ($count=0; $row = $result->fetch_assoc(); $count++) {
     $result_array[$count] = $row;
   }

   echo 'result = ';
   echo ($result_array[0]['needclean'] == '1');
}

?>