<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(7), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
	$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
	$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
	
     //main
     $main = & new Template('addnick'); 	 
		$idp = $itech->input['idp'];
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idc = $itech->input['idc'];
		$idnick = $itech->input['idnick'];
		
		$namedes = $itech->input['namedes'];
		$nickname = $itech->input['nickname'];
		$typechat = $itech->input['typechat'];		
		$status = $itech->input['status'];
		$iorder = $itech->input['iorder'];
		
		$sl1 = "";
		$sl2 = "";		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idnick) {
					$list_consultant = $print_2->GetDropDown("", "consultant","STATUS <> 'Deleted' AND ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$main->set('list_consultant', $list_consultant);
					$main->set('namedes', "");
					$main->set('nickname', "");
					$main->set('typechat', "");					
					$main->set('sl1', "");
					$main->set('sl2', "selected");
					$main->set('iorder', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m nick chat");
					$main->set('ac', "a");
					$main->set('idnick', "");

			echo $main->fetch("addnick");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idnick) {
					$catinfo = $common->getInfo("consultant_has_chat","CONSULTANT_CHAT_ID = '".$idnick."'");
					$list_consultant = $print_2->GetDropDown($catinfo['ID_CONSULTANT'], "consultant","STATUS <> 'Deleted' AND ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$main->set('list_consultant', $list_consultant);
					$main->set('namedes', $catinfo['NAME']);
					$main->set('nickname', $catinfo['NICK_NAME']);
					if($catinfo['TYPECHAT'] == 'YAHOO') $s1 = "selected";
					elseif($catinfo['TYPECHAT'] == 'SKYPE') $s2 = "selected";
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					
					if($catinfo['MESSENGER_ON'] == 'Active') $sl1 = "selected";
					elseif($catinfo['MESSENGER_ON'] == 'Inactive') $sl2 = "selected";					
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('iorder', $catinfo['IORDER']);
						
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh nick chat");
					$main->set('ac', "e");
					$main->set('idnick', $idnick);

			echo $main->fetch("addnick");
			exit;
		}		
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idnick) {

		// Begin input data
		// add nick
				try {
					$ArrayData = array( 1 => array(1 => "ID_CONSULTANT", 2 => $idc),
										2 => array(1 => "NAME", 2 => $namedes),
										3 => array(1 => "NICK_NAME", 2 => $nickname),
										4 => array(1 => "TYPECHAT", 2 => $typechat),
										5 => array(1 => "MESSENGER_ON", 2 => $status),
										6 => array(1 => "IORDER", 2 => $iorder)										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"consultant_has_chat");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idnick) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "ID_CONSULTANT", 2 => $idc),
										2 => array(1 => "NAME", 2 => $namedes),
										3 => array(1 => "NICK_NAME", 2 => $nickname),
										4 => array(1 => "TYPECHAT", 2 => $typechat),
										5 => array(1 => "MESSENGER_ON", 2 => $status),
										6 => array(1 => "IORDER", 2 => $iorder)										
									  );
							  					
					$update_condit = array( 1 => array(1 => "CONSULTANT_CHAT_ID", 2 => $idnick));
					$common->UpdateDB($ArrayData,"consultant_has_chat",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="s"  && $idp) {

		// Begin update data
		$infomess = array();
		$catinfo = $common->getInfo("consultant_has_chat","CONSULTANT_CHAT_ID = '".$idp."'");
		if($catinfo['MESSENGER_ON'] == 'Active') $status = "Inactive";
		elseif($catinfo['MESSENGER_ON'] == 'Inactive') $status = "Active";
				try {
					$ArrayData = array(
										1 => array(1 => "MESSENGER_ON", 2 => $status)																	
									  );
							  					
					$update_condit = array( 1 => array(1 => "CONSULTANT_CHAT_ID", 2 => $idp));
					$common->UpdateDB($ArrayData,"consultant_has_chat",$update_condit);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";

				} catch (Exception $e) {
						//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng!";
					}
					
			echo json_encode($infomess);
			exit;
	
	}

?>
