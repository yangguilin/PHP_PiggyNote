
//
// 便签页面的脚本内容
//


// Show or hide the detail div
$(function () {

    $("#m_ShowdetailLink").toggle(function () {
			$("#m_detailContainerDiv").show(600);
		},function(){
			$("#m_detailContainerDiv").hide(600);
		});
});

// 由于修改重复类型，来更新时间选项的值
function UpdateOtherControlStatusByRepeatType(obj) {
    // target weekday control
    if (obj.value == "everyday") {
		$("#m_targetWeekdayList").css({"display":"none"});
		$("#m_targetWeekdayListLab").css({"display":"none"});
    } else {
		$("#m_targetWeekdayList").css({"display":""});
		$("#m_targetWeekdayListLab").css({"display":""});
    }
    // owner user control
    if (obj.value == "unspecified") {
		$("#m_owneruseridlist").css({"display":""});
		$("#m_owneruseridlab").remove();
    } else {
		$("#m_owneruseridlist option:first-child").attr("selected", true);
		$("#m_owneruseridlist").css({"display":"none"});
		if ($("#m_owneruseridlab").length == 0)
			$("<label id='m_owneruseridlab'>自己</label>").insertAfter("#m_owneruseridlistlab");
    }
}

// Show or hide the item detail
function ShowNoteDetail(clickObj) {

    var selectedItem;
    var itemList = document.getElementsByName("m_itemDetail");
    for (var i = 0; i < itemList.length; i++) {
        if (itemList[i].attributes["noteId"].value == clickObj.attributes["noteId"].value) {
            selectedItem = itemList[i];
            break;
        }
    }

    var detailDiv = selectedItem;
    if (detailDiv.style.display == "") {
        detailDiv.style.display = "none";
    } else {
        detailDiv.style.display = "";
    }
}

/// disable add new note button
// this function didn't work in chrome explorer, but IE and FireFox is working well.
function DisableAddNewNoteButton(obj) {
    $("#m_addNewNoteButton")[0].disabled = true;
}

// update add new note button status when title is changed
function NoteTitleChanged(obj) {
    if (obj != null && obj.value != "") {
        $("#m_addNewNoteButton")[0].disabled = false;
    } else {
        $("#m_addNewNoteButton")[0].disabled = true;
    }
}

// delete today note
function DeleteOrCancelTodayNote(obj) {
    if (obj == null) {
        return;
    }

    var noteId = obj.attributes["noteId"].value;
    var noteTitle = obj.attributes["noteTitle"].value;
    // confirm the delete action
    var isDeleted = false;
    if (confirm("确认是否删除下列事项？\r\r " + noteTitle) == true) {
        isDeleted = true;
    }

    if (isDeleted) {
        // 调用ajax请求
        $.post("current_note_process.php", { "id": noteId, "operation_type": "delete" },
            function (data) {
                if (data == "success") {
                    // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                    window.location = "index.php";
                } else {
                    alert("删除或更新事项失败！" + data);
                }
            });
    }
}

// finish current note
function FinisheCurrentNote(obj) {
    if (obj == null) {
        return;
    }

    var noteId = obj.attributes["noteId"].value;

    // 调用ajax请求
    $.post("current_note_process.php", { "id": noteId, "operation_type": "finish" },
        function (data) {
            if (data == "success") {
                // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                window.location = "index.php";
            } else {
                alert("更新完成状态失败！");
            }
        });
}

// Do this note today.
function DealThisNoteToday(obj) {
    if (obj == null) {
        return;
    }

    var noteId = obj.attributes["noteId"].value;

    // 调用ajax请求
    $.post("current_note_process.php", { "id": noteId, "operation_type": "do_it_today" },
            function (data) {
                if (data == "success") {
                    // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                    window.location = "index.php";
                } else {
                    alert("更新失败！");
                }
            });
}

// 撤销已完成事项为未完成状态
function CancelFinishedNote(obj) {
    if (obj == null) return;

    var noteId = obj.attributes["noteId"].value;

    // 调用ajax请求
    $.post("current_note_process.php", { "id": noteId, "operation_type": "cancel_finished" },
            function (data) {
                if (data == "success") {
                    // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                    window.location = "index.php";
                } else {
                    alert("更新失败！");
                }
            });
}

