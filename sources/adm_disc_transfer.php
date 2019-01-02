<?php

    
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	// import danh sach cuoc phi va chiet khau
	$mess = "";
	 if (isset($itech->input['submit']))
	 {			
		
		$file_import = $_FILES['fileupload']['name'];
		if($$file_import!="" && !$print_2->check_extent_file($file_import,"csv|CSV|txt|TXT")){						
			$mess .= "Invalid file format.<br>";
			$flag = "false";
		}
		elseif($file_import!="" && $print_2->check_extent_file($file_import,"csv|CSV|txt|TXT"))
			{
			$file_input_tmp = $_FILES['fileupload']['tmp_name'];

			// Kiem tra comma hay tab
			$type_input = $itech->input['R1'];
			
			if($type_input=="comma") $dau = ",";
			else $dau = "\t";	
            $handle = fopen($file_input_tmp, "r");
			$line = 0;		
			 while (($data = fgetcsv($handle, 1000, $dau)) !== FALSE)
			 {
				//$sql = "INSERT into user(name,email,phone) values('$data[0]','$data[1]','$data[2]')";
				//mysql_query($sql) or die(mysql_error());
				if($line <> 0){
				
				// kiem tra dong 1 de biet: Tinh, Huyen, tri gia don hang, kieu van chuyen, loai khach hang
				$province_info= $common->getInfo("province","NAME = '".$data[0]."'");
				$dist_info= $common->getInfo("district","NAME = '".$data[1]."'");
								
				//echo "data0=".$province_info['ID_PROVINCE']."-data1=".$dist_info['ID_DIST']."-data2=".$data[2]."-data3=".$data[3]."-data4=".$data[4]."-data5=".$data[5]."-data6=".$data[6]."<br>";
				//if($line == 10) { exit;}

				$ArrayData = array( 1 => array(1 => "TYPE_ACCOUNT", 2 => $data[4]),
									2 => array(1 => "ID_TRANSFER", 2 => $data[3]),
									3 => array(1 => "NAME", 2 => ""),
									4 => array(1 => "PERCENT", 2 => $data[6]),
									5 => array(1 => "ID_PROVINCE", 2 => $province_info['ID_PROVINCE']),
									6 => array(1 => "FEE", 2 => $data[5]),
									7 => array(1 => "ID_DIST", 2 => $dist_info['ID_DIST']),
									8 => array(1 => "ID_MONEY_TRANS", 2 => $data[2])									
								  );				
				$id_const = $common->InsertDB($ArrayData,"discount_transfer_lookup");

				}
				$line++;
			 }
    
			 fclose($handle);			 
    	 }// end true

	   else
		{
			$file_import ="";
			$mess .= "Please enter attach file.<br>";
			$flag = "false";
		}
	
    } // end submit


	
	// set language
	$pre = "";
	$mn = $itech->input['mn'];
	
	// set header for operation		
	$_SESSION['tab'] = 5;// menu setting selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_setting'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('disc_transfer_list');
	
	$submenu2 = & new Template('submenu2_setting');
			for($i=1;$i<=4;$i++){
				if($mn == $i) {
				$submenu2->set('icon_selected'.$i,'icon_arr2');
				$submenu2->set('cl'.$i,'title_do');
				}
				else {
				$submenu2->set('icon_selected'.$i,'icon_arr1');
				$submenu2->set('cl'.$i,'title_xam');
				}
			}	
	$main->set('submenu2',$submenu2);				 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(10), "V")){
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
			$page = $itech->input['page'];
			$idpr = $itech->input['idpr'];
			//$idtr = $itech->input['idtr'];
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('TYPE_ACCOUNT','ID_TRANSFER','NAME','PERCENT','ID_PROVINCE','FEE','ID_DIST','ID_MONEY_TRANS');			
			$fet_box = & new Template('disc_transferlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			if(!$idpr) $condit = " ID_PROVINCE <> 0 ";
			else $condit = " ID_PROVINCE = '".$idpr."'";
						
			$sql="SELECT * FROM discount_transfer_lookup  WHERE ".$condit." ORDER BY ID_PROVINCE LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;
					if($stt%2 == 0) $cl = "FFFFCC";
					else $cl = "FFFFFF";
					$fet_box->set( "namedis", $rs['NAME']);
					$fet_box->set( "iddis", $rs['ID_DISCOUNT']);					
					$fet_box->set( "stt", $stt);
					$fet_box->set( "cl", $cl);
					
					// set neu co phan trang
					if ($page) $fet_box->set( "page", "&page=".$page);
					else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
					
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}									
										
					// get province
					$prinfo = $common->getInfo("province","ID_PROVINCE = '".$rs['ID_PROVINCE']."'");
					if($prinfo == "") $fet_box->set( "namepr", "Ch&#432;a c&oacute; d&#7919; li&#7879;u");
					else $fet_box->set( "namepr", $prinfo['NAME']);
					// get dist
					$disinfo = $common->getInfo("district","ID_DIST = '".$rs['ID_DIST']."'");
					if($disinfo  == "") $fet_box->set( "namedis", "Ch&#432;a c&oacute; d&#7919; li&#7879;u");
					else $fet_box->set( "namedis", $disinfo['NAME']);
					// get type transfer
					$typeinfo = $common->getInfo("type_transfer","ID_TRANSFER = '".$rs['ID_TRANSFER']."'");
					$fet_box->set( "nametr", $typeinfo['NAME_TRANSFER']);
					// get value money transfer
					$moneyinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$rs['ID_MONEY_TRANS']."'");
					$fet_box->set( "namemn", $moneyinfo['NAME']);
					// get type customer
					$cusinfo = $common->getInfo("role_lookup","ROLE_ID = '".$rs['TYPE_ACCOUNT']."'");
					$fet_box->set( "namecus", $cusinfo['NAME']);
					// Get info datasub
					$datasub .= $fet_box->fetch("disc_transferlist_rs");
	
				}
							
				if($datasub != "")
				$main->set('disc_transferlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('disc_transferlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("discount_transfer_lookup",$condit,"ID_DISCOUNT");
					if(!$idpr)
					$link_page = "index.php?act=adm_disc_transfer";
					else $link_page = "index.php?act=adm_disc_transfer&idpr=".$idpr;
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set('mess',$mess);
			//$main->set('idpr', $idpr);
	
	
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
