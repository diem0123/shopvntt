<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	$cat = $itech->input['cat'];
	$fnum = $itech->input['CKEditorFuncNum'];
	$nameCKEditor = $itech->input['CKEditor'];
	$langCode = $itech->input['langCode'];
	$_SESSION['fnum'] = $fnum;
	$_SESSION['nameCKEditor'] = $nameCKEditor;
	$_SESSION['langCode'] = $langCode;

	// set language
	//$pre = "";
	
	
     //main
     $main = & new Template('adm_listfile');

			
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

	$tpl_sub = & new Template('adm_listfile_rs'); 
	$tpl_sub->set('sub','');
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);
	$base_url = $INFO['roothttp'];
	$base_nohttp = $INFO['rootnohttp'];
	$main->set('base_url', $base_url);
	$main->set('base_nohttp', $base_nohttp);
	$main->set('list_files', $tpl_sub);
	if(isset($_SESSION['selecf']) && $_SESSION['selecf'] == 1)
	$main->set('slfile', 1);
	else
	$main->set('slfile', 0);

    echo $main->fetch('adm_listfile');
?>
