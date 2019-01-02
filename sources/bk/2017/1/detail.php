<?php

// kiem tra logged in

$idn = $itech->input['idn'];	
if($idn != "" )
$DB->query( "UPDATE common SET COUNTVIEW = COUNTVIEW+1 WHERE ID_COMMON = '".$idn."'" );

	//get detail
$condit = " ID_COMMON = '".$idn."' AND STATUS = 'Active' ";		
$info_news = $common->getInfo("common",$condit);
$get_idtype= $info_news['ID_TYPE'];
$get_idcat = $info_news['ID_CAT'];
$picture = $info_news['PICTURE'];
if($info_news['STITLE'.$pre]=="")
		$title_news = $info_news['STITLE'];//
	else
		$title_news = $info_news['STITLE'.$pre];//
		//$main_contents = $info_news['SCONTENTS'.$pre];
	if($info_news['SCONTENTS'.$pre]=="")
		$content_decode = base64_decode($info_news['SCONTENTS']);//
	else
		$content_decode = base64_decode($info_news['SCONTENTS'.$pre]);//
	$_SESSION['descript'] = $info_news['SCONTENTSHORT'.$pre];
	$main_contents = $content_decode;
	//$main_contents_short = $info_news['SCONTENTSHORT'.$pre];
	//$short_content = str_replace('- ','</br>- ',$info_news['SCONTENTSHORT'.$pre]);
	//$main_contents_short = $short_content;
	$main_contents_short = $info_news['SCONTENTSHORT'.$pre];
	$idtype = $info_news['ID_TYPE'];		
	$idcat = $info_news['ID_CAT'];
	$idcatsub = $info_news['ID_CAT_SUB'];
	
	// set menu active
	switch ($get_idtype){
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

	// get language
	$pre = $_SESSION['pre'];
	
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
	
	//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linkcat.'.html" >'.$name_titlecat['SNAME'.$pre].'</a><span class="arrow"><span></span></span></span>';
	//$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
	//$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';

	$lkcat = '<a  href="info-'.$idtype.'-'.$name_linkcat.'.html" >'.$name_titlecat['SNAME'.$pre].'</a>';
	$lksubcat = '<a  href="info-'.$idtype.'-'.$name_linksubcat.'.html?idcat='.$idcat.'" >'.$name_titlesubcat['SNAME'.$pre].'</a>';
	$lksubprocat = '<a  href="info-'.$idtype.'-'.$name_linksubprocat.'.html?idcat='.$idcat.'&idcatsub='.$idcatsub.'" >'.$name_titlesubprocat['SNAME'.$pre].'</a>';
	
	$lkcat = " <span>></span> ".$lkcat;
	if($idtype==3 || $idtype==6)
	$lkcat = "";// dau muc chung goc

if($idtype && !$idcat && !$idcatsub)
	$tpl_title->set('namecat', $lkcat);		
elseif($idtype && $idcat && !$idcatsub){
	//if($idtype==1 || $idtype==3) $lksubcat = '';// danh cho Tin tuc chung
	$tpl_title->set('namecat',$lksubcat);
}
elseif($idcatsub)
	$tpl_title->set('namecat',$lksubprocat);

	//$tpl_title->set('namecat', $lkcat);	
$tpl_title->set('title_sp', "title_breadcrumb");

$tpl_title->set('idtype', $idtype);
$tpl_title->set('idcat', $idcat);
$tpl_title->set('idcatsub', $idcatsub);

$title_cat = $tpl_title->fetch('title_subcat_info');

	//header
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
else
	$header = $common->getHeader('header');

	//main
$main = & new Template('main');      

$tempres=& new Template('main1');	  
$tempres->set('quote', ""); 
$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
if($_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_info";
	$tpl_detail = "m_detail1";
}
else{
	$tplmain = "main_detail";
	$tpl_detail = "detail1";
}

$main_info=& new Template($tplmain);

$temp_contents=& new Template($tpl_detail);	

	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
$tpl_menur_pro= new Template('list_menur_pro');
$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);

$main_info->set('list_menur_pro', $tpl_menur_pro);

$main_info->set('idtype', $idtype);
$temp_contents->set('main_contents_short',$main_contents_short);
$temp_contents->set('main_contents', $main_contents);
$main_info->set('detailinfo', $main_contents);
$temp_contents->set('title_news', $title_news);
$temp_contents->set('picture',$picture);
$_SESSION['get_title'] = $title_news;

	//set for url like icon	+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++				
	//$name_link_pro = $print_2->vn_str_filter($info_pro['PRODUCT_NAME']);
$name_link_pro = $print->getlangvalue($info_news,"STITLE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
$url_like = "chitiet-".$idn."-".$name_link_pro.".html";
$temp_contents->set('url_like',$url_like);
$temp_contents->set('idn', $idn);
$temp_contents->set('name_link_pro', $name_link_pro);

	// get menu list
$field_name_other1_news = array("ID_TYPE","IORDER");
$field_name_other2_news = array("ID_CAT","IORDER");
		//$list_cat_menu_news = $print_2->GetRowMenu("common_cat"," ID_TYPE= '".$info_news['ID_TYPE']."' ORDER BY IORDER ", "ID_CAT", "SNAME", $field_name_other1_news, "menu_cat_rs", "common_cat_sub","", "ID_CAT_SUB", "SNAME", $field_name_other2_news, "menu_catsub_rs",$pre, $info_news['ID_CAT'], $info_news['ID_CAT_SUB']);
		//$temp_contents->set('list_cat_menu_news', $list_cat_menu_news);

	// get others news
	// check for idcatsub not null
	$condit_other = "";
if($info_news['ID_CAT_SUB'] > 0){
	$condit_other = " ID_CAT_SUB = '".$info_news['ID_CAT_SUB']."' AND STATUS = 'Active' ";
		// get link for view all		
	$link_view_all = "info-".$info_news['ID_TYPE']."-".$_SESSION['name_link'].".html?idcat=".$info_news['ID_CAT']."&idcatsub=".$info_news['ID_CAT_SUB'];
}
elseif($info_news['ID_CAT'] > 0){
	$condit_other = " ID_CAT = '".$info_news['ID_CAT']."' AND STATUS = 'Active' ";
		// get link for view all		
	$link_view_all = "info-".$info_news['ID_TYPE']."-".$_SESSION['name_link'].".html?idcat=".$info_news['ID_CAT'];
}
if($condit_other != "")
$condit_other .= " AND ID_COMMON <> '".$idn."'";
else
$condit_other .= " AND ID_COMMON = '1100110000001'";

$field_name_other = array("SCONTENTSHORT","SCONTENTS","ID_TYPE","PICTURE","ID_CAT","ID_CAT_SUB");
$list_news_rs = $print_2->list_row_multi_field("common", $condit_other, "ID_COMMON", "STITLE", $field_name_other, " ORDER BY DATE_UPDATED DESC", "detail1_list_other", 0,10, 1, $idmnselected=0, $pre );

		// display view all or non display view all news of this catsub
$total_get = $common->CountRecord("common",$condit_other,"ID_COMMON");
		//echo $condit_other;
if($total_get >= 1) $show_link_viewall = 1;
else $show_link_viewall = 0;

$link_view_all = "info-".$info_news['ID_TYPE']."-".$name_linkcat.".html";
$temp_contents->set('list_news_other', $list_news_rs);
$temp_contents->set('total_get', $total_get);
$temp_contents->set('show_link_viewall', $show_link_viewall);
$temp_contents->set('link_view_all', $link_view_all);
if($get_idtype == 2 ){
	$data = $print->get_data_lq($get_idcat,$pre,'list_splq','list_splq_rs');
	//print_r($data);
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
	$temp_contents->set('idtype',$get_idtype);
	// get title	
$main_info->set('title_cat', $title_cat);

$main_info->set('intro2', "");
$main_info->set('idtype',$get_idtype);
$main_info->set('titlepr', "");
$main_info->set('display_list', 1);	
$main_info->set('splq',$data);
$main_info->set('main_contents', $temp_contents);		



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

	// Get main 2
$main->set('main2', $main_info);


	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
if($_SESSION['dv_Type']=="phone")
	$footer = & new Template('footer');	
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

	//get tin tuc
$detail_news = $print->get_tintuc($pre,$idn);	
$temp_contents->set('news',$detail_news);
$main_info->set('titlepr', "");
$main_info->set('display_list', 1);		
$main_info->set('titlepr', "");
$main_info->set('display_list', 1);	

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