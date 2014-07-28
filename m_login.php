<?php

	require_once("note_sc_fns.php");

	extract($_POST);
	if (isset($_POST["name"]) && isset($_POST["password"])){
		if (user_login($_POST["name"], $_POST["password"])){
			echo "success";
		} else {
			echo "failure";
		}
	} else {
		echo "failure";
	}
	
?>