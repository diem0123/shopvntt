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
     $main = & new Template('addbrand'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idcat = $itech->input['idcat'];
		
		$catname = $itech->input['catname'];
		$iorder = $itech->input['iorder'];
		$status = $itech->input['status'];
		$content = $itech->input['content'];
		$tieubieu = $itech->input['TIEUBIEU'];
		$contentencode = $itech->input['contentencode'];
		
		$content_ykien = $itech->input['content_ykien'];
		$contentencode_ykien = $itech->input['contentencode_ykien'];
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
					$main->set('tbchecked',$tieubieu);
					$main->set('ac', "a");
					$main->set('idcat', "");
					$main->set('content', "");
					$main->set('content_ykien', "");

			echo $main->fetch("addbrand");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idcat) {
					$catinfo = $common->getInfo("brand","ID_BRAND = '".$idcat."'");
					$main->set('catname', $catinfo['NAME_BRAND']);
					$main->set('iorder', $catinfo['IORDER']);
					if($catinfo['TIEUDIEM']==1)
						$tbchecked = "Checked";
					if($catinfo['STATUS'] == 'Active') $sl1 = "selected";
						elseif($catinfo['STATUS'] == 'Inactive') $sl2 = "selected";
						elseif($catinfo['STATUS'] == 'Deleted') $sl3 = "selected";
						$main->set('sl1', $sl1);
						$main->set('sl2', $sl2);
						$main->set('sl3', $sl3);
					$main->set('tbchecked',$tbchecked);
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh nh&atilde;n hi&#7879;u");
					$main->set('ac', "e");
					$main->set('idcat', $idcat);
					$main->set('content', base64_decode($catinfo['CONTENT']));
					$main->set('content_ykien', base64_decode($catinfo['CONTENT_YKIEN']));

			echo $main->fetch("addbrand");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idcat) {
			 $main = & new Template('viewbrand');
					$catinfo = $common->getInfo("brand","ID_BRAND = '".$idcat."'");
					$main->set('catname', $catinfo['NAME_BRAND']);
					$main->set('iorder', $catinfo['IORDER']);
					$main->set('status', $catinfo['STATUS']);
					$main->set('content', $catinfo['CONTENT']);
					$main->set('content_ykien', $catinfo['CONTENT_YKIEN']);
					$main->set('tbchecked',$tieubieu);

			echo $main->fetch("viewbrand");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idcat) {
		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => "NAME_BRAND", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "STATUS", 2 => $status),
										4 => array(1 => "CONTENT", 2 => $contentencode),
										5 => array(1 => "TIEUDIEM", 2 => $tieubieu),
										6 => array(1 => "CONTENT_YKIEN", 2 => $contentencode_ykien)
										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,"brand");					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idcat) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => "NAME_BRAND", 2 => $catname),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "STATUS", 2 => $status),
										4 => array(1 => "CONTENT", 2 => $contentencode),
										5 => array(1 => "TIEUDIEM", 2 => $tieubieu),
										6 => array(1 => "CONTENT_YKIEN", 2 => $contentencode_ykien)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_BRAND", 2 => $idcat));
					$common->UpdateDB($ArrayData,"brand",$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
