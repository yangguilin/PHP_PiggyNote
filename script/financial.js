/*
	�˽ű��ļ�ר��Ϊfinancial.phpҳ��׼��
	by yanggl 20121016
*/

	// ҳ��ȫ�ֱ���
	var g_costCategoryArr = ""; // ["��", "ʳ", "ס", "��", "ҽ", "Т", "��", "��", "ѧ", "��", "Ѷ", "��", "��", "һ", "��"];
	var g_incomeCategoryArr = ""; // ["����", "����"];

	$(document).ready(function () {
		// ��ʼ��ҳ��ؼ�������¼�
		initControlsAndBindEvents();
		// ��ȡ�û���������
		getUserCategoryData();
	});

	// ��ʼ��ҳ��ؼ�������¼�
	function initControlsAndBindEvents(){
		// ��������¼�
		$("#txt_money").bind("input propertychange", function () {
			var num = Number($(this).val());
			var disabledVal = (num == null || num <= 0);
			$("#btn_submit").attr("disabled", disabledVal);
		});

		// Ϊ��¼�����ݰ�ť����¼�
		$("#div_latest_items_op_bar>span").bind("click", function () {
			var index = $(this).attr("val");
			if ($("#div_latest_items_op_bar>span[val=" + index + "]").hasClass("selected"))
				return;
			else {
				selectLatestNotesButton($(this).attr("val"));
				$(this).addClass("selected").css("cursor", "default")
					.siblings("span").removeClass("selected").css("cursor", "pointer");
			}
		});	

		// ��ʼ������ѡ��ؼ�
		initDatetimeRelatedControls();

		// ��ʼ���û����ÿ�
		initUserConfigBox();
	}

	// ��ʼ���û����ÿ�Ĭ��ѡ��
	function initUserConfigBox()
	{
		// �������ÿ��¼���
		$("#div_quick_opration_box>h3>span").bind("click", function(){
			$(this).addClass("selected").css("cursor", "default")
				.siblings("span").removeClass("selected").css("cursor", "pointer");
			
			var tagVal = $(this).attr("val");
			if (tagVal != null)
			{
				if (tagVal == "category")
				{
					$("#div_category_modify_box").show();
					$("#div_plan_modify_box").hide();
					InitCategoryConfigBoxHeight("category_config");
				}
				else if (tagVal == "plan")
				{
					$("#div_category_modify_box").hide();
					$("#div_plan_modify_box").show();
					InitCategoryConfigBoxHeight("month_plan");
				}
			}
		});
		// �����Ѽƻ����¼���
		$("#input_month_cost_plan").bind("keyup", function(){
			var newVal = $(this).val();
			$("#btnUpMCP").attr("disabled", (isNaN(newVal) || $(this).attr("oldVal") == newVal));
		});

		// Ĭ��ѡ��������tab
		$("#div_quick_opration_box>h3>span:first").click();
		// ��ȡ�û�Ĭ�������Ѽƻ�
		getUserMonthCostPlan();
	}

	// ��ȡ�û�Ĭ�������Ѽƻ�
	function getUserMonthCostPlan()
	{
		var userid = $("#hidden_cur_userid").val();
		$.get("financial_process.php",
			{"user_name":userid, "operation_type":"get_user_month_cost_plan"},
			function(data){
				if (isNaN(data) == false)
				{
					$("#hide_month_cost_plan_config").val(data);
					$("#input_month_cost_plan").val(data);

					// ��ȡ�û�ͳ��������
					getUserSummaryData();
				}
				else
				{
					alert("Load user month cost plan value fail.");
				}
			});
	}

	// ��ȡ�û���������
	function getUserCategoryData(){
		var userid = $("#hidden_cur_userid").val();
        $.get("financial_process.php", 
			{ "user_name": userid, "operation_type": "get_user_category_data" },
            function (data) {
				if (data == null)
					alert("��ȡ�û�������Ϣʧ��");

				var categoryArr = data.split(";");
				if (categoryArr.length == 2){
					g_costCategoryArr = categoryArr[0].split(",");
					g_incomeCategoryArr = categoryArr[1].split(",");
					// ��ʼ����������
					initCategoryDataList();
					// ��ȡ�û�������Ϣ
					showUserCategoryData();
					// ��ʼ���������������߶�
					InitCategoryConfigBoxHeight("category_config");
					// ��ȡ��������
					$("#div_latest_items_op_bar>span:contains('����')").click();
				}
            }, "json");
	}

	// ��ʼ���������������߶�
	function InitCategoryConfigBoxHeight(tabType){
		var divBoxHeight = 100;
		if (tabType == "category_config")
			divBoxHeight += $("#div_category_modify_box>table tr").length * 27;

		// ���ݷ���ʵ���������������߶�100px��ʼ��ÿ������27px
		$("#div_quick_opration_box").css("height", divBoxHeight);
	}

	// ��ʼ���û���������������ݣ����������¼���Ĭ��ѡ���
	function initCategoryDataList(){
		// �����ϸ����б�
		var allCostCategoryHtml = getCostCategoryDropDownList("dropdownlist");
		$("#div_cost_category_content_box").append($(allCostCategoryHtml));
		var allIncomeCategoryHtml = getIncomeCategoryDropDownList("dropdownlist");
		$("#div_income_category_content_box").append($(allIncomeCategoryHtml));
		
		// ���������б���ʽ����ʱע��
		// Ϊ��ϸ����б����ӵ���¼�
		// $("#div_category_box div span").click(function () {
		// 	$(this).addClass("selected").css("cursor", "default")
		//		.siblings("span").removeClass("selected").css("cursor", "pointer");
		// });

		// Ϊ����������ӵ���¼�
		$("#div_money_type_box>span").bind("click", function () {
			if ($(this).text().indexOf("����") >= 0) {
				$("#div_cost_category_content_box").hide("normal");
				$("#div_income_category_content_box").show("normal");
				$("#select_stat_type").val(1).attr("disabled", true);
				// $("#div_income_category_content_box>span:contains('н')").click(); // ���������б���ʽ����ʱע��
			} else {
				$("#div_cost_category_content_box").show("normal");
				$("#div_income_category_content_box").hide("normal");
				$("#select_stat_type").attr("disabled", false);
				// $("#div_cost_category_content_box>span:contains('ʳ')").click(); // ���������б���ʽ����ʱע��
			}
			 $(this).addClass("selected").css("cursor", "default"). // ���������б���ʽ����ʱע��
				 siblings("span").removeClass("selected").css("cursor", "pointer"); // ���������б���ʽ����ʱע��
		});

		// Ĭ��ѡ����
		$("#div_money_type_box>span:contains('֧��')").click();

		// Ĭ������
		$("#div_income_category_content_box").hide();
	}

	// ��ȡ֧�����������б�
	function getCostCategoryDropDownList(type){
		var allItemHtml = "";
		for (var i = 0; i < g_costCategoryArr.length; i++) {
			if (g_costCategoryArr[i] == "")
				continue;
			var arr = g_costCategoryArr[i].split(":");

			if (type != null && type == "dropdownlist"){
				// �����б���ʽ
				if (i == 0)
					allItemHtml = "<select id='select_cost_category'>";

				allItemHtml += "<option value='" + arr[0] + "'>" + arr[1] + "</option>";

				if (i == g_costCategoryArr.length - 1)
					allItemHtml += "</select>";
			} else {
				// ������ʾ��ʽ
//				allItemHtml += "<span val='" + arr[0] + "'>[" + arr[1] + "]</span>";
//				if (i == 4 || i == 9 || i == 14)
//					allItemHtml += "<br/>";
//				else
//					allItemHtml += "<label>&nbsp;&nbsp;</label>";
			}
		}

		return allItemHtml;
	}

	// ��ȡ������������б�
	function getIncomeCategoryDropDownList(type){
		var allItemHtml = "";
		for (var i = 0; i < g_incomeCategoryArr.length; i++) {
			if (g_incomeCategoryArr[i] == "")
				continue;
			var arr = g_incomeCategoryArr[i].split(":");
			if (type != null && type == "dropdownlist"){
				// �����б���ʽ
				if (i == 0)
					allItemHtml = "<select id='select_income_category'>";

				allItemHtml += "<option value='" + arr[0] + "'>" + arr[1] + "</option>";

				if (i == g_costCategoryArr.length - 1)
					allItemHtml += "</select>";
			} else {
				// ������ʾ��ʽ
				// allItemHtml += "<span val='" + arr[0] + "'>[" + arr[1] + "]</span><label>&nbsp;&nbsp;</label>";
			}
		}

		return allItemHtml;
	}

	// ��ʼ������ѡ��ؼ�
	function initDatetimeRelatedControls(){
		// �������ѡ��ؼ�
		$("#input_datetime").datepicker({ 
			dateFormat:"yy-mm-dd", 
			changeMonth:true, 
			changeYear:true, 
			showOn:"both", 
			showButtonPanel:true, 
			closeTextType:"close", 
			maxDate:"0"});
		$("#input_datetime").val(new Date().format("yyyy-MM-dd"));
		$("#input_datetime2").datepicker({ 
			dateFormat:"yy-mm-dd", 
			changeMonth:true, 
			changeYear:true, 
			showOn:"both", 
			showButtonPanel:true, 
			closeTextType:"close", 
			maxDate:"0",
			onSelect:function(dateText, inst){
				var eDate = new Date().format("yyyy-MM-dd");
				var sArr = dateText.split("-");
				var eArr = eDate.split("-");
				var sRDate = new Date(sArr[0], sArr[1]-1, sArr[2]);
				var eRDate = new Date(eArr[0], eArr[1]-1, eArr[2]);
				var result = (eRDate - sRDate) / (24 * 60 * 60 * 1000);
				
				// ���ԭ������
				$("#div_latest_items tr:gt(0)").remove();
				// ��ȡ����
				getTodayNotes(result);
			}});
		$("#div_latest_items_op_bar_datetime_box").hide();
	}

	// ��ȡ�û�������Ϣ
	function showUserCategoryData(){
		for (var i=0; i<g_costCategoryArr.length; i++){
			var itemArr = g_costCategoryArr[i].split(":");
			addOneCategoryItem("cost", itemArr[0], itemArr[1], itemArr[2], itemArr[3]);
		}
		for (var i=0; i<g_incomeCategoryArr.length; i++){
			var itemArr = g_incomeCategoryArr[i].split(":");
			addOneCategoryItem("income", itemArr[0], itemArr[1], itemArr[2], itemArr[3]);
		}		
	}

	// ���һ�з�����б�
	function addOneCategoryItem(type, id, name, des, time){
		var money_type = type == "cost" ? "֧��" : "����";
		$title_row = $("#tbl_category_items tr:last");
		var categoryRow = "<tr categoryid='" + id + "'><td>" + money_type + "</td><td>" + name + "</td><td>" + des + "</td><td>" + time + "</td>><td><span onclick='unlockUserCategoryForEdit(this)' style='cursor:pointer;' >[�޸�]</span></td></tr>";
		$title_row.before($(categoryRow));
	}

	// ����������Ϣ��Ŀ���ڱ༭
	function unlockUserCategoryForEdit(obj){
		// �õ�ҳ�����
		$ctgItemTr = $(obj).parent().parent();
		$ctgNameTd = $ctgItemTr.children("td:eq(1)");
		$ctgDesTd = $ctgItemTr.children("td:eq(2)");
		$ctgOpTd = $ctgItemTr.children("td:eq(4)");
		// ���������ڱ༭����Ŀ�ָ���ʼ
		restoreCtgItem();
		// ������״̬�Ͳ�����ťinput
		var ctgNameInput = "<input type='text' value='" + $ctgNameTd.text() + "' style='width:60px;' onchange='updateOpBtnText(this)' />";
		var ctgDesInput = "<input type='text' value='" + $ctgDesTd.text() + "' style='width:120px;' onchange='updateOpBtnText(this)' />";
		var ctgUpInput = "<input type='button' value='����' oldname='" + $ctgNameTd.text() + "' olddes='" + $ctgDesTd.text() + "' onclick='updateCtgItem(this)' categoryid='" + $ctgItemTr.attr("categoryid") + "' />"; 
		// ���ԭ������
		$ctgNameTd.text("");
		$ctgDesTd.text("");
		// ���������
		$ctgNameTd.append($(ctgNameInput));
		$ctgDesTd.append($(ctgDesInput));
		// ���²�����ť
		$ctgOpTd.children("span").hide("fast");
		$ctgOpTd.append($(ctgUpInput));
	}

	// ��ĳ�������޸ĵķ���ָ�Ϊ��ʼδ�޸�״̬
	function restoreCtgItem(){
		$editTr = $("#tbl_category_items tr:has(td:has(input[oldname]))");
		if ($editTr.length > 0){
			// ����jq����
			$ctgNameTd_edit = $editTr.children("td:eq(1)");
			$ctgDesTd_edit = $editTr.children("td:eq(2)");
			$ctgOpTd_edit = $editTr.children("td:eq(4)");
			// �ָ�
			var oldName = $ctgOpTd_edit.children("input").attr("oldname");
			var oldDes = $ctgOpTd_edit.children("input").attr("olddes");
			$ctgNameTd_edit.empty().text(oldName);
			$ctgDesTd_edit.empty().text(oldDes);
			$ctgOpTd_edit.children("span").show().end().children("input").remove();
		}
	}

	// ���·�����Ŀ
	function updateCtgItem(obj){
		// ���͸�������
		var userid = $("#hidden_cur_userid").val();
		var categoryid = $(obj).attr("categoryid");
		var ctgName = $(obj).parent().parent().children("td:eq(1)").children("input").val();
		var ctgDes = $(obj).parent().parent().children("td:eq(2)").children("input").val();

        $.post("financial_process.php", 
			{ "user_name": userid, "operation_type": "update_category_item", "_ctgid":categoryid, "_ctgName":ctgName, "_ctgDes":ctgDes },
            function (data) {
                if (data == "success") {
					$(obj).attr("oldname", ctgName);
					$(obj).attr("olddes", ctgDes);
					restoreCtgItem();
                } else {
					alert("���·���ʧ��");
                }
            });
	}

	// ���·�����ӵ����ݿ�
	function AddNewCategoryToDB(obj){
		// data
		var userid = $("#hidden_cur_userid").val();
		$opTr = $(obj).parent().parent();
		var type = $opTr.children("td:eq(0)").children("select").val();
		var ctgName = removeEnterCharForString($opTr.children("td:eq(1)").children("input").val());
		var des = removeEnterCharForString($opTr.children("td:eq(2)").children("input").val());
		
		// post
		$.get("financial_process.php", 
		{ "user_name": userid, "operation_type": "add_new_category", "_moneytype":type, "_ctgname":ctgName, "_ctgdes":des },
		function (data) {
			if (!isNaN(data)) {
				// ����ɱ༭��
				$opTr.empty();
				// ����¼ӷ�����
				addOneCategoryItem(type, data, ctgName, des, "0");
				// ������ʾ����ӷ��ࡱ��ť
				$("#opTr").css("display", "");
				// ��̬���������ĸ߶ȣ�ÿ������27px
				$("#div_quick_opration_box").css("height", $("#div_quick_opration_box").height() + 27);

			} else {
				alert("��ӷ���ʧ��");
			}
		});
	}

	// ����µķ���
	function AddNewCategory(obj){
		$opTr = $(obj).parent().parent();

		var typeTd = "<td><select><option value='cost'>֧��</option><option value='income'>����</option></select></td>";
		var categoryTd = "<td><input type='text' style='width:50px' /></td>";
		var desTd = "<td><input type='text' style='width:180px' /></td>";
		var opTd = "<td colspan='2'><input type='button' onclick='AddNewCategoryToDB(this)' value='���' /></td>";
		var newTr = "<tr>" + typeTd + categoryTd + desTd + opTd + "</tr>";
		// ��������
		$opTr.before($(newTr));

		// ���ص�������·��ࡱ��ť
		$("#opTr").css("display", "none");
	}

	// ���²�����ť��ʾ����
	function updateOpBtnText(obj){
		// ����jq����
		$(obj).parent().parent().children("td:eq(5)").children("input").val("����");
	}

	// ��¼���������ť�¼�
	function selectLatestNotesButton(index){
		if (index != null){
			if (index == "n"){
				$("#div_latest_items_op_bar_datetime_box").show("fast");
			}else{
				$("#div_latest_items_op_bar_datetime_box").hide("normal");
				// ���ԭ������
				$("#div_latest_items tr:gt(0)").remove();
				
				if (index == "income"){
					getIncomeNotes();
				}else{
					getTodayNotes(index);
				}
			}
		}
	}

	// ��ȡ���ռ�¼����
	function getTodayNotes(index){
		getUserNotes(index, "get_today_notes");
	}

	// ��ȡ�û���������
	function getIncomeNotes(){
		getUserNotes(0, "get_user_income_data");
	}

	// ��ȡ�û���¼���ݣ�ͨ���Ӻ�����
	function getUserNotes(index, op_type){
		var userid = $("#hidden_cur_userid").val();

		$.get("financial_process.php",
			{"user_name":userid, "operation_type":op_type, "date_index":index},
			function(data){
				for (var i=0;i<data.length;i++){
					var short_date = data[i]["createtime"];
					short_date = short_date.substring(0, short_date.indexOf(" "));
					// ��Ӽ�¼
					addItemToTodayTbl(data[i]["id"], data[i]["moneytype"], data[i]["amount"], data[i]["category"], data[i]["remark"], short_date);
				}
				// �����¼����Ϊ��
				if (data == ""){
					addNoRecordMessageRow();
				}
        }, "json");
	}

	// ���һ��û�м�¼��ʾ��
	function addNoRecordMessageRow(){
		var	itemHtml = "<tr no_record><td colspan='6' style='text-align:center;'>����û�м��˼�¼Ӵ</td></tr>"
		$("#tbl_today_items tr:first").after($(itemHtml));
	}

    // ��ȡ�û�ͳ������
	function getUserSummaryData(){
		var userid = $("#hidden_cur_userid").val();
		$.get("financial_process.php",
			{"user_name":userid, "operation_type":"get_user_summary_data"},
			function(data){
				var arr = data.split(",");
				$("#span_user_total_income").text(arr[0]);
				$("#span_user_total_cost").text(arr[1]);
				$("#span_cur_month_total_income").text(arr[2]);
				$("#span_cur_month_total_cost").text(arr[3]);
				// ���±��¼ƻ�֧��������
				updateCurMonthCostPlanBar(Number(arr[3]));
			});
	}

	// ���±��¼ƻ�֧��������
	function updateCurMonthCostPlanBar(totalCost){
		var planCost = $("#hide_month_cost_plan_config").val() - 0;
		if (planCost == null || isNaN(planCost))
		{
			planCost = 1000;
		}

		var finalVal = 100;
		if (totalCost > 0){
			if (planCost <= totalCost)
				finalVal = 0;
			else
				finalVal = (planCost - totalCost) / planCost * 100;			
		}
		
		$("#prg_month_budget").val(finalVal.toFixed(2));
		var planStatus = Number(totalCost).toFixed(2) + " / " + planCost.toFixed(2);
		$("#span_cur_month_plan_process").text(planStatus);
	}

	// ���һ����¼
	function addNew() {
		
		// ���ð�ť
		$("#btn_submit").attr("disabled", true);

		// ��ҳ��ץȡ����
		var type = $("#div_money_type_box>span[class='selected']").attr("val");
		var amount = $("#txt_money").val();
		// var category = $("#div_category_box>div:visible>span[class='selected']").attr("val");
		var category = $("#div_category_box>div:visible>select").val();
		var remark = removeEnterCharForString($("#txt_financial_remark").val());
		var datetime = $("#input_datetime").val();
		var stattype = $("#select_stat_type").val();

		// ��ӵ����ݿ�
		var userid = $("#hidden_cur_userid").val();
        $.post("financial_process.php", 
			{ "user_name": userid, "operation_type": "add", "_type":type, "_amount":amount, "_category":category, "_remark":remark, "_datetime":datetime, "_stattype":stattype },
            function (data) {
                if (data == "success") {
					if ($("#div_latest_items_op_bar>span:contains('����')").hasClass("selected")){
						// ��Ӽ�¼
						addItemToTodayTbl("-1", type, amount, category, remark, datetime);
					}else {
						// �л��������б�
						$("#div_latest_items_op_bar>span:contains('����')").click();
					}

					resetInputBox();
					// ����ͳ������
					UpdateSummuryPanel(type, amount);
                } else {
                    alert("��Ӽ�¼����������");
					$("#btn_submit").attr("disabled", false);
                }
            });
	}

	// ����ͳ����������
	function UpdateSummuryPanel(type, amount){
		if (type == "cost"){
			var newCostVal = Number($("#span_cur_month_total_cost").text()) + Number(amount);
			$("#span_cur_month_total_cost").text(newCostVal.toFixed(2));
			var newTotalCostVal = Number($("#span_user_total_cost").text()) + Number(amount);
			$("#span_user_total_cost").text(newTotalCostVal.toFixed(2));
			updateCurMonthCostPlanBar(newCostVal);
		}else if (type == "income")
		{
			var newIncomeVal = Number($("#span_cur_month_total_income").text()) + Number(amount);
			$("#span_cur_month_total_income").text(newIncomeVal.toFixed(2));
			var newTotalIncomeVal = Number($("#span_user_total_income").text()) + Number(amount);
			$("#span_user_total_income").text(newTotalIncomeVal.toFixed(2));
		}
	}

	function addItemToTodayTbl(id, type, amount, category, remark, datetime) {
		var showCategory = getCategoryNameById(category);
		
		// ����б�����ʾ�޼�¼��ʾ�У���ɾ��
		if ($("#tbl_today_items tr[no_record]").length > 0)
			$("#tbl_today_items tr[no_record]").empty();

		var delTd = "";
		if (id == "-1")
			delTd = "<td>#</td>";
		else
			delTd = "<td><span itemId='" + id + "' onclick='deleteFinancialItem(this)' style='cursor:pointer;' >[x]</span></td>";
		
		var itemHtml = "<tr>" + delTd + "<td>" + (type == "cost" ? "֧��" : "����") + "</td><td>" + showCategory + "</td><td>" + amount + "</td><td>" + datetime + "</td><td>" + remark + "</td></tr>";
		$("#tbl_today_items tr:first").after($(itemHtml));
	}

	// ����categoryId��ȡ��������
	function getCategoryNameById(id){
		for (var i=0; i < g_costCategoryArr.length; i++){
			var itemArr = g_costCategoryArr[i].split(":");
			if (itemArr[0] == id)
				return itemArr[1];
		}
		for (var i=0; i < g_incomeCategoryArr.length; i++){
			var itemArr = g_incomeCategoryArr[i].split(":");
			if (itemArr[0] == id)
				return itemArr[1];
		}
	}

	function resetInputBox() {
		$("#div_money_type_box>span:contains('֧��')").click();
		$("#txt_money").val("0");
		$("#txt_financial_remark").val("");
	}

	// ɾ����ϸ��Ŀ
	function deleteFinancialItem(obj) {
		if (obj == null) {
			return;
		}

		var id = obj.attributes["itemId"].value;
		$("#tbl_today_items span[itemid=" + id + "]").parent().parent().css({"background-color":"#b7e9b5"});

		$(obj).hide("slow");
		var confirmBtn = "<input type='button' value='ɾ��' itemId='" + id + "' onclick='DoDeleteItem(this)'>";
		$(obj).parent().append(confirmBtn);
		$(obj).parent().parent()
			.siblings("tr:not(tr:first)").css({"background-color":"#F7F7F7"})
			.children("td:gt(0) span[itemId!=" + id + "]").show()
			.children("td:gt(0) input[itemId!=" + id + "]").hide();
	}

	function DoDeleteItem(obj){
		var userid = $("#hidden_cur_userid").val();
		var id = $(obj).attr("itemId");
		// ����ajax����
		$.post("financial_process.php", { "id": id, "operation_type": "delete", "user_name" : userid },
			function (data) {
				if (data == "success") {
					$("#tbl_today_items span[itemid=" + id + "]").parent().parent().remove();
					if ($("#tbl_today_items tr").length == 1)
						addNoRecordMessageRow();
				} else {
					alert("ɾ�����������ʧ�ܣ�" + data);
				}
			});
	}


	// ��Date����չ���� Date ת��Ϊָ����ʽ��String 
	// ��(M)����(d)��Сʱ(h)����(m)����(s)������(q) ������ 1-2 ��ռλ���� 
	// ��(y)������ 1-4 ��ռλ��������(S)ֻ���� 1 ��ռλ��(�� 1-3 λ������) 
	// ���ӣ� 
	// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
	// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
	Date.prototype.format = function(fmt) 
	{ //author: meizz 
	  var o = { 
		"M+" : this.getMonth()+1,                 //�·� 
		"d+" : this.getDate(),                    //�� 
		"h+" : this.getHours(),                   //Сʱ 
		"m+" : this.getMinutes(),                 //�� 
		"s+" : this.getSeconds(),                 //�� 
		"q+" : Math.floor((this.getMonth()+3)/3), //���� 
		"S"  : this.getMilliseconds()             //���� 
	  }; 
	  if(/(y+)/.test(fmt)) 
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
	  for(var k in o) 
		if(new RegExp("("+ k +")").test(fmt)) 
	  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length))); 
	  return fmt; 
	}

	// ȥ���ַ����к��еĻس���\n
	function removeEnterCharForString(str){
		return str.replace(/\n/g, "");
	}

	// ���������Ѽƻ�
	function updateMonthCostPlan()
	{
		// ���͸�������
		var userid = $("#hidden_cur_userid").val();
		var month_cost_plan = $("#input_month_cost_plan").val();

        $.post("financial_process.php", 
			{ "user_name": userid, "operation_type": "update_month_cost_plan", "newPlanVal":month_cost_plan },
            function (data) {
                if (data == "success") {
					$("#input_month_cost_plan").attr("oldVal", month_cost_plan);
					$("#btnUpMCP").attr("disabled", "true");
                } else {
					alert("�����¼ƻ���������ʧ��");
                }
            });
	}

	// ����ȫ�����Ѽ�¼
	function DownloadAllRecordNotes(){
		var userid = $("#hidden_cur_userid").val();
		var download_url = "financial_download_process.php?operation_type=download_all_record_notes&user_name=" + $("#hidden_cur_userid").val();
		window.location.target = "_blank";
		window.location.href = download_url;
	}