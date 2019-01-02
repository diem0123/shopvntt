<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     
		$ac = $itech->input['ac'];
		$idlike = $itech->input['idlike'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		
	
		if($idlike > 0 && $ac=='d' && $vlid == ""){					
				try {
				
					$sql = "DELETE FROM products_like WHERE ID_LIKE = '".$idlike."' AND ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'";
					$DB->query($sql);
					
					$url_redidrect='san-pham-da-luu.html?page='.$page;
					$common->redirect_url($url_redidrect);				
					exit;
					
				} catch (Exception $e) {
					echo $e;
				}								
				
		}
		// delete list
		elseif($ac=='d' && $vlid != ""){														
			try{				
				$sql = "DELETE FROM products_like WHERE ID_LIKE IN (".$vlid.") AND ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'";
				$DB->query($sql);

			}catch(Exception $e){echo $e;}
			//echo "sql=".$sql;exit;
			$url_redidrect='san-pham-da-luu.html?page='.$page;
			$common->redirect_url($url_redidrect);				
			exit;					
		}				
							
		

?>
