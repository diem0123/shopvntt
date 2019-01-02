<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     
		$ac = $itech->input['ac'];
		$idp = $itech->input['idp'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		
		$infomess = array();
		// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_account', "D")){
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "Bạn không được quyền xoá!";
			//$url_redidrect='index.php?act=error403';
			//$common->redirect_url($url_redidrect);				
			//exit;		
		}
		
		elseif($idp > 0 && $ac=='d' && $vlid == ""){					
				try {
					$ArrayData = array( 1 => array(1 => "STATUS", 2 => "Deleted"),
								2 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))
							  );
					  
					$update_condit = array( 1 => array(1 => "ID_CONSULTANT", 2 => $idp));
					$common->UpdateDB($ArrayData,"consultant",$update_condit);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";
					//$url_redidrect='index.php?act=adm_cus_payment&idc=5&page='.$page;
					//$common->redirect_url($url_redidrect);
					// return after submited
					//echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
				
					
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Không thể xoá!";
				}								
				
		}
		// delete list
		elseif($ac=='d' && $vlid != ""){														
			try{
				$sql = "UPDATE consultant SET STATUS = 'Deleted', UPDATED_DATE = '".date('Y-m-d H:i:s')."' WHERE ID_CONSULTANT IN (".$vlid.") ";
				$DB->query($sql);
				$infomess['mess1'] = "success";
				$infomess['mess2'] = "";

			}catch(Exception $e){
				//echo $e;
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "Không thể xoá!";
			}
			//echo "sql=".$sql;exit;
			//$url_redidrect='donhang-6-quan-ly-don-hang.html?idcat=24&page='.$page;
			//$common->redirect_url($url_redidrect);
			// return after submited
			//echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
						
		}
		else {
					//echo $lang['not_del_products'];
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "Bạn không được quyền xoá!";
					//$infomess['mess2'] = "vlid=".$vlid;
				}
				
				echo json_encode($infomess);
				exit;
		
							
		

?>
