<?php

    
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	$page = $itech->input['page'];
	$idps = $itech->input['idps'];
	$mn = $itech->input['mn'];
			
	// set header for operation		
	$_SESSION['tab'] = 4;// menu common selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_common'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('banner_list');
	$submenu2 = & new Template('submenu2_banner'); 
	for($i=1;$i<=9;$i++){
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
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_banner&idps=1', "V")){
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
			
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('IORDER','NAME_IMG','TITLE_IMG','ID_PS','LINK');			
			$fet_box = & new Template('bannerlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_PS = '".$idps."'";
			$sql="SELECT * FROM banner  WHERE ".$condit." ORDER BY IORDER LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;					
					$fet_box->set( "idbn", $rs['ID_BN']);
					$fet_box->set( "stt", $stt);
					$fet_box->set( "LINK", $rs['LINK']);
					$imgname = '<img src="images/bin/banner/'.$rs['NAME_IMG'].'" width="200">';
					$fet_box->set( "imgname", $imgname);
					
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
					
					// get position
					$info_ps = $common->getInfo("banner_position","ID_PS = '".$rs['ID_PS']."'");
					$fet_box->set("psname", $info_ps['NAME']);
					// get cat
					$info_cat = $common->getInfo("categories","ID_CATEGORY = '".$rs['ID_PS_SUB']."'");
					$fet_box->set("psname_sub", $info_cat['NAME_CATEGORY']);
					
					// Get info datasub
					$datasub .= $fet_box->fetch("bannerlist_rs");
	
				}
							
				if($datasub != "")
				$main->set('bannerlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('bannerlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("banner",$condit,"ID_BN");
					
					$link_page = "index.php?act=adm_banner&idps=".$idps;
					$class_link = "linkpage";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set('idps', $idps);			
	
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
