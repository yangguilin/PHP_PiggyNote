//
// financial statistic page javascript file
// 20130318 by yanggl
// 

//
// 全局变量
//

var g_userId = null; // userid
var g_cost_mapping = new Array(), g_income_mapping = new Array(), g_all_category_mapping = new Array(); // 用户分类对应表
var g_stat_type_mapping = ["", "日常", "阶段", "大额"];
var g_cost_droplist = "", g_income_droplist = ""; // 用户分类下拉列表html
var g_stat_type_droplist = "<select style='width:60px;' onchange='UpdateOpBtnStatus(this)'><option value='1'>日常</option><option value='2'>阶段</option><option value='3'>大额</option></select>";
var g_recordMonthNum = null; // 用户记录月数
var g_cat_filt_big_data = false; // 按分类统计中的过滤大额数据开关
var g_mon_avg_stage_data = false; // 按月份统计中的平均阶段支出项的开关
var g_mon_filt_big_data = false; // 按月份统计中的过滤大额数据开关

// Unicode编码与GB2312转换方法
var GB2312UnicodeConverter = {
	ToUnicode: function (str) {
		return escape(str).toLocaleLowerCase().replace(/%u/gi, '\\u');
	}
	, ToGB2312: function (str) {
		return unescape(str.replace(/\\u/gi, '%u'));
	}
};

//
// 初始化
//

window.onload = function(){

	// 载入页面数据
	LoadPageData();
	// 初始化页面元素事件及样式
	InitPageStyleAndEvent();
	// 执行默认动作
	ExecuteDefaultBehavior();
};


// 执行默认动作
function ExecuteDefaultBehavior(){
	// 默认选择当月统计项
	$("#div_quick_opration_box>h3>span:first").click();
	$("#div_mon_filt_type>span[filt_type='normal']").click();
}

// 初始化页面元素事件及样式
function InitPageStyleAndEvent(){
	// 1. 统计项切换tab事件及样式
	$("#div_quick_opration_box>h3>span").bind("click", function(){
		$(this).addClass("selected").css("cursor", "default")
			.siblings("span").removeClass("selected").css("cursor", "pointer");
		
		var tagVal = $(this).attr("val");
		if (tagVal){
			var map = {"cur_month":"div_cur_month_stat_box",
				"category":"div_category_stat_box",
				"any_month":"div_any_month_stat_box", 
				"any_year":"div_any_year_stat_box",
				"big_data":"div_big_data_stat_box"};

			for(var item in map){
				if (item == tagVal)
					$("#" + map[tagVal]).show();
				else
					$("#" + map[item]).hide();
			}
		}
	});
	// 2. 按月统计切换过滤模式按钮事件及样式
	$("#div_mon_filt_type>span").bind("click", function(){
		$(this).addClass("filt_selected").css("cursor", "default")
			.siblings("span").removeClass("filt_selected").css("cursor", "pointer");
		// 根据过滤类型来获取按月统计数据
		GetAnyMonthStatDataByFiltType($(this).attr("filt_type"));
	});
	// 调整id为main的div容器的高度
	$("#main").css("height", "900px");
}

// 载入页面数据
function LoadPageData(){
	// 用户ID
	g_userId = $("#hidden_cur_userid").val();
	// 获取分类对应表
	GetCategoryMapping();
	// 生成收入和支出下拉选择框html
	CreateCategoryDropDownListHtml();

	// 获取本月统计数据
	getUserNotes("curmonth", true);
	// 获取月份统计数据
	getUserNotes("anymonth", true);
	// 获取大额统计数据
	getUserNotes("bigdata", true);
}

//
// 辅助函数
//

// 获取统计数据
function getUserNotes(statType, init){
	$.get("financial_stat_process.php",
		{"op_type":"query", "userid":g_userId, "stat_type":statType},
		function(data){
			if (data == ""){
				alert("获取统计信息失败");
			}else{
				if (statType == "curmonth")
					FillDataToTable4CurMonth(data);
				else if (statType == "anymonth"){
					FillDataToTable4AnyMonth(data);
					if (init != null && init == true){
						// 获取分类统计数据
						getUserNotes("category");
					}
				}
				else if (statType == "category")
					FillDataToTable4Category(data);
				else if (statType == "bigdata")
					FillDataToTable4BigData(data);
			}
	}, "json");
}

// 根据类别获取详细记录
function ViewCategoryDetail(obj){
	// 按钮显示变更
	ChangeButtonStatus(obj);

	var category = $(obj).attr("category");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"category", "category":category},
		function(data){
			if (!data){
				alert("获取统计信息失败");
			} else {
				// 显示详细结果
				ShowDetailItems(data);
			}
	}, "json");
}

// 查看月份详细数据
function ViewMonthDetail(obj){
	// 按钮显示变更
	ChangeButtonStatus(obj);

	var yearmonth = $(obj).attr("yearmonth");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"yearmonth", "yearmonth":yearmonth},
		function(data){
			if (!data){
				alert("获取统计信息失败");
			} else {
				// 显示详细结果
				ShowDetailItems(data);
			}
	}, "json");
}

// 查看当月某分类详细数据
function ViewCurMonthCategoryDetail(obj){
	// 按钮显示变更
	ChangeButtonStatus(obj);

	var yearmonth = $(obj).attr("yearmonth");
	var category = $(obj).attr("category");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"curmonthcategory", "yearmonth":yearmonth, "category":category},
		function(data){
			if (!data){
				alert("获取统计信息失败");
			} else {
				// 显示详细结果
				ShowDetailItems(data);
			}
	}, "json");
}

// 查看大额详细数据
function ViewBigDataDetail(obj){
	// 按钮显示变更
	ChangeButtonStatus(obj);

	$.get("financial_stat_process.php",
	{"op_type":"querydetail", "userid":g_userId, "stat_type":"bigdata"},
		function(data){
			if (!data){
				alert("获取统计信息失败");
			} else {
				// 显示详细结果
				ShowDetailItems(data);
			}
	}, "json");
}

// 查看阶段详细数据
function ViewStageDataDetail(obj){
	// 按钮显示变更
	ChangeButtonStatus(obj);

	$.get("financial_stat_process.php",
	{"op_type":"querydetail", "userid":g_userId, "stat_type":"stagedata"},
		function(data){
			if (!data){
				alert("获取统计信息失败");
			} else {
				// 显示详细结果
				ShowDetailItems(data);
			}
	}, "json");
}

// 更新详细条目数据信息
function UpdateDetailItem(obj){
	
	// 更新按钮文字
	$(obj).attr("disabled", "true");

	// 用的页面对象
	$ctgItemTr = $(obj).parent().parent();
	$categoryTd = $ctgItemTr.children("td:eq(1)");
	$statTypeTd = $ctgItemTr.children("td:eq(2)");
	$opTd = $ctgItemTr.children("td:eq(6)");

	var noteid = $(obj).attr("noteid");
	var newcategory = $categoryTd.children("select").val();
	var new_stat_type = $statTypeTd.children("select").val();
	$.post("financial_stat_process.php", 
		{"op_type":"updateitem", "stat_type":"update", "userid":g_userId, "noteid":noteid, "newcategory":newcategory, "newstattype":new_stat_type},
		function(data){
			if (data != "success"){
				alert("更新数据失败");
			} else {
				$categoryTd.empty().text(g_all_category_mapping[newcategory]);
				$statTypeTd.empty().text(g_stat_type_mapping[new_stat_type]);
				// 更新元素属性值
				$opTd.children("span").attr("category", newcategory).end().children("input").attr("oldcategory", newcategory);
				$opTd.children("span").attr("stattype", new_stat_type).end().children("input").attr("oldstattype", new_stat_type);
				// 更新元素可见性
				$opTd.children("span").css("display", "").end().children("input").css("display", "none");
				// 显示数据变更提示
				$("#div_data_changed_msg_bar").css("display", "");
			}
	});
}

// 重新载入全部统计数据
function ReloadAllStatData(){
	// 清空原有统计数据表格
	$("#tbl_cost_category tr:gt(0)").remove();
	$("#tbl_income_category tr:gt(0)").remove();
	$("#tbl_any_month tr:gt(0)").remove();
    $("#tbl_big_data tr:gt(0)").remove();
	$("#tbl_cost_items tr:gt(0)").remove();
	$("#tbl_income_items tr:gt(0)").remove();
	// 清空详细数据表格
	$("#tbl_detail_items tr:gt(0)").remove();
	
	// 重新载入
	// 获取本月统计数据
	getUserNotes("curmonth");
	// 获取月份统计数据
	$("#div_mon_filt_type>span[filt_type='normal']").click();
	// 获取分类统计数据
	GetStatDataByCategory();
	// 获取大额统计数据
	getUserNotes("bigdata");

	// 数据变更条隐藏
	$("#div_data_changed_msg_bar").css("display", "none");
}

// 改变“查看”按钮的显示状态
function ChangeButtonStatus(obj){
	// 更新其他项
	$("span.spanButton:hidden").css("display", "").siblings("span").css("display", "none");
	// 更改当前项
	$(obj).css("display", "none").siblings("span").css("display", "");
}

// 显示该类别下所有记录
function ShowDetailItems(data){
	// 清空详细列表中原有数据
	$("#tbl_detail_items tr:gt(0)").remove();
	// 切换显示文字
	var html = "";

	for (var i=0; i<data.length; i++){
		var moneyType = "", category = data[i]["category"], categoryText = "", noteid = data[i]["id"], statType = data[i]["stattype"];
		var opTdHtml = "<td><span onclick='UnlockDetailItemForEdit(this)' stattype='" + statType + "' category='" + category + "' style='cursor:pointer;' >[修改]</span><input type='button' oldstattype='" + statType + "' oldcategory='" + category + "' noteid='" + noteid + "' onclick='UpdateDetailItem(this)' value='更新' style='display:none;' disabled='true' /></td>>";

		if (data[i]["moneytype"] == "cost"){
			moneyType = "支出";
			categoryText = g_cost_mapping[category];
		} else {
			moneyType = "收入";
			categoryText = g_income_mapping[category];
		}

		var shortDate = data[i]["createtime"].substr(0, 10);
		var statType = g_stat_type_mapping[Number(data[i]["stattype"])];
		html += "<tr><td>" + moneyType + "</td><td>" + categoryText + "</td><td>" + statType + "</td><td>" + data[i]["amount"] + "</td><td>" + data[i]["remark"] + "</td><td>" + shortDate + "</td>" + opTdHtml + "</tr>";
	}

	$("#tbl_detail_items tr:first").after($(html));
}

// 解锁详细信息条目为可编辑状态
function UnlockDetailItemForEdit(obj){
	// 用的页面对象
	$ctgItemTr = $(obj).parent().parent();
	$ctgCategoryTd = $ctgItemTr.children("td:eq(1)");
	$statTypeTd = $ctgItemTr.children("td:eq(2)");
	$ctgOpTd = $ctgItemTr.children("td:eq(6)");
	// 将其他正在编辑的条目恢复初始
	ResDetailItemFromEdit();
	// 可选择状态的DropList
	var curItemCategory = $(obj).attr("category");
	var curItemStatType = $(obj).attr("stattype");
	
	var ctgDropListHtml = (curItemCategory.length < 3) ? g_cost_droplist : g_income_droplist;
	var $dropList = $(ctgDropListHtml);
	$dropList.val(curItemCategory);
	var statTypeDropListHtml = g_stat_type_droplist;
	var $statTypeDropList = $(statTypeDropListHtml);
	$statTypeDropList.val(curItemStatType);
	// 清空原有内容
	$ctgCategoryTd.text("");
	$statTypeTd.text("");
	// 插入输入框
	$ctgCategoryTd.append($dropList);
	$statTypeTd.append($statTypeDropList);
	// 更新操作按钮
	$ctgOpTd.children("span").css("display", "none").end().children("input").css("display", "");
}

// 更新操作按钮显示文字
function UpdateOpBtnStatus(obj){
	// 可用jq对象
	var $opInputObj = $(obj).parent().parent().children("td:eq(6)").children("input");
	var $categorySelectObj = $(obj).parent().parent().children("td:eq(1)").children("select");
	var $statTypeSelectObj = $(obj).parent().parent().children("td:eq(2)").children("select");

	if ($categorySelectObj == null || $statTypeSelectObj == null) return;

	var ctg_changed = $opInputObj.attr("oldcategory") == $categorySelectObj.val();
	var stat_type_changed = $opInputObj.attr("oldstattype") == $statTypeSelectObj.val();

	$opInputObj.attr("disabled", (ctg_changed && stat_type_changed));
}

// 将某行正在修改的分类恢复为初始未修改状态
function ResDetailItemFromEdit(){
	$editTr = $("#tbl_detail_items tr:has(td:has(select))");
	if ($editTr.length > 0){
		// 可用jq对象
		$ctgCategoryTd_edit = $editTr.children("td:eq(1)");
		$statTypeTd_edit = $editTr.children("td:eq(2)");
		$ctgOpTd_edit = $editTr.children("td:eq(6)");
		// 恢复
		var oldCategory = $ctgOpTd_edit.children("input").attr("oldcategory");
		var oldStatType = $ctgOpTd_edit.children("input").attr("oldstattype");
		$ctgCategoryTd_edit.empty().text(g_all_category_mapping[oldCategory]);
		$ctgOpTd_edit.empty().text(g_stat_type_mapping[oldStatType]);
		$ctgOpTd_edit.children("span").css("display", "").end().children("input").css("display", "none");
	}
};

// 填充“按分类统计”数据到页面
function FillDataToTable4Category(data){
	var cost_html = "", income_html = "";

	// 获取支出和收入总额
	var total_cost = 0, total_income = 0;
	for (var i=0; i<data.length; i++){
		var ctg = data[i]["category"];
		var total = Number(data[i]["total"]);
		if (typeof(ctg) == "string" && ctg.length == 3)
			total_income += total;
		else
			total_cost += total;
	}

	for (var i=0; i<data.length; i++){
		var category = data[i]["category"];
		// 支出的分类序号为1-2位数，收入的为3位开始，暂时以此为条件进行判断
		var trStart = "<tr><td>";
		var percentTd = GetPercentTdForCategoryStat(category, Number(data[i]["total"]), total_cost, total_income);
		var detailTd = "<td><span class='spanButton' category='" + category + "' onclick='ViewCategoryDetail(this)'>[查看]</span><span style='display:none;color:green;'>正在查看>></span></td>";
		var trEnd = "</td><td>" + data[i]["num"] + "</td><td>" + data[i]["total"] + "</td><td>" + Math.round(Number(data[i]["total"])/Number(g_recordMonthNum)) + "</td>" + percentTd + detailTd + "</tr>";
		if (category.length == 3) { 
			income_html += trStart + g_income_mapping[category] + trEnd;
		} else {
			cost_html += trStart + g_cost_mapping[category] + trEnd;
		}
	}

	// 添加无数据时的提示行
	var emptyTr = "<tr><td colspan='5' class='emptyTd'>暂无数据哦~</td></tr>";
	if (!cost_html.length){
		cost_html = emptyTr;
	}
	if (!income_html.length){
		income_html = emptyTr;
	}

	// 将输入插入到页面
	$("#tbl_cost_category tr:first").after($(cost_html));
	$("#tbl_income_category tr:first").after($(income_html));
}

// 获取分类总额占总额的百分比,自动区分收入和支出
function GetPercentTdForCategoryStat(category, ctg_total, total_cost, total_income){
	var html = "<td>";
	if (typeof(category) == "string" && ctg_total != null && total_income != null && total_cost != null){
			var total_num = category.length == 3 ? total_income : total_cost;
			html += (ctg_total / total_num * 100).toFixed(2) + "%";
		}
	return (html + "</td>");
}


// 填充“按月统计”数据到页面
function FillDataToTable4AnyMonth(data){
	// 清除按月份统计信息结果
	$("#tbl_any_month tr:gt(0)").remove();
	var html = "";

	// 循环读取数据
	var preMonth = data[0]["yearmonth"];
	var preIncome = 0, preCost = 0;
	g_recordMonthNum = 0; // 初始化全局月数记录数位0
	var detailTdBegin = "<td><span class='spanButton' yearmonth='";
	var detailTdEnd = "' onclick='ViewMonthDetail(this)'>[查看]</span><span style='display:none;color:green;'>正在查看>></span></td>";
	for (var i=0; i<data.length; i++){
		var curMonth = data[i]["yearmonth"], curType = data[i]["moneytype"], curVal = data[i]["total"];
		
		if (preMonth != curMonth){
			html += "<tr><td>" + ConvertDateString(preMonth) + "</td><td>" + preIncome + "</td><td>" + preCost + "</td>" + detailTdBegin + preMonth + detailTdEnd + "</tr>";
			preMonth = curMonth;
			preIncome = 0;
			preCost = 0;
			g_recordMonthNum++; // 累计月数+1
		}

		if (curType == "income")
			preIncome = curVal;
		else
			preCost = curVal;

		// 针对最后一行数据进行结果输出
		if (i == (data.length - 1)){
			html += "<tr><td>" + ConvertDateString(preMonth) + "</td><td>" + preIncome + "</td><td>" + preCost + "</td>" + detailTdBegin + preMonth + detailTdEnd + "</tr>";
		}
	}

	// 将输入插入到页面
	$("#tbl_any_month tr:first").after($(html));
}

// 将本月统计信息展现在页面的table中
function FillDataToTable4CurMonth(data){

	var incomeTblHtml = "";
	var costTblHtml = "";

	// 按照收入与支出类型拼组各自Html
	var income_total = 0;
	var cost_total = 0;
	for (var i=0; i<data.length; i++){
		var itemIndex = data[i]["category"];
		var itemNum = data[i]["num"];
		var itemTotal = Number(data[i]["total"]);

		var startPart = "<tr><td>";
		var endPart = "</td><td>" + itemNum + "</td><td>" + itemTotal + "</td><td><span class='spanButton' yearmonth='" + (new Date()).Format('yyyyMM') + "' category='" + itemIndex + "' onclick='ViewCurMonthCategoryDetail(this)'>[查看]</span><span style='display:none;color:green;'>正在查看>></span></td></tr>";;
		if (g_cost_mapping[itemIndex] != undefined){
			costTblHtml += startPart + g_cost_mapping[itemIndex] + endPart;
			cost_total += itemTotal;
		}else if (g_income_mapping[itemIndex] != undefined){
			incomeTblHtml += startPart + g_income_mapping[itemIndex] + endPart;
			income_total += itemTotal;
		}
	}

	// 添加总计行
	var totalTrStart = "<tr class='totalTr'><td>总计</td><td colspan='3'>";
	var totalTrEnd = "</td></tr>";
	if (costTblHtml){
		costTblHtml += totalTrStart + cost_total + totalTrEnd;
	}
	if (incomeTblHtml){
		incomeTblHtml += totalTrStart + income_total + totalTrEnd;
	}

	// 添加无数据时的提示行
	var emptyTr = "<tr><td colspan='4' class='emptyTd'>暂无数据哦~</td></tr>";
	if (!costTblHtml.length){
		costTblHtml = emptyTr;
	}
	if (!incomeTblHtml.length){
		incomeTblHtml = emptyTr;
	}
	
	// 将输入插入到页面
	$("#tbl_cost_items tr:first").after($(costTblHtml));
	$("#tbl_income_items tr:first").after($(incomeTblHtml));
}

// 将大额统计信息展现在页面table中
function FillDataToTable4BigData(data){
	var html = "";

	var itemNum = Number(data[0]["num"]);
	var itemTotal = Number(data[0]["total"]);
	var itemNum2 = Number(data[1]["num"]);
	var itemTotal2 = Number(data[1]["total"]);
	if (itemNum == null || itemTotal == null || itemNum2 == null || itemTotal2 == null) return;

	if (itemNum > 0 && itemTotal > 0)
		html = "<tr><td>大额</td><td>" + itemNum + "</td><td>" + itemTotal + "</td><td><span class='spanButton' onclick='ViewBigDataDetail(this)'>[查看]</span><span style='display:none;color:green;'>正在查看>></span></td></tr>";

	// 添加阶段统计部分
	if (itemNum2 > 0 && itemTotal2 > 0)
		html += "<tr><td>阶段</td><td>" + itemNum2 + "</td><td>" + itemTotal2 + "</td><td><span class='spanButton' onclick='ViewStageDataDetail(this)'>[查看]</span><span style='display:none;color:green;'>正在查看>></span></td></tr>";

	// 添加无数据时的提示行
	var emptyTr = "<tr><td colspan='4' class='emptyTd'>暂无数据哦~</td></tr>";
	if (!html.length){
		html = emptyTr;
	}

	// 将输入插入到页面
	$("#tbl_big_data tr:first").after($(html));
}

// 获取用户分类对应数组
function GetCategoryMapping(){
	var category_data = $("#hidden_user_category_data").val();
	if (category_data == null || category_data == "")
		return;
	// Unicode转换GB2312格式
	category_data = GB2312UnicodeConverter.ToGB2312(category_data);
	
	// 获取收入与支出的对应数组
	var costCategoryArr = null;
	var incomeCategoryArr = null;
	var categoryArr = category_data.split(";");
	if (categoryArr.length == 2){
		costCategoryArr = categoryArr[0].split(",");
		incomeCategoryArr = categoryArr[1].split(",");
	}

	for (var i=0; i<costCategoryArr.length; i++){
		var arr = costCategoryArr[i].split(":");
		g_cost_mapping[arr[0]] = arr[1];
		g_all_category_mapping[arr[0]] = arr[1];
	}
	for (var i=0; i<incomeCategoryArr.length; i++){
		var arr = incomeCategoryArr[i].split(":");
		g_income_mapping[arr[0]] = arr[1];
		g_all_category_mapping[arr[0]] = arr[1];
	}
}

// 获取用户分类下拉列表Html
function CreateCategoryDropDownListHtml(){
	var htmlBegin = "<select style='width:60px;' onchange='UpdateOpBtnStatus(this)'>";
	var htmlEnd = "</select>";

	g_cost_droplist = htmlBegin;
	g_income_droplist = htmlBegin;

	for(var item in g_cost_mapping){
		g_cost_droplist += "<option value='" + item + "'>" + g_cost_mapping[item] + "</option>";
	}
	for(var item in g_income_mapping){
		g_income_droplist += "<option value='" + item + "'>" + g_income_mapping[item] + "</option>";
	}

	g_cost_droplist += htmlEnd;
	g_income_droplist += htmlEnd;
}

// 将格式为201203的日期格式，转化为2012年03月
function ConvertDateString(dateStr){
	if (dateStr.length != 6) 
		return dateStr;

	var year = dateStr.substr(0, 4);
	var month = dateStr.substr(4);
	return year + "年" + month + "月";
}

// 对Date的扩展，将 Date 转化为指定格式的String   
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，   
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)   
// 例子：   
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423   
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18   
Date.prototype.Format = function(fmt)   
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


// 为按分类统计过滤大额类型数据
function FiltBigDataItemForCategoryStat(){
	$.get("financial_stat_process.php",
		{ "op_type":"query", "userid":g_userId, "stat_type":"category", "filt_tpe":"bigdata" },
		function(data){
			if (data == ""){
				alert("获取统计信息失败");
			}else{
				// 清除按类别统计信息结果
				$("#tbl_cost_category tr:gt(0)").remove();
				$("#tbl_income_category tr:gt(0)").remove();
				// 重新插入数据
				FillDataToTable4Category(data);
				// 修改过滤按钮的显示
				$("#cat_filt_btn").hide(0);
				$("#cat_normal_btn").show(0);
				// 更新全局参数
				g_cat_filt_big_data = true;
			}
	}, "json");

    // 阻止默认事件
	event.preventDefault()
}


// 按分类统计获取数据（不过滤任何数据）
function GetStatDataByCategory(){
	// 清除按类别统计信息结果
	$("#tbl_cost_category tr:gt(0)").remove();
	$("#tbl_income_category tr:gt(0)").remove();
	// 查询获取新数据
	getUserNotes("category");
	// 修改过滤按钮的显示
	$("#cat_normal_btn").hide(0);
	$("#cat_filt_btn").show(0);
	// 更新全局参数
	g_cat_filt_big_data = false;
    // 阻止默认事件
	event.preventDefault()
}

// 根据过滤类型来获取按月统计数据
function FiltMonthStatData(filt_type){
	if (filt_type == null) return;
	$.get("financial_stat_process.php",
		{ "op_type":"query", "userid":g_userId, "stat_type":"anymonth", "filt_tpe":filt_type },
		function(data){
			if (data == ""){
				alert("获取统计信息失败");
			}else{
				// 清除按月份统计信息结果
				$("#tbl_any_month tr:gt(0)").remove();
				// 重新插入数据
				FillDataToTable4AnyMonth(data);
			}
	}, "json");
}

// 为按月份统计过滤大额类型数据
function FiltBigDataItemForMonthStat(){
	FiltMonthStatData("bigdata");
	// 更新全局参数
	g_mon_filt_big_data = true;
	g_mon_avg_stage_data = false;
}


// 按月份统计获取数据（正常查询）
function GetStatDataByMonth(){
	// 清除按月份统计信息结果
	$("#tbl_any_month tr:gt(0)").remove();
	// 查询获取新数据
	getUserNotes("anymonth");
	// 更新全局参数
	g_mon_filt_big_data = false;
	g_mon_avg_stage_data = false;
}

// 按月份统计获取数据（日常生活模式，即过滤大额，并平均化阶段支出项）
function FiltBigDataAndAvgStageForMonthStat(){
	FiltMonthStatData("daily");
	// 更新全局参数
	g_mon_filt_big_data = true;
	g_mon_avg_stage_data = true;
}

// 根据过滤类型来获取按月统计数据
function GetAnyMonthStatDataByFiltType(filt_type){
	if (filt_type != null && filt_type == "bigdata"){ // 过滤大额
		FiltBigDataItemForMonthStat();
	} else if (filt_type != null && filt_type == "bigdata_and_stage"){ // 日常生活模式，即过滤大额，并平均化阶段支出项
		FiltBigDataAndAvgStageForMonthStat();
	} else { // 正常查询
		GetStatDataByMonth();
	}
}