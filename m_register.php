<?php
  // include function files for this application
  require_once('note_sc_fns.php');

  extract($_POST);

  try   {
    // check forms filled in
    if (!filled_out($_POST)) {
      throw new Exception('����д��ע����Ϣ����������������д��');
    }

    // email address not valid
    if (!valid_email($email)) {
      throw new Exception('���������ʽ����ȷ����������д��');
    }

    // passwords not the same
    if ($passwd != $passwd2) {
      throw new Exception('�����������벻ƥ�䣡��������д��');
    }

    // check password length is ok
    // ok if username truncates, but passwords will get
    // munged if they are too long.
    if ((strlen($passwd) < 6) || (strlen($passwd) > 16)) {
      throw new Exception('���볤��Ҫ����6��С��16���ַ�����������д��');
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
