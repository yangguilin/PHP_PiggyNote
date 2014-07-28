<?php
	include("note_sc_fns.php");
	// 检查用户合法性
	check_valid_user();
	// 显示页头
	do_html_header("financial");

	echo '<input id="hidden_cur_userid" type="hidden" value='.$_SESSION["current_username"].'>';
?>

	<!--载入页面特定脚本和样式文件-->
	<script type="text/javascript" src="script/financial.js"></script>
	<script src="script/jquery-ui.min.js"></script>
	<link type="text/css" href="css/financial.css" rel="Stylesheet" />

	<!--用户自定义配置操作框-->
	<div id="div_config_opration_box">
		<a href="financial.php" style="text-decoration:none;">返回 我的小账本</a>
	</div>
	<div style="background-color:gray; height:1px; width:1220px; margin-top:15px; margin-bottom:30px;"></div>

	<div id="div_quick_opration_box">
		<h3>
			<span val="category">我的分类</span> | 
			<span val="plan">我的计划</span>
		</h3>
		<div style="background-color:gray; height:1px; width:485px;"></div>
		<div id="div_category_modify_box">
			<table id="tbl_category_items">
				<tr>
					<td style="width:70px;">类别</td>
					<td style="width:70px;">详细分类</td>
					<td style="width:210px;">分类说明</td>
					<td style="width:40px;">次数</td>
					<td style="width:80px;">操作</td>
				</tr>
				<tr id="opTr">
					<td colspan="5" style="text-align:center;">
						<span style="cursor:pointer; color:green;" onclick="AddNewCategory(this)">[添加新分类]</span>
					</td>
				</tr>
			</table>
		</div>
		<div id="div_plan_modify_box">
			<div>
				<label>月消费计划：</label>
				<input type="text" id="input_month_cost_plan" type="number" min="0" required="required" oldVal="" />
				元&nbsp;&nbsp;
				<input type="button" id="btnUpMCP" value="更新" onclick="updateMonthCostPlan()" disabled="true" />
			</div>
		</div>
	</div>

<?php	
// 页面底部
do_html_footer("financial");
?>