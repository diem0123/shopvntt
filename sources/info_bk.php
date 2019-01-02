<?php

// kiem tra logged in	
	$_SESSION['descript'] = "";// mo ta cho the description
	$idtype = $itech->input['idtype'];
	$idcat = $itech->input['idcat'];
	$idcatsub = $itech->input['idcatsub'];
	$idn = $itech->input['idn'];
	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = $itech->vars['num_record_on_page'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
     
	// get language
	$pre = $_SESSION['pre'];
	
	// set menu active
	switch ($idtype){
		case 1: $_SESSION['tab'] = 2;// gioithieu
		break;
		case 2: $_SESSION['tab'] = 4;// Dich vu
		break;		
		case 3: if($idcat == 11) $_SESSION['tab'] = 6; else $_SESSION['tab'] = 5;// tin tuc (11: tuyen dung, else tin tuc)
		break;		
		
		default: $_SESSION['tab'] = 1;// home
	}	
	
	// get title button
/*	
	if($_SESSION['tab'] == 2) $name_title = $lang['tl_aboutus'];
	elseif($_SESSION['tab'] == 7) $name_title = $lang['tl_news'];
	elseif($_SESSION['tab'] == 4) $name_title = $lang['tl_nhamay'];
	elseif($_SESSION['tab'] == 5) $name_title = $lang['tl_ttrai'];
	elseif($_SESSION['tab'] == 6) $name_title = $lang['tl_tulieu'];	
	elseif($_SESSION['tab'] == 8) $name_title = $lang['tl_contact'];
*/	
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
	
	$name_titlecat = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
	$name_titlesubcat = $common->getInfo("common_cat","ID_TYPE = '".$idtype."' AND ID_CAT = '".$idcat."'");
	$name_titlesubprocat = $common->getInfo("common_cat_sub","ID_CAT_SUB = '".$idcatsub."'");
	
	//$name_linkcat = $print_2->vn_str_filter($name_titlecat['SNAME']);
	$name_linkcat = $print->getlangvalue($name_titlecat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	//$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['SNAME']);
	$name_linksubcat = $print->getlangvalue($name_titlesubcat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	//$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['SNAME']);
	$name_linksubprocat = $print->getlangvalue($name_titlesubprocat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="muc-'.$idtype.'-'.$name_linkcat.'.html" >'.$name_titlecat['SNAME'.$pre].'</a><span class="arrow"><span></span></span></span>';
	$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="muc-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="muc-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	
	if($idtype && !$idcat && !$idcatsub)
	$tpl_title->set('namecat', $lkcat);		
	elseif($idtype && $idcat && !$idcatsub){	
	$tpl_title->set('namecat', $lkcat.$lksubcat);
	}
	elseif($idcatsub)
	$tpl_title->set('namecat', $lkcat.$lksubcat.$lksubprocat);
	
	$tpl_title->set('title_sp', "title_breadcrumb");
	
	$tpl_title->set('idtype', $idtype);
	$tpl_title->set('idcat', $idcat);
	$tpl_title->set('idcatsub', $idcatsub);
	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	$main_info=& new Template('main_info');
		  
	//$temp_contents=& new Template('common1');
	// check conditional for display menu with ID_TYPE	
	
	if($idtype && !$idcat && !$idcatsub){		
		// Display Info allows ID_TYPE
		// idtype = 1: display default info company		
		if($idtype ==1){			
			$condit = " ID_COMMON = 21 AND STATUS = 'Active' ";
			$display_list = 0;
			$idcat = 1;
			$info_news = $common->getInfo("common",$condit);			
		}
		// tin tuc tong hop
		elseif($idtype ==3){			
			$condit = " ID_TYPE = 3 AND ID_CAT = 10 AND STATUS = 'Active' ";
			$display_list = 1;
			$idcat = 10;			
		}
		
		elseif($idtype ==2 || $idtype ==4 || $idtype ==5 || $idtype ==6){
			// check idtype has count(idtype)>1 then display list news where idcat
			// elseif is null or =1 then display detail info			
			// ve nha may
			if($idtype ==2){			
			$condit = " ID_TYPE= '".$idtype."' AND ID_CAT = 6 AND STATUS = 'Active' ";
			$idcat = 6;			
			}
			// thong tin lien he
			elseif($idtype ==4){			
			$condit = " ID_TYPE= '".$idtype."' AND ID_CAT = 18 AND STATUS = 'Active' ";
			$idcat = 18;			
			}
			// Tai lieu
			elseif($idtype ==5){		
			$condit = " ID_TYPE= '".$idtype."' AND STATUS = 'Active' ";
			$idcat = 0;			
			}
			else{
			$condit = " ID_TYPE= '".$idtype."' AND STATUS = 'Active' ";
			}
			
			$count_idtype = $common->CountRecord("common",$condit,"ID_COMMON");
			// display list news
			if($count_idtype>1) $display_list =1;
			// display info detail default
			else{
				$display_list = 0;
				$info_news = $common->getInfo("common",$condit);
			}
		}
		// idtype = 3: display default layout home_sub				
		else{
		// Nothing display
		$main_contents = "";
		}
		// get paging for default
		// get title name link
		// neu ton tai muc con
		if($idcat){
			$info_title = $common->getInfo("common_cat"," ID_CAT = '".$idcat."'");
			$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);			
			$_SESSION['get_title'] = $info_title['SNAME'.$pre];
			$link_page = "muc-".$idtype."-".$name_link.".html?idcat=".$idcat;
		}
		// neu khong co muc con
		else{
			$info_title = $common->getInfo("common_type"," ID_TYPE = '".$idtype."'");			
			$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);						
			$_SESSION['get_title'] = $info_title['SNAME'.$pre];
			$link_page = "muc-".$idtype."-".$name_link.".html?";
		}

	}
	elseif($idtype && $idcat && !$idcatsub){
		// check idcat has count(idcat)>1 then display list news where idcat
		// elseif is null or =1 then display detail info
		// get all news if $idcat ==10
		if($idcat ==10) $condit = " ID_TYPE= '".$idtype."' AND STATUS = 'Active' ";
		else
		$condit = " ID_TYPE= '".$idtype."' AND ID_CAT= '".$idcat."' AND STATUS = 'Active' ";
		// get name link in session (set from funtion GetRowMenu)
		//$link_page = "muc-".$idtype."-".$_SESSION['name_link'].".html?idcat=".$idcat;
		$count_idcat = $common->CountRecord("common",$condit,"ID_COMMON");
		// display list news
		if($count_idcat>1) $display_list =1;
		// display info detail default
		else{
			$display_list = 0;
			$info_news = $common->getInfo("common",$condit);
		}
		
		// get title name link
		$info_title = $common->getInfo("common_cat"," ID_CAT = '".$idcat."'");
		$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['SNAME'.$pre];
		$link_page = "muc-".$idtype."-".$name_link.".html?idcat=".$idcat;
	}
	elseif($idtype && $idcat && $idcatsub){
		// check idcat has count(idcatsub)>1 then display list news where idcatsub
		// elseif is null or =1 then display detail info	
		$condit = " ID_TYPE= '".$idtype."' AND ID_CAT= '".$idcat."' AND ID_CAT_SUB= '".$idcatsub."' AND STATUS = 'Active' ";
		// get name link in session (set from funtion GetRowMenu)
		//$link_page = "muc-".$idtype."-".$_SESSION['name_link'].".html?idcat=".$idcat."&idcatsub=".$idcatsub;
		$count_idcatsub = $common->CountRecord("common",$condit,"ID_COMMON");
		// display list news
		if($count_idcatsub>1) $display_list =1;
		// display info detail default
		else{
			$display_list = 0;
			$info_news = $common->getInfo("common",$condit);
		}
		
		// get title name link
		$info_title = $common->getInfo("common_cat"," ID_CAT = '".$idcat."'");
		$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$_SESSION['get_title'] = $info_title['SNAME'.$pre];
		$link_page = "muc-".$idtype."-".$name_link.".html?idcat=".$idcat."&idcatsub=".$idcatsub;
	}
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list products
//	if($idtype !=5){
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
//	}
	// get menu list industries
/*
	else{
	$field_name_other1 = array("IORDER","STATUS");
	$field_name_other2 = array("IORDER","STATUS");
	$field_name_other3 = array("IORDER","STATUS");
	$list_cat_menu = $print_2->GetRowMenuIndus($field_name_other1, "menuind_cat_rs",  $field_name_other2, "menuind_catsub_rs", $field_name_other3, "menuind_procat_rs", $pre, $idtype, $idcat, $idcatsub, $ida, $idsk, $pri, $idbr, $idmt);
	}
*/	
	$main_info->set('list_cat_menu', $list_cat_menu);
	$main_info->set('idtype', $idtype);
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************
/*
// dong lenh nay chi co tac dung khi co menu rieng ve tin tuc	
	if($idcat && $idcatsub){
	if($_SESSION['name_link'] == "") $link_page = "muc-".$idtype."-".$name_link.".html?idcat=".$idcat."&idcatsub=".$idcatsub; 
	else $link_page = "muc-".$idtype."-".$_SESSION['name_link'].".html?idcat=".$idcat."&idcatsub=".$idcatsub;
	}
	elseif($idcat && !$idcatsub){
	if($_SESSION['name_link'] == "") $link_page = "muc-".$idtype."-".$name_link.".html?idcat=".$idcat; 
	else $link_page = "muc-".$idtype."-".$_SESSION['name_link'].".html?idcat=".$idcat;
	
	}
*/
	
	// display list and paging: hien thi chung cho tat ca cac truong hop cap bac menu phia tren	
	if($display_list ==1){
		
		$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB");
		// phan chung cho kieu tin tuc
		if($idcat != 32 && $idcat != 33){
		$list_news_rs = $print_2->list_row_multi_field("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC", "list_news_rs", $pageoffset,$num_record_on_page, $page, $idmnselected=0, $pre );
		$tplnews = "list_news";		
		}
		// get rieng cho thu vien anh va chung nhan
		else{
		$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC", "list_2all_images_info", 2, $pageoffset, $num_record_on_page, $page, $idmnselected=0, $pre );				
		$tplnews = "list_news_info";	
		
		}
		
		$tpl_news =& new Template($tplnews);
		$tpl_news->set('list_news_rs', $list_news_rs);
		
		// paging
		$total_get = $common->CountRecord("common",$condit,"ID_COMMON");

		$class_link = "linkpage";
		if ($num_record_on_page < $total_get)
			$tpl_news->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		else
			$tpl_news->set('paging' , '');			
		
		$main_contents = $tpl_news->fetch($tplnews);
		$_SESSION['descript'] = "";
	}
	else{
		if($info_news['SCONTENTS'.$pre]=="")
		$content_decode = base64_decode($info_news['SCONTENTS']);
		else
		$content_decode = base64_decode($info_news['SCONTENTS'.$pre]);
		$_SESSION['descript'] = $info_news['SCONTENTSHORT'.$pre];
		$main_contents = $content_decode;	
	}
	
	// picture slide show
	$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$main_info->set('intro', $tpl_intro);
	
	// get san pham tieu bieu
	//$list_sptb = $print->get_sptb($pre,$idmnselected);
	$main_info->set('list_sptb', "");
	
/*	
	// get text title hotnews
	$texthot = $common->gethonhop(54);
	$tpl_intro->set('texthot', $texthot['SCONTENTSHORT']);
*/	
	// get title	
	$main_info->set('title_cat', $title_cat);
	
	$main_info->set('intro2', "");

	// get contact		
	$list_contact = $print->get_list_contact($pre);
	$main_info->set('list_contact', $list_contact);
	
	// get hot news		
	$hotnews_left = $print->gethot_news_left($pre, 6);
	$main_info->set('hotnews', $hotnews_left);
	
	// get logo partner
	$logo_partner = $print->getlogo_partner($pre);
	$main_info->set('logo_partner', $logo_partner);
	
	$main_info->set('main_contents', $main_contents);		
	
	// get list chat
	//$list_chat = $print->getNickchat();
	//$main_info->set('list_chat', $list_chat);
	$support_online = $print->getSupport();
	$main_info->set('support_online', $support_online);
	
	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());		
	
	// Get main 2
	$main->set('main2', $main_info);
	
	

	//footer
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	$footer->set('todayaccess', $print->get_today());

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	echo $tpl->fetch('home');
?>