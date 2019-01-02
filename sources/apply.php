<?php

	$jobid=$itech->input['jobid'];
	$condit = " ID_COMMON = '".$jobid."' AND STATUS = 'Active' ";		
	$job = $common->getInfo("common",$condit);
	$name_link = $print->getlangvalue($job,"STITLE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	$linkapp = "chitiet-".$jobid."-".$name_link.".html";
	
		
// kiem tra Submit
 if (isset($itech->input['submit']))
 {
	
		$fullname=$itech->input['fullname'];
		$email=$itech->input['email'];
		$telephone=$itech->input['telephone'];
		$comment=$itech->input['comment'];

		$flag = true;
		$mess ="";
		$logo = $_FILES['fileupload']['name'];

		if($logo!="" && $user_2->check_extent_file($logo,"doc|docx|xls|xlsx|pdf|DOC|DOCX|XLS|XLSX|PDF")==false){			
			$mess .= "File không đúng định dạng. Vui lòng nộp lại file khác!<br>";
			$flag = false;
		}else
			if($logo!="" && $user_2->check_extent_file($logo,"doc|docx|xls|xlsx|pdf|DOC|DOCX|XLS|XLSX|PDF")==true)
			{
				$file_input_tmp = $_FILES['fileupload']['tmp_name'];
				$file_input_name =$_FILES['fileupload']['name'];
				//$prefix = "logo" . time();				
				$source = $file_input_tmp;
		        $file_name = $fullname."_".$file_input_name;		       
				//$dir_upload = DIR_RS."appl/";
		        //$dest = $dir_upload.$file_name;				
		        //copy( $source, $dest );
		        $logo = $file_name;
			}
			else
			{
				$logo ="";
			}
		
	if($flag){	
		// Send mail file apply	
		$subject = $fullname." - Apply ".$job['STITLE']." GCfood.com.vn";
        //$headers  = "MIME-Version: 1.0\r\n";
        //$headers .= "Content-type: text/html; charset=UTF-8\r\n";
        //$headers .= "To:<".$mailto.">\r\n";
        //$headers .= "From: ".$_POST['email']."\r\n";
		$content="
		Apply ".$job['STITLE']." trên GCfood.com.vn <br>
		Full name: ".$fullname."<br>
		Email: ".$email."<br>
		Telephone no: ".$telephone."<br>
		Motivation for the job: <br>".nl2br($comment);
		//echo $message;
		//$ok = @mail($mailto, $subject, $message, $headers);
		$emailfrom = $email;
		
		$ids = 1;
		$getsmtp = $common->getInfo("outgoing_email","OUTGOING_EMAIL_ID = '".$ids."'");
		
		$emailto = $getsmtp['FROM_EMAIL'];//$itech->vars['email_webmaster'];
		
		$nameto = $getsmtp['FROM_NAME'];		
		$count_app = 0;
		
		
		
		if(!isset($_SESSION['eml'])) $_SESSION['eml'] = "";
				
		$mail = new emailtemp();
		if(($email != "goh.gilbert@gmail.com" || $email != "mark357177@hotmail.com") && $_SESSION['eml'] != 5){		
		//echo "L=".$emailfrom;exit;
		//$error = $mail->gmail_sender($emailfrom, $fullname, $emailto, $nameto, $content, $subject, $source, $file_name);
		$error = $mail->sendmark($emailfrom,$fullname,$emailto,$nameto,$content,$subject, $source, $file_name);
		if(!isset($_SESSION['eml'])) $_SESSION['eml'] = 1;
		else
		$_SESSION['eml'] += 1;
		
		}
		else {
		$error = 'Không được phép. Bạn apply quá nhiều lần!';
		}
		//$error='ok';
		if ($error=='ok'){
		// Send auto reply
		$emailTemplate = new Template('tpl_app_reply');
		$emailTemplate->set('fullname',$fullname);
		$subject_auto = "Chúc mừng bạn đã nộp hồ sơ thành công!";
		$content_auto = $emailTemplate->fetch('tpl_app_reply');
		//echo $subject_auto."<br>";
		//echo $content_auto."<br>";exit;
		//$mail->sendmark($emailto, $nameto, $emailfrom, $fullname, $content_auto, $subject_auto);
		//$mail->gmail_sender($emailto, $nameto, $emailfrom, $fullname, $content_auto, $subject_auto);
		$error = $mail->sendmark($emailto,$nameto,$emailfrom,$fullname,$content_auto,$subject_auto);
		
		$print->redirect($linkapp, 'Thông tin đã được gửi đi. Cảm ơn bạn!.' ,'Thanks');
		}
		else{		
		$print->redirect($linkapp, $error ,'Thanks');
		}
		
	}
	else{
		$print->redirect($linkapp, $mess ,'Thanks');	
	}

}
else{
		$print->redirect($linkapp, 'Thao tác không hợp lệ' ,'Thanks');	
	}
     

?>