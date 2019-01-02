<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_deleteproduct'); 	 
	 $pathimg = $itech->vars['pathimg'];
				
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];		
		$idp = $itech->input['idp'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		
		$infomess = array();
		
	// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "D")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');		

					$pagetitle = $itech->vars['site_name'];
					$main->set("title", $pagetitle);
	
				if($idp > 0 && $ac=='d' && $vlid == ""){					

					// check permission com incharge owner
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$url_redidrect='index.php?act=error403';
						$common->redirect_url($url_redidrect);				
						exit;
					}
				
						try {
							$ArrayData = array( 1 => array(1 => "STATUS", 2 => "Deleted"),
										2 => array(1 => "DATE_UPDATE", 2 => date('Y-m-d'))
									  );
							  
							$update_condit = array( 1 => array(1 => "ID_PRODUCT", 2 => $idp));
							$common->UpdateDB($ArrayData,"products",$update_condit);
							$infomess['mess1'] = "success";
							$infomess['mess2'] = "";
							
						} catch (Exception $e) {
							//echo $e;
							$infomess['mess1'] = "nosuccess";
							$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
						}
					//echo $lang['del_products'];
					//$main->set("mess", $lang['del_products']);
					//echo $main->fetch("adm_deleteproduct");
				}
				// delete list
				elseif($ac=='d' && $vlid != ""){				
					// check permission com incharge owner
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$url_redidrect='index.php?act=error403';
						$common->redirect_url($url_redidrect);				
						exit;
					}
					
					try{
						$sql = "UPDATE products SET STATUS = 'Deleted', DATE_UPDATE = '".date('Y-m-d')."' WHERE ID_PRODUCT IN (".$vlid.") ";
						$DB->query($sql);
						$infomess['mess1'] = "success";
						$infomess['mess2'] = "";

					}catch(Exception $e){
						//echo $e;
						$infomess['mess1'] = "nosuccess";
						$infomess['mess2'] = "L&#7895;i h&#7879; th&#7889;ng, kh&ocirc;ng th&#7875; x&oacute;a!";
					}
					
					//$url_redidrect='index.php?act=adm_products&page='.$page;
					//$common->redirect_url($url_redidrect);				
					//exit;					
				}
				else {
					//echo $lang['not_del_products'];
					$infomess['mess1'] = "nosuccess";
					$infomess['mess2'] = "B&#7841;n kh&ocirc;ng &#273;&#432;&#7907;c ph&eacute;p x&oacute;a!";
					//$infomess['mess2'] = "vlid=".$vlid;
				}
				
				echo json_encode($infomess);
				exit;
				
				//sleep(25);	
					// return after submited
			//	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
							
		

?>
