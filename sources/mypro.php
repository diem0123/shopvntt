<?php

// kiem tra logged in
	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=login';
		$common->redirect_url($url_redidrect);
	}
	

	$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="san-pham-da-luu.html" >'.$lang['tl_prsave'].'</a><span class="arrow"><span></span></span></span>';
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	//$name_title = "T&Agrave;I KHO&#7842;N";
	
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2	
	$main_info=& new Template('main_info');
	
	
	// get main content 
	$tpl1 =& new Template('mypro');			
	
	// setup for paging			
			$page = $itech->input['page'];
			//$idtype = $itech->input['idtype'];
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('ID_CONSULTANT','ID_PRODUCT','CREATED_DATE');			
			$fet_box = & new Template('mypro_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt = 0;
			
			$condit = " ID_CONSULTANT = ".$_SESSION['ID_CONSULTANT'];
			$sql="SELECT * FROM products_like  WHERE ".$condit." ORDER BY CREATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql);
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;
					$info_pro = $common->getInfo("products","ID_PRODUCT = '".$rs['ID_PRODUCT']."'");
					$fet_box->set( "productname", $info_pro['PRODUCT_NAME'.$pre]);
					$name_link = $print_2->vn_str_filter($info_pro['PRODUCT_NAME'.$pre]);
					$fet_box->set( "name_link", $name_link);
					$name_query_string = "?idc=".$info_pro['ID_CATEGORY']."&idt=".$info_pro['ID_TYPE']."&idprocat=".$info_pro['ID_PRO_CAT'];
					$fet_box->set( "menu_select", $name_query_string);
					$fet_box->set( "ID_CATEGORY", $info_pro['ID_CATEGORY']);
					$fet_box->set( "THUMBNAIL", $info_pro['THUMBNAIL']);
					$fet_box->set("price", number_format($info_pro['PRICE'],0,"","."));
					$fet_box->set("sale", $info_pro['SALE']);								
					$fet_box->set( "idp", $rs['ID_PRODUCT']);
					$fet_box->set( "idlike", $rs['ID_LIKE']);
					$fet_box->set( "stt", $stt);
					$fet_box->set( "CREATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					
					
					
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
										
					// Get info datasub
					$datasub .= $fet_box->fetch("mypro_rs");
	
				}
							
				if($datasub != "")
				$tpl1->set('prolike_list_rs', $datasub);
				else{
				$fet_boxno = & new Template('nopro_like');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("nopro_like");
				$tpl1->set('prolike_list_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("products_like",$condit,"ID_LIKE");
					
					$link_page = "san-pham-da-luu.html?";
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$tpl1->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$tpl1->set('paging' , '');
									
			$tpl1->set('page', $page);

	$main_contents = $tpl1->fetch('mypro');
	
	// picture slide show
	$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$main_info->set('intro', $tpl_intro);
		
	// get title	
	$main_info->set('title_cat', $title_cat);	
	$main_info->set('main_contents', $main_contents);
	$main_info->set('display_list', $display_list);
	$main_info->set('titlepr', $titlepr);	
	
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetListMN_right($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idtype, $idcat, $idcatsub, 0, 0, 0, 0, 0);
	$tpl_menur= new Template('list_menur');
	$tpl_menur->set('list_menur_rs', $list_cat_menu);
	// check ton tai menu con
	$listsubmn = $common->getListValue("common_cat","STATUS = 'Active' AND ID_TYPE = '".$idtype."' ORDER BY IORDER",'ID_CAT');
	if($listsubmn != "")
	$main_info->set('list_menur', $tpl_menur);
	else
	$main_info->set('list_menur', "");
		
	$cpanel = $print->getmenu_account($pre, 1, 0);
	
	// get hot news		
	//$hotnews_right = $print->gethot_news_right($pre, 8);
	$main_info->set('hotnews_right', $cpanel);
	
	// get hoat dong va thanh tich		
	$hdthanhtich = $print->get_hdongttich($pre, 8);
	$main_info->set('hdthanhtich', $hdthanhtich);

	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());		
	
	// Get main 2
	$main->set('main2', $main_info);
	
	

	//footer
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	$footer->set('todayaccess', $print->get_today());

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	echo $tpl->fetch('home');
?>