<?php

	include("note_sc_fns.php");

	extract($_POST);

	// ��post���ݽ��и�ʽת��
	$user_name = iconv("UTF-8", "gbk", $user_name);
	$friend_name = iconv("UTF-8", "gbk", $friend_name);

	if (!$operation_type) {
		echo 'No operation type.';
	}else{
		// �������
		if ($user_name == null || $friend_name == null || $status_value == null)
			echo '��������';

		// ���ݲ������ͻ�ȡ��ѯ���
		$query = "";
		switch (strtolower($operation_type))
		{
			case "add":
			{
				$query = "insert into piggynote_relationships(id, userid, friendid, status, lastmodifiedtime) values('', '".$user_name."', '".$friend_name."', 0, now())";
				break;
			}
			case "update":
			{
				$query = "update piggynote_relationships set status = ".$status_value." where userid = '".$user_name."' and friendid = '".$friend_name."'";
				break;
			}
			case "delete":
			{
				$query = "delete from piggynote_relationships where userid = '".$user_name."' and friendid = '".$friend_name."'";
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