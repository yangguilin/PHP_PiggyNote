<?php
  // include function files for this application
  require_once('note_sc_fns.php');

  //create short variable names
  $email=$_POST['email'];
  $username=$_POST['username'];
  $passwd=$_POST['passwd'];
  $passwd2=$_POST['passwd2'];
  // start session which may be needed later
  // start it now because it must go before headers
  session_start();
  try   {
    // check forms filled in
    if (!filled_out($_POST)) {
      throw new Exception('您填写的注册信息不完整，请重新填写！');
    }

    // email address not valid
    if (!valid_email($email)) {
      throw new Exception('电子邮箱格式不正确！请重新填写！');
    }

    // passwords not the same
    if ($passwd != $passwd2) {
      throw new Exception('两次密码输入不匹配！请重新填写！');
    }

    // check password length is ok
    // ok if username truncates, but passwords will get
    // munged if they are too long.
    if ((strlen($passwd) < 6) || (strlen($passwd) > 16)) {
      throw new Exception('密码长度要大于6且小于16个字符！请重新填写！');
    }

    // attempt to register
    // this function can also throw an exception
    register($username, $email, $passwd);
	// add default financial category list to db.
	save_def_category_data_to_db($username);
    // register session variable
    $_SESSION['current_username'] = $username;
	$_SESSION['valid_user'] = true;

    // provide link to members page
	display_register_success_message();

   // end page
   // do_html_footer();
  }
  catch (Exception $e) {
	 display_registration_form($e->getMessage());
     exit;
  }
?>
