<?php

	require_once('note_sc_fns.php');

	do_html_header();

?>
	<style type="text/css">
		form * { font-family: Verdana; font-size: 100%; }
		#email #username #passwd #passwd2 { width:200px; }
		label { width: 10em; float: left; }
		label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
		p { clear: both; }
		.submit { margin-left: 5em; }
		em { font-weight: bold; padding-right: 1em; vertical-align: top; }
	</style>

	<script type="text/javascript">

		$(document).ready(function () {
			// validate signup form on keyup and submit
			$("#registerform").validate({
				rules: {
					username: {
						required: true,
						minlength: 2
					},
					passwd: {
						required: true,
						minlength: 5,
						maxlength:16
					},
					passwd2: {
						required: true,
						minlength: 5,
						maxlength:16,
						equalTo: "#passwd"
					},
					email: {
						required: true,
						email: true
					}
				},
				messages: {
					username: {
						required: "����д�û���",
						minlength: "�û����������2���ַ�"
					},
					passwd: {
						required: "�������û�����",
						minlength: "���볤�ȱ������5��С��16���ַ�"
					},
					passwd2: {
						required: "���ٴ������û�����",
						minlength: "���볤�ȱ������5��С��16���ַ�",
						equalTo: "��������������ͬ������"
					},
					email: "������Ϸ��������ַ"
				}
			});
		});

	</script>

<?php

	display_registration_form();

	do_html_footer();

?>