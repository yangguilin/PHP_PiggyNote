<?php

// Display today notes.
function display_today_notes($username, $quick_category) {
?>
	<!--ѡ������ٷ����Ļ���-->
	<div style="clear: both;"></div>
	<!--�����˵�-->
	<div id="m_shortCategoryDiv">
		<label style=" font-weight:bold;">���ٷ��ࣺ</label>
		<label>
	<?php if(!$quick_category) {?>
			[&nbsp;ȫ��&nbsp;|&nbsp;
			<a href="index.php?quick_category=unspecified" style = "text-decoration:none;">����</a>&nbsp;|&nbsp;
			<a href="index.php?quick_category=office" style = "text-decoration:none;">�칫��</a>&nbsp;|&nbsp;
			<a href="index.php?quick_category=home" style = "text-decoration:none;">�ؼҴ���</a>&nbsp;]
	<?php }else{
			if ($quick_category == "all")
				echo '[&nbsp;ȫ��';
			else
				echo '[&nbsp;<a href="index.php?quick_category=all" style = "text-decoration:none;">ȫ��</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "unspecified")
				echo '����';
			else
				echo '<a href="index.php?quick_category=unspecified" style = "text-decoration:none;">����</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "office")
				echo '�칫��';
			else
				echo '<a href="index.php?quick_category=office" style = "text-decoration:none;">�칫��</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "home")
				echo '�ؼҴ���&nbsp;]';
			else
				echo '<a href="index.php?quick_category=home" style = "text-decoration:none;">�ؼҴ���</a>&nbsp;]';
		  } ?>
		</label>
	</div>
<?php
	// ��ȡ���մ���������
	$note_array = get_current_notes($username, $quick_category);

	$note_count = 0;
	if (strlen($note_array) > 0)
		$note_count = count($note_array);

	echo '<!--���մ�������--><div id="main_todayNotesContainerDiv"><div class="titleDiv"><label>���մ�������';
	if ($note_count > 0) {
		echo '<label>('.$note_count.')</label>';
		echo '</label></div><div id="m_todayNotesListDiv"><div><div id="m_menuOne"><label>��ͨ</label></div>';
	}else{
		echo '</label></div><div id="m_todayNotesListDiv"><br /><br /><br /><br /><br /><br /><br /><br /><div class="showMessageDiv">���޴������~</div><div>';
	}

	foreach ($note_array as $row) {
		echo '<ul><li><a href="#" style = "text-decoration:none;" onclick="FinisheCurrentNote(this)" noteId='.$row["id"].'>[��]</a>';
		echo '<a href="#" style = "text-decoration:none;" onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[�w]</a>&nbsp;&nbsp;';
		echo $row['title'];
		if ($row['details'])
			echo '<label style=" color:Green;" title='.$row['details'].'>[��ϸ]</label>';
		if ($row["owneruserid"] != $row["offeruserid"])
			echo '<lable style="color:Green;" title="���Ժ���['.$row["offeruserid"].']">[��������]</lable>';
		echo '</li><div></div></ul>';
	}

	echo '</div></div></div><div class="flatlyLine"></div>';
}

// Display today finished notes.
function display_today_finished_notes($username) {
	// ��ȡ�������������
	$today_finished_notes = get_today_finished_notes($username);
?>
	<div style=" min-height:100px;">
		<div class="titleDiv">
			<p>
				���������
			</p>
		</div>
<?php
	if (count($today_finished_notes) == 0) {
?>
			<br /><br />
			<div class="showMessageDiv">���컹û�����κ��¶��أ�~</div>
<?php
	} else {
?>
			<div id="today_finished_notes_list_container">
<?php
		foreach ($today_finished_notes as $row) {
?>
				<div>
					<div>
<?php
						$title = ($row["owneruserid"] == $row["offeruserid"]) ? $row["title"] : $row["title"].'<lable style="color:Green;" title="���Ժ���['.$row["offeruserid"].']">[��������]</lable>';
                        echo $title;
?>
					</div>
					&nbsp;&nbsp;
					<a href="#" noteId="<?php echo $row["id"] ?>" onclick="CancelFinishedNote(this)">[����]</a>
                </div>
<?php
		}
?>
			</div>
<?php
	}
?>
	</div>
</div>

<!--�м�ָ���-->
<div class="verticalLine"></div>
<?php
}

// Display unplan and comming notes.
function display_unplan_and_comming_notes($username) {
	// ��ȡ�Ҳ�δ���䡢�ѷ���������б�
	$unplan_notes = get_unplan_notes($username);
	$comming_notes = get_comming_notes($username);

	$note_count = 0;
	$comming_count = 0;
	if (strlen($unplan_notes) > 0)
		$note_count = count($unplan_notes);
	if (strlen($comming_notes) > 0)
		$comming_count = count($comming_notes);
?>
	<!--���ڶ�������-->
	<div id="left_secondLine"></div>

	<div id="rightContentDiv">
		<div style="margin: 15px;">
				<div class="titleDiv">
					<p>��δ�趨���ڵ��¶���
<?php
	if ($note_count > 0)
		echo '<label>('.$note_count.')</label>';

?>
					</p>
				</div>
				<div>
					<table>
<?php
	foreach ($unplan_notes as $row) {
		$title = $row["title"];
		if ($row["owneruserid"] != $row["offeruserid"])
			$title .= '<lable style="color:Green;" title="���Ժ���['.$row["offeruserid"].']">[����]</lable>';
		echo '<tr><td class="smallListFirstColumn">#</td><td style="width: 320px;">'.$title.'</td>';
		echo '<td style="width: 30px;"><a href="#" style="text-decoration:none;" onclick="DealThisNoteToday(this)" noteId='.$row["id"].'>[��]</a></td>';
		echo '<td style="width: 30px;"><a href="#" style="text-decoration:none;" onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[�w]</a></td></tr>';
	}
?>
					</table>
				</div>
				<div class="titleDiv">
					<p>�������ճ̵��¶�
<?php
	if ($comming_count > 0)
		echo '<label>('.$comming_count.')</label>';
?>
					</p>
				</div>
				<div>
					<table>
<?php foreach ($comming_notes as $row) { ?>
							<tr>
								<td class="smallListFirstColumn">
									#
								</td>
								<td style="width: 250px;">
<?php 
	echo $row["title"];
	if ($row["owneruserid"] != $row["offeruserid"])
		echo '<lable style="color:Green;" title="���Ժ���['.$row["offeruserid"].']">[����]</lable>';
?>
								</td>
								<td style="width:100px;"><?php echo date("m-d", strtotime($row["targetdate"])) ?></td>
								<td style="width: 30px;">
<?php echo '<a href="#" style = " text-decoration:none; " onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[�w]</a>'; ?>
								</td>
							</tr>
<?php } ?>
					</table>
				</div>
		</div>
	</div>
<?php
}

// Display note form.
function display_current_note_form($current_user_name) {
	?>
	<form method="post" action="current_note_process.php">
		<div id="main_quickAddContainerDiv">
		<div class="titleDiv">
			<label>��ʲô�¶���Ҫ���ţ�</label>
		</div>
		<div style="float: left;">
			<div style="height: 35px;">
				<input type="text" name="noteTitle" style="width:530px; height:25px;" oninput="NoteTitleChanged(this)" onpropertychange="NoteTitleChanged(this)" />
				<a href="#" id="m_ShowdetailLink" class="removeBotto">��ϸ���</a>
			</div>
			<div id="m_detailContainerDiv" style="display: none;">
				<textarea rows="10" name="noteDetail" style="width:526px; height:80px;"></textarea>
			</div>
			<div id="m_quickAddOptionsDiv">
				��ǩ���ࣺ
				<select name="noteCategory" style = "width:100px;height:18px;font-size:12px;">
					<option value="unspecified">����</option>
					<option value="office">�칫��</option>
					<option value="home">�ؼҴ���</option>
				</select>
				&nbsp;&nbsp;�����̶ȣ�
				<select name="noteLevel" style = "width:100px;height:18px;font-size:12px;">
					<option value="unspecified">��ͨ</option>
					<option value="emergent">����</option>
					<option value="important">��Ҫ</option>
					<option value="both">������Ҫ</option>
				</select>
				<div style=" height:8px;"></div>
				�ظ����ͣ�
				<select name="noteRepeatType" style = "width:100px;height:18px;font-size:12px;" onchange = "UpdateOtherControlStatusByRepeatType(this)">
					<option value="unspecified">��</option>
					<option value="everyday">ÿ��</option>
					<option value="everyweek">ÿ��</option>
				</select>
				<label id="m_targetWeekdayListLab">&nbsp;&nbsp;ִ��ʱ�䣺</label>
				<select id="m_targetWeekdayList" name="noteTargetWeekday" style = "width:100px;height:18px;font-size:12px;">
<?php
	$sorted_data_list = get_target_weekday_dropdownlist_datasource();
	foreach ($sorted_data_list as $item)
		echo '<option value="'.$item["name"].'">'.$item["value"].'</option>';
?>
				</select>
				<label id="m_owneruseridlistlab">&nbsp;&nbsp;��ǩ������</label>
				<select id="m_owneruseridlist" name="noteOwner" style = "width:100px;height:18px;font-size:12px;">
<?php
	$owner_list = get_all_friends_list($current_user_name);
	echo '<option value="'.$current_user_name.'" selected>�Լ�</option>';
	foreach ($owner_list as $user)
		echo '<option value="'.$user["userid"].'">'.$user["userid"].'</option>';
?>
				</select>
				<input name="offerUserid" type="hidden" value="<?php echo $current_user_name ?>" />
			</div>
		</div>
		<div id="m_quickAddButton">
			<input type="hidden" name="operation_type" value="add" />
			<input id="m_addNewNoteButton" type="submit" value="д��ǩ" style="font-size: 18px;" disabled />
		</div>
	</form>
</div>
	

<?php
}

// Display the form of user login.
function display_user_login_form($login_fail){
?>
	<div style=" text-align:center; vertical-align:middle; margin-top:150px; color:black;">
<?php
	if ($login_fail)
		echo '<h1>�û��������������</h1>';
	else
		echo '<h1>���ȵ�¼</h1>';
?>
		<form method="post" action="login.php">
			<p>�˺�:&nbsp;<input type="text" name="name" style=" width:150px;"></p>
			<p>����:&nbsp;<input type="password" name="password" style=" width:150px;"></p>
			<p><input type="submit" name="submit" value="��  ½" style="width:120px;height:40px;border-radius:8px;"></p>
		</form>
		<p>
			<a style="color:darkred;" href="register.php">>>��Ҫע��<<</a>
		</p>
		<p>
			<a style="color:darkred;" href="../bbs/portal.php">>>���� ����������<<</a>
		</p>
	</div>
<?php
}


// Display the footer of the page.
function do_html_footer($page_name){
?>
			<!--�¶˷ָ���-->
			<div style="clear: both;">
<?php 
	if ($page_name != null){
		if ($page_name == "index")
			echo '<div class="flatlyLine"></div>';
		else if ($page_name == "financial")
			echo '<div style="height:50px;">';
	}

?>
			</div>
			<div id="footer"></div>
		</div>
	</div>
</body>
</html>
<?php
}

// Display the header of the page.
function do_html_header($page_name){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>С���ǩ</title>
		<link href="css/Site.css" rel="stylesheet" type="text/css" />
		<link href="css/Common.css" rel="stylesheet" type="text/css" />
		<link href="css/Home.css" rel="stylesheet" type="text/css" />
		<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="script/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="script/jquery.validate.js"></script>
		<script type="text/javascript" src="script/HomeIndexPage.js"></script>
		<script type="text/javascript" src="script/friend_note_management_page.js"></script>
		<script type="text/javascript" src="script/repeat_note_manager_page.js"></script>
		<script type="text/javascript" src="script/friend_management_page.js"></script>

<script type="text/javascript">
		
	$(document).ready(function () {
		page_onload();
		//// index.php
		// 1. ���hover�¼�
		$("#today_finished_notes_list_container>div").hover(function () {
			$(this).children("a").show("fast");

		}, function () {
			$(this).children("a").hide("normal");
		});
	});

	// ҳ��������ɵ����¼���Ŀǰ��Ҫ���ڳ�ʼ��һЩҳ��Ԫ��״̬����ʽ
	function page_onload() {
		// index.php
		$("#today_finished_notes_list_container>div").css({ "height": "30px", "display": "list-item", "list-style-type": "circle" });
		$("#today_finished_notes_list_container>div>div").css("display", "inline-block");
		$("#today_finished_notes_list_container>div>div").css("text-decoration", "line-through");
		$("#today_finished_notes_list_container>div>div:even").css("background-color", "#FFFACD");
		$("#today_finished_notes_list_container>div>a").css({"display":"inline-block", "text-decoration":"none"});
		$("#today_finished_notes_list_container a").hide();
	}
	
</script>
	</head>
	<body>
		<div class="page">
			<div id="header">
				<div id="title">
					<img src="img/logo.png" alt="С���ǩ" style=" width:160px; height:80px;" />
				</div>
				<div id="logindisplay">
<?php 
	if (isset($_SESSION["current_username"])) 
		echo '��ӭ���� <b>'.$_SESSION["current_username"].'</b>![ <a href="logout.php">�˳�</a> ]';
	else
		echo '���ȵ�¼';
?>
				</div>
				<div id="menucontainer">
					<ul id="menu">
						<li><a href="index.php">�ҵı�ǩ</a></li>
						<li><a href="financial.php">�ҵ�С�˱�</a></li>
						<li><a href="friend_management_page.php">���ѹ���</a></li>
						<li><a href="../bbs/">���տ�����������</a></li>
					</ul>
				</div>
			</div>
<?php

	if ($page_name != null){
		if ($page_name == "index") {
?>
			<div id="main">
			<div style=" margin-bottom:10px;">
				<div style=" font-size:12px;">
					<a href="repeat_note_manager_page.php" style = "text-decoration:none;">[�����ظ�����]</a>
					&nbsp;|&nbsp;
					<a href="friend_note_management_page.php" style = "text-decoration:none;">[��������ѵı�ǩ]</a>
				</div>
			</div>
			<!--ˮƽ�ָ���-->
			<div class="flatlyLine"></div>
			<!--�������-->
			<div id="mainContentDiv">
<?php
		} else if ($page_name == "repeat_note_manager_page" || $page_name == "friend_note_management_page"){
?>
			<div id="main">
			<div>
				<a href="index.php" style=" text-decoration:none; ">���� �ҵı�ǩ</a>
			</div>
			<div style=" height:20px;"></div>
<?php
		} else if ($page_name == "friend_management_page"){
?>
			<div id="main">
			<div style=" height:20px;"></div>
<?php
		} else 	{
?>
			<div id="main">
			<div style=" height:20px;"></div>
<?php
		}
	}
}

// Display registration form.
function display_registration_form($register_error) {
?>
	<div style=" vertical-align:middle; margin-top:150px; color:black;">
		<form id="registerform" method="post" action="register_new.php" style=" margin-left:450px;">
			<p>
				<label for="email">��������</label>
				<em>*</em><input id="email" name="email" type="text" />
			</p>                    
			<p>
				<label for="username">�˺�</label>
				<em>*</em><input id="username" name="username" type="text" />
			</p>
			<p>
				<label for="passwd">����</label>
				<em>*</em><input id="passwd" name="passwd" type="password" />
			</p>
			<p>
				<label for="passwd2">ȷ������</label>
				<em>*</em><input id="passwd2" name="passwd2" type="password" />
			</p>                    
			<p>
				<input class="submit" type="submit" value="ע��" style=" height:30px; width:100px;" />&nbsp;&nbsp;
				<input type="button" value="��  ��" style=" height:30px; width:100px;" onclick="window.location='index.php'" />
			</p>
		</form>
	</div>
<?php

}

// Display register success message.
function display_register_success_message(){
?>
	<html>
		<body style=" background-color:#5c87b2;">
			<div style=" text-align:center; vertical-align:middle; margin-top:150px; color:darkred;">
				ע��ɹ���<a href="index.php">���ǰ�����ı�ǩ</a>
			</div>
		</body>
	</html>
<?php
}

// �����ظ�������
function display_repeat_notes_table($userid){
?>
	<div>
		<table>
			<tr>
				<th style=" width:350px;">
					��������
				</th>
				<th style=" width:80px;">
					����
				</th>
				<th style=" width:80px;">
					����
				</th>
				<th style=" width:80px;">
					�ظ�����
				</th>
				<th style=" width:70px;">
					ʱ�䣨�ܣ�
				</th>
				<th style=" width:70px;">
					����ʱ��
				</th>
				<th style=" width:60px;">
					��ǰ״̬
				</th>
				<th style=" width:80px;">
					�������
				</th>
			</tr>
<?php
	// ��ȡ��ǰ�û����ظ�������
	$repeat_notes = get_current_user_repeat_notes($userid);
	foreach ($repeat_notes as $row){
?>
			<tr>
<?php
	echo '<td style="word-break:break-all;" title=><label>'.$row["title"].'</label></td>';
?>
				<td>
					<select id="NoteCategory" name="NoteCategory" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="category" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["category"]=="unspecified") echo 'selected'; ?> value="unspecified">����</option>
						<option <?php if($row["category"]=="office") echo 'selected'; ?> value="office">�칫��</option>
						<option <?php if($row["category"]=="home") echo 'selected'; ?> value="home">�ؼҴ���</option>
					</select>
				</td>
				<td>
					<select id="NoteLevel" name="NoteLevel" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="level" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["level"]=="unspecified") echo 'selected'; ?> value="unspecified">��ͨ</option>
						<option <?php if($row["level"]=="emergent") echo 'selected'; ?> value="emergent">����</option>
						<option <?php if($row["level"]=="important") echo 'selected'; ?> value="important">��Ҫ</option>
						<option <?php if($row["level"]=="both") echo 'selected'; ?> value="both">������Ҫ</option>
					</select>
				</td>
				<td>
					<select id="NoteRepeatType" name="NoteRepeatType" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="repeatType" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["repeattype"]=="everyday") echo 'selected'; ?> value="everyday">ÿ��</option>
						<option <?php if($row["repeattype"]=="everyweek") echo 'selected'; ?> value="everyweek">ÿ��</option>
					</select>
				</td>
				<td>
				<?php if($row["repeattype"]=="everyweek") {?>
					<select id="NoteTargetWeekday" name="NoteTargetWeekday" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="targetWeekday" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["targetweekday"]=="monday") echo 'selected'; ?> value="monday">����һ</option>
						<option <?php if($row["targetweekday"]=="tuesday") echo 'selected'; ?> value="tuesday">���ڶ�</option>
						<option <?php if($row["targetweekday"]=="wednesday") echo 'selected'; ?> value="wednesday">������</option>
						<option <?php if($row["targetweekday"]=="thursday") echo 'selected'; ?> value="thursday">������</option>
						<option <?php if($row["targetweekday"]=="friday") echo 'selected'; ?> value="friday">������</option>
						<option <?php if($row["targetweekday"]=="saturday") echo 'selected'; ?> value="saturday">������</option>
						<option <?php if($row["targetweekday"]=="sunday") echo 'selected'; ?> value="sunday">������</option>
					</select>
				<?php }else{
					echo '<label>--</label>';
				}?>
				</td>
<?php	
	
echo '<td>'.date("m-d", strtotime($row["createtime"])).'</td>';	

$isenable = ord($row["isenable"]);

if ($isenable == 1)
	echo '<td>����</td>';
else
	echo '<td>��ͣ</td>';

if ($isenable == 1)
	echo '<td><a href="#" style=" text-decoration:none; " noteId="'.$row["id"].'" procType="close" onclick="ChangeRepeatNoteEnableStatus(this)">��ͣ</a></td>';
else
	echo '<td><a href="#" style=" text-decoration:none; " noteId="'.$row["id"].'" procType="start" onclick="ChangeRepeatNoteEnableStatus(this)">����</a>&nbsp;<label>|</label>&nbsp;<a href="#" noteId="'.$row["id"].'" noteTitle="'.$row["title"].'" onclick="DeleteRepeatNote(this)" style=" text-decoration:none;">ɾ��</a></td>';
?>
			</tr>
<?php
	}
?>
		</table>
	</div>
<?php
}

// ��ʾ�ҵĺ����б�
function display_my_friend_list($userid){
	// ��ȡ��ǰ�û��ĺ����б�
	$my_friends = get_current_user_friends($userid);
?>
	<!--�����б�-->
	<div>
		<p class="titleDiv">�Ҷ�����Щ����</p>
		<table>
			<tr>
				<th style="width:30px;"></th>
				<th style="width:150px;">
					�û���
				</th>
				<th style="width:200px;">
					״̬
				</th>
				<th style="width:120px; text-align:center;">����</th>
			</tr>
<?php
	$number = 1;
	$activeFriend = true;
	foreach ($my_friends as $row){
		$activeFriend = ($userid == $row["friendid"]) ? false : true;
?>
			<tr>
				<td style=" text-align:center;"><?php echo $number ?></td>
				<td>
					<?php echo $activeFriend ? $row["friendid"] : $row["userid"]; ?>
				</td>
					<td>
					<label>
<?php
	if ($row["status"] == 0)
		echo $activeFriend ? '�ȴ��Է�ȷ��' : '�봦���������';
	else if ($row["status"] == 1)
		echo $activeFriend ? '�Է��ܾ�����' : '�Ѿܾ��Է�����';
	else if ($row["status"] == 2)
		echo '�Ѿ���Ϊ����';
?>
					</label>
				</td>
				<td style=" text-align:center;">
<?php
	if ($row["status"] == 0)
	{
		if ($activeFriend)
			echo '----';
		else
		{
			echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'agree\')" style="text-decoration:none;">ͬ��</a>&nbsp;|&nbsp;<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'reject\')" style="text-decoration:none;">�ܾ�</a>';
		}
			
	}
	else if ($row["status"] == 1)
	{
		if ($activeFriend)
			echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'delete\')" style="text-decoration:none;">֪����</a>';
	}
	else if ($row["status"] == 2)
		echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'delete\')" style="text-decoration:none;">�������</a>';
?>
				</td>
			</tr>
<?php } ?>
		</table>
	</div>

<?php
}

// ��ʾ������Ա�б�������Ӻ��ѣ�
function display_other_people_list($userid){
	// ��ȡ��ǰ�û��ĺ����б�
	$all_user_list = get_current_all_user_list($userid);
?>
	<!--�û��б�-->
	<div>
		<p class="titleDiv">�������ʶ��Щ�ˣ�</p>
		<table>
			<tr>
				<th style="width:30px;"></th>
				<th style="width:150px;">
					�û���
				</th>
				<th style="width:200px;">
					ע������
				</th>
				<th style="width:120px; text-align:center;">����</th>
			</tr>
<?php
	$number = 1;
	foreach ($all_user_list as $row){
?>
			<tr>
				<td style=" text-align:center;"><?php echo $number++ ?></td>
				<td><?php echo $row["username"] ?></td>
				<td><?php echo $row["email"] ?></td>
				<td style=" text-align:center;">
					<a href="#" user_name="<?php echo $userid ?>" friend_name="<?php echo $row["username"] ?>" onclick="manage_friend_status(this, 'add')" style=" text-decoration:none; ">������Ӻ���</a>
				</td>
			</tr>
<?php } ?>
		</table>
	</div>
	
<?php
}


// ���ƺ������ѱ�ǩ��Ϣ�б�
function display_friend_notes_table($userid){
?>
	<div>
		<table>
			<tr>
				<th style=" width:350px;">
					��������
				</th>
				<th style=" width:80px;">
					����
				</th>
				<th style=" width:80px;">
					����
				</th>
				<th style=" width:70px;">
					���ͺ���
				</th>
				<th style=" width:70px;">
					����ʱ��
				</th>
				<th style=" width:60px;">
					��ǰ״̬
				</th>
				<th style=" width:80px;">
					�������
				</th>
			</tr>
<?php
	// ��ȡ��ǰ�û��ĺ��������б�
	$friend_notes = get_current_user_friend_notes($userid);
	foreach ($friend_notes as $row){
?>
			<tr>
<?php
	echo '<td style="word-break:break-all;" title=><label>'.$row["title"].'</label></td>';

	if ($row["category"]=="unspecified")
		echo '<td>����</td>';
	else if ($row["category"]=="office")
		echo '<td>�칫��</td>';
	else if ($row["category"]=="home")
		echo '<td>�ؼҴ���</td>';

	if ($row["level"]=="unspecified")
		echo '<td>��ͨ</td>';
	else if ($row["level"]=="emergent")
		echo '<td>����</td>';
	else if ($row["level"]=="important")
		echo '<td>��Ҫ</td>';
	else if ($row["level"]=="both")
		echo '<td>������Ҫ</td>';

	echo '<td>'.$row["owneruserid"].'</td>';

	echo '<td>'.date("m-d", strtotime($row["createtime"])).'</td>';

	$status = intval($row["status"]);
	if ($status == 0)
		echo '<td>ȷ����</td>';
	else if ($status == 1)
		echo '<td>�ѽ���</td>';
	else if ($status == 2)
		echo '<td>�Ѿܾ�</td>';
	else if ($status == 3)
		echo '<td>�����</td>';
	else
		echo '<td>δ֪״̬</td>';

if ($status == 2 || $status == 3)
	echo '<td><a href="#" style=" text-decoration:none; " item_id="'.$row["id"].'" onclick="update_friend_note_status(this, \'delete\')">֪����</a></td>';
else
	echo '<td> -- </td>';
?>
			</tr>
<?php
	}
?>
		</table>
	</div>
<?php
}

?>