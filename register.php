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
						required: "请填写用户名",
						minlength: "用户名必须大于2个字符"
					},
					passwd: {
						required: "请输入用户密码",
						minlength: "密码长度必须大于5且小于16个字符"
					},
					passwd2: {
						required: "请再次输入用户密码",
						minlength: "密码长度必须大于5且小于16个字符",
						equalTo: "请输入与上面相同的密码"
					},
					email: "请输入合法的邮箱地址"
				}
			});
		});

	</script>

<?php

	display_registration_form();

	do_html_footer();

?>