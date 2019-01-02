<?php
		$infomess = array();
		$countcart = 0;		
		
		$od = $itech->input['od'];
		$idp = $itech->input['idp'];
		$idcap = $itech->input['idcap'];
		$_SESSION['shopping_idcap'] .= $idcap.",";
		$list_idcap = rtrim($_SESSION['shopping_idcap'],",");
		// kiem tra con hang hay het hang
		$info_pro = $common->getInfo("capacity_product","ID_CAPACITY IN ( ".$list_idcap.") AND STATUS = 'Active'");
		if($info_pro['TINHTRANG']==0){
			$infomess['mess1'] = 'expire';		
			$infomess['mess2'] = 'thong-bao-het-hang.html';
			$_SESSION['pro_idp'] = $idp;//???
			echo json_encode($infomess);		
			exit;
		}
		$_SESSION['NAME_CAP'] = $info_pro['NAME_CAPACITY'];
		//----set giá cho từng dung tích ----
		if($info_pro['PRICE']!='')
		{
			if($info_pro['SALE'] != "" && $info_pro['SALE']>0){
					 $_SESSION['PRICE'] = number_format($info_pro['PRICE'],0,"",".");
					 $_SESSION["PRICE_SALE"] = number_format(($info_pro['PRICE']-$info_pro['PRICE']*$info_pro['SALE']/100),0,"",".");
					 $_SESSION["SALE"] = $info_pro['SALE'];
					 $_SESSION["PRICE_DISC"] = number_format($info_pro['PRICE']*$info_pro['SALE']/100,0,"",".");
				}
			else{
				$_SESSION['PRICE'] = number_format($info_pro['PRICE'],0,"",".");
				$_SESSION["PRICE_SALE"] ="";
				$_SESSION["SALE"]="";
				$_SESSION["PRICE_DISC"]="";
			}
		}
		else
		{
				 $_SESSION['PRICE'] ="";
				 $_SESSION["PRICE_SALE"] ="";
				 $_SESSION["SALE"]="";
				 $_SESSION["PRICE_DISC"]="";
		}

		
		$_SESSION['shopping_idcap_select'] = $idcap;
		
		
		//---- End set giá cho dung tích ----
		
		
		if(!isset($_SESSION['shoppingcart'])) $_SESSION['shoppingcart'] = "";
		elseif($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);		
		}
		// check idp existed in shoppingcart then not insert
		if($idp > 0 && $_SESSION['shoppingcart'] == "") {
		// set idp
		$_SESSION['shoppingcart'] = $idp;
		// set value
		$_SESSION['shoppingcart_quantity'] = "1";
		}
		elseif($idp > 0 && !in_array($idp, $binshop)){
			//$_SESSION['shopping_idcap_select'] .= ",".$idcap;
			$_SESSION['shoppingcart'] .= ",".$idp;
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