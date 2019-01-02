<?php
		$email = $itech->input['email'];
		$infomess = array();
		$check_account = $common->getListValue("consultant","EMAIL = '".$email."'","ID_CONSULTANT");		
		
		if($check_account == "") {
			$infomess['mess1'] = "no";					
			$infomess['mess2'] = $email;			
		}
		else{
			$infomess['mess1'] = "yes";					
			$infomess['mess2'] = $email;			
		}
		
		echo json_encode($infomess);
		exit;
?>