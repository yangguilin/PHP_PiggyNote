<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'phpexcel/Classes/PHPExcel.php';
include("note_sc_fns.php");

// Extract the post values.
extract($_POST);

if ($operation_type && $operation_type == "download_all_record_notes" && $user_name) {
	// 查询用户的账目记录
	$user_data = query_user_data($user_name);
	// 将数据文件写入excel文件，并下载
	download_excel_file($user_data, $user_name);
	exit;
}else{
	echo 'parameter error';
}


// 以excel格式下载用户数据文件
function download_excel_file($user_data, $user_name){
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Piggy Notes")
								 ->setLastModifiedBy("Piggy Notes")
								 ->setTitle("Piggy Notes Financial Data")
								 ->setSubject("Piggy Notes Financial Data")
								 ->setDescription("Piggy Notes Financial Data in Excel Format")
								 ->setKeywords("Piggy Notes Financial Data")
								 ->setCategory("Financial");


   // 写入目录标题
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '序号')
			->setCellValue('B1', '类别')
			->setCellValue('C1', '详细分类')
			->setCellValue('D1', '金额(元)')
			->setCellValue('E1', '记录时间')
			->setCellValue('F1', '备注')
			->setCellValue('G1', '统计类型')
			->setCellValue('H1', '详细分类数值');

   // 获取用户分类信息
   $user_category_arr = get_user_category_arr($user_name);

   // 循环写入数据到excel
   $row_num = 2;
   foreach ($user_data as $row) {
	   $stat_type = '';
	   switch ($row['stattype']){
			case 1 : {
				$stat_type = "日常";
				break;
			}
			case 2 : {
				$stat_type = "阶段";
				break;
			}
			case 3 : {
				$stat_type = "大额";
				break;
			}
	   }

     	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$row_num, $row_num - 1)
				->setCellValue('B'.$row_num, ($row['moneytype'] == "cost" ? '支出' : '收入'))
				->setCellValue('C'.$row_num, $user_category_arr[$row['category'].''])
				->setCellValue('D'.$row_num, $row['amount'])
				->setCellValue('E'.$row_num, date('Y-m-d', strtotime($row['createtime'])))
				->setCellValue('F'.$row_num, iconv("GBK", "UTF-8",$row['remark']))
				->setCellValue('G'.$row_num, $stat_type)
				->setCellValue('H'.$row_num, $row['category']);
		$row_num++;
   }

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('PiggyNotes');
	// 调整数据列的宽度、隐藏列
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setVisible(false);
	$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Excel5)
	$file_name = 'my_financial_data_'.date('y_M_d').'.xls';
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
}

// 查询用户所有数据文件
function query_user_data($userid){
	$query = "SELECT * FROM piggynote_financial_dailynote p WHERE userid='".$userid."' ORDER BY createtime DESC";
	$result = db_execute_query($query);
	if (!$result)
		return false;

	return $result;
}

?>