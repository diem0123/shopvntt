<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 // set for language
	 $pre = '';
     //main
     $main = & new Template('adm_addphoto'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idphoto = $itech->input['idphoto'];
		$idtype = $itech->input['idtype'];
		$typecat = $itech->input['typecat'];
		$subcat = $itech->input['subcat'];
		$photoname = $itech->input['photoname'];				
		$status = $itech->input['status'];
		$short_content = $itech->input['short_content'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		$user_incharge = $itech->input['cst'];
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		
	// set permission
	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(9), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
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

		if($idphoto && $ac=="e" && $submit == "") {									
					
					$cm = $common->getInfo("photos","ID_PT = '".$idphoto."'");					
					$main->set('photoname', $cm['STITLE'.$pre]);					
					
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
					$main->set('idphoto', $idphoto);
					if($cm['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $cm['PICTURE'];
					$main->set('thumbnail', $thumbnail);					
								
					$list_consultant = $print_2->GetDropDown($cm['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$ronly = "disabled";					
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);

			echo $main->fetch("adm_editphoto");
			exit;
		}
		
		elseif($idphoto && $ac=="e" && $submit != "") {
		
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
										
					$main->set('photoname', $photoname);					
										
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
					
					$main->set('idphoto', $idphoto);

					echo $main->fetch("adm_editphoto");
					exit;
							

				//echo $checkupload[0];
			} // end checkupload
			
			if($file_ul != "" && $checkfile){
			// upload receive file
			// file2 = $file_name la file goc chua resize
				$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);						
					if($file2 != ""){						
					// resize image file 1 for thumbnail													
							//$name1 = "small_".time()."_".rand()."_".$name_thumb[0];
							$name1 = "photo_".$file2;
							// process get thumbnail resize image
							$picname = $file2;
							$pic_small = $name1;
							$origfolder = "imagenews";							
							$width = $itech->vars['wthumb_photo'];
							$height = $itech->vars['hthumb_photo'];
							$quality = $itech->vars['quality'];
							$check_thumb = $print_2->makethumb($picname, $pic_small, $origfolder, $width, $height, $quality);
							// if resize successfully
							if($check_thumb) {
							$file1 = $pic_small;
							// check exist file old image on directory
								//if (file_exists($dir_upload.$file2) && file_exists($dir_upload.$pic_small)) {
									//unlink($dir_upload.$file2);					
								//}
							}
							else $file1 = $file2;

						$filesucess = $file1;
						//echo "upload successfully!";
					}
					else {
						  echo $lang['err_upload3'];
						  exit;
					}

			}
																				
			// input db	
			if($idtype == "") $idtype = 0;
			if($typecat == "") $typecat = 0;
			if($subcat == "") $subcat = 0;
			
			// check exist file images
					$cm_detail = $common->getInfo("photos","ID_PT ='".$idphoto."'");
					$old_thumbnail = $cm_detail['PICTURE'];
					$old_big = $cm_detail['BIG_IMG'];
					if($filesucess == ""){
					$filesucess = $old_thumbnail;
					$file2 = $old_big;
					$file1_check = false;
					}
					else $file1_check = true; 
					
				try {
					$ArrayData  = array( 1 => array(1 => "STITLE".$pre, 2 => $photoname),
										2 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										3 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),
										4 => array(1 => "STATUS", 2 => $status),										
										5 => array(1 => "DATE_UPDATED", 2 => date('Y-m-d')),
										6 => array(1 => "ID_TYPE", 2 => $idtype),
										7 => array(1 => "POST_BY", 2 => $_SESSION['ID_CONSULTANT']),
										8 => array(1 => "PICTURE", 2 => $filesucess),
										9 => array(1 => "ID_CAT", 2 => $typecat),
										10 => array(1 => "ID_CAT_SUB", 2 => $subcat),
										11 => array(1 => "BIG_IMG", 2 => $file2)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_PT", 2 => $idphoto));
					$common->UpdateDB($ArrayData,"photos",$update_condit);
					
				} catch (Exception $e) {
						echo $e;
					}
					
					// check remove old image
						if($file1_check && $old_thumbnail != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_thumbnail)) {
								unlink($dir_upload.$old_thumbnail);					
							}
							if (file_exists($dir_upload.$old_big)) {
								unlink($dir_upload.$old_big);					
							}
						}
					
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
