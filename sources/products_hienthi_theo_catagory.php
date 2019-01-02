<?php

// kiem tra logged in
	
	$sapxep = $itech->input['sapxep'];
	//echo "sapxep=".$sapxep;
	$idc = $itech->input['idc'];
	$idt = $itech->input['idt'];	
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	$keyword = $itech->input['keyword'];
	$ph = $itech->input['ph'];
	$brand = $itech->input['brand'];
	$keyword1 = $itech->input['keyword1'];
	if($keyword1 && !$keyword) $keyword = $keyword1;

	
	// get sort by
	switch($sapxep){
		case 1: $getsortby = " DATE_UPDATE DESC ";// san pham moi nhat $ph = 1;
		break;
/*		
		case 2: $ph = 2;$getsortby = " DATE_UPDATE DESC ";// san pham yeu thich nhat	
		break;
		case 3: $ph = 3;$getsortby = " DATE_UPDATE DESC ";// san pham ban chay nhat	
		break;
		case 4: $ph = 4;$getsortby = " DATE_UPDATE DESC ";// san pham co qua tang	
		break;
*/		
		case 2: $getsortby = " PRICE ";// gia thap - cao	
		break;
		case 3: $getsortby = " PRICE DESC ";// gia cao - thap	
		break;
		case 4: $getsortby = " PRODUCT_NAME  ";// ten a - z	
		break;
		case 5: $getsortby = " PRODUCT_NAME DESC ";// ten z - a	
		break;
		
		default: $getsortby = " DATE_UPDATE DESC ";//
		
	}
	
	$tempkey = "";
	$keyword_criteria = "";	
	
	// search keyword
		if ($keyword != "" && $keyword != "T&igrave;m ki&#7871;m" )
		{
			//$t1 = 0 < $local ? " AND " : " AND ";
			$t1 = " AND ";
			$word = explode(" ", $keyword );
			$tempkey = " ( PRODUCT_NAME LIKE '%".$word[0]."%' OR PRICE LIKE '%".$word[0]."%' OR INFO_SHORT LIKE '%".$word[0]."%')";    
			for ($i=1;$i<count($word);$i++)
			{
				$keyword_criteria .= " AND ( PRODUCT_NAME LIKE '%".$word[$i]."%' OR PRICE LIKE '%".$word[$i]."%' OR INFO_SHORT LIKE '%".$word[$i]."%')";
			}
			
			$keyword_criteria = $t1.$tempkey.$keyword_criteria;
			//$condit_keyword .= $keyword_criteria;
		}
	
	//if($ida && !$idsk && !$idprocat) $idprocat = $ida;
	//elseif(!$ida && $idsk && !$idprocat) $idprocat = $idsk;
	
	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = $itech->vars['num_record_on_page_column'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
	// get language
	$pre = $_SESSION['pre'];
    
	//header
	$_SESSION['tab'] = 3; // set menu active: products
	$header = $common->getHeader('header');
	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
		  
	$temp_contents=& new Template('main_products');
	// check conditional for display menu with ID_TYPE		
	// get menu selected when view product detail
	$name_query_string = "";
	
	// get products hot, favorites,..
	if($ph >0 ){
		if(!$idc) $idc =1;
		if(!$idt) $idt =1;
		if($ph==1){
		$name_link = "san-pham-hot";
		$conditpro = "ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
		$orderby = "DATE_UPDATE";
		
		}
		elseif($ph==2){
		$name_link = "san-pham-yeu-thich";
		$conditpro = "ID_CATEGORY = '".$idc."' AND FAVORITES > 0 AND STATUS = 'Active'";
		$orderby = "FAVORITES";		
		}
		elseif($ph==3) {
		$name_link = "san-pham-ban-chay";
		$conditpro = "ID_CATEGORY = '".$idc."' AND COUNT_BUY > 0 AND STATUS = 'Active'";
		$orderby = "COUNT_BUY";
		}
		elseif($ph==4) {
		$name_link = "san-pham-co-qua-tang";
		$conditpro = "ID_CATEGORY = '".$idc."' AND GIF = 1 AND STATUS = 'Active'";
		$orderby = "DATE_UPDATE";
		}
		// get menu select
		for($i=1;$i<=4;$i++){
			if(!$ph && $i==1) $temp_contents->set('st'.$i, "style21");
			elseif($ph && $ph == $i) $temp_contents->set('st'.$i, "style21");
			else $temp_contents->set('st'.$i, "style1");
		}
		
		$display_list = 1;											
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt."&ph=".$ph;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt."&ph=".$ph;	
		//$idmnselected = array(0 => $name_id, 1 => $vl_id);
		//$idmnselected = $name_query_string;
	}
	
	elseif($idc && !$idt && !$idprocat ){		
		// Display Info products allows idc				
			$conditpro = "ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
			$display_list = 1;					
		
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY']);
		$name_link = $print->getlangvalue($info_title,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_CATEGORY'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?";//?sapxep=".$sapxep;
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc; 	

	}
	elseif($idc && $idt && !$idprocat){		
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_TYPE']);
		$name_link = $print->getlangvalue($info_title,"NAME_TYPE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_TYPE'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt;		
		
	}
	elseif($idc && $idt && $idprocat){
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND ID_PRO_CAT = '".$idprocat."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_PRO_CAT']);
		$name_link = $print->getlangvalue($info_title,"NAME_PRO_CAT",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_PRO_CAT'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt."&idprocat=".$idprocat;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt."&idprocat=".$idprocat;			
		
	}
	else{
/*
		$conditpro = " STATUS = 'Active'";
		//$display_list = 1;					
		// get paging for default
		// get title name link
		//$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY'.$pre]);			
		$name_link = "danh-sach";
		$_SESSION['get_title'] = "Search product";
		$idc = 0;
		$link_page = "sanpham-".$idc."-".$name_link.".html?keyword=".$keyword;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc;
*/	
		$display_list = 0;
	}
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	
	$temp_contents->set('list_cat_menu', $list_cat_menu);
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************
	/*
	if($idt && $idprocat)
	$link_page = "sanpham-".$idc."-".$_SESSION['name_link'].".html?idt=".$idt."&idprocat=".$idprocat;
	elseif($idt && !$idprocat)
	$link_page = "sanpham-".$idc."-".$_SESSION['name_link'].".html?idt=".$idt;
	*/
	// display list and paging: hien thi chung cho tat ca cac truong hop cap bac menu phia tren	
	if($display_list ==1){
		// get tite category
		$title_cat = "";
		$tpl_title =& new Template('title_subcat');
		
		$name_titlecat = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");
		$name_titlesubcat = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		$name_titlesubprocat = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		
		//$name_linkcat = $print_2->vn_str_filter($name_titlecat['NAME_CATEGORY']);
		$name_linkcat = $print->getlangvalue($name_titlecat,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		//$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['NAME_TYPE']);
		$name_linksubcat = $print->getlangvalue($name_titlesubcat,"NAME_TYPE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		//$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['NAME_PRO_CAT']);
		$name_linksubprocat = $print->getlangvalue($name_titlesubprocat,"NAME_PRO_CAT",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a><span class="arrow"><span></span></span></span>';
		$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
		$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
		$linkT = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-0-danh-sach.html" >'.$lang['tl_prod'].'</a><span class="arrow"><span></span></span></span>';
		if($idc) $linkT = "";
		
		if(!$idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $linkT);	
		elseif($idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $linkT.$lkcat);		
		elseif($idc && $idt && !$idprocat)
		$tpl_title->set('namecat', $linkT.$lkcat.$lksubcat);
		elseif($idc && $idt && $idprocat)
		$tpl_title->set('namecat', $linkT.$lkcat.$lksubcat.$lksubprocat);
		
		$tpl_title->set('title_sp', "title_breadcrumb");
		
	/*	
		$name_titlecat = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");
		$name_titlesubcat = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		$name_titlesubprocat = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		$title_cat = "";
		$tpl_title =& new Template('title_subcat');
		
		$name_linkcat = $print_2->vn_str_filter($name_titlecat['NAME_CATEGORY'.$pre]);
		$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['NAME_TYPE'.$pre]);
		$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['NAME_PRO_CAT'.$pre]);
		$lkcat = '<a href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a>';
		$lksubcat = '<a href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a>';
		$lksubprocat = '<a href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a>';
	
	
		
		
		if(!$idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', "S&#7842;N PH&#7848;M");	
		elseif($idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $lkcat);		
		elseif($idc && $idt && !$idprocat)
		$tpl_title->set('namecat', $lkcat." -> ".$lksubcat);
		elseif($idc && $idt && $idprocat)
		$tpl_title->set('namecat', $lkcat." -> ".$lksubcat." -> ".$lksubprocat);
		
		$tpl_title->set('title_sp', "title");
		
		*/
		
		//$title_cat = $tpl_title->fetch('title_subcat');
		
		$tpl_title->set('idc', $idc);
		// xu ly title trong truong hop tim kiem va binh thuong
		if($idc){
		$title_cat = $tpl_title->fetch('title_subcat');		
		}
		else{			
			$tpl_title->set('namecat', $linkT);			
			//$tpl_title->set('title_sp', "title");
			$title_cat = $tpl_title->fetch('title_subcat');			
		}
		
		
		$list_images1 = $title_cat;
		
		// search with brand
		if($brand) {
		$conditpro .= " AND ID_BR = '".$brand."' ";
		$link_page .= "&brand=".$brand;
		}
	
		$conditpro .= $keyword_criteria;
//echo $conditpro;		
		//$idmnselected = array(0 => $name_id, 1 => $vl_id);
		$idmnselected = $name_query_string;		
		$field_name_other = array('CATALOG','IMAGE_LARGE','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'ID_AGES', 'ID_SKILL', 'INFO_SHORT', 'CODE', 'COUNTVIEW','COUNT_QUANTITY', 'EAN_CODE', 'SIZE', '');		
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby.", ID_PRODUCT DESC", "list_3all_images_procat", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );		
		$tpl_pro =& new Template('main_list_pro');
		$tpl_pro->set('title_cat', $title_cat);
		//echo $conditpro." ORDER BY ".$getsortby;
		if($list_images != "")
			$tpl_pro->set('list_all_images_procat', $list_images);
		else{
			$fet_boxno = & new Template('nokq');
			$text = "Kh&ocirc;ng t&igrave;m th&#7845;y s&#7843;n ph&#7849;m!";
			$fet_boxno->set("text", $text);
			$fb = $fet_boxno->fetch('nokq');
			$tpl_pro->set('list_all_images_procat', $fb);
		}
		
		$tpl_pro->set('idc', $idc);
		
		// paging
		$total_get = $common->CountRecord("products",$conditpro,"ID_PRODUCT");
		$class_link = "linkpage";
		if ($num_record_on_page < $total_get){			
			$tpl_pro->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		}	
		else
			$tpl_pro->set('paging' , '');			
		
		// tong so ket qua	
		$tpl_pro->set('total_get', $total_get);
		$tpl_pro->set('idc', $idc);
		// get sortby	
		$tpl_pro->set('idc', $idc);
		$tpl_pro->set('idt', $idt);
		$tpl_pro->set('idprocat', $idprocat);
		$tpl_pro->set('ida', $ida);
		$tpl_pro->set('idsk', $idsk);
		$tpl_pro->set('keyword', $keyword);
		$tpl_pro->set('ph', $ph);
		for($i=1;$i<=5;$i++){
			if(!$sapxep && $i==1) {
			$tpl_pro->set('s'.$i, "selected");
			}
			elseif($i == $sapxep) $tpl_pro->set('s'.$i, "selected");
			else $tpl_pro->set('s'.$i, "");
			
		}
		// get list brand		
		$list_brand = $print_2->GetDropDown($brand, "brand_lookup","" ,"ID_BR", "NAME", "IORDER");
		$tpl_pro->set('list_brand', $list_brand);
		$main_contents = $tpl_pro->fetch('main_list_pro');
	}
	else{	
	//+++++++++++++++++++++++++++++++++++++++++++++ Hien thi list category
		// get tite category
			$title_cat = "";
			$tpl_title =& new Template('title_subcat');
			$idc = 0;
			$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-danh sach.html" >'.$lang['tl_prod'].'</a><span class="arrow"><span></span></span></span>';
			$tpl_title->set('namecat', $lkcat);
			//$tpl_title->set('namecat', $rs1['NAME_CATEGORY'.$pre]);
			//$_SESSION['get_title'] = $rs1['NAME_CATEGORY'.$pre];
			$tpl_title->set('title_sp', "title_breadcrumb");
			$title_cat = $tpl_title->fetch('title_subcat');			
			$_SESSION['get_title'] = $lang['tl_prod'];					
			$idfield = "ID_CATEGORY";
			$conditpro1 = " STATUS = 'Active' ";
			$name_link = "danh-sach";
			$link_page = "sanpham-".$idc."-".$name_link.".html";
			$name_query_string = "";
			$idmnselected1 = $name_query_string;		
			$field_name_other1 = array('NAME_TYPE','ID_TYPE','STATUS','ID_CATEGORY', 'IORDER', 'LINK','PICTURE');
			$list_images = $print_2->list_row_column_multifield("categories_sub", $conditpro1, "ID_TYPE", "NAME_TYPE", $field_name_other1, " ORDER BY ID_CATEGORY ", "list_3images_subcat", 3, $pageoffset, 27, $page, $idmnselected1, $pre );
			
			$tpl_pro =& new Template('main_homelist_pro');
			//$tpl_pro->set('title_cat', $title_cat);
			//echo $conditpro." ORDER BY ".$getsortby;
			if($list_images != "")
				$tpl_pro->set('list_all_images_procat', $list_images);
			else{
				$fet_boxno = & new Template('nokq');
				$text = "Kh&ocirc;ng t&igrave;m th&#7845;y s&#7843;n ph&#7849;m!";
				$fet_boxno->set("text", $text);
				$fb = $fet_boxno->fetch('nokq');
				$tpl_pro->set('list_all_images_procat', $fb);
			}
			
			$tpl_pro->set('idc', $idc);
			
			// paging
			$total_get = $common->CountRecord("products",$conditpro,"ID_PRODUCT");
			$class_link = "linkpage";
			if ($num_record_on_page < $total_get){			
				$tpl_pro->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
			}	
			else
				$tpl_pro->set('paging' , '');
			
			$main_contents = $tpl_pro->fetch('main_homelist_pro');
			
	}
	
	// picture slide show
	$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$temp_contents->set('intro', $tpl_intro);
	
	// get hot news		
	$hotnews_left = $print->gethot_news_left($pre, 6);
	$temp_contents->set('hotnews', $hotnews_left);
	
	// get logo partner
	$logo_partner = $print->getlogo_partner($pre);
	$temp_contents->set('logo_partner', $logo_partner);
		
	$temp_contents->set('title_cat', $title_cat);
	$temp_contents->set('main_contents', $main_contents);
	$temp_contents->set('idc', $idc);
	
	// get statistic
	$temp_contents->set('statistics', $print->statistics());
	$temp_contents->set('todayaccess', $print->get_today( ));
	$temp_contents->set('counter', $print->get_counter());	
	
	$main->set('main2', $temp_contents); 	 

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