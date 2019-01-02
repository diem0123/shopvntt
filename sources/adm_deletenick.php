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
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(7), "D")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		if($idp > 0 && $ac=='d' && $vlid == ""){					
				try {
					$sql = "DELETE FROM consultant_has_chat WHERE CONSULTANT_CHAT_ID = '".$idp."'";
					$DB->query($sql);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";
					//$url_redidrect='index.php?act=adm_cus_payment&idc=5&page='.$page;
					//$common->redirect_url($url_redidrect);
					// return after submited
					//echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
				
					
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
				}								
				
		}
		// delete list
		elseif($ac=='d' && $vlid != ""){														
			try{
				//$sql = "UPDATE consultant SET STATUS = 'Deleted', UPDATED_DATE = '".date('Y-m-d H:i:s')."' WHERE ID_CONSULTANT IN (".$vlid.") ";
				$sql = "DELETE FROM consultant_has_chat WHERE CONSULTANT_CHAT_ID IN (".$vlid.") ";
				$DB->query($sql);
				$infomess['mess1'] = "success";
				$infomess['mess2'] = "";

			}catch(Exception $e){
				//echo $e;
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
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
					$infomess['mess2'] = "B&#7841;n kh&ocirc;ng &#273;&#432;&#7907;c ph&eacute;p x&oacute;a!";
					//$infomess['mess2'] = "vlid=".$vlid;
				}
				
				echo json_encode($infomess);
				exit;
		
							
		

?>
