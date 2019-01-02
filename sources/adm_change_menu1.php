<?php
		// get language
		$pre = $_SESSION['pre'];
		$cat = $itech->input['cat'];
		
		$list_type_category = $print_2->GetDropDown("", "menu2", "STATUS = 'Active' AND ID_CATEGORY = '".$cat."'"  , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
		$tpl = & new Template('adm_changetype_menu1');		
		$tpl->set('list_type_category', $list_type_category);
		echo $tpl->fetch("adm_changetype_menu1");
?>