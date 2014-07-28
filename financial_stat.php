<?php
	include("note_sc_fns.php");
	// 检查用户合法性
	check_valid_user();
	// 显示页头
	do_html_header("financial");

	echo '<input id="hidden_cur_userid" type="hidden" value='.$_SESSION["current_username"].' />';
	echo '<input id="hidden_user_category_data" type="hidden" value='.$_SESSION["user_category_data"].' />';
?>

	<!--载入页面特定脚本和样式文件-->
	<script type="text/javascript" src="script/financial_stat.js"></script>
	<script src="script/jquery-ui.min.js"></script>
	<link type="text/css" href="css/financial_stat.css" rel="Stylesheet" />

	<!--用户自定义配置操作框-->
	<div id="div_statistic_opration_box">
		<a href="financial.php" style="text-decoration:none;">返回 我的小账本</a>
	</div>
	<div class="splitLine"></div>

	<div id="div_quick_opration_box">
		<h3>
			<span val="cur_month">本月统计</span> | 			
			<span val="category">按类统计</span> | 
			<span val="any_month">按月统计</span> | 
			<!--<span val="any_year">按年统计</span> | -->
			<span val="big_data">特殊类型统计</span>
		</h3>
		<div class="splitLine2"></div>
		<div id="div_cur_month_stat_box">
			<table id="tbl_cost_items" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:80px;">支出类别</td>
					<td style="width:80px;">记录次数</td>
					<td style="width:80px;">小计(元)</td>
					<td style="width:70px;">详细</td>
				</tr>
			</table>
			<br/>
			<br/>
			<table id="tbl_income_items">
				<tr class="titleTr">
					<td style="width:80px;">收入类别</td>
					<td style="width:80px;">记录次数</td>
					<td style="width:80px;">小计(元)</td>
					<td style="width:70px;">详细</td>
				</tr>
			</table>
		</div>
		<div id="div_category_stat_box">
			<div style="margin-top:10px; margin-bottom:-5px;">
				<label>快捷操作:</label>
				<a id="cat_filt_btn" href="#" style="text-decoration:none;" onclick="FiltBigDataItemForCategoryStat()" >[过滤大额]</a>
				<a id="cat_normal_btn" href="#" style="text-decoration:none; display:none;" onclick="GetStatDataByCategory()" >[正常统计]</a>
			</div>
			<table id="tbl_cost_category" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:80px;">支出类别</td>
					<td style="width:60px;">记录次数</td>
					<td style="width:80px;">总计(元)</td>
					<td style="width:80px;">月平均(元)</td>
					<td style="width:60px">百分比</td>
					<td style="width:70px">详细</td>
				</tr>
			</table>
			<br/>
			<br/>
			<table id="tbl_income_category">
				<tr class="titleTr">
					<td style="width:80px;">收入类别</td>
					<td style="width:60px;">记录次数</td>
					<td style="width:80px;">总计(元)</td>
					<td style="width:80px;">月平均(元)</td>
					<td style="width:60px">百分比</td>
					<td style="width:70px">详细</td>
				</tr>
			</table>
		</div>
		<div id="div_any_month_stat_box">
			<div id="div_mon_filt_type" style="margin-top:10px; margin-bottom:-5px;">
				<label>模式选择:</label>
				<span filt_type="normal" class="filt_unselected">[正常统计]</span>
				&nbsp;|&nbsp;
				<span filt_type="bigdata" class="filt_unselected">[过滤大额]</span>
				&nbsp;|&nbsp;
				<span filt_type="bigdata_and_stage" class="filt_unselected">[日常模式]</span>
			</div>
			<table id="tbl_any_month" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:100px;">月份</td>
					<td style="width:100px;">收入(元)</td>
					<td style="width:100px;">支出(元)</td>
					<td style="width:70px">详细</td>
				</tr>
			</table>
		</div>
		<div id="div_any_year_stat_box">
			<div>

			</div>
		</div>
		<div id="div_big_data_stat_box">
			<table id="tbl_big_data" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:80px;">支出类别</td>
					<td style="width:80px;">记录次数</td>
					<td style="width:80px;">小计(元)</td>
					<td style="width:70px">详细</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="div_quick_detail_result_box">
		<div>
			<h3>
				<span id="span_detail_result_title">详细内容</span>
			</h3>
			<div id="div_data_changed_msg_bar" style="color:darkred; display:none;">
				<span>数据变更，请手动更新统计数据!</span>
				<span style="cursor:pointer; float:right; margin-right:20px;" onclick="ReloadAllStatData()">[重载统计数据]</span>
			</div>
		</div>
		<div class="splitLine2"></div>
		<div id="div_detail_notes">
			<table id="tbl_detail_items" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:30px;">类别</td>
					<td style="width:60px;">详细分类</td>
					<td style="width:60px;">统计分类</td>
					<td style="width:40px;">金额</td>
					<td style="width:100px;">备注</td>
					<td style="width:80px;">日期</td>
					<td style="width:50px;">操作</td>
				</tr>
			</table>
		</div>
	</div>

<?php	
// 页面底部
do_html_footer("financial");
?>