
//
// �˽ű��ļ�Ϊ�����ѹ���ҳ��ר�ýű��ļ�
// ����� - 20120711
// 

	// ������Ӻ���
	function manage_friend_status(obj, op_type){
		if (obj == null || op_type == null)	
			return false;
		
		$user_name = $(obj).attr("user_name");
		$friend_name = $(obj).attr("friend_name");
		
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


		$.post("friend_management_process.php", 
			{"user_name":$user_name, "friend_name":$friend_name, "status_value":$status_value, "operation_type":op_type}, 
			function(data){
				if (data.toLowerCase() == "success")
					window.location = "friend_management_page.php";
				else
				  alert("���º��ѹ�ϵ״̬ʧ�ܣ�������");
			  }
		);
	}
	
	