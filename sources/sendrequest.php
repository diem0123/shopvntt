<?php

// kiem tra logged in
	
	// get language
	$pre = $_SESSION['pre'];	
	$idp = $itech->input['idp'];
	
	$fullname = $itech->input['fullname'];	
	$address = $itech->input['address'];	
	$content = $itech->input['content'];
	$cus_contentencode = $itech->input['cus_contentencode'];
	
	$uri_content_decode = urldecode($cus_contentencode);// decode uri before
	$b64_content_decode = base64_decode($uri_content_decode);// continue decode base64	
	
	$infomess = array();
	
	if($idp){
		$info_pro = $common->getInfo("products","ID_PRODUCT = '".$idp."' AND STATUS = 'Active'");
		// check send mail					
					$subject = "Request products ".$info_pro['PRODUCT_NAME']." (code:".$info_pro['CODE'].")";
					$tplcontent = & new Template('tpl_request');      					
					$tplcontent->set('fullname', $fullname);
					
					$tplcontent->set('subj', $subj);
					//$province_info = $common->getInfo("province","ID_PROVINCE = '".$province."'");
					//$dist_info = $common->getInfo("district","ID_DIST = '".$dist."'");
					//$tplcontent->set('name_province', $province_info['NAME']);
					//$tplcontent->set('name_dist', $dist_info['NAME']);
					$tplcontent->set('address', $address);
					//$content_decode = base64_decode($cus_contentencode);
					$tplcontent->set('content', $b64_content_decode);
									
					$content= $tplcontent->fetch('tpl_request');
					
					$emailfrom = $email;					
					$namefrom = "";
					
					$emailto = $itech->vars['email_webmaster'];
					//$emailto = "htktinternet@gmail.com";
					$nameto = $INFO['name_webmaster'];
					$mail = new emailtemp();
					//$error = $mail->gmail_sender($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);
					$error = 'nok';
					// redirect to register success
					if($error == "ok") {
						$infomess['mess1'] = "ok";
						$infomess['mess2'] = $lang['txt_okie_request'];					
					}
					else{
						$infomess['mess1'] = "nook";
						$infomess['mess2'] = $lang['txt_err_request'];//$b64_content_decode;//
						
					}

	}
	else {
		$infomess['mess1'] = "nook";
		$infomess['mess2'] = "No product request!";

	}
	
	echo json_encode($infomess);		
	exit;

?>