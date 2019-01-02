<?php

	$_SESSION['descript'] = "";// mo ta cho the description
	$idtype = $itech->input['idtype'];
	$idcat = $itech->input['idcat'];
	$idcatsub = $itech->input['idcatsub'];
	$idn = $itech->input['idn'];
	$page = $itech->input['page'];
	$keyword = $itech->input['keyword'];
	
	// get menuselect
	$menuinfo = $common->getInfo("menu1","IDMUC = '".$idtype."'");
	if(isset($menuinfo['IDMUC'])) $_SESSION['tab'] = $menuinfo['IORDER'];
	else $_SESSION['tab'] = 1;
	
	
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = 9;//$itech->vars['num_record_on_page'];
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
	
	// set menu active
/*
	switch ($idtype){
		case 1: $_SESSION['tab'] = 2;// gioithieu
		break;
		case 2: $_SESSION['tab'] = 3;// Sản phẩm
		break;
		case 9: $_SESSION['tab'] = 4;// Nhà máy
		break;
		case 4: $_SESSION['tab'] = 5;// Vùng nguyên liệu
		break;
		case 7: $_SESSION['tab'] = 100;// Tư liệu
		break;
		case 10: $_SESSION['tab'] = 6;//Tin tức
		break;
		case 11: $_SESSION['tab'] = 7;//Tuyển dụng
		break;
		default: $_SESSION['tab'] = 1;// home
	}	
*/

	// get tite category
	$title_cat = "";
	$tpl_title =new Template('title_subcat_info');
	
	$name_titlecat = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
	$name_titlesubcat = $common->getInfo("common_cat","ID_TYPE = '".$idtype."' AND ID_CAT = '".$idcat."'");
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
		$name_titlesubcat1 = $common->getInfo("common_cat","ID_TYPE = '".$idtype."' AND ID_CAT = 1");
		$name_linksubcat1 = $print->getlangvalue($name_titlesubcat1,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		}
		else{
		$name_titlesubcat1 = $common->getInfo("common_cat","ID_TYPE = '".$idtype."' AND ID_CAT = 1");
		$name_linksubcat1 = $print->getlangvalue($name_titlesubcat1,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$name_titlesubcat = $common->getInfo("common_cat","ID_TYPE = '".$idtype."' AND ID_CAT = '".$idcat."'");
		$name_linksubcat = $print->getlangvalue($name_titlesubcat,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		}
	$link_df = 'info-'.$idtype.'-'.$name_linksubcat1.'.html?idcat=1';
	}
	else{
	$link_df = 'info-'.$idtype.'-'.$name_linkcat.'.html';
	}
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="'.$link_df.'" >'.$name_titlecat['SNAME'.$pre].'</a><span class="arrow"><span></span></span></span>';
	//$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	//$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	
	if(isset($name_titlecat['SNAME'.$pre]))
	$lkcat = '<a href="'.$link_df.'" >'.$name_titlecat['SNAME'.$pre].'</a>';
	else
	$lkcat = "";
	if(isset($name_titlesubcat['SNAME'.$pre]))
	$lksubcat = '<a href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a>';
	else
	$lksubcat = "";
	if(isset($name_titlesubprocat['SNAME'.$pre]))
	$lksubprocat = '<a href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a>';
	else
	$lksubprocat = "";
	
	//$lkcat = "";// khong cho hien thi chu dich vu
	if($idtype==3 && $idcat !="")
	$lkcat = "";// dau muc chung goc
	
	if($idtype && !$idcat && !$idcatsub){
		$tpl_title->set('namecat', $lkcat);
		//set for url like icon	++++++++++++++++++++++++++++++++						
		$url_like = $link_df;
	}
	elseif($idtype && $idcat && !$idcatsub){	
	$tpl_title->set('namecat', $lksubcat);
	$url_like = 'info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat;
	}
	elseif($idcatsub){
	$tpl_title->set('namecat',$lksubprocat);
	$url_like = 'info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub;
	}
	
	$tpl_title->set('title_sp', "title_breadcrumb");

	$tpl_title->set('idtype', $idtype);
	$tpl_title->set('idcat', $idcat);
	$tpl_title->set('idcatsub', $idcatsub);
	
	$title_cat = $tpl_title->fetch('title_subcat_info');
	
	
	
	
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');

	//main
	$main = new Template('main');      

	$tempres=new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") $tplmain = "m_main_info";
	else $tplmain = "main_info";
	$main_info=new Template($tplmain);		  
	
	if($idtype && !$idcat && !$idcatsub){		
		// get info
		$condit = " ID_TYPE = '".$idtype."' AND STATUS = 'Active' ";				
		$info_news = $common->getInfo("common_type",$condit);
		// check display
		if($info_news['HT']==2){			
			$display_list = 0;// display detail			
		}
		elseif($info_news['HT']==1 ){
			// check idtype has count(idtype)>1 then display list news where idcat
			// elseif is null or =1 then display detail info						
			// chi hien thi tin tong hop idcat=9
			if($idtype==2)
			$condit = " ID_TYPE= '".$idtype."' AND ID_CAT <> 10 AND ID_CAT <> 11 AND STATUS = 'Active' ";
			else
			$condit = " ID_TYPE= '".$idtype."' AND STATUS = 'Active' ";
			$count_idtype = $common->CountRecord("common",$condit,"ID_COMMON");
			//print_r($count_idtype);
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
			$info_title = $common->getInfo("common_type"," ID_TYPE = '".$idtype."'");			
			$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);						
			$_SESSION['get_title'] = $info_title['SNAME'.$pre];
			$link_page = "info-".$idtype."-".$name_link.".html?";
		}
		//echo $display_list;

	}
	elseif($idtype && $idcat && !$idcatsub){
		// check idcat has count(idcat)>1 then display list news where idcat
		// elseif is null or =1 then display detail info
		$condit = " ID_TYPE= '".$idtype."' AND ID_CAT= '".$idcat."' AND STATUS = 'Active' ";
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
				if($idtype == 2) $display_list = 1;
				else $display_list = 0;
				$info_news = $common->getInfo("common",$condit);
			}
		}
		
		// get title name link
		$info_title = $common->getInfo("common_cat"," ID_CAT = '".$idcat."'");
		$name_link = $print->getlangvalue($info_title,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['SNAME'.$pre];
		$link_page = "info-".$idtype."-".$name_link.".html?idcat=".$idcat;
		//echo $display_list;
	}
	elseif($idtype && $idcat && $idcatsub){
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
		$title_search = "Kết quả tìm kiếm";
		$_SESSION['get_title'] = "Danh sach thong tin";
		$idtype = 0;
		$link_page = "info-".$name_link.".html?keyword=".$keyword;//
		// get menu selected when view product detail
		$name_query_string = "idtype=".$idtype;
	
	}
	
		
	$main_info->set('idtype', $idtype);
	
	// display list and paging	
	if($display_list ==1){		
		$condit .= $keyword_criteria;
		//echo "111111111111111111111".$condit;
		$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB");
		// get common information
		if($idtype==1 ){
		//danh rieng cho hien thi hinh anh cong ty				
		//$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC ", "list_3news_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
		//$tplnews = "list_news_img";
		// paging
		//echo 'dadasd'; exit;
		$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
		}
		/*if( isset($idcat) ){
			//$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC ", "list_3news_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
			//$tplnews = "list_news_data";
			echo 'dddddddd';
		}*/
		
		elseif($idtype==2 ){
			
			$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC ", "list_3sv_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
			$tplnews = "list_sv_img";
			// paging
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
		}
		elseif($idtype == 10 ){
			$addmore = " AND STITLE".$pre." <> '' ";
			$condit .= $addmore;
			//echo "222222222222=".$condit;
			$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY HOME DESC, DATE_UPDATED DESC, ID_COMMON DESC ", "list_3news_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
			$tplnews = "list_3news_img";

			// paging
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
			
		}
		elseif($idtype == 9 || $idtype ==7 || $idtype==4 ){
			$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY HOME DESC, DATE_UPDATED DESC, ID_COMMON DESC ", "list_3news_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
			$tplnews = "list_3news_img";

			// paging
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
			
		}
		elseif($idtype==2000){
		//danh rieng cho hien thi hinh dich vu				
		$list_news_rs = $print_2->list_row_column_multifield("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC ", "list_3sv_rs", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );
		$tplnews = "list_sv_img";
		// paging
		$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
		}
		elseif($idtype==11) {
			$tplrs = "list_recruit_rs";
			$tplnews = "list_recruit";
			$list_news_rs = $print_2->list_row_multi_field("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC", $tplrs, $pageoffset,$num_record_on_page, $page, $idmnselected=0, $pre );			
			// paging
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
			// get banner tuyen dung
			$bannertd = $common->gethonhop(72);
			if($bannertd == "")
			$contentbanner = "";
			else{				
			if($bannertd['SCONTENTS'.$pre]=="")
				$contentbanner = base64_decode($bannertd['SCONTENTS']);				
			else
				$contentbanner = base64_decode($bannertd['SCONTENTS'.$pre]);				
			}
			
		}
		else{
			if($_SESSION['dv_Type']=="phone"){
				$tplrs = "m_list_news_rs";
				$tplnews = "m_list_news";
			}
			else{
								
				$tplrs = "list_search_rs";//"list_3news_rs";
				$tplnews = "list_3news_img";//"list_news";
				
			}

			$list_news_rs = $print_2->list_row_multi_field("common", $condit, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC, ID_COMMON DESC", $tplrs, $pageoffset,$num_record_on_page, $page, $idmnselected=0, $pre );
			//$tplnews = "list_news";
			// paging
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");
		}
		
		$tpl_news =new Template($tplnews);
		$tpl_news->set('list_news_rs', $list_news_rs);
		if($idtype==11)
		$tpl_news->set('contentbanner', $contentbanner);
		

		$class_link = "linkpage";
		if ($num_record_on_page < $total_get)
			$tpl_news->set('paging',$print_2->pagingphpA( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
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
		if($idtype==1){
			$list_about = $print->getmenusv($pre, 0, 'list_menu_about', 'list_menu_about_rs', 1);
			
			$main_info->set('menu_about',$list_about);
			$main_contents = $content_decode;
		}
		else
		$main_contents = $content_decode;
		$titlepr = $info_news['SNAME'.$pre];// title parent
	}
	
	$bannerqc = $common->gethonhop(111);
	if($bannerqc == "")
	$main_info->set('bannerqc', "");
	else{
		//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
	if($bannerqc['SCONTENTS'.$pre]=="")
		$main_info->set('bannerqc', base64_decode($bannerqc['SCONTENTS']));		
	else
		$main_info->set('bannerqc', base64_decode($bannerqc['SCONTENTS'.$pre]));
		//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
	}
	// picture slide show
	$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$main_info->set('intro', $tpl_intro);
		
	// get title
	$main_info->set('idtype',$idtype);
	$main_info->set('idcat', $idcat);
	if($idtype == "")
	$main_info->set('title_cat', $title_search);
	else
	$main_info->set('title_cat', $title_cat);
	
	$main_info->set('main_contents', $main_contents);
	$main_info->set('display_list', $display_list);
	$main_info->set('titlepr', $titlepr);
	
	$main_info->set('url_like',$url_like);
	
	
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
	if($idtype!=3)		
	$hotnews_right = $print->gethot_news_right_tintuc($pre, 20);// tin tuc
	else
	$hotnews_right = $print->gethot_news_right($pre, 20);// golf corner
	
	$main_info->set('hotnews_right', $hotnews_right);	
	
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
	$footer = new Template('m_footer');	
	else
	$footer = new Template('footer');
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
	$tpl = new Template();
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
	
// get img fb
$pathimg = $itech->vars['pathimgfb'];
if(isset($info_news['PICTURE']) && $info_news['PICTURE'] != "")
$urlpic = $pathimg."/imagenews/".$info_news['PICTURE'];
else
$urlpic = $pathimg."/imagenews/nhamaygcfood-14867987351006188375.jpg";
$tenurl = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$tpl->set('nameimg', $urlpic);
$tpl->set('nameurl', $tenurl);	

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
	
?>