<?php		
		$infomess = array();
		$binshop = array();
		$countcart = 0;
		
		$idp = $itech->input['idp'];
		
		if(!isset($_SESSION['shoppingcart'])) $_SESSION['shoppingcart'] = "";
		elseif($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);
		$binshop_quantity = explode(",",$_SESSION['shoppingcart_quantity']);		
		//$countcart = count($binshop);
		}
		// check idp existed in shoppingcart and remove it
		$i = 0;
		if($idp > 0 && in_array($idp, $binshop)){
			$_SESSION['shoppingcart'] = "";
			$_SESSION['shoppingcart_quantity'] = "";
			foreach($binshop as $k=>$v) {
				if($v != $idp){
					if($i==0) {
					$_SESSION['shoppingcart'] = $v;
					$_SESSION['shoppingcart_quantity'] = $binshop_quantity[$k];
					}
					else {
					$_SESSION['shoppingcart'] .= ",".$v;
					$_SESSION['shoppingcart_quantity'] .= ",".$binshop_quantity[$k];
					}
					$i++;
				}
			}			
		}
		echo "&#272;&atilde; lo&#7841;i kh&#7887;i Gi&#7887; h&agrave;ng";		
		exit;
?>