<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$infomess['mess1'] = "nosuccess";
		$infomess['mess2'] = "Bạn không được quyền xoá!";
	}	
	 
     
		$ac = $itech->input['ac'];
		$idp = $itech->input['idp'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		$cap = $itech->input['cap'];
		
		$infomess = array();
		// set permission
		//$nlink = "adm_commonlist&idtype=".$idtype;		
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_menu', "D")){
			//$url_redidrect='index.php?act=error403';
			//$common->redirect_url($url_redidrect);				
			//exit;
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "Bạn không được quyền xoá!";			
		}
		
		elseif($idp > 0 && $ac=='d' && $cap == 1){					
				try {
					$sql = "DELETE FROM menu1 WHERE ID_CATEGORY = '".$idp."'";
					$DB->query($sql);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";
	
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Không thể xoá!";
				}								
				
		}
		elseif($idp > 0 && $ac=='d' && $cap == 2){					
				try {
					$sql = "DELETE FROM menu2 WHERE ID_TYPE = '".$idp."'";
					$DB->query($sql);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";
	
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Không thể xoá!";
				}								
				
		}
		elseif($idp > 0 && $ac=='d' && $cap == 3){					
				try {
					$sql = "DELETE FROM menu3 WHERE ID_PRO_CAT = '".$idp."'";
					$DB->query($sql);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";
	
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Không thể xoá!";
				}								
				
		}
		else {					
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Bạn không được quyền xoá!";					
				}
				
				echo json_encode($infomess);
				exit;
		
							
		

?>
