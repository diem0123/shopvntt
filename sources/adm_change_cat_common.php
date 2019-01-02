<?php
		$cat = $itech->input['cat'];
		$list_type_category = $print_2->GetDropDown("", "common_cat", "ID_TYPE = '".$cat."' AND STATUS = 'Active' "  , "ID_CAT", "SNAME", "IORDER");
		$tpl = & new Template('adm_changetype_rs');		
		$tpl->set('list_type_category', $list_type_category);
		echo $tpl->fetch("adm_changetype_rs");
?>