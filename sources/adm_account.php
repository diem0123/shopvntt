<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	// get sub mennu
	$submenu = & new Template('submenu_account'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_account_list'); 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_account', "V")){
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
		// set header for operation
		if($roleinfo['ROLE_TYPE'] == "Operation"){
		$_SESSION['tab'] = 3;// menu Account selected
		$header = $common->getHeader('adm_header');
		//$submenu2 = & new Template('submenu2_account'); 
		//$submenu2->set('sub2','');
		$main->set('submenu2',"");			
		}
		else{
		$_SESSION['tab'] = "";
		$header = $common->getHeader('header');			
		$main->set('submenu2','');
		}
			

		// setup for paging			
			$page = $itech->input['page'];						
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('USER_NAME','STATUS');			
			$fet_box = & new Template('adm_accountlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			
		/*
			if($role['ROLE_ID'] == 1)
			$condit = " AND c.ID_CONSULTANT = chr.ID_CONSULTANT AND chr.ROLE_ID = rlk.ROLE_ID AND c.ID_CONSULTANT IN (".$list_consultant_operation.") ";
			//if($role['ROLE_ID'] == 2)
			else
			$condit = " AND c.ID_CONSULTANT = chr.ID_CONSULTANT AND chr.ROLE_ID = rlk.ROLE_ID AND c.ID_CONSULTANT =  '".$_SESSION['ID_CONSULTANT']."'";
		*/
		
			$condit = " AND c.ID_CONSULTANT = chr.ID_CONSULTANT AND chr.ROLE_ID = rlk.ROLE_ID ";
			
			$sql="SELECT c.USER_NAME, c.FULL_NAME, c.ID_CONSULTANT, c.CREATED_DATE, c.UPDATED_DATE, c.STATUS, rlk.ROLE_ID, rlk.NAME FROM consultant c, consultant_has_role chr, role_lookup rlk WHERE c.STATUS <> 'Deleted' ".$condit." ORDER BY c.UPDATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								
                    $fet_box->set( "ROLE_NAME", $rs['NAME'.$pre]);
					$fet_box->set( "idr", $rs['ROLE_ID']);
					$fet_box->set( "consultantname", $rs['FULL_NAME']);
					$fet_box->set( "idc", $rs['ID_CONSULTANT']);
					// set neu co phan trang
					if ($page) $fet_box->set( "page", "&page=".$page);
					else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
					
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i.$pre]]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i.$pre]]);
						}
					}
					
					$fet_box->set( "CREATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					$fet_box->set( "UPDATED_DATE", $common->datetimevn($rs['UPDATED_DATE']));
					
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_accountlist_rs");
	
				}
								
				if($datasub != "")
				$main->set('accountlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('accountlist_rs', $datasub);
				}
				
				
				// Paging
					if($role['ROLE_ID'] == 1)
					$totalpro = $common->countRecord("consultant"," STATUS <> 'Deleted'","ID_CONSULTANT");
					else $totalpro = 1;
					
					$link_page = "index.php?act=adm_account";
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
	
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
