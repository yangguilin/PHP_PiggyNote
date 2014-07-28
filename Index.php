
<?php

	include("note_sc_fns.php");

	// 检查用户合法性
	check_valid_user();

	// 获取URL中携带的页面参数：目前仅支持快速分类参数
	extract($_POST);

	// 显示页头
	do_html_header("index");

	// 显示快速添加日志表单
	display_current_note_form($_SESSION["current_username"]); 

	// 今日待处理列表
	display_today_notes($_SESSION["current_username"], $quick_category);

	// 今日已完成列表
	display_today_finished_notes($_SESSION["current_username"]);

	// 显示右侧两个列表
	display_unplan_and_comming_notes($_SESSION["current_username"]);
	
	// 页面底部
	do_html_footer("index");
?>

