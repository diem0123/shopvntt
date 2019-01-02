<?php

    
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_menu', "V")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	
	// get language
	$pre = $_SESSION['pre'];
	
	$mn = $itech->input['mn'];
	if(!$mn) $mn =1;
	$_SESSION['mn'] = $mn;
	
	 $header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_setting'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('menu_list');
	$submenu2 = & new Template('submenu2_menuc1'); 
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
			$idtype = $itech->input['idtype'];
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('IORDER','LINK','STATUS');			
			$fet_box = & new Template('menulist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " 1 ";
			$sql="SELECT * FROM menu1  WHERE ".$condit." ORDER BY IORDER LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;
					$fet_box->set( "CATNAME", $rs['NAME_CATEGORY'.$pre]);
					$fet_box->set( "idcat", $rs['ID_CATEGORY']);
					$fet_box->set( "stt", $stt);
					
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
							elseif(isset($rs[$field_name_other[$i]]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i]]);
						}
					}									
					
					
					// Get info datasub
					$datasub .= $fet_box->fetch("menulist_rs");
	
				}
							
				if($datasub != "")
				$main->set('menulist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('menulist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("menu1",$condit,"ID_CATEGORY");
					
					$link_page = "index.php?act=adm_menu";
					$class_link = "linkpage";
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
