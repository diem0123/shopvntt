<?php

// kiem tra logged in
	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=login';
		$common->redirect_url($url_redidrect);
	}
	
	$idtype = 6;// default menu selected account
	//$idcat = $itech->input['idcat'];
	$idcat = 22;// default view_account
	$idcatsub = $itech->input['idcatsub'];
	
	$username = $itech->input['email'];
	$user_member = $itech->input['user_member'];
	$password = $itech->input['password'];
	$email = $itech->input['email'];
	$fullname = $itech->input['fullname'];
	$tel = $itech->input['tel'];
	$address = $itech->input['address'];
	$province = $itech->input['province'];
	if($province=="") $province = 0;
	$country = $itech->input['country'];
	if($country=="") $country = 0;
	$dist = $itech->input['dist'];
	if($dist=="") $dist = 0;
	//$idrole = 5;// default customer normal
	$gender = $itech->input['gender'];// gioi tinh
	if($gender=="") $gender = 0;
	//$status = "Inactive";// default, user need activation in email
	$submit = $itech->input['submit'];
	$mess1 = "";
	$mess0 = "";
	
	if($submit != "")
	 {
		// check account duplicate
		$check_account = $common->getListValue("consultant","EMAIL = '".$email."' AND ID_CONSULTANT <> '".$_SESSION['ID_CONSULTANT']."'","ID_CONSULTANT");
		//echo $check_account;
		$check_user_member = $common->getListValue("consultant","USER_MEMBER = '".$user_member."' AND ID_CONSULTANT <> '".$_SESSION['ID_CONSULTANT']."'","ID_CONSULTANT");
		if($check_account != "" || $check_user_member != ""){
			if($check_user_member != "" )
			$mess0 = $lang['exist_usermember'];
			if($check_account != "")
			$mess1 = $lang['exist_email'];
		}
		else{
		$infomess = array();
		$checkfile = true;
		
		// get temp path to file
		
		$tmp_name = $_FILES['file_ul']['name'];
		$file_ul = $_FILES['file_ul']['name'];
		$pathimg = $itech->vars['pathimg'];
		
		// path file upload
		$dir_upload = $pathimg."/avatar/";
		// if file empty or upload failed
		$filesucess = "";
		$mess2 = ""; // check file upload
		
			// check if file upload not empty
			if($file_ul != "")	{
				if($file_ul!="" && !$print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG")){			
				$file_ul ="";
				$checkfile = false;
				$mess2 = $lang['err_upload1'];
				}
				elseif($file_ul!="" && $print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG"))
				{
						$file_input_tmp = $_FILES['file_ul']['tmp_name'];				
						$prefix = time();
						$checkfile = true;
						//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
				}		
			}
			// if checkupload not successfull or email is exist return form add or edit
			if($mess2 != "" ){									
					$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
					if($cst['AVATAR'] == "")
					$avatar = "noavatar.jpg";
					else 
					$avatar = $cst['AVATAR'];											

				//echo $checkupload[0];
			} // end checkupload
			else{
			
				if($file_ul != "" && $checkfile){
				// upload receive file
				// file2 = $file_name la file goc chua resize
				
					$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
						if($file2 != ""){						
						// resize image file 1 for thumbnail													
								//$name1 = "small_".time()."_".rand()."_".$name_thumb[0];
								$name1 = "avatar_".$file2;
								// process get thumbnail resize image
								$picname = $file2;
								$pic_small = $name1;
								$origfolder = "avatar";							
								$width = $itech->vars['wavatar'];
								$height = $itech->vars['havatar'];
								$quality = $itech->vars['qualityavatar'];
								$check_thumb = $print_2->makethumb($picname, $pic_small, $origfolder, $width, $height, $quality);
								// if resize successfully
								if($check_thumb) {
								$file1 = $pic_small;
								// check exist file old image on directory
									if (file_exists($dir_upload.$file2) && file_exists($dir_upload.$pic_small)) {
										unlink($dir_upload.$file2);					
									}
								}
								else $file1 = $file2;

							$filesucess = $file1;
							
							//echo "upload successfully!";
						}
						else {
							  echo $lang['err_upload3'];
							  exit;
						}

				}
			
					// check exist file images
					$cs = $common->getInfo("consultant","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
					$old_avatar = $cs['AVATAR'];
					$password_old = $cs['PASSWORD'];
					if($password == "") $password = $password_old;
					else $password = hash('sha256',$password);
					if($filesucess == ""){
					$filesucess = $old_avatar;
					$file1_check = false;
					}
					else $file1_check = true;					
						
					try {
						$ArrayData = array( 1 => array(1 => "GENDER", 2 => $gender),
											2 => array(1 => "FULL_NAME", 2 => $fullname),
											3 => array(1 => "TEL", 2 => $tel),
											4 => array(1 => "EMAIL", 2 => $email),
											5 => array(1 => "PASSWORD", 2 => $password),										
											6 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
											7 => array(1 => "ADDRESS", 2 => $address),										
											8 => array(1 => "USER_NAME", 2 => $username),
											9 => array(1 => "ID_PROVINCE", 2 => $province),
											10 => array(1 => "ID_DIST", 2 => $dist),
											11 => array(1 => "AVATAR", 2 => $filesucess),
											12 => array(1 => "USER_MEMBER", 2 => $user_member),
											13 => array(1 => "COUNTRIES_ID", 2 => $country)
										  );
								  
						$update_condit = array( 1 => array(1 => "ID_CONSULTANT", 2 => $_SESSION['ID_CONSULTANT']));
						$common->UpdateDB($ArrayData,"consultant",$update_condit);
						
						// check remove old image
						if($file1_check && $old_avatar != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_avatar)) {
								unlink($dir_upload.$old_avatar);					
							} 
						}
						
						// redirect to manage account
						$url_redidrect='thong-tin-tai-khoan.html';
						$common->redirect_url($url_redidrect);
						
					} catch (Exception $e) {
							echo $e;
						}
			}
		}
	 }
	 else {
	
		// get info user
		$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
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
		if($cst['AVATAR'] == "")
		$avatar = "noavatar.jpg";
		else 
		$avatar = $cst['AVATAR'];
		
		/*
		$province_info = $common->getInfo("province","ID_PROVINCE = '".$province."'");
		$dist_info = $common->getInfo("district","ID_DIST = '".$dist."'");
		$gender_info = $common->getInfo("gender","ID_GENDER = '".$gender."'");
		*/
	
	}
	
	$list_provinces = $print_2->GetDropDown($province, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
	$list_country = $print_2->GetDropDown($country, "countries","" ,"countries_id", "countries_name", "c_oder DESC");
	$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province."'" ,"ID_DIST", "NAME", "IORDER");
	$list_gender = $print_2->GetDropDown($gender, "gender","" ,"ID_GENDER", "NAME".$pre, "IORDER");
	
	
     
	//header
	//$pre = '';// get language
	$_SESSION['tab'] = 1;// Account
	//$name_title = "T&Agrave;I KHO&#7842;N";
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="thong-tin-tai-khoan.html" >T&Agrave;I KHO&#7842;N</a><span class="arrow"><span></span></span></span>';
	$lkcat = '<a href="thong-tin-tai-khoan.html" >'.$lang['tl_tkhoan'].'</a>';
	$lkcat = " <span>></span> ".$lkcat;
	
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
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
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") {
	$tplmain = "m_main_info";
	$tplc = "m_edit_account";
	}
	else {
	$tplmain = "main_info";
	$tplc = "edit_account";
	}
	
	$main_info=& new Template($tplmain);
	
	
	// get main content 
	$tpl1 =& new Template($tplc);			
	$tpl1->set('email', $email);
	$tpl1->set('user_member', $user_member);
	$tpl1->set('password', '');	
	$tpl1->set('fullname', $fullname);
	$tpl1->set('tel', $tel);
	$tpl1->set('address', $address);
	$tpl1->set('avatar', $avatar);
	$tpl1->set('list_provinces', $list_provinces);
	$tpl1->set('list_country', $list_country);
	$tpl1->set('list_dist', $list_dist);
	$tpl1->set('list_gender', $list_gender);
	$tpl1->set('mess0', $mess0);
	$tpl1->set('mess1', $mess1);
	$tpl1->set('mess2', $mess2);	
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
	
	// get hot news		
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
	
	

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$footer = & new Template('m_footer');	
	else
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	$footer->set('todayaccess', $print->get_today());
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
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>