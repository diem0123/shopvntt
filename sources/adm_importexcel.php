<?php
//	mysqli_query("SET character_set_results=utf8");
    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_importexcel'); 	 
	 
	$idcom = 1;// default for tomiki
	$id_pro_cat_com = 1;
	
	//Set cat và typecat
	if($_SESSION['cat'] AND $_SESSION['typecat'] ){
		$cat 	 = $_SESSION['cat'];
		$typecat = $_SESSION['typecat'];
		//echo $cat.' || '.$typecat;exit;
	}
	elseif($_SESSION['cat'] AND $_SESSION['typecat'] =='' ){
		$cat 	 = $_SESSION['cat'];
		$typecat = 0;
	}
	// set for wysywyg insert images
	$_SESSION['idcom'] = $idcom;
	$ac = $itech->input['ac'];
	$file = $itech->input['files'];
	$files = $_FILES['files'];
	$submit = $itech->input['submit'];
	$ex = $itech->input['ex'];
	
	if($idcom && $ac=="a" && $submit == ""){
		 echo $main->fetch("adm_importexcel");
		 //echo ROOT_PATH."includes/class.uploader.php";
		 
		 exit;
		 
	 }
	elseif($idcom && $ac=="a" && $submit != "") {
			if(!$files['name'][0]){
				echo $main->fetch("adm_importexcel");
				//echo'<pre>';echo($files['name'][0]);exit;
				echo 'Không có file';exit;
			}
			else{
			//include(ROOT_PATH."includes/class.uploader.php");
				$uploader = new Uploader();
				$data = $uploader->upload($_FILES['files'], array(
					'limit' => 10, //Maximum Limit of files. {null, Number}
					'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
					'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
					'required' => false, //Minimum one file is required for upload {Boolean}
					'uploadDir' => ROOT_PATH.'files/', //Upload directory {String}
					'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
					'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
					'replace' => true, //Replace the file if it already exists  {Boolean}
					'perms' => null, //Uploaded file permisions {null, Number}
					'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
					'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
					'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
					'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
					'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
					'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
				));
					if($data['isComplete']){
					$conn=mysqli_connect("localhost", "root", "root","lacoco_db") or die("can't connect database");
					mysqli_set_charset($conn, "utf8");
					$info = $data['data'];
					//echo'<pre>';print_r($info['metas']);exit;
					foreach($info['metas'] as $getdata){
						//echo $getdata['file'];
						//echo '<pre>'; print_r($getdata);exit;
						 $filename = $getdata['file']; //Url file excel
						 $inputFileType = PHPExcel_IOFactory::identify($filename); 
						 $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
						 $objReader->setReadDataOnly(true); /**  Load $inputFileName to a PHPExcel Object  **/ 
						 $objPHPExcel = $objReader->load("$filename"); 
						 $total_sheets=$objPHPExcel->getSheetCount();
						 $allSheetName=$objPHPExcel->getSheetNames();
						 $objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
						 $highestRow    = $objWorksheet->getHighestRow();
						 $highestColumn = $objWorksheet->getHighestColumn();
						 $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						
						 $arraydata = array(); 
						 $arraydata_id = array();
						 for ($row = 3; $row <= $highestRow;++$row)
							 {     for ($col = 0; $col <$highestColumnIndex;++$col)     
								{         $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
										  //print_r($value);
										  //$objWorksheet->setCellValue($col.$row, $code);
										  $arraydata[$row-2][$col]=$value;     
								} 
							} 
						
						echo '<pre>';
						//print_r($arraydata);echo'<br>';exit;
						$num = count($arraydata);
						//echo $num;exit;
						
						for($i=1;$i<=$num;$i++){
							$get_array[$i] = array(
												0  => $arraydata[$i][0],
												1  => $arraydata[$i][1],//Mã vạch -> Mã code dung tích (capacity_product)
												2  => $arraydata[$i][2],//Tên hàng (product)
												3  => $arraydata[$i][3],//Nhóm hàng -> Mã code ( product )
												4  => $arraydata[$i][4],//Dung tích (capacity_product)
												5  => $arraydata[$i][5],//Giá sản phẩm (product, capacity_product)
												6  => $arraydata[$i][6],
												7  => $arraydata[$i][7],
												8  => $arraydata[$i][8],
												9  => $arraydata[$i][9],
												10 => $arraydata[$i][10],
												11 => $arraydata[$i][11],
												12 => $arraydata[$i][12],
												13 => $arraydata[$i][13],
												14 => $arraydata[$i][14],
												15 => $cat,              //ID_CATEGORY (product)
												16 => $typecat           //ID_TYPE (product)
												
								);
							$array_product[$i] = array(
												0 => $cat, 				//ID_CATEGORY
												1 => $typecat,			//ID_TYPE
												2 => $arraydata[$i][2],	//NAME_PRODUCT
												3 => $arraydata[$i][5],	//PRICE
												4 => $arraydata[$i][3]	//CODE
												
								);// Set mảng cho sản phẩm
							$array_cap_product[$i] = array(
												0 => $arraydata[$i][4],//Dung tích
												1 => $arraydata[$i][1],//Mã code dung tích
												2 => $arraydata[$i][5],//Giá sản phẩm
												
								);// Set mảng cho dung tích
							
						}
						echo '<pre>';
						print_r($array_product);
						//echo $j;
						exit;
						$num = count($get_array);
						for($i=0;$i<$num;$i++){
							
							//Kiểm tra ma hàng đã tồn tại hay chưa nếu chưa thực hiện add ngược lại update
							$check_sql = 'SELECT * FROM products where PRODUCT_NAME = "'.$get_array[$i][1].'"';
							//echo $check_sql;
							$re_check = mysqli_query($conn,$check_sql);
							$rows_check = mysqli_fetch_assoc($re_check);
							//if(!empty($get_array[$i][12])){$id_pro_cat = $get_array[$i][12];}else {	
							$id_pro_cat = 0;
							//}
							if($rows_check==''){
								//Nếu dữ liệu file excel không tồn tại trong csdl thực hiện thêm mới
								
								//echo'them';
								//echo $arraydata[$i][8];
							
								$get_data = array(
											'ID_COM'         =>$idcom,
											'ID_PRO_CAT_COM' =>$id_pro_cat_com,
											'ID_CATEGORY'    =>$get_array[$i][8],
											'ID_TYPE'        =>$get_array[$i][10],
											'CODE'           =>$get_array[$i][0],
											'ID_PRO_CAT'     =>$id_pro_cat,
											'PRODUCT_NAME'   =>$get_array[$i][1],
											'INFO_SHORT'     =>$get_array[$i][2],
											'INFO_DETAIL'    =>base64_encode($get_array[$i][3]),
											'PRICE'          =>$get_array[$i][4],
											'PARAM'          =>base64_encode($get_array[$i][5]),
											'SALE'           =>$get_array[$i][6],
											'STATUS'         =>'Active',
											'THUMBNAIL'		 =>'small_1476961068_noimages.jpg',
											'POST_BY'        =>$_SESSION['ID_CONSULTANT'],
											'DATE_POST'      =>date('Y-m-d H:i:s'),
											'ID_BRAND'		 =>0
								);
								//echo '<pre>';
								//print_r ($get_data);exit;
								$add_query = 'INSERT INTO products (ID_COM,ID_PRO_CAT_COM,ID_CATEGORY,ID_TYPE,ID_PRO_CAT,CODE,PRODUCT_NAME,INFO_SHORT,INFO_DETAIL,PRICE,PARAM,SALE,STATUS,THUMBNAIL,IMAGE_LARGE,POST_BY,DATE_POST,ID_BRAND) VALUES ('.$get_data['ID_COM'].','.$get_data['ID_PRO_CAT_COM'].','.$get_data['ID_CATEGORY'].','.$get_data['ID_TYPE'].','.$get_data['ID_PRO_CAT'].',"'.$get_data['CODE'].'","'.$get_data['PRODUCT_NAME'].'","'.$get_data['INFO_SHORT'].'","'.$get_data['INFO_DETAIL'].'",'.$get_data['PRICE'].',"'.$get_data['PARAM'].'","'.$get_data['SALE'].'","'.$get_data['STATUS'].'","'.$get_data['THUMBNAIL'].'","'.$get_data['THUMBNAIL'].'",'.$get_data['POST_BY'].',"'.$get_data['DATE_POST'].'","'.$get_data['ID_BRAND'].'")';
								//echo '<pre>';
								//echo $add_query;exit;
								//mysql_query("SET NAMES 'UTF8'");
								if(mysqli_query($conn,$add_query))
								{	$check_final = 1;								
									//echo 'Import dữ liệu thành công';
									//echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
								}
								else{
									$check_final = 0;
									echo $main->fetch("adm_importexcel");
									echo 'Import dữ liệu không thành công ';
								}
							}
							elseif($rows_check !='') {

								$get_data = array(
											'ID_COM'         =>$idcom,
											'ID_PRO_CAT_COM' =>$id_pro_cat_com,
											'ID_CATEGORY'    =>$get_array[$i][8],
											'ID_TYPE'        =>$get_array[$i][10],
											'ID_PRO_CAT'     =>$get_array[$i][12],
											'CODE'           =>$get_array[$i][0],
											'PRODUCT_NAME'   =>$get_array[$i][1],
											'INFO_SHORT'     =>$get_array[$i][2],
											'INFO_DETAIL'    =>base64_encode($get_array[$i][3]),
											'PRICE'          =>$get_array[$i][4],
											'PARAM'          =>base64_encode($get_array[$i][5]),
											'SALE'           =>$get_array[$i][6],
											'STATUS'		 => 'Active',
											'POST_BY'		 =>$_SESSION['ID_CONSULTANT'],
											'DATE_UPDATE'    =>date('Y-m-d H:i:s')
								);
								
								$update_query = "UPDATE products SET
													ID_COM          = ".$get_data['ID_COM'].",
													ID_PRO_CAT_COM  = ".$get_data['ID_PRO_CAT_COM'].",
													ID_CATEGORY     = ".$get_data['ID_CATEGORY'].",
													ID_TYPE         = ".$get_data['ID_TYPE'].",
													ID_PRO_CAT      = ".$get_data['ID_PRO_CAT'].",
													PRODUCT_NAME    ='".$get_data['PRODUCT_NAME']."',
													INFO_SHORT      ='".$get_data['INFO_SHORT']."',
													INFO_DETAIL     ='".$get_data['INFO_DETAIL']."',
													PRICE           = ".$get_data['PRICE'].",
													PARAM           ='".$get_data['PARAM']."',
													SALE            =".$get_data['SALE'].",
													STATUS          ='".$get_data['STATUS']."',
													DATE_UPDATE     ='".$get_data['DATE_UPDATE']."',
													POST_BY         =".$get_data['POST_BY']."
								                WHERE PRODUCT_NAME  = '".$get_data['PRODUCT_NAME']."'";
							
								
								if(mysqli_query($conn,$update_query)){
									$check_final=1;
									//echo 'Import dữ liệu thành công';
								}
								else{
									$check_final=0;
									echo 'Update thất bại';
									
								}
							}
							//Nếu check_final = 1 thực hiện reload trang admin list products
							if ($check_final ==1){
								//echo 'thành công';
								echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
							}
							else {
								
								//echo $main->fetch("adm_importexcel");
								echo 'Có lỗi không thể Import dữ liệu vào database';
								break;
							}
							//Nếu check_final = 0 thông báo lỗi trả về admin_importexcel
						  //$add_sql='INSERT INTO tb_excel(name,email,phone) VALUES("'.$get_array[$i][0].'","'.$get_array[$i][1].'","'.$get_array[$i][2].'")';
						  //if (mysqli_query($conn,$add_sql))echo 'thanh cong';
						 
						}
					   					 
					}
				}
					

				if($data['hasErrors']){
					$errors = $data['errors'];
					print_r($errors);
				}
								
			}
		}

?>
