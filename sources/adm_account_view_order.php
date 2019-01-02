<?php

if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(6), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	
	$idpayment = $itech->input['idpayment'];

	
	$pre = '';// get language

	// get main content 
	$tpl1 =& new Template('adm_account_view_order');			
	// get info payment
	$pay_info = $common->getInfo("payments","ID_PAYMENT = '".$idpayment."'");
	$tpl1->set('paymentname', $pay_info['CODE']);
	$tpl1->set('CREATED_DATE', $common->datetimevn($pay_info['CREATED_DATE']));
	$tpl1->set('APPROVE', $pay_info['APPROVE']);
	$content_decode = base64_decode($pay_info['CONTENT_MAIL']);
	$tpl1->set('content_email', $content_decode);

	echo $tpl1->fetch('adm_account_view_order');
?>