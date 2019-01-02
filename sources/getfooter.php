<?php
		$getscreen = $itech->input['getscreen'];
		if($getscreen <= 1024) $temp = "footer_1024";
		else $temp = "footer_l1024";
		$tpl =  new Template($temp);		
		$tpl->set('statistics', $print->statistics());
		$tpl->set('todayaccess', $print->get_today( ));
		$tpl->set('counter', $print->get_counter());
		echo $tpl->fetch($temp);
		exit;
?>