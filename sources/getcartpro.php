<?php
		$infomess = array();
		$countcart = 0;		
		
		// set shopping cart
		if(!isset($_SESSION['shoppingcart'])) {
		$_SESSION['shoppingcart'] = "";
		$countcart = 0;
		}
		elseif($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);
		$countcart = count($binshop);		
		}
		else{
		$countcart = 0;		
		}
		
		//$tpl->set('countcart',$countcart);
		
		$infomess['mess1'] = $countcart;	
		$infomess['mess2'] = '';
				
		echo json_encode($infomess);		
		exit;
?>