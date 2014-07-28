
<?php

  header("Content-Type: text/html; charset=gb2312");

	function db_result_to_array($result) {
	   $res_array = array();

	   for ($count=0; $row = $result->fetch_assoc(); $count++) {
		 $res_array[$count] = $row;
	   }

	   return $res_array;
	}

   echo '<div>OK�������ǿ�������������ʾ�����</div>';

   $conn = new mysqli('dmn-01.hichina.com', 'dmn011620', 'd4g8e1s6j7', 'dmn011620_db');

	$conn->query("set names 'gbk'"); 

   $query = "select * from piggynote_financial_dailynote where userid = 'yanggl' and createtime = curdate()";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }

   $result = db_result_to_array($result);
   
   // echo JSON($result);

   foreach ($result as $row)
	echo JSON($row);
	
?>

<?php
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

?>
