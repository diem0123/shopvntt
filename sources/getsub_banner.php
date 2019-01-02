<?php
		$idps = $itech->input['idps'];
		//=3 muc san pham
		if($idps==3)
		$list_type_category = $print_2->GetDropDown("", "categories", "STATUS = 'Active' ", "ID_CATEGORY", "NAME_CATEGORY", "IORDER");
		else
		$list_type_category=""; 
		$tpl = & new Template('changecat_rs');		
		$tpl->set('list_ps_sub', $list_type_category);
		echo $tpl->fetch("changecat_rs");
?>