
//
// �˽ű��ļ�Ϊ�������ṩ��ǩ���ݹ���ҳ��ר�ýű��ļ�
// ����� - 20120726
// 

	// ���º��ѱ�ǩ��״̬
	function update_friend_note_status(obj, op_type){
		if (obj == null || op_type == null)	
			return false;
		
		$item_id = $(obj).attr("item_id");
		
		// ��������������״ֵ̬
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
				  alert("���º��ѱ�ǩ״̬ʧ�ܣ�������");
			  }
		);
	}
	
	