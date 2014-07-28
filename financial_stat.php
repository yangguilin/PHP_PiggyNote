<?php
	include("note_sc_fns.php");
	// ����û��Ϸ���
	check_valid_user();
	// ��ʾҳͷ
	do_html_header("financial");

	echo '<input id="hidden_cur_userid" type="hidden" value='.$_SESSION["current_username"].' />';
	echo '<input id="hidden_user_category_data" type="hidden" value='.$_SESSION["user_category_data"].' />';
?>

	<!--����ҳ���ض��ű�����ʽ�ļ�-->
	<script type="text/javascript" src="script/financial_stat.js"></script>
	<script src="script/jquery-ui.min.js"></script>
	<link type="text/css" href="css/financial_stat.css" rel="Stylesheet" />

	<!--�û��Զ������ò�����-->
	<div id="div_statistic_opration_box">
		<a href="financial.php" style="text-decoration:none;">���� �ҵ�С�˱�</a>
	</div>
	<div class="splitLine"></div>

	<div id="div_quick_opration_box">
		<h3>
			<span val="cur_month">����ͳ��</span> | 			
			<span val="category">����ͳ��</span> | 
			<span val="any_month">����ͳ��</span> | 
			<!--<span val="any_year">����ͳ��</span> | -->
			<span val="big_data">��������ͳ��</span>
		</h3>
		<div class="splitLine2"></div>
		<div id="div_cur_month_stat_box">
			<table id="tbl_cost_items" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:80px;">֧�����</td>
					<td style="width:80px;">��¼����</td>
					<td style="width:80px;">С��(Ԫ)</td>
					<td style="width:70px;">��ϸ</td>
				</tr>
			</table>
			<br/>
			<br/>
			<table id="tbl_income_items">
				<tr class="titleTr">
					<td style="width:80px;">�������</td>
					<td style="width:80px;">��¼����</td>
					<td style="width:80px;">С��(Ԫ)</td>
					<td style="width:70px;">��ϸ</td>
				</tr>
			</table>
		</div>
		<div id="div_category_stat_box">
			<div style="margin-top:10px; margin-bottom:-5px;">
				<label>��ݲ���:</label>
				<a id="cat_filt_btn" href="#" style="text-decoration:none;" onclick="FiltBigDataItemForCategoryStat()" >[���˴��]</a>
				<a id="cat_normal_btn" href="#" style="text-decoration:none; display:none;" onclick="GetStatDataByCategory()" >[����ͳ��]</a>
			</div>
			<table id="tbl_cost_category" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:80px;">֧�����</td>
					<td style="width:60px;">��¼����</td>
					<td style="width:80px;">�ܼ�(Ԫ)</td>
					<td style="width:80px;">��ƽ��(Ԫ)</td>
					<td style="width:60px">�ٷֱ�</td>
					<td style="width:70px">��ϸ</td>
				</tr>
			</table>
			<br/>
			<br/>
			<table id="tbl_income_category">
				<tr class="titleTr">
					<td style="width:80px;">�������</td>
					<td style="width:60px;">��¼����</td>
					<td style="width:80px;">�ܼ�(Ԫ)</td>
					<td style="width:80px;">��ƽ��(Ԫ)</td>
					<td style="width:60px">�ٷֱ�</td>
					<td style="width:70px">��ϸ</td>
				</tr>
			</table>
		</div>
		<div id="div_any_month_stat_box">
			<div id="div_mon_filt_type" style="margin-top:10px; margin-bottom:-5px;">
				<label>ģʽѡ��:</label>
				<span filt_type="normal" class="filt_unselected">[����ͳ��]</span>
				&nbsp;|&nbsp;
				<span filt_type="bigdata" class="filt_unselected">[���˴��]</span>
				&nbsp;|&nbsp;
				<span filt_type="bigdata_and_stage" class="filt_unselected">[�ճ�ģʽ]</span>
			</div>
			<table id="tbl_any_month" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:100px;">�·�</td>
					<td style="width:100px;">����(Ԫ)</td>
					<td style="width:100px;">֧��(Ԫ)</td>
					<td style="width:70px">��ϸ</td>
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
					<td style="width:80px;">֧�����</td>
					<td style="width:80px;">��¼����</td>
					<td style="width:80px;">С��(Ԫ)</td>
					<td style="width:70px">��ϸ</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="div_quick_detail_result_box">
		<div>
			<h3>
				<span id="span_detail_result_title">��ϸ����</span>
			</h3>
			<div id="div_data_changed_msg_bar" style="color:darkred; display:none;">
				<span>���ݱ�������ֶ�����ͳ������!</span>
				<span style="cursor:pointer; float:right; margin-right:20px;" onclick="ReloadAllStatData()">[����ͳ������]</span>
			</div>
		</div>
		<div class="splitLine2"></div>
		<div id="div_detail_notes">
			<table id="tbl_detail_items" class="firstTblInBox">
				<tr class="titleTr">
					<td style="width:30px;">���</td>
					<td style="width:60px;">��ϸ����</td>
					<td style="width:60px;">ͳ�Ʒ���</td>
					<td style="width:40px;">���</td>
					<td style="width:100px;">��ע</td>
					<td style="width:80px;">����</td>
					<td style="width:50px;">����</td>
				</tr>
			</table>
		</div>
	</div>

<?php	
// ҳ��ײ�
do_html_footer("financial");
?>