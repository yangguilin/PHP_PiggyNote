<?php

require_once('db_fns.php');

// 检查是否为合法用户，如果尚未登录，跳转到登录页面
function check_valid_user(){
	session_start();
	if (!isset($_SESSION["valid_user"]) || !isset($_SESSION["current_username"])){
		unset($_SESSION["valid_user"]);
		unset($_SESSION["current_username"]);
		header("location:login.php");
	}
}

// 用户登录
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
     throw new Exception('数据库查询失败.');
  }

  return $result->num_rows > 0;
}

// 用户登出
function user_logout(){
	session_start();
	unset($_SESSION['valid_user']);
	unset($_SESSION['current_username']);
	session_destroy();
	header("location:login.php");
}
?>