<?php 

// Get today's notes.
function get_current_notes($userid, $category) {

   if ((!$userid) || ($userid == '')) {
     return false;
   }

   // 添加分类条件
   $query_category_part = "";
   if ($category && $category != "all")
     $query_category_part = " and category='".$category."' ";

   // 每天执行一次的数据清理操作，必须首先执行
   current_notes_data_clean_everyday($userid);

   // restore unfinished notes to be unplaned.
   restore_unfinished_notes_to_unplaned($userid);

   // Check repeat note and add them into current notes database table when it's time.
   add_repeat_note_into_currentnotes($userid);

   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "select * from piggynote_currentnotes where owneruserid = '".$userid."'".$query_category_part." and finished = 0 and targetdate = curdate() and closetime is null order by createtime desc";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }
   $num_current_notes = @$result->num_rows;
   if ($num_current_notes == 0) {
      return false;
   }
   $result = db_result_to_array($result);
   return $result;
}

// Get today finished notes.
function get_today_finished_notes($userid) {
   if ((!$userid) || ($userid == '')) {
     return false;
   }

   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "select * from piggynote_currentnotes where owneruserid = '".$userid."' and targetdate = curdate() and closetime > curdate() and closetime < date_add(curdate(), interval 1 day) and finished = 1";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }
   $num_current_notes = @$result->num_rows;
   if ($num_current_notes == 0) {
      return false;
   }
   $result = db_result_to_array($result);
   return $result;
}

// Get unplan notes.
function get_unplan_notes($userid) {
   if ((!$userid) || ($userid == '')) {
     return false;
   }

   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "select * from piggynote_currentnotes where owneruserid = '".$userid."' and closetime is null and finished = 0 and year(targetdate) = '0000' and targetweekday = 'unspecified' order by createtime desc";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }
   $num_current_notes = @$result->num_rows;
   if ($num_current_notes == 0) {
      return false;
   }
   $result = db_result_to_array($result);
   return $result;
}

// Get comming notes.
function get_comming_notes($userid) {
   if ((!$userid) || ($userid == '')) {
     return false;
   }

   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "select * from piggynote_currentnotes where owneruserid = '".$userid."' and closetime is null and finished = 0 and targetdate > curdate() and targetweekday != 'unspecified' order by targetdate";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }
   $num_current_notes = @$result->num_rows;
   if ($num_current_notes == 0) {
      return false;
   }
   $result = db_result_to_array($result);
   return $result;
}

// Add new note.
function add_new_note($noteTitle, $noteDetail, $noteCategory, $noteLevel, $noteTargetWeekday, $noteRepeatType, $noteOwner) {

}

// 每天执行一次的数据清理操作，必须首先执行
function current_notes_data_clean_everyday($userid){
	if (user_need_clean_data_today($userid)) {
	   // 将已完成的事项转移到数据表finishednotes数据表
	   transfer_notes_to_finished_table($userid);
	   // 清除未完成的重复事项
	   remove_unfinished_repeattype_notes($userid);

	   // 更新用户的LastAutoTaskExecuteTime字段
	   $conn = db_connect();
	   $conn->query("set names 'gbk'");
	   $update_query = "update piggynote_authorised_users set LastAutoTaskExecuteTime = now() where username='".$userid."'";
	   $result = @$conn->query($update_query);
	   if (!$result)
	     return false;
	}
}

// 用户今天是否需要清理数据
function user_need_clean_data_today($userid){
	$conn = db_connect();
	$conn->query("set names 'gbk'");
	$query = "select (LastAutoTaskExecuteTime is null || LastAutoTaskExecuteTime < curdate()) as needclean from piggynote_authorised_users where username='".$userid."'";
	$result = @$conn->query($query);
	if (!$result)
		return false;

	$result_array =  db_result_to_array($result);

	return ($result_array[0]['needclean'] == 1);
}

// 清除未完成的重复事项
function remove_unfinished_repeattype_notes($userid){
	$conn = db_connect();
	$conn->query("set names 'gbk'");
	$delete_query = "delete from piggynote_currentnotes where owneruserid = '".$userid."' and repeattype != 'unspecified' and targetdate < curdate() and finished = 0 and closetime is null";
	$result = @$conn->query($delete_query);
	if (!$result) 
		return false;
}

// Add repeat type notes into current notes table.
function add_repeat_note_into_currentnotes($userid) {
	$conn = db_connect();
	$conn->query("set names 'gbk'");

	// 添加重复事项
	$add_query = "insert into piggynote_currentnotes
					select '', title, details, category, level, curdate(), targetweekday, repeattype, owneruserid, offeruserid, now(), null, 0
					from piggynote_repeatnotes
					where owneruserid = '".$userid."' and isenable = 1 and lastexecutetime < curdate()
					and (repeattype = 'everyday' || (repeattype = 'everyweek' && targetweekday = lower(dayname(curdate()))))";
	$result = @$conn->query($add_query);
	if (!$result)
		return false;

	// 更新重复事项的添加时间
	$add_query = "update piggynote_repeatnotes set lastexecutetime = now()
					where owneruserid = '".$userid."' and isenable = 1 and (lastexecutetime < curdate() || lastexecutetime is null)
					and (repeattype = 'everyday' || (repeattype = 'everyweek' && targetweekday = lower(dayname(curdate()))))";
	$result = @$conn->query($add_query);
	if (!$result)
		return false;
}

// 将已完成的事项转移到数据表finishednotes数据表
function transfer_notes_to_finished_table($userid) {
	$conn = db_connect();
	$conn->query("set names 'gbk'");
	
	// 转移已完成数据
	$transfer_query = "insert into piggynote_finishednotes select '', title, details, category, level, targetweekday, repeattype, owneruserid, offeruserid, createtime, closetime, finished from piggynote_currentnotes where owneruserid = '".$userid."' and closetime < curdate() and finished = 1 and closetime is not null";
	$result = @$conn->query($transfer_query);
	if (!$result) 
		return false;

	// 删除该部分数据
	$delete_query = "delete from piggynote_currentnotes where owneruserid = '".$userid."' and finished = 1 and closetime is not null and closetime < curdate()";
	$result = @$conn->query($delete_query);
	if (!$result)
		return false;
}

// Restore all unfinished notes's status to be unplaned.
function restore_unfinished_notes_to_unplaned($userid){
   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "update piggynote_currentnotes set targetdate='0000-00-00 00:00:00', targetweekday='unspecified' where owneruserid = '".$userid."' and targetdate != '0000-00-00 00:00:00' and targetdate < curdate() and targetweekday != 'unspecified' and repeattype = 'unspecified' and closetime is null and finished = 0";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }
}

// Register a new user.
function register($username, $email, $passwd){
	// Check variables.
	if (!$username || !$email || !$passwd)
		echo 'The post values have some mistake.';

	// New connect.
	$conn = db_connect();
	// Set encode type.
	$conn->query("set names 'gbk'"); 

	// Insert data into db. Deal with note by which type of repeat style.
	$query = "INSERT INTO piggynote_authorised_users(email, username, password) VALUES('".$email."', '".$username."', sha1('".$passwd."'))";

	// Execute query.
	if (!$conn->query($query))
		echo 'Insert new note fail.';
}

// 检查用户名是否存在
function check_username_exist($username){
	$query = "SELECT COUNT(id) as num FROM piggynote_authorised_users WHERE username='".$username."'";
	$result = db_execute_query($query);
   
    if (!$result) {
	  return false;
    }
    
	if ($result[0]["num"] == "0")
		return false;
	else
		return true;
}

// 获取当前用户的重复任务项
function get_current_user_repeat_notes($userid){
   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $query = "select * from piggynote_repeatnotes where owneruserid = '".$userid."'";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }

   return db_result_to_array($result);
}

// 获取除了好友外的所有用户列表
function get_current_all_user_list($userid){
   $query = "select username, email from piggynote_authorised_users where username != '".$userid."' and username not in (select friendid as userid from piggynote_relationships where userid = '".$userid."' union select userid from piggynote_relationships where friendid = '".$userid."')";
   return db_execute_query($query);
}

// 获取当前用户所有好友列表
function get_current_user_friends($userid){
   $query = "select * from piggynote_relationships where userid = '".$userid."' or (friendid = '".$userid."' and status != 1)";
   return db_execute_query($query);
}

// 获取用户好友列表
function get_all_friends_list($userid){
	$query = "select friendid as userid from piggynote_relationships where userid = '".$userid."' and status = 2 union select userid from piggynote_relationships where friendid = '".$userid."' and status = 2;";
	return db_execute_query($query);
}

// 获取当前用户发给好友的便签的列表
function get_current_user_friend_notes($userid){
   $query = "select * from piggynote_friendnotes where offeruserid = '".$userid."' order by createtime desc";
	return db_execute_query($query);
}

//
// Helpful functions.

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
		case "unspecified":
			return "0000-00-00 00:00:00";
	}

	$day_interval = 0;
	if ($wday > $target_wday)
		$day_interval = $target_wday + 7 - $wday;
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

function filled_out($form_vars) {
  // test that each variable has a value
  foreach ($form_vars as $key => $value) {
     if ((!isset($key)) || ($value == '')) {
        return false;
     }
  }
  return true;
}

function valid_email($address) {
  // check an email address is possibly valid
  if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)) {
    return true;
  } else {
    return false;
  }
}

?>