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
     $main = & new Template('add_disc_transfer'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$iddis = $itech->input['iddis'];
		
		$idpr = $itech->input['idpr'];
		$idd = $itech->input['idd'];
		$idtr = $itech->input['idtr'];
		$idmn = $itech->input['idmn'];
		$idrole = $itech->input['idrole'];
		
		$sale = $itech->input['sale'];
		$fee = $itech->input['fee'];				
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];
		
		// process string to int
		$price_tem = explode(".",$fee);
		$price_str = "";
		for($i1=0;$i1<count($price_tem);$i1++){
			$price_str .= $price_tem[$i1];
		}
		
		$fee = (int)$price_str;				
		if($sale == "") $sale = 0;		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$iddis) {
					$list_provinces = $print_2->GetDropDown($idpr, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
					//$list_dist = $print_2->GetDropDown($idd, "district"," ID_PROVINCE = '".$idpr."'" ,"ID_DIST", "NAME", "IORDER");
					$list_tr = $print_2->GetDropDown($idtr, "type_transfer","" ,"ID_TRANSFER", "NAME_TRANSFER", "IORDER");
					$list_mn = $print_2->GetDropDown($idmn, "money_transfer_lookup","" ,"ID_MONEY_TRANS", "NAME", "IORDER");
					$list_role = $print_2->GetDropDown($idmn, "role_lookup"," ROLE_ID IN (3,4,5) " ,"ROLE_ID", "NAME", "IORDER");
					
					$main->set('list_provinces', $list_provinces);
					$main->set('list_dist', "");
					$main->set('list_tr', $list_tr);
					$main->set('list_mn', $list_mn);
					$main->set('list_role', $list_role);
					
					$main->set('sale', "");
					$main->set('fee', "");
					$main->set('content', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m chi&#7871;t kh&#7845;u v&#7853;n chuy&#7875;n");
					$main->set('ac', "a");
					$main->set('iddis', "");

			echo $main->fetch("add_disc_transfer");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $iddis) {
					$catinfo = $common->getInfo("discount_transfer_lookup","ID_DISCOUNT = '".$iddis."'");
					$list_provinces = $print_2->GetDropDown($catinfo['ID_PROVINCE'], "province","" ,"ID_PROVINCE", "NAME", "IORDER");
					$list_dist = $print_2->GetDropDown($catinfo['ID_DIST'], "district"," ID_PROVINCE = '".$catinfo['ID_PROVINCE']."'" ,"ID_DIST", "NAME", "IORDER");
					$list_tr = $print_2->GetDropDown($catinfo['ID_TRANSFER'], "type_transfer","" ,"ID_TRANSFER", "NAME_TRANSFER", "IORDER");
					$list_mn = $print_2->GetDropDown($catinfo['ID_MONEY_TRANS'], "money_transfer_lookup","" ,"ID_MONEY_TRANS", "NAME", "IORDER");
					$list_role = $print_2->GetDropDown($catinfo['TYPE_ACCOUNT'], "role_lookup"," ROLE_ID IN (3,4,5) " ,"ROLE_ID", "NAME", "IORDER");
					
					$main->set('list_provinces', $list_provinces);
					$main->set('list_dist', $list_dist);
					$main->set('list_tr', $list_tr);
					$main->set('list_mn', $list_mn);
					$main->set('list_role', $list_role);
					
					$main->set('sale', $catinfo['PERCENT']);
					$main->set('fee', $catinfo['FEE']);
					$content_decode = base64_decode($catinfo['NAME'.$pre]);
					$main->set('content', $content_decode);	
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh chi&#7871;t kh&#7845;u v&#7853;n chuy&#7875;n");
					$main->set('ac', "e");
					$main->set('iddis', $iddis);

			echo $main->fetch("add_disc_transfer");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $iddis) {
			 $main = & new Template('viewdisc_transfer');
					$catinfo = $common->getInfo("discount_transfer_lookup","ID_DISCOUNT = '".$iddis."'");
					// get province
					$prinfo = $common->getInfo("province","ID_PROVINCE = '".$catinfo['ID_PROVINCE']."'");
					$main->set( "namepr", $prinfo['NAME']);
					// get dist
					$disinfo = $common->getInfo("district","ID_DIST = '".$catinfo['ID_DIST']."'");
					$main->set( "namedis", $disinfo['NAME']);
					// get type transfer
					$typeinfo = $common->getInfo("type_transfer","ID_TRANSFER = '".$catinfo['ID_TRANSFER']."'");
					$main->set( "nametr", $typeinfo['NAME_TRANSFER']);
					// get value money transfer
					$moneyinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$catinfo['ID_MONEY_TRANS']."'");
					$main->set( "namemn", $moneyinfo['NAME']);
					// get type customer
					$cusinfo = $common->getInfo("role_lookup","ROLE_ID = '".$catinfo['TYPE_ACCOUNT']."'");
					$main->set( "namecus", $cusinfo['NAME']);
					
					$main->set('sale', $catinfo['PERCENT']);
					$main->set('fee', $catinfo['FEE']);
					$content_decode = base64_decode($catinfo['NAME'.$pre]);
					$main->set('content', $content_decode);

			echo $main->fetch("viewdisc_transfer");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$iddis) {

		// Begin input data
		// add Category		
				try {
					$ArrayData = array( 1 => array(1 => "TYPE_ACCOUNT", 2 => $idrole),
										2 => array(1 => "ID_TRANSFER", 2 => $idtr),
										3 => array(1 => "NAME", 2 => $contentencode),
										4 => array(1 => "PERCENT", 2 => $sale),
										5 => array(1 => "ID_PROVINCE", 2 => $idpr),
										6 => array(1 => "FEE", 2 => $fee),
										7 => array(1 => "ID_DIST", 2 => $idd),
										8 => array(1 => "ID_MONEY_TRANS", 2 => $idmn)
										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"discount_transfer_lookup");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $iddis) {

		// Begin update data
				try {
					$ArrayData = array( 1 => array(1 => "TYPE_ACCOUNT", 2 => $idrole),
										2 => array(1 => "ID_TRANSFER", 2 => $idtr),
										3 => array(1 => "NAME", 2 => $contentencode),
										4 => array(1 => "PERCENT", 2 => $sale),
										5 => array(1 => "ID_PROVINCE", 2 => $idpr),
										6 => array(1 => "FEE", 2 => $fee),
										7 => array(1 => "ID_DIST", 2 => $idd),
										8 => array(1 => "ID_MONEY_TRANS", 2 => $idmn)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_DISCOUNT", 2 => $iddis));
					$common->UpdateDB($ArrayData,"discount_transfer_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
