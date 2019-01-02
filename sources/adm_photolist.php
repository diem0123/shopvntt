<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	// set header for operation		
	$_SESSION['tab'] = 4;// menu Common selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_common'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_photolist');
	//$submenu2 = & new Template('submenu2_account'); 
	//$submenu2->set('sub2','');
	$main->set('submenu2',"");				 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(9), "V")){
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
			//$idtype = $itech->input['idtype'];
			//$typecat = $itech->input['typecat'];
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('STATUS', 'DATE_POST', 'DATE_UPDATED', 'PICTURE');			
			$fet_box = & new Template('adm_photolist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
						
			$condit = " STATUS <> 'Deleted' ";
			$sql="SELECT * FROM photos  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								                   
					$fet_box->set( "photoname", $rs['STITLE'.$pre]);
					$fet_box->set( "idphoto", $rs['ID_PT']);
					
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
					
					$fet_box->set( "DATE_POST", $common->datetimevn($rs['DATE_POST']));
					$fet_box->set( "DATE_UPDATED", $common->datetimevn($rs['DATE_UPDATED']));
					$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = ".$rs['POST_BY']);
					$fet_box->set("FULL_NAME", $consultantinfo['FULL_NAME']);
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_photolist_rs");
	
				}
								
				if($datasub != "")
				$main->set('photolist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('photolist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("photos",$condit,"ID_PT");
										
					$link_page = "index.php?act=adm_photolist";					
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
