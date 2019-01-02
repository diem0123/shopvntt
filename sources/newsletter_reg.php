<?php

		$countget = $itech->input['countget'];
		$_SESSION['captcha'] = simple_php_captcha();
		$infomess = array();

		$infomess['mess1'] = "<img src='".$_SESSION['captcha']['image_src']."' alt='Captcha code'>";
		$infomess['mess2'] = $_SESSION['captcha']['code'];
			
		echo json_encode($infomess);
		exit;

?>
