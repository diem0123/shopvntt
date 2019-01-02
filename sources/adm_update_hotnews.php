<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(9), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
	$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
	$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
	
     //main
     $main = & new Template('addhotnews'); 	 		
		$idp = $itech->input['idp'];
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idhot = $itech->input['idhot'];		
		
		$titlename = $itech->input['titlename'];
		$idn = $itech->input['idn'];		
		$status = $itech->input['status'];
		$iorder = $itech->input['iorder'];
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idhot) {					
					$main->set('titlename', "");
					$main->set('idn', "");								
					$main->set('sl1', "selected");
					$main->set('sl2', "");
					$main->set('sl3', "");
					$main->set('iorder', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m Hot news");
					$main->set('ac', "a");
					$main->set('idhot', "");

			echo $main->fetch("addhotnews");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idhot) {
					$catinfo = $common->getInfo("hotnews","IDHOT = '".$idhot."'");					
					$main->set('titlename', $catinfo['STITLE']);
					$main->set('idn', $catinfo['ID_COMMON']);					
					
					if($catinfo['STATUS'] == 'Active') $sl1 = "selected";
					elseif($catinfo['STATUS'] == 'Inactive') $sl2 = "selected";
					else $sl3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('iorder', $catinfo['IORDER']);
						
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh hot news");
					$main->set('ac', "e");
					$main->set('idhot', $idhot);

			echo $main->fetch("addhotnews");
			exit;
		}		
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idhot) {

		// Begin input data
		// add nick
				try {
					$ArrayData = array( 1 => array(1 => "STITLE", 2 => $titlename),
										2 => array(1 => "ID_COMMON", 2 => $idn),
										3 => array(1 => "IORDER", 2 => $iorder),
										4 => array(1 => "STATUS", 2 => $status)																		
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"hotnews");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idhot) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "STITLE", 2 => $titlename),
										2 => array(1 => "ID_COMMON", 2 => $idn),
										3 => array(1 => "IORDER", 2 => $iorder),
										4 => array(1 => "STATUS", 2 => $status)																		
									  );
							  					
					$update_condit = array( 1 => array(1 => "IDHOT", 2 => $idhot));
					$common->UpdateDB($ArrayData,"hotnews",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="s"  && $idp) {

		// Begin update data
		$infomess = array();
		$catinfo = $common->getInfo("hotnews","IDHOT = '".$idp."'");
		if($catinfo['STATUS'] == 'Active') $status = "Inactive";
		elseif($catinfo['STATUS'] == 'Inactive') $status = "Active";
				try {
					$ArrayData = array(
										1 => array(1 => "STATUS", 2 => $status)																	
									  );
							  					
					$update_condit = array( 1 => array(1 => "IDHOT", 2 => $idp));
					$common->UpdateDB($ArrayData,"hotnews",$update_condit);
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
