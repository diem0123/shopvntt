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

	$tempres=new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_info";
	$tplct = "m_main_orderpro";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "main_orderpro";
	}
	
	// Get data main 2
	$main_products= new Template($tplmain);
	// get content
	$temp_contents= new Template($tplct);	
	$pre = '';// get language
	$idcom =1; // set default for company
	// dta = 0 for display on web, 1: for display on email customer, $base_url: for path
	// getvieworderpro($idcom, 0, $base_url);
	// get info shoppng cart
	$data = $print_2->getvieworderpro($idcom, 0, '');
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvod = "m_vieworderpro";
	else $tplvod = "vieworderpro";
	
	$tpl1 =  new Template($tplvod);		
	$tpl1->set('viewcart_list_rs', $data);	
	$temp_contents->set('main_order', $tpl1);															

	$main_products->set('main_contents', $temp_contents);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone"){
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