
<?php

	include("note_sc_fns.php");

	// ����û��Ϸ���
	check_valid_user();

	// ��ȡURL��Я����ҳ�������Ŀǰ��֧�ֿ��ٷ������
	extract($_POST);

	// ��ʾҳͷ
	do_html_header("friend_management_page");

	// ��ʾ�ҵĺ����б�
	display_my_friend_list($_SESSION["current_username"]);

	// ��ʾ������Ա�б�������Ӻ��ѣ�
	display_other_people_list($_SESSION["current_username"]);

	// ҳ��ײ�
	do_html_footer();

?>