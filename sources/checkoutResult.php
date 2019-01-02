<?php
			
//get all data from querystring
//transactionID=123P1210020000506&time=1349164000&status=1&ticket=0d5b8e8d4aea134c3cd159dd500f3598
$info_emailpay = $common->getInfo("payments_lookup","ID_PROPAY = 7 ");



$aConfig = array
(
	'merchantCode'=> $info_emailpay['MERCHANT'],
	'url'=>'https://sandbox.123pay.vn/miservice/queryOrder',
	'key'=>'MIKEY',
	'passcode'=> $info_emailpay['ACCESSCODE'],
);
$kq = $_GET['kq'];
$transactionID = $_GET['transactionID'];
$time = $_GET['time'];
$status = $_GET['status'];
$ticket = $_GET['ticket'];

$recalChecksum = md5($status.$time.$transactionID.$aConfig['key']);
if($recalChecksum != $ticket)
{
	echo "&#272;&#7883;a ch&#7881; Url kh&ocirc;ng ph&ugrave; h&#7907;p";//'Invalid url';
	exit;	
}
$iCurrentTS = time();
$iTotalSecond = $iCurrentTS - $time;

$iLimitSecond = 300;//5 min = 5*60 = 300
if($iTotalSecond < 0 || $iTotalSecond > $iLimitSecond)
{
	echo "Th&#7901;i gian thao t&aacute;c c&#7911;a b&#7841;n &#273;&atilde; v&#432;&#7907;t qu&aacute;       th&#7901;i gian cho ph&eacute;p.";//'Expired url';
	exit;	 
}

$transStatus = "";
function getResponseDescription($responseCode) {
	
	switch ($responseCode) {
		case "0" :
			$result = "M&#7899;i";
			break;
		case "1" :
			$result = "Th&agrave;nh c&ocirc;ng";
			break;
		case "-10" :
			$result = "Giao d&#7883;ch kh&ocirc;ng t&#7891;n t&#7841;i. Vui l&ograve;ng th&#7921;c hi&#7879;n giao d&#7883;ch m&#7899;i.";
			break;
		case "-100" :
			$result = "&#272;&#417;n h&agrave;ng b&#7883; h&#7911;y";
			break;
		case "10" :
			$result = "&#272;ang ki&#7875;m tra th&ocirc;ng tin t&agrave;i kho&#7843;n. Giao d&#7883;ch ch&#432;a b&#7883; tr&#7915; ti&#7873;n.";
			break;
		case "20" :
			$result = "Kh&ocirc;ng x&aacute;c &#273;&#7883;nh tr&#7841;ng th&aacute;i thanh to&aacute;n t&#7915; ng&acirc;n h&agrave;ng";
			break;
		case "5000" :
			$result = "H&#7879; th&#7889;ng b&#7853;n";
			break;
		case "6200" :
			$result = "Vi ph&#7841;m quy &#273;&#7883;nh nghi&#7879;p v&#7909;  gi&#7919;a &#273;&#7889;i t&aacute;c &amp; 123Pay";
			break;
		case "6212" :
			$result = "Ngo&agrave;i gi&#7899;i h&#7841;n thanh to&aacute;n / giao d&#7883;ch";
			break;
		case "7200" :
			$result = "Th&ocirc;ng tin thanh to&aacute;n kh&ocirc;ng h&#7907;p l&#7879;";
			break;
		case "7201" :
			$result = "Kh&ocirc;ng &#273;&#7911; ti&#7873;n trong t&agrave;i kho&#7843;n thanh to&aacute;n";
			break;
		case "7202" :
			$result = "Kh&ocirc;ng &#273;&#7843;m b&#7843;o s&#7889; d&#432; t&#7889;i thi&#7875;u trong t&agrave;i kho&#7843;n thanh to&aacute;n";
			break;
		case "7203" :
			$result = "Gi&#7899;i h&#7841;n t&#7841;i ng&acirc;n h&agrave;ng: T&#7893;ng s&#7889; ti&#7873;n / ng&agrave;y";
			break;
		case "7204" :
			$result = "Gi&#7899;i h&#7841;n t&#7841;i ng&acirc;n h&agrave;ng: T&#7893;ng s&#7889; giao d&#7883;ch / ng&agrave;y";
			break;
		case "7205" :
			$result = "Gi&#7899;i h&#7841;n t&#7841;i ng&acirc;n h&agrave;ng: Gi&aacute; tr&#7883; / giao d&#7883;ch";
			break;
		case "7210" :
			$result = "Kh&aacute;ch h&agrave;ng kh&ocirc;ng nh&#7853;p th&ocirc;ng tin thanh to&aacute;n";
			break;
		case "7211" :
			$result = "Ch&#432;a &#273;&#259;ng k&yacute; d&#7883;ch v&#7909; thanh to&aacute;n tr&#7921;c tuy&#7871;n";
			break;
		case "7212" :
			$result = "D&#7883;ch v&#7909; thanh to&aacute;n tr&#7921;c tuy&#7871;n c&#7911;a t&agrave;i kho&#7843;n &#273;ang t&#7841;m kh&oacute;a";
			break;
		case "7213" :
			$result = "T&agrave;i kho&#7843;n thanh to&aacute;n b&#7883; kh&oacute;a";
			break;
		case "7220" :
			$result = "Kh&aacute;ch h&agrave;ng kh&ocirc;ng nh&#7853;p OTP";
			break;
		case "7221" :
			$result = "Nh&#7853;p sai th&ocirc;ng tin th&#7867;/t&agrave;i kho&#7843;n qu&aacute; 3 l&#7847;n";
			break;
		case "7222" :
			$result = "Ng&#432;&#7901;i s&#7911; d&#7909;ng h&#7911;y giao d&#7883;ch - User cancel";
			break;
		case "7223" :
			$result = "OTP h&#7871;t h&#7841;n";
			break;
		case "7224" :
			$result = "Nh&#7853;p sai th&ocirc;ng tin OTP qu&aacute; 3 l&#7847;n";
			break;
		case "7231" :
			$result = "Sai t&ecirc;n ch&#7911; th&#7867;";
			break;
		case "7232" :
			$result = "Card kh&ocirc;ng h&#7907;p l&#7879;, kh&ocirc;ng t&igrave;m th&#7845;y kh&aacute;ch h&agrave;ng / t&agrave;i kho&#7843;n";
			break;
		case "7233" :
			$result = "Expired Card";
			break;
		case "7234" :
			$result = "Lost Card";
			break;
		case "7235" :
			$result = "Stolen Card";
			break;
		case "7236" :
			$result = "Card is marked deleted";
			break;
		case "7241" :
			$result = "Credit Card - Card Security Code verification failed";
			break;
		case "7242" :
			$result = "Credit Card - Address Verification Failed";
			break;
		case "7243" :
			$result = "Credit Card - Address Verification and Card Security Code Failed";
			break;
		case "7244" :
			$result = "Credit Card - Card did not pass all risk checks";
			break;
		case "7245" :
			$result = "Credit Card - Bank Declined Transaction";
			break;
		case "7246" :
			$result = "Credit Card - Account has stop/hold(hold money,...)";
			break;
		case "7247" :
			$result = "Credit Card - Account closed";
			break;
		case "7248" :
			$result = "Credit Card - Frozen Account";
			break;
		case "7300" :
			$result = "L&#7895;i giao ti&#7871;p h&#7879; th&#7889;ng ng&acirc;n h&agrave;ng";
			break;
		default :
			$result = "Giao dich khong xac dinh";
	}
	return $result;
}


//now connect to your database and load order info
//if you did not receive notify status from 123Pay
//please call service queryOrder to get a final order status
$notifiedStatus = 0;//for example your system did not receive notify status from 123Pay yet
//kq la ket qua tra ve cua cac url: 1: tc, 2: cancel, 3: that bai, 4: loi khong xu ly duoc chuyen qua tu CreateOrder
if($kq==4) {$transStatus = $_GET['ms'];}
elseif(!$notifiedStatus)
{	
		try
		{
			$aData = array
			(
				'mTransactionID' => $transactionID,
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
					//echo 'Order info:<hr>';
					//echo 'mTransactionId:'.$transactionID.'<br>';
					//echo '123PayTransactionId: '.$result[1].'<br>';
					//echo 'Status: '.$result[2].'<br>';
					//echo 'Amount: '.$result[3].'<br>';
					//echo '<hr>';
					if($result[2]=='1')//success
					{
						//Do success call service
						//echo 'Checkout process successfully';
						$transStatus = "Giao d&#7883;ch x&#7917; l&yacute; th&agrave;nh c&ocirc;ng";//'Checkout process successfully';//$lang['tl_gdtcong'];
						//$code_order = $_SESSION['payment_order'];
						//$code_order = $transactionID;
						$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 1),
										     2 => array(1 => "STATUS123PAY", 2 => $result[2]),
											 3 => array(1 => "TIME123PAY", 2 => $time),
											 4 => array(1 => "TICKET123PAY", 2 => $ticket)
						
						);	
						
						$update_condit2 = array( 1 => array(1 => "MERCHTXNREF", 2 => $transactionID));
						$common->UpdateDB($ArrayData2,"payments",$update_condit2);
						unset($_SESSION['totalpay']);
						unset($_SESSION['totalpay_disc']);
						unset($_SESSION['totalpay_final']);
						unset($_SESSION['fee_transfer']);			
						unset($_SESSION['shoppingcart']);
						unset($_SESSION['shoppingcart_quantity']);
									
					}else{					
						//echo 'Show message base on order status ('.$result[2].')';
						$transStatus = "Giao d&#7883;ch ch&#432;a th&agrave;nh c&ocirc;ng";//'Show message base on order status ('.$result[2].')';//$lang['tl_gdpending'];
						//$code_order = $_SESSION['payment_order'];
						$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 1),
										     2 => array(1 => "STATUS123PAY", 2 => $result[2])											 
						
						);											
						$update_condit2 = array( 1 => array(1 => "MERCHTXNREF", 2 => $transactionID));
						$common->UpdateDB($ArrayData2,"payments",$update_condit2);
					}
				}else{
					//echo 'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
					$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";//'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';//$lang['tl_gdthatbai'];
				}
			}else{
				//do error call service.
				$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";//'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
			}
		}catch(Exception $e)
		{
			//write log here to monitor your exception
			$transStatus = "D&#7883;ch v&#7909;   &#273;&#7863;t h&agrave;ng    g&#7885;i kh&ocirc;ng th&#7875; x&#7917; l&yacute;, vui l&ograve;ng ki&#7875;m tra l&#7841;i";//'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
		}
}else{
	$transStatus = 'Show checkout result base on notify status';
}

/*+++++++++++++++++++ Xu ly giao dien +++++++++++++++++++++++++++++++++++++++*/	
	// get language
	//$pre = '';
    
	//header
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
	//$pre = '';// get language
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_viewcart";
	$tplct = "m_123pay";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "123pay";
	}
					
	$main_products=& new Template($tplmain);		  
	$temp_contents=& new Template($tplct);	
	$temp_contents->set('mTransactionId', $transactionID);
	
	if($result[0]=='1'){
	$infopay = $common->getInfo("payments","MERCHTXNREF = '".$transactionID."'");		
	$temp_contents->set('IdTransaction', $result[1]);
	$temp_contents->set('code_order', $infopay['CODE']);
	$temp_contents->set('CREATED_DATE', $common->datetimevn($infopay['CREATED_DATE']));
	$temp_contents->set('Status', $result[2]);
	$temp_contents->set('getResponseDescription', getResponseDescription($result[2]));
	$temp_contents->set('Amount', $result[3]);
	$temp_contents->set('idpay', $infopay['ID_PAYMENT']);
	}
	else{
	$temp_contents->set('IdTransaction', "");
	$temp_contents->set('code_order', "");
	$temp_contents->set('CREATED_DATE', "");
	$temp_contents->set('Status', "");
	$temp_contents->set('getResponseDescription', "");
	$temp_contents->set('Amount', "");
	$temp_contents->set('idpay', "");
	
	}
	$temp_contents->set('message', $transStatus);
	
	
	$main_products->set('main_contents', $temp_contents);
	
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
	
	$main_products->set('list_menur_pro', $tpl_menur_pro);
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_products->set('logo_partner', $logo_partner);
		
	
	// Get main 2
	$main->set('main2', $main_products);
	
	$main->set('main3', "");
	$main->set('main4', "");
	$main->set('main5', "");

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