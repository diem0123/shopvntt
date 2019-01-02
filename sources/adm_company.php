<?php
	if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	// check permission
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_company', "V")){
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
	//$pre = '';// get language
	$_SESSION['tab'] = 2;// Account	
	
	$header = $common->getHeader('adm_header');
	
	$submenu = & new Template('submenu_company'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('adm_cominfo'); 
	 $catinfo = $common->getInfo("company","ID_COM = 1");
     $main->set('comname',$catinfo['COM_NAME'.$pre]);
	 $main->set('shortname',$catinfo['SHORT_NAME'.$pre]);
	 $main->set('logoname', $catinfo['LOGO']);
	 $main->set('email', $catinfo['EMAIL']);
	 $main->set('tel', $catinfo['TEL']);
	 $main->set('fax', $catinfo['FAX']);
	 $main->set('mobi', $catinfo['MOBI']);
	 $main->set('skype', $catinfo['SKYPE']);
	 $main->set('yahoo', $catinfo['YAHOO']);
	 $main->set('website', $catinfo['WEBSITE']);
	 $main->set('address', $catinfo['ADDRESS'.$pre]);
	 //$main->set('content', $catinfo['DESCRIPTION']);
	 
	 //$submenu2 = & new Template('submenu2_company'); 
     //$submenu2->set('comname','');
	 $main->set('submenu2',"");
	
     //footer
     $footer = & new Template('adm_footer');
	 $footer->set('statistics1', '');

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);     
     $tpl->set('submenu', $submenu);
	 $tpl->set('main', $main);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('adm_home');
?>
