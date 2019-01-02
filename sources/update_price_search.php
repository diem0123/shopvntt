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
     $main = & new Template('addprice_search'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idmn = $itech->input['idmn'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$from = $itech->input['from'];
		$to = $itech->input['to'];

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idmn) {
					$main->set('catname', "");
					$main->set('iorder', "");
					$main->set('from', "");
					$main->set('to', "");					
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m tr&#7883; gi&aacute;");
					$main->set('ac', "a");
					$main->set('idmn', "");

			echo $main->fetch("addprice_search");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idmn) {
					$catinfo = $common->getInfo("price_lookup","ID_PRICE = '".$idmn."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('from', $catinfo['FROM_MONEY']);
					$main->set('to', $catinfo['TO_MONEY']);					
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh tr&#7883; gi&aacute;");
					$main->set('ac', "e");
					$main->set('idmn', $idmn);

			echo $main->fetch("addprice_search");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idmn) {
			 $main = & new Template('viewprice_search');
					$catinfo = $common->getInfo("price_lookup","ID_PRICE = '".$idmn."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('from', $catinfo['FROM_MONEY']);
					$main->set('to', $catinfo['TO_MONEY']);	

			echo $main->fetch("viewprice_search");
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
										4 => array(1 => "TO_MONEY", 2 => $to)																				
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"price_lookup");					

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
										4 => array(1 => "TO_MONEY", 2 => $to)										
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_PRICE", 2 => $idmn));
					$common->UpdateDB($ArrayData,"price_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
