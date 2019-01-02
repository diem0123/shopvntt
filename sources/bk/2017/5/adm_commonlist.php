<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	// set language
	//$pre = "";
	// set header for operation		
	$_SESSION['tab'] = 4;// menu setting selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_common'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_common_list');
	//$submenu2 = & new Template('submenu2_category'); 
	
	$main->set('submenu2',"");				 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_commonlist', "V")){
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
			$idtype = $itech->input['idtype'];
			$typecat = $itech->input['typecat'];
			$idcat = $itech->input['idcat'];
			$idsubcat = $itech->input['idsubcat'];
			
			$keyword = $itech->input['keyword'];
			
			$keyword_criteria = "";		
		
			// search keyword
			if ($keyword != "" )
			{
				//$t1 = 0 < $local ? " AND " : " AND ";
				$t1 = " AND ";
				$word = explode(" ", $keyword );
				$tempkey = " ( ID_COMMON LIKE '%".$word[0]."%' OR STITLE LIKE '%".$word[0]."%' OR SCONTENTSHORT LIKE '%".$word[0]."%'  )";    
				for ($i=1;$i<count($word);$i++)
				{
					$keyword_criteria .= " AND ( ID_COMMON LIKE '%".$word[$i]."%' OR STITLE LIKE '%".$word[$i]."%' OR SCONTENTSHORT LIKE '%".$word[$i]."%' )";
				}
				
				$keyword_criteria = $t1.$tempkey.$keyword_criteria;
				
			}
			
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('STATUS', 'DATE_POST', 'DATE_UPDATED', 'PICTURE');			
			$fet_box = & new Template('adm_commonlist_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			
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
			
			$condit .= $keyword_criteria;
			
			//echo $condit;
			
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								                   
					$fet_box->set( "commonname", $rs['STITLE'.$pre]);
					$fet_box->set( "idcommon", $rs['ID_COMMON']);
					$fet_box->set( "idtype", $rs['ID_TYPE']);
					$fet_box->set( "STATUS", $rs['STATUS']);
					// set neu co phan trang
					if ($page) $fet_box->set( "page", "&page=".$page);
					else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
					
					// set for field name other chid
					if(count($field_name_other) > 0){
						if($pre != ""){	
								if(isset($rs[$field_name_other[$i].$pre])){							 
								 $fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
								 }
								 elseif(isset($rs[$field_name_other[$i]])){							 
								 $fet_box->set($field_name_other[$i], $rs[$field_name_other[$i]]);
								 }
								 else
								 $fet_box->set($field_name_other[$i], "");
							}
							else{							
								if(isset($rs[$field_name_other[$i]])){							 
								 $fet_box->set($field_name_other[$i], $rs[$field_name_other[$i]]);
								 }
								elseif(!isset($rs[$field_name_other[$i]])){							 
								 $fet_box->set($field_name_other[$i], "");
								 }
							}
					}
					
					$fet_box->set( "DATE_POST", $common->datetimevn($rs['DATE_POST']));
					$fet_box->set( "DATE_UPDATED", $common->datetimevn($rs['DATE_UPDATED']));
					$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = ".$rs['POST_BY']);
					$fet_box->set("FULL_NAME", $consultantinfo['FULL_NAME']);
					
					// Get info datasub
					$datasub .= $fet_box->fetch("adm_commonlist_rs");
	
				}
								
				if($datasub != "")
				$main->set('commonlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('commonlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("common",$condit,"ID_COMMON");
					
					if($typecat){
						if ($keyword != "" )
						$link_page = "index.php?act=adm_commonlist&idtype=".$idtype."&typecat=".$typecat."&keyword=".$keyword;
						else
						$link_page = "index.php?act=adm_commonlist&idtype=".$idtype."&typecat=".$typecat;
					
					}
					else{
						if ($keyword != "" )
						$link_page = "index.php?act=adm_commonlist&idtype=".$idtype."&idcat=".$idcat."&keyword=".$keyword;
						else
						$link_page = "index.php?act=adm_commonlist&idtype=".$idtype."&idcat=".$idcat;
					
					}

					$class_link = "linkpage";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set('query_str', "&idtype=".$idtype);
			if(!$typecat) $typecat = $idcat;
			$list_type_category = $print_2->GetDropDown($typecat, "common_cat", "ID_TYPE = '".$idtype."' AND STATUS = 'Active' "  , "ID_CAT", "SNAME".$pre, "IORDER");
			$main->set('list_type_category', $list_type_category);
			$main->set('idtype', $idtype);
			$typeinfo = $common->getInfo("common_type","ID_TYPE ='".$idtype."'");
			$main->set('typename', $typeinfo['SNAME'.$pre]);
			if(!$idcat) $idcat = $typecat;
			$catinfo = $common->getInfo("common_cat","ID_CAT ='".$idcat."'");
			$main->set('catname', $catinfo['SNAME'.$pre]);
			
			$list_commontype = $print_2->GetDropDown($idtype, "common_type","STATUS = 'Active'" ,"ID_TYPE", "SNAME".$pre, "IORDER");	
			$list_type_category = $print_2->GetDropDown($typecat, "common_cat", "ID_TYPE = '".$idtype."' AND STATUS = 'Active' "  , "ID_CAT", "SNAME".$pre, "IORDER");			
			$list_sub_category = $print_2->GetDropDown($idsubcat, "common_cat_sub", "ID_CAT = '".$typecat."'"  , "ID_CAT_SUB", "SNAME".$pre, "IORDER");
			$main->set('list_commontype', $list_commontype);
			$main->set('list_type_category', $list_type_category);
			$main->set('list_sub_category', $list_sub_category);
			if(!$idsubcat)
			$main->set('show3', "style='display:none'");
			else
			$main->set('show3', "style='display:table-row'");
	
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
