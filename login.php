<?php

	require_once("note_sc_fns.php");

	do_html_header();

	extract($_POST);
	if (isset($_POST["name"]) && isset($_POST["password"])){
		if (user_login($_POST["name"], $_POST["password"])){
			session_start();
			$_SESSION["valid_user"] = true;
			$_SESSION["current_username"] = $_POST["name"];
			header("location:index.php");
		} else {
			display_user_login_form(true);	
		}
	} else {
		display_user_login_form(false);	
	}
	
	// Ò³Ãæµ×²¿
	do_html_footer();
?>