<?php

// kiem tra logged in (khong dua kiem tra login o day nua)	
	$order = $itech->input['order'];	
	if(isset($_SESSION['ID_CONSULTANT']) && $_SESSION['ID_CONSULTANT'] > 0 && $order){								
			$infomess = array();
			// get info user			
			$info_cst = $common->getInfo("consultant","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
			$infomess['FULL_NAME'] = $info_cst['FULL_NAME'];
			$infomess['TEL'] = $info_cst['TEL'];
			$infomess['EMAIL'] = $info_cst['EMAIL'];
			$infomess['ID_PROVINCE'] = $info_cst['ID_PROVINCE'];
			$infomess['ID_DIST'] = $info_cst['ID_DIST'];
			$infomess['ADDRESS'] = $info_cst['ADDRESS'];
			$infomess['mess1'] = "success";
			
			echo json_encode($infomess);
			exit;
	}
	else {
		$infomess['mess1'] = "nosuccess";
		$infomess['mess2'] = "Kh&ocirc;ng th&#7875; l&#7845;y th&ocirc;ng tin user. Vui l&ograve;ng li&ecirc;n h&#7879; ch&uacute;ng t&ocirc;i &#273;&#7875; &#273;&#432;&#7907;c h&#7895; tr&#7907;.";
		echo json_encode($infomess);
		exit;
	}	 
?>