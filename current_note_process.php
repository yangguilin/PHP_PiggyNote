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
				if (!$noteCategory || !$noteLevel || !$noteRepeatType || !$noteTargetWeekday || !$noteOwner || !$offerUserid || !$noteTitle)
					echo 'The post values have some mistake.';

				// Get target date by target weekday.
				$target_date = get_date_by_weekday($noteTargetWeekday);

				// Insert data into db. Deal with note by which type of repeat style.
				$query = "";
				if (strtolower($noteRepeatType) == "unspecified") {
					if ($noteOwner == $offerUserid){
						$query = "INSERT INTO piggynote_currentnotes
							VALUES('', '".$noteTitle."', '".$noteDetail."', '".$noteCategory."', '".$noteLevel."', '".$target_date."', '".$noteTargetWeekday."', 
							'".$noteRepeatType."', '".$noteOwner."', '".$offerUserid."', now(), null, 0)";
					}else{ // when user offer a note to his friend.
						$query = "insert into piggynote_friendnotes values('', '".$noteTitle."', '".$noteDetail."', '".$noteCategory."', '".$noteLevel."', '".$noteOwner."', '".$offerUserid."', 0, now(), null)";
					}
				} else {
					if (strtolower($noteRepeatType) == "everyday")
						$noteTargetWeekday = "";

					$query = "INSERT INTO piggynote_repeatnotes
						VALUES('', '".$noteTitle."', '".$noteDetail."', '".$noteCategory."', '".$noteLevel."', '".$noteTargetWeekday."', '".$noteRepeatType."', '".$noteOwner."', '".$offerUserid."', '0000-00-00 00:00:00', now(), null, 1)";
				}
				
	
				// Execute query.
				if (!$conn->query($query))
					echo 'Insert new note fail.';

				// Redirect to index.php.
				header("location:index.php");
				break;
			}
			case "delete":
			{
				if ($id) {
					$query = "DELETE FROM piggynote_currentnotes WHERE id=".$id."";
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break;
			}
			case "finish":
			{
				if ($id) {
					$query = "UPDATE piggynote_currentnotes SET closetime=now(), finished=1 WHERE id=".$id;
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break;
			}
			case "do_it_today":
			{
				if ($id) {
					$query = "UPDATE piggynote_currentnotes SET targetdate = curdate(), targetweekday = dayname(curdate()) WHERE id=".$id;
					if ($conn->query($query))
						echo("success");
					else
						echo("fail");
				}
				break; 
			}
			case "cancel_finished":
			{
				if ($id){
					$query = "UPDATE piggynote_currentnotes SET closetime = null, finished = 0 WHERE id=".$id;
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