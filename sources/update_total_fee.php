<?php
		$fee_trans = $itech->input['fee_trans'];
		$sale_trans = $itech->input['sale_trans'];
		if($fee_trans == "") $fee_trans = 0;
		if($sale_trans == "") $sale_trans = 0;
		
		$fee_transfer = $fee_trans - ($fee_trans*$sale_trans/100);
		
		// get info total final
		if(isset($_SESSION['totalpay_disc']) && $_SESSION['totalpay_disc'] != ""){				
			$totalpay_final = $_SESSION['totalpay_disc'] + $fee_transfer;		
		}
		else $totalpay_final = 0;
		
		$_SESSION['totalpay_final'] = $totalpay_final;
		$_SESSION['fee_transfer'] = $fee_transfer;
		$_SESSION['fee'] = $fee_trans;
		$_SESSION['sale'] = $sale_trans;
		
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type']=="phone") $tplvs = "m_update_total_fee";
		else $tplvs = "update_total_fee";
		
		$fet_final = & new Template($tplvs);		
		$fet_final->set("fee_transfer", number_format($fee_transfer,0,"","."));
		$fet_final->set("fee", number_format($fee_trans,0,"","."));
		$fet_final->set("sale", $sale_trans);
		$fet_final->set("totalpay_final", number_format($totalpay_final,0,"","."));
		$data_final = $fet_final->fetch($tplvs);
		echo $data_final;
		exit;
?>