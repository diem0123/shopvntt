<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	// get sub mennu
	$submenu = & new Template('submenu_company'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_cus_payment_list'); 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(6), "V")){
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

		$page = $itech->input['page'];
		$idc = $itech->input['idc'];
		$cus_info = $common->getInfo("consultant","ID_CONSULTANT = '".$idc."'");
		$rolecus_info = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$idc."'");
		$idrole = $rolecus_info['ROLE_ID'];
		
		$pagetitle = $itech->vars['site_name'];
		$main->set("title", $pagetitle);
		// set header for operation
		if($roleinfo['ROLE_TYPE'] == "Operation"){
		$_SESSION['tab'] = 2;// menu Company selected
		$header = $common->getHeader('adm_header');
		$submenu2 = & new Template('submenu2_customer'); 
		for($i=3;$i<=5;$i++){
				if($idrole == $i) {
				$submenu2->set('icon_selected'.$i,'icon_arr2');
				$submenu2->set('cl'.$i,'title_do');
				}
				else {
				$submenu2->set('icon_selected'.$i,'icon_arr1');
				$submenu2->set('cl'.$i,'title_xam');
				}
			}
		$main->set('submenu2',$submenu2);			
		}
		else{
		$_SESSION['tab'] = "";
		$header = $common->getHeader('header');			
		$main->set('submenu2','');
		}
			

		// setup for paging						
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('CODE','ID_CONSULTANT','TOTAL_PAY', 'IP', 'ID_PROPAY', 'CONTENT_MAIL', 'CREATED_DATE', 'APPROVE', 'UPDATED_DATE','TOTAL_PAY_DISC','TOTAL_PAY_FINAL','FEE_TRANSFER');			
			$fet_box = & new Template('adm_cus_paymentlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = '".$idc."'";
			$sql="SELECT * FROM payments  WHERE ".$condit." ORDER BY UPDATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
				$fet_box->set( "paymentname", $rs['CODE']);
					$fet_box->set( "ID_PAYMENT", $rs['ID_PAYMENT']);
					
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
					
					$fet_box->set( "CREATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = ".$rs['ID_CONSULTANT']);
					$fet_box->set("FULL_NAME", $consultantinfo['FULL_NAME']);
					$fet_box->set("EMAIL", $consultantinfo['EMAIL']);
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_cus_paymentlist_rs");
	
				}
							
				if($datasub != "")
				$main->set('paymentlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('paymentlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("payments",$condit,"ID_PAYMENT");
					
					$link_page = "index.php?act=adm_payments";
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set("EMAIL", $cus_info['EMAIL']);
			$main->set("fullname", $cus_info['FULL_NAME']);
			$main->set('idc', $idc);
	
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
