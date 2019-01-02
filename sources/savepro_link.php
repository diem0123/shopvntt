<?php
		$infomess = array();		
		
		$od = $itech->input['od'];
		$idp = $itech->input['idp'];
		$autos = $itech->input['autos'];
		
	// kiem tra user da login chua
	if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$_SESSION['pro_like'] = $idp;
		$infomess['mess1'] = 'notlogin';		
		$infomess['mess2'] = 'dangnhap.html';
		echo json_encode($infomess);		
		exit;
		
	}
		// kiem tra san pham da luu hay chua luu
		$info_pro = $common->getInfo("products_like","ID_PRODUCT = '".$idp."' AND ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
		
		if($info_pro==""){
			
		//insert idp
			try {
					$ArrayData = array( 1 => array(1 => "ID_CONSULTANT", 2 => $_SESSION['ID_CONSULTANT']),
										2 => array(1 => "ID_PRODUCT", 2 => $idp),
										3 => array(1 => "CREATED_DATE", 2 => date('Y-m-d H:i:s'))										
									  );
							  
					$idlike = $common->InsertDB($ArrayData,"products_like");
						
				} catch (Exception $e) {
						echo $e;
					}
				// check luu tu dong tu login
				if($autos ==1){
					$url_redidrect='san-pham-da-luu.html';
					$common->redirect_url($url_redidrect);
				}
				else{				
					$infomess['mess1'] = 'loggedin';		
					$infomess['mess2'] = 'san-pham-da-luu.html';
					
					echo json_encode($infomess);		
					exit;
				}
		}
		else{
		
			// check luu tu dong tu login
				if($autos ==1){
					$url_redidrect='san-pham-da-luu.html';
					$common->redirect_url($url_redidrect);
				}
				else{				
					$infomess['mess1'] = 'loggedin';		
					$infomess['mess2'] = 'san-pham-da-luu.html';
					
					echo json_encode($infomess);		
					exit;
				}
		
		}
						
?>