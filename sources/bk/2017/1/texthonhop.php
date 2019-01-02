<?php
		$idcm = $itech->input['idcm'];
		$infocm = $common->gethonhop($idcm);
		//$content_decode = base64_decode($infocm['SCONTENTS']);
		$ct = $infocm['SCONTENTSHORT'];
		$tpl =  new Template('texthonhop');		
		$tpl->set('ct', $ct);				
		echo $tpl->fetch('texthonhop');
		exit;
?>