
<?php

	include("note_sc_fns.php");

	session_start();

	// 检查用户合法性
	check_valid_user();

	// 显示页头
	do_html_header("friend_note_management_page");
	
	// 显示好友提醒事项的内容
	display_friend_notes_table($_SESSION["current_username"]);

	// 页面底部
	do_html_footer();

?>