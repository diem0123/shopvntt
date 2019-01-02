<?php
		$infomess = array();
		$countcart = 0;		
		
		$od = $itech->input['od'];
		$idp = $itech->input['idp'];
		$numql = $itech->input['numql'];
		
		// kiem tra con hang hay het hang
		$info_pro = $common->getInfo("products","ID_PRODUCT = '".$idp."' AND STATUS = 'Active'");
		if($info_pro['EXPIRE']==1){
			$infomess['mess1'] = 'expire';		
			$infomess['mess2'] = 'thong-bao-het-hang.html';
			$_SESSION['pro_idp'] = $idp;
			echo json_encode($infomess);		
			exit;
		}
		
		if(!isset($_SESSION['shoppingcart'])) $_SESSION['shoppingcart'] = "";
		elseif($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);		
		}
		// check idp existed in shoppingcart then not insert
		if($idp > 0 && $_SESSION['shoppingcart'] == "") {
			// set idp
			$_SESSION['shoppingcart'] = $idp;
			// set value
			if($numql != "")
			$_SESSION['shoppingcart_quantity'] = "$numql";
			else
			$_SESSION['shoppingcart_quantity'] = "1";
		
		}
		elseif($idp > 0 && !in_array($idp, $binshop)){
			$_SESSION['shoppingcart'] .= ",".$idp;
			// set value
			if($numql != "")
			$_SESSION['shoppingcart_quantity'] .= ",$numql";
			else
			$_SESSION['shoppingcart_quantity'] .= ",1";
		
		}
		
		// get count cart
		if($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);
		$countcart = count($binshop);
		}
		else $countcart = 0;
		// get go to order or input shopping cart
		if($od){
			$infomess['mess1'] = 'order';		
			$infomess['mess2'] = 'thong-tin-gio-hang.html';
		}
		else{
			//$infomess['mess1'] = '<a href="thong-tin-gio-hang.html" class="style1">Gi&#7887; h&agrave;ng ('.$countcart.')</a>';
			$infomess['mess1'] = $countcart;	
			$infomess['mess2'] = '&#272;&atilde; th&ecirc;m v&agrave;o gi&#7887; h&agrave;ng';
		}
		echo json_encode($infomess);		
		exit;
?>