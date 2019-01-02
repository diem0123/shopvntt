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
     $main = & new Template('addsubcatelement'); 	 
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$cat = $itech->input['cat'];
		$idtype = $itech->input['typecat'];
		$idprocat = $itech->input['idprocat'];
		
		$nameprocat = $itech->input['nameprocat'];
		$iorder = $itech->input['iorder'];
		$link = $itech->input['link'];
		
		//if($idtype != 2 && $idtype != 3){
		$tablename = "product_categories";
		$field_id = "ID_PRO_CAT";
		$field_name = "NAME_PRO_CAT";
		//}
	/*	
		elseif($idtype ==2){
		$tablename = "ages_type";
		$field_id = "ID_AGES";
		$field_name = "NAME_TYPE_AGE";
		}
		elseif($idtype ==3){
		$tablename = "skill_type";
		$field_id = "ID_SKILL";
		$field_name = "NAME_TYPE_SKILL";
		}
*/
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "" && !$idprocat) {
					$list_category = $print_2->GetDropDown($cat, "categories","" ,"ID_CATEGORY", "NAME_CATEGORY", "IORDER");					
					$list_type_category = $print_2->GetDropDown($idtype, "categories_sub" , "ID_CATEGORY = '".$cat."'" , "ID_TYPE", "NAME_TYPE", "IORDER");
					$main->set('list_category', $list_category);
					$main->set('list_type_category', $list_type_category);
					$main->set('nameprocat', "");
					$main->set('iorder', "");
					$main->set('link', "");
					
					$main->set('button_action', "Th&ecirc;m");
					$main->set('title_action', "Th&ecirc;m chuy&ecirc;n m&#7909;c");
					$main->set('ac', "a");
					$main->set('idprocat', "");

			echo $main->fetch("addsubcatelement");
			exit;
		}
		elseif($ac=="e" && $submit == "" && $idtype && $idprocat) {
					
					$procatinfo = $common->getInfo($tablename,$field_id." = '".$idprocat."'");
					
					$list_category = $print_2->GetDropDown($procatinfo['ID_CATEGORY'], "categories","" ,"ID_CATEGORY", "NAME_CATEGORY", "IORDER");
					$list_type_category = $print_2->GetDropDown($procatinfo['ID_TYPE'], "categories_sub" , "ID_CATEGORY = '".$procatinfo['ID_CATEGORY']."'" , "ID_TYPE", "NAME_TYPE", "IORDER");
					
					$main->set('list_category', $list_category);
					$main->set('list_type_category', $list_type_category);
					$main->set('nameprocat', $procatinfo[$field_name]);
					$main->set('iorder', $procatinfo['IORDER']);
					$main->set('link', $procatinfo['LINK']);
					
					$main->set('button_action', "L&#432;u");
					$main->set('title_action', "Hi&#7879;u ch&#7881;nh chuy&ecirc;n m&#7909;c");
					$main->set('ac', "e");
					$main->set('idprocat', $idprocat);

			echo $main->fetch("addsubcatelement");
			exit;
		}
		elseif($ac=="v" && $submit == "" && $idtype && $idprocat) {
			 $main = & new Template('viewsubcatelement');
					$procatinfo = $common->getInfo($tablename,$field_id." = '".$idprocat."'");
					$catinfo = $common->getInfo("categories","ID_CATEGORY = '".$procatinfo['ID_CATEGORY']."'");
					$typeinfo = $common->getInfo("categories_sub","ID_TYPE = '".$procatinfo['ID_TYPE']."'");
					$main->set('namecat', $catinfo['NAME_CATEGORY']);
					$main->set('nametype', $typeinfo['NAME_TYPE']);
					$main->set('nameprocat', $procatinfo[$field_name]);					
					$main->set('iorder', $procatinfo['IORDER']);
					$main->set('link', $procatinfo['LINK']);

			echo $main->fetch("viewsubcatelement");
			exit;
		}
		// submit add 			
		elseif($ac=="a"  && $submit != "" && !$idprocat) {

		// Begin input data
		// add Category
				try {
					$ArrayData = array( 1 => array(1 => $field_name, 2 => $nameprocat),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "LINK", 2 => $link),
										4 => array(1 => "ID_CATEGORY", 2 => $cat),
										5 => array(1 => "ID_TYPE", 2 => $idtype)										
									  );
							  
					$id_ct = $common->InsertDB($ArrayData,$tablename);					

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}
	elseif($ac=="e"  && $submit != "" && $idprocat) {

		// Begin update data
		
				try {
					$ArrayData = array( 1 => array(1 => $field_name, 2 => $nameprocat),
										2 => array(1 => "IORDER", 2 => $iorder),
										3 => array(1 => "LINK", 2 => $link),
										4 => array(1 => "ID_CATEGORY", 2 => $cat),
										5 => array(1 => "ID_TYPE", 2 => $idtype)
									  );
							  					
					$update_condit = array( 1 => array(1 => $field_id, 2 => $idprocat));
					$common->UpdateDB($ArrayData,$tablename,$update_condit);

				} catch (Exception $e) {
						echo $e;
					}
					
	// return after submited
	echo "<script language=javascript>window.close();window.opener.location.reload();</script>";
	
	}

?>
