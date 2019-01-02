<?php
/*
+--------------------------------------------------------------------------
|   Focus Consulting
|   ========================================
|   by Le Minh Than 
|      
|   ========================================
|   Web: http://www.focusconsulting.com.vn
|   Time: Friday, October 01, 2010 
|   Email: htktinternet@yahoo.com
+---------------------------------------------------------------------------
*/ 
// kiem tra logged in
	if(isset($_SESSION['uidadm'])){
		$sql = "SELECT * FROM ".$lang['tbl_jobseeker']." WHERE uid='".$_SESSION['uidadm']."'";
		$query = $DB->query( $sql );
		if ($row = $DB->fetch_row( $query ) )
		{
			$fullname=$row['fullname'];
		}
	}
	
function randomPass() {
          $chars = "abcdefghijkmnopqrstuvwxyz023456789";
         srand((double)microtime()*1000000);
         $i = 0;
         $pass = '' ;
         while ($i <= 7) {
             $num = rand() % 33;
             $tmp = substr($chars, $num, 1);
             $pass = $pass . $tmp;
             $i++;
         }
         return $pass;
     }
     if ( isset( $itech->input['submit'] ) )
{
	$username=$itech->input['username'];
	
   // $sql = "SELECT * FROM ".$lang['tbl_jobseeker']." WHERE username='".$itech->input['username']."' AND password='".md5( $itech->input['password'] )."'";
	$sql = "SELECT * FROM ".$lang['tbl_jobseeker']." WHERE username='".$itech->input['username']."'";
    $query = $DB->query( $sql );
    if ( $row = $DB->fetch_row( $query ) )
    {
            //$uidadm = $row['iId'];
           // $nameadm = $row['sFullname'];
		   //send mail
		   $table = $lang['tbl_jobseeker'];
		   $newPass = randomPass();
		   // update new password
		   $sqlp = "UPDATE ".$table." SET password='".md5($newPass)."' WHERE username = '".$itech->input['username']."' LIMIT 1;";
              $DB->query($sqlp);
			  
			  // get fullname 
              $sql = "SELECT fullname FROM ".$table." WHERE username = '".$username."';";
              $query = $DB->query($sql);
               if ($result = $DB->fetch_row($query)){
                    $fullname = $result['fullname'];
               }
			   
			   //mail
         // --- Create Message Text
		  $mailto = username;
          $content = $lang['dear'].$fullname.", <br>
                  <br>".$lang['introresetpass']."<br>
                  <br>".$lang['textloginemail'].$username." 
                  <br>".$lang['textpassword'].$newPass." <br>
                  <br>".$lang['textthanks']."<br>
                  <br>".$lang['textregards']."<br>
			<br> ".$lang['textteam']."
			  Website: <a href=http://www.focusconsulting.com.vn>www.focusconsulting.com.vn</a><br>              ";
          $subject = "Your Login Information on e-Focus Consulting";

          //$headers  = "MIME-Version: 1.0\r\n";
          //$headers .= "Content-type: text/html; charset=UTF-8\r\n";
          //$headers .= "To: ".$username." <".$username.">\r\n";
          //$headers .= "From: focusconsulting.com.vn < ".$itech->vars['email_webmaster']." >\r\n";
          // Send the message
          //echo $headers."<br>".$message;exit;
          //$ok = @mail($mailto, $subject, $message, $headers);
		  $emailfrom = "contact@focusconsulting.com.vn";
		  $namefrom = "e-Focus Consulting";
		//$emailto = $itech->vars['email_webmaster'];
		$emailto = $username;
		$mail = new emailtemp();
		/*
		echo "username=".$username;
		echo "<br>emailfrom=".$emailfrom;
		echo "<br>namefrom=".$namefrom;
		echo "<br>emailto=".$emailto;
		echo "<br>subject=".$subject;
		echo "<br>content=".$content;exit;
		*/
		$error = $mail->sendmark($emailfrom, $namefrom,$emailto,'',$content, $subject);
		if ($error=='ok'){
		$print->redirect('index.php?act=login', 'An email has been sent to inform your password.' ,'Login');
		}
		else $print->redirect('index.php?act=home', $error ,'Thanks');
    }
    else
    {
	   $username = $itech->input['username'];
       $mess  = "Login email not found.<br>";
	   // $print->redirect( "index.php?act=login", "Username or password not found!", "Login" );
    }
}    
     //header
   
      $header = & new Template('header_home');
      $header->set('title', $INFO['site_name']);
	  $header->set('fullname', $fullname);
      
     //main
       $main = & new Template('main');
      
       //************************************************ Doi tuong oj5
     
     //set for template
	/* 
     $arrtemplate5 = $print_1->arraytemp ('main1', 'main1_1', 'mainvar1_1'); // danh sach template
     $object_num5 = array('home', 'main', ''); // Ten file, vi tri de chon title?
     $main->set('main1', $print_1->ShowTemplatehome($arrtemplate5, $object_num5, 3, 'home')); 
	 */
	 
	  $tempres1=& new Template('main1');
	  $ContentShort = $print_2->GetQuote($lang['tbl_common']);		
	  $tempres1->set('quote', $ContentShort); // 6

	  $main->set('main1', $tempres1); 
     
     // Login
     
      $tempres=& new Template('resetpass');
	  $tempres->set('username',$username);
	  $tempres->set('mess',$mess);
     
      $main_temp = & new Template('mainsub1');
      $main_temp->set('main_gioithieu', $tempres); // 6
      
     $main->set('main2', $main_temp); // 6
	
  
     //footer
     $footer = & new Template('footer');
     //$footer->set('totaltime', $Debug->endTimer());
     $footer->set('counter', $print->get_counter());
     $footer->set('statistics', $print->statistics());

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
     //$tpl->set('left', $left);
     $tpl->set('main', $main);
     //$tpl->set('maintab', $maintab);
     //$tpl->set('right', $right);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('home');
?>