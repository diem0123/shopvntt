<?php
 
// kiem tra logged in

	// get language
	$pre = '';
    
	//header
	 $_SESSION['tab'] = 1; // set menu active: home
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	// picture slide show
	$tempres=& new Template('picmain');	  
	$tempres->set('quote', ""); 
	$main->set('main1', $tempres); 

	// Get data main 2
		  
	$temp_contents=& new Template('main_home');
	// check conditional for display menu with ID_TYPE
	// 1: Gioi thieu, 2: Me va Be, 3: Ho tro, 4: Catalogs
	$pre = '';// get language
	//get detail
		$condit = " ID_COMMON = '".$idn."' AND STATUS = 'Active' ";		
		$info_news = $common->getInfo("common",$condit);
		$main_contents = $info_news['SCONTENTS'.$pre];
		
	// get menu list
		$field_name_other1 = array("ID_TYPE","IORDER");
		$field_name_other2 = array("ID_CAT","IORDER");
		$list_cat_menu = $print_2->GetRowMenu("common_cat"," ID_TYPE= '".$info_news['ID_TYPE']."' ORDER BY IORDER ", "ID_CAT", "SNAME", $field_name_other1, "menu_cat_rs", "common_cat_sub","", "ID_CAT_SUB", "SNAME", $field_name_other2, "menu_catsub_rs",$pre, $info_news['ID_CAT'], $info_news['ID_CAT_SUB']);
		$temp_contents->set('list_cat_menu', $list_cat_menu);
	
	$temp_contents->set('main_contents', $main_contents);
	// get others news
	// check for idcatsub not null
		if($info_news['ID_CAT_SUB'] > 0){
		$condit_other = " ID_CAT_SUB = '".$info_news['ID_CAT_SUB']."' AND STATUS = 'Active' ";
		// get link for view all		
		$link_view_all = "muc-".$info_news['ID_TYPE']."-".$_SESSION['name_link'].".html?idcat=".$info_news['ID_CAT']."&idcatsub=".$info_news['ID_CAT_SUB'];
		}
		elseif($info_news['ID_CAT'] > 0){
		$condit_other = " ID_CAT = '".$info_news['ID_CAT']."' AND STATUS = 'Active' ";
		// get link for view all		
		$link_view_all = "muc-".$info_news['ID_TYPE']."-".$_SESSION['name_link'].".html?idcat=".$info_news['ID_CAT'];
		}
		$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB");
		$list_news_rs = $print_2->list_row_multi_field("common", $condit_other, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC", "detail1_list_other", 0,10, 1, $idmnselected=0, $pre );

		// display view all or non display view all news of this catsub
		$total_get = $common->CountRecord("common",$condit_other,"ID_COMMON");
		if($total_get > 2) $show_link_viewall = 1;
		else $show_link_viewall = 0;					
		$temp_contents->set('list_news_other', $list_news_rs);
		$temp_contents->set('total_get', $total_get);
		$temp_contents->set('show_link_viewall', $show_link_viewall);
		$temp_contents->set('link_view_all', $link_view_all);
	
	$main->set('main2', $temp_contents); 	 

	//footer
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());

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