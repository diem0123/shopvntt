<?php
		$province = $itech->input['province'];
		$dist = $itech->input['dist'];
		$list_dist = $print_2->GetDropDown($dist, "district"," ID_PROVINCE = '".$province."'" ,"ID_DIST", "NAME", "IORDER");
		$tpl = & new Template('changedist_rs2');		
		$tpl->set('list_dist', $list_dist);
		echo $tpl->fetch("changedist_rs2");
?>