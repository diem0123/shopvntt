<?php
	
	$idp=$itech->input['idp'];	
	$info_pro = $common->getInfo("products","ID_PRODUCT = '".$idp."' AND STATUS = 'Active'");
	$idc = $info_pro['ID_CATEGORY'];
	$name_link = $print_2->vn_str_filter($info_pro['PRODUCT_NAME']);
	$mess1 = "";
	$mess2 = "";
	
// kiem tra Submit
     if (isset($itech->input['submit']))
	 {
		
		$fullname=$itech->input['fullname'];
		$email=$itech->input['email'];
		$namefriend=$itech->input['namefriend'];
		$emailfriend=$itech->input['emailfriend'];
		$comment=$itech->input['comment'];
		$subject1 = $itech->input['subject'];
		
		// Send mail to friend	
		$subject = "[".$fullname."] ".$itech->input['subject'];
        
		
		$emailTemplate = & new Template('tpl_sendtofriend');
				
		$emailTemplate->set('friendname',$namefriend);
		$emailTemplate->set('email',$email);
		$emailTemplate->set('fullname',$fullname);
		$emailTemplate->set('comment',$comment);
		$emailTemplate->set('titlepro', $info_pro['PRODUCT_NAME']);
		$emailTemplate->set('idc', $idc);
		$emailTemplate->set('name_link', $name_link);		
		$emailTemplate->set('idp',$idp);
		
		
		$content = $emailTemplate->fetch('tpl_sendtofriend');
		//echo $content;
		
		$emailfrom = $itech->vars['email_webmaster'];//$email;
		$namefrom = $INFO['name_webmaster'];
		
		$emailto = $emailfriend;
		$nameto = $namefriend;
		
		$mail = new emailtemp();
		$error = $mail->gmail_sender($emailfrom,$namefrom,$emailto,$nameto,$content,$subject);
		
		if ($error=='ok'){
		$mess1 = 'ok';
		$mess2 = "<p>Th&ocirc;ng tin &#273;&atilde; &#273;&#432;&#7907;c g&#7917;i &#273;i!</p>
<p>C&#7843;m &#417;n b&#7841;n &#273;&atilde; quan t&acirc;m &#273;&#7871;n s&#7843;n ph&#7849;m c&#7911;a ch&uacute;ng t&ocirc;i. </p>";
		}
		else {
		$mess1 = 'notok';
		$mess2 = "<p>Email hi&#7879;n t&#7841;i kh&ocirc;ng g&#7917;i &#273;&#432;&#7907;c! B&#7841;n vui l&ograve;ng th&#7917; l&#7841;i ho&#7863;c g&#7917;i l&#7841;i sau.</p>";
		}

			  
		
	 }    
     //header        
      
     //main
       $main = & new Template('sendtofriend');
	   $main->set('fullname',$fullname);
	   $main->set('email',$email);
	   $main->set('namefriend',$namefriend);
	   $main->set('emailfriend',$emailfriend);
	   $main->set('comment',$comment);
	   $main->set('subject',$subject1);
	   $main->set('idp',$idp);
	   $main->set('mess1',$mess1);
	   $main->set('mess2',$mess2);

	   echo $main->fetch('sendtofriend');
?>