<?php

function db_connect() {
   $result = new mysqli('dmn-01.hichina.com', 'dmn011620', 'd4g8e1s6j7', 'dmn011620_db');
   if (!$result) {
     throw new Exception('Could not connect to database server');
   } else {
     return $result;
   }
}

function db_result_to_array($result) {
   $res_array = array();

   for ($count=0; $row = $result->fetch_assoc(); $count++) {
     $res_array[$count] = $row;
   }

   return $res_array;
}

// ִ�в�ѯ���ݲ����������ؽ����������
function db_execute_query($query){
   $conn = db_connect();
   $conn->query("set names 'gbk'"); 
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }

   return db_result_to_array($result);
}

// ִ�зǲ�ѯ���ݿ����
function db_execute($query){
	$conn = db_connect();
	$conn->query("set names 'gbk'"); 
	if ($conn->query($query))
		return true;
	else
		return false;
}

/**************************************************************
 *
 *	������ת��ΪJSON�ַ������������ģ�
 *	@param	array	$array		Ҫת��������
 *	@return string		ת���õ���json�ַ���
 *	@access public
 *
 *************************************************************/
function JSON($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);
	return urldecode($json);
}

/**************************************************************
 *
 *	ʹ���ض�function������������Ԫ��������
 *	@param	string	&$array		Ҫ������ַ���
 *	@param	string	$function	Ҫִ�еĺ���
 *	@return boolean	$apply_to_keys_also		�Ƿ�ҲӦ�õ�key��
 *	@access public
 *
 *************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}
 
		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}
		}
	}
	$recursive_counter--;
}

?>
