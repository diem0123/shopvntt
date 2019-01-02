<?php
/*
+--------------------------------------------------------------------------
|   Email: htktinternet@yahoo.com
+---------------------------------------------------------------------------
*/
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(15), "V")){
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
		
	 $del=$itech->input['del'];
	 if($del ==1) {
		 $iId=$itech->input['iId'];
		 $DB->query("DELETE FROM contact WHERE IID ='".$iId."'");					  
		 $print->redirect('index.php?act=cm_adm_contact', "Contact has been deleted successfully!",'adm_home');
	 }	
	 
	 // set header for operation		
	$_SESSION['tab'] = 6;// menu Email Marketing selected
	$header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_emk'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('cm_adm_contact');
	//$submenu2 = & new Template('submenu2_account'); 
	//$submenu2->set('sub2','');
	$main->set('submenu2',"");
	
    $pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);
	
     $main->set('seach_result',$print_2->getlist_contact("contact", 0));
	
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
