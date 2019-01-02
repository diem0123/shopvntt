<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_viewproduct'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];		
		$idp = $itech->input['idp'];

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

					$pagetitle = $itech->vars['site_name'];
					$main->set("title", $pagetitle);
	
		if($idp && $ac=="v") {
					$pro_detail = $common->getInfo("products","ID_PRODUCT ='".$idp."'");					

					// check permission com incharge owner
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$url_redidrect='index.php?act=error403';
						$common->redirect_url($url_redidrect);
						exit;
					}
					
					$name_category = $common->getInfo("categories","ID_CATEGORY ='".$pro_detail['ID_CATEGORY']."'");
					$name_type_category = $common->getInfo("categories_sub","ID_TYPE ='".$pro_detail['ID_TYPE']."'");
					$name_procat = $common->getInfo("product_categories","ID_PRO_CAT ='".$pro_detail['ID_PRO_CAT']."'");										
					$list_provinces = $print_2->GetDropDown($pro_detail['ID_PROVINCE'], "province","" ,"ID_PROVINCE", "NAME", "IORDER");					
		
					$main->set('name_category', $name_category['NAME_CATEGORY']);
					$main->set('name_type_category', $name_type_category['NAME_TYPE']);
					$main->set('name_procat', $name_procat['NAME_PRO_CAT']);
					$main->set('list_provinces', $list_provinces);
					
					if($pro_detail['ID_CATEGORY'] == 1){
					// get for cat =1
					// get readonly for toys follow ages
					$name_ages = $common->getInfo("categories_sub","ID_TYPE ='2' AND ID_CATEGORY = '1' ");
					// get list ages type
					$name_ages_sub = $common->getInfo("ages_type","ID_AGES ='".$pro_detail['ID_AGES']."'");
					// get readonly for toys follow skills
					$name_skills = $common->getInfo("categories_sub","ID_TYPE ='3' AND ID_CATEGORY = '1' ");
					// get list skills type
					$name_skills_sub = $common->getInfo("skill_type","ID_SKILL ='".$pro_detail['ID_SKILL']."'");
					
					// set display ages and skill
					$sh = 'style = "display:table-row"';
					$main->set('show_age_skill', $sh);
					$main->set('name_ages', $name_ages['NAME_TYPE']);
					$main->set('name_ages_sub', $name_ages_sub['NAME_TYPE_AGE']);
					$main->set('name_skills', $name_skills['NAME_TYPE']);
					$main->set('name_skills_sub', $name_skills_sub['NAME_TYPE_SKILL']);
					}
					else{
					$main->set('name_ages', "");
					$main->set('name_ages_sub', "");
					$main->set('name_skills', "");
					$main->set('name_skills_sub', "");
					// set display ages and skill
					$sh = 'style = "display:none"';
					$main->set('show_age_skill', $sh);
					}
										
										
					$main->set('CODE', $pro_detail['CODE']);
					$main->set('productname', $pro_detail['PRODUCT_NAME']);					
					$main->set('EAN_CODE', $pro_detail['EAN_CODE']);
					$main->set('SIZE', $pro_detail['SIZE']);
					$main->set('MATERIAL', $pro_detail['MATERIAL']);
					$main->set('STANDARD', $pro_detail['STANDARD']);
					$main->set('COUNT_QUANTITY', $pro_detail['COUNT_QUANTITY']);
					$main->set('AGE_NUMBER', $pro_detail['AGE']);					
					$main->set('PRICE', number_format($pro_detail['PRICE'],0,"","."));
					$main->set('SALE', $pro_detail['SALE']);
					
					if($pro_detail['EXPIRE']) $EXPIRE = "checked";
					$main->set('EXPIRE', $EXPIRE);
					if($pro_detail['KHUYENMAI']) $KHUYENMAI = "checked";
					$main->set('KHUYENMAI', $KHUYENMAI);
					if($pro_detail['HOT']) $HOT = "checked";
					$main->set('HOT', $HOT);					
					$main->set('INFO_SHORT', $pro_detail['INFO_SHORT']);
					$main->set('status', $pro_detail['STATUS']);
					$main->set('dispr', "");
					
					$content_out = base64_decode($pro_detail['INFO_DETAIL']);
					$main->set('content', $content_out);
					$content_param = base64_decode($pro_detail['PARAM']);
					$main->set('param', $content_param);
					$main->set('mess1', "");
					$main->set('mess2', "");
					$main->set('idcom', $idcom);
					
					$main->set('idp', $idp);
					$main->set('thumbnail', $pro_detail['THUMBNAIL']);
					$main->set('image_large', $pro_detail['IMAGE_LARGE']);
					$main->set('cat_directory', $pro_detail['ID_CATEGORY']);
					$POST_BY = $common->getInfo("consultant","ID_CONSULTANT ='".$pro_detail['ID_CATEGORY']."'");
					$main->set('POST_BY', $POST_BY['FULL_NAME']." (".$POST_BY['EMAIL'].")");	
					//echo 'images/bin/'.$pro_detail['ID_CATEGORY'].'/'.$pro_detail['IMAGE_LARGE'];echo'<br>';
					//echo $pro_detail['IMAGE_LARGE'];
			echo $main->fetch("adm_viewproduct");
			exit;
		}
							
		

?>
