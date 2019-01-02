<?php
		// get language
		$pre = $_SESSION['pre'];
		$typecat = $itech->input['typecat'];	
		$list_sub_category = $print_2->GetDropDown("", "product_categories", "STATUS = 'Active' AND ID_TYPE = '".$typecat."'"  , "ID_PRO_CAT", "NAME_PRO_CAT".$pre, "IORDER");
		$tpl = & new Template('adm_changesubcat_rs1');		
		$tpl->set('list_sub_category', $list_sub_category);
		echo $tpl->fetch("adm_changesubcat_rs1");
				
?>