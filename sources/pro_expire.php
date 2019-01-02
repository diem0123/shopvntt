<?php

// kiem tra logged in
	

	// get language
	$pre = '';
    
	//header
	$_SESSION['tab'] = 6; // set menu active: Account
	$header = $common->getHeader('header');
	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2	
	
	$temp_contents=& new Template('main_products');
	// check conditional for display menu with ID_TYPE	
	$pre = '';// get language
	// get main content login
	$info_pro = $common->getInfo("products","ID_PRODUCT = '".$_SESSION['pro_idp']."' AND STATUS = 'Active'");
	$tpl_login =& new Template('proexpire_mess');		
	$tpl_login->set('mess1', "");//$_SESSION['mess_expire1']
	$tpl_login->set('mess2', "");//$_SESSION['mess_expire2']
	$tpl_login->set('product_name',$info_pro['PRODUCT_NAME']);
	
	$main_contents = $tpl_login->fetch('proexpire_mess');

	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk);
	
	$temp_contents->set('list_cat_menu', $list_cat_menu);
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************

	
	// get intro top main content
	$tpl_intro =& new Template('intro_common');
	$idps = $_SESSION['tab'];
	$info_bn = $common->getInfo("banner","ID_PS = '".$idps."'");
	$tpl_intro->set('imgname', $info_bn['NAME_IMG']);
	$tpl_intro->set('info', $info_bn['INFO']);
	$intro = $tpl_intro->fetch('intro_common');
	
	$temp_contents->set('intro', $intro);
	$temp_contents->set('main_contents', $main_contents);
	
	// get hot products
	$condit_sphot = "ID_CATEGORY = 1 AND STATUS = 'Active'";
	$condit_spchay = "ID_CATEGORY = 1 AND COUNT_BUY > 0 AND STATUS = 'Active'";
	$condit_spthich = "ID_CATEGORY = 1 AND FAVORITES > 0 AND STATUS = 'Active'";
	$condit_spgif = "ID_CATEGORY = 1 AND GIF = 1 AND STATUS = 'Active'";
	$sphot = $common->CountRecord("products",$condit_sphot,"ID_PRODUCT");
	$spchay = $common->CountRecord("products",$condit_spchay,"ID_PRODUCT");
	$spthich = $common->CountRecord("products",$condit_spthich,"ID_PRODUCT");
	$spgif = $common->CountRecord("products",$condit_spgif,"ID_PRODUCT");
	$temp_contents->set('sphot', $sphot);
	$temp_contents->set('spchay', $spchay);
	$temp_contents->set('spthich', $spthich);
	$temp_contents->set('spgif', $spgif);
	
	// get list chat
	$list_chat = $print->getNickchat();
	$temp_contents->set('list_chat', $list_chat);

	$main->set('main2', $temp_contents); 	 

	//footer
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	echo $tpl->fetch('home');
?>