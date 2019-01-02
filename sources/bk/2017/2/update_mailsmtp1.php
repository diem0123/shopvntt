<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'update_mailsmtp', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	 
	 $header = $common->getHeader('adm_header');
	// get sub mennu
	$submenu = & new Template('submenu_setting'); 
	$submenu->set('sub','');
    
     //main
     $main = & new Template('add_mailsmtp'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$ids = $itech->input['ids'];
		
		$fromname = $itech->input['fromname'];		
		$hostname = $itech->input['hostname'];
		
		$port = $itech->input['port'];		
		$smtptype = $itech->input['smtptype'];
		
		$emailsmtp = $itech->input['emailsmtp'];		
		$pass = $itech->input['pass'];

	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="aaaaaaa" && $submit == "" && !$ids) {
					$main->set('emailsmtp', "");
					$main->set('pass', "");					
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m email SMTP");
					$main->set('ac', "a");
					$main->set('ids', "");

			echo $main->fetch("add_mailsmtp");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $ids) {
					$catinfo = $common->getInfo("outgoing_email","OUTGOING_EMAIL_ID = '".$ids."'");
					$main->set('fromname', $catinfo['FROM_NAME']);					
					$main->set('hostname', $catinfo['SMTP_SERVER']);
					$main->set('port', $catinfo['SMTP_PORT']);
					$main->set('smtptype', $catinfo['SMTP_TYPE']);
					if($catinfo['SMTP_TYPE'] == 'ssl'){
						$s1 = "selected";
						$s2 = "";
					}
					elseif($catinfo['SMTP_TYPE'] == 'tls'){
						$s1 = "";
						$s2 = "selected";
					}
					
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					
					$main->set('emailsmtp', $catinfo['SMTP_USER']);
					$main->set('pass', $catinfo['SMTP_PASS']);
					$main->set('pass1', $catinfo['SMTP_PASS']);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh email SMTP");
					$main->set('ac', "e");
					$main->set('ids', $ids);

			//echo $main->fetch("add_mailsmtp");
			//exit;
		}
		elseif($ac=="v" && $submit == "" && $ids) {
			 $main = & new Template('view_mailsmtp');
					$catinfo = $common->getInfo("outgoing_email","OUTGOING_EMAIL_ID = '".$ids."'");
					$main->set('fromname', $catinfo['FROM_NAME']);					
					$main->set('hostname', $catinfo['SMTP_SERVER']);
					$main->set('port', $catinfo['SMTP_PORT']);
					$main->set('smtptype', $catinfo['SMTP_TYPE']);										
					$main->set('emailsmtp', $catinfo['SMTP_USER']);
					$main->set('pass', $catinfo['SMTP_PASS']);

			//echo $main->fetch("view_mailsmtp");
			//exit;
		}
		// submit add 			
		elseif($ac=="aaaaa"  && $submit != "" && !$ids) {

		// Begin input data
		// add SMTP
				try {
					$ArrayData = array( 1 => array(1 => "SMTP_SERVER", 2 => $hostname),
										2 => array(1 => "SMTP_USER", 2 => $emailsmtp),
										3 => array(1 => "SMTP_PASS", 2 => $pass)										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"outgoing_email");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	//echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $ids) {

		// Begin update data
		
				try {
					$ArrayData = array( 
										1 => array(1 => "FROM_NAME", 2 => $fromname),
										2 => array(1 => "SMTP_SERVER", 2 => $hostname),
										3 => array(1 => "SMTP_PORT", 2 => $port),
										4 => array(1 => "SMTP_TYPE", 2 => $smtptype),
										5 => array(1 => "SMTP_USER", 2 => $emailsmtp),
										6 => array(1 => "SMTP_PASS", 2 => $pass)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "OUTGOING_EMAIL_ID", 2 => $ids));
					$common->UpdateDB($ArrayData,"outgoing_email",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.location.href='index.php?act=update_mailsmtp&ac=v&ids=1';</script>";
	
	}
	
	 //footer
     $footer = & new Template('adm_footer');
	 $footer->set('statistics1', '');

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
	 $tpl->set('submenu', $submenu);
     $tpl->set('main', $main);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('adm_home');

?>
