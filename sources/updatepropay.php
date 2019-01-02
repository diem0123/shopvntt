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
     $main =  new Template('addpropay'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idpropay = $itech->input['idpropay'];
		
		$propayname = $itech->input['propayname'];		
		$emailpay = $itech->input['emailpay'];
		$merchant = $itech->input['merchant'];
		$accesscode = $itech->input['accesscode'];
		$iorder = $itech->input['iorder'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];			

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idpropay) {
					$main->set('propayname', "");
					$main->set('emailpay', "");
					$main->set('merchant', "");
					$main->set('accesscode', "");
					$main->set('iorder', "");
					$main->set('content', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m ph&#432;&#417;ng th&#7913;c thanh to&aacute;n");
					$main->set('ac', "a");
					$main->set('idpropay', "");

			echo $main->fetch("addpropay");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idpropay) {
					$catinfo = $common->getInfo("payments_lookup","ID_PROPAY = '".$idpropay."'");
					$main->set('propayname', $catinfo['PROPAY_NAME']);
					$main->set('emailpay', $catinfo['EMAIL_PAY']);
					$main->set('merchant', $catinfo['MERCHANT']);					
					$main->set('accesscode', $catinfo['ACCESSCODE']);
					$main->set('iorder', $catinfo['IORDER']);
					//$main->set('content', $catinfo['INFO']);
					$content_decode = base64_decode($catinfo['INFO'.$pre]);
					$main->set('content', $content_decode);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh ph&#432;&#417;ng th&#7913;c thanh to&aacute;n");
					$main->set('ac', "e");
					$main->set('idpropay', $idpropay);

			echo $main->fetch("addpropay");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idpropay) {
			 $main = & new Template('viewpropay');
					$catinfo = $common->getInfo("payments_lookup","ID_PROPAY = '".$idpropay."'");
					$main->set('propayname', $catinfo['PROPAY_NAME']);
					$main->set('emailpay', $catinfo['EMAIL_PAY']);
					$main->set('merchant', $catinfo['MERCHANT']);					
					$main->set('accesscode', $catinfo['ACCESSCODE']);
					$main->set('iorder', $catinfo['IORDER']);
					//$main->set('content', $catinfo['INFO']);
					$content_decode = base64_decode($catinfo['INFO'.$pre]);
					$main->set('content', $content_decode);
										
					$main->set('title_action', "Xem ph&#432;&#417;ng th&#7913;c thanh to&aacute;n");

			echo $main->fetch("viewpropay");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idpropay) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "PROPAY_NAME", 2 => $propayname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "INFO", 2 => $contentencode),
										4 => array(1 => "EMAIL_PAY", 2 => $emailpay),
										5 => array(1 => "MERCHANT", 2 => $merchant),
										6 => array(1 => "ACCESSCODE", 2 => $accesscode)	
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"payments_lookup");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idpropay) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "PROPAY_NAME", 2 => $propayname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "INFO", 2 => $contentencode),
										4 => array(1 => "EMAIL_PAY", 2 => $emailpay),
										5 => array(1 => "MERCHANT", 2 => $merchant),
										6 => array(1 => "ACCESSCODE", 2 => $accesscode)	
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_PROPAY", 2 => $idpropay));
					$common->UpdateDB($ArrayData,"payments_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
