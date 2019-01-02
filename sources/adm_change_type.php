<?php
		// get language
		$pre = $_SESSION['pre'];
		$typecat = $itech->input['typecat'];	
		$list_sub_category = $print_2->GetDropDown("", "product_categories", "ID_TYPE = '".$typecat."' AND STATUS = 'Active' "  , "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");
		$tpl = & new Template('adm_changesubcat_rs');		
		$tpl->set('list_sub_category', $list_sub_category);
		echo $tpl->fetch("adm_changesubcat_rs");
				
?>