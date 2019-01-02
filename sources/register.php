<?php

// kiem tra logged in
	
	$idc = $itech->input['idc'];
	$idt = $itech->input['idt'];
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	
	$username = $itech->input['email'];
	$user_member = $itech->input['email'];
	$password = $itech->input['password'];
	$email = $itech->input['email'];
	$fullname = $itech->input['email'];
	$tel = $itech->input['tel'];
	$address = $itech->input['address'];
	$province = $itech->input['province'];
	if($province=="") $province = 0;
	$country = $itech->input['country'];
	if($country=="") $country = 0;
	$dist = $itech->input['dist'];
	if($dist=="") $dist = 0;
	$idrole = 5;// default customer normal
	$gender = $itech->input['gender'];// gioi tinh
	if($gender=="") $gender = 0;
	$status = "Active";// default, user need activation in email
	$submit = $itech->input['submit'];
	$mess1 = "";
	$mess0 = "";
	
	
	$list_provinces = $print_2->GetDropDown($province, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
	$list_country = $print_2->GetDropDown($country, "countries","" ,"countries_id", "countries_name", "c_oder DESC");
	$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province."'" ,"ID_DIST", "NAME", "IORDER");
	$list_gender = $print_2->GetDropDown($gender, "gender","" ,"ID_GENDER", "NAME".$pre, "IORDER");
	
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="dangky.html" >'.$lang['tl_reg'].'</a><span class="arrow"><span></span></span></span>';
	$lkcat = '<a href="dangky.html" >'.$lang['tl_reg'].'</a>';
	$lkcat = " <span>></span> ".$lkcat;
	// get tite category
	$title_cat = "";
	$tpl_title = new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	
	if($submit != "")
	 {	
		// check account duplicate
		$check_account = $common->getListValue("consultant","EMAIL = '".$email."'","ID_CONSULTANT");
		$check_user_member = $common->getListValue("consultant","USER_MEMBER = '".$user_member."'","ID_CONSULTANT");
		if($check_account != "" || $check_user_member != ""){
			if($check_user_member != "" )
			$mess0 = $lang['exist_usermember'];
			if($check_account != "")
			$mess1 = $lang['exist_email'];
		}
		else{
	 	// input db								
				try {
					$ArrayData = array( 1 => array(1 => "GENDER", 2 => $gender),
										2 => array(1 => "FULL_NAME", 2 => $fullname),
										3 => array(1 => "TEL", 2 => $tel),
										4 => array(1 => "EMAIL", 2 => $email),
										5 => array(1 => "PASSWORD", 2 => hash('sha256',$password)),
										6 => array(1 => "STATUS", 2 => $status),
										7 => array(1 => "CREATED_DATE", 2 => date('Y-m-d H:i:s')),
										8 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
										9 => array(1 => "ADDRESS", 2 => $address),										
										10 => array(1 => "USER_NAME", 2 => $username),
										11 => array(1 => "ID_PROVINCE", 2 => $province),
										12 => array(1 => "ID_DIST", 2 => $dist),
										13 => array(1 => "USER_MEMBER", 2 => $user_member),
										14 => array(1 => "COUNTRIES_ID", 2 => $country)
										
									  );
							  
					$id_const = $common->InsertDB($ArrayData,"consultant");
					
					$ArrayData2 = array( 1 => array(1 => "ROLE_ID", 2 => $idrole),
										2 => array(1 => "ID_CONSULTANT", 2 => $id_const)																			
									  );
					
					$idc_hasrole = $common->InsertDB($ArrayData2,"consultant_has_role");
					// send mail register
					$subject = $fullname." Dang ky tai khoan thanh cong";
					$tplcontent =  new Template('tpl_active_account');      					
					$tplcontent->set('fullname', $fullname);					
					$content= $tplcontent->fetch('tpl_active_account');
					
					$emailfrom = $itech->vars['email_webmaster'];
					
					$namefrom = $INFO['name_webmaster'];
					$emailto = $email;
					$nameto = "";
					$mail = new emailtemp();
					//$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);
					// redirect to register success
					$url_redidrect='dang-ky-thanh-cong.html';
					$common->redirect_url($url_redidrect);
					
				} catch (Exception $e) {
						echo $e;
					}
	 
		}
	 }
	
	// get language
	//$pre = '';
    
	//header
	$_SESSION['tab'] = 1; // set menu active: Account
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

	// Get data main 2	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") {
	$tplmain = "m_main_info";
	$tplc = "m_register";
	}
	else {
	$tplmain = "main_register";
	$tplc = "register";
	}	
	
	$main_info= new Template($tplmain);
	// check conditional for display menu with ID_TYPE	
	$pre = '';// get language
	// get main content login
	$tpl_login = new Template($tplc);		
	$tpl_login->set('pre_title', $pre_title);
	$tpl_login->set('email', $email);
	$tpl_login->set('user_member', $user_member);
	$tpl_login->set('password', '');	
	$tpl_login->set('fullname', $fullname);
	$tpl_login->set('tel', $tel);
	$tpl_login->set('address', $address);
	$tpl_login->set('list_provinces', $list_provinces);
	$tpl_login->set('list_country', $list_country);
	$tpl_login->set('list_dist', $list_dist);
	$tpl_login->set('list_gender', $list_gender);
	$tpl_login->set('mess0', $mess0);
	$tpl_login->set('mess1', $mess1);
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
	
	$footer = $common->getFooter('footer');	
	
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