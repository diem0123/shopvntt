<?php

// kiem tra logged in	
	
	$idtype = $itech->input['idtype'];
	$idcat = $itech->input['idcat'];
	$idcatsub = $itech->input['idcatsub'];
	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = $itech->vars['num_record_on_page'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
     
	//header
	//$pre = '';// get language
	
	// set menu active
	$_SESSION['tab'] = 4;// hinh anh
	// get title button
	
	$name_title = $lang['tl_gallery'];
	
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	$main_info=& new Template('main_info');
		  
	$temp_contents=& new Template('common1');		
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idcat, $idcatsub highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk);
	
	$temp_contents->set('list_cat_menu', $list_cat_menu);
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************

		$condit = " STATUS = 'Active' ";
	
		$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB","BIG_IMG");
		$list_photo_rs = $print_2->list_row_multi_field("photos", $condit, "ID_PT", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC", "list_photo_rs", $pageoffset,$num_record_on_page, $page, $idmnselected=0, $pre );
		$tpl_news =& new Template('list_photo');
		$tpl_news->set('list_photo_rs', $list_photo_rs);
		
		// paging
		$total_get = $common->CountRecord("photos",$condit,"ID_PT");
		$link_page = "hinh-anh.html";

		$class_link = "do";
		if ($num_record_on_page < $total_get)
			$tpl_news->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		else
			$tpl_news->set('paging' , '');			
		
		$main_contents = $tpl_news->fetch('list_photo');
	
	
	
	$temp_contents->set('main_contents', $main_contents);

	// set intro on top
	// get intro top main content
	$tpl_intro =& new Template('intro_viewcart');
	$idps = $_SESSION['tab'];
	$info_bn = $common->getInfo("banner","ID_PS = '".$idps."'");
	$tpl_intro->set('imgname', $info_bn['NAME_IMG']);
	$tpl_intro->set('info', $info_bn['INFO']);
	$intro = $tpl_intro->fetch('intro_viewcart');
	
	$main_info->set('intro', $intro);
	$main_info->set('main_contents', $temp_contents);
	// set menu
	$main_info->set('list_cat_menu', $list_cat_menu);
	$main_info->set('name_title', $name_title);
		
	// get list chat
	$list_chat = $print->getNickchat();
	$main_info->set('list_chat', $list_chat);
	
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