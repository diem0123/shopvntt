<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 // set for language
	 //$pre = '';
     //main
     $main = & new Template('adm_addcommon'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idcommon = $itech->input['idcommon'];
		$idtype = $itech->input['idtype'];
		$typecat = $itech->input['typecat'];
		$subcat = $itech->input['subcat'];
		$commonname = $itech->input['commonname'];				
		$status = $itech->input['status'];
		$short_content = $itech->input['short_content'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		$user_incharge = $itech->input['cst'];
		
		$HOME = $itech->input['HOME'];		
		if($HOME == "") $HOME = 0;
		$tieudiem = $itech->input['tieudiem'];		
		if($tieudiem == "") $tieudiem = 0;
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		
	// set permission
		if($idtype != ""){
		$nlink = "adm_commonlist&idtype=".$idtype;
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $nlink, "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		}
		
		// set permission conditional for role
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID ",'ID_CONSULTANT');
		// don't allow user access 
		if($roleinfo['ROLE_TYPE'] != "Operation"){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;
		}
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);
	
	$list_commontype = $print_2->GetDropDown($idtype, "common_type","STATUS = 'Active'" ,"ID_TYPE", "SNAME".$pre, "IORDER");
	$list_type_category = $print_2->GetDropDown($typecat, "common_cat", "ID_TYPE = '".$idtype."'"  , "ID_CAT", "SNAME".$pre, "IORDER");
	$list_sub_category = $print_2->GetDropDown("", "common_cat_sub", "ID_CAT = '".$typecat."'"  , "ID_CAT_SUB", "SNAME".$pre, "IORDER");

		if($idcommon && $ac=="e" && $submit == "") {									
					
					$cm = $common->getInfo("common","ID_COMMON = '".$idcommon."'");
					//$userinfo = $common->getInfo($conn,"Consultant","ID_CONSULTANT = '".$cm['POST_BY."'");					
					$list_commontype = $print_2->GetDropDown($cm['ID_TYPE'], "common_type","STATUS = 'Active'" ,"ID_TYPE", "SNAME".$pre, "IORDER");
					$list_type_category = $print_2->GetDropDown($cm['ID_CAT'], "common_cat", "ID_TYPE = '".$cm['ID_TYPE']."'"  , "ID_CAT", "SNAME".$pre, "IORDER");
					
					$list_sub_category = $print_2->GetDropDown("", "common_cat_sub", "ID_CAT = '".$cm['ID_CAT']."'"  , "ID_CAT_SUB", "SNAME".$pre, "IORDER");					
					// check level 3 not empty
					if($cm['ID_CAT_SUB'] != 0 && $cm['ID_CAT_SUB'] != "" && $list_sub_category != ""){
					$main->set('show3', "style='display:table-row'");
					$main->set('list_sub_category', $print_2->GetDropDown($cm['ID_CAT_SUB'], "common_cat_sub", "ID_CAT = '".$cm['ID_CAT']."'"  , "ID_CAT_SUB", "SNAME".$pre, "IORDER"));
					}
					else{
					$main->set('list_sub_category', "");
					$main->set('show3', "style='display:none'");
					}
		
					$main->set('list_commontype', $list_commontype);
					$main->set('list_type_category', $list_type_category);
					$main->set('commonname', $cm['STITLE'.$pre]);
					if($cm['HOME']) $tbchecked = "checked";
					else $tbchecked = "";
					$main->set('tbchecked', $tbchecked);
					if($cm['TIEUDIEM']) $tbchecked2 = "checked";
					else $tbchecked2 = "";
					$main->set('tbchecked2', $tbchecked2);
					
					if($cm['STATUS'] == 'Active') $sl1 = "selected";
					elseif($cm['STATUS'] == 'Inactive') $sl2 = "selected";
					elseif($cm['STATUS'] == 'Deleted') $sl3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('dispr', "");					
					
					$main->set('short_content', $cm['SCONTENTSHORT'.$pre]);
					$content_decode = base64_decode($cm['SCONTENTS'.$pre]);
					$main->set('content', $content_decode);
					$main->set('mess', "");										
					$main->set('idcommon', $idcommon);
					if($cm['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $cm['PICTURE'];
					$main->set('thumbnail', $thumbnail);					
								
					$list_consultant = $print_2->GetDropDown($cm['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$ronly = "disabled";					
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);

			echo $main->fetch("adm_editcommon");
			exit;
		}
		
		elseif($idcommon && $ac=="e" && $submit != "") {
		
		$infomess = array();
		$checkfile = true;
		
		// get temp path to file		
		$tmp_name = $_FILES['file_ul']['name'];
		$file_ul = $_FILES['file_ul']['name'];
		
		// path file upload
		$dir_upload = $pathimg."/imagenews/";
		if(!is_dir($dir_upload))
				mkdir($dir_upload, 0777, true);
		// if file empty or upload failed
		$filesucess = "";
		$mess1 = ""; // check email exist		
		
		// check if file upload not empty
		if($file_ul != "")	{
			if($file_ul!="" && !$print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG")){			
			$file_ul ="";
			$checkfile = false;
			$mess1 = $lang['err_upload1'];
			}
			elseif($file_ul!="" && $print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG"))
			{
					$file_input_tmp = $_FILES['file_ul']['tmp_name'];				
					$prefix = time();
					$checkfile = true;
					//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
			}		
		}
		
		// if checkupload not successfull
			if($mess1 != "" ){
				
					// set param return value for form
					if($status == 'Active') $sl1 = "selected";
					elseif($status == 'Inactive') $sl2 = "selected";
					elseif($status == 'Deleted') $sl3 = "selected";
					
					$main->set('list_commontype', $list_commontype);
					$main->set('list_type_category', $list_type_category);
					$list_sub_category = $print_2->GetDropDown("", "common_cat_sub", "ID_CAT = ".$typecat  , "ID_CAT_SUB", "SNAME".$pre, "IORDER");
					// check level 3 not empty
					if($subcat != "" && $list_sub_category != ""){
					$main->set('show3', "style='display:table-row'");
					$main->set('list_sub_category', $print_2->GetDropDown($subcat, "common_cat_sub", "ID_CAT = ".$typecat  , "ID_CAT_SUB", "SNAME".$pre, "IORDER"));
					}
					else{
					$main->set('list_sub_category', "");
					$main->set('show3', "style='display:none'");
					}					
					
					$main->set('commonname', $commonname);
					if($HOME) $tbchecked = "checked";
					else $tbchecked = "";
					$main->set('tbchecked', $tbchecked);
					if($tieudiem) $tbchecked2 = "checked";
					else $tbchecked2 = "";
					$main->set('tbchecked2', $tbchecked2);
										
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('dispr', "");
					
					$main->set('short_content', $short_content);
					$main->set('content', $content);
					$main->set('mess', $mess1);
					
					$list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$ronly = "disabled";					
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);
					
					$main->set('idcommon', $idcommon);

					echo $main->fetch("adm_editcommon");
					exit;
							

				//echo $checkupload[0];
			} // end checkupload
			
			if($file_ul != "" && $checkfile){
			// upload receive file
			// file2 = $file_name la file goc chua resize
				$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);						
				$name_arr = explode('.', $file_ul);
				$type = end($name_arr);
				
					if($file2 != "" && ($type != "png" && $type != "PNG")){						
					// resize image file 1 for thumbnail													
							//$name1 = "small_".time()."_".rand()."_".$name_thumb[0];
							$name1 = "news_".$file2;
							// process get thumbnail resize image
							$picname = $file2;
							$pic_small = $name1;
							$origfolder = "imagenews";							
							$width = $itech->vars['wtb_news'];
							$height = $itech->vars['htb_news'];
							$quality = $itech->vars['quality'];
							$check_thumb = $print_2->makethumb($picname, $pic_small, $origfolder, $width, $height, $quality);
							// if resize successfully
							if($check_thumb) {
							$file1 = $pic_small;
							// check exist file old image on directory
								if (file_exists($dir_upload.$file2) && file_exists($dir_upload.$pic_small)) {
									unlink($dir_upload.$file2);					
								}
							}
							else $file1 = $file2;

						$filesucess = $file1;
						//echo "upload successfully!";
					}
					elseif($file2 != ""){
						 $filesucess = $file2;
					}
					else {
						  echo $lang['err_upload3'];
						  exit;
					}

			}
																				
			// input db	
			if($typecat == "") $typecat = 0;
			if($subcat == "") $subcat = 0;
			
			// check exist file images
					$cm_detail = $common->getInfo("common","ID_COMMON ='".$idcommon."'");
					$old_thumbnail = $cm_detail['PICTURE'];
					if($filesucess == ""){
					$filesucess = $old_thumbnail;
					$file1_check = false;
					}
					else $file1_check = true; 
					
				try {
					$ArrayData  = array( 1 => array(1 => "STITLE".$pre, 2 => $commonname),
										2 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										3 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),
										4 => array(1 => "STATUS", 2 => $status),										
										5 => array(1 => "DATE_UPDATED", 2 => date('Y-m-d H:i:s')),
										6 => array(1 => "ID_TYPE", 2 => $idtype),
										7 => array(1 => "POST_BY", 2 => $_SESSION['ID_CONSULTANT']),
										8 => array(1 => "PICTURE", 2 => $filesucess),
										9 => array(1 => "ID_CAT", 2 => $typecat),
										10 => array(1 => "ID_CAT_SUB", 2 => $subcat),
										11 => array(1 => "HOME", 2 => $HOME),
										12 => array(1 => "TIEUDIEM", 2 => $tieudiem)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_COMMON", 2 => $idcommon));
					$common->UpdateDB($ArrayData,"common",$update_condit);
					
				} catch (Exception $e) {
						echo $e;
					}
					
					// check remove old image
						if($file1_check && $old_thumbnail != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_thumbnail)) {
								unlink($dir_upload.$old_thumbnail);					
							} 
						}
					
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
