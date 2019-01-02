<?php
		// get language
		$pre = $_SESSION['pre'];
		$cat = $itech->input['cat'];
		$list_type_category = $print_2->GetDropDown("", "categories_sub", "ID_CATEGORY = '".$cat."' AND STATUS = 'Active' " , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
		$tpl = & new Template('adm_changetype_rs');		
		$tpl->set('list_type_category', $list_type_category);
		echo $tpl->fetch("adm_changetype_rs");
?>