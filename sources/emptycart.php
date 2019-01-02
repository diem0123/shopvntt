<?php				
		$infomess = array();
		$st = $itech->input['st'];
		if($st == "emptycart") {
			unset($_SESSION['shoppingcart']);
			unset($_SESSION['shoppingcart_quantity']);
			echo "&#272;&atilde; h&#7911;y to&agrave;n b&#7897; Gi&#7887; h&agrave;ng";		
			exit;
		}
		else{
			echo "Kh&ocirc;ng th&#7875; h&#7911;y Gi&#7887; h&agrave;ng";		
			exit;
		}
?>