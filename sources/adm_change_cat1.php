<?php
		// get language
		$pre = $_SESSION['pre'];
		$cat = $itech->input['cat'];
		if($cat==7){
		$list_type_category = $print_2->GetDropDown("", "material_lookup", "STATUS = 'Active'"  , "ID_MT", "NAME".$pre, "IORDER");
		}
		else
		$list_type_category = $print_2->GetDropDown("", "categories_sub", "STATUS = 'Active' AND ID_CATEGORY = '".$cat."'"  , "ID_TYPE", "NAME_TYPE".$pre, "IORDER");
		$tpl = & new Template('adm_changetype_rs1');		
		$tpl->set('list_type_category', $list_type_category);
		echo $tpl->fetch("adm_changetype_rs1");
?>