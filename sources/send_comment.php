<?php

    $idp = $itech->input['idp'];
	$yeucau = $itech->input['yeucau'];
	$yeucau_st = "";
	if($yeucau==1) $yeucau_st = "A product sample";
	elseif($yeucau==2) $yeucau_st = "A quote"; 
	$fullname = $itech->input['cus_name'];
	$email = $itech->input['cus_email'];
	$tel = $itech->input['cus_tel'];
	$contentencode = $itech->input['content'];	
	$infomess = array();


	if(isset($idp) && $idp > 0){											
			
					$subject = "Contact from - ".$fullname;
					$tplcontent = & new Template('tpl_contactpro');      					
					$tplcontent->set('yeucau_st', $yeucau_st);
					$tplcontent->set('fullname', $fullname);
					$tplcontent->set('tel', $tel);
					$tplcontent->set('email', $email);
					$info_pro = $common->getInfo("products","ID_PRODUCT = '".$idp."' AND STATUS = 'Active'");
					$tplcontent->set('product_name', $info_pro['PRODUCT_NAME']);
					
					//$tplcontent->set('company', $company);
					//$tplcontent->set('city', $city);
					//$tplcontent->set('country', $country);
					//$tplcontent->set('subj', $subj);
					//$province_info = $common->getInfo("province","ID_PROVINCE = '".$province."'");
					//$dist_info = $common->getInfo("district","ID_DIST = '".$dist."'");
					//$tplcontent->set('name_province', $province_info['NAME']);
					//$tplcontent->set('name_dist', $dist_info['NAME']);
					//$tplcontent->set('address', $address);
					//$content_decode = base64_decode($cus_contentencode);
					$tplcontent->set('content', base64_decode($contentencode));
									
					$content= $tplcontent->fetch('tpl_contactpro');
					
					$emailfrom = $email;					
					$namefrom = "";
					$emailto = $itech->vars['email_webmaster'];
					//$emailto = "thanle76@gmail.com";
					$nameto = $INFO['name_webmaster'];
					$mail = new emailtemp();
					$error = $mail->sendmark($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);
					
					if($error == "ok") {
						$infomess['mess1'] = "success";
						$infomess['mess2'] = $lang['tl_thongtinsent'];						
					}
					else{
						$infomess['mess1'] = "nosuccess";
						$infomess['mess2'] = $lang['tl_emailnotsend'];
												
					}
			
			echo json_encode($infomess);
			exit;
	}
	else {
		$infomess['mess1'] = "nosuccess";
		$infomess['mess2'] = "Error send.";
		echo json_encode($infomess);
		exit;
	}

?>
