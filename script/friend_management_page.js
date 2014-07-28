
//
// 此脚本文件为“好友管理”页面专用脚本文件
// 杨桂林 - 20120711
// 

	// 申请添加好友
	function manage_friend_status(obj, op_type){
		if (obj == null || op_type == null)	
			return false;
		
		$user_name = $(obj).attr("user_name");
		$friend_name = $(obj).attr("friend_name");
		
		// 按操作类型设置状态值
		$status_value = -1;
		if (op_type == "reject")
		{
			$status_value = 1;
			op_type = "update";
		}
		else if (op_type == "agree")
		{
			$status_value = 2;
			op_type = "update";
		}


		$.post("friend_management_process.php", 
			{"user_name":$user_name, "friend_name":$friend_name, "status_value":$status_value, "operation_type":op_type}, 
			function(data){
				if (data.toLowerCase() == "success")
					window.location = "friend_management_page.php";
				else
				  alert("更新好友关系状态失败，请重试");
			  }
		);
	}
	
	