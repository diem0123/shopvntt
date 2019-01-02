<?php

// kiem tra logged in
// chua kiem tra, bo sung sau
	// check shopping cart
	if(!isset($_SESSION['shoppingcart']) || $_SESSION['shoppingcart'] == "" ){
		$url_redidrect='thong-tin-gio-hang.html';
		$common->redirect_url($url_redidrect);
	}
	
	$idp = $itech->input['idp'];
	// check for get menu selected
	$idc_mn = $itech->input['idc'];
	$idt_mn = $itech->input['idt'];
	$idprocat_mn = $itech->input['idprocat'];
	$ida_mn = $itech->input['ida'];
	$idsk_mn = $itech->input['idsk'];
	if($idc_mn && !$idt_mn && (!$idprocat_mn && !$ida_mn && !$idsk_mn)) $name_query_string = "idc=".$idc_mn;
	elseif($idc_mn && $idt_mn && (!$idprocat_mn && !$ida_mn && !$idsk_mn)) $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn;
	elseif($idc_mn && $idt_mn && ($idprocat_mn || $ida_mn || $idsk_mn)){
		if($ida_mn){ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&ida=".$ida_mn; }
		elseif($idsk_mn){ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&idsk=".$idsk_mn; }
		else{ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&idprocat=".$idprocat_mn; }
	}
	
	// get language
	$pre = '';
    
	//header
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
	if( $_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_info";
	$tplct = "m_main_orderpro";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "main_orderpro";
	}
	
	$main_products= new Template($tplmain);
		  
	$temp_contents= new Template($tplct);	
	$pre = '';// get language
	
		$infomess = array();
		$idcom = 1;// get default company
		//$postby = $itech->input['postby'];
		$postby = 1;// get default info tomiki
		
		//$fullname = $itech->input['fullname'];
		// get info contact buyer next
		$_SESSION['emailexist'] = "";
		if(isset($itech->input['fullname'])){
			$_SESSION['fname'] = $itech->input['fullname'];
			$_SESSION['ftel'] = $itech->input['tel'];
			$_SESSION['femail'] = $itech->input['email'];
			$_SESSION['faddress'] = $itech->input['address'];
			$_SESSION['fprovince'] = $itech->input['province'];
			$_SESSION['fdist'] = $itech->input['dist'];
			$_SESSION['fcontent'] = $itech->input['cus_contentencode'];// content has been encode
			// khoi tao dong thoi tai khoan cho khach hang
			$emailcheck = $itech->input['email'];
			$addreg = $itech->input['addreg'];
			$password = $itech->input['passwordreg'];
			// check email exist
			$check_account = $common->getListValue("consultant","EMAIL = '".$email."'","ID_CONSULTANT");				
			if($addreg && $check_account != "") {
			// tro lai thong bao email ton tai
			$_SESSION['emailexist'] = $emailcheck;
			$url_redidrect='thong-tin-nguoi-mua.html';
			$common->redirect_url($url_redidrect);
							
			}
			elseif($addreg && $check_account == ""){
				$_SESSION['emailexist'] = "";
				//+++++++++++++++++++++++ khoi tao account
				// input db
				$gender =0;
				$fullname = $itech->input['fullname'];
				$tel = $itech->input['tel'];
				$email = $itech->input['email'];
				$password = $itech->input['passwordreg'];
				$status = 'Inactive';
				$address = $itech->input['address'];
				$username = $itech->input['email'];
				$province = $itech->input['province'];
				$dist = $itech->input['dist'];
				$idrole = 5;// default customer normal 
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
										
									  );
							  
					$id_const = $common->InsertDB($ArrayData,"consultant");
					
					$ArrayData2 = array( 1 => array(1 => "ROLE_ID", 2 => $idrole),
										2 => array(1 => "ID_CONSULTANT", 2 => $id_const)																			
									  );
					
					$idc_hasrole = $common->InsertDB($ArrayData2,"consultant_has_role");
					// send mail register
					$subject = "Dang ky tai khoan thanh cong";
					$tplcontent = & new Template('tpl_active_account');      					
					$tplcontent->set('fullname', $fullname);					
					$content= $tplcontent->fetch('tpl_active_account');
					
					$emailfrom = $itech->vars['email_webmaster'];
					
					$namefrom = $INFO['name_webmaster'];
					$emailto = $email;
					$nameto = "";
					$mail = new emailtemp();
					$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);					
					
				} catch (Exception $e) {
						echo $e;
					}				
							
			}
			
			
		}
		$datasub = "";
		$datamain = "";		
		
		// get info receiver back
		$transfer_get = $_SESSION['transfer_get'];
		if($transfer_get == 1) {$cked1 = 'checked="checked"'; $cked2 = "";}
		elseif($transfer_get == 2) {$cked2 = 'checked="checked"'; $cked1 = "";}
		else {$cked1 = 'checked="checked"'; $cked2 = "";}
		$fullname_get = @$_SESSION['fname_get'];
		$tel_get = @$_SESSION['ftel_get'];
		$email_get = @$_SESSION['femail_get'];
		$address_get = @$_SESSION['faddress_get'];
		$province_get = @$_SESSION['fprovince_get'];
		$dist_get = @$_SESSION['fdist_get'];
		$list_provinces = $print_2->GetDropDown($province_get, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
		$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province_get."'" ,"ID_DIST", "NAME", "IORDER");
		//$content_get = @$_SESSION['fcontent_get'];			
		
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvod = "m_sendtransferpay_list_rs";
		else $tplvod = "sendtransferpay_list_rs";
	
		$fet_box =  new Template($tplvod);		
		$fet_box->set("cked1", $cked1);
		$fet_box->set("cked2", $cked2);
		$fet_box->set("fullname_get", $fullname_get);
		$fet_box->set("tel_get", $tel_get);
		$fet_box->set("email_get", $email_get);
		$fet_box->set("address_get", $address_get);
		$fet_box->set("list_provinces", $list_provinces);
		$fet_box->set("list_dist", $list_dist);
		// get content buu dien va phat nhanh
		// buu dien
		$idps1 = 1;
		$info_ct1 = $common->getInfo("type_transfer","ID_TRANSFER = '".$idps1."'");
		$ct1 = base64_decode($info_ct1['INFO']);
		$fet_box->set("buudien", $ct1);
		// phat nhanh
		$idps2 = 2;
		$info_ct2 = $common->getInfo("type_transfer","ID_TRANSFER = '".$idps2."'");
		$ct2 = base64_decode($info_ct2['INFO']);
		$fet_box->set("phatnhanh", $ct2);
		
		//if($content_get != ""){		
		//$fet_box->set("content_get", $content_get);
		//$uri_content_decode = urldecode($content);
		//$b64_content_decode = base64_decode($uri_content_decode);
		//$fet_box->set("content", $b64_content_decode);
		//}
		//else
		//$fet_box->set("content_get", "");
		//$fet_box->set("idcom", $idcom);
		//$fet_box->set("postby", $postby);
		$datasub = $fet_box->fetch($tplvod);
		
		// +++++++++ set cho main 1
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvodl = "m_sendtransferpay_list";
		else $tplvodl = "sendtransferpay_list";
		
		$tpl1 =  new Template($tplvodl);		
		$tpl1->set('transferpay_list', $datasub);
		// get info for check receiver same to buyer
		$tpl1->set('fullname', $_SESSION['fname']);
		$tpl1->set('tel', $_SESSION['ftel']);
		$tpl1->set('email', $_SESSION['femail']);
		$tpl1->set('address', $_SESSION['faddress']);
		$tpl1->set('province', $_SESSION['fprovince']);
		$tpl1->set('cus_dist', $_SESSION['fdist']);
		
		$temp_contents->set('main_order', $tpl1);															

	
	$main_products->set('main_contents', $temp_contents);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone"){
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "m_menupro_cat_rs",  $field_name_other2, "m_menupro_catsub_rs", $field_name_other3, "m_menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "m_list_menur_pro";
	} else {
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "list_menur_pro";
	}
	
	$tpl_menur_pro= new Template($tpll);
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$main_products->set('list_menur_pro', $tpl_menur_pro);
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_products->set('logo_partner', $logo_partner);
	
	// Get main 2
	$main->set('main2', $main_products);  	 

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	

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