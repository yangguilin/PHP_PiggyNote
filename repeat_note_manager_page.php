
<?php

	include("note_sc_fns.php");

	session_start();

	// ����û��Ϸ���
	check_valid_user();

	// ��ʾҳͷ
	do_html_header("repeat_note_manager_page");
	
	// ��ʾ�ظ����������
	display_repeat_notes_table($_SESSION["current_username"]);

	// ҳ��ײ�
	do_html_footer();

?>