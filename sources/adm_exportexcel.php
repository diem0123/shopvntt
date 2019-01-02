<?php

//Set gia tri cho bien
/*$ac = $itech->input['ac'];
$submit = $itech->input['submit'];	
$file_name = $itech->input['name_file']; 
//main
$main = & new Template('adm_exportexcel'); 
if($ac=="a" && $submit == ""){
		 echo $main->fetch("adm_exportexcel");
		 //echo ROOT_PATH."includes/class.uploader.php";

		 
	 }*/
/*if ($ac=="a"){*/
		/*******EDIT LINES 3-8*******/
		$conn=mysqli_connect("localhost", "root", "root","therapyshower_db") or die("can't connect database");
		$sql = "Select CODE,PRODUCT_NAME,INFO_SHORT,INFO_DETAIL,PRICE,PARAM,SALE,ID_CATEGORY,ID_TYPE,ID_PRO_CAT from products";
		$query = mysqli_query($conn,$sql);
		//$rows = mysqli_fetch_assoc($query);
		//echo '<pre>';
		//print_r($rows);
		//$num = count($rows);
		$i=0;
		while( ($rows = mysqli_fetch_assoc($query))!= NULL ){
				$get_data[$i] = array(
									0=>$rows['CODE'],
									1=>$rows['PRODUCT_NAME'],
									2=>$rows['INFO_SHORT'],
									3=>base64_decode($rows['INFO_DETAIL']),
									4=>$rows['PRICE'],
									5=>base64_decode($rows['PARAM']),
									6=>$rows['SALE'],
									7=>$rows['ID_CATEGORY'],
									8=>$rows['ID_TYPE'],
									9=>$rows['ID_PRO_CAT']
				);$i++;
				//echo $rows[$i]['CODE'];
				
				
			}
		//echo '<pre>';
		//print_r($get_data);
		require $_SERVER['DOCUMENT_ROOT'].'/mtherapyshower/includes/Classes/PHPExcel.php';
		// Bước 3: Khởi tạo đối tượng mới và xử lý
		$PHPExcel = new PHPExcel();
		 
		// Bước 4: Chọn sheet - sheet bắt đầu từ 0
		$PHPExcel->setActiveSheetIndex(0);
		 
		// Bước 5: Tạo tiêu đề cho sheet hiện tại
		$PHPExcel->getActiveSheet()->setTitle('List Products');
		// Bước 6: Tạo tiêu đề cho từng cell excel, 
		// Các cell của từng row bắt đầu từ A1 B1 C1 ...
		$PHPExcel->getActiveSheet()->setCellValue('A1', 'Mã Hàng');
		$PHPExcel->getActiveSheet()->setCellValue('B1', 'Tên hàng');
		$PHPExcel->getActiveSheet()->setCellValue('C1', 'Mô tả');
		$PHPExcel->getActiveSheet()->setCellValue('D1', 'Chi tiết'); 
		$PHPExcel->getActiveSheet()->setCellValue('E1', 'Giá');
		$PHPExcel->getActiveSheet()->setCellValue('F1', 'Thông số');
		$PHPExcel->getActiveSheet()->setCellValue('G1', 'Giảm giá');
		$PHPExcel->getActiveSheet()->setCellValue('H1', 'Mã danh mục');
		$PHPExcel->getActiveSheet()->setCellValue('I1', 'Mã loại');
		$PHPExcel->getActiveSheet()->setCellValue('J1', 'Mã dòng sản phẩm');
		
		// Bước 7: Lặp data và gán vào file
		// Vì row đầu tiên là tiêu đề rồi nên những row tiếp theo bắt đầu từ 2
		$rowNumber = 2;
		foreach($get_data as $result =>$item){

			 // A1, A2, A3, ...
			//$PHPExcel->getActiveSheet()->setCellValue('A' . $rowNumber, ($result + 1));
     
			// B1, B2, B3, ...
			$PHPExcel->getActiveSheet()->setCellValue('A' . $rowNumber, $item[0]);
		 
			// C1, C2, C3, ...
			$PHPExcel->getActiveSheet()->setCellValue('B' . $rowNumber, $item[1]);
			$PHPExcel->getActiveSheet()->setCellValue('C' . $rowNumber, $item[2]);
			$PHPExcel->getActiveSheet()->setCellValue('D' . $rowNumber, $item[3]);
			$PHPExcel->getActiveSheet()->setCellValue('E' . $rowNumber, $item[4]);
			$PHPExcel->getActiveSheet()->setCellValue('F' . $rowNumber, $item[5]);
			$PHPExcel->getActiveSheet()->setCellValue('G' . $rowNumber, $item[6]);
			$PHPExcel->getActiveSheet()->setCellValue('H' . $rowNumber, $item[7]);
			$PHPExcel->getActiveSheet()->setCellValue('I' . $rowNumber, $item[8]);
			$PHPExcel->getActiveSheet()->setCellValue('J' . $rowNumber, $item[9]);
			//$PHPExcel->getActiveSheet()->setCellValue('C' . $rowNumber, $item[1]);
			
			 
			// Tăng row lên để khỏi bị lưu đè
			$rowNumber++;
			
		}
		
			// Bước 8: Khởi tạo đối tượng Writer
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
		 
		// Bước 9: Trả file về cho client download
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="danh_sach_san_pham.xls"');
		header('Cache-Control: max-age=0');
		

		if (isset($objWriter)) {
			$objWriter->save('php://output');
		}	
		exit;
	

?>