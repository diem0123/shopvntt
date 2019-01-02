<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(10), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	 
     //main
     $main = & new Template('addprovince'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idpr = $itech->input['idpr'];
		
		$namepr = $itech->input['namepr'];
		$iorder = $itech->input['iorder'];
		$code = $itech->input['code'];		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idpr) {
					$main->set('namepr', "");
					$main->set('iorder', "");
					$main->set('code', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m t&#7881;nh th&agrave;nh");
					$main->set('ac', "a");
					$main->set('idpr', "");

			echo $main->fetch("addprovince");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idpr) {
					$catinfo = $common->getInfo("province","ID_PROVINCE = '".$idpr."'");
					$main->set('namepr', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('code', $catinfo['CODE']);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh t&#7881;nh th&agrave;nh");
					$main->set('ac', "e");
					$main->set('idpr', $idpr);

			echo $main->fetch("addprovince");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idpr) {
			 $main = & new Template('viewprovince');
					$catinfo = $common->getInfo("province","ID_PROVINCE = '".$idpr."'");
					$main->set('namepr', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('code', $catinfo['CODE']);

			echo $main->fetch("viewprovince");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idpr) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $namepr),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "CODE", 2 => $code)																		
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"province");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idpr) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $namepr),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "CODE", 2 => $code)																				
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_PROVINCE", 2 => $idpr));
					$common->UpdateDB($ArrayData,"province",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
