<?php
		$infomess = array();

		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "D")){
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "B&#7841;n kh&ocirc;ng c&oacute; quy&#7873;n truy c&#7853;p!";		
		}
		
		$idimg = $itech->input['id'];
		$textname = $itech->input['textname'];
		
		if($textname != "" && $idimg){	
			try {
				
				$ArrayData  = array( 1 => array(1 => "NAME".$pre, 2 => $textname)										
								  );
											
				$update_condit = array( 1 => array(1 => "ID_IMG", 2 => $idimg));
				$common->UpdateDB($ArrayData,"images_link",$update_condit);
				$infomess['mess1'] = "success";
				$infomess['mess2'] = "Update th&agrave;nh c&ocirc;ng!";
			
			} catch (Exception $e) {
				//echo $e;
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "System error!";
			}
		}
		else{
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "Text empty!";
		}
			
		echo json_encode($infomess);
		exit;	
					
?>