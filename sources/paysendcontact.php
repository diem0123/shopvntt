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
	
	$main_products= new Template($tplmain);
		  
	$temp_contents= new Template($tplct);	
	$pre = '';// get language
	
		$infomess = array();
		$idcom = 1;// get default company
		//$postby = $itech->input['postby'];
		$postby = 1;// get default info tomiki
		
		$datasub = "";
		$datamain = "";
		
		// get info buyer back from transfer procedure
		$fullname = @$_SESSION['fname'];
		$tel = @$_SESSION['ftel'];
		$email = @$_SESSION['femail'];
		$address = @$_SESSION['faddress'];
		$content = @$_SESSION['fcontent'];
		$province = @$_SESSION['fprovince'];
		$dist = @$_SESSION['fdist'];
		$list_provinces = $print_2->GetDropDown($province, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
		$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province."'" ,"ID_DIST", "NAME", "IORDER");

		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvod = "m_sendcontactpay_list_rs";
		else $tplvod = "sendcontactpay_list_rs";
		$fet_box =  new Template($tplvod);		
		$fet_box->set("fullname", $fullname);
		$fet_box->set("tel", $tel);
		$fet_box->set("email", $email);
		$fet_box->set("list_provinces", $list_provinces);
		$fet_box->set("list_dist", $list_dist);
		// check user loggedin
		if (isset($_SESSION['ID_CONSULTANT']) && $_SESSION['ID_CONSULTANT'] > 0){
		$fet_box->set("loggedin", 1);
		$_SESSION['emailexist'] = "";
		}
		else $fet_box->set("loggedin", 0);
		$fet_box->set("address", $address);
		if($content != ""){		
		$fet_box->set("content", $content);
		//$uri_content_decode = urldecode($content);
		//$b64_content_decode = base64_decode($uri_content_decode);
		//$fet_box->set("content", $b64_content_decode);
		}
		else
		$fet_box->set("content", "");
		$fet_box->set("idcom", $idcom);
		// check email exist to add account
		if(isset($_SESSION['emailexist']) && $_SESSION['emailexist'] != ""){
			$mess_existemail = "Email n&agrave;y &#273;&atilde; t&#7891;n t&#7841;i, vui l&ograve;ng ch&#7885;n email kh&aacute;c!";
			$fet_box->set("mess_existemail", $mess_existemail);	
			for($i=1;$i<=4;$i++){
				$fet_box->set("dl".$i, "style='display:table-row'");	
			}
			$fet_box->set("cked", "checked");
		}
		else{
			$mess_existemail = "";
			$fet_box->set("mess_existemail", $mess_existemail);
			for($i=1;$i<=4;$i++){
					$fet_box->set("dl".$i, "style='display:none'");	
				}
			$fet_box->set("cked", "");	
		}
		$fet_box->set("postby", $postby);
		$datasub = $fet_box->fetch($tplvod);
		
		// +++++++++ set cho main 1
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type_giohang']=='phone' || $_SESSION['dv_Type']=="phone") $tplvodl = "m_sendcontactpay_list";
		else $tplvodl = "sendcontactpay_list";
		
		$tpl1 =  new Template($tplvodl);		
		$tpl1->set('contactpay_list', $datasub);
		
		$temp_contents->set('main_order', $tpl1);															

	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc_mn, $idt_mn, $idprocat_mn, $ida_mn, $idsk_mn);
	
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