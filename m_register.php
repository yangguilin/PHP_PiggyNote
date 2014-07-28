<?php
  // include function files for this application
  require_once('note_sc_fns.php');

  extract($_POST);

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
	
	// check username exist
	if (check_username_exist($username)){
		echo "exist";
		exit;
	}

    // attempt to register
    // this function can also throw an exception
    register($username, $email, $passwd);
	// add default financial category list to db.
	save_def_category_data_to_db($username);

	echo "success";
	exit;
  }
  catch (Exception $e) {
     echo "fail";
	 exit;
  }
?>
