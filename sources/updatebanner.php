<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_banner&idps=1', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
	$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
	$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
	
     //main
     $main = & new Template('adm_addbanner'); 	 		
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];		
		$idbn = $itech->input['idbn'];
		$idps = $itech->input['idps'];
		
		$titlebn = $itech->input['titlebn'];
		$linkbn = $itech->input['linkbn'];		
		$iorder = $itech->input['iorder'];
		$content = $itech->input['content'];
		
		$pathimg = $itech->vars['pathimg'];		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idbn) {
					$list_ps = $print_2->GetDropDown("", "banner_position"," 1 " ,"ID_PS", "NAME", "IORDER");
					$main->set('list_ps', $list_ps);
					$main->set('titlebn', "");
					$main->set('linkbn', "");
					$main->set('iorder', "");
					$main->set('content', "");
					
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m Banner");
					$main->set('ac', "a");
					$main->set('idbn', "");
					$main->set('idps', "");

			echo $main->fetch("adm_addbanner");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idbn) {

					$catinfo = $common->getInfo("banner","ID_BN = '".$idbn."'");
					$list_ps = $print_2->GetDropDown($catinfo['ID_PS'], "banner_position"," 1 " ,"ID_PS", "NAME", "IORDER");
					$main->set('list_ps', $list_ps);
					$main->set('titlebn', $catinfo['TITLE_IMG']);
					$main->set('linkbn', $catinfo['LINK']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('content', $catinfo['INFO']);
					$imgname = '<img src="images/bin/banner/'.$catinfo['NAME_IMG'].'" width="300">';
					$main->set('imgname', $imgname);
											
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh Banner");
					$main->set('ac', "e");
					$main->set('idbn', $idbn);
					$main->set('idps', $catinfo['ID_PS']);

			echo $main->fetch("adm_addbanner");
			exit;
		}		
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idbn) {

		// Begin input data
		// add banner
				try {
					$ArrayData = array( 1 => array(1 => "NAME_IMG", 2 => $filesucess),
										2 => array(1 => "TITLE_IMG", 2 => $titlebn),
										3 => array(1 => "IORDER", 2 => $iorder),
										4 => array(1 => "INFO", 2 => $content),
										5 => array(1 => "ID_PS", 2 => $idps),
										6 => array(1 => "LINK", 2 => $linkbn)										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"banner");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idbn) {

		$checkfile = true;
		
		// get temp path to file		
		$tmp_name = $_FILES['file_ul']['name'];
		$file_ul = $_FILES['file_ul']['name'];
		
		// path file upload
		$dir_upload = $pathimg."/banner/";
		if(!is_dir($dir_upload))
				mkdir($dir_upload, 0777, true);
		// if file empty or upload failed
		$filesucess = "";
		$mess1 = "";
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
					$prefix = "banner_".time();
					$checkfile = true;
					//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
			}		
		}
		
		// if checkupload not successfull
			if($mess1 != "" ){
				
					$catinfo = $common->getInfo("banner","ID_BN = '".$idbn."'");
					$list_ps = $print_2->GetDropDown($catinfo['ID_PS'], "banner_position"," 1 " ,"ID_PS", "NAME", "IORDER");
					$main->set('list_ps', $list_ps);
					$main->set('titlebn', $titlebn);
					$main->set('linkbn', $linkbn);
					$main->set('iorder', $iorder);
					$main->set('content', $content);
					$imgname = '<img src="images/bin/banner/'.$catinfo['NAME_IMG'].'" width="300">';
					$main->set('imgname', $imgname);
					$main->set('mess', $mess1);
											
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh Banner");
					$main->set('ac', "e");
					$main->set('idbn', $idbn);
					$main->set('idps', $catinfo['ID_PS']);

			echo $main->fetch("adm_addbanner");
			exit;
							

				//echo $checkupload[0];
			} // end checkupload
			
			if($file_ul != "" && $checkfile){
			// upload receive file		
				$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);						
					if($file2 == ""){
					echo $lang['err_upload3'];
					exit;					
					}
					else $filesucess = $file2;
					
			}
			
			// check exist file images
					$catinfo = $common->getInfo("banner","ID_BN = '".$idbn."'");
					$old_thumbnail = $catinfo['NAME_IMG'];
					if($filesucess == ""){
					$filesucess = $old_thumbnail;
					$file1_check = false;
					}
					else $file1_check = true;
			
		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME_IMG", 2 => $filesucess),
										2 => array(1 => "TITLE_IMG", 2 => $titlebn),
										3 => array(1 => "IORDER", 2 => $iorder),
										4 => array(1 => "INFO", 2 => $content),
										5 => array(1 => "ID_PS", 2 => $idps),
										6 => array(1 => "LINK", 2 => $linkbn)										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_BN", 2 => $idbn));
					$common->UpdateDB($ArrayData,"banner",$update_condit);
					
					// check remove old image
						if($file1_check && $old_thumbnail != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_thumbnail)) {
								unlink($dir_upload.$old_thumbnail);					
							} 
						}

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="v"  && $idbn) {
		$main = & new Template('adm_viewbanner'); 
		$catinfo = $common->getInfo("banner","ID_BN = '".$idbn."'");
					$list_ps = $print_2->GetDropDown($catinfo['ID_PS'], "banner_position"," 1 " ,"ID_PS", "NAME", "IORDER");
					$main->set('list_ps', $list_ps);
					$main->set('titlebn', $catinfo['TITLE_IMG']);
					$main->set('linkbn', $catinfo['LINK']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('content', $catinfo['INFO']);
					$imgname = '<img src="images/bin/banner/'.$catinfo['NAME_IMG'].'" width="300">';
					$main->set('imgname', $imgname);
					
			echo $main->fetch("adm_viewbanner");
			exit;
	
	}

?>
