/*
	此脚本文件专门为financial.php页面准备
	by yanggl 20121016
*/

	// 页面全局变量
	var g_costCategoryArr = ""; // ["衣", "食", "住", "行", "医", "孝", "娱", "友", "学", "益", "讯", "孩", "借", "一", "余"];
	var g_incomeCategoryArr = ""; // ["工资", "其他"];

	$(document).ready(function () {
		// 初始化页面控件及相关事件
		initControlsAndBindEvents();
		// 获取用户分类数据
		getUserCategoryData();
	});

	// 初始化页面控件及相关事件
	function initControlsAndBindEvents(){
		// 给金额框绑定事件
		$("#txt_money").bind("input propertychange", function () {
			var num = Number($(this).val());
			var disabledVal = (num == null || num <= 0);
			$("#btn_submit").attr("disabled", disabledVal);
		});

		// 为记录详情快捷按钮添加事件
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

		// 初始化日期选择控件
		initDatetimeRelatedControls();

		// 初始化用户配置框
		initUserConfigBox();
	}

	// 初始化用户配置框及默认选项
	function initUserConfigBox()
	{
		// 分类配置框事件绑定
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
		// 月消费计划框事件绑定
		$("#input_month_cost_plan").bind("keyup", function(){
			var newVal = $(this).val();
			$("#btnUpMCP").attr("disabled", (isNaN(newVal) || $(this).attr("oldVal") == newVal));
		});

		// 默认选择配置项tab
		$("#div_quick_opration_box>h3>span:first").click();
		// 获取用户默认月消费计划
		getUserMonthCostPlan();
	}

	// 获取用户默认月消费计划
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

					// 获取用户统计栏数据
					getUserSummaryData();
				}
				else
				{
					alert("Load user month cost plan value fail.");
				}
			});
	}

	// 获取用户分类数据
	function getUserCategoryData(){
		var userid = $("#hidden_cur_userid").val();
        $.get("financial_process.php", 
			{ "user_name": userid, "operation_type": "get_user_category_data" },
            function (data) {
				if (data == null)
					alert("获取用户分类信息失败");

				var categoryArr = data.split(";");
				if (categoryArr.length == 2){
					g_costCategoryArr = categoryArr[0].split(",");
					g_incomeCategoryArr = categoryArr[1].split(",");
					// 初始化分类数据
					initCategoryDataList();
					// 获取用户分类信息
					showUserCategoryData();
					// 初始化分类配置容器高度
					InitCategoryConfigBoxHeight("category_config");
					// 获取今日数据
					$("#div_latest_items_op_bar>span:contains('今天')").click();
				}
            }, "json");
	}

	// 初始化分类配置容器高度
	function InitCategoryConfigBoxHeight(tabType){
		var divBoxHeight = 100;
		if (tabType == "category_config")
			divBoxHeight += $("#div_category_modify_box>table tr").length * 27;

		// 根据分类实际条数调整容器高度100px初始，每行增加27px
		$("#div_quick_opration_box").css("height", divBoxHeight);
	}

	// 初始化用户分类数据相关数据，及添加相关事件及默认选项等
	function initCategoryDataList(){
		// 添加详细类别列表
		var allCostCategoryHtml = getCostCategoryDropDownList("dropdownlist");
		$("#div_cost_category_content_box").append($(allCostCategoryHtml));
		var allIncomeCategoryHtml = getIncomeCategoryDropDownList("dropdownlist");
		$("#div_income_category_content_box").append($(allIncomeCategoryHtml));
		
		// 采用下拉列表形式，暂时注释
		// 为详细类别列表项，添加点击事件
		// $("#div_category_box div span").click(function () {
		// 	$(this).addClass("selected").css("cursor", "default")
		//		.siblings("span").removeClass("selected").css("cursor", "pointer");
		// });

		// 为操作类别项，添加点击事件
		$("#div_money_type_box>span").bind("click", function () {
			if ($(this).text().indexOf("收入") >= 0) {
				$("#div_cost_category_content_box").hide("normal");
				$("#div_income_category_content_box").show("normal");
				$("#select_stat_type").val(1).attr("disabled", true);
				// $("#div_income_category_content_box>span:contains('薪')").click(); // 采用下拉列表形式，暂时注释
			} else {
				$("#div_cost_category_content_box").show("normal");
				$("#div_income_category_content_box").hide("normal");
				$("#select_stat_type").attr("disabled", false);
				// $("#div_cost_category_content_box>span:contains('食')").click(); // 采用下拉列表形式，暂时注释
			}
			 $(this).addClass("selected").css("cursor", "default"). // 采用下拉列表形式，暂时注释
				 siblings("span").removeClass("selected").css("cursor", "pointer"); // 采用下拉列表形式，暂时注释
		});

		// 默认选择项
		$("#div_money_type_box>span:contains('支出')").click();

		// 默认隐藏
		$("#div_income_category_content_box").hide();
	}

	// 获取支出分类下拉列表
	function getCostCategoryDropDownList(type){
		var allItemHtml = "";
		for (var i = 0; i < g_costCategoryArr.length; i++) {
			if (g_costCategoryArr[i] == "")
				continue;
			var arr = g_costCategoryArr[i].split(":");

			if (type != null && type == "dropdownlist"){
				// 下拉列表形式
				if (i == 0)
					allItemHtml = "<select id='select_cost_category'>";

				allItemHtml += "<option value='" + arr[0] + "'>" + arr[1] + "</option>";

				if (i == g_costCategoryArr.length - 1)
					allItemHtml += "</select>";
			} else {
				// 并排显示形式
//				allItemHtml += "<span val='" + arr[0] + "'>[" + arr[1] + "]</span>";
//				if (i == 4 || i == 9 || i == 14)
//					allItemHtml += "<br/>";
//				else
//					allItemHtml += "<label>&nbsp;&nbsp;</label>";
			}
		}

		return allItemHtml;
	}

	// 获取收入分类下拉列表
	function getIncomeCategoryDropDownList(type){
		var allItemHtml = "";
		for (var i = 0; i < g_incomeCategoryArr.length; i++) {
			if (g_incomeCategoryArr[i] == "")
				continue;
			var arr = g_incomeCategoryArr[i].split(":");
			if (type != null && type == "dropdownlist"){
				// 下拉列表形式
				if (i == 0)
					allItemHtml = "<select id='select_income_category'>";

				allItemHtml += "<option value='" + arr[0] + "'>" + arr[1] + "</option>";

				if (i == g_costCategoryArr.length - 1)
					allItemHtml += "</select>";
			} else {
				// 并排显示形式
				// allItemHtml += "<span val='" + arr[0] + "'>[" + arr[1] + "]</span><label>&nbsp;&nbsp;</label>";
			}
		}

		return allItemHtml;
	}

	// 初始化日期选择控件
	function initDatetimeRelatedControls(){
		// 添加日期选择控件
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
				
				// 清除原有数据
				$("#div_latest_items tr:gt(0)").remove();
				// 获取数据
				getTodayNotes(result);
			}});
		$("#div_latest_items_op_bar_datetime_box").hide();
	}

	// 获取用户分类信息
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

	// 添加一行分类项到列表
	function addOneCategoryItem(type, id, name, des, time){
		var money_type = type == "cost" ? "支出" : "收入";
		$title_row = $("#tbl_category_items tr:last");
		var categoryRow = "<tr categoryid='" + id + "'><td>" + money_type + "</td><td>" + name + "</td><td>" + des + "</td><td>" + time + "</td>><td><span onclick='unlockUserCategoryForEdit(this)' style='cursor:pointer;' >[修改]</span></td></tr>";
		$title_row.before($(categoryRow));
	}

	// 解锁分类信息条目用于编辑
	function unlockUserCategoryForEdit(obj){
		// 用的页面对象
		$ctgItemTr = $(obj).parent().parent();
		$ctgNameTd = $ctgItemTr.children("td:eq(1)");
		$ctgDesTd = $ctgItemTr.children("td:eq(2)");
		$ctgOpTd = $ctgItemTr.children("td:eq(4)");
		// 将其他正在编辑的条目恢复初始
		restoreCtgItem();
		// 可输入状态和操作按钮input
		var ctgNameInput = "<input type='text' value='" + $ctgNameTd.text() + "' style='width:60px;' onchange='updateOpBtnText(this)' />";
		var ctgDesInput = "<input type='text' value='" + $ctgDesTd.text() + "' style='width:120px;' onchange='updateOpBtnText(this)' />";
		var ctgUpInput = "<input type='button' value='更新' oldname='" + $ctgNameTd.text() + "' olddes='" + $ctgDesTd.text() + "' onclick='updateCtgItem(this)' categoryid='" + $ctgItemTr.attr("categoryid") + "' />"; 
		// 清空原有内容
		$ctgNameTd.text("");
		$ctgDesTd.text("");
		// 插入输入框
		$ctgNameTd.append($(ctgNameInput));
		$ctgDesTd.append($(ctgDesInput));
		// 更新操作按钮
		$ctgOpTd.children("span").hide("fast");
		$ctgOpTd.append($(ctgUpInput));
	}

	// 将某行正在修改的分类恢复为初始未修改状态
	function restoreCtgItem(){
		$editTr = $("#tbl_category_items tr:has(td:has(input[oldname]))");
		if ($editTr.length > 0){
			// 可用jq对象
			$ctgNameTd_edit = $editTr.children("td:eq(1)");
			$ctgDesTd_edit = $editTr.children("td:eq(2)");
			$ctgOpTd_edit = $editTr.children("td:eq(4)");
			// 恢复
			var oldName = $ctgOpTd_edit.children("input").attr("oldname");
			var oldDes = $ctgOpTd_edit.children("input").attr("olddes");
			$ctgNameTd_edit.empty().text(oldName);
			$ctgDesTd_edit.empty().text(oldDes);
			$ctgOpTd_edit.children("span").show().end().children("input").remove();
		}
	}

	// 更新分类条目
	function updateCtgItem(obj){
		// 发送更新请求
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
					alert("更新分类失败");
                }
            });
	}

	// 将新分类添加到数据库
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
				// 清除可编辑行
				$opTr.empty();
				// 添加新加分类行
				addOneCategoryItem(type, data, ctgName, des, "0");
				// 重新显示“添加分类”按钮
				$("#opTr").css("display", "");
				// 动态调整容器的高度，每行增加27px
				$("#div_quick_opration_box").css("height", $("#div_quick_opration_box").height() + 27);

			} else {
				alert("添加分类失败");
			}
		});
	}

	// 添加新的分类
	function AddNewCategory(obj){
		$opTr = $(obj).parent().parent();

		var typeTd = "<td><select><option value='cost'>支出</option><option value='income'>收入</option></select></td>";
		var categoryTd = "<td><input type='text' style='width:50px' /></td>";
		var desTd = "<td><input type='text' style='width:180px' /></td>";
		var opTd = "<td colspan='2'><input type='button' onclick='AddNewCategoryToDB(this)' value='添加' /></td>";
		var newTr = "<tr>" + typeTd + categoryTd + desTd + opTd + "</tr>";
		// 插入新行
		$opTr.before($(newTr));

		// 隐藏掉“添加新分类”按钮
		$("#opTr").css("display", "none");
	}

	// 更新操作按钮显示文字
	function updateOpBtnText(obj){
		// 可用jq对象
		$(obj).parent().parent().children("td:eq(5)").children("input").val("更新");
	}

	// 记录详情操作按钮事件
	function selectLatestNotesButton(index){
		if (index != null){
			if (index == "n"){
				$("#div_latest_items_op_bar_datetime_box").show("fast");
			}else{
				$("#div_latest_items_op_bar_datetime_box").hide("normal");
				// 清除原有数据
				$("#div_latest_items tr:gt(0)").remove();
				
				if (index == "income"){
					getIncomeNotes();
				}else{
					getTodayNotes(index);
				}
			}
		}
	}

	// 获取今日记录数据
	function getTodayNotes(index){
		getUserNotes(index, "get_today_notes");
	}

	// 获取用户收入数据
	function getIncomeNotes(){
		getUserNotes(0, "get_user_income_data");
	}

	// 获取用户记录数据（通用子函数）
	function getUserNotes(index, op_type){
		var userid = $("#hidden_cur_userid").val();

		$.get("financial_process.php",
			{"user_name":userid, "operation_type":op_type, "date_index":index},
			function(data){
				for (var i=0;i<data.length;i++){
					var short_date = data[i]["createtime"];
					short_date = short_date.substring(0, short_date.indexOf(" "));
					// 添加记录
					addItemToTodayTbl(data[i]["id"], data[i]["moneytype"], data[i]["amount"], data[i]["category"], data[i]["remark"], short_date);
				}
				// 如果记录条数为空
				if (data == ""){
					addNoRecordMessageRow();
				}
        }, "json");
	}

	// 添加一条没有记录提示行
	function addNoRecordMessageRow(){
		var	itemHtml = "<tr no_record><td colspan='6' style='text-align:center;'>当天没有记账记录哟</td></tr>"
		$("#tbl_today_items tr:first").after($(itemHtml));
	}

    // 获取用户统计数据
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
				// 更新本月计划支出进度条
				updateCurMonthCostPlanBar(Number(arr[3]));
			});
	}

	// 更新本月计划支出进度条
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

	// 添加一条记录
	function addNew() {
		
		// 禁用按钮
		$("#btn_submit").attr("disabled", true);

		// 从页面抓取数据
		var type = $("#div_money_type_box>span[class='selected']").attr("val");
		var amount = $("#txt_money").val();
		// var category = $("#div_category_box>div:visible>span[class='selected']").attr("val");
		var category = $("#div_category_box>div:visible>select").val();
		var remark = removeEnterCharForString($("#txt_financial_remark").val());
		var datetime = $("#input_datetime").val();
		var stattype = $("#select_stat_type").val();

		// 添加到数据库
		var userid = $("#hidden_cur_userid").val();
        $.post("financial_process.php", 
			{ "user_name": userid, "operation_type": "add", "_type":type, "_amount":amount, "_category":category, "_remark":remark, "_datetime":datetime, "_stattype":stattype },
            function (data) {
                if (data == "success") {
					if ($("#div_latest_items_op_bar>span:contains('今天')").hasClass("selected")){
						// 添加记录
						addItemToTodayTbl("-1", type, amount, category, remark, datetime);
					}else {
						// 切换到今天列表
						$("#div_latest_items_op_bar>span:contains('今天')").click();
					}

					resetInputBox();
					// 更新统计数字
					UpdateSummuryPanel(type, amount);
                } else {
                    alert("添加记录出问题啦！");
					$("#btn_submit").attr("disabled", false);
                }
            });
	}

	// 更新统计面板的数字
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
		
		// 如果列表有显示无记录提示行，先删除
		if ($("#tbl_today_items tr[no_record]").length > 0)
			$("#tbl_today_items tr[no_record]").empty();

		var delTd = "";
		if (id == "-1")
			delTd = "<td>#</td>";
		else
			delTd = "<td><span itemId='" + id + "' onclick='deleteFinancialItem(this)' style='cursor:pointer;' >[x]</span></td>";
		
		var itemHtml = "<tr>" + delTd + "<td>" + (type == "cost" ? "支出" : "收入") + "</td><td>" + showCategory + "</td><td>" + amount + "</td><td>" + datetime + "</td><td>" + remark + "</td></tr>";
		$("#tbl_today_items tr:first").after($(itemHtml));
	}

	// 根据categoryId获取分类名称
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
		$("#div_money_type_box>span:contains('支出')").click();
		$("#txt_money").val("0");
		$("#txt_financial_remark").val("");
	}

	// 删除详细条目
	function deleteFinancialItem(obj) {
		if (obj == null) {
			return;
		}

		var id = obj.attributes["itemId"].value;
		$("#tbl_today_items span[itemid=" + id + "]").parent().parent().css({"background-color":"#b7e9b5"});

		$(obj).hide("slow");
		var confirmBtn = "<input type='button' value='删除' itemId='" + id + "' onclick='DoDeleteItem(this)'>";
		$(obj).parent().append(confirmBtn);
		$(obj).parent().parent()
			.siblings("tr:not(tr:first)").css({"background-color":"#F7F7F7"})
			.children("td:gt(0) span[itemId!=" + id + "]").show()
			.children("td:gt(0) input[itemId!=" + id + "]").hide();
	}

	function DoDeleteItem(obj){
		var userid = $("#hidden_cur_userid").val();
		var id = $(obj).attr("itemId");
		// 调用ajax请求
		$.post("financial_process.php", { "id": id, "operation_type": "delete", "user_name" : userid },
			function (data) {
				if (data == "success") {
					$("#tbl_today_items span[itemid=" + id + "]").parent().parent().remove();
					if ($("#tbl_today_items tr").length == 1)
						addNoRecordMessageRow();
				} else {
					alert("删除或更新事项失败！" + data);
				}
			});
	}


	// 对Date的扩展，将 Date 转化为指定格式的String 
	// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
	// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
	// 例子： 
	// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
	// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
	Date.prototype.format = function(fmt) 
	{ //author: meizz 
	  var o = { 
		"M+" : this.getMonth()+1,                 //月份 
		"d+" : this.getDate(),                    //日 
		"h+" : this.getHours(),                   //小时 
		"m+" : this.getMinutes(),                 //分 
		"s+" : this.getSeconds(),                 //秒 
		"q+" : Math.floor((this.getMonth()+3)/3), //季度 
		"S"  : this.getMilliseconds()             //毫秒 
	  }; 
	  if(/(y+)/.test(fmt)) 
		fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
	  for(var k in o) 
		if(new RegExp("("+ k +")").test(fmt)) 
	  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length))); 
	  return fmt; 
	}

	// 去除字符串中含有的回车符\n
	function removeEnterCharForString(str){
		return str.replace(/\n/g, "");
	}

	// 更新月消费计划
	function updateMonthCostPlan()
	{
		// 发送更新请求
		var userid = $("#hidden_cur_userid").val();
		var month_cost_plan = $("#input_month_cost_plan").val();

        $.post("financial_process.php", 
			{ "user_name": userid, "operation_type": "update_month_cost_plan", "newPlanVal":month_cost_plan },
            function (data) {
                if (data == "success") {
					$("#input_month_cost_plan").attr("oldVal", month_cost_plan);
					$("#btnUpMCP").attr("disabled", "true");
                } else {
					alert("更新月计划消费数额失败");
                }
            });
	}

	// 下载全部消费记录
	function DownloadAllRecordNotes(){
		var userid = $("#hidden_cur_userid").val();
		var download_url = "financial_download_process.php?operation_type=download_all_record_notes&user_name=" + $("#hidden_cur_userid").val();
		window.location.target = "_blank";
		window.location.href = download_url;
	}