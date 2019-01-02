<?php
		
		
		$stt = $itech->input['stt'];		
		$infomess = array();
		$pre = $_SESSION['pre'];
		$_SESSION['tab'] = $stt;

		$infomess['sele'] = "active";
		$infomess['nosele'] = "";

		echo json_encode($infomess);
		exit;
?>