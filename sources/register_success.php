<?php

// kiem tra logged in
	

	// get language
	//$pre = '';
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="dangky.html" >'.$lang['tl_reg'].'</a><span class="arrow"><span></span></span></span>';
	$lkcat = '<a href="dangky.html" >'.$lang['tl_reg'].'</a>';
	$lkcat = " <span>></span> ".$lkcat;
	
	// get tite category
	$title_cat = "";
	$tpl_title = new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');
    
	//header
	$_SESSION['tab'] = 1; // set menu active: Account
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');
	//main
	$main = new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") {
	$tplmain = "m_main_info";
	$tplc = "m_register_success";
	}
	else {
	$tplmain = "main_register";
	$tplc = "register_success";
	}
	
	$main_info= new Template($tplmain);
	// check conditional for display menu with ID_TYPE	
	$pre = '';// get language
	// get main content login
	$tpl_login = new Template($tplc);		
	$tpl_login->set('mess', "");
	$main_contents = $tpl_login->fetch($tplc);

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
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpl_menur_pro= new Template('list_menur_pro');
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$main_info->set('list_menur_pro', $tpl_menur_pro);
	
	$support_online = $print->getSupportOnline(66);
	$main_info->set('support_online', $support_online);
	
	$clip = $print->getclip();
	$main_info->set('clip', $clip);
	
	// get hot news
	$hotnews_right = $print->gethot_news_right_h($pre, 12);
	$main_info->set('hotnews_right', $hotnews_right);
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_info->set('logo_partner', $logo_partner);

	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());		
	
	// Get main 2
	$main->set('main2', $main_info);
	
	

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	
	$footer =  $common->getFooter('footer');	
	//all
	$tpl =  new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>