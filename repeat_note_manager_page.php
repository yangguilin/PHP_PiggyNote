
<?php

	include("note_sc_fns.php");

	session_start();

	// 检查用户合法性
	check_valid_user();

	// 显示页头
	do_html_header("repeat_note_manager_page");
	
	// 显示重复事项的内容
	display_repeat_notes_table($_SESSION["current_username"]);

	// 页面底部
	do_html_footer();

?>