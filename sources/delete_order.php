<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     
		$ac = $itech->input['ac'];
		$idpayment = $itech->input['idpayment'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		
	
		if($idpayment > 0 && $ac=='d' && $vlid == ""){					
				try {
					$ArrayData = array( 1 => array(1 => "STATUS", 2 => "Deleted"),
								2 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))
							  );
					  
					$update_condit = array( 1 => array(1 => "ID_PAYMENT", 2 => $idpayment));
					$common->UpdateDB($ArrayData,"payments",$update_condit);
					$url_redidrect='donhang-6-quan-ly-don-hang.html?idcat=24&page='.$page;
					$common->redirect_url($url_redidrect);				
					exit;
					
				} catch (Exception $e) {
					echo $e;
				}								
				
		}
		// delete list
		elseif($ac=='d' && $vlid != ""){														
			try{
				$sql = "UPDATE payments SET STATUS = 'Deleted', UPDATED_DATE = '".date('Y-m-d H:i:s')."' WHERE ID_PAYMENT IN (".$vlid.") ";
				$DB->query($sql);

			}catch(Exception $e){echo $e;}
			//echo "sql=".$sql;exit;
			$url_redidrect='donhang-6-quan-ly-don-hang.html?idcat=24&page='.$page;
			$common->redirect_url($url_redidrect);				
			exit;					
		}				
							
	

?>
