<?php

	
	$idtype = $itech->input['idtype'];
	$member = $itech->input['member'];
	//$idtype = 6;// default menu selected account
	$idcat = $itech->input['idcat'];
	//$idcat = 22;// default view_account
	$idcatsub = $itech->input['idcatsub'];	
	// redirect to url for account references	
	
	// get info user
	if($member != ""){
	$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$member."'");
	$owner = 0;
	}
	else{
		// kiem tra logged in
		if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='dangnhap.html';
		$common->redirect_url($url_redidrect);
		}
		$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
		$owner = 1;
	}
	
	$username = $cst['USER_NAME'];
	$user_member = $cst['USER_MEMBER'];
	$password = "";
	$email = $cst['EMAIL'];
	$fullname = $cst['FULL_NAME'];
	$tel = $cst['TEL'];
	$address = $cst['ADDRESS'];
	$province = $cst['ID_PROVINCE'];
	$country = $cst['COUNTRIES_ID'];
	$dist = $cst['ID_DIST'];
	$idrole = 5;// default customer normal
	$gender = $cst['GENDER'];// gioi tinh
	
	$province_info = $common->getInfo("province","ID_PROVINCE = '".$province."'");
	$country_info = $common->getInfo("countries","countries_id = '".$country."'");
	$dist_info = $common->getInfo("district","ID_DIST = '".$dist."'");
	$gender_info = $common->getInfo("gender","ID_GENDER = '".$gender."'");
     
	//header
	//$pre = '';// get language
	$_SESSION['tab'] = 1;// Account
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="thong-tin-tai-khoan.html" >T&Agrave;I KHO&#7842;N</a><span class="arrow"><span></span></span></span>';
	$lkcat = '<a href="thong-tin-tai-khoan.html" >'.$lang['tl_tkhoan'].'</a>';
	$lkcat = " <span>></span> ".$lkcat;
	
	// get tite category
	$title_cat = "";
	$tpl_title = new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	//$name_title = "T&Agrave;I KHO&#7842;N";
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');

	//main
	$main =  new Template('main');      

	$tempres= new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") {
	$tplmain = "m_main_info";
	$tplc = "m_view_account";
	}
	else {
	$tplmain = "main_profile";
	$tplc = "view_account";
	}
	
	// Get data main 2	
	$main_info= new Template($tplmain);
	
	
	// get main content 
	$tpl1 = new Template($tplc);			
	$tpl1->set('user_member', $user_member);
	$tpl1->set('email', $email);
	$tpl1->set('password', '');	
	$tpl1->set('fullname', $fullname);
	$tpl1->set('tel', $tel);
	$tpl1->set('address', $address);
	$tpl1->set('provinces_name', $province_info['NAME']);
	$tpl1->set('country_name', $country_info['countries_name']);
	$tpl1->set('dist_name', $dist_info['NAME']);
	$tpl1->set('gender_name', $gender_info['NAME'.$pre]);
	$tpl1->set('owner', $owner);
	$main_contents = $tpl1->fetch($tplc);
	
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
	

	// hien thi cot menu quan ly account
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") $tplvod = "m_menu_account_cat_rs";
	else $tplvod = "menu_account_cat_rs";
			
	$tpl_menu_acc= new Template($tplvod);
	$tpl_menu_acc->set('idac', '');
	$main_info->set('list_menuacc', $tpl_menu_acc->fetch($tplvod));
	$main_info->set('acc', 1);
	
	$support_online = $print->getSupportOnline(66);
	$main_info->set('support_online', $support_online);
	
	$clip = $print->getclip();
	$main_info->set('clip', $clip);
	
	// get hot news
	$hotnews_right = $print->gethot_news_right_h($pre, 12);
	$main_info->set('hotnews_right', $hotnews_right);
	
	//$hotnews_right = $print->gethot_news_right_group2($pre, 12);
	//$main_info->set('hotnews_right', $hotnews_right);
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_info->set('logo_partner', $logo_partner);

	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());		
	
	// Get main 2
	$main->set('main2', $main_info);
	
	

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