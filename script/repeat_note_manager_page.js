
    // change note property
    function ChangeNoteProperty(obj) {
        if (obj == null) {
            return;
        }
        else {
            var noteId = obj.attributes["noteId"].value;
            var propertyType = obj.attributes["propertyType"].value;
            var newPropertyValue = obj.value;
            // 调用ajax请求
            $.post("repeat_note_process.php", { "id": noteId, "propertyType": propertyType, "newPropertyValue": newPropertyValue, "operation_type": "update" },
                function (data) {
                    if (data == "success") {
                        // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("更新事项属性失败！" + data);
                    }
                });
        }
    }

	// 更改重复事项的使用状态
    function ChangeRepeatNoteEnableStatus(obj) {
        if (obj == null) {
            return;
        }
        else {
            var noteId = obj.attributes["noteId"].value;
            var procType = obj.attributes["procType"].value;
            var newPropertyValue = 0;
			if (procType == "start")
				newPropertyValue = 1;

            // 调用ajax请求
            $.post("repeat_note_process.php", { "id": noteId, "propertyType": "isenable", "newPropertyValue": newPropertyValue, "operation_type": "update" },
                function (data) {
                    if (data == "success") {
                        // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("更新事项属性失败！" + data);
                    }
                });
        }
    }

    // delete specify repeat note
    function DeleteRepeatNote(obj) {
        if (obj == null) {
            return;
        }

        var noteId = obj.attributes["noteId"].value;
        var noteTitle = obj.attributes["noteTitle"].value;
        // confirm the delete action
        if (confirm("确认要删除该重复事项？\r\r " + noteTitle) == true) {
            // 调用ajax请求
            $.post("repeat_note_process.php", { "id": noteId, "operation_type": "delete" },
                function (data) {
                    if (data == "success") {
                        // 应该采用脚本自动更新列表，目前先采用重新载入页面的方式
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("删除事项失败！");
                    }
                });
        }
    }
