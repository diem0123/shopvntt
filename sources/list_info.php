<?php

	$_SESSION['descript'] = "";// mo ta cho the description
	$idtype = $itech->input['idtype'];
	$idcat = $itech->input['idc'];
	$idcatsub = $itech->input['idcatsub'];
	$idn = $itech->input['idn'];
	//echo $idcat;//echo $idcatsub;echo $idn;
	$page = $itech->input['page'];
	$keyword = $itech->input['keyword'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = 7;//$itech->vars['num_record_on_page'];
	$maxpage = 10; // group 10 link on paging list
	//if(($idtype==1 && $idcat==8) || $idtype==2) $num_record_on_page =9;// ap dung cho hinh anh cong ty	
	
	$pageoffset =($page-1)* $num_record_on_page;
     
	// get language
	$pre = $_SESSION['pre'];
	
	$tempkey = "";
	$keyword_criteria = "";	
	
	// search keyword
		if ($keyword != "" )
		{
			//$t1 = 0 < $local ? " AND " : " AND ";
			$t1 = " AND ";
			$word = explode(" ", $keyword );
			$tempkey = " ( STITLE".$pre." LIKE '%".$word[0]."%' OR SCONTENTSHORT".$pre." LIKE '%".$word[0]."%')";    
			for ($i=1;$i<count($word);$i++)
			{
				$keyword_criteria .= " AND ( STITLE".$pre." LIKE '%".$word[$i]."%' OR SCONTENTSHORT".$pre." LIKE '%".$word[$i]."%')";
			}
			
			$keyword_criteria = $t1.$tempkey.$keyword_criteria;
			//$condit_keyword .= $keyword_criteria;
		}
	$get_id_type = $print->get_IDTYPE($idcat);
	// set menu active
	switch ($get_id_type){
		case 1: $_SESSION['tab'] = 2;// gioithieu
		break;
		case 2: $_SESSION['tab'] = 3;// Dịch vụ
		break;
		case 4: $_SESSION['tab'] = 5;// Khuyến mại
		break;
		case 7: $_SESSION['tab'] = 6;// Kiến thức
		break;
		default: $_SESSION['tab'] = 1;// home
	}	
	
	// get tite category
	$title_cat = "";
	$tpl_title =& new Template('title_subcat_info');
	//lay idtpype dua vao idcat
	
	$name_titlecat = $common->getInfo("common_type","ID_TYPE = '".$get_id_type."'");
	$name_titlesubcat = $common->getInfo("common_cat","ID_TYPE = '".$get_id_type."' AND ID_CAT = '".$idcat."'");
	$name_titlesubprocat = $common->getInfo("common_cat_sub","ID_CAT_SUB = '".$idcatsub."'");
	
	//$name_linkcat = $print_2->vn_str_filter($name_titlecat['SNAME']);
	$name_linkcat = $print->getlangvalue($name_titlecat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	//$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['SNAME']);
	$name_linksubcat = $print->getlangvalue($name_titlesubcat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	//$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['SNAME']);
	$name_linksubprocat = $print->getlangvalue($name_titlesubprocat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
	//$lkcat = '<li><a  href="info-'.$idtype.'-'.$name_linkcat.'.html" >'.$name_titlecat['SNAME'.$pre].'</a></li>';
	//$lksubcat = '<li><a  href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a></li>';
	//$lksubprocat = '<li><a  href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a></li>';
	// get default link
	if($idtype==1) {
		if(!$idcat){
		$name_titlesubcat1 = $common->getInfo("common_cat","ID_TYPE = '".$get_id_type."' AND ID_CAT = 1");
		$name_linksubcat1 = $print->getlangvalue($name_titlesubcat1,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		}
		else{
		$name_titlesubcat1 = $common->getInfo("common_cat","ID_TYPE = '".$get_id_type."' AND ID_CAT = 1");
		$name_linksubcat1 = $print->getlangvalue($name_titlesubcat1,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$name_titlesubcat = $common->getInfo("common_cat","ID_TYPE = '".$get_id_type."' AND ID_CAT = '".$idcat."'");
		$name_linksubcat = $print->getlangvalue($name_titlesubcat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		}
	$link_df = 'info-'.$get_id_type.'-'.$name_linksubcat1.'.html?idcat=1';
	}
	else{
	$link_df = 'info-'.$get_id_type.'-'.$name_linkcat.'.html';
	}
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="'.$link_df.'" >'.$name_titlecat['SNAME'.$pre].'</a><span class="arrow"><span></span></span></span>';
	//$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	//$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	
	$lkcat = '<a href="'.$link_df.'" >'.$name_titlecat['SNAME'.$pre].'</a>';
	$lksubcat = '<a href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a>';
	$lksubprocat = '<a href="info-'.$get_id_type.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a>';
	
	//$lkcat = "";// khong cho hien thi chu dich vu
	$lkcat = " <span>></span> ".$lkcat;
	if($get_id_type==3 && $idcat !="")
	$lkcat = "";// dau muc chung goc
	
	if($get_id_type && !$idcat && !$idcatsub)
	{
		$tpl_title->set('namecat', $lkcat);	
	}	
	elseif($get_id_type && $idcat && !$idcatsub){	
	$tpl_title->set('namecat', $lkcat." <span>></span> ".$lksubcat);
	}
	elseif($idcatsub){
	$tpl_title->set('namecat', $lkcat." <span>></span> ".$lksubcat." <span>></span> ".$lksubprocat);
	}
	$tpl_title->set('title_sp', "title_breadcrumb");

	$tpl_title->set('idtype', $get_id_type);
	$tpl_title->set('idcat', $idcat);
	$tpl_title->set('idcatsub', $idcatsub);
	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('header');
	else
	$header = $common->getHeader('header');

	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") $tplmain = "main_info";
	else $tplmain = "main_list_info";
	$main_info=& new Template($tplmain);		  
	
	if($get_id_type && !$idcat && !$idcatsub){		
		// get info
		$condit = " ID_TYPE = '".$get_id_type."' AND STATUS = 'Active' ";		
		$info_news = $common->getInfo("common_type",$condit);
		// check display
		if($info_news['HT']==2){			
			$display_list = 0;// display detail			
		}
		elseif($info_news['HT']==1 ){
			// check idtype has count(idtype)>1 then display list news where idcat
			// elseif is null or =1 then display detail info						
			// chi hien thi tin tong hop idcat=9
			if($idtype==3)
			$condit = " ID_TYPE= '".$get_id_type."' AND ID_CAT <> 10 AND ID_CAT <> 11 AND STATUS = 'Active' ";
			else
			$condit = " ID_TYPE= '".$get_id_type."' AND STATUS = 'Active' ";
			$count_idtype = $common->CountRecord("common",$condit,"ID_COMMON");
			// display list news
			if($count_idtype>1) $display_list =1;
			// display info detail default
			else{
				$display_list = 0;
				$info_news = $common->getInfo("common",$condit);
			}
		}			
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
			$link_page = "info-".$idtype."-".$name_link.".html?idcat=".$idcat;
		}
		// neu khong co muc con
		else{
			$info_title = $common->getInfo("common_type"," ID_TYPE = '".$get_id_type."'");			
			$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);						
			$_SESSION['get_title'] = $info_title['SNAME'.$pre];
			$link_page = "info-".$idtype."-".$name_link.".html?";
		}

	}
	elseif($get_id_type && $idcat && !$idcatsub){
		// check idcat has count(idcat)>1 then display list news where idcat
		// elseif is null or =1 then display detail info
		$condit = " ID_TYPE= '".$get_id_type."' AND ID_CAT= '".$idcat."' AND STATUS = 'Active' ";
		$info_news = $common->getInfo("common_cat",$condit);
		// check display
		if($info_news['HT']==2){			
			$display_list = 0;// display detail			
		}
		elseif($info_news['HT']==1 ){
			$count_idcat = $common->CountRecord("common",$condit,"ID_COMMON");
			// display list news
			if($count_idcat>1) $display_list =1;
			// display info detail default
			else{
				$display_list = 0;
				$info_news = $common->getInfo("common",$condit);
			}
		}
		
		// get title name link
		$info_title = $common->getInfo("common_cat"," ID_CAT = '".$idcat."'");
		$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['SNAME'.$pre];
		$link_page = "info-".$idtype."-".$name_link.".html?idcat=".$idcat;
	}
	elseif($get_id_type && $idcat && $idcatsub){
		// check idcat has count(idcatsub)>1 then display list news where idcatsub
		// elseif is null or =1 then display detail info	
		$condit = " ID_CAT= '".$idcat."' AND ID_CAT_SUB= '".$idcatsub."' AND STATUS = 'Active' ";
		$info_news = $common->getInfo("common_cat_sub",$condit);
		if($info_news['HT']==2){			
			$display_list = 0;// display detail			
		}
		elseif($info_news['HT']==1 ){		
			$count_idcatsub = $common->CountRecord("common",$condit,"ID_COMMON");
			// display list news
			if($count_idcatsub>1) $display_list =1;
			// display info detail default
			else{
				$display_list = 0;
				$info_news = $common->getInfo("common",$condit);
			}
		}
		// get title name link
		$info_title = $common->getInfo("common_cat_sub"," ID_CAT_SUB = '".$idcatsub."'");
		$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$_SESSION['get_title'] = $info_title['SNAME'.$pre];
		$link_page = "info-".$idtype."-".$name_link.".html?idcat=".$idcat."&idcatsub=".$idcatsub;
	}
	else{
		$condit = " STATUS = 'Active' AND ID_TYPE <> 5 ";
		$display_list = 1;					
		// get paging for default
		// get title name link
		//$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY'.$pre]);			
		$name_link = "danh-sach";
		$_SESSION['get_title'] = "Danh sach thong tin";
		$idtype = 0;
		$link_page = "info-".$name_link.".html?keyword=".$keyword;//
		// get menu selected when view product detail
		$name_query_string = "idtype=".$idtype;
	
	}
	
		
	$main_info->set('idtype', $get_id_type);
	
	// display list and paging	
	if($display_list ==1){

		//$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB");
		// get common information

		// hiển thị dịch vụ chăm sóc bà bầu			
		//$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC ", "list_3news_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
		//$tplnews = "list_news_img";
		// paging
		
	
	}
	
	if($idcat==21 || $idcat == 22 || $idcat == 23 || $idcat == 24 || $idcat == 25 ){
		$main_contents = $print->get_list_data_cat($pre,$idcat);
		/*
		$condit .= $keyword_criteria;
		$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT");
		$data = $print_2->list_row_column_multifield("common_cat", $condit, "ID_CAT", "SNAME", $field_name_other, " ORDER BY IORDER DESC, ID_CAT DESC ", "list_data_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
		print_r($data);
		$tplnews = "list_data";
		$tpl_news =& new Template($tplnews);
		$tpl_news->set('list_data_rs', $data);
		$main_contents = $tpl_news->fetch($tplnews);
		*/
		$dv_lienquan   = $print->get_dv_lienquan($pre,$idcat);
		$menu_sp       = $print->get_menu_lq($pre);
		$ykien_kh      = $print->get_ykien_info();
		$list_newsp    = $print->listsp_new($pre);		
		//print_r($main_contents);
		//$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
	}
	else if($idcat == 26 || $idcat == 27){
		$main_contents = $print->get_list_data($pre,$idcat);
		$menu_sp       = $print->get_menu_lq($pre);
		$ykien_kh      = $print->get_ykien_info();
		$list_newsp    = $print->listsp_new($pre);
		$dv_lienquan   = $print->get_dv_lienquan($pre,$idcat);
	}
	// picture slide show
	$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$main_info->set('intro', $tpl_intro);
	$main_info->set('menu_sp',$menu_sp);
	$main_info->set('ykien_kh',$ykien_kh);
	$main_info->set('sanpham_moi',$list_newsp);	
	$main_info->set('dv_lienquan',$dv_lienquan);
	// get title	
	$main_info->set('title_cat', $title_cat);	
	$main_info->set('main_contents', $main_contents);
	
	
	$main_info->set('display_list', $display_list);
	$main_info->set('titlepr', $titlepr);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpl_menur_pro= new Template('list_menur_pro');
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$main_info->set('list_menur_pro', $tpl_menur_pro);
	
	// get menu list common type
	$field_name_other1 = array("PICTURE","IORDER","STATUS");
	$field_name_other2 = array("ID_TYPE","PICTURE","IORDER","STATUS");
	$field_name_other3 = array("ID_CAT","PICTURE","IORDER","STATUS");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
	$tmpcat = "m_menu_cat_rs";
	$tmpcat_sub = "m_menu_catsub_rs";
	$tmppro_sub = "m_menu_prosub_rs";
	$menur = "m_list_menur";
	}
	else{
	$tmpcat = "menu_cat_rs";
	$tmpcat_sub = "menu_catsub_rs";
	$tmppro_sub = "menu_prosub_rs";
	$menur = "list_menur";
	}
	//$list_menur_rs = $print_2->GetListMN_right($field_name_other1, $tmpcat,  $field_name_other2, $tmpcat_sub, $field_name_other3, $tmppro_sub, $pre, $idtype, $idcat, $idcatsub, 0, 0, 0, 0, 0);
	$list_menur_rs = $print->getmenu_common($pre, 0, $tmpcat, $tmpcat_sub, $idtype);
	$tpl_menur= new Template($menur);
	$tpl_menur->set('list_menur_rs', $list_menur_rs);
	$tpl_menur->set('nametype', $name_titlecat['SNAME'.$pre]);
		
	$main_info->set('list_menur_common', $tpl_menur);
	
	//$support_online = $print->getSupportOnline(66);
	//$main_info->set('support_online', $support_online);
	
	$clip = $print->getclip();
	$main_info->set('clip', $clip);
	
	// get hot news	
	//if($idtype!=3)		
	//$hotnews_right = $print->gethot_news_right_tintuc($pre, 20);// tin tuc
	//else
	//$hotnews_right = $print->gethot_news_right($pre, 20);// golf corner
	
	//$main_info->set('hotnews_right', $hotnews_right);	
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_info->set('logo_partner', $logo_partner);

	// get statistic
	$main_info->set('statistics', $print->statistics());
	$main_info->set('todayaccess', $print->get_today( ));
	$main_info->set('counter', $print->get_counter());		
	
	// Get main 2
	$main->set('main2', $main_info);
	
	

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$footer = & new Template('m_footer');	
	else
	$footer = & new Template('footer');
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	$footer->set('todayaccess', $print->get_today());
	// get cong ty footer
	$cty = $common->gethonhop(6);
	if($cty == "")
	$footer->set('cty', "");
	else{
		$footer->set('title_welcome', $cty['STITLE'.$pre]);
		if($cty['SCONTENTS'.$pre]=="")
		$footer->set('cty', base64_decode($cty['SCONTENTS']));
		else
		$footer->set('cty', base64_decode($cty['SCONTENTS'.$pre]));
	}

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	// get so dien thoai dat hang
	$welcome = $common->gethonhop(4);
	if($welcome == "")
	$tpl->set('welcome', "");
	else{
		$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($welcome['SCONTENTS'.$pre]=="")
		$tpl->set('welcome', base64_decode($welcome['SCONTENTS']));
		else
		$tpl->set('welcome', base64_decode($welcome['SCONTENTS'.$pre]));
	}
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
	
?>