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
     $main = & new Template('addmaterial'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idcat = $itech->input['idcat'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$status = $itech->input['status'];
		
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idcat) {
					$main->set('catname', "");
					$main->set('iorder', "");
					
					$main->set('sl1', "");
					$main->set('sl2', "selected");
					$main->set('sl3', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m ch&#7845;t li&#7879;u");
					$main->set('ac', "a");
					$main->set('idcat', "");

			echo $main->fetch("addmaterial");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idcat) {
					$catinfo = $common->getInfo("material_lookup","ID_MT = '".$idcat."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					
					if($catinfo['STATUS'] == 'Active') $sl1 = "selected";
						elseif($catinfo['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($catinfo['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh ch&#7845;t li&#7879;u");
					$main->set('ac', "e");
					$main->set('idcat', $idcat);

			echo $main->fetch("addmaterial");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idcat) {
			 $main = & new Template('viewmaterial');
					$catinfo = $common->getInfo("material_lookup","ID_MT = '".$idcat."'");
					$main->set('catname', $catinfo['NAME']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('status', $catinfo['STATUS']);

			echo $main->fetch("viewmaterial");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idcat) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "STATUS", 2 => $status)
										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"material_lookup");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idcat) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "STATUS", 2 => $status)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_MT", 2 => $idcat));
					$common->UpdateDB($ArrayData,"material_lookup",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
