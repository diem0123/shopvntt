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
     $main = & new Template('add_type_transfer'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idtr = $itech->input['idtr'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$contentencode = $itech->input['contentencode'];
		$content = $itech->input['content'];

	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idtr) {
					$main->set('catname', "");
					$main->set('iorder', "");
					$main->set('content', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m ki&#7875;u v&#7853;n chuy&#7875;n");
					$main->set('ac', "a");
					$main->set('idtr', "");

			echo $main->fetch("add_type_transfer");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idtr) {
					$catinfo = $common->getInfo("type_transfer","ID_TRANSFER = '".$idtr."'");
					$main->set('catname', $catinfo['NAME_TRANSFER']);
					$main->set('iorder', $catinfo['IORDER']);
					$content_decode = base64_decode($catinfo['INFO'.$pre]);
					$main->set('content', $content_decode);					
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh ki&#7875;u v&#7853;n chuy&#7875;n");
					$main->set('ac', "e");
					$main->set('idtr', $idtr);

			echo $main->fetch("add_type_transfer");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idtr) {
			 $main = & new Template('view_type_transfer');
					$catinfo = $common->getInfo("type_transfer","ID_TRANSFER = '".$idtr."'");
					$main->set('catname', $catinfo['NAME_TRANSFER']);
					$main->set('iorder', $catinfo['IORDER']);
					$content_decode = base64_decode($catinfo['INFO'.$pre]);
					$main->set('content', $content_decode);	

			echo $main->fetch("view_type_transfer");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idtr) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME_TRANSFER", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "INFO", 2 => $contentencode)																		
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"type_transfer");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idtr) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME_TRANSFER", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "INFO", 2 => $contentencode)																				
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_TRANSFER", 2 => $idtr));
					$common->UpdateDB($ArrayData,"type_transfer",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
