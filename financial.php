<?php
	include("note_sc_fns.php");
	// ����û��Ϸ���
	check_valid_user();
	// ��ʾҳͷ
	do_html_header("financial");

	echo '<input id="hidden_cur_userid" type="hidden" value='.$_SESSION["current_username"].'>';
?>
		<!--����ҳ���ض��ű�����ʽ�ļ�-->
		<script type="text/javascript" src="script/financial.js"></script>
		<script src="script/jquery-ui.min.js"></script>
		<link type="text/css" href="css/financial.css" rel="Stylesheet" />
		<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>

		<!--�û��Զ������ò�����-->
		<div id="div_config_opration_box">
			<a href="financial_config.php" style="text-decoration:none;">[�Զ�������]</a>&nbsp;&nbsp;
			<a href="financial_stat.php" style="text-decoration:none;">[��Ŀͳ��]</a>&nbsp;&nbsp;
			<a href="#" onclick="DownloadAllRecordNotes()" style="text-decoration:none;">[����ȫ����¼]</a>
		</div>
		<div style="background-color:gray; height:1px; width:1220px; margin-top:10px; margin-bottom:20px;"></div>

        <div id="div_summary_box">
		    <div>
                <label>�û������ܼƣ�</label>
                <span id="span_user_total_income"></span>&nbsp;Ԫ
            </div>
            <div>
                <label>�û�֧���ܼƣ�</label>
                <span id="span_user_total_cost"></span>&nbsp;Ԫ
            </div>
            <div>
                <label>���������룺</label>
                <span id="span_cur_month_total_income"></span>&nbsp;Ԫ
            </div>
            <div>
                <label>������֧����</label>
                <span id="span_cur_month_total_cost"></span>&nbsp;Ԫ
            </div>
            <div>
				<input type="hidden" id="hide_month_cost_plan_config" value="" />
                <label>����֧���ƻ���</label><br />
                <span id="span_cur_month_plan_process"></span>&nbsp;Ԫ
                <progress id="prg_month_budget" max="100" value="100"></progress>
            </div>
        </div>
        <div id="div_user_input_box">
            <div id="div_money_type_box">
                <label>������</label>
                <span val="cost">[֧��]</span>&nbsp;&nbsp;
                <span val="income">[����]</span>
            </div>
            <div id="div_money_amount_box">
                <label>��</label>
                <input id="txt_money" type="number" min="0" required="required" autofocus="true" placeholder="0" value="0" />
            </div>
            <div id="div_category_box">
                <div style="display:inline-block; vertical-align:top; padding-top:4px;">���</div>
				<div id="div_cost_category_content_box"></div>
				<div id="div_income_category_content_box"></div>
            </div>
            <div id="div_stat_type_box">
                <div style="display:inline-block; vertical-align:top; padding-top:4px;">ͳ�ƣ�</div>
				<div id="div_stat_type_content_box">
					<select id="select_stat_type">
						<option value="1" selected>�ճ�</option>
						<option value="2">�׶�</option>
						<option value="3">���</option>
					</select>
				</div>
            </div>
            <div id="div_remark_box">
                <label>��ע��</label>
                <textarea id="txt_financial_remark" rows="2" cols="150"></textarea>
            </div>
            <div id="div_datetime_box">
                <label>���ڣ�</label>
				<input type="text" size="10" id="input_datetime" value="" />
            </div>
            <div id="div_submit_box">
                <input id="btn_submit" type="button" value="��¼" onclick="addNew()" disabled="disabled" />
            </div>
        </div>
        <div id="div_latest_items_box">
            <h3>��¼����</h3>
			<div style="background-color:gray; height:1px; width:600px;"></div>
			<div id="div_latest_items_op_bar">
				<span val="0">[����]</span>&nbsp;&nbsp;
				<span val="1">[����]</span>&nbsp;&nbsp;
				<span val="2">[ǰ��]</span>&nbsp;&nbsp;
				<span val="income">[ȫ������]</span>&nbsp;&nbsp;
				<span val="n">[�Զ���]</span>&nbsp;&nbsp;
				<div id="div_latest_items_op_bar_datetime_box">
					ѡ������: <input type="text" id="input_datetime2" /> 
				</div>
			</div>
			<div id="div_latest_items">
				<table id="tbl_today_items">
					<tr>
						<td style="width:70px;"></td>
						<td style="width:50px;">���</td>
						<td style="width:100px;">��ϸ����</td>
						<td style="width:40px;">���</td>
						<td style="width:100px;">ʱ��</td>
						<td style="width:140px;">��ע</td>
					</tr>
				</table>
			</div>
        </div>
<?php	
// ҳ��ײ�
do_html_footer("financial");
?>
