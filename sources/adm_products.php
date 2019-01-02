<?php


if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
	$url_redidrect='index.php?act=adm_login';
	$common->redirect_url($url_redidrect);
}	

     //main
$main =  new Template('adm_products_list'); 
		/*
			if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "V")){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;		
			}			
		*/
		
			
		
		// setup for paging
			$page = $itech->input['page'];
			$idcom = $itech->input['idcom'];
			$vlid = $itech->input['vlid'];
			$cat = $itech->input['cat'];
			$typecat = $itech->input['typecat'];
		
		//Tạo session cho cat và typecat import vào database dựa trên phần mềm
			$_SESSION['cat']=$cat;
			$_SESSION['typecat']=$typecat;
			
		
			$brand = $itech->input['brand'];
			$subcat = $itech->input['subcat'];
			$_SESSION['idcategory'] = $cat;
			$_SESSION['idtypecat']  = $typecat;
			$_SESSION['subcat']     = $subcat;
			// danh rieng phan bon chuyen dung
			if($cat==700 && $typecat =="") $idsk = 5000;
			elseif($cat==700 && $typecat !="")$idsk = $typecat;
			if($idsk==5000)
				$listidp = $common->getListValue("list_tinhnang"," ID_MT >0 ","ID_PRODUCT");
			else
				$listidp = $common->getListValue("list_tinhnang","ID_MT = '".$idsk."'","ID_PRODUCT");
			//echo "listidp=".$listidp;
			if($listidp=="") $listidp =0;		 
			
			
			$condit = "";
			$condit1 = "";
			if($cat && !$typecat && !$subcat) {
				if($cat==700){
					$condit .= " AND ID_PRODUCT IN(".$listidp.") ";
					$condit1 .= " AND ID_PRODUCT IN(".$listidp.") ";
					
				}
				else{			
					$condit .= " AND ID_CATEGORY = '".$cat."'";
					$condit1 .= " AND ID_CATEGORY = '".$cat."' ";
					
				}
			}
			elseif($cat && $typecat && !$subcat) {
				if($cat==700){
					$condit .= " AND ID_PRODUCT IN(".$listidp.") ";
					$condit1 .= " AND ID_PRODUCT IN(".$listidp.") ";
					
				}
				else{			
					$condit .= " AND ID_CATEGORY = '".$cat."' AND ID_TYPE = '".$typecat."'";
					$condit1 .= " AND ID_CATEGORY = '".$cat."' AND ID_TYPE = '".$typecat."'";
					
				}
			}
			elseif($cat && $typecat && $subcat) {
				$condit .= " AND ID_CATEGORY = '".$cat."' AND ID_TYPE = '".$typecat."' AND ID_PRO_CAT = '".$subcat."'";
				$condit1 .= " AND ID_CATEGORY = '".$cat."' AND ID_TYPE = '".$typecat."' AND ID_PRO_CAT = '".$subcat."'";
				
			}
			else {
					if($cat==700){
					$condit .= " AND ID_PRODUCT IN(".$listidp.") ";
					$condit1 .= " AND ID_PRODUCT IN(".$listidp.") ";
					
					}
					else{			
						$condit .= " AND ID_CATEGORY = '".$cat."'";
						$condit1 .= " AND ID_CATEGORY = '".$cat."' ";
						
					}
				}
			
			if ($page == 0 || $page == "")
				$page=1;

			//$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$num_record_on_page = 5000;
			$maxpage = 100; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
			
			//check role type
			$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
			$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
			
			//$userinfo = $common->getInfo("ConsultantIncharge","ID_COM = '".$idcom."'");

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
			$_SESSION['tab'] = 2;// menu company selected
			 // get sub mennu
			$submenu =  new Template('submenu_company'); 
			$submenu->set('sub','');
			$header = $common->getHeader('adm_header');
			$submenu2 =  new Template('submenu2_products'); 
			$list_category = $print_2->GetDropDown($cat, "categories","STATUS = 'Active'" ,"ID_CATEGORY", "NAME_CATEGORY".$pre, "IORDER");
			$submenu2->set('list_category', $list_category);
			if($cat==700)
				$list_type_category = $print_2->GetDropDown($typecat, "material_lookup","STATUS = 'Active' " ,"ID_MT", "NAME".$pre, "IORDER");
			else
				$list_type_category = $print_2->GetDropDown($typecat, "categories_sub","STATUS = 'Active' AND ID_CATEGORY = '".$cat."'" ,"ID_TYPE", "NAME_TYPE".$pre, "IORDER");
			$submenu2->set('list_type_category', $list_type_category);
			$list_sub_category = $print_2->GetDropDown($subcat, "product_categories", "STATUS = 'Active' AND ID_TYPE = '".$typecat."'"  , "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");
			$submenu2->set('list_sub_category', $list_sub_category);
			
			$main->set('submenu2',$submenu2);			
		}
		else{
			$_SESSION['tab'] = "";
			$header = $common->getHeader('header');			
			$main->set('submenu2','');
		}

		$field_name_other = array('ID_PRO_CAT_COM','STATUS','DATE_POST','DATE_UPDATE', 'ID_PRO_CAT', 'CODE', 'IORDER','ID_BRAND');			
		$fet_box =  new Template('adm_products_list_rs');
			$datasub = ""; // de lay du lieu noi cac dong		
			$sql="SELECT * FROM products p WHERE p.STATUS <> 'Deleted' ".$condit." ORDER BY p.DATE_UPDATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
			//echo $sql;exit;
			while ($rs=$DB->fetch_row($query))
			{				
				if($rs['PRODUCT_NAME'.$pre]!='')
					$fet_box->set( "productname", $rs['PRODUCT_NAME'.$pre]);
				else
					$fet_box->set( "productname", $rs['PRODUCT_NAME']);
				$fet_box->set( "idcom", $rs['ID_COM']);
				$fet_box->set( "idp", $rs['ID_PRODUCT']);
					// set neu co phan trang
				if ($page) $fet_box->set( "page", "&page=".$page);
				else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					

					// set for field name other chid
				if(count($field_name_other) > 0){
					for($i=0;$i<count($field_name_other);$i++){
						if($pre != ""){	
							if(isset($rs[$field_name_other[$i].$pre])){							 
								$fet_box->set($field_name_other[$i].$p, $rs[$field_name_other[$i].$pre]);
							}
							elseif(isset($rs[$field_name_other[$i]])){							 
								$fet_box->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
							}
							else
								$fet_box->set($field_name_other[$i].$p, "");
						}
						else{							
							if(isset($rs[$field_name_other[$i]])){							 
								$fet_box->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
							}
							elseif(!isset($rs[$field_name_other[$i]])){							 
								$fet_box->set($field_name_other[$i].$p, "");
							}
						}
					}
				}
				$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = ".$rs['POST_BY']);
				$fet_box->set("FULL_NAME", $consultantinfo['FULL_NAME']);
				$brandinfo = $common->getInfo("brand","ID_BRAND = ".$rs['ID_BRAND']);
				$fet_box->set("NAME_BRAND", $brandinfo['NAME_BRAND']);

					// Get info datasub
				$datasub .= $fet_box->fetch("adm_products_list_rs");

			}

			if($datasub != "")
				$main->set('products_list_rs', $datasub);
			else{
				$fet_boxno =  new Template('adm_noproduct');
				$text = "B&#7841;n ch&#432;a c&oacute; s&#7843;n ph&#7849;m n&agrave;o!";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("adm_noproduct");
				$main->set('products_list_rs', $datasub);
			}


				// Paging
			$totalpro = $common->countRecord("products"," STATUS <> 'Deleted' ".$condit1,"ID_PRODUCT");

			$link_page = "index.php?act=adm_products&cat=".$cat."&typecat=".$typecat;
			$class_link = "active";
			if ($num_record_on_page < $totalpro){						
				$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
			}
			else
				$main->set('paging' , '');

			$main->set('page', $page);
		/*	
			$list_sub_category_ages = $print_2->GetDropDown($ages_sub, "ages_type", "ID_TYPE = '2' "  , "ID_AGES", "NAME_TYPE_AGE", "IORDER");
			$list_sub_category_skills = $print_2->GetDropDown($skills_sub, "skill_type", "ID_TYPE = '3' "  , "ID_SKILL", "NAME_TYPE_SKILL", "IORDER");
			$main->set('list_sub_category_ages', $list_sub_category_ages);
			$main->set('list_sub_category_skills', $list_sub_category_skills);
		*/	
			
			$main->set('cat', $cat);			

     //footer
			$footer =  new Template('adm_footer');
			$footer->set('statistics1', '');

     //all
			$tpl =  new Template();
			$tpl->set('header', $header);
	 $tpl->set('submenu', $submenu);//
	 $tpl->set('main', $main);
	 $tpl->set('footer', $footer);

	 echo $tpl->fetch('adm_home');
	 ?>
