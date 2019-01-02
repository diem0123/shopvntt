<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_editproduct'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idp = $itech->input['idp'];
		$cat = $itech->input['cat'];
		$typecat = $itech->input['typecat'];
		if(!$typecat) $typecat = 0;
		$subcat = $itech->input['subcat'];
		if(!$subcat) $subcat = 0;
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
		
		$SIZE = $itech->input['sizepro'];
		$MATERIAL = "";//$itech->input['MATERIAL'];
		$STANDARD = $itech->input['STANDARD'];
		
		$USE_IN = $itech->input['USE_IN'];
		$ORIGIN = $itech->input['ORIGIN'];

		$id_brand = $itech->input['id_brand'];
		// $brand = $itech->input['brand'];
		// if(!$brand) $brand  = 0;
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
		
		$custom_color = $itech->input['COLOR'];
		$RATE = $itech->input['RATE'];
		if($RATE == "") $RATE = 0;
		$INFO_SHORT = $itech->input['INFO_SHORT'];
		$province = $itech->input['province'];
		$status = $itech->input['status'];
		
		$sex = $itech->input['SEX'];
		
		
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
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
		//check consultant incharge for company
		if($idcom){
			$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
			// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation" && $_SESSION['ID_CONSULTANT'] != $user_incharge_com['ID_CONSULTANT']){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;
			}
		}

					$pagetitle = $itech->vars['site_name'];
					$main->set("title", $pagetitle);
	
	$list_category = $print_2->GetDropDown($cat, "categories"," STATUS = 'Active' " ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
	
	$list_provinces = $print_2->GetDropDown(1, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
	
	
		if($idp && $ac=="e" && $submit == "") {
					$pro_detail = $common->getInfo("products","ID_PRODUCT ='".$idp."'");
					$userinfo = $common->getInfo("consultant_incharge","ID_COM = '".$pro_detail['ID_COM']."'");

					// check permission com incharge owner
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$url_redidrect='index.php?act=error403';
						$common->redirect_url($url_redidrect);
						exit;
					}
					
					$list_category = $print_2->GetDropDown($pro_detail['ID_CATEGORY'], "categories"," STATUS = 'Active' " ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
					$list_type_category = $print_2->GetDropDown($pro_detail['ID_TYPE'], "categories_sub" , "ID_CATEGORY = '".$pro_detail['ID_CATEGORY']."'" , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
					$list_sub_category = $print_2->GetDropDown($pro_detail['ID_PRO_CAT'], "product_categories", "ID_TYPE = '".$pro_detail['ID_TYPE']."'", "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");										
					$list_provinces = $print_2->GetDropDown(1, "province","" ,"ID_PROVINCE", "NAME", "IORDER");					
		
					$main->set('list_category', $list_category);
					// print_r($list_category);exit;
					$main->set('list_type_category', $list_type_category);
					$main->set('list_sub_category', $list_sub_category);
					$main->set('list_provinces', $list_provinces);										
					$main->set('sex',$pro_detail['SEX']);	
					
					
					$main->set('CODE', $pro_detail['CODE']);
					$main->set('productname', $pro_detail['PRODUCT_NAME'.$pre]);					
					$main->set('EAN_CODE', $pro_detail['EAN_CODE']);
					$main->set('SIZE', $pro_detail['SIZE']);
					$main->set('MATERIAL', $pro_detail['MATERIAL']);
					$main->set('STANDARD', $pro_detail['STANDARD']);
					
					$main->set('USE_IN', $pro_detail['USE_IN']);
					$main->set('ORIGIN', $pro_detail['ORIGIN']);
					// list brand
					$list_brand = $print_2->GetDropDown($pro_detail['ID_BRAND'], "brand","STATUS = 'Active'" ,"ID_BRAND", "NAME_BRAND", "IORDER");
					$main->set('list_brand', $list_brand);
					// list material
					//$list_material = $print_2->GetDropDown($pro_detail['ID_MT'], "material_lookup","" ,"ID_MT", "NAME", "IORDER");
					//$main->set('list_material', $list_material);
					
					$main->set('COUNT_QUANTITY', $pro_detail['COUNT_QUANTITY']);
					$main->set('AGE_NUMBER', $pro_detail['AGE']);					
					$main->set('PRICE', number_format($pro_detail['PRICE'],0,"","."));
					$main->set('SALE', $pro_detail['SALE']);
					$main->set('RATE', $pro_detail['RATE']);
					
					if($pro_detail['EXPIRE']) $EXPIRE = "checked";
					$main->set('EXPIRE', $EXPIRE);
					if($pro_detail['KHUYENMAI']) $KHUYENMAI = "checked";
					$main->set('KHUYENMAI', $KHUYENMAI);
					if($pro_detail['HOT']) $tbchecked1 = "checked";
					$main->set('tbchecked1', $tbchecked1);
					
					if($pro_detail['TIEUBIEU']) $tbchecked = "checked";
					$main->set('tbchecked', $tbchecked);
					
					$main->set('iorder', $pro_detail['IORDER']);
					$main->set('INFO_SHORT', $pro_detail['INFO_SHORT'.$pre]);

					$main->set('custom_color', $pro_detail['COLOR']);
					
					// check show/hide status for operation and user
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						if($pro_detail['STATUS'] == 'Active') $sl1 = "selected";
						elseif($pro_detail['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($pro_detail['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
						$main->set('dispr', "");
					}
					else{
						if($pro_detail['STATUS'] == 'Active') $sl1 = "selected";
						elseif($pro_detail['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($pro_detail['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
						$dispr = 'style = "display:none"';
						$main->set('dispr', $dispr);
					}
					$content_out = base64_decode($pro_detail['INFO_DETAIL'.$pre]);
					$main->set('content', $content_out);
					$content_param = base64_decode($pro_detail['PARAM'.$pre]);
					$main->set('param', $content_param);
					$main->set('mess1', "");
					$main->set('mess2', "");
					$main->set('idcom', $idcom);
					
					$main->set('idp', $idp);
					$main->set('thumbnail', $pro_detail['THUMBNAIL']);
					$main->set('image_large', $pro_detail['IMAGE_LARGE']);
					$main->set('cat_directory', $pro_detail['ID_CATEGORY']);
					$main->set('catalog', $pro_detail['CATALOG']);
					
					//check consultant incharge for company
					//$user_incharge_com = $common->getInfo($conn,"ConsultantIncharge","ID_COM ='".$pro_detail->ID_COM."'"); => only check for post by and session idconsultant
					
					//check role type
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					else{
						$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);

			echo $main->fetch("adm_editproduct");
			exit;
		}
		elseif($idp && $ac=="e" && $submit != "") {
		// path file upload
		$color1 = $itech->input['custom_color'];
		//echo $color1;exit;
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
		if($file_ul!="" && !$print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG")){			
			$file_ul ="";
			$checkfile = false;
			$mess_all[1] = $lang['err_upload1'];
		}
		elseif($file_ul!="" && $print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG"))
		{
				$file_input_tmp = $_FILES['file_ul']['tmp_name'];				
				$prefix = time();
				$checkfile = true;
				//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
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
// no need check file empty with edit product		
		else
		{
			$file_ul ="";
			$checkfile = false;
			$mess_all[1] = $lang['err_upload2'];
		}				
*/
			// if checkupload not successfull return form add or edit
		if(!$checkfile || !$checkfile2 ){
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
					$main->set('sex',$sex);
					
					$main->set('USE_IN', $USE_IN);
					$main->set('ORIGIN', $ORIGIN);
					$main->set('list_brand', $list_brand);
					$main->set('list_material', $list_material);
					
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

					$main->set('custom_color', $custom_color);
					$main->set('RATE', $RATE);
					
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
					$main->set('idp', $idp);
					$pro_detail = $common->getInfo("products","ID_PRODUCT ='".$idp."'");
					$main->set('thumbnail', $pro_detail['THUMBNAIL']);
					$main->set('image_large', $pro_detail['IMAGE_LARGE']);
					$main->set('cat_directory', $pro_detail['ID_CATEGORY']);
					
					
					
					//check role type
					if($roleinfo['ROLE_TYPE'] == "Operation"){
						$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					else{
						$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
						$ronly = "disabled";
					}
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);
			
				echo $main->fetch("adm_editproduct");
				exit;
			
			} // end $mess_all okie
			else{
			// upload receive file
			$file_cat = $print_2->copy_and_change_filename($file_input_tmp_catalog, $file_catalog, $dir_upload, $prefix);
				if($file_ul != ""){
			// file2 = $file_name la file goc chua resize
				//$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);
				$file2 = $print_2->copy_and_change_filename_dd($file_input_tmp, $file_ul, $dir_upload, $prefix);
				
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
				else{
					$file1 = "";
					$file2 = "";
					$file3 = "";
				
				}
			}
	}	
			// input db	
			// check exist file images big and large
					$pro_detail = $common->getInfo("products","ID_PRODUCT ='".$idp."'");
					$old_thumbnail = $pro_detail['THUMBNAIL'];
					$old_image_large = $pro_detail['IMAGE_LARGE'];
					$old_image_big = $pro_detail['IMAGE_BIG'];
					$old_catalog = $pro_detail['CATALOG'];
					
					if($file1 == ""){					
					$file1 = $pro_detail['THUMBNAIL'];
					$file1_check = false;
					}
					else $file1_check = true;
					
					if($file3 == ""){
					$file3 = $pro_detail['IMAGE_LARGE'];
					$file3_check = false;
					}
					else $file3_check = true;
					
					if($file2 == ""){
					$file2 = $pro_detail['IMAGE_BIG'];
					$file2_check = false;
					}
					else $file2_check = true;
					
					if($file_cat == ""){
					$file_cat = $pro_detail['CATALOG'];
					$file_cat_check = false;
					}
					else $file_cat_check = true;
										
					
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
										13 => array(1 => "DATE_UPDATE", 2 => date('Y-m-d H:i:s')),
										14 => array(1 => "INFO_SHORT".$pre, 2 => $INFO_SHORT),
										15 => array(1 => "CODE", 2 => $CODE),
										16 => array(1 => "KHUYENMAI", 2 => $KHUYENMAI),										
										17 => array(1 => "EAN_CODE", 2 => $EAN_CODE),
										18 => array(1 => "SIZE", 2 => $SIZE),
										19 => array(1 => "MATERIAL".$pre, 2 => $MATERIAL),
										20 => array(1 => "STANDARD".$pre, 2 => $STANDARD),
										21 => array(1 => "COUNT_QUANTITY", 2 => $COUNT_QUANTITY),
										22 => array(1 => "AGE", 2 => $AGE_NUMBER),
										23 => array(1 => "ID_SKILL", 2 => $skills_sub),
										24 => array(1 => "ID_AGES", 2 => $ages_sub),
										25 => array(1 => "ID_CATEGORY", 2 => $cat),
										26 => array(1 => "ID_TYPE", 2 => $typecat),										
										27 => array(1 => "POST_BY", 2 => $_SESSION['ID_CONSULTANT']),
										28 => array(1 => "STATUS", 2 => $status),
										29 => array(1 => "PARAM".$pre, 2 => $contentencodeparam),
										30 => array(1 => "CATALOG", 2 => $file_cat),
										31 => array(1 => "IORDER", 2 => $iorder),
										//32 => array(1 => "ID_BRAND", 2 => 0),
										//32 => array(1 => "ID_MT", 2 => $material),
										//33 => array(1 => "USE_IN", 2 => $USE_IN),
										//34 => array(1 => "ORIGIN", 2 => $ORIGIN),
										32 => array(1 => "TIEUBIEU", 2 => $TIEUBIEU),
										//36 => array(1 => "COLOR", 2 => $color1),
										//37 => array(1 => "SEX", 2 => $sex),
										33 => array(1 => "HOT", 2 => $HOT)
									  );
				//	echo'<pre>';print_r($ArrayData);exit;	  
					$update_condit = array( 1 => array(1 => "ID_PRODUCT", 2 => $idp));
					$common->UpdateDB($ArrayData,"products",$update_condit);
			
			
					// check remove old image
						if($file1_check && $old_thumbnail != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_thumbnail)) {
								unlink($dir_upload.$old_thumbnail);					
							} 
						}	
						if($file3_check && $old_image_large != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_image_large)) {
								unlink($dir_upload.$old_image_large);					
							} 
						}
						if($file2_check && $old_image_big != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_image_big)) {
								unlink($dir_upload.$old_image_big);					
							} 
						}
						
						if($file_cat_check && $old_catalog != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_catalog)) {
								unlink($dir_upload.$old_catalog);					
							} 
						}

						
				} catch (Exception $e) {
						echo $e;
					}									
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
