//
// financial statistic page javascript file
// 20130318 by yanggl
// 

//
// ȫ�ֱ���
//

var g_userId = null; // userid
var g_cost_mapping = new Array(), g_income_mapping = new Array(), g_all_category_mapping = new Array(); // �û������Ӧ��
var g_stat_type_mapping = ["", "�ճ�", "�׶�", "���"];
var g_cost_droplist = "", g_income_droplist = ""; // �û����������б�html
var g_stat_type_droplist = "<select style='width:60px;' onchange='UpdateOpBtnStatus(this)'><option value='1'>�ճ�</option><option value='2'>�׶�</option><option value='3'>���</option></select>";
var g_recordMonthNum = null; // �û���¼����
var g_cat_filt_big_data = false; // ������ͳ���еĹ��˴�����ݿ���
var g_mon_avg_stage_data = false; // ���·�ͳ���е�ƽ���׶�֧����Ŀ���
var g_mon_filt_big_data = false; // ���·�ͳ���еĹ��˴�����ݿ���

// Unicode������GB2312ת������
var GB2312UnicodeConverter = {
	ToUnicode: function (str) {
		return escape(str).toLocaleLowerCase().replace(/%u/gi, '\\u');
	}
	, ToGB2312: function (str) {
		return unescape(str.replace(/\\u/gi, '%u'));
	}
};

//
// ��ʼ��
//

window.onload = function(){

	// ����ҳ������
	LoadPageData();
	// ��ʼ��ҳ��Ԫ���¼�����ʽ
	InitPageStyleAndEvent();
	// ִ��Ĭ�϶���
	ExecuteDefaultBehavior();
};


// ִ��Ĭ�϶���
function ExecuteDefaultBehavior(){
	// Ĭ��ѡ����ͳ����
	$("#div_quick_opration_box>h3>span:first").click();
	$("#div_mon_filt_type>span[filt_type='normal']").click();
}

// ��ʼ��ҳ��Ԫ���¼�����ʽ
function InitPageStyleAndEvent(){
	// 1. ͳ�����л�tab�¼�����ʽ
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
	// 2. ����ͳ���л�����ģʽ��ť�¼�����ʽ
	$("#div_mon_filt_type>span").bind("click", function(){
		$(this).addClass("filt_selected").css("cursor", "default")
			.siblings("span").removeClass("filt_selected").css("cursor", "pointer");
		// ���ݹ�����������ȡ����ͳ������
		GetAnyMonthStatDataByFiltType($(this).attr("filt_type"));
	});
	// ����idΪmain��div�����ĸ߶�
	$("#main").css("height", "900px");
}

// ����ҳ������
function LoadPageData(){
	// �û�ID
	g_userId = $("#hidden_cur_userid").val();
	// ��ȡ�����Ӧ��
	GetCategoryMapping();
	// ���������֧������ѡ���html
	CreateCategoryDropDownListHtml();

	// ��ȡ����ͳ������
	getUserNotes("curmonth", true);
	// ��ȡ�·�ͳ������
	getUserNotes("anymonth", true);
	// ��ȡ���ͳ������
	getUserNotes("bigdata", true);
}

//
// ��������
//

// ��ȡͳ������
function getUserNotes(statType, init){
	$.get("financial_stat_process.php",
		{"op_type":"query", "userid":g_userId, "stat_type":statType},
		function(data){
			if (data == ""){
				alert("��ȡͳ����Ϣʧ��");
			}else{
				if (statType == "curmonth")
					FillDataToTable4CurMonth(data);
				else if (statType == "anymonth"){
					FillDataToTable4AnyMonth(data);
					if (init != null && init == true){
						// ��ȡ����ͳ������
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

// ��������ȡ��ϸ��¼
function ViewCategoryDetail(obj){
	// ��ť��ʾ���
	ChangeButtonStatus(obj);

	var category = $(obj).attr("category");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"category", "category":category},
		function(data){
			if (!data){
				alert("��ȡͳ����Ϣʧ��");
			} else {
				// ��ʾ��ϸ���
				ShowDetailItems(data);
			}
	}, "json");
}

// �鿴�·���ϸ����
function ViewMonthDetail(obj){
	// ��ť��ʾ���
	ChangeButtonStatus(obj);

	var yearmonth = $(obj).attr("yearmonth");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"yearmonth", "yearmonth":yearmonth},
		function(data){
			if (!data){
				alert("��ȡͳ����Ϣʧ��");
			} else {
				// ��ʾ��ϸ���
				ShowDetailItems(data);
			}
	}, "json");
}

// �鿴����ĳ������ϸ����
function ViewCurMonthCategoryDetail(obj){
	// ��ť��ʾ���
	ChangeButtonStatus(obj);

	var yearmonth = $(obj).attr("yearmonth");
	var category = $(obj).attr("category");
	$.get("financial_stat_process.php", 
		{"op_type":"querydetail", "userid":g_userId, "stat_type":"curmonthcategory", "yearmonth":yearmonth, "category":category},
		function(data){
			if (!data){
				alert("��ȡͳ����Ϣʧ��");
			} else {
				// ��ʾ��ϸ���
				ShowDetailItems(data);
			}
	}, "json");
}

// �鿴�����ϸ����
function ViewBigDataDetail(obj){
	// ��ť��ʾ���
	ChangeButtonStatus(obj);

	$.get("financial_stat_process.php",
	{"op_type":"querydetail", "userid":g_userId, "stat_type":"bigdata"},
		function(data){
			if (!data){
				alert("��ȡͳ����Ϣʧ��");
			} else {
				// ��ʾ��ϸ���
				ShowDetailItems(data);
			}
	}, "json");
}

// �鿴�׶���ϸ����
function ViewStageDataDetail(obj){
	// ��ť��ʾ���
	ChangeButtonStatus(obj);

	$.get("financial_stat_process.php",
	{"op_type":"querydetail", "userid":g_userId, "stat_type":"stagedata"},
		function(data){
			if (!data){
				alert("��ȡͳ����Ϣʧ��");
			} else {
				// ��ʾ��ϸ���
				ShowDetailItems(data);
			}
	}, "json");
}

// ������ϸ��Ŀ������Ϣ
function UpdateDetailItem(obj){
	
	// ���°�ť����
	$(obj).attr("disabled", "true");

	// �õ�ҳ�����
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
				alert("��������ʧ��");
			} else {
				$categoryTd.empty().text(g_all_category_mapping[newcategory]);
				$statTypeTd.empty().text(g_stat_type_mapping[new_stat_type]);
				// ����Ԫ������ֵ
				$opTd.children("span").attr("category", newcategory).end().children("input").attr("oldcategory", newcategory);
				$opTd.children("span").attr("stattype", new_stat_type).end().children("input").attr("oldstattype", new_stat_type);
				// ����Ԫ�ؿɼ���
				$opTd.children("span").css("display", "").end().children("input").css("display", "none");
				// ��ʾ���ݱ����ʾ
				$("#div_data_changed_msg_bar").css("display", "");
			}
	});
}

// ��������ȫ��ͳ������
function ReloadAllStatData(){
	// ���ԭ��ͳ�����ݱ��
	$("#tbl_cost_category tr:gt(0)").remove();
	$("#tbl_income_category tr:gt(0)").remove();
	$("#tbl_any_month tr:gt(0)").remove();
    $("#tbl_big_data tr:gt(0)").remove();
	$("#tbl_cost_items tr:gt(0)").remove();
	$("#tbl_income_items tr:gt(0)").remove();
	// �����ϸ���ݱ��
	$("#tbl_detail_items tr:gt(0)").remove();
	
	// ��������
	// ��ȡ����ͳ������
	getUserNotes("curmonth");
	// ��ȡ�·�ͳ������
	$("#div_mon_filt_type>span[filt_type='normal']").click();
	// ��ȡ����ͳ������
	GetStatDataByCategory();
	// ��ȡ���ͳ������
	getUserNotes("bigdata");

	// ���ݱ��������
	$("#div_data_changed_msg_bar").css("display", "none");
}

// �ı䡰�鿴����ť����ʾ״̬
function ChangeButtonStatus(obj){
	// ����������
	$("span.spanButton:hidden").css("display", "").siblings("span").css("display", "none");
	// ���ĵ�ǰ��
	$(obj).css("display", "none").siblings("span").css("display", "");
}

// ��ʾ����������м�¼
function ShowDetailItems(data){
	// �����ϸ�б���ԭ������
	$("#tbl_detail_items tr:gt(0)").remove();
	// �л���ʾ����
	var html = "";

	for (var i=0; i<data.length; i++){
		var moneyType = "", category = data[i]["category"], categoryText = "", noteid = data[i]["id"], statType = data[i]["stattype"];
		var opTdHtml = "<td><span onclick='UnlockDetailItemForEdit(this)' stattype='" + statType + "' category='" + category + "' style='cursor:pointer;' >[�޸�]</span><input type='button' oldstattype='" + statType + "' oldcategory='" + category + "' noteid='" + noteid + "' onclick='UpdateDetailItem(this)' value='����' style='display:none;' disabled='true' /></td>>";

		if (data[i]["moneytype"] == "cost"){
			moneyType = "֧��";
			categoryText = g_cost_mapping[category];
		} else {
			moneyType = "����";
			categoryText = g_income_mapping[category];
		}

		var shortDate = data[i]["createtime"].substr(0, 10);
		var statType = g_stat_type_mapping[Number(data[i]["stattype"])];
		html += "<tr><td>" + moneyType + "</td><td>" + categoryText + "</td><td>" + statType + "</td><td>" + data[i]["amount"] + "</td><td>" + data[i]["remark"] + "</td><td>" + shortDate + "</td>" + opTdHtml + "</tr>";
	}

	$("#tbl_detail_items tr:first").after($(html));
}

// ������ϸ��Ϣ��ĿΪ�ɱ༭״̬
function UnlockDetailItemForEdit(obj){
	// �õ�ҳ�����
	$ctgItemTr = $(obj).parent().parent();
	$ctgCategoryTd = $ctgItemTr.children("td:eq(1)");
	$statTypeTd = $ctgItemTr.children("td:eq(2)");
	$ctgOpTd = $ctgItemTr.children("td:eq(6)");
	// ���������ڱ༭����Ŀ�ָ���ʼ
	ResDetailItemFromEdit();
	// ��ѡ��״̬��DropList
	var curItemCategory = $(obj).attr("category");
	var curItemStatType = $(obj).attr("stattype");
	
	var ctgDropListHtml = (curItemCategory.length < 3) ? g_cost_droplist : g_income_droplist;
	var $dropList = $(ctgDropListHtml);
	$dropList.val(curItemCategory);
	var statTypeDropListHtml = g_stat_type_droplist;
	var $statTypeDropList = $(statTypeDropListHtml);
	$statTypeDropList.val(curItemStatType);
	// ���ԭ������
	$ctgCategoryTd.text("");
	$statTypeTd.text("");
	// ���������
	$ctgCategoryTd.append($dropList);
	$statTypeTd.append($statTypeDropList);
	// ���²�����ť
	$ctgOpTd.children("span").css("display", "none").end().children("input").css("display", "");
}

// ���²�����ť��ʾ����
function UpdateOpBtnStatus(obj){
	// ����jq����
	var $opInputObj = $(obj).parent().parent().children("td:eq(6)").children("input");
	var $categorySelectObj = $(obj).parent().parent().children("td:eq(1)").children("select");
	var $statTypeSelectObj = $(obj).parent().parent().children("td:eq(2)").children("select");

	if ($categorySelectObj == null || $statTypeSelectObj == null) return;

	var ctg_changed = $opInputObj.attr("oldcategory") == $categorySelectObj.val();
	var stat_type_changed = $opInputObj.attr("oldstattype") == $statTypeSelectObj.val();

	$opInputObj.attr("disabled", (ctg_changed && stat_type_changed));
}

// ��ĳ�������޸ĵķ���ָ�Ϊ��ʼδ�޸�״̬
function ResDetailItemFromEdit(){
	$editTr = $("#tbl_detail_items tr:has(td:has(select))");
	if ($editTr.length > 0){
		// ����jq����
		$ctgCategoryTd_edit = $editTr.children("td:eq(1)");
		$statTypeTd_edit = $editTr.children("td:eq(2)");
		$ctgOpTd_edit = $editTr.children("td:eq(6)");
		// �ָ�
		var oldCategory = $ctgOpTd_edit.children("input").attr("oldcategory");
		var oldStatType = $ctgOpTd_edit.children("input").attr("oldstattype");
		$ctgCategoryTd_edit.empty().text(g_all_category_mapping[oldCategory]);
		$ctgOpTd_edit.empty().text(g_stat_type_mapping[oldStatType]);
		$ctgOpTd_edit.children("span").css("display", "").end().children("input").css("display", "none");
	}
};

// ��䡰������ͳ�ơ����ݵ�ҳ��
function FillDataToTable4Category(data){
	var cost_html = "", income_html = "";

	// ��ȡ֧���������ܶ�
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
		// ֧���ķ������Ϊ1-2λ���������Ϊ3λ��ʼ����ʱ�Դ�Ϊ���������ж�
		var trStart = "<tr><td>";
		var percentTd = GetPercentTdForCategoryStat(category, Number(data[i]["total"]), total_cost, total_income);
		var detailTd = "<td><span class='spanButton' category='" + category + "' onclick='ViewCategoryDetail(this)'>[�鿴]</span><span style='display:none;color:green;'>���ڲ鿴>></span></td>";
		var trEnd = "</td><td>" + data[i]["num"] + "</td><td>" + data[i]["total"] + "</td><td>" + Math.round(Number(data[i]["total"])/Number(g_recordMonthNum)) + "</td>" + percentTd + detailTd + "</tr>";
		if (category.length == 3) { 
			income_html += trStart + g_income_mapping[category] + trEnd;
		} else {
			cost_html += trStart + g_cost_mapping[category] + trEnd;
		}
	}

	// ���������ʱ����ʾ��
	var emptyTr = "<tr><td colspan='5' class='emptyTd'>��������Ŷ~</td></tr>";
	if (!cost_html.length){
		cost_html = emptyTr;
	}
	if (!income_html.length){
		income_html = emptyTr;
	}

	// ��������뵽ҳ��
	$("#tbl_cost_category tr:first").after($(cost_html));
	$("#tbl_income_category tr:first").after($(income_html));
}

// ��ȡ�����ܶ�ռ�ܶ�İٷֱ�,�Զ����������֧��
function GetPercentTdForCategoryStat(category, ctg_total, total_cost, total_income){
	var html = "<td>";
	if (typeof(category) == "string" && ctg_total != null && total_income != null && total_cost != null){
			var total_num = category.length == 3 ? total_income : total_cost;
			html += (ctg_total / total_num * 100).toFixed(2) + "%";
		}
	return (html + "</td>");
}


// ��䡰����ͳ�ơ����ݵ�ҳ��
function FillDataToTable4AnyMonth(data){
	// ������·�ͳ����Ϣ���
	$("#tbl_any_month tr:gt(0)").remove();
	var html = "";

	// ѭ����ȡ����
	var preMonth = data[0]["yearmonth"];
	var preIncome = 0, preCost = 0;
	g_recordMonthNum = 0; // ��ʼ��ȫ��������¼��λ0
	var detailTdBegin = "<td><span class='spanButton' yearmonth='";
	var detailTdEnd = "' onclick='ViewMonthDetail(this)'>[�鿴]</span><span style='display:none;color:green;'>���ڲ鿴>></span></td>";
	for (var i=0; i<data.length; i++){
		var curMonth = data[i]["yearmonth"], curType = data[i]["moneytype"], curVal = data[i]["total"];
		
		if (preMonth != curMonth){
			html += "<tr><td>" + ConvertDateString(preMonth) + "</td><td>" + preIncome + "</td><td>" + preCost + "</td>" + detailTdBegin + preMonth + detailTdEnd + "</tr>";
			preMonth = curMonth;
			preIncome = 0;
			preCost = 0;
			g_recordMonthNum++; // �ۼ�����+1
		}

		if (curType == "income")
			preIncome = curVal;
		else
			preCost = curVal;

		// ������һ�����ݽ��н�����
		if (i == (data.length - 1)){
			html += "<tr><td>" + ConvertDateString(preMonth) + "</td><td>" + preIncome + "</td><td>" + preCost + "</td>" + detailTdBegin + preMonth + detailTdEnd + "</tr>";
		}
	}

	// ��������뵽ҳ��
	$("#tbl_any_month tr:first").after($(html));
}

// ������ͳ����Ϣչ����ҳ���table��
function FillDataToTable4CurMonth(data){

	var incomeTblHtml = "";
	var costTblHtml = "";

	// ����������֧������ƴ�����Html
	var income_total = 0;
	var cost_total = 0;
	for (var i=0; i<data.length; i++){
		var itemIndex = data[i]["category"];
		var itemNum = data[i]["num"];
		var itemTotal = Number(data[i]["total"]);

		var startPart = "<tr><td>";
		var endPart = "</td><td>" + itemNum + "</td><td>" + itemTotal + "</td><td><span class='spanButton' yearmonth='" + (new Date()).Format('yyyyMM') + "' category='" + itemIndex + "' onclick='ViewCurMonthCategoryDetail(this)'>[�鿴]</span><span style='display:none;color:green;'>���ڲ鿴>></span></td></tr>";;
		if (g_cost_mapping[itemIndex] != undefined){
			costTblHtml += startPart + g_cost_mapping[itemIndex] + endPart;
			cost_total += itemTotal;
		}else if (g_income_mapping[itemIndex] != undefined){
			incomeTblHtml += startPart + g_income_mapping[itemIndex] + endPart;
			income_total += itemTotal;
		}
	}

	// ����ܼ���
	var totalTrStart = "<tr class='totalTr'><td>�ܼ�</td><td colspan='3'>";
	var totalTrEnd = "</td></tr>";
	if (costTblHtml){
		costTblHtml += totalTrStart + cost_total + totalTrEnd;
	}
	if (incomeTblHtml){
		incomeTblHtml += totalTrStart + income_total + totalTrEnd;
	}

	// ���������ʱ����ʾ��
	var emptyTr = "<tr><td colspan='4' class='emptyTd'>��������Ŷ~</td></tr>";
	if (!costTblHtml.length){
		costTblHtml = emptyTr;
	}
	if (!incomeTblHtml.length){
		incomeTblHtml = emptyTr;
	}
	
	// ��������뵽ҳ��
	$("#tbl_cost_items tr:first").after($(costTblHtml));
	$("#tbl_income_items tr:first").after($(incomeTblHtml));
}

// �����ͳ����Ϣչ����ҳ��table��
function FillDataToTable4BigData(data){
	var html = "";

	var itemNum = Number(data[0]["num"]);
	var itemTotal = Number(data[0]["total"]);
	var itemNum2 = Number(data[1]["num"]);
	var itemTotal2 = Number(data[1]["total"]);
	if (itemNum == null || itemTotal == null || itemNum2 == null || itemTotal2 == null) return;

	if (itemNum > 0 && itemTotal > 0)
		html = "<tr><td>���</td><td>" + itemNum + "</td><td>" + itemTotal + "</td><td><span class='spanButton' onclick='ViewBigDataDetail(this)'>[�鿴]</span><span style='display:none;color:green;'>���ڲ鿴>></span></td></tr>";

	// ��ӽ׶�ͳ�Ʋ���
	if (itemNum2 > 0 && itemTotal2 > 0)
		html += "<tr><td>�׶�</td><td>" + itemNum2 + "</td><td>" + itemTotal2 + "</td><td><span class='spanButton' onclick='ViewStageDataDetail(this)'>[�鿴]</span><span style='display:none;color:green;'>���ڲ鿴>></span></td></tr>";

	// ���������ʱ����ʾ��
	var emptyTr = "<tr><td colspan='4' class='emptyTd'>��������Ŷ~</td></tr>";
	if (!html.length){
		html = emptyTr;
	}

	// ��������뵽ҳ��
	$("#tbl_big_data tr:first").after($(html));
}

// ��ȡ�û������Ӧ����
function GetCategoryMapping(){
	var category_data = $("#hidden_user_category_data").val();
	if (category_data == null || category_data == "")
		return;
	// Unicodeת��GB2312��ʽ
	category_data = GB2312UnicodeConverter.ToGB2312(category_data);
	
	// ��ȡ������֧���Ķ�Ӧ����
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

// ��ȡ�û����������б�Html
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

// ����ʽΪ201203�����ڸ�ʽ��ת��Ϊ2012��03��
function ConvertDateString(dateStr){
	if (dateStr.length != 6) 
		return dateStr;

	var year = dateStr.substr(0, 4);
	var month = dateStr.substr(4);
	return year + "��" + month + "��";
}

// ��Date����չ���� Date ת��Ϊָ����ʽ��String   
// ��(M)����(d)��Сʱ(h)����(m)����(s)������(q) ������ 1-2 ��ռλ����   
// ��(y)������ 1-4 ��ռλ��������(S)ֻ���� 1 ��ռλ��(�� 1-3 λ������)   
// ���ӣ�   
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423   
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18   
Date.prototype.Format = function(fmt)   
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


// Ϊ������ͳ�ƹ��˴����������
function FiltBigDataItemForCategoryStat(){
	$.get("financial_stat_process.php",
		{ "op_type":"query", "userid":g_userId, "stat_type":"category", "filt_tpe":"bigdata" },
		function(data){
			if (data == ""){
				alert("��ȡͳ����Ϣʧ��");
			}else{
				// ��������ͳ����Ϣ���
				$("#tbl_cost_category tr:gt(0)").remove();
				$("#tbl_income_category tr:gt(0)").remove();
				// ���²�������
				FillDataToTable4Category(data);
				// �޸Ĺ��˰�ť����ʾ
				$("#cat_filt_btn").hide(0);
				$("#cat_normal_btn").show(0);
				// ����ȫ�ֲ���
				g_cat_filt_big_data = true;
			}
	}, "json");

    // ��ֹĬ���¼�
	event.preventDefault()
}


// ������ͳ�ƻ�ȡ���ݣ��������κ����ݣ�
function GetStatDataByCategory(){
	// ��������ͳ����Ϣ���
	$("#tbl_cost_category tr:gt(0)").remove();
	$("#tbl_income_category tr:gt(0)").remove();
	// ��ѯ��ȡ������
	getUserNotes("category");
	// �޸Ĺ��˰�ť����ʾ
	$("#cat_normal_btn").hide(0);
	$("#cat_filt_btn").show(0);
	// ����ȫ�ֲ���
	g_cat_filt_big_data = false;
    // ��ֹĬ���¼�
	event.preventDefault()
}

// ���ݹ�����������ȡ����ͳ������
function FiltMonthStatData(filt_type){
	if (filt_type == null) return;
	$.get("financial_stat_process.php",
		{ "op_type":"query", "userid":g_userId, "stat_type":"anymonth", "filt_tpe":filt_type },
		function(data){
			if (data == ""){
				alert("��ȡͳ����Ϣʧ��");
			}else{
				// ������·�ͳ����Ϣ���
				$("#tbl_any_month tr:gt(0)").remove();
				// ���²�������
				FillDataToTable4AnyMonth(data);
			}
	}, "json");
}

// Ϊ���·�ͳ�ƹ��˴����������
function FiltBigDataItemForMonthStat(){
	FiltMonthStatData("bigdata");
	// ����ȫ�ֲ���
	g_mon_filt_big_data = true;
	g_mon_avg_stage_data = false;
}


// ���·�ͳ�ƻ�ȡ���ݣ�������ѯ��
function GetStatDataByMonth(){
	// ������·�ͳ����Ϣ���
	$("#tbl_any_month tr:gt(0)").remove();
	// ��ѯ��ȡ������
	getUserNotes("anymonth");
	// ����ȫ�ֲ���
	g_mon_filt_big_data = false;
	g_mon_avg_stage_data = false;
}

// ���·�ͳ�ƻ�ȡ���ݣ��ճ�����ģʽ�������˴���ƽ�����׶�֧���
function FiltBigDataAndAvgStageForMonthStat(){
	FiltMonthStatData("daily");
	// ����ȫ�ֲ���
	g_mon_filt_big_data = true;
	g_mon_avg_stage_data = true;
}

// ���ݹ�����������ȡ����ͳ������
function GetAnyMonthStatDataByFiltType(filt_type){
	if (filt_type != null && filt_type == "bigdata"){ // ���˴��
		FiltBigDataItemForMonthStat();
	} else if (filt_type != null && filt_type == "bigdata_and_stage"){ // �ճ�����ģʽ�������˴���ƽ�����׶�֧����
		FiltBigDataAndAvgStageForMonthStat();
	} else { // ������ѯ
		GetStatDataByMonth();
	}
}