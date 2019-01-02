<?php

	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
     $main = & new Template('popup_library');
	
		// setup for paging			
			$f = $itech->input['f'];
			$page = $itech->input['page'];
			$idtype = $itech->input['idtype'];
			$typecat = $itech->input['typecat'];
			$idcat = $itech->input['idcat'];
			$idsubcat = $itech->input['idsubcat'];
			$pathimg = $itech->vars['pathimg'];
			$path_http = $itech->vars['roothttp'];
			$dir_upload = $pathimg."/imagenews/";
			
			if ($page == 0 || $page == "")
				$page=1;

			//$num_record_on_page = $itech->vars['num_record_on_page_admin']; 
			$numrecordcolumn = 16; // 5dongx8cot
			$maxpage = 5; // group 10 link on paging list
			
			//$pageoffset =($page-1)* $num_record_on_page;
								
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
		if($f==1){
			while ($f = readdir($diropen)) {
			$m++;				
				if (preg_match("/\.pdf|\.doc|\.docx|\.xls|\.xlsx/i", $f)){				
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
			$templ_rs = "popup_list_file_library";
		}
		else{
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
			
			$templ_rs = "popup_list_image_library";
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
		   
		$list_images = $print_2->list_row_column_notable( $list, $pageoffsetcolumn, $end, $numcount, $page, $templ_rs, 4, $path_http, 1);

	}
			
		$jsname = "getlibrary";
		$class_link = "do";
		if ($numrecordcolumn < $totalfile)			
			$main->set('paging',$print_2->pagingjs( $totalfile, $numrecordcolumn, $maxpage, $page, $jsname, $class_link));
		else
			$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set('pagejs', $page);
			$main->set('thumuc', 'imagenews');
		
			$main->set('list_images', $list_images);

     echo $main->fetch('popup_library');
?>
