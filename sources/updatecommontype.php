<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(9), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	 
     //main
     $main = & new Template('addcommontype'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idtype = $itech->input['idtype'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$status = $itech->input['status'];
		$short_content = $itech->input['short_content'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		$mn = $itech->input['mn'];		
		if($mn == "") $mn = 0;
		$ht = $itech->input['ht'];// cach hien thi thong tin
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		$ht1 = "";
		$ht2 = "";

	
	$pagetitle = $itech->vars['site_name'];
	$pathimg = $itech->vars['pathimg'];
	$idcom = 1;// default for com
	// set for wysywyg insert images
	$_SESSION['idcom'] = $idcom;
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idtype) {
					$main->set('catname', "");
					$main->set('iorder', "");
					$main->set('sl1', "selected");
					$main->set('sl2', "");
					$main->set('sl3', "");
					$main->set('short_content', "");
					$main->set('content', "");					
					$main->set('tbchecked', "");
					$main->set('ht1', "selected");
					$main->set('ht2', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m chuy&ecirc;n m&#7909;c");
					$main->set('ac', "a");
					$main->set('idtype', "");

			echo $main->fetch("addcommontype");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idtype) {
					$catinfo = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
					$main->set('catname', $catinfo['SNAME'.$pre]);
					$main->set('iorder', $catinfo['IORDER']);
					if($catinfo['STATUS'] == 'Active') $sl1 = "selected";
						elseif($catinfo['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($catinfo['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
						if($catinfo['HT'] == 1) $ht1 = "selected";
						elseif($catinfo['HT'] == 2) $ht2 = "selected";						
						$main->set('ht1', $ht1);
						$main->set('ht2', $ht2);

					
					$main->set('short_content', $catinfo['SCONTENTSHORT'.$pre]);
					$content_decode = base64_decode($catinfo['SCONTENTS'.$pre]);
					$main->set('content', $content_decode);
					if($catinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $catinfo['PICTURE'];
					$main->set('thumbnail', $thumbnail);
					$main->set('mess', "");
					if($catinfo['MN']) $tbchecked = "checked";
					else $tbchecked = "";
					$main->set('tbchecked', $tbchecked);
					$main->set('button_action', $lang['luu']);
					$main->set('title_action', $lang['hieuchinhchuyenmuc']);
					$main->set('ac', "e");
					$main->set('idtype', $idtype);

			echo $main->fetch("addcommontype");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idtype) {
			 $main = & new Template('viewcommontype');
					$catinfo = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
					$main->set('catname', $catinfo['SNAME'.$pre]);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('status', $catinfo['STATUS']);
					
					if($catinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $catinfo['PICTURE'];
					$main->set('thumbnail', $thumbnail);

			echo $main->fetch("viewcommontype");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idtype) {

		// Begin input data
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
				
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('catname', $catname);
					$main->set('iorder', $iorder);

					$main->set('short_content', $short_content);
					$main->set('content', $content);
					$main->set('mess', $mess1);
					if($mn) $tbchecked = "checked";
					else $tbchecked = "";
					$main->set('tbchecked', $tbchecked);
					if($ht == 1) $ht1 = "selected";
					elseif($ht == 2) $ht2 = "selected";
					$main->set('ht1', $ht1);
					$main->set('ht2', $ht2);
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m chuy&ecirc;n m&#7909;c");
					$main->set('ac', "a");
					$main->set('idtype', "");

					echo $main->fetch("addcommontype");
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
					else {
						  echo $lang['err_upload3'];
						  exit;
					}

			}
		
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "SNAME".$pre, 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),										
										3 => array(1 => "STATUS", 2 => $status),
										4 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										5 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),
										6 => array(1 => "PICTURE", 2 => $filesucess),
										7 => array(1 => "MN", 2 => $mn),
										8 => array(1 => "HT", 2 => $ht)
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"common_type");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idtype) {
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
				
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('catname', $catname);
					$main->set('iorder', $iorder);

					$main->set('short_content', $short_content);
					$main->set('content', $content);
					$main->set('mess', $mess1);
					if($mn) $tbchecked = "checked";
					else $tbchecked = "";
					$main->set('tbchecked', $tbchecked);
					if($ht == 1) $ht1 = "selected";
					elseif($ht == 2) $ht2 = "selected";
				
					$main->set('ht1', $ht1);
					$main->set('ht2', $ht2);
					

					$catinfo = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
					if($catinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $catinfo['PICTURE'];
					$main->set('thumbnail', $thumbnail);

					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh chuy&ecirc;n m&#7909;c");
					$main->set('ac', "e");
					$main->set('idtype', $idtype);

					echo $main->fetch("addcommontype");
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
					else {
						  echo $lang['err_upload3'];
						  exit;
					}

			}
			
			// check exist file images
					$catinfo = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
					$old_thumbnail = $catinfo['PICTURE'];
					if($filesucess == ""){
					$filesucess = $old_thumbnail;
					$file1_check = false;
					}
					else $file1_check = true;

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "SNAME".$pre, 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),										
										3 => array(1 => "STATUS", 2 => $status),
										4 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										5 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),
										6 => array(1 => "PICTURE", 2 => $filesucess),
										7 => array(1 => "MN", 2 => $mn),
										8 => array(1 => "HT", 2 => $ht)
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_TYPE", 2 => $idtype));
					$common->UpdateDB($ArrayData,"common_type",$update_condit);

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
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
