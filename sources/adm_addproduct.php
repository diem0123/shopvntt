<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_addproduct'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$cat = $itech->input['cat'];
		$typecat = $itech->input['typecat'];
		if(!$typecat) $typecat = 0;
		$subcat = $itech->input['subcat'];
		if(!$subcat) $subcat = 0;

		$id_brand = $itech->input['id_brand'];
		// if(!$brand) $brand  = 0;
		// get default for ages and skills
		$age_type = 2;
		$ages_sub = $itech->input['ages_sub'];
		if(!$ages_sub) $ages_sub = 0;
		$skill_type = 3;
		$skills_sub = $itech->input['skills_sub'];
		if(!$skills_sub) $skills_sub = 0;
		
		$CODE = $itech->input['CODE'];
		$EAN_CODE = "";//$itech->input['EAN_CODE'];
		$productname = $itech->input['productname'];
		$custom_color = $itech ->input['custom_color'];
		$SIZE = $itech->input['sizepro'];
		$MATERIAL = "";//$itech->input['MATERIAL'];
		$STANDARD = $itech->input['STANDARD'];
		
		$USE_IN = $itech->input['USE_IN'];
		$ORIGIN = $itech->input['ORIGIN'];
		

		$material = $itech->input['material'];
		if(!$material) $material  = 0;
		
		$COUNT_QUANTITY = $itech->input['COUNT_QUANTITY'];
		if(!$COUNT_QUANTITY) $COUNT_QUANTITY  = 0;
		$AGE_NUMBER = "";//$itech->input['AGE_NUMBER'];		
		
		$PRICE = $itech->input['PRICE'];
		// process string to int
		$price_tem = explode(".",$PRICE);
		$price_str = "";
		for($i1=0;$i1<count($price_tem);$i1++){
			$price_str .= $price_tem[$i1];
		}
		
		if(!$PRICE) $PRICE = 0;
		else $PRICE = (int)$price_str;
		
		$SALE = $itech->input['SALE'];
		if($SALE == "") $SALE = 0;
		$EXPIRE = $itech->input['EXPIRE'];
		if($EXPIRE == "") $EXPIRE = 0;
		$KHUYENMAI = $itech->input['KHUYENMAI'];
		if($KHUYENMAI == "") $KHUYENMAI = 0;
		$HOT = $itech->input['HOT'];		
		if($HOT == "") $HOT = 0;
		
		$TIEUBIEU = $itech->input['TIEUBIEU'];		
		if($TIEUBIEU == "") $TIEUBIEU = 0;
		
		$iorder = $itech->input['iorder'];		
		if($iorder == "") $iorder = 0;
		
		$INFO_SHORT = $itech->input['INFO_SHORT'];
		$province = $itech->input['province'];
		//$sex = $itech->input['SEX'];
		$sex=0;
		$status = $itech->input['status'];
		$contentencode = $itech->input['contentencode'];
		//echo "content=".$contentencode;
		
		$content = $itech->input['content'];
		$contentencodeparam = $itech->input['contentencodeparam'];
		//echo "<br> param=".$contentencodeparam;
		$param = $itech->input['param'];
		
		$user_incharge = $itech->input['cst'];
		$addmore = $itech->input['addmore'];
		// get images from library
		$thumblibrary = $itech->input['thumblibrary'];
		$largelibrary = $itech->input['largelibrary'];
		
		if($thumblibrary){
		$thumb_temp = explode("/",$thumblibrary);
		$thumb_file = end($thumb_temp);
		}
		else $themb_file = "";
		if($largelibrary){
		$large_temp = explode("/",$largelibrary);
		$large_file = end($large_temp);
		}
		else $large_file = "";
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		
	// set permission		
		/*
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		*/
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
		//check consultant incharge for company
		
			//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
			// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;
			}
		

					$pagetitle = $itech->vars['site_name'];
					$main->set("title", $pagetitle);
	// get form company
	// if add company

	$list_category = $print_2->GetDropDown($cat, "categories"," STATUS = 'Active' " ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
	
	$list_provinces = $print_2->GetDropDown(1, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
	
	// list brand
	$list_brand = $print_2->GetDropDown($id_brand, "brand","STATUS = 'Active'" ,"ID_BRAND", "NAME_BRAND", "IORDER");
	// list material
	//$list_material = $print_2->GetDropDown($material, "material_lookup","" ,"ID_MT", "NAME", "IORDER");
	
		if($idcom && $ac=="a" && $submit == "") {				
					
					$main->set('list_category', $list_category);
					$main->set('list_type_category', "");
					$main->set('list_brand', $list_brand);
					$main->set('list_sub_category', "");			
					$main->set('sex',$sex);
					$main->set('custom_color',$custom_color);
					
					$main->set('list_provinces', $list_provinces);
					$main->set('CODE', "");
					$main->set('productname', "");					
					$main->set('EAN_CODE', "");
					$main->set('SIZE', "");
					$main->set('MATERIAL', "");
					$main->set('STANDARD', "");
					
					$main->set('USE_IN', "");
					$main->set('ORIGIN', "");
					
					//$main->set('list_material', $list_material);
					
					$main->set('COUNT_QUANTITY', 0);
					$main->set('AGE_NUMBER', "");					
					$main->set('PRICE', "");
					$main->set('SALE', "");
					$main->set('EXPIRE', "");
					$main->set('KHUYENMAI', "");
					$main->set('HOT', "");
					$main->set('TIEUBIEU', "");
					$main->set('iorder', "");
					$main->set('INFO_SHORT', "");
					if($roleinfo['ROLE_TYPE'] == "Operation"){
					$main->set('sl1', "selected");
					$main->set('sl2', "");
					$main->set('sl3', "");
					$main->set('dispr', "");
					}
					else{
					$main->set('sl1', "");
					$main->set('sl2', "selected");
					$main->set('sl3', "");
					$dispr = 'style = "display:none"';
					$main->set('dispr', $dispr);
					}
					$main->set('content', "");
					$main->set('param', "");
					$main->set('mess1', "");
					$main->set('mess2', "");
					$main->set('mess3', "");
					$main->set('idcom', $idcom);
					//check role type
					//$user_incharge_com['ID_CONSULTANT'] 
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					else{
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);
			echo $main->fetch("adm_addproduct");
			exit;
		}
		elseif($idcom && $ac=="a" && $submit != "") {
		// echo $id_brand;
		// path file upload
		$dir_upload = $pathimg."/".$cat."/";
		$mess_all = array();
		
		// check if file input in images library
	if(!$thumblibrary && !$largelibrary){
		if(!is_dir($dir_upload))
		mkdir($dir_upload, 0777, true);
		$checkfile = true;
		$checkfile2 = true;
				
		$file_ul = $_FILES['file_ul']['name'];
		$file_catalog = $_FILES['file_catalog']['name'];
		if($file_ul!="" && !$print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|jpeg|JPG|GIF|BMP|PNG|JPEG")){			
			$file_ul ="";
			$checkfile = false;
			$mess_all[1] = $lang['err_upload1'];
		}
		elseif($file_ul!="" && $print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|jpeg|JPG|GIF|BMP|PNG|JPEG"))
		{
				$file_input_tmp = $_FILES['file_ul']['tmp_name'];				
				$prefix = time();
				$checkfile = true;
				//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
		}
		else
		{
			$file_ul ="";
			$checkfile = false;
			$mess_all[1] = $lang['err_upload2'];
		}
		// check file catalog
		if($file_catalog!="" && !$print_2->check_extent_file($file_catalog,"pdf|PDF|doc|DOC|docx|DOCX|xls|XLS|xlsx|XLSX")){			
			$file_catalog ="";
			$checkfile2 = false;
			$mess_all[2] = $lang['err_upload1'];
		}
		elseif($file_catalog!="" && $print_2->check_extent_file($file_catalog,"pdf|PDF|doc|DOC|docx|DOCX|xls|XLS|xlsx|XLSX"))
		{
				$file_input_tmp_catalog = $_FILES['file_catalog']['tmp_name'];				
				$prefix = time();
				$checkfile2 = true;
				//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
		}
		/*
		else
		{
			$file_catalog ="";
			$checkfile = false;
			$mess_all[2] = $lang['err_upload2'];
		}*/

			// if checkupload not successfull return form add or edit
		if(!$checkfile || !$checkfile2){
			// set param return value for form
					$main->set('list_category', $list_category);
					$list_type_category = $print_2->GetDropDown($typecat, "categories_sub" , "ID_CATEGORY = '".$cat."'" , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
					$list_sub_category = $print_2->GetDropDown($subcat, "product_categories", "ID_TYPE = '".$typecat."'", "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");

					$main->set('list_type_category', $list_type_category);
					$main->set('list_sub_category', $list_sub_category);
					
					$main->set('list_provinces', $list_provinces);
					$main->set('CODE', $CODE);
					$main->set('productname', $productname);
					$main->set('EAN_CODE', $EAN_CODE);
					$main->set('SIZE', $SIZE);
					$main->set('MATERIAL', $MATERIAL);
					$main->set('STANDARD', $STANDARD);
					
					$main->set('custom_color',$custom_color);
					$main->set('USE_IN', $USE_IN);
					$main->set('ORIGIN', $ORIGIN);
					$main->set('list_brand', $list_brand);
					//$main->set('list_material', $list_material);
					//$main->set('sex',$sex);
					
					$main->set('COUNT_QUANTITY', $COUNT_QUANTITY);
					$main->set('AGE_NUMBER', $AGE_NUMBER);
					$main->set('PRICE', number_format($PRICE,0,"","."));
					$main->set('SALE', $SALE);
					if($EXPIRE) $EXPIRE = "checked";
					$main->set('EXPIRE', $EXPIRE);
					if($KHUYENMAI) $KHUYENMAI = "checked";
					$main->set('KHUYENMAI', $KHUYENMAI);
					if($HOT) $tbchecked1 = "checked";
					$main->set('tbchecked1', $tbchecked1);
					
					if($TIEUBIEU) $tbchecked = "checked";
					$main->set('tbchecked', $tbchecked);
					
					$main->set('iorder', $iorder);
					$main->set('INFO_SHORT', $INFO_SHORT);
					
					if($roleinfo['ROLE_TYPE'] == "Operation"){
					if($status == 'Active') $sl1 = "selected";
					elseif($status == 'Inactive') $sl2 = "selected";
					elseif($status == 'Deleted') $sl3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('dispr', "");
					}
					else{
					$main->set('sl1', "");
					$main->set('sl2', "selected");
					$main->set('sl3', "");
					$dispr = 'style = "display:none"';
					$main->set('dispr', $dispr);
					}					
					
					$main->set('content', $content);
					$main->set('param', $param);
					
					$main->set('mess1', "");
					$main->set('mess2', $mess_all[1]);
					$main->set('mess3', $mess_all[2]);
										
					$main->set('idcom', $idcom);
					//check role type
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					else{
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);

				echo $main->fetch("adm_addproduct");
				exit;
			
			} // end $mess_all okie
			else{
			// upload receive file
			// file2 = $file_name la file goc chua resize
				$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);
				//$file_cat = $print_2->copy_and_change_filename($file_input_tmp_catalog, $file_catalog, $dir_upload, $prefix);
				
					if($file2 != ""){						
					// resize image file 1 for thumbnail						
							
							//$name1 = "small_".time()."_".rand()."_".$name_thumb[0];
							$name1 = "small_".$file2;
							// process get thumbnail resize image
							$picname = $file2;
							$pic_small = $name1;
							$origfolder = $cat;							
							$width = $itech->vars['wthumb'];
							$height = $itech->vars['hthumb'];
							$quality = $itech->vars['qualitythumb'];
							$check_thumb = $print_2->makethumb($picname, $pic_small, $origfolder, $width, $height, $quality);
							// if resize successfully
							if($check_thumb) {
							$file1 = $pic_small;							
							}
							else $file1 = $file2;
						// resize image file 2 for slide (file3= slide)						
						// check size: <= 350 then no change, > 350 then change
						$f2wh = getimagesize($dir_upload.$file2);
						$f2width = $f2wh[0];
						$f2height = $f2wh[1];
						if($f2width <= $itech->vars['width_detail']) $file3 = $file2;
						else{
								$name3 = "slide_".$file2;
								// process get file2 resize image
								$picname3 = $file2;
								$pic_small3 = $name3;
								$origfolder3 = $cat;
								$width3 = $itech->vars['width_detail'];
								$height3 = $itech->vars['height_detail'];
								$quality3 = $itech->vars['quality_detail'];
								$check_thumb3 = $print_2->makethumb($picname3, $pic_small3, $origfolder3, $width3, $height3, $quality3);
								// if resize successfully
								if($check_thumb3) {
								$file3 = $pic_small3;								
								}
								else $file3 = $file2;
						}
						
						//echo "upload successfully!";
					}
					else {
						  echo $lang['err_upload3'];
						  exit;
					}
				
				
			}
	}	
			// input db								
				try {
					$ArrayData = array( 1 => array(1 => "ID_COM", 2 => $idcom),
										2 => array(1 => "ID_PRO_CAT_COM", 2 => $cat),
										3 => array(1 => "ID_PRO_CAT", 2 => $subcat),
										4 => array(1 => "PRODUCT_NAME".$pre, 2 => $productname),
										5 => array(1 => "THUMBNAIL", 2 => $file1),
										6 => array(1 => "IMAGE_LARGE", 2 => $file3),
										7 => array(1 => "IMAGE_BIG", 2 => $file2),
										8 => array(1 => "INFO_DETAIL".$pre, 2 => $contentencode),
										9 => array(1 => "PRICE", 2 => $PRICE),
										10 => array(1 => "SALE", 2 => $SALE),
										11 => array(1 => "EXPIRE", 2 => $EXPIRE),
										12 => array(1 => "ID_PROVINCE", 2 => $province),
										13 => array(1 => "DATE_POST", 2 => date('Y-m-d H:i:s')),
										14 => array(1 => "DATE_UPDATE", 2 => date('Y-m-d H:i:s')),
										15 => array(1 => "INFO_SHORT".$pre, 2 => $INFO_SHORT),
										16 => array(1 => "CODE", 2 => $CODE),
										17 => array(1 => "KHUYENMAI", 2 => $KHUYENMAI),										
										18 => array(1 => "EAN_CODE", 2 => $EAN_CODE),
										19 => array(1 => "SIZE", 2 => $SIZE),
										20 => array(1 => "MATERIAL".$pre, 2 => $MATERIAL),
										21 => array(1 => "STANDARD".$pre, 2 => $STANDARD),
										22 => array(1 => "COUNT_QUANTITY", 2 => $COUNT_QUANTITY),
										23 => array(1 => "AGE", 2 => $AGE_NUMBER),
										24 => array(1 => "ID_SKILL", 2 => $skills_sub),
										25 => array(1 => "ID_AGES", 2 => $ages_sub),
										26 => array(1 => "ID_CATEGORY", 2 => $cat),
										27 => array(1 => "ID_TYPE", 2 => $typecat),										
										28 => array(1 => "POST_BY", 2 => $_SESSION['ID_CONSULTANT']),
										29 => array(1 => "STATUS", 2 => $status),
										30 => array(1 => "PARAM".$pre, 2 => $contentencodeparam),										
										31 => array(1 => "IORDER", 2 => $iorder),
										32 => array(1 => "ID_BRAND", 2 => $id_brand),
										33 => array(1 => "ID_MT", 2 => $material),
										34 => array(1 => "USE_IN", 2 => $USE_IN),
										35 => array(1 => "ORIGIN", 2 => $ORIGIN),
										36 => array(1 => "TIEUBIEU", 2 => $TIEUBIEU),
										37 => array(1 => "SEX", 2 => $sex),
										38 => array(1 => "COLOR", 2 => $custom_color),
										39 => array(1 => "HOT", 2 => $HOT)
									  );
					// echo '<pre>';		  
					// print_r($ArrayData);exit;
					$idrt = $common->InsertDB($ArrayData,"products");
					//echo "idrt=".$idrt;exit;
					//$sql = "INSERT INTO products (PRODUCT_NAME,ID_PRO_CAT,POST_BY) value ( 'tin tuc', '1', '1') ";
					//$DB->query($sql);					
				} catch (Exception $e) {
						echo $e;
					}
				// check if addmore
				if($addmore == "addmore"){
				// set param return value for form
					$main->set('list_category', $list_category);
					$list_type_category = $print_2->GetDropDown($typecat, "categories_sub" , "ID_CATEGORY = '".$cat."'" , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
					$list_sub_category = $print_2->GetDropDown($subcat, "product_categories", "ID_TYPE = '".$typecat."'", "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");

					$main->set('list_type_category', $list_type_category);
					$main->set('list_sub_category', $list_sub_category);
					$main->set('list_provinces', $list_provinces);
					$main->set('list_brand', $list_brand);
					$main->set('CODE', $CODE);
					$main->set('productname', "");
					$main->set('PRICE', number_format($PRICE,0,"","."));
					$main->set('SALE', $SALE);
					
					$main->set('USE_IN', $USE_IN);
					$main->set('ORIGIN', $ORIGIN);
					$main->set('SIZE', "");					
					$main->set('STANDARD', "");
					
					$main->set('list_material', $list_material);
					
					if($EXPIRE) $EXPIRE = "checked";
					$main->set('EXPIRE', $EXPIRE);
					if($KHUYENMAI) $KHUYENMAI = "checked";
					$main->set('KHUYENMAI', $KHUYENMAI);
					if($HOT) $tbchecked1 = "checked";
					$main->set('tbchecked1', $tbchecked1);
					
					if($TIEUBIEU) $tbchecked = "checked";
					$main->set('tbchecked', $tbchecked);
					//$main->set('COUNT_QUANTITY', $COUNT_QUANTITY);
					
					$main->set('iorder', $iorder);
					$main->set('INFO_SHORT', "");
					
					if($roleinfo['ROLE_TYPE'] == "Operation"){
					if($status == 'Active') $sl1 = "selected";
					elseif($status == 'Inactive') $sl2 = "selected";
					elseif($status == 'Deleted') $sl3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('dispr', "");
					}
					else{
					$main->set('sl1', "");
					$main->set('sl2', "selected");
					$main->set('sl3', "");
					$dispr = 'style = "display:none"';
					$main->set('dispr', $dispr);
					}

					$main->set('content', "");
					$main->set('param', "");
					$main->set('mess1', "");
					$main->set('mess2', "");
					$main->set('mess3', "");
					$main->set('idcom', $idcom);
					//check role type
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					else{
						$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT NOT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);
			
				echo $main->fetch("adm_addproduct");
				exit;				
				}						
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
