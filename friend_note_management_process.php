<?php

	include("note_sc_fns.php");

	extract($_POST);

	if (!$operation_type) {
		echo 'No operation type.';
	}else{
		// �������
		if ($item_id == null || $status_value == null)
			echo '��������';

		// ���ݲ������ͻ�ȡ��ѯ���
		$query = "";
		switch (strtolower($operation_type))
		{
			case "add":
			{
				// $query = "insert into piggynote_friendnotes(id, userid, friendid, status, lastmodifiedtime) values('', '".$user_name."', '".$friend_name."', 0, now())";
				break;
			}
			case "update":
			{
				// $query = "update piggynote_friendnotes set status = ".$status_value." where userid = '".$user_name."' and friendid = '".$friend_name."'";
				break;
			}
			case "delete":
			{
				$query = "delete from piggynote_friendnotes where id = '".$item_id."' and status in (2, 3)";
				break;
			}
		}
		// ִ��
		if (db_execute($query))
			echo 'success';
		else
			echo 'fail';
	}

?>