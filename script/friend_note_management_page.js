
//
// 此脚本文件为“好友提供便签内容管理”页面专用脚本文件
// 杨桂林 - 20120726
// 

	// 更新好友便签的状态
	function update_friend_note_status(obj, op_type){
		if (obj == null || op_type == null)	
			return false;
		
		$item_id = $(obj).attr("item_id");
		
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


		$.post("friend_note_management_process.php", 
			{"item_id":$item_id, "status_value":$status_value, "operation_type":op_type}, 
			function(data){
				if (data.toLowerCase() == "success")
					window.location = "friend_note_management_page.php";
				else
				  alert("更新好友便签状态失败，请重试");
			  }
		);
	}
	
	