
<?php

	function db_result_to_array($result) {
	   $res_array = array();

	   for ($count=0; $row = $result->fetch_assoc(); $count++) {
		 $res_array[$count] = $row;
	   }

	   return $res_array;
	}

   echo '<div>OK，让我们看看中文字体显示情况！</div>';

   $conn = new mysqli('dmn-01.hichina.com', 'dmn011620', 'd4g8e1s6j7', 'dmn011620_db');

	$conn->query("set names 'gbk'"); 

   $query = "SELECT categorydata FROM piggynote_financial_category WHERE userid='ygl123'";
   $result = @$conn->query($query);
   if (!$result) {
     return false;
   }

   $result = db_result_to_array($result);
   
   foreach ($result as $row)
   {
	echo '<div>';
	echo $row['categorydata'];
	echo '</div>';
   }
?>