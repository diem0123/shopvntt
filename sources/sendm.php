<?php

		$subject = "kiem tra thong tin lan ".time();
       
		$content="Noi dung duoc kiem tra ".time();
		
		$emailfrom = "htktinternet@gmail.com";
		$fullname = "LMT";
		
		$emailto = "minhtrung_dn@yahoo.com";
		$nameto = "MT";

		$mail = new emailtemp();
		$error = $mail->gmail_sender($emailfrom, $fullname, $emailto, $nameto, $content, $subject, $source, $file_name);

		if ($error=='ok'){
			echo "mail gui thanh cong!";			
		}
		else echo "mail gui that bai!";


    
?>