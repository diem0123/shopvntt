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
     $main = & new Template('adddist'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idd = $itech->input['idd'];
		
		$named = $itech->input['named'];
		$iorder = $itech->input['iorder'];
		$idpr = $itech->input['idpr'];		

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idd) {
					$main->set('named', "");
					$main->set('iorder', "");
					$list_provinces = $print_2->GetDropDown($idpr, "province","" ,"ID_PROVINCE", "NAME", "IORDER");
					$main->set('list_provinces', $list_provinces);
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m Qu&#7853;n/Huy&#7879;n");
					$main->set('ac', "a");
					$main->set('idd', "");

			echo $main->fetch("adddist");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idd) {
					$catinfo = $common->getInfo("district","ID_DIST = '".$idd."'");
					$list_provinces = $print_2->GetDropDown($catinfo['ID_PROVINCE'], "province","" ,"ID_PROVINCE", "NAME", "IORDER");
					$main->set('list_provinces', $list_provinces);
					$main->set('named', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);					
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh Qu&#7853;n/Huy&#7879;n");
					$main->set('ac', "e");
					$main->set('idd', $idd);

			echo $main->fetch("adddist");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idd) {
			 $main = & new Template('viewdist');
					$catinfo = $common->getInfo("district","ID_DIST = '".$idd."'");
					$prinfo = $common->getInfo("province","ID_PROVINCE = '".$catinfo['ID_PROVINCE']."'");
					$main->set('named', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('namepr', $prinfo['NAME']);

			echo $main->fetch("viewdist");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idd) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $named),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "ID_PROVINCE", 2 => $idpr)																		
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"district");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idd) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $named),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "ID_PROVINCE", 2 => $idpr)																				
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_DIST", 2 => $idd));
					$common->UpdateDB($ArrayData,"district",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
