
<?php

	include("note_sc_fns.php");

	// 检查用户合法性
	check_valid_user();

	// 获取URL中携带的页面参数：目前仅支持快速分类参数
	extract($_POST);

	// 显示页头
	do_html_header("friend_management_page");

	// 显示我的好友列表
	display_my_friend_list($_SESSION["current_username"]);

	// 显示其他人员列表（可以添加好友）
	display_other_people_list($_SESSION["current_username"]);

	// 页面底部
	do_html_footer();

?>