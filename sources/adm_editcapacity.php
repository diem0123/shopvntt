<?php

if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
	$url_redidrect='index.php?act=adm_login';
	$common->redirect_url($url_redidrect);
}	

     //main
$main = & new Template('adm_editcapacity'); 	 
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idcapacity = $itech->input['idcapacity'];

		$name_capacity = $itech->input['name_capacity'];
		// echo $idcapacity;exit;
		$id_product = $itech->input['id_product'];	
		
		$PRICE = $itech->input['PRICE'];
		// process string to int
		$price_tem = explode(".",$PRICE);
		$price_str = "";
		for($i1=0;$i1<count($price_tem);$i1++){
			$price_str .= $price_tem[$i1];
		}
		
		if(!$PRICE) $PRICE = 0;
		else $PRICE = (int)$price_str;
		
		$SALE = $itech->input['SALE'];
		if($SALE == "") $SALE = 0;
		
		$TINHTRANG = $itech->input['TINHTRANG'];		
		if($TINHTRANG == "") $TINHTRANG = 0;
		
		$iorder = $itech->input['iorder'];		
		if($iorder == "") $iorder = 0;
		
		$status = $itech->input['status'];
		
		
		$contentencode = $itech->input['contentencode'];
		//echo "content=".$contentencode;
		
		$content = $itech->input['content'];
		$contentencodeparam = $itech->input['contentencodeparam'];
		//echo "<br> param=".$contentencodeparam;
		$param = $itech->input['param'];
		
		$user_incharge = $itech->input['cst'];
		$addmore = $itech->input['addmore'];
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		
	// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
		//check consultant incharge for company
		if($idcom){
			$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
			// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation" && $_SESSION['ID_CONSULTANT'] != $user_incharge_com['ID_CONSULTANT']){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;
			}
		}


		if($idcapacity && $ac=="e" && $submit == "") {
			$capacity_detail = $common->getInfo("capacity_product","ID_CAPACITY ='".$idcapacity."'");
			// echo '<pre>';
			// print_r($capacity_detail);
					// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);
				exit;
			}								
			$list_product = $print_2->GetDropDown($capacity_detail['ID_PRODUCT'], "products","STATUS = 'Active'" ,"ID_PRODUCT", "PRODUCT_NAME", "IORDER");
			$main->set('list_product', $list_product);

			$main->set('name_capacity', $capacity_detail['NAME_CAPACITY']);	
			$main->set('idcapacity', $capacity_detail['ID_CAPACITY']);					
			
			$main->set('PRICE', number_format($capacity_detail['PRICE'],0,"","."));
			$main->set('SALE', $capacity_detail['SALE']);

			if($capacity_detail['TINHTRANG']) $tbchecked = "checked";
			$main->set('tbchecked', $tbchecked);

			$main->set('iorder', $capacity_detail['IORDER']);

					// check show/hide status for operation and user
			if($roleinfo['ROLE_TYPE'] == "Operation"){
				if($capacity_detail['STATUS'] == 'Active') $sl1 = "selected";
				elseif($capacity_detail['STATUS'] == 'Inactive') $sl2 = "selected";
				elseif($capacity_detail['STATUS'] == 'Deleted') $sl3 = "selected";
				$main->set('sl1', $sl1);
				$main->set('sl2', $sl2);
				$main->set('sl3', $sl3);
				$main->set('dispr', "");
			}
			else{
				if($capacity_detail['STATUS'] == 'Active') $sl1 = "selected";
				elseif($capacity_detail['STATUS'] == 'Inactive') $sl2 = "selected";
				elseif($capacity_detail['STATUS'] == 'Deleted') $sl3 = "selected";
				$main->set('sl1', $sl1);
				$main->set('sl2', $sl2);
				$main->set('sl3', $sl3);
				$dispr = 'style = "display:none"';
				$main->set('dispr', $dispr);
			}
			// $content_out = base64_decode($capacity_detail['CONTENT']);
			$main->set('content', $capacity_detail['CONTENT']);

			$main->set('idcapacity', $idcapacity);
					//check role type
			if($roleinfo['ROLE_TYPE'] == "Operation"){
				$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
				$ronly = "disabled";
			}
			else{
				$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
				$ronly = "disabled";
			}

			$main->set('list_consultant', $list_consultant);
			$main->set('ronly', $ronly);

			echo $main->fetch("adm_editcapacity");
			exit;
		}
		elseif($idcapacity && $ac=="e" && $submit != "") {
			try {
				$ArrayData = array(
					1 => array(1 => "NAME_CAPACITY", 2 => $name_capacity ),
					2 => array(1 => "PRICE", 2 => $PRICE),
					3 => array(1 => "SALE", 2 => $SALE),                   
					4 => array(1 => "STATUS", 2 => $status),
					5 => array(1 => "CONTENT", 2 => $content),                   
					6 => array(1 => "IORDER", 2 => $iorder),
					7 => array(1 => "TINHTRANG", 2 => $TINHTRANG),
					8 => array(1 => "ID_PRODUCT", 2 => $id_product)
					);
					//echo'<pre>';print_r($ArrayData);exit;	  
				$update_condit = array( 1 => array(1 => "ID_CAPACITY", 2 => $idcapacity));
				$common->UpdateDB($ArrayData,"capacity_product",$update_condit);


			} catch (Exception $e) {
				echo $e;
			}									
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}
		if($idcapacity && $ac=="v" && $submit == "") {
			$capacity_detail = $common->getInfo("capacity_product","ID_CAPACITY ='".$idcapacity."'");
			// echo '<pre>';
			// print_r($capacity_detail);
					// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);
				exit;
			}								
			$list_product = $print_2->GetDropDown($capacity_detail['ID_PRODUCT'], "products","STATUS = 'Active'" ,"ID_PRODUCT", "PRODUCT_NAME", "IORDER");
			$main->set('list_product', $list_product);

			$main->set('name_capacity', $capacity_detail['NAME_CAPACITY']);	
			$main->set('idcapacity', $capacity_detail['ID_CAPACITY']);					
			
			$main->set('PRICE', number_format($capacity_detail['PRICE'],0,"","."));
			$main->set('SALE', $capacity_detail['SALE']);

			if($capacity_detail['TINHTRANG']) $tbchecked = "checked";
			$main->set('tbchecked', $tbchecked);

			$main->set('iorder', $capacity_detail['IORDER']);

					// check show/hide status for operation and user
			if($roleinfo['ROLE_TYPE'] == "Operation"){
				if($capacity_detail['STATUS'] == 'Active') $sl1 = "selected";
				elseif($capacity_detail['STATUS'] == 'Inactive') $sl2 = "selected";
				elseif($capacity_detail['STATUS'] == 'Deleted') $sl3 = "selected";
				$main->set('sl1', $sl1);
				$main->set('sl2', $sl2);
				$main->set('sl3', $sl3);
				$main->set('dispr', "");
			}
			else{
				if($capacity_detail['STATUS'] == 'Active') $sl1 = "selected";
				elseif($capacity_detail['STATUS'] == 'Inactive') $sl2 = "selected";
				elseif($capacity_detail['STATUS'] == 'Deleted') $sl3 = "selected";
				$main->set('sl1', $sl1);
				$main->set('sl2', $sl2);
				$main->set('sl3', $sl3);
				$dispr = 'style = "display:none"';
				$main->set('dispr', $dispr);
			}
			// $content_out = base64_decode($capacity_detail['CONTENT']);
			$main->set('content', $capacity_detail['CONTENT']);

			$main->set('idcapacity', $idcapacity);
					//check role type
			if($roleinfo['ROLE_TYPE'] == "Operation"){
				$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
				$ronly = "disabled";
			}
			else{
				$list_consultant = $print_2->GetDropDown($pro_detail['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
				$ronly = "disabled";
			}

			$main->set('list_consultant', $list_consultant);
			$main->set('ronly', $ronly);

			echo $main->fetch("adm_editcapacity");
			exit;
		}

		?>
