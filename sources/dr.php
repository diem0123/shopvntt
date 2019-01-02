<?php
			
/* -----------------------------------------------------------------------------

 Version 2.0

--------------------------------------------------------------------------------

 @author OnePAy JSC

------------------------------------------------------------------------------*/

// *********************
// START OF MAIN PROGRAM
// *********************


// Define Constants
// ----------------
// This is secret for encoding the MD5 hash
// This secret will vary from merchant to merchant
// To not create a secure hash, let SECURE_SECRET be an empty string - ""
// $SECURE_SECRET = "secure-hash-secret";
//$SECURE_SECRET = "A3EFDFABA8653DF2342E8DAC29B51AF0";
$SECURE_SECRET = "363CBA4F521157358DBAB71A7D7238F6";

// If there has been a merchant secret set then sort and loop through all the
// data in the Virtual Payment Client response. While we have the data, we can
// append all the fields that contain values (except the secure hash) so that
// we can create a hash and validate it against the secure hash in the Virtual
// Payment Client response.


// NOTE: If the vpc_TxnResponseCode in not a single character then
// there was a Virtual Payment Client error and we cannot accurately validate
// the incoming data from the secure hash. */

// get and remove the vpc_TxnResponseCode code from the response fields as we
// do not want to include this field in the hash calculation
$vpc_Txn_Secure_Hash = $_GET ["vpc_SecureHash"];
unset ( $_GET ["vpc_SecureHash"] );

// set a flag to indicate if hash has been validated
$errorExists = false;

if (strlen ( $SECURE_SECRET ) > 0 && $_GET ["vpc_TxnResponseCode"] != "7" && $_GET ["vpc_TxnResponseCode"] != "No Value Returned") {
	
    //$stringHashData = $SECURE_SECRET;
    //*****************************khởi tạo chuỗi mã hóa rỗng*****************************
    $stringHashData = "";
	
	// sort all the incoming vpc response fields and leave out any with no value
	foreach ( $_GET as $key => $value ) {
//        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
//            $stringHashData .= $value;
//        }
//      *****************************chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về*****************************
        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
		    $stringHashData .= $key . "=" . $value . "&";
		}
	}
//  *****************************Xóa dấu & thừa cuối chuỗi dữ liệu*****************************
    $stringHashData = rtrim($stringHashData, "&");	
	
	
//    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $stringHashData ) )) {
//    *****************************Thay hàm tạo chuỗi mã hóa*****************************
	if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$SECURE_SECRET)))) {
		// Secure Hash validation succeeded, add a data field to be displayed
		// later.
		$hashValidated = "CORRECT";
	} else {
		// Secure Hash validation failed, add a data field to be displayed
		// later.
		$hashValidated = "INVALID HASH";
	}
} else {
	// Secure Hash was not validated, add a data field to be displayed later.
	$hashValidated = "INVALID HASH";
}

// Define Variables
// ----------------
// Extract the available receipt fields from the VPC Response
// If not present then let the value be equal to 'No Value Returned'
// Standard Receipt Data
$amount = null2unknown ( $_GET ["vpc_Amount"] );
$locale = null2unknown ( $_GET ["vpc_Locale"] );
//$batchNo = null2unknown ( $_GET ["vpc_BatchNo"] );
$command = null2unknown ( $_GET ["vpc_Command"] );
//$message = null2unknown ( $_GET ["vpc_Message"] );
$version = null2unknown ( $_GET ["vpc_Version"] );
//$cardType = null2unknown ( $_GET ["vpc_Card"] );
$orderInfo = null2unknown ( $_GET ["vpc_OrderInfo"] );
//$receiptNo = null2unknown ( $_GET ["vpc_ReceiptNo"] );
$merchantID = null2unknown ( $_GET ["vpc_Merchant"] );
//$authorizeID = null2unknown ( $_GET ["vpc_AuthorizeId"] );
$merchTxnRef = null2unknown ( $_GET ["vpc_MerchTxnRef"] );
$transactionNo = null2unknown ( $_GET ["vpc_TransactionNo"] );
//$acqResponseCode = null2unknown ( $_GET ["vpc_AcqResponseCode"] );
$txnResponseCode = null2unknown ( $_GET ["vpc_TxnResponseCode"] );

// This is the display title for 'Receipt' page 
//$title = $_GET ["Title"];


// This method uses the QSI Response code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
//
// @param $responseCode String containing the QSI Response Code
//
// @return String containing the appropriate description
//
function getResponseDescription($responseCode) {
	
	switch ($responseCode) {
		case "0" :
			$result = "Giao d&#7883;ch th&agrave;nh c&ocirc;ng - Approved";
			break;
		case "1" :
			$result = "Ng&acirc;n h&agrave;ng t&#7915; ch&#7889;i giao d&#7883;ch - Bank Declined";
			break;
		case "3" :
			$result = "M&atilde; &#273;&#417;n v&#7883; kh&ocirc;ng t&#7891;n t&#7841;i - Merchant not exist";
			break;
		case "4" :
			$result = "Kh&ocirc;ng &#273;&uacute;ng access code - Invalid access code";
			break;
		case "5" :
			$result = "S&#7889; ti&#7873;n kh&ocirc;ng h&#7907;p l&#7879; - Invalid amount";
			break;
		case "6" :
			$result = "M&atilde; ti&#7873;n t&#7879; kh&ocirc;ng t&#7891;n t&#7841;i - Invalid currency code";
			break;
		case "7" :
			$result = "L&#7895;i kh&ocirc;ng x&aacute;c &#273;&#7883;nh - Unspecified Failure ";
			break;
		case "8" :
			$result = "S&#7889; th&#7867; kh&ocirc;ng &#273;&uacute;ng - Invalid card Number";
			break;
		case "9" :
			$result = "T&ecirc;n ch&#7911; th&#7867; kh&ocirc;ng &#273;&uacute;ng - Invalid card name";
			break;
		case "10" :
			$result = "Th&#7867; h&#7871;t h&#7841;n/Th&#7867; b&#7883; kh&oacute;a - Expired Card";
			break;
		case "11" :
			$result = "Th&#7867; ch&#432;a &#273;&#259;ng k&yacute; s&#7917; d&#7909;ng d&#7883;ch v&#7909; - Card Not Registed Service(internet banking)";
			break;
		case "12" :
			$result = "Ng&agrave;y ph&aacute;t h&agrave;nh/H&#7871;t h&#7841;n kh&ocirc;ng &#273;&uacute;ng - Invalid card date";
			break;
		case "13" :
			$result = "V&#432;&#7907;t qu&aacute; h&#7841;n m&#7913;c thanh to&aacute;n - Exist Amount";
			break;
		case "21" :
			$result = "S&#7889; ti&#7873;n kh&ocirc;ng &#273;&#7911; &#273;&#7875; thanh to&aacute;n - Insufficient fund";
			break;
		case "99" :
			$result = "Ng&#432;&#7901;i s&#7911; d&#7909;ng h&#7911;y giao d&#7883;ch - User cancel";
			break;
		default :
			$result = "Giao d&#7883;ch th&#7845;t b&#7841;i - Failured";
	}
	return $result;
}

//  -----------------------------------------------------------------------------
// If input is null, returns string "No Value Returned", else returns input
function null2unknown($data) {
	if ($data == "") {
		return "No Value Returned";
	} else {
		return $data;
	}
}
//  ----------------------------------------------------------------------------

$transStatus = "";
if($hashValidated=="CORRECT" && $txnResponseCode=="0"){
	$transStatus =$lang['tl_gdtcong'];
	$code_order = $_SESSION['payment_order'];
								$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 1));											
								$update_condit2 = array( 1 => array(1 => "CODE", 2 => $code_order));
								$common->UpdateDB($ArrayData2,"payments",$update_condit2);
}elseif ($hashValidated=="INVALID HASH" && $txnResponseCode=="0"){
	$transStatus = $lang['tl_gdpending'];
	$code_order = $_SESSION['payment_order'];
								$ArrayData2 = array( 1 => array(1 => "APPROVE", 2 => 2));											
								$update_condit2 = array( 1 => array(1 => "CODE", 2 => $code_order));
								$common->UpdateDB($ArrayData2,"payments",$update_condit2);
}else {
	$transStatus = $lang['tl_gdthatbai'];
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