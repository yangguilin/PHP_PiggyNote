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
			case "update":
			{
				// Check variables.
				if ($id == null || $propertyType == null || $newPropertyValue == null)
					echo 'The post values have some mistake.';
				
				// ���ո����ֶε����ͽ������ִ����Ƿ���ַ����ֶ���ӵ�����
				$query_set_part = $propertyType."='".$newPropertyValue."'";
				if ($propertyType == "isenable")
					$query_set_part = $propertyType."=".$newPropertyValue."";

				// Insert data into db. Deal with note by which type of repeat style.
				$query = "update piggynote_repeatnotes set ".$query_set_part." where id = ".$id;
	
				// Execute query.
				if ($conn->query($query))
					echo("success");
				else
					echo("fail");
				break;
			}
			case "delete":
			{
				if ($id) {
					$query = "DELETE FROM piggynote_repeatnotes WHERE id=".$id."";
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break;
			}
		}
	}
?>