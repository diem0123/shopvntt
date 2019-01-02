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
     $main = & new Template('addmoney_order'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idmn = $itech->input['idmn'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$from = $itech->input['from'];
		$to = $itech->input['to'];
		$type_trigia = $itech->input['type_trigia'];
		
		$s1 = "";
		$s2 = "";
		$s3 = "";

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idmn) {
					$main->set('catname', "");
					$main->set('iorder', "");
					$main->set('from', "");
					$main->set('to', "");
					$main->set('s1', "");
					$main->set('s2', "");
					$main->set('s3', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m tr&#7883; gi&aacute; &#273;&#417;n h&agrave;ng");
					$main->set('ac', "a");
					$main->set('idmn', "");

			echo $main->fetch("addmoney_order");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idmn) {
					$catinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$idmn."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('from', $catinfo['FROM_MONEY']);
					$main->set('to', $catinfo['TO_MONEY']);
					if($catinfo['TYPE_TRIGIA'] == 'vc') $s1 = "selected";
					elseif($catinfo['TYPE_TRIGIA'] == 'ck') $s2 = "selected";
					elseif($catinfo['TYPE_TRIGIA'] == 'kg') $s3 = "selected";
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					$main->set('s3', $s3);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh tr&#7883; gi&aacute;");
					$main->set('ac', "e");
					$main->set('idmn', $idmn);

			echo $main->fetch("addmoney_order");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idmn) {
			 $main = & new Template('viewmoney_order');
					$catinfo = $common->getInfo("money_transfer_lookup","ID_MONEY_TRANS = '".$idmn."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('from', $catinfo['FROM_MONEY']);
					$main->set('to', $catinfo['TO_MONEY']);
					if($catinfo['TYPE_TRIGIA'] == 'vc') $TYPE_TRIGIA = "Tr&#7883; gi&aacute; &#273;&#417;n h&agrave;ng t&iacute;nh c&#432;&#7899;c v&#7853;n chuy&#7875;n";
					elseif($catinfo['TYPE_TRIGIA'] == 'ck') $TYPE_TRIGIA = "Tr&#7883; gi&aacute; &#273;&#417;n h&agrave;ng &#272;&#7841;i l&yacute; chi&#7871;t kh&#7845;u";
					elseif($catinfo['TYPE_TRIGIA'] == 'kg') $TYPE_TRIGIA = "Tr&#7883; gi&aacute; &#273;&#417;n h&agrave;ng &#272;&#7841;i l&yacute; k&yacute; g&#7917;i";
					$main->set('TYPE_TRIGIA', $TYPE_TRIGIA);

			echo $main->fetch("viewmoney_order");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idmn) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "FROM_MONEY", 2 => $from),
										4 => array(1 => "TO_MONEY", 2 => $to),
										5 => array(1 => "TYPE_TRIGIA", 2 => $type_trigia)
										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"money_transfer_lookup");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idmn) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "FROM_MONEY", 2 => $from),
										4 => array(1 => "TO_MONEY", 2 => $to),
										5 => array(1 => "TYPE_TRIGIA", 2 => $type_trigia)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_MONEY_TRANS", 2 => $idmn));
					$common->UpdateDB($ArrayData,"money_transfer_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
