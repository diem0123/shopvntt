<?php

// kiem tra logged in
	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=login';
		$common->redirect_url($url_redidrect);
	}
	
	//$idtype = $itech->input['idtype'];
	$idtype = 6;// default menu selected account
	//$idcat = $itech->input['idcat'];
	$idcat = 24;// default manage order
	$idcatsub = $itech->input['idcatsub'];
	
	$idpayment = $itech->input['idpayment'];

	//header
	$pre = '';// get language
	$_SESSION['tab'] = 6;// Account
	$name_title = "T&Agrave;I KHO&#7842;N";
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") {
	$tplmain = "m_main_viewcart";
	$tplc = "m_account_view_order";
	}
	else {
	$tplmain = "main_viewcart";
	$tplc = "account_view_order";
	}
	
	$temp_contents=& new Template($tplmain);

	// get main content 
	$tpl1 =& new Template($tplc);
	// get info payment
	$pay_info = $common->getInfo("payments","ID_PAYMENT = '".$idpayment."'");
	//echo "STATUS123PAY=".$pay_info['STATUS123PAY']."IDPROPAY=".$pay_info['ID_PROPAY'];;
	// query cap nhat lai don hang 123pay
	if($pay_info['ID_PROPAY'] == 7 && $pay_info['STATUS123PAY'] != '1'){
		$transactionID = $pay_info['MERCHTXNREF'];
		$info_emailpay = $common->getInfo("payments_lookup","ID_PROPAY = 7 ");

		$aConfig = array
		(
			'merchantCode'=> $info_emailpay['MERCHANT'],
			'url'=>'https://sandbox.123pay.vn/miservice/queryOrder',
			'key'=>'MIKEY',
			'passcode'=> $info_emailpay['ACCESSCODE'],
		);
		
		try
		{
			$aData = array
			(
				'mTransactionID' => $pay_info['MERCHTXNREF'],
				'merchantCode' =>$aConfig['merchantCode'],
				'clientIP' => $_SERVER ['REMOTE_ADDR'],//current browser ip
				'passcode' =>$aConfig['passcode'],
				'checksum' =>'',
			);
			
			$data = Common::callRest($aConfig, $aData);
			
			$result = $data->return;
			if($result['httpcode'] ==  200)
			{
				/* Retun data format
				Array
				(
					[0] => 1
					[1] => 123P1210020000507
					[2] => 1
					[3] => 100000
					[4] => 100000
					[5] => BANKNET
					[6] => 
					[7] => bc44083e998b5e24a922ffad04ea779a84bb2ee3
					[httpcode] => 200
				)
				*/
				if($result[0]=='1')
				{	
					/*
					echo 'Order info:<hr>';
					echo 'mTransactionId:'.$transactionID.'<br>';
					echo '123PayTransactionId: '.$result[1].'<br>';
					echo 'Status: '.$result[2].'<br>';
					echo 'Amount: '.$result[3].'<br>';
					echo '<hr>';
					*/
					
					if($result[2]=='1')//success
					{
						//Do success call service
						//echo 'Checkout process successfully';
						$transStatus = "Giao d&#7883;ch x&#7917; l&yacute; th&agrave;nh c&ocirc;ng";
						//$transStatus = 'Checkout process successfully';//$lang['tl_gdtcong'];																
						// neu database doi tac chua co status thanh cong thi cap nhat
						$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 1),
										     2 => array(1 => "STATUS123PAY", 2 => $result[2])											 
						
						);
						
						$update_condit2 = array( 1 => array(1 => "MERCHTXNREF", 2 => $transactionID));
						$common->UpdateDB($ArrayData2,"payments",$update_condit2);
					}else{					
						//echo 'Show message base on order status ('.$result[2].')';
						//$transStatus = 'Show message base on order status ('.$result[2].')';//$lang['tl_gdpending'];
						$transStatus = "Giao d&#7883;ch ch&#432;a th&agrave;nh c&ocirc;ng";
						
						$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 0),
										     2 => array(1 => "STATUS123PAY", 2 => $result[2])											 
						
						);											
						$update_condit2 = array( 1 => array(1 => "MERCHTXNREF", 2 => $transactionID));
						$common->UpdateDB($ArrayData2,"payments",$update_condit2);
					}
				}else{					
					$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";
				}
			}else{				
				$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";
			}
		}catch(Exception $e)
		{			
			$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";
		}
	}
	
	// get info payment lay thong tin moi
	$pay_info = $common->getInfo("payments","ID_PAYMENT = '".$idpayment."'");
	
	$tpl1->set('paymentname', $pay_info['CODE']);
	$tpl1->set('CREATED_DATE', $common->datetimevn($pay_info['CREATED_DATE']));
	$tpl1->set('APPROVE', $pay_info['APPROVE']);
	$content_decode = base64_decode($pay_info['CONTENT_MAIL']);
	$tpl1->set('content_email', $content_decode);

	$main_contents = $tpl1->fetch($tplc);
	
	
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$temp_contents->set('logo_partner', $logo_partner);
	
	$temp_contents->set('main_contents', $main_contents);
	$temp_contents->set('name_title', $name_title);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type']=="phone"){
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "m_menupro_cat_rs",  $field_name_other2, "m_menupro_catsub_rs", $field_name_other3, "m_menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "m_list_menur_pro";
	} else {
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "list_menur_pro";
	}
	
	$tpl_menur_pro= new Template($tpll);
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$temp_contents->set('list_menur_pro', $tpl_menur_pro);
		

	$main->set('main2', $temp_contents); 


	

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$footer = & new Template('m_footer');	
	else
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	// get cong ty footer
	$cty = $common->gethonhop(6);
	if($cty == "")
	$footer->set('cty', "");
	else{
		$footer->set('title_welcome', $cty['STITLE'.$pre]);
		if($cty['SCONTENTS'.$pre]=="")
		$footer->set('cty', base64_decode($cty['SCONTENTS']));
		else
		$footer->set('cty', base64_decode($cty['SCONTENTS'.$pre]));
	}

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>