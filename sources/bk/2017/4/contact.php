<?php

// kiem tra logged in
	
	// get language
	$pre = $_SESSION['pre'];
	
	$idtype = $itech->input['idtype'];
	//$idtype = 6;// default menu selected account
	$idcat = $itech->input['idcat'];
	//$idcat = 22;// default view_account
	$idcatsub = $itech->input['idcatsub'];
	
	// get form contact
	$submit = $itech->input['submit'];
	$fullname = $itech->input['fullname'];
	$tel = $itech->input['tel'];
	$email = $itech->input['email'];
	$province = $itech->input['province'];
	$dist = $itech->input['dist'];
	$address = $itech->input['address'];
	
	$company = $itech->input['company'];
	$city = $itech->input['city'];
	$country = $itech->input['country'];
	$subj = $itech->input['subj'];	
	
	$content = $itech->input['content'];
	$cus_contentencode = $itech->input['cus_contentencode'];
	
	// get menuselect
	$menuinfo = $common->getInfo("menu1"," LINK = 'lienhe.html' ");
	if(isset($menuinfo['IORDER'])) $_SESSION['tab'] = $menuinfo['IORDER'];
	else $_SESSION['tab'] = 1;
	
	
	if(isset($submit) && $submit != ""){
		// check send mail					
					$subject = $subj;
					$tplcontent = & new Template('tpl_contact');      					
					$tplcontent->set('fullname', $fullname);
					$tplcontent->set('tel', $tel);
					$tplcontent->set('email', $email);
					
					$tplcontent->set('company', $company);
					$tplcontent->set('city', $city);
					$tplcontent->set('country', $country);
					$tplcontent->set('subj', $subj);
					//$province_info = $common->getInfo("province","ID_PROVINCE = '".$province."'");
					//$dist_info = $common->getInfo("district","ID_DIST = '".$dist."'");
					//$tplcontent->set('name_province', $province_info['NAME']);
					//$tplcontent->set('name_dist', $dist_info['NAME']);
					$tplcontent->set('address', $address);
					//$content_decode = base64_decode($cus_contentencode);
					$tplcontent->set('content', $content);
									
					$content= $tplcontent->fetch('tpl_contact');
					
					$ids = 1;
					$getsmtp = $common->getInfo("outgoing_email","OUTGOING_EMAIL_ID = '".$ids."'");
					
					$emailfrom = $email;					
					$namefrom = $getsmtp['FROM_NAME'];//$fullname; email dem gui thay
					$emailto = $getsmtp['FROM_EMAIL'];//$itech->vars['email_webmaster'];
					//$emailto = "thanle76@gmail.com";
					$nameto = $getsmtp['FROM_NAME'];//$INFO['name_webmaster'];
					$mail = new emailtemp();
					$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);					
					// redirect to register success
					if($error == "ok") {
						$_SESSION['mess_forgot1'] = $lang['tl_thongtinsent'];
						$_SESSION['mess_forgot2'] = "";
					}
					else{
						$_SESSION['mess_forgot1'] = $lang['tl_emailnotsend'];
						$_SESSION['mess_forgot2'] = "";
					}
					
					$url_redidrect='thong-bao.html';
					$common->redirect_url($url_redidrect);
	
	}
	
	// redirect to url for account references
	

	
	// get main content 
	//$_SESSION['tab'] = 8;// Contact
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
	$tplc = "m_contact";	
	}
	else{
	$tplc = "contact";
	}
	
	$tpl1 =& new Template($tplc);
	$list_provinces = $print_2->GetDropDown($province, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
	$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province."'" ,"ID_DIST", "NAME", "IORDER");
	$tpl1->set("fullname", $fullname);
	$tpl1->set("tel", $tel);
	$tpl1->set("email", $email);
	$tpl1->set("list_provinces", $list_provinces);
	$tpl1->set("list_dist", $list_dist);
	$tpl1->set("address", $address);	
	$tpl1->set("content", $content);
	
	$infocontact = $common->getInfo("common"," ID_COMMON = 113");
	if($infocontact['SCONTENTS'.$pre]=="")
	$tpl1->set("infocontact", base64_decode($infocontact['SCONTENTS']));
	else
	$tpl1->set("infocontact", base64_decode($infocontact['SCONTENTS'.$pre]));
	
	$main_contents = $tpl1->fetch($tplc);


	
	
	//header
	
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
	
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="lienhe.html" >'.$lang['tl_contact'].'</a><span class="arrow"><span></span></span></span>';	
	$lkcat = '<a href="lienhe.html" >'.$lang['tl_lienhe'].'</a>';	
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
	if($_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_info";	
	}
	else{
	$tplmain = "main_info";
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
	
	$support_online = $print->getSupportOnline(66);
	$main_info->set('support_online', $support_online);
	
	if($idtype==6)		
	$hotnews_right = $print->gethot_news_right_tintuc($pre, 20);// tin tuc
	else
	$hotnews_right = $print->gethot_news_right($pre, 20);// golf corner
	$main_info->set('hotnews_right', "");
	
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
	if($_SESSION['dv_Type']=="phone")
	$footer = & new Template('m_footer');	
	else
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	// get cong ty footer
	$cty = $common->gethonhop(6);
	if($cty == "")
	$footer->set('cty', "");
	else{
		$footer->set('title_welcome', $cty['STITLE'.$pre]);
		if($cty['SCONTENTS'.$pre]=="")
		$footer->set('cty', base64_decode($cty['SCONTENTS']));
		else
		$footer->set('cty', base64_decode($cty['SCONTENTS'.$pre]));
	}

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

// get img fb
$pathimg = $itech->vars['pathimgfb'];
$urlpic = $pathimg."/imagenews/nhamaygcfood-14867987351006188375.jpg";
$tenurl = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$tpl->set('nameimg', $urlpic);
$tpl->set('nameurl', $tenurl);		

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>