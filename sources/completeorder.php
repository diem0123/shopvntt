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
	//$pre = '';
    
	//header
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
	$tplct = "m_main_orderpro";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "main_orderpro";
	}
	
	$main_products=& new Template($tplmain);
		  
	$temp_contents=& new Template($tplct);	
	//$pre = '';// get language
			
		$idcom = 1;// get default company
		//$postby = $itech->input['postby'];
		$postby = 1;// get default info halamfashion		
		$datasub = "";
		$datamain = "";
		// process send mail order products
		$fet_box_main =  new Template('sendorder_list_rs_email');
		// get view order
		// dta = 0 for display on web, 1: for display on email customer, $base_url: for path
		// getvieworderpro($idcom, 0, $base_url);
		$data1 = $print_2->getvieworderpro($idcom, 1, '');			
		
		// get fee transfer and total payment real
		$fet_final =  new Template('update_total_fee_email');		
		$fet_final->set("fee_transfer", number_format(@$_SESSION['fee_transfer'],0,"","."));
		$fet_final->set("fee", number_format(@$_SESSION['fee'],0,"","."));
		$fet_final->set("sale", $_SESSION['sale']);
		$fet_final->set("totalpay_final", number_format(@$_SESSION['totalpay_final'],0,"","."));		
		
		$data_final = $fet_final->fetch("update_total_fee_email");
		
		// get info buyer		
		$fet_box2 =  new Template('view_sendcontactpay_list_rs_email');		
		$fet_box2->set("fullname", @$_SESSION['fname']);
		$fet_box2->set("tel", @$_SESSION['ftel']);
		$fet_box2->set("email", @$_SESSION['femail']);
		
		$provinceinfo = $common->getInfo("province","ID_PROVINCE = '".@$_SESSION['fprovince']."'");
		$fet_box2->set('province_name', $provinceinfo['NAME']);
		$distinfo = $common->getInfo("district","ID_DIST = '".@$_SESSION['fdist']."'");
		$fet_box2->set('dist_name', $distinfo['NAME']);
		
		$fet_box2->set("address", @$_SESSION['faddress']);
		if($_SESSION['fcontent'] != ""){
		$uri_content_decode = urldecode($_SESSION['fcontent']);// decode uri before
		$b64_content_decode = base64_decode($uri_content_decode);// continue decode base64		
		$fet_box2->set("content", $b64_content_decode);
		$comment_donhang = $b64_content_decode;// get info buyer contact
		}
		else{
		$fet_box2->set("content", "");
		$comment_donhang = "";
		}
		
		//$descrip_donhang = $print_2->doikhongdau($comment_donhang);
		//echo $descrip_donhang; exit;
			
		$data2 = $fet_box2->fetch("view_sendcontactpay_list_rs_email");
		// get info receiver
		$fet_box3 = & new Template('view_sendtransferpay_list_rs_email');		
		$fet_box3->set("fullname_get", @$_SESSION['fname_get']);
		$fet_box3->set("tel_get", @$_SESSION['ftel_get']);
		$fet_box3->set("email_get", @$_SESSION['femail_get']);
		
		$provinceinfo = $common->getInfo("province","ID_PROVINCE = '".@$_SESSION['fprovince_get']."'");
		$fet_box3->set('province_name_get', $provinceinfo['NAME']);
		$distinfo = $common->getInfo("district","ID_DIST = '".@$_SESSION['fdist_get']."'");
		$fet_box3->set('dist_name_get', $distinfo['NAME']);
		
		$fet_box3->set("address_get", @$_SESSION['faddress_get']);
		$transfer_get = $_SESSION['transfer_get'];
		if($transfer_get == 1) {
		$fet_box3->set("cked1", 'checked="checked"');
		$fet_box3->set("cked2", "");		
		}
		elseif($transfer_get == 2) {
		$fet_box3->set("cked2", 'checked="checked"');
		$fet_box3->set("cked1", "");
		}
		$fet_box3->set("cked", $transfer_get);
		// get content buu dien va phat nhanh
		// buu dien
		$idps1 = 1;
		$info_ct1 = $common->getInfo("type_transfer","ID_TRANSFER = '".$idps1."'");
		$ct1 = base64_decode($info_ct1['INFO']);
		$fet_box3->set("buudien", $ct1);
		// phat nhanh
		$idps2 = 2;
		$info_ct2 = $common->getInfo("type_transfer","ID_TRANSFER = '".$idps2."'");
		$ct2 = base64_decode($info_ct2['INFO']);
		$fet_box3->set("phatnhanh", $ct2);
		$data3 = $fet_box3->fetch("view_sendtransferpay_list_rs_email");
		
		// get procedure payment
		$pay_get = @$_SESSION['pay_get'];					
		$fet_box4 = & new Template('view_procedurepay_list_rs_email');
		if($pay_get != ""){
			for($i=1;$i<=7;$i++){						
				if($pay_get == $i) {
				$fet_box4->set("cked".$i, 'checked="checked"');
				$fet_box4->set("cked", $i);
				}
				else $fet_box4->set("cked".$i, "");
				//get content
				$info_ct1 = $common->getInfo("payments_lookup","ID_PROPAY = '".$i."'");
				$ct1 = base64_decode($info_ct1['INFO']);
				$fet_box4->set("content_pay".$i, $ct1);
			}		
		}
		else 
		$fet_box4->set("cked", 1);		
		$data4 = $fet_box4->fetch("view_procedurepay_list_rs_email");
		
		// Set all review
		// create or update code order getvieworderpro($idcom, $dta, $mail_sent='')
		// dta: 1: for dipslay view shopping cart on email customer else view on web
		// mail_sent = '': not insert code_order, =1 insert or update code_order
		$print_2->getvieworderpro($idcom, 1, 1);// $mail_sent=1
		// get info payment
		$pay_info = $common->getInfo("payments","CODE = '".@$_SESSION['payment_order']."'");
		
		$fet_box_main->set("fullname", @$_SESSION['fname']);
		$fet_box_main->set('paymentname', $pay_info['CODE']);
		$fet_box_main->set('CREATED_DATE', $common->datetimevn($pay_info['CREATED_DATE']));
		$fet_box_main->set('APPROVE', $pay_info['APPROVE']);
		$fet_box_main->set("preview_order", $data1.$data_final.$data2.$data3.$data4);
		
		// get hotline
		$hotline = $common->gethonhop(1);
		$fet_box_main->set("hotline", $hotline['SCONTENTSHORT']);
		
		$datasub_main = $fet_box_main->fetch("sendorder_list_rs_email");
		
		//echo $datasub_main;exit;
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvod = "m_completeorder_list_rs";
		else $tplvod = "completeorder_list_rs";
		
		$fet_box =  new Template($tplvod);			
		
		// send mail
		$code_order = @$_SESSION['payment_order'];
		$totalpay = @$_SESSION['totalpay'];
		$totalpay_final = @$_SESSION['totalpay_final'];
		$totalpay_final_onepay = $totalpay_final*100;// one pay get 2 number end as decimal
		//echo "code=".$code_order;
		$subject = @$_SESSION['fname']."- Don dat hang ".$code_order;
		$content= $datasub_main;		
		$emailfrom = $itech->vars['email_webmaster'];
		
		$namefrom = $INFO['name_webmaster'];
		$emailto = @$_SESSION['femail'];
		$nameto = @$_SESSION['fname'];
		$mail = new emailtemp();
		$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);		
		$error_tmk = $mail->sendmark($emailfrom,$namefrom,$emailfrom,$namefrom,$content,$subject);
		//$error='ok';
		if ($error=='ok'){
		$mess_complete = $lang['tl_msentcomplete'];					
			// update email content, procedure payment					
			$mail_encode = base64_encode($content);
			// get ma so giao dich cua khach hang
			$vpc_MerchTxnRef = date('YmdHis') . rand();
			$ArrayData2 = array( 1 => array(1 => "ID_PROPAY", 2 => $pay_get),
										2 => array(1 => "CONTENT_MAIL", 2 => $mail_encode),										
										3 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
										4 => array(1 => "STATUS", 2 => 'Active'),
										5 => array(1 => "TOTAL_PAY_DISC", 2 => @$_SESSION['totalpay_disc']),
										6 => array(1 => "FEE_TRANSFER", 2 => $_SESSION['fee_transfer']),
										7 => array(1 => "TOTAL_PAY_FINAL", 2 => @$_SESSION['totalpay_final']),
										8 => array(1 => "ID_TRANSFER", 2 => @$_SESSION['transfer_get']),
										9 => array(1 => "MERCHTXNREF", 2 => $vpc_MerchTxnRef)
										
									  );
												
								$update_condit2 = array( 1 => array(1 => "CODE", 2 => $code_order));
								$common->UpdateDB($ArrayData2,"payments",$update_condit2);
			
			// process payment with nganluong, baokim, onepay
			// $pay_get = 2: the ATM 20 ngan hang; = 4 ngan luong; 5: baokim
			// submit to unit process payment
			$fet_box->set("code_order", $code_order);
			$fet_box->set("totalpay", $totalpay);
			$fet_box->set("totalpay_final", $totalpay_final);
			$fet_box->set("totalpay_final_onepay", $totalpay_final_onepay);
			$fet_box->set("comment_donhang", $comment_donhang);
			// chuyen sang khong dau cho 123pay
			$descrip_donhang = $print_2->doikhongdau($comment_donhang);
			//echo $descrip_donhang; exit;
			$fet_box->set("descrip_donhang", $descrip_donhang);
			
			// set for onepay
			$fet_box->set("shipping_address", @$_SESSION['faddress_get']);			
			$fet_box->set("shipping_province", @$_SESSION['fdist_get']);			
			$fet_box->set("shipping_city", @$_SESSION['fprovince_get']);
			$fet_box->set("cus_phone", @$_SESSION['ftel_get']);
			$fet_box->set("cus_mail", @$_SESSION['femail_get']);			
			$fet_box->set("cus_username", @$_SESSION['useremail']);
			
			//$fet_box->set("idcom", $idcom);
			$fet_box->set("postby", $postby);
			// get info email payment
			$info_emailpay = $common->getInfo("payments_lookup","ID_PROPAY = '".$pay_get."'");		
			$fet_box->set("emailpay", $info_emailpay['EMAIL_PAY']);
			$fet_box->set("pay_get", $pay_get);
			$fet_box->set("merchant_onepay", $info_emailpay['MERCHANT']);
			$fet_box->set("accesscode_onepay", $info_emailpay['ACCESSCODE']);			
			$fet_box->set("vpc_MerchTxnRef", $vpc_MerchTxnRef);
			// 123pay
			if(isset($_SESSION['pay123']))
			$fet_box->set("bankCode", $_SESSION['pay123']);
			else
			$fet_box->set("bankCode", "123PAY");
			

			// empty shopping cart
			//unset($_SESSION['payment_order']);
		/*	
			unset($_SESSION['totalpay']);
			unset($_SESSION['totalpay_disc']);
			unset($_SESSION['totalpay_final']);
			unset($_SESSION['fee_transfer']);			
			unset($_SESSION['shoppingcart']);
			unset($_SESSION['shoppingcart_quantity']);
			*/

			}
		else 
		$mess_complete = $lang['tl_mnotsentcomplete'];
					
		$fet_box->set("mess_complete", $mess_complete);
		$datasub = $fet_box->fetch($tplvod);
		
		// +++++++++ set cho main 1
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvodl = "m_completeorder_list";
		else $tplvodl = "completeorder_list";
	
		$tpl1 = & new Template($tplvodl);		
		$tpl1->set('completeorder_list', $datasub);
		
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
	
	$main->set('main3', "");
	$main->set('main4', "");
	$main->set('main5', "");

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	
	$footer = $common->getFooter('footer');	
	

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