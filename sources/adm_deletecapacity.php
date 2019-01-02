<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     
		$ac = $itech->input['ac'];
		$idcapacity = $itech->input['idcapacity'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		
		$infomess = array();
		// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(7), "D")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		if($idcapacity > 0 && $ac=='d' && $vlid == ""){					
				try {
					$ArrayData = array( 1 => array(1 => "STATUS", 2 => "Deleted")								
							  );
					  
					$update_condit = array( 1 => array(1 => "ID_CAPACITY", 2 => $idcapacity));
					$common->UpdateDB($ArrayData,"capacity_product",$update_condit);
					$infomess['mess1'] = "success";					
					$infomess['mess2'] = "";					
				
					
				} catch (Exception $e) {
					//echo $e;
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
				}								
				
		}
		// delete list
		elseif($ac=='d' && $vlid != ""){														
			try{
				$sql = "UPDATE capacity_product SET STATUS = 'Deleted' WHERE ID_CAPACITY IN (".$vlid.") ";
				$DB->query($sql);
				$infomess['mess1'] = "success";
				$infomess['mess2'] = "";

			}catch(Exception $e){
				//echo $e;
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
			}			
						
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
