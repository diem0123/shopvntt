<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 // set for language
	 $pre = '';
     //main
     $main = & new Template('adm_viewphoto'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idphoto = $itech->input['idphoto'];
		
		
	// set permission
	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(9), "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
		
		// set permission conditional for role
		//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID ",'ID_CONSULTANT');
		// don't allow user access 
		if($roleinfo['ROLE_TYPE'] != "Operation"){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;
		}
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($idphoto && $ac=="v") {					
					$cm = $common->getInfo("photos","ID_PT = '".$idphoto."'");								
					
					$main->set('photoname', $cm['STITLE'.$pre]);					
					
					if($cm['STATUS'] == 'Active') $sl1 = "selected";
					elseif($cm['STATUS'] == 'Inactive') $sl2 = "selected";
					elseif($cm['STATUS'] == 'Deleted') $sl3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('dispr', "");					
					
					$main->set('short_content', $cm['SCONTENTSHORT'.$pre]);
					$content_decode = base64_decode($cm['SCONTENTS'.$pre]);
					$main->set('content', $content_decode);
					$main->set('mess', "");										
					$main->set('idphoto', $idphoto);
					if($cm['PICTURE'] == "") $thumbnail = "noimages.jpg";
					else $thumbnail = $cm['PICTURE'];
					$main->set('thumbnail', $thumbnail);					
								
					$list_consultant = $print_2->GetDropDown($cm['POST_BY'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
					$ronly = "disabled";					
					
					$main->set('list_consultant', $list_consultant);
					$main->set('ronly', $ronly);

			echo $main->fetch("adm_viewphoto");
			exit;
		}
				

?>
