<?php


// ����·���
function add_new_category_item($userid, $money_type, $ctg_name, $ctg_des){
	$newCtgId = 0;

	$cur_category_str = '<?xml version="1.0" encoding="gb2312"?>
<root><cost><item value="1"><name>��</name><description>Ư���·�Ŷ</description><usedtime>0</usedtime></item><item value="2"><name>ʳ</name><description>�ճ��Ժ�</description><usedtime>0</usedtime></item><item value="3"><name>ס</name><description/><usedtime>0</usedtime></item><item value="4"><name>��</name><description/><usedtime>0</usedtime></item><item value="5"><name>ҽ</name><description/><usedtime>0</usedtime></item><item value="6"><name>��</name><description/><usedtime>0</usedtime></item><item value="7"><name>��</name><description/><usedtime>0</usedtime></item><item value="8"><name>Ѷ</name><description/><usedtime>0</usedtime></item><item value="9"><name>Т</name><description/><usedtime>0</usedtime></item><item value="10"><name>��</name><description></description><usedtime>0</usedtime></item><item value="11"><name>��</name><description/><usedtime>0</usedtime></item><item value="12"><name>ѧ</name><description>ѧϰ���</description><usedtime>0</usedtime></item><item value="13"><name>Ͷ</name><description/><usedtime>0</usedtime></item><item value="14"><name>һ</name><description>һ����</description><usedtime>0</usedtime></item><item value="15"><name>����</name><description/><usedtime>0</usedtime></item></cost><income><item value="100"><name>н</name><description>���������������ȵ�</description><usedtime>0</usedtime></item><item value="101"><name>��</name><description>��ʱ������</description><usedtime>0</usedtime></item><item value="102"><name>Ͷ</name><description>Ͷ������</description><usedtime>0</usedtime></item><item value="103"><name>����</name><description/><usedtime>0</usedtime></item></income>
</root>
';
	// ����Ͳ�������
	$dom = new DomDocument();
	$dom->loadXML($cur_category_str);
	$xpath = new DomXpath($dom);


	$typeElement = $xpath->query("//root/".$money_type);
	$curMaxId = intval($typeElement->item(0)->lastChild->attributes->item(0)->nodeValue);
	$newCtgId = $curMaxId + 1;

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
	$typeElement->item(0)->appendChild($newItemElement);

	$new_xml_dom = $dom->saveXML();

	echo $new_xml_dom;

	return $newCtgId;
}

//echo add_new_category_item('yanggl', "income", "��������", "��������1");
echo add_new_category_item('yanggl', "cost", "test cost", "aaaa");

?>