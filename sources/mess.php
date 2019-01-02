<?php

// kiem tra logged in

	// get main content 
	$tpl_login =& new Template('mess');		
	$tpl_login->set('mess1', $_SESSION['mess_forgot1']);
	$tpl_login->set('mess2', $_SESSION['mess_forgot2']);
	
	$main_contents = $tpl_login->fetch('mess');			    
	$_SESSION['tab'] = 8;
	//header
	
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
	
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="lienhe.html" >'.$lang['tl_contact'].'</a><span class="arrow"><span></span></span></span>';	
	//$lkcat = '<a href="lienhe.html" >'.$lang['tl_lienhe'].'</a>';	
	$lkcat =$lang['tl_lienhe'];	
	//$lkcat = " <span>></span> ".$lkcat;
	$tpl_title->set('namecat', $lkcat);		
	
	$tpl_title->set('title_sp', "title_breadcrumb");
	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	//$name_title = $lang['tl_contact'];
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_info";	
	}
	else{
	$tplmain = "main_info_phanhoi";
	}
	
	$main_info=& new Template($tplmain);
		
	$main_info->set('title_cat', $title_cat);
	$main_info->set('idtype', 100);
	
	$main_info->set('intro2', "");
		
	$main_info->set('main_contents', $main_contents);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpl_menur_pro= new Template('list_menur_pro');
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$main_info->set('list_menur_pro', $tpl_menur_pro);
	$name_cat = 'Notification';
	$main_info->set('name_cat',$name_cat);
	$support_online = $print->getSupportOnline(66);
	$main_info->set('support_online', $support_online);
	
	if($idtype==6)		
	$hotnews_right = $print->gethot_news_right_tintuc($pre, 20);// tin tuc
	else
	$hotnews_right = $print->gethot_news_right($pre, 20);// golf corner
	$main_info->set('hotnews_right', $hotnews_right);
	
	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_info->set('logo_partner', $logo_partner);
	
	// get san pham tieu bieu
	//$list_sptb = $print->get_sptb($pre,$idmnselected);
	//$main_info->set('list_sptb', $list_sptb);
	$main_info->set('list_sptb', "");
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_info->set('logo_partner', $logo_partner);

	$main->set('main2', $main_info);
	

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	
	$footer = $common->getFooter('footer');	
	

	
	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	// get so dien thoai dat hang
	$welcome = $common->gethonhop(4);
	if($welcome == "")
	$tpl->set('welcome', "");
	else{
		$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($welcome['SCONTENTS'.$pre]=="")
		$tpl->set('welcome', base64_decode($welcome['SCONTENTS']));
		else
		$tpl->set('welcome', base64_decode($welcome['SCONTENTS'.$pre]));
	}
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>