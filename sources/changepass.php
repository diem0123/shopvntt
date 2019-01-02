<?php

// kiem tra logged in
	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=login';
		$common->redirect_url($url_redidrect);
	}
	
	$idtype = 6;// default menu selected account
	//$idcat = $itech->input['idcat'];
	$idcat = 23;// default changepass
	$idcatsub = $itech->input['idcatsub'];
		
	$password_old = $itech->input['password_old'];
	$password = $itech->input['password'];
	$submit = $itech->input['submit'];
	$mess1 = "";
	
	if($submit != "")
	 {
		// check account duplicate
		$check_pass = $common->getInfo("consultant","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."' AND PASSWORD = '".hash('sha256',$password_old)."'");
		if($check_pass == ""){
			$mess1 = "M&#7853;t kh&#7849;u c&#361; kh&ocirc;ng &#273;&uacute;ng!";
		}
		else{
				// update db					
				$password = hash('sha256',$password);
					
				try {
					$ArrayData = array( 1 => array(1 => "PASSWORD", 2 => $password),										
										2 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))																		
									  );
							  
					$update_condit = array( 1 => array(1 => "ID_CONSULTANT", 2 => $_SESSION['ID_CONSULTANT']));
					$common->UpdateDB($ArrayData,"consultant",$update_condit);
					
					// redirect to manage account
					$url_redidrect='taikhoan-6-Xem-thong-tin-tai-khoan.html?idcat=22';
					$common->redirect_url($url_redidrect);
					
				} catch (Exception $e) {
						echo $e;
					}
	 
		}
	 }
     
	//header
	$pre = '';// get language
	$_SESSION['tab'] = 6;// Account	
	$name_title = "T&Agrave;I KHO&#7842;N";
	
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2	
	$temp_contents=& new Template('main_info');

	// get main content 
	$tpl1 =& new Template('changepass');				
	$tpl1->set('password', '');		
	$tpl1->set('mess1', $mess1);	
	$main_contents = $tpl1->fetch('changepass');
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idcat, $idcatsub highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_TYPE","IORDER");
	$field_name_other2 = array("ID_CAT","IORDER");
	$list_cat_menu = $print_2->GetRowMenu("common_cat"," ID_TYPE= '".$idtype."' ORDER BY IORDER ", "ID_CAT", "SNAME", $field_name_other1, "menu_ac_cat_rs", "common_cat_sub","", "ID_CAT_SUB", "SNAME", $field_name_other2, "menu_ac_catsub_rs",$pre, $idcat, $idcatsub);
	$temp_contents->set('list_cat_menu', $list_cat_menu);
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************

	// get intro top main content
	$tpl_intro =& new Template('intro_common');
	$tpl_intro->set('varintro', "");
	$intro = $tpl_intro->fetch('intro_common');
	
	$temp_contents->set('intro', $intro);
	$temp_contents->set('main_contents', $main_contents);
	$temp_contents->set('name_title', $name_title);
	
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

	$main->set('main2', $temp_contents); 


	$main->set('main3', "");
	$main->set('main4', "");
	$main->set('main5', "");

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