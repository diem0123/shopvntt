<?php
		$pathimg = $itech->vars['pathimg'];
		$submit = $itech->input['uplibrary'];
		$idp = $itech->input['idp'];
		$n = $itech->input['n'];		
		
		$infomess = array();
		
		// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "AE")){
			$infomess['mess1'] = "Khong co quyen upload";		
		}
		
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
		//check consultant incharge for company
		
		//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
		// check permission com incharge owner
		if($roleinfo['ROLE_TYPE'] != "Operation"){
			$infomess['mess1'] = "Khong co quyen upload";
		}
		
		$pro_info = $common->getInfo("products","ID_PRODUCT ='".$idp."'");
		$cat = $pro_info['ID_CATEGORY'];
		// path file upload
		$dir_upload = $pathimg."/".$cat."/";
		if(!is_dir($dir_upload))
		mkdir($dir_upload, 0777, true);
		$check = true;
		$checkfile = array();
		$file_ul = array();
		$path_ul = array();
		$file_input_tmp = array();
		$prefix = array();
		$file_orginal = array();
		$file_out = array();
		$mess_all = array();
		// total file upload
		
		for($i=1;$i<=$n;$i++){
		$file_ul[$i] = $_FILES['img'.$i]['name'];		
		$path_ul[$i] = $_FILES['img'.$i]['tmp_name'];		
		}
		
		if($submit != ""){
		
			for($i=1;$i<=$n;$i++){
				if($file_ul[$i] !="" && !$print_2->check_extent_file($file_ul[$i],"jpg|gif|bmp|png|JPG|GIF|BMP|PNG")){			
					$file_ul[$i] ="";
					$checkfile[$i] = false;
					$mess_all[$i] = $lang['err_upload1'];
				}
				elseif($file_ul[$i] !="" && $print_2->check_extent_file($file_ul[$i],"jpg|gif|bmp|png|JPG|GIF|BMP|PNG"))
				{
						$file_input_tmp[$i] = $path_ul[$i];
						$prefix[$i] = time();
						$checkfile[$i] = true;
						$mess_all[$i] = "";
						//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
				}
				else
				{
					$file_ul[$i] ="";
					$file_input_tmp[$i] = "";
					$prefix[$i] = "";
					$checkfile[$i] = false;
					$mess_all[$i] = "Vui l&ograve;ng ch&#7885;n &#7843;nh &#273;&#7875; upload!";//$lang['err_upload2'];
				}
			}
			
			for($i=1;$i<=count($checkfile);$i++){
				if(!$checkfile[$i]) $check = false;
			}
			
			// get message error
			if(!$check){
				$infomess['mess0'] = "nosuccess";
				for($i=1;$i<=$n;$i++){
					$infomess['mess'.$i] = $mess_all[$i];
				}
				
				echo json_encode($infomess);
				exit;		
			}
			// upload file
			else{
				for($j=1;$j<=count($file_ul);$j++){
					if($file_ul[$j] != ""){
						$file_orginal[$j] = $print_2->copy_and_change_filename($file_input_tmp[$j], $file_ul[$j], $dir_upload, $prefix[$j]);
						if($file_orginal[$j] != ""){
						$infomess['mess0'] = "success";
						$infomess['mess'.$j] = "Upload th&agrave;nh c&ocirc;ng!";
							// resize image 																		
							$pic_out = "rs_".$file_orginal[$j];							
							$pic_orginal = $file_orginal[$j];							 
							$origfolder = $cat;
							$width = 762;//$itech->vars['width_detail'];
							$height = 1100;//$itech->vars['height_detail'];
							$quality = $itech->vars['quality_detail'];
							$check_thumb = $print_2->makethumb($pic_orginal, $pic_out, $origfolder, $width, $height, $quality);
							// if resize successfully
							if($check_thumb) {
							$file_out[$j] = $pic_out;
								// remove orginal file
								if (file_exists($dir_upload.$pic_out) && file_exists($dir_upload.$file_orginal[$j]))
								unlink($dir_upload.$file_orginal[$j]);
							}
							else $file_out[$j] = $file_orginal[$j];
							
							// insert database
							//get order							
							$iorder = $common->getListValue("images_link","ID_PRODUCT = '".$idp."' ORDER BY IORDER DESC ","IORDER") + 1;
							$text = $itech->input['text'.$j];
							
							try {
								$ArrayData = array( 1 => array(1 => "NAME_IMG", 2 => $file_out[$j]),
													2 => array(1 => "ID_PRODUCT", 2 => $idp),
													3 => array(1 => "IORDER", 2 => $iorder),
													4 => array(1 => "ID_CATEGORY", 2 => $cat),
													5 => array(1 => "NAME".$pre, 2 => $text)
												  );
										  
								$idimg = $common->InsertDB($ArrayData,"images_link");
									
							} catch (Exception $e) {
									echo $e;
								}
						
						}
						else {
						$infomess['mess'.$j] = "File kh&ocirc;ng th&#7875; upload!";
						}
					}
					else $infomess['mess'.$j] = "";
				}
	
				
				echo json_encode($infomess);
				exit;
			
			}
		}
		
		$main = & new Template('adm_prolink_img');
		$main->set('mess1', "");
		$main->set('mess2', "");
		$main->set('idp', $idp);
		$main->set('idc', $cat);
		$main->set('image_display',$pro_info['IMAGE_LARGE']);
					
		// set change size image
		$origfolder = $pro_info['ID_CATEGORY'];
		$width = $itech->vars['width_detail'];
		$height = $itech->vars['height_detail'];
		$wh = $print_2->changesizeimage($pro_info['IMAGE_LARGE'], $origfolder, $width, $height);
		$main->set( "w", $wh[0]);
		$main->set( "h", $wh[1]);

		echo $main->fetch("adm_prolink_img");

?>