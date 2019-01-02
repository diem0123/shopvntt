<?php

// kiem tra logged in
	
	$idc = $itech->input['idc'];
	$idt = $itech->input['idt'];
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	
	$username = $itech->input['username'];	
	
	if(isset($itech->input['submit']))
	 {
	 	// check account exist		
		$check_account = $common->getListValue("consultant","EMAIL = '".$username."'","ID_CONSULTANT");		
		if($check_account == "") {
			$msg = $lang['tl_mmailkotontai'];
		}
		else {
			// check send mail
					$cs = $common->getInfo("consultant","EMAIL ='".$username."'");	
					$subject = "Nhan lai mat khau";
					$tplcontent = & new Template('tpl_forgot');      					
					$tplcontent->set('fullname', $cs['FULL_NAME']);
					$tplcontent->set('username', $username);
					$pass = "xkl".rand();
					$password = hash('sha256',$pass);
					$ArrayData = array( 
										1 => array(1 => "PASSWORD", 2 => $password),										
										2 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))
																			
									  );
							  
					$update_condit = array( 1 => array(1 => "EMAIL", 2 => $username));
					$common->UpdateDB($ArrayData,"consultant",$update_condit);
					
					$tplcontent->set('pass', $pass);					
					$content= $tplcontent->fetch('tpl_forgot');
					
					$emailfrom = $itech->vars['email_webmaster'];
					$namefrom = $INFO['name_webmaster'];
					$emailto = $username;
					$nameto = "";
					$mail = new emailtemp();					
					$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);
					//$error = 'ok';
					// redirect to register success
					if($error == "ok") {
						$_SESSION['mess_forgot1'] = $lang['tl_mmailguidenban'];
						$_SESSION['mess_forgot2'] = $lang['tl_mtcong'];
					}
					else{
						$_SESSION['mess_forgot1'] = $lang['tl_mnotsend'];
						$_SESSION['mess_forgot2'] = $lang['tl_msport'];
					}
					
					$url_redidrect='nhan-lai-mat-khau.html';
					$common->redirect_url($url_redidrect);
			
			
		}
	 }

	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="quen-mat-khau.html" >'.$lang['tl_fogot'].'</a><span class="arrow"><span></span></span></span>';
	$lkcat = '<a href="quen-mat-khau.html" >'.$lang['tl_fogot'].'</a>';
	$lkcat = " <span>></span> ".$lkcat;
	
	// get tite category
	$title_cat = "";
	$tpl_title = new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	// get language
	//$pre = '';
    
	//header
	$_SESSION['tab'] = 1; // set menu active: account
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
	
	$tplmain = "main_forgot";
	$tplc = "forgot";
	
	
	$main_info=& new Template($tplmain);
	// check conditional for display menu with ID_TYPE	
	$pre = '';// get language
	// get main content forgot
	$tpl_login = new Template($tplc);		
	$tpl_login->set('username', $username);	
	$tpl_login->set('msg', $msg);
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