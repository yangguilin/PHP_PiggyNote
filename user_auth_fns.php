<?php

require_once('db_fns.php');

// ����Ƿ�Ϊ�Ϸ��û��������δ��¼����ת����¼ҳ��
function check_valid_user(){
	session_start();
	if (!isset($_SESSION["valid_user"]) || !isset($_SESSION["current_username"])){
		unset($_SESSION["valid_user"]);
		unset($_SESSION["current_username"]);
		header("location:login.php");
	}
}

// �û���¼
function user_login($username, $password) {
// check username and password with db
// if yes, return true
// else throw exception

  // connect to db
  $conn = db_connect();
  $conn->query("set names 'gbk'"); 

  // check if username is unique
  $result = $conn->query("select id from piggynote_authorised_users where username = '".$username."' and password = sha1('".$password."')");
  if (!$result) {
     throw new Exception('���ݿ��ѯʧ��.');
  }

  return $result->num_rows > 0;
}

// �û��ǳ�
function user_logout(){
	session_start();
	unset($_SESSION['valid_user']);
	unset($_SESSION['current_username']);
	session_destroy();
	header("location:login.php");
}
?>