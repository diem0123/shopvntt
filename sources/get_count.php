<?php		
		$infomess = array();		
		$infomess['soline'] = $print->statistics();
		$infomess['stoday'] = $print->get_today();
		$infomess['stotal'] = $print->get_counter();
		// get hotline
		$hotline = $common->gethonhop(1);
		$infomess['hotline'] = $hotline['SCONTENTSHORT'];		
		echo json_encode($infomess);
		exit;
?>