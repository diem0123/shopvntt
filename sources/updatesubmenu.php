<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_menu', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	
	// get language
	$pre = $_SESSION['pre'];
	
     //main
     $main = & new Template('addsubmenu'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$cat = $itech->input['cat'];
		$idtype = $itech->input['idtype'];
		
		$nametype = $itech->input['nametype'];
		$iorder = $itech->input['iorder'];
		$link = $itech->input['link'];
		$idmuc = $itech->input['idmuc'];
		$nametb = $itech->input['nametb'];
		
		$pathimg = $itech->vars['pathimg'];
		
		$status = $itech->input['status'];
		$short_content = $itech->input['short_content'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		$ht = $itech->input['ht'];// cach hien thi thong tin
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		
		
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);
	
	$fet_box = & new Template('adm_list_setmenusub_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$condit = " STATUS = 'Active' AND ID_TYPE <> 5 ";// = '".$cat."'";
			$sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY ID_TYPE ";    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{		
				if($rs['SNAME'.$pre]!=''){
                    $fet_box->set( "name", $rs['SNAME'.$pre]);
				}else $fet_box->set("name",$rs['SNAME']);
					$fet_box->set( "idtype", $rs['ID_TYPE']);
					$fet_box->set( "idcat", $rs['ID_CAT']);
					

					// Get info datasub
				$datasub .= $fet_box->fetch("adm_list_setmenusub_rs");
	
			}
			

		if($ac=="a" && $submit == "" && !$idtype) {
					$list_category = $print_2->GetDropDown($cat, "menu1","" ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
					$main->set('list_category', $list_category);
					$main->set('nametype', "");
					$main->set('adm_list_setmenusub_rs', $datasub);
					$main->set('iorder', "");
					$main->set('link', "");
					$main->set('idmuc', "");
					$main->set('nametb', "");
					
					$main->set('sl1', "");
					$main->set('sl2', "selected");
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

			echo $main->fetch("addsubmenu");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idtype) {
					$typeinfo = $common->getInfo("menu2","ID_TYPE = '".$idtype."'");
					$list_category = $print_2->GetDropDown($typeinfo['ID_CATEGORY'], "menu1","" ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");					
					$main->set('list_category', $list_category);
					$main->set('nametype', $typeinfo['NAME_TYPE'.$pre]);
					$main->set('adm_list_setmenusub_rs', $datasub);
					$main->set('iorder', $typeinfo['IORDER']);
					$main->set('link', $typeinfo['LINK']);
					$main->set('idmuc', $typeinfo['IDMUC']);
					$main->set('nametb', $typeinfo['NAMETB']);
					
					if($typeinfo['STATUS'] == 'Active') $sl1 = "selected";
						elseif($typeinfo['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($typeinfo['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
						
						if($typeinfo['HT'] == 1) $ht1 = "selected";
						elseif($typeinfo['HT'] == 2) $ht2 = "selected";						
						$main->set('ht1', $ht1);
						$main->set('ht2', $ht2);

					
					$main->set('short_content', $typeinfo['SCONTENTSHORT'.$pre]);
					$content_decode = base64_decode($typeinfo['SCONTENTS'.$pre]);
					$main->set('content', $content_decode);
					if($typeinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $typeinfo['PICTURE'];
					$main->set('thumbnail', $thumbnail);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh chuy&ecirc;n m&#7909;c");
					$main->set('ac', "e");
					$main->set('idtype', $idtype);

			echo $main->fetch("addsubmenu");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idtype) {
			 $main = & new Template('viewsubcategory');
					$typeinfo = $common->getInfo("menu2","ID_TYPE = '".$idtype."'");					
					$catinfo = $common->getInfo("menu1","ID_CATEGORY = '".$typeinfo['ID_CATEGORY']."'");
					$main->set('namecat', $catinfo['NAME_CATEGORY'.$pre]);
					$main->set('nametype', $typeinfo['NAME_TYPE'.$pre]);					
					$main->set('iorder', $typeinfo['IORDER']);
					$main->set('link', $typeinfo['LINK']);
					$main->set('status', $typeinfo['STATUS']);
					if($typeinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $typeinfo['PICTURE'];
					$main->set('thumbnail', $thumbnail);

			echo $main->fetch("viewsubcategory");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idtype) {
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
		
		if($mess1 != "" ){
			$list_category = $print_2->GetDropDown($cat, "menu1","" ,"ID_CATEGORY", "NAME_CATEGORY", "IORDER");
			$main->set('list_category', $list_category);
			$main->set('nametype', $nametype);
			$main->set('iorder', $iorder);
			$main->set('link', $link);
			$main->set('idmuc', $idmuc);
			$main->set('nametb', $nametb);
			
			if($status == 'Active') $sl1 = "selected";
			elseif($status == 'Inactive') $sl2 = "selected";
			elseif($status == 'Deleted') $sl3 = "selected";
			$main->set('sl1', $sl1);
			$main->set('sl2', $sl2);
			$main->set('sl3', $sl3);
			$main->set('mess', $mess1);
			
			$main->set('short_content', $short_content);
			$main->set('content', $content);					
			if($ht == 1) $ht1 = "selected";
			elseif($ht == 2) $ht2 = "selected";
			$main->set('ht1', $ht1);
			$main->set('ht2', $ht2);
					
			$main->set('button_action', "Th&ecirc;m");
			$main->set('title_action', "Th&ecirc;m chuy&ecirc;n m&#7909;c");
			$main->set('ac', "a");
			$main->set('idtype', "");

			echo $main->fetch("addsubmenu");
			exit;
		
		}
		
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
							$width = $itech->vars['wthumb_news'];
							$height = $itech->vars['hthumb_news'];
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

		// Begin input data
		// add sub Category
		if($idmuc=='')$idmuc=1000;
		if($nametb =='')$nametb ='categories';
				try {
					$ArrayData = array( 1 => array(1 => "NAME_TYPE".$pre, 2 => $nametype),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "LINK", 2 => $link),
										4 => array(1 => "ID_CATEGORY", 2 => $cat),
										5 => array(1 => "PICTURE", 2 => $filesucess),
										6 => array(1 => "STATUS", 2 => $status),
										7 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										8 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),																				
										9 => array(1 => "HT", 2 => $ht),
										10 => array(1 => "IDMUC", 2 => $idmuc),
										11 => array(1 => "NAMETB", 2 => $nametb)
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"menu2");					

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
		
		if($mess1 != "" ){
			$list_category = $print_2->GetDropDown($cat, "menu1","" ,"ID_CATEGORY", "NAME_CATEGORY", "IORDER");
			$main->set('list_category', $list_category);
			$main->set('nametype', $nametype);
			$main->set('iorder', $iorder);
			$main->set('link', $link);
			$main->set('idmuc', $idmuc);
			$main->set('nametb', $nametb);
			if($status == 'Active') $sl1 = "selected";
			elseif($status == 'Inactive') $sl2 = "selected";
			elseif($status == 'Deleted') $sl3 = "selected";
			$main->set('sl1', $sl1);
			$main->set('sl2', $sl2);
			$main->set('sl3', $sl3);
			$main->set('mess', $mess1);
			
			$main->set('short_content', $short_content);
			$main->set('content', $content);
			
			if($ht == 1) $ht1 = "selected";
			elseif($ht == 2) $ht2 = "selected";
		
			$main->set('ht1', $ht1);
			$main->set('ht2', $ht2);
									
			$typeinfo = $common->getInfo("menu2","ID_TYPE = '".$idtype."'");
			if($typeinfo['PICTURE'] == "") $thumbnail = "noimages.jpg";
			else $thumbnail = $typeinfo['PICTURE'];
			$main->set('thumbnail', $thumbnail);
			
			$main->set('button_action', "L&#432;u");
			$main->set('title_action', "Hi&#7879;u ch&#7881;nh chuy&ecirc;n m&#7909;c");
			$main->set('ac', "e");
			$main->set('idtype', $idtype);

			echo $main->fetch("addsubmenu");
			exit;
		
		}
		
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
							$width = $itech->vars['wthumb_news'];
							$height = $itech->vars['hthumb_news'];
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
					$cm_detail = $common->getInfo("menu2","ID_TYPE ='".$idtype."'");
					$old_thumbnail = $cm_detail['PICTURE'];
					if($filesucess == ""){
					$filesucess = $old_thumbnail;
					$file1_check = false;
					}
					else $file1_check = true;
		// Begin update data
		if($idmuc=='')$idmuc=1000;
		if($nametb =='')$nametb ='categories';
				try {
					$ArrayData = array( 1 => array(1 => "NAME_TYPE".$pre, 2 => $nametype),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "LINK", 2 => $link),
										4 => array(1 => "ID_CATEGORY", 2 => $cat),
										5 => array(1 => "PICTURE", 2 => $filesucess),
										6 => array(1 => "STATUS", 2 => $status),
										7 => array(1 => "SCONTENTSHORT".$pre, 2 => $short_content),
										8 => array(1 => "SCONTENTS".$pre, 2 => $contentencode),										
										9 => array(1 => "HT", 2 => $ht),
										10 => array(1 => "IDMUC", 2 => $idmuc),
										11 => array(1 => "NAMETB", 2 => $nametb)
									  );
					//print_r($ArrayData);exit;	  					
					$update_condit = array( 1 => array(1 => "ID_TYPE", 2 => $idtype));
					$common->UpdateDB($ArrayData,"menu2",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
