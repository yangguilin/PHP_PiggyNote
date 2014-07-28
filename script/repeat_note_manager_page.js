
    // change note property
    function ChangeNoteProperty(obj) {
        if (obj == null) {
            return;
        }
        else {
            var noteId = obj.attributes["noteId"].value;
            var propertyType = obj.attributes["propertyType"].value;
            var newPropertyValue = obj.value;
            // ����ajax����
            $.post("repeat_note_process.php", { "id": noteId, "propertyType": propertyType, "newPropertyValue": newPropertyValue, "operation_type": "update" },
                function (data) {
                    if (data == "success") {
                        // Ӧ�ò��ýű��Զ������б�Ŀǰ�Ȳ�����������ҳ��ķ�ʽ
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("������������ʧ�ܣ�" + data);
                    }
                });
        }
    }

	// �����ظ������ʹ��״̬
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

            // ����ajax����
            $.post("repeat_note_process.php", { "id": noteId, "propertyType": "isenable", "newPropertyValue": newPropertyValue, "operation_type": "update" },
                function (data) {
                    if (data == "success") {
                        // Ӧ�ò��ýű��Զ������б�Ŀǰ�Ȳ�����������ҳ��ķ�ʽ
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("������������ʧ�ܣ�" + data);
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
        if (confirm("ȷ��Ҫɾ�����ظ����\r\r " + noteTitle) == true) {
            // ����ajax����
            $.post("repeat_note_process.php", { "id": noteId, "operation_type": "delete" },
                function (data) {
                    if (data == "success") {
                        // Ӧ�ò��ýű��Զ������б�Ŀǰ�Ȳ�����������ҳ��ķ�ʽ
                        window.location = "repeat_note_manager_page.php";
                    } else {
                        alert("ɾ������ʧ�ܣ�");
                    }
                });
        }
    }
