<?php
		$subcat = $itech->input['subcat'];
		$infomess = array();
		$procat_info = $common->getInfo("product_categories","ID_PRO_CAT ='".$subcat."'");
		// display select for ages and skill in cat=1 tomiki toys
		if($procat_info['ID_CATEGORY'] == 1) {
		$infomess['mess1'] = "success";
		$infomess['mess2'] = $subcat;
		echo json_encode($infomess);
		exit;
		}		
		else {
		$infomess['mess1'] = "no";
		$infomess['mess2'] = "";
		echo json_encode($infomess);
		exit;
		}
				
?>