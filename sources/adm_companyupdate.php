<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_company', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	 
     	 
		//$ac = $itech->input['ac'];
		//$idcom = $itech->input['idcom'];
		$idcom = 1;
		$ac = 'e';
		
		$submit = $itech->input['submit'];
				
		$comname = $itech->input['comname'];
		$shortname = $itech->input['shortname'];
		$email = $itech->input['email'];
		$tel = $itech->input['tel'];
		$fax = $itech->input['fax'];
		$mobi = $itech->input['mobi'];
		$skype = $itech->input['skype'];
		$yahoo = $itech->input['yahoo'];
		$website = $itech->input['website'];
		$address = $itech->input['address'];
		$province = $itech->input['province'];
		$status = $itech->input['status'];
		$contentencode = $itech->input['contentencode'];//$_POST['contentencode'];
		$content = $itech->input['content'];
		$user_incharge = $itech->input['cst'];
		
		//echo "content=".$contentencode;exit;
		
	$pagetitle = $itech->vars['site_name'];
	$list_provinces = $print_2->GetDropDown($province, "province","" ,"ID_PROVINCE", "NAME", "IORDER");

		if($ac=="e" && $submit == "" && $idcom) {
										//main
					$main =  new Template('editcompany'); 
					$main->set("title", $pagetitle);

					$cominfo = $common->getInfo("company","ID_COM = ".$idcom);
					$list_provinces = $print_2->GetDropDown($cominfo['ID_PROVINCE'], "province","" ,"ID_PROVINCE", "NAME", "IORDER");					

					$main->set('comname', $cominfo['COM_NAME'.$pre]);
					$main->set('shortname', $cominfo['SHORT_NAME'.$pre]);
					$main->set('email', $cominfo['EMAIL']);
					$main->set('tel', $cominfo['TEL']);
					$main->set('fax', $cominfo['FAX']);
					$main->set('mobi', $cominfo['MOBI']);
					$main->set('skype', $cominfo['SKYPE']);
					$main->set('yahoo', $cominfo['YAHOO']);
					$main->set('website', $cominfo['WEBSITE']);
					$main->set('address', $cominfo['ADDRESS'.$pre]);
					$main->set('list_provinces', $list_provinces);										
					if($cominfo['LOGO'] == "")
					$main->set('logoname', "nologo.jpg");
					else 
					$main->set('logoname', $cominfo['LOGO']);
					//$content_out = base64_decode($cominfo['DESCRIPTION']);
					//$main->set('content', $content_out);
					$main->set('mess', "");
					$main->set('idcom', $idcom);					

			echo $main->fetch("editcompany");
			exit;
		}
		
		if($ac=="e"  && $submit != "" && $idcom) {

				// Begin update data				
				
						try {
							$ArrayData = array( 1 => array(1 => "COM_NAME".$pre, 2 => $comname),
												2 => array(1 => "EMAIL", 2 => $email),
												3 => array(1 => "TEL", 2 => $tel),
												4 => array(1 => "FAX", 2 => $fax),
												5 => array(1 => "WEBSITE", 2 => $website),
												6 => array(1 => "ADDRESS".$pre, 2 => $address),
												7 => array(1 => "DATE_UPDATED", 2 => date('Y-m-d'))	,
												8 => array(1 => "SHORT_NAME".$pre, 2 => $shortname),
												9 => array(1 => "MOBI", 2 => $mobi),
												10 => array(1 => "SKYPE", 2 => $skype),
												11 => array(1 => "YAHOO", 2 => $yahoo)
											  );
														
							$update_condit = array( 1 => array(1 => "ID_COM", 2 => $idcom));
							$common->UpdateDB($ArrayData,"company",$update_condit);

						} catch (Exception $e) {
								echo $e;
							}
							
			// return after submited
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
			
			}

?>
