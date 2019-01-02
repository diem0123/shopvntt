<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	$cat = $itech->input['cat'];
	if($cat==1000 || $cat=="") $thumuc = "imagenews";
	else $thumuc = $cat;
	
	$f = $itech->input['f'];
	if($f == 1) { $_SESSION['selecf'] = 1; }
	elseif($f != "" && $f == 0) $_SESSION['selecf'] = 0;

	// set language
	//$pre = "";
	// set header for operation		
	$_SESSION['tab'] = 4;// menu setting selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_common'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_library');
	$submenu2 = & new Template('submenu2_library'); 
	$list_category = $print_2->GetDropDown($cat, "categories","STATUS = 'Active'" ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
	$submenu2->set('list_category', $list_category); 
	$main->set('submenu2',$submenu2);
					 
	if($_SESSION['selecf'] == 1) $nlink = "thu-vien-anh.html?f=1";
	else $nlink = "thu-vien-anh.html?f=0";
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $nlink, "V")){
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
			//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
			// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;
			}

		$pagetitle = $itech->vars['site_name'];
		$main->set("title", $pagetitle);

		// setup for paging			
			
			
			//echo "s=".$f."-".$_SESSION['selecf'];
			
			$ff = $f;// chong trung bien file open
			$page = $itech->input['page'];
			$idtype = $itech->input['idtype'];
			$typecat = $itech->input['typecat'];
			$idcat = $itech->input['idcat'];
			$idsubcat = $itech->input['idsubcat'];
			$pathimg = $itech->vars['pathimg'];
			$dir_upload = $pathimg."/".$thumuc."/";
			
			if ($page == 0 || $page == "")
				$page=1;

			//$num_record_on_page = $itech->vars['num_record_on_page_admin']; 
			$numrecordcolumn = 40; // 5dongx8cot
			$maxpage = 10; // group 10 link on paging list
			
			//$pageoffset =($page-1)* $num_record_on_page;
		
					
			$fet_box = & new Template('adm_library');
			$datasub = ""; // de lay du lieu noi cac dong
			
			if($idtype && !$idcat && !$typecat && !$idsubcat)
			$condit = "ID_TYPE = '".$idtype."' AND STATUS <> 'Deleted'";
			elseif($idtype && $typecat && !$idsubcat)
			$condit = "ID_TYPE = '".$idtype."' AND ID_CAT = '".$typecat."' AND STATUS <> 'Deleted'";
			elseif($idtype && $idcat && !$idsubcat)
			$condit = "ID_TYPE = '".$idtype."' AND ID_CAT = '".$idcat."' AND STATUS <> 'Deleted'";
			elseif($idtype && $idcat && $idsubcat)
			$condit = "ID_TYPE = '".$idtype."' AND ID_CAT = '".$idcat."' AND ID_CAT_SUB = '".$idsubcat."' AND STATUS <> 'Deleted'";
			else
			$condit = " STATUS <> 'Deleted'";
			
		$dir_img = $dir_upload."/";
		if(!is_dir($dir_img))
		mkdir($dir_img, 0777, true);	
	
		//$files_all = array();
		$list_temp = array();
		$list = array();
		$m=0;
		$diropen = opendir($dir_img);
/*		
		while ($f = readdir($diropen)) {
		$m++;
			//if (eregi("\.jpg|\.gif|\.png", $f)){
			if (preg_match("/\.jpg|\.gif|\.png/i", $f)){
			//echo $f."<br>";
				//array_push($files_all, $f);				
				$slide = strpos($f,"slide");				
				$small = strpos($f,"small");
				//echo "slide=".$slide."-sm=".$small."<br>";
				if($slide !== false || $small !== false) {					
					if($slide == 0 || $small == 0) $flag =1;
					else {
					$ftime = filemtime($dir_img.$f)."_".$m;
					$list_temp[$ftime] = $f;
					}
				}			
				else {
					$ftime = filemtime($dir_img.$f)."_".$m;
					$list_temp[$ftime] = $f;
				}
								
			}	
		}
	*/
		if(isset($_SESSION['selecf']) && $_SESSION['selecf'] == 1){
			while ($f = readdir($diropen)) {
			$m++;				
				//if (preg_match("/\.pdf|\.doc|\.docx|\.xls|\.xlsx/i", $f)){
				if (preg_match("/\.pdf|\.ppt|\.pptx|\.doc|\.docx|\.xls|\.xlsx|\.txt|\.mp3|\.wmv|\.mp4|\.avi|\.wav|\.wma|\.mpg|\.mpeg|\.swf|\.flv/i", $f)){
				//echo $f."<br>";
					//array_push($files_all, $f);				
					$slide = strpos($f,"slide");				
					$small = strpos($f,"small");
					//echo "slide=".$slide."-sm=".$small."<br>";
					if($slide !== false || $small !== false) {					
						if($slide == 0 || $small == 0) $flag =1;
						else {
						$ftime = filemtime($dir_img.$f)."_".$m;
						$list_temp[$ftime] = $f;
						}
					}			
					else {
						$ftime = filemtime($dir_img.$f)."_".$m;
						$list_temp[$ftime] = $f;
					}
									
				}	
			}
			$templ_rs = "list_file_library";
		}
		else{
			$_SESSION['selecf'] = 0;
			while ($f = readdir($diropen)) {
			$m++;
				//if (eregi("\.jpg|\.gif|\.png", $f)){				
				//if (preg_match("/\.jpg|\.gif|\.png/i", $f)){
				if (preg_match("/\.jpg|\.jpeg|\.bmp|\.gif|\.png/i", $f)){
				//echo $f."<br>";
					//array_push($files_all, $f);				
					$slide = strpos($f,"slide");				
					$small = strpos($f,"small");
					//echo "slide=".$slide."-sm=".$small."<br>";
					if($slide !== false || $small !== false) {					
						if($slide == 0 || $small == 0) $flag =1;
						else {
						$ftime = filemtime($dir_img.$f)."_".$m;
						$list_temp[$ftime] = $f;
						}
					}			
					else {
						$ftime = filemtime($dir_img.$f)."_".$m;
						$list_temp[$ftime] = $f;
					}
									
				}	
			}
			
			$templ_rs = "list_image_library";
		}
		
		closedir($diropen);
		
	$list_images = "";	
	$totalfile = count($list_temp);
	// order desc
	$j = $totalfile - 1;
	if(!empty($list_temp)){
		ksort($list_temp);
		foreach($list_temp as $key => $value){
			if($j>=0)
			$list[$j] = $value;
			$j--;			
		}
		
		$pageoffsetcolumn =($page-1)* $numrecordcolumn;		
		$end = $pageoffsetcolumn + $numrecordcolumn;
		   if ($end <= $totalfile) {
				$numcount = $numrecordcolumn;
		   }
		   else {				
				$numcount = $totalfile - ($end - $numrecordcolumn);
				$end = $totalfile;
		   }
		   
		$list_images = $print_2->list_row_column_notable( $list, $pageoffsetcolumn, $end, $numcount, $page, $templ_rs, 8, "", 1, $thumuc);

	}
			
		$link_page = "thu-vien-anh.html?cat=".$cat."&f=".$ff;
		$class_link = "linkpage";
		if ($numrecordcolumn < $totalfile)			
			$main->set('paging',$print_2->pagingphp( $totalfile, $numrecordcolumn, $maxpage, $link_page, $page, $class_link ));
		else
			$main->set('paging' , '');
									
			$main->set('page', $page);
		
			$main->set('list_images', $list_images);
			$main->set('thumuc', $thumuc);
			$main->set('f', $ff);
	
     //footer
     $footer = & new Template('adm_footer');
	 $footer->set('statistics1', '');

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
	 $tpl->set('submenu', $submenu);
     $tpl->set('main', $main);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('adm_home');
	 
?>
