<?php

	include("note_sc_fns.php");
	
	// Extract the post values.
	extract($_POST);

	if (!$op_type) {
		echo 'operation type error';
	}else{
		$conn = db_connect();
		$conn->query("set names 'gbk'"); 

		if (!$userid || !$stat_type)
			echo 'parameters error.';

		$statData = "";
		$updateRet = "";
		switch ($op_type)
		{
			case "query":
			{
				if ($stat_type == "curmonth")
					$statData = GetCurMonthStatData($userid);
				else if ($stat_type == "anymonth"){
					if ($filt_tpe && $filt_tpe == "bigdata")
						$statData = GetStatDataByMonthFiltBigData($userid);
					else if ($filt_tpe && $filt_tpe == "daily")
						$statData = GetStatDataByMonthByDailyStype($userid);
					else
						$statData = GetStatDataByMonth($userid);
				}
				else if ($stat_type == "category"){
					if ($filt_tpe && $filt_tpe == "bigdata")
						$statData = GetStatDataByCategoryFiltBigData($userid);
					else
						$statData = GetStatDataByCategory($userid);
				}
				else if ($stat_type == "bigdata")
					$statData = GetStatDataByBigData($userid);

				break;
			}
			case "querydetail":
			{
				if ($stat_type == "category" && $category)
					$statData = GetRecordsByCategory($userid, $category);
				else if ($stat_type == "yearmonth" && $yearmonth)
					$statData = GetRecordsByYearMonth($userid, $yearmonth);
				else if ($stat_type == "curmonthcategory" && $yearmonth && $category)
					$statData = GetRecordsByCurMonthCategory($userid, $yearmonth, $category);
				else if ($stat_type == "bigdata")
					$statData = GetRecordsByBigData($userid);
				else if ($stat_type == "stagedata")
					$statData = GetRecordsByStageData($userid);

				break;
			}
			case "updateitem":
			{
				if (!$noteid || !$newcategory || !$newstattype)
					echo 'parameters error.';
				
				if ($stat_type == "update")
					$updateRet = UpdateNoteItem($userid, $noteid, $newcategory, $newstattype);

				break;
			}
		}
		
		// ����JSON��ʽ�ַ���
		if ($statData && !$updateRet)
			echo JSON($statData);
		else if (!$statData && $updateRet)
			echo $updateRet;
		else
			echo "fail";
	}


	//
	// �ڲ�������
	//

	// ��ȡ���µ�ͳ������
	function GetCurMonthStatData($userid){
		$query = "SELECT category, COUNT(id) as num, ROUND(SUM(amount), 0) as total FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND YEAR(createtime)=YEAR(now()) AND MONTH(createtime) = MONTH(now()) GROUP BY category ORDER BY total DESC";
		
		return db_execute_query($query);
	}

	// �����·�ͳ������
	function GetStatDataByMonth($userid){
		$query = "select ROUND(sum(amount), 0) as total, moneytype, EXTRACT(YEAR_MONTH FROM createtime) as yearmonth from `piggynote_financial_dailynote` where userid='".$userid."' group by yearmonth, moneytype ORDER BY yearmonth DESC";

		return db_execute_query($query);
	}

	// �����·�ͳ�����ݣ����˴�������
	function GetStatDataByMonthFiltBigData($userid){
		$query = "select ROUND(sum(amount), 0) as total, moneytype, EXTRACT(YEAR_MONTH FROM createtime) as yearmonth from `piggynote_financial_dailynote` where userid='".$userid."' AND stattype<3 group by yearmonth, moneytype ORDER BY yearmonth DESC";

		return db_execute_query($query);
	}

	// ������ͳ������
	function GetStatDataByCategory($userid){
		$query = "SELECT category, COUNT(id) as num, ROUND(SUM(amount), 0) as total FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' GROUP BY category ORDER BY num DESC";

		return db_execute_query($query);
	}

	// ������ͳ�����ݣ����˴�������
	function GetStatDataByCategoryFiltBigData($userid){
		$query = "SELECT category, COUNT(id) as num, ROUND(SUM(amount), 0) as total FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND stattype<3 GROUP BY category ORDER BY num DESC";

		return db_execute_query($query);
	}

	// ���·�ͳ�����ݣ����˴�����ݺͽ׶����ݣ�
	function GetStatDataByMonthFiltBigDataAndStage($userid){
		$query = "select ROUND(sum(amount), 0) as total, moneytype, EXTRACT(YEAR_MONTH FROM createtime) as yearmonth from `piggynote_financial_dailynote` where userid='".$userid."' AND stattype=1 group by yearmonth, moneytype ORDER BY yearmonth DESC";

		return db_execute_query($query);
	}

	// ������ͳ�����ݣ��ճ�����ģʽ�������˴�������ƽ���׶���֧���
	function GetStatDataByMonthByDailyStype($userid){
		$query_data = GetStatDataByMonthFiltBigDataAndStage($userid);
		$cur_user_stage_total = GetCurUserStageData($userid);
		
		// ��ȡ��¼�·���
		$month_num = 0;
		for ($i = 0; $i < count($query_data); $i++){
			if ($query_data[$i]["moneytype"] == "cost")
				$month_num++;
		}
		
		// ��ȡ��ƽ���׶�������ֵ
		$avg_stage_value = round(($cur_user_stage_total / $month_num), 0);

		// Ϊ�ճ�������ӽ׶�����
		for ($i=0;$i<count($query_data);$i++){
			if ($query_data[$i]["moneytype"] == "cost"){
				$query_data[$i]["total"] = strval(intval($query_data[$i]["total"]) + $avg_stage_value);
			}
		}

		return $query_data;
	}

	// ��ȡ��ǰ�û������н׶���֧���ܶ�
	function GetCurUserStageData($userid){
		$query = "SELECT SUM(amount) as total FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND stattype=2";
		$ret = db_execute_query($query);
		return intval($ret[0]["total"]);
	}

	// ���������ͳ������
	function GetStatDataByBigData($userid){
		$query = "SELECT COUNT(id) as num, ROUND(SUM(amount), 0) as total FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' and stattype>1 GROUP BY stattype ORDER BY stattype DESC";

		return db_execute_query($query);
	}

	// ��������ȡ��ϸ��¼
	function GetRecordsByCategory($userid, $category){
		$query = "SELECT id, moneytype, amount, category, remark, createtime, stattype FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND category=".$category." ORDER BY createtime DESC";
		return db_execute_query($query);
	}

	// �������»�ȡ��ϸ��¼
	function GetRecordsByYearMonth($userid, $yearmonth){
		$query = "SELECT id, moneytype, amount, category, remark, createtime, stattype FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND EXTRACT(YEAR_MONTH FROM createtime) = '".$yearmonth."' ORDER BY createtime DESC";
		return db_execute_query($query);
	}

	// �������¼������ȡ��ϸ����
	function GetRecordsByCurMonthCategory($userid, $yearmonth, $category){
		$query = "SELECT id, moneytype, amount, category, remark, createtime, stattype FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND EXTRACT(YEAR_MONTH FROM createtime) = '".$yearmonth."' AND category=".$category." ORDER BY createtime DESC";
		return db_execute_query($query);
	}

	// ��������ͳ�����ͻ�ȡ��ϸ���ݣ��������
	function GetRecordsByBigData($userid){
		$query = "SELECT id, moneytype, amount, category, remark, createtime, stattype FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND stattype=3 ORDER BY createtime DESC";
		return db_execute_query($query);
	}

	// ��������ͳ�����ͻ�ȡ��ϸ���ݣ��׶�����
	function GetRecordsByStageData($userid){
		$query = "SELECT id, moneytype, amount, category, remark, createtime, stattype FROM `piggynote_financial_dailynote` WHERE userid='".$userid."' AND stattype=2 ORDER BY createtime DESC";
		return db_execute_query($query);
	}

	// ���¼�¼����
	function UpdateNoteItem($userid, $noteid, $newcategory, $newstattype){
		$query = "UPDATE `piggynote_financial_dailynote` SET category=".$newcategory.", stattype=".$newstattype." WHERE id='".$noteid."' AND userid='".$userid."'";
		if (db_execute($query))
			return "success";
		else
			return "fail";
	}
?>