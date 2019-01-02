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
     $main = & new Template('adm_chat_list'); 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(7), "V")){
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
		
			$field_name_other = array('USER_NAME','TYPECHAT','IMG_NAME','MESSENGER_ON','NICK_NAME','IORDER','NAME');			
			$fet_box = & new Template('adm_chatlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			
			if($role['ROLE_ID'] == 1)
			$condit = " AND c.ID_CONSULTANT = chr.ID_CONSULTANT AND c.ID_CONSULTANT IN (".$list_consultant_operation.") ";
			elseif($role['ROLE_ID'] == 2)
			$condit = " AND c.ID_CONSULTANT = chr.ID_CONSULTANT AND c.ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'";
			$sql="SELECT * FROM consultant c, consultant_has_chat chr WHERE c.STATUS <> 'Deleted' ".$condit." ORDER BY chr.IORDER LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								                   
					$fet_box->set( "consultantname", $rs['FULL_NAME']);
					$fet_box->set( "idc", $rs['ID_CONSULTANT']);
					$fet_box->set( "idnick", $rs['CONSULTANT_CHAT_ID']);
					$fet_box->set( "NAME", $rs['NAME'.$pre]);
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
					$fet_box->set( "UPDATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					
					if($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'yahoo-messenger-icon2.png';
					elseif($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'yahoo-messenger-icon2-gray.gif';
					elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'skype2.png';
					elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'skype1.png';
					$fet_box->set( "iconchat", $iconchat);
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_chatlist_rs");
	
				}
								
				if($datasub != "")
				$main->set('chatlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('chatlist_rs', $datasub);
				}
				
				
				// Paging
					//$totalpro = $common->countRecord("consultant"," STATUS <> 'Deleted'","ID_CONSULTANT");
					$totalpro = $common->countRecord("consultant c, consultant_has_role chr, role_lookup rlk"," c.STATUS <> 'Deleted' ".$condit,"ID_CONSULTANT");					
					
					$link_page = "index.php?act=adm_chat";
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
