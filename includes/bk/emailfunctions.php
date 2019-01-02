<?php
//email functions
class emailtemp {
	
 	//var $email_webmaster = "contact@focusconsulting.com.vn";
 	//var $name_webmaster = "Focusconsulting.com.vn";
	
function sendmark($emailFrom, $nameFrom, $emailTo, $nameTo, $content, $subject, $fileAttachment='', $file_name=''){
			//Get email object
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug = 0; // Kiem tra loi : 1 hien thi loi va thong bao, 2 = chi thong bao loi
			$mail->Host       = "smtp.gmail.com";
			$mail->Port       = 465;
			$mail->SMTPAuth   = true; // enable SMTP authentication
			$mail->SMTPSecure = "ssl";  // sets the prefix to the servier
			
			$mail->Username   = "mailhethong2015@gmail.com";
			$mail->Password   = ".tk21ls1";
	
			$mail->CharSet = "utf-8";
			$mail->From       = $emailFrom;
			$mail->FromName   = $nameFrom;
			$mail->Subject    = $subject;

			//$content = eregi_replace("[\]",'',$content);
			//$content = preg_replace("[\]",'',$content);

			//$mail->AddReplyTo($emailFrom, $nameFrom);

			$mail->HeaderLine('MIME-Version','1.0\r\n');
			$mail->HeaderLine('Content-type','text/html; charset=UTF-8\r\n');
			$mail->HeaderLine('To',$nameTo.'<'.$emailTo.'>\r\n');
			$mail->HeaderLine('From', $nameFrom.'<'.$emailFrom.'>\r\n');
			//$mail->HeaderLine('Reply-To', $nameFrom.'<'.$emailFrom.'>\r\n');

			

			//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->WordWrap   = 50; // set word wrap
			$mail->MsgHTML($content);
			$mail->AddAddress($emailTo, $nameTo);

			if($fileAttachment){ 
				$mail->AddAttachment($fileAttachment, $file_name);         // add attachments
				//$mail->AttachAll();
			}
			
			/*
			$arrfileAttachment=explode('|',$fileAttachment);
			$arrfile_name=explode('|',$file_name);
			for ($i=0;$i<count($arrfile_name);$i++)
			{
				if ($arrfileAttachment[$i])
				{
					$mail->AddAttachment($arrfileAttachment[$i], $arrfile_name[$i]);         // add attachments
					$mail->AttachAll();
				}
			}
			
			*/

			$mail->IsHTML(true); // send as HTML
			$ok = $mail->Send();
			
			if (!$ok)
				return 'Sorry! an email can not delivered, please contact webmaster.';//$mail->ErrorInfo
			else
				return 'ok';
}
     

} 

?>
