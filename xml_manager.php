<?php

include_once('db_fns.php');

// get and save default category data to db.
function save_def_category_data_to_db($userid){
	// get default category xml data.
	$xml = simplexml_load_file('data/default_category.xml');
	$xml_str = $xml->asXML();
	// save to db
	$query = "INSERT INTO piggynote_financial_category VALUES('', '".$userid."', '".$xml_str."', now(), '".$xml_str."')";
	return db_execute($query);
}

// ����userid��ȡ��������
function get_category_data_by_userid($userid){
	$query = "SELECT categorydata FROM piggynote_financial_category WHERE userid='".$userid."'";
	$result = db_execute_query($query);
	if (!$result) {
		return false;
    }

    return $result[0]["categorydata"];
}

// ��ȡ�û�����id��name����
function get_user_category_arr($userid){
	$category_xml_str = get_category_data_by_userid($userid);
	$arr = array();
	
	$xml = simplexml_load_string($category_xml_str);
	// ֧������
	foreach($xml->cost->item as $item){
		$arr[$item['value'].''] = $item->name;
	}
	// �������
	foreach($xml->income->item as $item){
		$arr[$item['value'].''] = $item->name;
	}

	return $arr;
}

// ��ȡǰ̨js���õķ������ݸ�ʽ�ַ���
function get_category_data_for_js($category_xml_str){
	$category_str = "";

	$xml = simplexml_load_string($category_xml_str);
	// ֧������
	foreach($xml->cost->item as $item){
		$category_str .= $item['value'].":".$item->name.":".$item->description.":".$item->usedtime.",";
	}
	if (substr($category_str, -1) == ",")
		$category_str = substr($category_str, 0, -1);
	$category_str .= ";";
	// �������
	foreach($xml->income->item as $item){
		$category_str .= $item['value'].":".$item->name.":".$item->description.":".$item->usedtime.",";
	}
	if (substr($category_str, -1) == ",")
		$category_str = substr($category_str, 0, -1);

	return $category_str;
}

// ���·�����
function update_category_item($userid, $categoryid, $category_name, $category_des){
	$cur_category_str = get_category_data_by_userid($userid);

	// ����͸�������
	$dom = new DomDocument();
	$dom->loadXML($cur_category_str);
	$xpath = new DomXpath($dom);
	$element = $xpath->query("//item[@value='".$categoryid."']")->item(0);
	if ($element->hasChildNodes()){
		$element->getElementsByTagName("name")->item(0)->nodeValue = $category_name;
		$element->getElementsByTagName("description")->item(0)->nodeValue = $category_des;
	}
	$new_category_xml = $dom->saveXML();

	// ���µ�db
	return UpdateUserFinancialCategory($userid, $new_category_xml);
}

// ����·���
function add_new_category_item($userid, $money_type, $ctg_name, $ctg_des){
	
	$newCtgId = 0;
	// ��ȡ�û������ַ���
	$cur_category_str = get_category_data_by_userid($userid);

	// ����Xml�ĵ�
	$dom = new DomDocument();
	$dom->loadXML($cur_category_str);
	$xpath = new DomXpath($dom);

	// ��ȡ���Id
	$updateElement = $xpath->query("//".$money_type);
	$updateItemList = $xpath->query("//".$money_type."/item");
	if ($money_type == "cost")
		$newCtgId = $updateItemList->length + 1;
	else
		$newCtgId = $updateItemList->length + 100;

	// ��ȡ�·���ڵ�Element
	$newItemElement = CreateNewItemElement($dom, $newCtgId, $ctg_name, $ctg_des);

	// ���뵽Dom�ĵ�
	$updateElement->item(0)->appendChild($newItemElement);

	// ����Xml���浽���ݿ�
	$new_category_xml = $dom->saveXML();

	// ���µ�db
	
	if(UpdateUserFinancialCategory($userid, $new_category_xml))
		return $newCtgId;
	else
		return -1;
}

// �����µķ�����ڵ�
function CreateNewItemElement($dom, $newCtgId, $ctg_name, $ctg_des){

	// �����µ����ýڵ�
	$newItemElement = $dom->createElement("item");
	$newIdAttr = $dom->createAttribute("value");
	$txtId = $dom->createTextNode($newCtgId);
	$newIdAttr->appendChild($txtId);

	$newNameElement = $dom->createElement("name");
	$txtName = $dom->createTextNode($ctg_name);
	$newNameElement->appendChild($txtName);

	$newDesElement = $dom->createElement("description");
	$txtDes = $dom->createTextNode($ctg_des);
	$newDesElement->appendChild($txtDes);

	$newTimeElement = $dom->createElement("usedtime");
	$txtTime = $dom->createTextNode("0");
	$newTimeElement->appendChild($txtTime);

	$newItemElement->appendChild($newIdAttr);
	$newItemElement->appendChild($newNameElement);
	$newItemElement->appendChild($newDesElement);
	$newItemElement->appendChild($newTimeElement);

	return $newItemElement;
}

// �����û�����
function UpdateUserFinancialCategory($userid, $new_xml_category){
	$query = "UPDATE piggynote_financial_category SET categorydata='".$new_xml_category."' WHERE userid='".$userid."'";
	return db_execute($query);
}

?>