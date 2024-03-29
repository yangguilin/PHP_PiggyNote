<?php

	include("note_sc_fns.php");
	

	// Extract the post values.
	extract($_POST);

	if (!$operation_type) {
		echo 'No operation type.';
	}else{
		// New connect.
		$conn = db_connect();
		// Set encode type.
		$conn->query("set names 'gbk'"); 

		switch ($operation_type)
		{
			case "add":
			{
				// Check variables.
				if (!$user_name || !$_amount || !$_datetime || !$_category || !$_type || !$_stattype)
				{
					echo 'The post values have some mistake.';
				} else {
					$_remark = iconv("UTF-8","GBK",$_remark);
					$query = "INSERT INTO piggynote_financial_dailynote
						VALUES('', '".$user_name."', '".$_type."', '".$_amount."', '".$_category."', '".$_remark."', '".$_datetime."', now(), ".$_stattype.")";
					
					// Execute query.
					if ($conn->query($query))
							echo("success");
						else
							echo("fail");
				}
				break;
			}
			case "delete":
			{
				if ($id) {
					$query = "DELETE FROM piggynote_financial_dailynote WHERE id=".$id."";
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break;
			}
			case "update":
			{
				if ($id) {
					$query = "";
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break;
			}
			case "get_today_notes":
			{
				$notes = get_today_financial_notes($user_name, $date_index);
				echo JSON($notes);
				break;
			}
			case "get_user_summary_data":
			{
				$total_income = get_user_total_amount($user_name, "income");
				$total_cost = get_user_total_amount($user_name, "cost");
				$cur_month_income = get_current_month_total_amount($user_name, "income");
				$cur_month_cost = get_current_month_total_amount($user_name, "cost");

				$res_data = sprintf("%.2f", $total_income).",".sprintf("%.2f", $total_cost).",".sprintf("%.2f", $cur_month_income).",".sprintf("%.2f", $cur_month_cost);
				echo $res_data;
				break;
			}
			case "get_user_category_data":
			{
				$cur_category_str = get_category_data_by_userid($user_name);
				if ($cur_category_str == null){ // 如果查询没有该用户的分类数据，则插入默认数据后再查一遍
					save_def_category_data_to_db($user_name);
					$cur_category_str = get_category_data_by_userid($user_name);
				}
				$category_data = get_category_data_for_js($cur_category_str);
				$category_data_json = JSON($category_data);
				// 将分类信息保存到session中
				SaveUserCategoryDataToSession($category_data_json);
				echo $category_data_json;
				break;
			}
			case "update_category_item":
			{
				if (update_category_item($user_name, $_ctgid, $_ctgName, $_ctgDes))
					echo("success");
				else
					echo("fail");

				break;
			}
			case "get_user_income_data":
			{
				$notes = get_user_income_notes($user_name, 0);
				echo JSON($notes);
				break;
			}
			case "get_user_month_cost_plan":
			{
				echo get_user_month_cost_plan($user_name);
				break;
			}
			case "update_month_cost_plan":
			{
				if ($newPlanVal == null)
				{
					echo "Need newPlanVal.";
				}

				if (update_user_month_cost_plan($user_name, $newPlanVal) == true)
				{
					echo "success";
				}
				break;
			}
			case "add_new_category":
			{
				if (!$user_name || !$operation_type || !$_moneytype || !$_ctgname || !$_ctgdes){
					echo 'The post values have some mistake.';
				} else {
					echo add_new_category_item($user_name, $_moneytype, $_ctgname, $_ctgdes); 
				}
				break;
			}
			case "query_user_note_max_id":
			{
				if (!$user_name || !$operation_type){
					echo 'The post values have some mistake.';
				} else {
					echo GetUserNoteMaxId($user_name);
				}
				break;
			}
			case "get_latest_month_notes":
			{
				$notes = get_latest_month_notes($user_name);
				echo JSON($notes);
				break;
			}
		}
	}

	// 获取用户记录最大的id值（即最新记录的id）
	function GetUserNoteMaxId($user_name){
	   $query = "SELECT MAX(id) AS maxid FROM piggynote_financial_dailynote WHERE userid='".$user_name."'";
	   $result = db_execute_query($query);
	   if (!$result) {
		 return false;
	   }
	   
	   return $result[0]["maxid"];
	}

	// 将分类信息保存到session中
	function SaveUserCategoryDataToSession($category_data_json){
		session_start();
		$_SESSION["user_category_data"] = $category_data_json;
	}

	// update month cost plan value.
	function update_user_month_cost_plan($userid, $newVal)
	{
		$query = "UPDATE piggynote_financial_config SET monthcostplan = ".$newVal;
		if (db_execute($query) == false)
		{
			echo "Update month cost plan value fail.";
			exit();
		}

		return true;
	}

	// 获取用户月消费计划
	function get_user_month_cost_plan($userid)
	{
		$month_cost_plan = 1000;

		$query = "SELECT monthcostplan FROM piggynote_financial_config WHERE userid='".$userid."'";
		$result = db_execute_query($query);
		if (count($result) > 0)
		{
			$month_cost_plan = $result[0]["monthcostplan"];
		}
		else
		{
			insert_user_default_config($userid, $month_cost_plan);
		}

		if ($month_cost_plan == null)
		{
			echo "prepare to insert default";
			// insert
			insert_user_default_config($userid, $month_cost_plan);
		}

		echo $month_cost_plan;
	}

	// Insert default config data for user
	function insert_user_default_config($userid, $defaultVal)
	{
		$query = "INSERT INTO piggynote_financial_config(userid, monthcostplan) VALUES('".$userid."', ".$defaultVal.")";
		if (!db_execute($query))
		{
			echo "Insert default config data fail";
			exit();
		}
	}

	// get user total amount by type
	function get_user_total_amount($userid, $money_type){
	   $query = "SELECT IFNULL(sum(amount), 0) as total_amount FROM piggynote_financial_dailynote WHERE userid='".$userid."' AND moneytype='".$money_type."'";
	   $result = db_execute_query($query);
	   if (!$result) {
		 return false;
	   }

	   return $result[0]["total_amount"];
	}

   // Get today's notes.
   function get_today_financial_notes($userid, $date_index) {
	   $conn = db_connect();
	   $conn->query("set names 'gbk'"); 
	   $query = "select * from piggynote_financial_dailynote where userid = '".$userid."' and createtime = date_sub(curdate(), interval '".$date_index."' day)";
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

   // Get latest month notes.
   function get_latest_month_notes($userid) {
	   $conn = db_connect();
	   $conn->query("set names 'gbk'"); 
	   $query = "select * from piggynote_financial_dailynote where userid = '".$userid."' and createtime > date_sub(curdate(), interval '30' day) order by id desc";
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

    // 获取用户收入记录数据
	function get_user_income_notes($userid, $date_index)
	{
		$query = "SELECT * FROM piggynote_financial_dailynote WHERE userid = '".$userid."' AND moneytype='income' AND createtime < date_sub(curdate(), interval '".$date_index."' day)";
		$result = db_execute_query($query);
		if (!$result)
			return false;

		return $result;
	}

	// Get current month total amount by money type
    function get_current_month_total_amount($userid, $money_type){
	   $query = "SELECT IFNULL(sum(amount), 0) as total_amount FROM piggynote_financial_dailynote WHERE userid='".$userid."' AND year(createtime)=year(now()) AND month(createtime)=month(now()) AND moneytype='".$money_type."'";
	   $result = db_execute_query($query);
	   if (!$result) {
		 return false;
	   }
	   
	   return $result[0]["total_amount"];
	}
	 
?>