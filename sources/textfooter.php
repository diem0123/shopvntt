<?php
		$infocm = $common->getInfo("company","ID_COM = 1 ");
		$infomess = array();
		$pre = $_SESSION['pre'];
		
		$infomess['COM_NAME'] = $infocm['COM_NAME'.$pre];
		$infomess['EMAIL'] = $infocm['EMAIL'];
		$infomess['TEL'] = $infocm['TEL'];
		$infomess['FAX'] = $infocm['FAX'];
		$infomess['MOBI'] = $infocm['MOBI'];
		$infomess['SKYPE'] = $infocm['SKYPE'];
		$infomess['YAHOO'] = $infocm['YAHOO'];
		$infomess['WEBSITE'] = $infocm['WEBSITE'];
		$infomess['ADDRESS'] = $infocm['ADDRESS'.$pre];
		
		echo json_encode($infomess);
		exit;
?>