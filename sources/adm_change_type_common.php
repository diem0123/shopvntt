<?php
		$typecat = $itech->input['typecat'];
		$infomess = array();
		$list_sub_category = $print_2->GetDropDown("", "common_cat_sub", "ID_CAT = '".$typecat."'"  , "ID_CAT_SUB", "SNAME", "IORDER");
		$tpl = & new Template('adm_changesubcat_rs');		
		$tpl->set('list_sub_category', $list_sub_category);
		if($list_sub_category == ""){
			$infomess['mess1'] = "no";
			$infomess['mess2'] = $typecat;
			echo json_encode($infomess);
			exit;
		}
		else{
			$infomess['mess1'] = $typecat;
			$infomess['mess2'] = $tpl->fetch("adm_changesubcat_rs");
			echo json_encode($infomess);
			exit;		
		}		
?>