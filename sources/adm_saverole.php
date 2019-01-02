<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idr = $itech->input['idr'];
		$rolename = $itech->input['rolename'];
		$roletype = $itech->input['roletype'];
		$level = $itech->input['level'];
	
		$sl1 = "";
		$sl2 = "";
		$s1 = "";
		$s2 = "";
		$s3 = "";
		$s4 = "";
		$s5 = "";
		
	// set permission	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_roles', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}	
	
	$pagetitle = $itech->vars['site_name'];	

		$iorder = $common->getListValue("role_lookup","1 ORDER BY ROLE_ID DESC","ROLE_ID") + 1;

		if ($ac=="a" && $submit != "")
		{
		
			try {
				$ArrayData = array( 1 => array(1 => "ROLE_TYPE", 2 => $roletype),
										2 => array(1 => "LEVEL", 2 => $level),
										3 => array(1 => "NAME", 2 => $rolename),
										4 => array(1 => "IORDER", 2 => $iorder),
										5 => array(1 => "CREATED_DATE", 2 => date('Y-m-d H:i:s')),
										6 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))										
									  );
							  
					$idr = $common->InsertDB($ArrayData,"role_lookup");

			} catch (Exception $e) {
							echo $e;
						}
			
			//Add Role Has permission
			$list_feature = $common->getListRecord("feature_lookup","1","IORDER");			
			while ($rs=$DB->fetch_row($list_feature))
			{
				$ae=($itech->input['r_'.$rs['FEATURE_ID'].'_a']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_a'];
				$v=($itech->input['r_'.$rs['FEATURE_ID'].'_v']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_v'];
				$d=($itech->input['r_'.$rs['FEATURE_ID'].'_d']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_d'];
				$ex=($itech->input['r_'.$rs['FEATURE_ID'].'_ex']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_ex'];
				$da=($itech->input['r_'.$rs['FEATURE_ID'].'_da']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_da'];
				
				try {
					$ArrayData1 = array( 1 => array(1 => "FEATURE_ID", 2 => $rs['FEATURE_ID']),
										2 => array(1 => "ROLE_ID", 2 => $idr),
										3 => array(1 => "AE", 2 => $ae),
										4 => array(1 => "V", 2 => $v),
										5 => array(1 => "D", 2 => $d),
										6 => array(1 => "EX", 2 => $ex),
										7 => array(1 => "DATA_ACCESS", 2 => $da)										
									  );
					if ($ae!="" || $v!="" || $d!="" || $ex!="" || $da!="")		  
					$idr_pms = $common->InsertDB($ArrayData1,"role_has_permission");					
				
				} catch (Exception $e) {
							echo $e;
						}		
			}
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
		}
		else 
		if ($ac=="e" && $submit != "" && $idr)
		{
						try {
								$ArrayData = array( 1 => array(1 => "ROLE_TYPE", 2 => $roletype),
													2 => array(1 => "LEVEL", 2 => $level),
													3 => array(1 => "NAME", 2 => $rolename),										
													4 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))										
									  );
							  								
								$update_condit = array( 1 => array(1 => "ROLE_ID", 2 => $idr));
								$common->UpdateDB($ArrayData,"role_lookup",$update_condit);
							
						} catch (Exception $e) {
							echo $e;
						}

			
			$list_feature = $common->getListRecord("feature_lookup","1","IORDER");
			$num=0;
			$strInput="";						
			while ($rs=$DB->fetch_row($list_feature))
			{
				$ae=($itech->input['r_'.$rs['FEATURE_ID'].'_a']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_a'];
				$v=($itech->input['r_'.$rs['FEATURE_ID'].'_v']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_v'];
				$d=($itech->input['r_'.$rs['FEATURE_ID'].'_d']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_d'];
				$ex=($itech->input['r_'.$rs['FEATURE_ID'].'_ex']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_ex'];
				$da=($itech->input['r_'.$rs['FEATURE_ID'].'_da']=="")?0:$itech->input['r_'.$rs['FEATURE_ID'].'_da'];
				
				$vl_check = $common->getListValue("role_has_permission","ROLE_ID = ".$idr." AND FEATURE_ID = ".$rs['FEATURE_ID']." LIMIT  0,1","ROLE_PERMISSION_ID");
				if ($vl_check == "")
				{
					try {
						$ArrayData1 = array( 1 => array(1 => "FEATURE_ID", 2 => $rs['FEATURE_ID']),
											2 => array(1 => "ROLE_ID", 2 => $idr),
											3 => array(1 => "AE", 2 => $ae),
											4 => array(1 => "V", 2 => $v),
											5 => array(1 => "D", 2 => $d),
											6 => array(1 => "EX", 2 => $ex),
											7 => array(1 => "DATA_ACCESS", 2 => $da)										
									  );
						if ($ae!="" || $v!="" || $d!="" || $ex!="" || $da!=""){		  
							$idr_pms = $common->InsertDB($ArrayData1,"role_has_permission");
							if ($num==0)
								$strInput = $rs['FEATURE_ID'];
							else
								$strInput .=",".$rs['FEATURE_ID'];
						}
										
					} catch (Exception $e) {
							echo $e;
						}	
				}
				else 
				{
					if ($ae !="" || $v !="" || $d !="" || $ex !="" || $da !="")
					{
						try{
							$ArrayData2 = array(1 => array(1 => "AE", 2 => $ae),
											2 => array(1 => "V", 2 => $v),
											3 => array(1 => "D", 2 => $d),
											4 => array(1 => "EX", 2 => $ex),
											5 => array(1 => "DATA_ACCESS", 2 => $da)										
									  );
							
							$update_condit = array( 1 => array(1 => "ROLE_PERMISSION_ID", 2 => $vl_check));
							$common->UpdateDB($ArrayData2,"role_has_permission",$update_condit);
													
						} catch (Exception $e) {
							echo $e;
						}
					}
					else
					{
						try{
							$sql = "DELETE FROM role_has_permission WHERE ROLE_PERMISSION_ID = '".$vl_check."'";
							$DB->query($sql);
						
						} catch (Exception $e) {
							echo $e;
						}	 
					}
					if ($num == 0)
						$strInput = $rs['FEATURE_ID'];
					else
						$strInput .= ",".$rs['FEATURE_ID'];
				}
				$num++;
			}
			if ($strInput == "")
				$strInput = "''";
			
			$strInput = trim($strInput,",");
			try{
				$sql = "DELETE FROM role_has_permission WHERE ROLE_ID = '".$idr."' AND FEATURE_ID NOT IN (".$strInput.")";
				$DB->query($sql);
			
			//echo $sql;exit;			
			}catch (Exception $e){
					echo $e;
			}
			
			//echo $strInput;exit;		 
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
		}
		else
		if ($ac=="d")
		{
			try{
				$sql = "DELETE FROM role_lookup WHERE ROLE_ID = '".$idr."'";
				$DB->query($sql);
			
			}catch (Exception $e){
					echo $e;
			}
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
		}

?>
