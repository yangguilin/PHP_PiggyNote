
<?php

	include("note_sc_fns.php");

	session_start();

	// ����û��Ϸ���
	check_valid_user();

	// ��ʾҳͷ
	do_html_header("friend_note_management_page");
	
	// ��ʾ�����������������
	display_friend_notes_table($_SESSION["current_username"]);

	// ҳ��ײ�
	do_html_footer();

?>