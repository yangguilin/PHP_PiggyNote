
<?php

	include("note_sc_fns.php");

	// ����û��Ϸ���
	check_valid_user();

	// ��ȡURL��Я����ҳ�������Ŀǰ��֧�ֿ��ٷ������
	extract($_POST);

	// ��ʾҳͷ
	do_html_header("index");

	// ��ʾ���������־��
	display_current_note_form($_SESSION["current_username"]); 

	// ���մ������б�
	display_today_notes($_SESSION["current_username"], $quick_category);

	// ����������б�
	display_today_finished_notes($_SESSION["current_username"]);

	// ��ʾ�Ҳ������б�
	display_unplan_and_comming_notes($_SESSION["current_username"]);
	
	// ҳ��ײ�
	do_html_footer("index");
?>

