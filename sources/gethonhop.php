<?php
		$idcm = $itech->input['idcm'];
		$infocm = $common->gethonhop($idcm);
		$content_decode = base64_decode($infocm['SCONTENTS']);
		$tpl =  new Template($temp);		
		$tpl->set('statistics', $print->statistics());
		$tpl->set('todayaccess', $print->get_today( ));
		$tpl->set('counter', $print->get_counter());
		echo $tpl->fetch($temp);
		exit;
?>