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

	<!--�û��Զ������ò�����-->
	<div id="div_config_opration_box">
		<a href="financial.php" style="text-decoration:none;">���� �ҵ�С�˱�</a>
	</div>
	<div style="background-color:gray; height:1px; width:1220px; margin-top:15px; margin-bottom:30px;"></div>

	<div id="div_quick_opration_box">
		<h3>
			<span val="category">�ҵķ���</span> | 
			<span val="plan">�ҵļƻ�</span>
		</h3>
		<div style="background-color:gray; height:1px; width:485px;"></div>
		<div id="div_category_modify_box">
			<table id="tbl_category_items">
				<tr>
					<td style="width:70px;">���</td>
					<td style="width:70px;">��ϸ����</td>
					<td style="width:210px;">����˵��</td>
					<td style="width:40px;">����</td>
					<td style="width:80px;">����</td>
				</tr>
				<tr id="opTr">
					<td colspan="5" style="text-align:center;">
						<span style="cursor:pointer; color:green;" onclick="AddNewCategory(this)">[����·���]</span>
					</td>
				</tr>
			</table>
		</div>
		<div id="div_plan_modify_box">
			<div>
				<label>�����Ѽƻ���</label>
				<input type="text" id="input_month_cost_plan" type="number" min="0" required="required" oldVal="" />
				Ԫ&nbsp;&nbsp;
				<input type="button" id="btnUpMCP" value="����" onclick="updateMonthCostPlan()" disabled="true" />
			</div>
		</div>
	</div>

<?php	
// ҳ��ײ�
do_html_footer("financial");
?>