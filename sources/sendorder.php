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
	$main =  new Template('main');      

	$tempres= new Template('main1');	  
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
	
	$main_products= new Template($tplmain);
		  
	$temp_contents= new Template($tplct);	
	$pre = '';// get language
	
		$infomess = array();
		$idcom = 1;// get default company
		//$postby = $itech->input['postby'];
		$postby = 1;// get default info tomiki
		
		$datasub = "";
		$datamain = "";
		
		if(isset($itech->input['pay']))
		$_SESSION['pay_get'] = $itech->input['pay'];
		
		if(isset($itech->input['pay123']))
		$_SESSION['pay123'] = $itech->input['pay123'];		
		
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") {
		$tplvod = "m_sendorder_list_rs";
		$viewphone = 0;
		}
		else {
		$tplvod = "sendorder_list_rs";
		$viewphone = 1;
		}
		$fet_box =  new Template($tplvod);
		// get view order
		// dta = 0 for display on web, 1: for display on email customer, $base_url: for path
		// getvieworderpro($idcom, 0, $base_url);
		$_SESSION['sod'] = 1;// de nhan biet truong hop view ko co title don hang
		$data1 = $print_2->getvieworderpro($idcom, $viewphone, '');
		
		// process fee transfer
		$fee_transfer = 0;		
		// object: user normal and user reseller the same (recaculator in admin for reseller)
		// check total money order to get fee transfer (tong gia tri don hang tinh theo gia goc)
		if(@$_SESSION['totalpay'] < 500000) $id_money_trans = 1;
		elseif(@$_SESSION['totalpay'] >= 500000 && @$_SESSION['totalpay'] <= 1000000) $id_money_trans = 2;
		elseif(@$_SESSION['totalpay'] > 1000000) $id_money_trans = 3;		
		
		// check type user loggedin
		if (isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] != "") {		
			// get consultant info (get username for onepay)
			$cst_info = $common->getInfo("consultant","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
			$_SESSION['useremail'] = $cst_info['USER_NAME'];
			//check role type
			$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
			$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");			
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				// get fee follow province + id_transfer + type_account
				//$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = '".$roleinfo['ROLE_ID']."' AND ID_PROVINCE ='".@$_SESSION['fprovince_get']."' AND ID_DIST ='".@$_SESSION['fdist_get']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".@$_SESSION['transfer_get']."'");
				// user normal and user reseller the same (recaculator in admin for reseller)
				$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = 5 AND ID_PROVINCE ='".@$_SESSION['fprovince_get']."' AND ID_DIST ='".@$_SESSION['fdist_get']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".@$_SESSION['transfer_get']."'");
				if($fee_info == ""){
					$fee_transfer =	100000001;
					$fee = 100000001;
					$sale = 100000001;									
				}
				else{
					// check transfer ATM direct
					if($_SESSION['pay_get'] == 1){
						$fee_transfer = $fee_info['FEE'] - ($fee_info['FEE']*$fee_info['PERCENT']/100);
						$fee = $fee_info['FEE'];
						$sale = $fee_info['PERCENT'];
					}
					else {
						$fee_transfer = $fee_info['FEE'];
						$fee = $fee_info['FEE'];
						$sale = 0;
					}
				}
				
			}			
		}
		// for not login then default type_account = 5: account normal
		else {
		
			// get consultant info (get username for onepay)
			$_SESSION['useremail'] = @$_SESSION['femail'];
			// get fee follow province + id_transfer + type_account
				//$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = 5 AND ID_PROVINCE ='".@$_SESSION['fprovince_get']."' AND ID_TRANSFER = '".@$_SESSION['transfer_get']."'");
				$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = 5 AND ID_PROVINCE ='".@$_SESSION['fprovince_get']."' AND ID_DIST ='".@$_SESSION['fdist_get']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".@$_SESSION['transfer_get']."'");				
				// check transfer ATM direct
				if($fee_info == ""){
				//echo "tinh=".@$_SESSION['fprovince_get']."-quan=".@$_SESSION['fdist_get']."-khoan tien=".$id_money_trans."-van chuyen=".@$_SESSION['transfer_get'];
					$fee_transfer =	100000001;
					$fee = 100000001;
					$sale = 100000001;									
				}
				else{
				echo "phi=".$fee_info['FEE'];
					if($_SESSION['pay_get'] == 1){
						$fee_transfer = $fee_info['FEE'] - ($fee_info['FEE']*$fee_info['PERCENT']/100);
						$fee = $fee_info['FEE'];
						$sale = $fee_info['PERCENT'];
					}
					else {
						$fee_transfer = $fee_info['FEE'];
						$fee = $fee_info['FEE'];
						$sale = 0;
					}
				}	
		}
		
		// get info total final
		if(isset($_SESSION['totalpay_disc']) && $_SESSION['totalpay_disc'] != ""){		
			if($fee_transfer == 100000001) $totalpay_final = 100000001;
			else $totalpay_final = $_SESSION['totalpay_disc'] + $fee_transfer;		
		}
		else $totalpay_final = 0;
		
		$_SESSION['totalpay_final'] = $totalpay_final;
		$_SESSION['fee_transfer'] = $fee_transfer;
		$_SESSION['fee'] = $fee;
		$_SESSION['sale'] = $sale;
		
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplfl = "m_totalpay_final_list";
		else $tplfl = "totalpay_final_list";
		
		$fet_final =  new Template($tplfl);
		if($fee_transfer == 100000001){
		$fet_final->set("fee_transfer", $fee_transfer);
		$fet_final->set("fee", $fee);
		$fet_final->set("sale", $sale);
		$fet_final->set("totalpay_final", $totalpay_final);		
		}
		else{
		$fet_final->set("fee_transfer", number_format($fee_transfer,0,"","."));
		$fet_final->set("fee", number_format($fee,0,"","."));
		$fet_final->set("sale", $sale);
		$fet_final->set("totalpay_final", number_format($totalpay_final,0,"","."));
		}
		
		$data_final = $fet_final->fetch($tplfl);

		// get info buyer
//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplvs = "m_view_sendcontactpay_list_rs";
		else $tplvs = "view_sendcontactpay_list_rs";
		
		$fet_box2 =  new Template($tplvs);		
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
		}
		else
		$fet_box2->set("content", "");
		$data2 = $fet_box2->fetch($tplvs);
		// get info receiver
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplvsf = "m_view_sendtransferpay_list_rs";
		else $tplvsf = "view_sendtransferpay_list_rs";
		
		$fet_box3 =  new Template($tplvsf);		
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
		$data3 = $fet_box3->fetch($tplvsf);
		
		// get procedure payment
		$pay_get = @$_SESSION['pay_get'];
		
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplpd = "m_view_procedurepay_list_rs";
		else $tplpd = "view_procedurepay_list_rs";
		
		$fet_box4 =  new Template($tplpd);
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
		$data4 = $fet_box4->fetch($tplpd);
		
		// Set all review
		
		$fet_box->set("preview_order", $data1.$data_final.$data2.$data3.$data4);
		

		$fet_box->set("idcom", $idcom);
		$fet_box->set("postby", $postby);
		// get hotline
		$hotline = $common->gethonhop(1);
		$fet_box->set("hotline", $hotline['SCONTENTSHORT']);
		
		$datasub = $fet_box->fetch($tplvod);
		
		// +++++++++ set cho main 1
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplvodl = "m_sendorder_list";
		else $tplvodl = "sendorder_list";
		$tpl1 =  new Template($tplvodl);		
		$tpl1->set('sendorder_list', $datasub);
		
		$temp_contents->set('main_order', $tpl1);															
	
	$main_products->set('main_contents', $temp_contents);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone"){
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