<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(10), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	 
     //main
     $main = & new Template('add_reseller'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idrs = $itech->input['idrs'];
						
		$idmn = $itech->input['idmn'];
		$idrole = $itech->input['idrole'];
		
		$sale = $itech->input['sale'];
		$ttkg = $itech->input['ttkg'];
		$timeyc = $itech->input['timeyc'];
		
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		
		// process string to int
		$price_tem = explode(".",$ttkg);
		$price_str = "";
		for($i1=0;$i1<count($price_tem);$i1++){
			$price_str .= $price_tem[$i1];
		}
		
		$ttkg = (int)$price_str;				
		if($sale == "") $sale = 0;		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idrs) {										
					$list_mn = $print_2->GetDropDown($idmn, "money_transfer_lookup","" ,"ID_MONEY_TRANS", "NAME", "IORDER");
					$list_role = $print_2->GetDropDown($idmn, "role_lookup"," ROLE_ID IN (3,4) " ,"ROLE_ID", "NAME", "IORDER");
															
					$main->set('list_mn', $list_mn);
					$main->set('list_role', $list_role);
					
					$main->set('sale', "");
					$main->set('ttkg', "");
					$main->set('timeyc', "");
					$main->set('content', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m ch&iacute;nh s&aacute;ch &#272;&#7841;i l&yacute;");
					$main->set('ac', "a");
					$main->set('idrs', "");

			echo $main->fetch("add_reseller");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idrs) {
					$catinfo = $common->getInfo("discount_reseller_lookup","ID_RS_DISCOUNT = '".$idrs."'");					
					$list_mn = $print_2->GetDropDown($catinfo['ID_MONEY_TRANS'], "money_transfer_lookup","" ,"ID_MONEY_TRANS", "NAME", "IORDER");
					$list_role = $print_2->GetDropDown($catinfo['TYPE_ACCOUNT'], "role_lookup"," ROLE_ID IN (3,4) " ,"ROLE_ID", "NAME", "IORDER");
										
					$main->set('list_mn', $list_mn);
					$main->set('list_role', $list_role);
					
					$main->set('sale', $catinfo['PERCENT']);
					$main->set('ttkg', $catinfo['TOTAL_KYGUI']);
					$main->set('timeyc', $catinfo['TIME']);
					
					$content_decode = base64_decode($catinfo['NAME'.$pre]);
					$main->set('content', $content_decode);	
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh ch&iacute;nh s&aacute;ch &#272;&#7841;i l&yacute;");
					$main->set('ac', "e");
					$main->set('idrs', $idrs);

			echo $main->fetch("add_reseller");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idrs) {
			 $main = & new Template('view_reseller');
					$catinfo = $common->getInfo("discount_reseller_lookup","ID_RS_DISCOUNT = '".$idrs."'");					
					// get value money transfer
					$moneyinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$catinfo['ID_MONEY_TRANS']."'");
					$main->set( "namemn", $moneyinfo['NAME']);
					// get type customer
					$cusinfo = $common->getInfo("role_lookup","ROLE_ID = '".$catinfo['TYPE_ACCOUNT']."'");
					$main->set( "namecus", $cusinfo['NAME']);
					
					$main->set('sale', $catinfo['PERCENT']);
					$main->set('ttkg', $catinfo['TOTAL_KYGUI']);
					$main->set('timeyc', $catinfo['TIME']);
					$content_decode = base64_decode($catinfo['NAME'.$pre]);
					$main->set('content', $content_decode);

			echo $main->fetch("view_reseller");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idrs) {

		// Begin input data
		// add Category		
				try {
					$ArrayData = array( 1 => array(1 => "TYPE_ACCOUNT", 2 => $idrole),										
										2 => array(1 => "NAME", 2 => $contentencode),
										3 => array(1 => "PERCENT", 2 => $sale),
										4 => array(1 => "TIME", 2 => $timeyc),
										5 => array(1 => "TOTAL_KYGUI", 2 => $ttkg),										
										6 => array(1 => "ID_MONEY_TRANS", 2 => $idmn)
										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"discount_reseller_lookup");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idrs) {

		// Begin update data
				try {
					$ArrayData = array( 1 => array(1 => "TYPE_ACCOUNT", 2 => $idrole),										
										2 => array(1 => "NAME", 2 => $contentencode),
										3 => array(1 => "PERCENT", 2 => $sale),
										4 => array(1 => "TIME", 2 => $timeyc),
										5 => array(1 => "TOTAL_KYGUI", 2 => $ttkg),										
										6 => array(1 => "ID_MONEY_TRANS", 2 => $idmn)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_RS_DISCOUNT", 2 => $idrs));
					$common->UpdateDB($ArrayData,"discount_reseller_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
