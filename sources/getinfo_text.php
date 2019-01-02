<?php
		
		$idcm = $itech->input['idcm'];
		$infocm = $common->gethonhop($idcm);
		$content_decode = base64_decode($infocm['SCONTENTS'.$pre]);
		//$ct = $infocm['SCONTENTSHORT'];
		$tpl =  new Template('textcommon');		
		$tpl->set('content', $content_decode);				
		echo $tpl->fetch('textcommon');
		exit;
?>