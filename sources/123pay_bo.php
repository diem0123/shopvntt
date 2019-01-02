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

$transactionID = $_GET['transactionID'];
$time = $_GET['time'];
$status = $_GET['status'];
$ticket = $_GET['ticket'];

$recalChecksum = md5($status.$time.$transactionID.$aConfig['key']);
if($recalChecksum != $ticket)
{
	echo 'Invalid url';
	exit;	
}
$iCurrentTS = time();
$iTotalSecond = $iCurrentTS - $time;

$iLimitSecond = 300;//5 min = 5*60 = 300
if($iTotalSecond < 0 || $iTotalSecond > $iLimitSecond)
{
	echo 'Expired url';
	exit;	 
}

//now connect to your database and load order info
//if you did not receive notify status from 123Pay
//please call service queryOrder to get a final order status
$notifiedStatus = 0;//for example your system did not receive notify status from 123Pay yet
if(!$notifiedStatus)
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
					echo 'Order info:<hr>';
					echo 'mTransactionId:'.$transactionID.'<br>';
					echo '123PayTransactionId: '.$result[1].'<br>';
					echo 'Status: '.$result[2].'<br>';
					echo 'Amount: '.$result[3].'<br>';
					echo '<hr>';
					if($result[2]=='1')//success
					{
						//Do success call service
						echo 'Checkout process successfully';
					}else{					
						echo 'Show message base on order status ('.$result[2].')';
					}
				}else{
					echo 'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
				}
			}else{
				//do error call service.
				echo 'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
			}
		}catch(Exception $e)
		{
			//write log here to monitor your exception
			echo 'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';
		}
}else{
	echo 'Show checkout result base on notify status';
}
					
			
//  ----------------------------------------------------------------------------

$transStatus = "";
if($result[0]=='1'){			
	if($result[2]=='1'){
		$transStatus =$lang['tl_gdtcong'];
		$code_order = $_SESSION['payment_order'];
									$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 1));											
									$update_condit2 = array( 1 => array(1 => "CODE", 2 => $code_order));
									$common->UpdateDB($ArrayData2,"payments",$update_condit2);
	}else {
		$transStatus = 'Show message base on order status ('.$result[2].')';//$lang['tl_gdpending'];
		$code_order = $_SESSION['payment_order'];
									$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 2));											
									$update_condit2 = array( 1 => array(1 => "CODE", 2 => $code_order));
									$common->UpdateDB($ArrayData2,"payments",$update_condit2);
	}
} else {
	$transStatus = 'Call service queryOrder fail: Order is processing. Please waiting some munite and check your order history list';//$lang['tl_gdthatbai'];
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
	$tplct = "m_dr";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "dr";
	}
	
	$main_products=& new Template($tplmain);		  
	$temp_contents=& new Template($tplct);	
	$temp_contents->set('transStatus', $transStatus);
	$temp_contents->set('merchantID', $merchantID);
	$temp_contents->set('merchTxnRef', $merchTxnRef);
	$temp_contents->set('orderInfo', $orderInfo);
	$temp_contents->set('amount', $amount/100);// Do ban dau nhan 100 nen ket qua tra ve phai chia lai 100 de dung so ban dau
	$temp_contents->set('txnResponseCode', $txnResponseCode);
	$temp_contents->set('getResponseDescription', getResponseDescription ( $txnResponseCode ));
	$temp_contents->set('message', $message);
	$temp_contents->set('transactionNo', $transactionNo);

	
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