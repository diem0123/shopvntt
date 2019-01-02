<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	// check permission
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(1), "V")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
			
	//check role type
	$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
	$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
	$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
	$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
	//check consultant incharge for company		
		//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
		// check permission com incharge owner
		if($roleinfo['ROLE_TYPE'] != "Operation"){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;
		}
	
	 //header
	$pre = '';// get language
	$_SESSION['tab'] = 1;// Account	
	
	$header = $common->getHeader('adm_header');
	
     //main
     $main = & new Template('adm_main_home'); 
     $main->set('seach_result','');
	 $main->set('statistics', $print->statistics());
	
     //footer
     $footer = & new Template('adm_footer');
	 $footer->set('statistics1', '');

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);     
     $tpl->set('main', $main);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('adm_home');
?>
