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
	//$pre = '';// get language
	
		$infomess = array();
		$idcom = 1;// get default company
		$postby = $itech->input['postby'];
		
		$datasub = "";
		$datamain = "";
		
		// get info contact receiver next
		if(isset($itech->input['fullname_get'])){
		$_SESSION['transfer_get'] = $itech->input['transfer_get'];
		$_SESSION['fname_get'] = $itech->input['fullname_get'];
		$_SESSION['ftel_get'] = $itech->input['tel_get'];
		$_SESSION['femail_get'] = $itech->input['email_get'];
		$_SESSION['faddress_get'] = $itech->input['address_get'];
		$_SESSION['fprovince_get'] = $itech->input['province_get'];
		$_SESSION['fdist_get'] = $itech->input['dist'];
		$_SESSION['fcontent_get'] = $itech->input['cus_contentencode_get'];// content has been encode
		}
		
		// get info buyer back from completeorder procedure
		$pay_get = @$_SESSION['pay_get'];		
			
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplvod = "m_procedurepay_list_rs";
		else $tplvod = "procedurepay_list_rs";
		$fet_box =  new Template($tplvod);
		if($pay_get != ""){
			for($i=1;$i<=7;$i++){						
				if($pay_get == $i) $fet_box->set("cked".$i, 'checked="checked"');
				else $fet_box->set("cked".$i, "");
			}		
		}
		else 
		$fet_box->set("cked1", 'checked="checked"');
		//checked pay123 mac dinh
		$pay123check = @$_SESSION['pay123'];
		if($pay123check == "123PCC"){
		$fet_box->set("cked_pay123b", "checked");
		$fet_box->set("cked_pay123a", "");
		}
		else{
		$fet_box->set("cked_pay123b", "");
		$fet_box->set("cked_pay123a", "checked");
		}
		
		$fet_box->set("idcom", $idcom);
		$fet_box->set("postby", $postby);
		// get content description		
		for($i=1;$i<=7;$i++){
		$info_ct1 = $common->getInfo("payments_lookup","ID_PROPAY = '".$i."'");
		$ct1 = base64_decode($info_ct1['INFO']);
		$fet_box->set("content_pay".$i, $ct1);
		}
		
		$datasub = $fet_box->fetch($tplvod);
		
		// +++++++++ set cho main 1
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' ||$_SESSION['dv_Type']=="phone") $tplvodl = "m_procedurepay_list";
		else $tplvodl = "procedurepay_list";
		$tpl1 =  new Template($tplvodl);		
		$tpl1->set('procedurepay_list', $datasub);
		
		$temp_contents->set('main_order', $tpl1);															
	
	// set intro on top
	// get intro top main content
	$tpl_intro = new Template('intro_viewcart');
	$idps = $_SESSION['tab'];
	$info_bn = $common->getInfo("banner","ID_PS = '".$idps."'");
	$tpl_intro->set('imgname', $info_bn['NAME_IMG']);
	$tpl_intro->set('info', $info_bn['INFO']);
	$intro = $tpl_intro->fetch('intro_viewcart');
	
	$main_products->set('intro', $intro);
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