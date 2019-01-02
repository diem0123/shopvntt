<?php

// kiem tra logged in (khong dua kiem tra login o day nua)	
	$ap = $itech->input['ap'];
	$idp = $itech->input['idp'];	
	if(isset($_SESSION['ID_CONSULTANT']) && $_SESSION['ID_CONSULTANT'] > 0 && $idp && $ap){								
			$infomess = array();
		if($ap == 1){	
			try {
					$ArrayData  = array( 
										1 => array(1 => "STATUS", 2 => 'Active'),										
										2 => array(1 => "DATE_UPDATED", 2 => date('Y-m-d'))																				
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_COMMENT", 2 => $idp));
					$common->UpdateDB($ArrayData,"comments",$update_condit);
					$infomess['mess1'] = "success";
					$infomess['mess2'] = "approved";
					
				} catch (Exception $e) {
						//echo $e;
						$infomess['mess1'] = "nosuccess";
						$infomess['mess2'] = "noapproved";
					}
		}
		elseif($ap == 2){	
			try {
					$ArrayData  = array( 
										1 => array(1 => "STATUS", 2 => 'Deleted'),										
										2 => array(1 => "DATE_UPDATED", 2 => date('Y-m-d'))																				
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_COMMENT", 2 => $idp));
					$common->UpdateDB($ArrayData,"comments",$update_condit);
					$infomess['mess1'] = "success";
					$infomess['mess2'] = "removed";
					
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "noremoved";
				}
		}		
		echo json_encode($infomess);
		exit;
	}
	else {
		$infomess['mess1'] = "nosuccess";
		$infomess['mess2'] = "You do not permission access this page";
		echo json_encode($infomess);
		exit;
	}	 
?>