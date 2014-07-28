<?php


// 添加新分类
function add_new_category_item($userid, $money_type, $ctg_name, $ctg_des){
	$newCtgId = 0;

	$cur_category_str = '<?xml version="1.0" encoding="gb2312"?>
<root><cost><item value="1"><name>衣</name><description>漂亮衣服哦</description><usedtime>0</usedtime></item><item value="2"><name>食</name><description>日常吃喝</description><usedtime>0</usedtime></item><item value="3"><name>住</name><description/><usedtime>0</usedtime></item><item value="4"><name>行</name><description/><usedtime>0</usedtime></item><item value="5"><name>医</name><description/><usedtime>0</usedtime></item><item value="6"><name>礼</name><description/><usedtime>0</usedtime></item><item value="7"><name>娱</name><description/><usedtime>0</usedtime></item><item value="8"><name>讯</name><description/><usedtime>0</usedtime></item><item value="9"><name>孝</name><description/><usedtime>0</usedtime></item><item value="10"><name>娃</name><description></description><usedtime>0</usedtime></item><item value="11"><name>借</name><description/><usedtime>0</usedtime></item><item value="12"><name>学</name><description>学习相关</description><usedtime>0</usedtime></item><item value="13"><name>投</name><description/><usedtime>0</usedtime></item><item value="14"><name>一</name><description>一笔帐</description><usedtime>0</usedtime></item><item value="15"><name>其他</name><description/><usedtime>0</usedtime></item></cost><income><item value="100"><name>薪</name><description>工资啦，奖金啦等等</description><usedtime>0</usedtime></item><item value="101"><name>贷</name><description>临时性收入</description><usedtime>0</usedtime></item><item value="102"><name>投</name><description>投资收益</description><usedtime>0</usedtime></item><item value="103"><name>其他</name><description/><usedtime>0</usedtime></item></income>
</root>
';
	// 载入和插入内容
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

//echo add_new_category_item('yanggl', "income", "测试收入", "我是描述1");
echo add_new_category_item('yanggl', "cost", "test cost", "aaaa");

?>