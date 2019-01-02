<?php

    
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
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
     $main = & new Template('reseller_list');
	
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
			$idrole = $itech->input['idrole'];			
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('TYPE_ACCOUNT','NAME','PERCENT','TIME','TOTAL_KYGUI','ID_MONEY_TRANS');			
			$fet_box = & new Template('resellerlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			if(!$idrole) $condit = " 1 ";
			else $condit = " TYPE_ACCOUNT = '".$idrole."'";
						
			$sql="SELECT * FROM discount_reseller_lookup  WHERE ".$condit." ORDER BY TYPE_ACCOUNT LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;
					if($stt%2 == 0) $cl = "FFFFCC";
					else $cl = "FFFFFF";
					//$fet_box->set( "namers", $rs['NAME']);
					$fet_box->set( "idrs", $rs['ID_RS_DISCOUNT']);					
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
										
					
					// get value money transfer
					$moneyinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$rs['ID_MONEY_TRANS']."'");
					$fet_box->set( "namemn", $moneyinfo['NAME']);
					// get type customer
					$cusinfo = $common->getInfo("role_lookup","ROLE_ID = '".$rs['TYPE_ACCOUNT']."'");
					$fet_box->set( "namecus", $cusinfo['NAME']);
					// Get info datasub
					$datasub .= $fet_box->fetch("resellerlist_rs");
	
				}
							
				if($datasub != "")
				$main->set('resellerlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('resellerlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("discount_reseller_lookup",$condit,"ID_RS_DISCOUNT");
					if(!$idrole)
					$link_page = "index.php?act=adm_reseller";
					else $link_page = "index.php?act=adm_reseller&idrole=".$idrole;
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
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
