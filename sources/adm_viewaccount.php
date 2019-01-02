<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	$rolemn = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
	$roleinfomn = $common->getInfo("role_lookup","ROLE_ID ='".$rolemn['ROLE_ID']."'");
	 
     //main
     $main = & new Template('adm_viewaccount'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idc = $itech->input['idc'];
				
		
	// set permission	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_account', "V")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}	
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="v" && $idc) {
					$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$idc."'");
					$main->set('username', $cst['USER_NAME']);
					$chr = $common->getInfo("consultant_has_role","ID_CONSULTANT = '".$idc."'");
					if($roleinfomn['ROLE_ID'] ==1 && $chr['ROLE_ID'] == 1) $role_list = $print_2->GetDropDown($chr['ROLE_ID'], "role_lookup", "", "ROLE_ID", "NAME", "IORDER");
					else $role_list = $print_2->GetDropDown($chr['ROLE_ID'], "role_lookup", "ROLE_ID <>1", "ROLE_ID", "NAME", "IORDER");
					$main->set('role_list', $role_list);
					$rolecus = $common->getInfo("role_lookup","ROLE_ID ='".$chr['ROLE_ID']."'");
					if($rolecus['ROLE_TYPE'] != "Operation" && $roleinfomn['ROLE_TYPE'] != "Operation"){
						$dis = 'style="display: none;"';
					}
					else $dis = "";
					$main->set('dis', $dis);
					$main->set('rolename', $rolecus['NAME']);
					$main->set('consultantname', $cst['FULL_NAME']);
					$main->set('email', $cst['EMAIL']);
					$main->set('tel', $cst['TEL']);
					$main->set('website', $cst['WEBSITE']);
					$main->set('address', $cst['ADDRESS']);
					$main->set('mobi', $cst['MOBI']);
					$main->set('chatyahoo', $cst['CHAT_YAHOO']);
					$main->set('chatskype', $cst['CHAT_SKYPE']);
					$main->set('status', $cst['STATUS']);
										
					$gender_info = $common->getInfo("gender","ID_GENDER = '".$cst['GENDER']."'");
					$main->set('namegender', $gender_info['NAME']);
					
					if($cst['TITLE'] == 'Mr.') $s1 = "selected";
					elseif($cst['TITLE'] == 'Mrs.') $s2 = "selected";
					elseif($cst['TITLE'] == 'Ms.') $s3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					$main->set('s3', $s3);
					$main->set('mess1', "");
					$main->set('mess2', "");
					if($cst['AVATAR'] == "")
					$main->set('avatar', "noavatar.jpg");
					else 
					$main->set('avatar', $cst['AVATAR']);
					$main->set('idc', $idc);
					if($cst['EMAIL_NGANLUONG'] == "NO")
					$main->set('emailnganluong', "");
					else
					$main->set('emailnganluong', $cst['EMAIL_NGANLUONG']);
					if($cst['EMAIL_BAOKIM'] == "NO")
					$main->set('emailbaokim', "");
					else
					$main->set('emailbaokim', $cst['EMAIL_BAOKIM']);
					if($cst['EMAIL_ONEPAY'] == "NO")
					$main->set('emailonepay', "");
					else
					$main->set('emailonepay', $cst['EMAIL_ONEPAY']);
					
					$main->set('idc', $idc);

			echo $main->fetch("adm_viewaccount");
			exit;
		}

?>
