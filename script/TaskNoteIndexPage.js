
//
// 工作日志页面脚本
//

    // Show or hide the detail div
    $(function () {

        $("#m_ShowdetailLink").click(function () {
            var detailDiv = $("#m_detailContainerDiv")[0];
            if (detailDiv.style.display == "") {
                detailDiv.style.display = "none";
            } else {
                detailDiv.style.display = "";
            }
        });
    });

    // change task status
    function ChangeTaskStatus(obj) {
        if (obj == null) {
            return;
        }
        else {
            var taskId = obj.attributes["taskId"].value;
            var newStatus = obj.value;
            // 调用ajax请求
            $.post("/TaskNote/ChangeTaskStatus", { "id": taskId, "newStatus": newStatus },
                    function (data) {
                        if (data == "success") {
                            // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                            window.location = "TaskNote";
                        } else {
                            alert("更新任务状态失败！");
                        }
                    });
        }
    }

    // filter task note by user
    function FilterTaskNoteByUser(obj) {
        if (obj == null) {
            return;
        }
        else {
            window.location = "/TaskNote?userId=" + obj.value;
        }
    }

    // filter finished list by different type
    function ChangeFinishedListType(obj) {
        if (obj == null) {
            return;
        }
        else {
            window.location = "/TaskNote?finishedFilter=" + obj.value;
        }
    }

    // delete specify task note
    function DeleteTask(obj) {
        if (obj == null) {
            return;
        }

        var taskId = obj.attributes["taskId"].value;
        var taskTitle = obj.attributes["taskTitle"].value;
        // confirm the delete action
        if (confirm("确认要删除下列任务项？\r\r " + taskTitle) == true) {
            // 调用ajax请求
            $.post("/TaskNote/DeleteTask", { "id": taskId },
                    function (data) {
                        if (data == "success") {
                            // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                            window.location = "TaskNote";
                        } else {
                            alert("删除任务项失败！");
                        }
                    });
        }
    }