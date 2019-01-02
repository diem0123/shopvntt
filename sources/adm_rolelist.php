<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	// set header for operation		
	$_SESSION['tab'] = 3;// menu Account selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_account'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_rolelist');
	//$submenu2 = & new Template('submenu2_account'); 
	//$submenu2->set('sub2','');
	$main->set('submenu2',"");				 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(8), "V")){
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
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('ROLE_TYPE','LEVEL','CREATED_DATE','UPDATED_DATE');			
			$fet_box = & new Template('adm_rolelist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			
			$condit = 1;
			$sql="SELECT * FROM role_lookup  WHERE ".$condit." ORDER BY UPDATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								
                    $fet_box->set( "ROLE_NAME", $rs['NAME'.$pre]);
					$fet_box->set( "idr", $rs['ROLE_ID']);
					if($rs['ROLE_TYPE'] == 'Consultant' ) $namerl = 'Nhân viên';
					elseif($rs['ROLE_TYPE'] == 'Operation' ) $namerl = 'Quản lý';
					$fet_box->set( "namerl", $namerl);
					
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
					$fet_box->set( "UPDATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_rolelist_rs");
	
				}
								
				if($datasub != "")
				$main->set('rolelist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('rolelist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("role_lookup","1","ROLE_ID");
					
					$link_page = "index.php?act=adm_rolelist";
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
