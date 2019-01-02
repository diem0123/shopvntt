<?php
/*
echo '<br> mTransactionID'.$_POST['mTransactionID'];
echo '<br> merchantCode'.$_POST['merchantCode'];
echo '<br> bankCode'.$_POST['bankCode'];
echo '<br> totalAmount'.$_POST['totalAmount'];
echo '<br> clientIP'.$_POST['clientIP'];
echo '<br> custName'.$_POST['custName'];
echo '<br> custAddress'.$_POST['custAddress'];
echo '<br> custGender'.$_POST['custGender'];
echo '<br> custDOB'.$_POST['custDOB'];
echo '<br> custPhone'.$_POST['custPhone'];
echo '<br> custMail'.$_POST['custMail'];
echo '<br> description'.$_POST['description'];
echo '<br> cancelURL'.$_POST['cancelURL'];
echo '<br> redirectURL'.$_POST['redirectURL'];
echo '<br> errorURL'.$_POST['errorURL'];
echo '<br> passcode'.$_POST['passcode'];
*/
// ham nay tam ko dung vi da lay ben completeorder
function createUniqueOrderId($orderIdPrefix)
{
	return $orderIdPrefix.time();
}
$mTransactionID = '';
$orderIdPrefix = 'micode';

$result = null;
$resultMessage = '';
if($_POST)
{
	$mTransactionID=$_POST['mTransactionID'];
	//$resultMessage = 'Current order id: <strong>'.$mTransactionID.'</strong><br>';
	$aData = array
	(
		'mTransactionID' => $mTransactionID,
		'merchantCode' =>$_POST['merchantCode'],
		'bankCode' =>$_POST['bankCode'],
		'totalAmount' =>$_POST['totalAmount'],
		'clientIP' =>$_POST['clientIP'],
		'custName' =>$_POST['custName'],
		'custAddress' =>$_POST['custAddress'],
		'custGender' =>$_POST['custGender'],
		'custDOB' =>$_POST['custDOB'],
		'custPhone' =>$_POST['custPhone'],
		'custMail' =>$_POST['custMail'],
		'description' =>$_POST['description'],
		'cancelURL' => $_POST['cancelURL'],
        'redirectURL' => $_POST['redirectURL'],
        'errorURL' => $_POST['errorURL'],
		'passcode' =>$_POST['passcode'],
		'checksum' =>'',
		'addInfo' =>''
	);
	
	$aConfig = array
	(
		'url'=>'https://sandbox.123pay.vn/miservice/createOrder1',
		'key'=> $_POST['mikey'],
		'passcode'=> $_POST['passcode'],
		'cancelURL' => $_POST['cancelURL'], //fill cancelURL here
		'redirectURL' => $_POST['redirectURL'], //fill redirectURL here
        'errorURL' => $_POST['errorURL'], //fill errorURL here
	);
	
	
	try
	{
		$data = Common::callRest($aConfig, $aData);//call 123Pay service
		$result = $data->return;
		//print_r($result);
		if($result['httpcode'] ==  200)
		{
			//call service success do success flow
			if($result[0]=='1')//service return success
			{
				//re-create checksum
				$rawReturnValue = '1'.$result[1].$result[2];
				$reCalChecksumValue = sha1($rawReturnValue.$aConfig['key']);
				if($reCalChecksumValue == $result[3])//check checksum
				{
					$resultMessage .= 'Call service result:<hr>';
					$resultMessage .=  'mTransactionID='.$mTransactionID.'<br>';
					$resultMessage .=  '123PayTransactionID='.$result[1].'<br>';
					$resultMessage .=  'URL='.$result[2].'<br>';
					echo'<script>window.location.href="'.$result[2].'"</script>';                                        
                    exit();
					//call php header to redirect to input card page
					//$resultMessage .= '<a style="color:red;font-weight:bold;" href="'.$result[2].'" target="_parent">Click here to go to payment process</a><br>';
				}else
				{
					//Call 123Pay service create order fail, return checksum is invalid
					$resultMessage .=  "D&#7919; li&#7879;u tr&#7843; v&#7873; t&#7915; d&#7883;ch v&#7909; thanh to&aacute;n kh&ocirc;ng ph&ugrave; h&#7907;p";//'Return data is invalid<br>';
				}
			//echo "vao 1";
			}else{
				//Call 123Pay service create order fail, please refer to API document to understand error code list
				//$result[0]=error code, $result[1] = error description
				$resultMessage .= "Kh&#7903;i t&#7841;o &#273;&#417;n h&agrave;ng thanh to&aacute;n v&#7899;i d&#7883;ch v&#7909; 123Pay kh&ocirc;ng th&agrave;nh c&ocirc;ng";
				$resultMessage .=  $result[0].': '.$result[1];
			//echo "vao 2";
			}
		}else{
			//call service fail, do error flow
			//$resultMessage .=  'Call 123Pay service fail. Please recheck your network connection<br>';
			$resultMessage .= "G&#7885;i ph&#432;&#417;ng th&#7913;c   thanh to&aacute;n v&#7899;i d&#7883;ch v&#7909; 123Pay kh&ocirc;ng th&agrave;nh c&ocirc;ng. Vui l&ograve;ng ki&#7875;m tra l&#7841;i k&#7871;t n&#7889;i m&#7841;ng.     ";
			//echo "vao 3";
		}
	}catch(Exception $e)
	{
		$resultMessage .=  '<pre>';
		$resultMessage .= $e->getMessage();
		//echo "vao 4";
	}
	//create new orderid
}

//show result
//echo $resultMessage;
$urlrs = 'http://therapyshower.vn/123pay-4-loi-system.html?ms='.$resultMessage;
echo'<script>window.location.href="'.$urlrs.'"</script>';                                        
exit();
?>
