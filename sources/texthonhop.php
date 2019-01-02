<?php
		$idcm = $itech->input['idcm'];
		$infocm = $common->gethonhop($idcm);
		// get language
		$pre = $_SESSION['pre'];
		
		//$content_decode = base64_decode($infocm['SCONTENTS']);
		$ct = $infocm['SCONTENTSHORT'.$pre];
		$tpl =  new Template('texthonhop');		
		$tpl->set('ct', $ct);				
		echo $tpl->fetch('texthonhop');
		exit;
?>