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
		<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>

		<!--用户自定义配置操作框-->
		<div id="div_config_opration_box">
			<a href="financial_config.php" style="text-decoration:none;">[自定义配置]</a>&nbsp;&nbsp;
			<a href="financial_stat.php" style="text-decoration:none;">[账目统计]</a>&nbsp;&nbsp;
			<a href="#" onclick="DownloadAllRecordNotes()" style="text-decoration:none;">[下载全部记录]</a>
		</div>
		<div style="background-color:gray; height:1px; width:1220px; margin-top:10px; margin-bottom:20px;"></div>

        <div id="div_summary_box">
		    <div>
                <label>用户收入总计：</label>
                <span id="span_user_total_income"></span>&nbsp;元
            </div>
            <div>
                <label>用户支出总计：</label>
                <span id="span_user_total_cost"></span>&nbsp;元
            </div>
            <div>
                <label>本月已收入：</label>
                <span id="span_cur_month_total_income"></span>&nbsp;元
            </div>
            <div>
                <label>本月已支出：</label>
                <span id="span_cur_month_total_cost"></span>&nbsp;元
            </div>
            <div>
				<input type="hidden" id="hide_month_cost_plan_config" value="" />
                <label>本月支出计划：</label><br />
                <span id="span_cur_month_plan_process"></span>&nbsp;元
                <progress id="prg_month_budget" max="100" value="100"></progress>
            </div>
        </div>
        <div id="div_user_input_box">
            <div id="div_money_type_box">
                <label>操作：</label>
                <span val="cost">[支出]</span>&nbsp;&nbsp;
                <span val="income">[收入]</span>
            </div>
            <div id="div_money_amount_box">
                <label>金额：</label>
                <input id="txt_money" type="number" min="0" required="required" autofocus="true" placeholder="0" value="0" />
            </div>
            <div id="div_category_box">
                <div style="display:inline-block; vertical-align:top; padding-top:4px;">类别：</div>
				<div id="div_cost_category_content_box"></div>
				<div id="div_income_category_content_box"></div>
            </div>
            <div id="div_stat_type_box">
                <div style="display:inline-block; vertical-align:top; padding-top:4px;">统计：</div>
				<div id="div_stat_type_content_box">
					<select id="select_stat_type">
						<option value="1" selected>日常</option>
						<option value="2">阶段</option>
						<option value="3">大额</option>
					</select>
				</div>
            </div>
            <div id="div_remark_box">
                <label>备注：</label>
                <textarea id="txt_financial_remark" rows="2" cols="150"></textarea>
            </div>
            <div id="div_datetime_box">
                <label>日期：</label>
				<input type="text" size="10" id="input_datetime" value="" />
            </div>
            <div id="div_submit_box">
                <input id="btn_submit" type="button" value="记录" onclick="addNew()" disabled="disabled" />
            </div>
        </div>
        <div id="div_latest_items_box">
            <h3>记录详情</h3>
			<div style="background-color:gray; height:1px; width:600px;"></div>
			<div id="div_latest_items_op_bar">
				<span val="0">[今天]</span>&nbsp;&nbsp;
				<span val="1">[昨天]</span>&nbsp;&nbsp;
				<span val="2">[前天]</span>&nbsp;&nbsp;
				<span val="income">[全部收入]</span>&nbsp;&nbsp;
				<span val="n">[自定义]</span>&nbsp;&nbsp;
				<div id="div_latest_items_op_bar_datetime_box">
					选择日期: <input type="text" id="input_datetime2" /> 
				</div>
			</div>
			<div id="div_latest_items">
				<table id="tbl_today_items">
					<tr>
						<td style="width:70px;"></td>
						<td style="width:50px;">类别</td>
						<td style="width:100px;">详细分类</td>
						<td style="width:40px;">金额</td>
						<td style="width:100px;">时间</td>
						<td style="width:140px;">备注</td>
					</tr>
				</table>
			</div>
        </div>
<?php	
// 页面底部
do_html_footer("financial");
?>
