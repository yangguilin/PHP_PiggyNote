<?php

// Display today notes.
function display_today_notes($username, $quick_category) {
?>
	<!--选项与快速分类间的换行-->
	<div style="clear: both;"></div>
	<!--顶部菜单-->
	<div id="m_shortCategoryDiv">
		<label style=" font-weight:bold;">快速分类：</label>
		<label>
	<?php if(!$quick_category) {?>
			[&nbsp;全部&nbsp;|&nbsp;
			<a href="index.php?quick_category=unspecified" style = "text-decoration:none;">任意</a>&nbsp;|&nbsp;
			<a href="index.php?quick_category=office" style = "text-decoration:none;">办公室</a>&nbsp;|&nbsp;
			<a href="index.php?quick_category=home" style = "text-decoration:none;">回家处理</a>&nbsp;]
	<?php }else{
			if ($quick_category == "all")
				echo '[&nbsp;全部';
			else
				echo '[&nbsp;<a href="index.php?quick_category=all" style = "text-decoration:none;">全部</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "unspecified")
				echo '任意';
			else
				echo '<a href="index.php?quick_category=unspecified" style = "text-decoration:none;">任意</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "office")
				echo '办公室';
			else
				echo '<a href="index.php?quick_category=office" style = "text-decoration:none;">办公室</a>';
			echo '&nbsp;|&nbsp';
			if ($quick_category == "home")
				echo '回家处理&nbsp;]';
			else
				echo '<a href="index.php?quick_category=home" style = "text-decoration:none;">回家处理</a>&nbsp;]';
		  } ?>
		</label>
	</div>
<?php
	// 获取今日待处理数据
	$note_array = get_current_notes($username, $quick_category);

	$note_count = 0;
	if (strlen($note_array) > 0)
		$note_count = count($note_array);

	echo '<!--今日待办事项--><div id="main_todayNotesContainerDiv"><div class="titleDiv"><label>今日待办事项';
	if ($note_count > 0) {
		echo '<label>('.$note_count.')</label>';
		echo '</label></div><div id="m_todayNotesListDiv"><div><div id="m_menuOne"><label>普通</label></div>';
	}else{
		echo '</label></div><div id="m_todayNotesListDiv"><br /><br /><br /><br /><br /><br /><br /><br /><div class="showMessageDiv">暂无待办事项！~</div><div>';
	}

	foreach ($note_array as $row) {
		echo '<ul><li><a href="#" style = "text-decoration:none;" onclick="FinisheCurrentNote(this)" noteId='.$row["id"].'>[√]</a>';
		echo '<a href="#" style = "text-decoration:none;" onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[╳]</a>&nbsp;&nbsp;';
		echo $row['title'];
		if ($row['details'])
			echo '<label style=" color:Green;" title='.$row['details'].'>[详细]</label>';
		if ($row["owneruserid"] != $row["offeruserid"])
			echo '<lable style="color:Green;" title="来自好友['.$row["offeruserid"].']">[好友提醒]</lable>';
		echo '</li><div></div></ul>';
	}

	echo '</div></div></div><div class="flatlyLine"></div>';
}

// Display today finished notes.
function display_today_finished_notes($username) {
	// 获取今日已完成数据
	$today_finished_notes = get_today_finished_notes($username);
?>
	<div style=" min-height:100px;">
		<div class="titleDiv">
			<p>
				今日已完成
			</p>
		</div>
<?php
	if (count($today_finished_notes) == 0) {
?>
			<br /><br />
			<div class="showMessageDiv">今天还没办完任何事儿呢！~</div>
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
						$title = ($row["owneruserid"] == $row["offeruserid"]) ? $row["title"] : $row["title"].'<lable style="color:Green;" title="来自好友['.$row["offeruserid"].']">[好友提醒]</lable>';
                        echo $title;
?>
					</div>
					&nbsp;&nbsp;
					<a href="#" noteId="<?php echo $row["id"] ?>" onclick="CancelFinishedNote(this)">[撤销]</a>
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

<!--中间分割线-->
<div class="verticalLine"></div>
<?php
}

// Display unplan and comming notes.
function display_unplan_and_comming_notes($username) {
	// 获取右侧未分配、已分配的任务列表
	$unplan_notes = get_unplan_notes($username);
	$comming_notes = get_comming_notes($username);

	$note_count = 0;
	$comming_count = 0;
	if (strlen($unplan_notes) > 0)
		$note_count = count($unplan_notes);
	if (strlen($comming_notes) > 0)
		$comming_count = count($comming_notes);
?>
	<!--左侧第二条横线-->
	<div id="left_secondLine"></div>

	<div id="rightContentDiv">
		<div style="margin: 15px;">
				<div class="titleDiv">
					<p>尚未设定日期的事儿？
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
			$title .= '<lable style="color:Green;" title="来自好友['.$row["offeruserid"].']">[好友]</lable>';
		echo '<tr><td class="smallListFirstColumn">#</td><td style="width: 320px;">'.$title.'</td>';
		echo '<td style="width: 30px;"><a href="#" style="text-decoration:none;" onclick="DealThisNoteToday(this)" noteId='.$row["id"].'>[√]</a></td>';
		echo '<td style="width: 30px;"><a href="#" style="text-decoration:none;" onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[╳]</a></td></tr>';
	}
?>
					</table>
				</div>
				<div class="titleDiv">
					<p>已列入日程的事儿
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
		echo '<lable style="color:Green;" title="来自好友['.$row["offeruserid"].']">[好友]</lable>';
?>
								</td>
								<td style="width:100px;"><?php echo date("m-d", strtotime($row["targetdate"])) ?></td>
								<td style="width: 30px;">
<?php echo '<a href="#" style = " text-decoration:none; " onclick="DeleteOrCancelTodayNote(this)" noteTitle='.$row["title"].' noteId='.$row["id"].'>[╳]</a>'; ?>
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
			<label>有什么事儿需要安排？</label>
		</div>
		<div style="float: left;">
			<div style="height: 35px;">
				<input type="text" name="noteTitle" style="width:530px; height:25px;" oninput="NoteTitleChanged(this)" onpropertychange="NoteTitleChanged(this)" />
				<a href="#" id="m_ShowdetailLink" class="removeBotto">详细点儿</a>
			</div>
			<div id="m_detailContainerDiv" style="display: none;">
				<textarea rows="10" name="noteDetail" style="width:526px; height:80px;"></textarea>
			</div>
			<div id="m_quickAddOptionsDiv">
				便签分类：
				<select name="noteCategory" style = "width:100px;height:18px;font-size:12px;">
					<option value="unspecified">任意</option>
					<option value="office">办公室</option>
					<option value="home">回家处理</option>
				</select>
				&nbsp;&nbsp;缓急程度：
				<select name="noteLevel" style = "width:100px;height:18px;font-size:12px;">
					<option value="unspecified">普通</option>
					<option value="emergent">紧急</option>
					<option value="important">重要</option>
					<option value="both">紧急重要</option>
				</select>
				<div style=" height:8px;"></div>
				重复类型：
				<select name="noteRepeatType" style = "width:100px;height:18px;font-size:12px;" onchange = "UpdateOtherControlStatusByRepeatType(this)">
					<option value="unspecified">否</option>
					<option value="everyday">每日</option>
					<option value="everyweek">每周</option>
				</select>
				<label id="m_targetWeekdayListLab">&nbsp;&nbsp;执行时间：</label>
				<select id="m_targetWeekdayList" name="noteTargetWeekday" style = "width:100px;height:18px;font-size:12px;">
<?php
	$sorted_data_list = get_target_weekday_dropdownlist_datasource();
	foreach ($sorted_data_list as $item)
		echo '<option value="'.$item["name"].'">'.$item["value"].'</option>';
?>
				</select>
				<label id="m_owneruseridlistlab">&nbsp;&nbsp;便签归属：</label>
				<select id="m_owneruseridlist" name="noteOwner" style = "width:100px;height:18px;font-size:12px;">
<?php
	$owner_list = get_all_friends_list($current_user_name);
	echo '<option value="'.$current_user_name.'" selected>自己</option>';
	foreach ($owner_list as $user)
		echo '<option value="'.$user["userid"].'">'.$user["userid"].'</option>';
?>
				</select>
				<input name="offerUserid" type="hidden" value="<?php echo $current_user_name ?>" />
			</div>
		</div>
		<div id="m_quickAddButton">
			<input type="hidden" name="operation_type" value="add" />
			<input id="m_addNewNoteButton" type="submit" value="写便签" style="font-size: 18px;" disabled />
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
		echo '<h1>用户名或者密码错误！</h1>';
	else
		echo '<h1>请先登录</h1>';
?>
		<form method="post" action="login.php">
			<p>账号:&nbsp;<input type="text" name="name" style=" width:150px;"></p>
			<p>密码:&nbsp;<input type="password" name="password" style=" width:150px;"></p>
			<p><input type="submit" name="submit" value="登  陆" style="width:120px;height:40px;border-radius:8px;"></p>
		</form>
		<p>
			<a style="color:darkred;" href="register.php">>>我要注册<<</a>
		</p>
		<p>
			<a style="color:darkred;" href="../bbs/portal.php">>>返回 你我他社区<<</a>
		</p>
	</div>
<?php
}


// Display the footer of the page.
function do_html_footer($page_name){
?>
			<!--下端分割线-->
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
		<title>小猪便签</title>
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
		// 1. 添加hover事件
		$("#today_finished_notes_list_container>div").hover(function () {
			$(this).children("a").show("fast");

		}, function () {
			$(this).children("a").hide("normal");
		});
	});

	// 页面载入完成调用事件，目前主要用于初始化一些页面元素状态和样式
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
					<img src="img/logo.png" alt="小猪便签" style=" width:160px; height:80px;" />
				</div>
				<div id="logindisplay">
<?php 
	if (isset($_SESSION["current_username"])) 
		echo '欢迎回来 <b>'.$_SESSION["current_username"].'</b>![ <a href="logout.php">退出</a> ]';
	else
		echo '请先登录';
?>
				</div>
				<div id="menucontainer">
					<ul id="menu">
						<li><a href="index.php">我的便签</a></li>
						<li><a href="financial.php">我的小账本</a></li>
						<li><a href="friend_management_page.php">好友管理</a></li>
						<li><a href="../bbs/">向日葵你我他社区</a></li>
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
					<a href="repeat_note_manager_page.php" style = "text-decoration:none;">[管理重复事项]</a>
					&nbsp;|&nbsp;
					<a href="friend_note_management_page.php" style = "text-decoration:none;">[管理给好友的便签]</a>
				</div>
			</div>
			<!--水平分割线-->
			<div class="flatlyLine"></div>
			<!--左侧主体-->
			<div id="mainContentDiv">
<?php
		} else if ($page_name == "repeat_note_manager_page" || $page_name == "friend_note_management_page"){
?>
			<div id="main">
			<div>
				<a href="index.php" style=" text-decoration:none; ">返回 我的便签</a>
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
				<label for="email">电子邮箱</label>
				<em>*</em><input id="email" name="email" type="text" />
			</p>                    
			<p>
				<label for="username">账号</label>
				<em>*</em><input id="username" name="username" type="text" />
			</p>
			<p>
				<label for="passwd">密码</label>
				<em>*</em><input id="passwd" name="passwd" type="password" />
			</p>
			<p>
				<label for="passwd2">确认密码</label>
				<em>*</em><input id="passwd2" name="passwd2" type="password" />
			</p>                    
			<p>
				<input class="submit" type="submit" value="注册" style=" height:30px; width:100px;" />&nbsp;&nbsp;
				<input type="button" value="返  回" style=" height:30px; width:100px;" onclick="window.location='index.php'" />
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
				注册成功！<a href="index.php">点击前往俺的便签</a>
			</div>
		</body>
	</html>
<?php
}

// 绘制重复事项表格
function display_repeat_notes_table($userid){
?>
	<div>
		<table>
			<tr>
				<th style=" width:350px;">
					事项名称
				</th>
				<th style=" width:80px;">
					分类
				</th>
				<th style=" width:80px;">
					缓急
				</th>
				<th style=" width:80px;">
					重复类型
				</th>
				<th style=" width:70px;">
					时间（周）
				</th>
				<th style=" width:70px;">
					创建时间
				</th>
				<th style=" width:60px;">
					当前状态
				</th>
				<th style=" width:80px;">
					更多操作
				</th>
			</tr>
<?php
	// 获取当前用户的重复任务项
	$repeat_notes = get_current_user_repeat_notes($userid);
	foreach ($repeat_notes as $row){
?>
			<tr>
<?php
	echo '<td style="word-break:break-all;" title=><label>'.$row["title"].'</label></td>';
?>
				<td>
					<select id="NoteCategory" name="NoteCategory" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="category" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["category"]=="unspecified") echo 'selected'; ?> value="unspecified">任意</option>
						<option <?php if($row["category"]=="office") echo 'selected'; ?> value="office">办公室</option>
						<option <?php if($row["category"]=="home") echo 'selected'; ?> value="home">回家处理</option>
					</select>
				</td>
				<td>
					<select id="NoteLevel" name="NoteLevel" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="level" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["level"]=="unspecified") echo 'selected'; ?> value="unspecified">普通</option>
						<option <?php if($row["level"]=="emergent") echo 'selected'; ?> value="emergent">紧急</option>
						<option <?php if($row["level"]=="important") echo 'selected'; ?> value="important">重要</option>
						<option <?php if($row["level"]=="both") echo 'selected'; ?> value="both">紧急重要</option>
					</select>
				</td>
				<td>
					<select id="NoteRepeatType" name="NoteRepeatType" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="repeatType" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["repeattype"]=="everyday") echo 'selected'; ?> value="everyday">每日</option>
						<option <?php if($row["repeattype"]=="everyweek") echo 'selected'; ?> value="everyweek">每周</option>
					</select>
				</td>
				<td>
				<?php if($row["repeattype"]=="everyweek") {?>
					<select id="NoteTargetWeekday" name="NoteTargetWeekday" noteId="<?php echo $row["id"]; ?>" onchange="ChangeNoteProperty(this)" propertyType="targetWeekday" style="width:80px;height:18px;font-size:12px;">
						<option <?php if($row["targetweekday"]=="monday") echo 'selected'; ?> value="monday">星期一</option>
						<option <?php if($row["targetweekday"]=="tuesday") echo 'selected'; ?> value="tuesday">星期二</option>
						<option <?php if($row["targetweekday"]=="wednesday") echo 'selected'; ?> value="wednesday">星期三</option>
						<option <?php if($row["targetweekday"]=="thursday") echo 'selected'; ?> value="thursday">星期四</option>
						<option <?php if($row["targetweekday"]=="friday") echo 'selected'; ?> value="friday">星期五</option>
						<option <?php if($row["targetweekday"]=="saturday") echo 'selected'; ?> value="saturday">星期六</option>
						<option <?php if($row["targetweekday"]=="sunday") echo 'selected'; ?> value="sunday">星期日</option>
					</select>
				<?php }else{
					echo '<label>--</label>';
				}?>
				</td>
<?php	
	
echo '<td>'.date("m-d", strtotime($row["createtime"])).'</td>';	

$isenable = ord($row["isenable"]);

if ($isenable == 1)
	echo '<td>正常</td>';
else
	echo '<td>暂停</td>';

if ($isenable == 1)
	echo '<td><a href="#" style=" text-decoration:none; " noteId="'.$row["id"].'" procType="close" onclick="ChangeRepeatNoteEnableStatus(this)">暂停</a></td>';
else
	echo '<td><a href="#" style=" text-decoration:none; " noteId="'.$row["id"].'" procType="start" onclick="ChangeRepeatNoteEnableStatus(this)">启用</a>&nbsp;<label>|</label>&nbsp;<a href="#" noteId="'.$row["id"].'" noteTitle="'.$row["title"].'" onclick="DeleteRepeatNote(this)" style=" text-decoration:none;">删除</a></td>';
?>
			</tr>
<?php
	}
?>
		</table>
	</div>
<?php
}

// 显示我的好友列表
function display_my_friend_list($userid){
	// 获取当前用户的好友列表
	$my_friends = get_current_user_friends($userid);
?>
	<!--好友列表-->
	<div>
		<p class="titleDiv">我都有哪些朋友</p>
		<table>
			<tr>
				<th style="width:30px;"></th>
				<th style="width:150px;">
					用户名
				</th>
				<th style="width:200px;">
					状态
				</th>
				<th style="width:120px; text-align:center;">操作</th>
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
		echo $activeFriend ? '等待对方确认' : '请处理好友请求';
	else if ($row["status"] == 1)
		echo $activeFriend ? '对方拒绝请求' : '已拒绝对方请求';
	else if ($row["status"] == 2)
		echo '已经成为好友';
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
			echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'agree\')" style="text-decoration:none;">同意</a>&nbsp;|&nbsp;<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'reject\')" style="text-decoration:none;">拒绝</a>';
		}
			
	}
	else if ($row["status"] == 1)
	{
		if ($activeFriend)
			echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'delete\')" style="text-decoration:none;">知道了</a>';
	}
	else if ($row["status"] == 2)
		echo '<a href="#" user_name="'.$row["userid"].'" friend_name="'.$row["friendid"].'" onclick="manage_friend_status(this, \'delete\')" style="text-decoration:none;">解除好友</a>';
?>
				</td>
			</tr>
<?php } ?>
		</table>
	</div>

<?php
}

// 显示其他人员列表（可以添加好友）
function display_other_people_list($userid){
	// 获取当前用户的好友列表
	$all_user_list = get_current_all_user_list($userid);
?>
	<!--用户列表-->
	<div>
		<p class="titleDiv">你可能认识那些人？</p>
		<table>
			<tr>
				<th style="width:30px;"></th>
				<th style="width:150px;">
					用户名
				</th>
				<th style="width:200px;">
					注册邮箱
				</th>
				<th style="width:120px; text-align:center;">操作</th>
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
					<a href="#" user_name="<?php echo $userid ?>" friend_name="<?php echo $row["username"] ?>" onclick="manage_friend_status(this, 'add')" style=" text-decoration:none; ">请求添加好友</a>
				</td>
			</tr>
<?php } ?>
		</table>
	</div>
	
<?php
}


// 绘制好友提醒便签信息列表
function display_friend_notes_table($userid){
?>
	<div>
		<table>
			<tr>
				<th style=" width:350px;">
					事项名称
				</th>
				<th style=" width:80px;">
					分类
				</th>
				<th style=" width:80px;">
					缓急
				</th>
				<th style=" width:70px;">
					抄送好友
				</th>
				<th style=" width:70px;">
					创建时间
				</th>
				<th style=" width:60px;">
					当前状态
				</th>
				<th style=" width:80px;">
					更多操作
				</th>
			</tr>
<?php
	// 获取当前用户的好友事项列表
	$friend_notes = get_current_user_friend_notes($userid);
	foreach ($friend_notes as $row){
?>
			<tr>
<?php
	echo '<td style="word-break:break-all;" title=><label>'.$row["title"].'</label></td>';

	if ($row["category"]=="unspecified")
		echo '<td>任意</td>';
	else if ($row["category"]=="office")
		echo '<td>办公室</td>';
	else if ($row["category"]=="home")
		echo '<td>回家处理</td>';

	if ($row["level"]=="unspecified")
		echo '<td>普通</td>';
	else if ($row["level"]=="emergent")
		echo '<td>紧急</td>';
	else if ($row["level"]=="important")
		echo '<td>重要</td>';
	else if ($row["level"]=="both")
		echo '<td>紧急重要</td>';

	echo '<td>'.$row["owneruserid"].'</td>';

	echo '<td>'.date("m-d", strtotime($row["createtime"])).'</td>';

	$status = intval($row["status"]);
	if ($status == 0)
		echo '<td>确认中</td>';
	else if ($status == 1)
		echo '<td>已接受</td>';
	else if ($status == 2)
		echo '<td>已拒绝</td>';
	else if ($status == 3)
		echo '<td>已完成</td>';
	else
		echo '<td>未知状态</td>';

if ($status == 2 || $status == 3)
	echo '<td><a href="#" style=" text-decoration:none; " item_id="'.$row["id"].'" onclick="update_friend_note_status(this, \'delete\')">知道了</a></td>';
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