<?php

// kiem tra logged in
	$_SESSION['get_title'] = "";// set title for home page
	$sapxep = $itech->input['sapxep'];
	//echo "sapxep=".$sapxep;
	$idc = $itech->input['idc'];
	$idt = $itech->input['idt'];
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	$keyword = $itech->input['keyword'];
	$ph = $itech->input['ph'];
	$keyword1 = $itech->input['keyword1'];
	if($keyword1 && !$keyword) $keyword = $keyword1;
	
	// get sort by
	switch($sapxep){
		case 1: $ph = 1;$getsortby = " DATE_UPDATE DESC ";// san pham moi nhat
		break;
		case 2: $ph = 2;$getsortby = " DATE_UPDATE DESC ";// san pham yeu thich nhat	
		break;
		case 3: $ph = 3;$getsortby = " DATE_UPDATE DESC ";// san pham ban chay nhat	
		break;
		case 4: $ph = 4;$getsortby = " DATE_UPDATE DESC ";// san pham co qua tang	
		break;
		case 5: $getsortby = " PRICE ";// gia thap - cao	
		break;
		case 6: $getsortby = " PRICE DESC ";// gia cao - thap	
		break;
		case 7: $getsortby = " PRODUCT_NAME  ";// ten a - z	
		break;
		case 8: $getsortby = " PRODUCT_NAME DESC ";// ten z - a	
		break;
		
		default: $getsortby = " DATE_UPDATE DESC ";//
		
	}
	
	$tempkey = "";
	$keyword_criteria = "";	
	
	// search keyword
	if ($keyword != "" && $keyword != "T&igrave;m ki&#7871;m" )
	{
			//$t1 = 0 < $local ? " AND " : " AND ";
		$t1 = " AND ";
		$word = explode(" ", $keyword );
		$tempkey = " ( PRODUCT_NAME LIKE '%".$word[0]."%' OR PRICE LIKE '%".$word[0]."%' OR INFO_SHORT LIKE '%".$word[0]."%')";    
		for ($i=1;$i<count($word);$i++)
		{
			$keyword_criteria .= " AND ( PRODUCT_NAME LIKE '%".$word[$i]."%' OR PRICE LIKE '%".$word[$i]."%' OR INFO_SHORT LIKE '%".$word[$i]."%')";
		}

		$keyword_criteria = $t1.$tempkey.$keyword_criteria;
			//$condit_keyword .= $keyword_criteria;
	}
	
	//if($ida && !$idsk && !$idprocat) $idprocat = $ida;
	//elseif(!$ida && $idsk && !$idprocat) $idprocat = $idsk;
	
	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = 21;//$itech->vars['num_record_on_page_column'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
	// get language
	$pre = $_SESSION['pre'];

	//header
	$_SESSION['tab'] = 1; // set menu active: products
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
	if($_SESSION['dv_Type']=="phone"){
		$tplmain = 'main_home';
	}
	else
		$tplmain = 'main_home';

	$temp_contents=& new Template($tplmain);
	// check conditional for display menu with ID_TYPE		
	// get menu selected when view product detail
	$name_query_string = "";
	
	// get products hot, favorites,..
	if($ph >0 ){
		if(!$idc) $idc =1;
		if(!$idt) $idt =1;
		if($ph==1){
			$name_link = "san-pham-hot";
			$conditpro = "ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
			$orderby = "DATE_UPDATE";

		}
		elseif($ph==2){
			$name_link = "san-pham-yeu-thich";
			$conditpro = "ID_CATEGORY = '".$idc."' AND FAVORITES > 0 AND STATUS = 'Active'";
			$orderby = "FAVORITES";		
		}
		elseif($ph==3) {
			$name_link = "san-pham-ban-chay";
			$conditpro = "ID_CATEGORY = '".$idc."' AND COUNT_BUY > 0 AND STATUS = 'Active'";
			$orderby = "COUNT_BUY";
		}
		elseif($ph==4) {
			$name_link = "san-pham-co-qua-tang";
			$conditpro = "ID_CATEGORY = '".$idc."' AND GIF = 1 AND STATUS = 'Active'";
			$orderby = "DATE_UPDATE";
		}
		// get menu select
		for($i=1;$i<=4;$i++){
			if(!$ph && $i==1) $temp_contents->set('st'.$i, "style21");
			elseif($ph && $ph == $i) $temp_contents->set('st'.$i, "style21");
			else $temp_contents->set('st'.$i, "style1");
		}
		
		$display_list = 1;											
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt."&ph=".$ph;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt."&ph=".$ph;	
		//$idmnselected = array(0 => $name_id, 1 => $vl_id);
		//$idmnselected = $name_query_string;
	}
	
	elseif($idc && !$idt && !$idprocat ){		
		// Display Info products allows idc				
		$conditpro = "ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
		$display_list = 1;					
		
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY']);
		$name_link = $print->getlangvalue($info_title,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$link_page = "sanpham-".$idc."-".$name_link.".html?sapxep=".$sapxep;
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc; 	

	}
	elseif($idc && $idt && !$idprocat){		
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_TYPE']);
		$name_link = $print->getlangvalue($info_title,"NAME_TYPE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);			
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt;		
		
	}
	elseif($idc && $idt && $idprocat){
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND ID_PRO_CAT = '".$idprocat."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_PRO_CAT']);
		$name_link = $print->getlangvalue($info_title,"NAME_PRO_CAT",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);			
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt."&idprocat=".$idprocat;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt."&idprocat=".$idprocat;			
		
	}
	else{
		$conditpro = " STATUS = 'Active'";
		$display_list = 1;					
		// get paging for default
		// get title name link
		//$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY'.$pre]);			
		$name_link = "san-pham-moi";
		$idc = 0;
		$link_page = "trangchu-".$idc."-".$name_link.".html?keyword=".$keyword;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc;

	}
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpl_menur_pro= new Template('list_menur_pro');
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$temp_contents->set('list_menur_pro', $tpl_menur_pro);
	
	//*************** Chu y: Dong lenh nay phai dat duoi get menu list moi lay duoc session *************
	/*
	if($idt && $idprocat)
	$link_page = "sanpham-".$idc."-".$_SESSION['name_link'].".html?idt=".$idt."&idprocat=".$idprocat;
	elseif($idt && !$idprocat)
	$link_page = "sanpham-".$idc."-".$_SESSION['name_link'].".html?idt=".$idt;
	*/
	// display list and paging: hien thi chung cho tat ca cac truong hop cap bac menu phia tren	
	if($display_list ==1){
		
		$title_cat = "";
		$tpl_title =& new Template('title_subcat');		
		$tpl_title->set('namecat', "S&#7842;N PH&#7848;M M&#7898;I");
		$tpl_title->set('title_sp', "title");
		$title_cat = $tpl_title->fetch('title_subcat');		

		$conditpro .= " AND TIEUBIEU = 1 ";
		$conditpro .= $keyword_criteria;
//echo $conditpro;		
		//$idmnselected = array(0 => $name_id, 1 => $vl_id);
		$idmnselected = $name_query_string;		
		$field_name_other = array('CATALOG','IMAGE_LARGE','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'ID_AGES', 'ID_SKILL', 'INFO_SHORT', 'CODE', 'COUNTVIEW','COUNT_QUANTITY', 'EAN_CODE', 'SIZE', '');
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby, "list_3all_images_procat", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );					             								 
		$tpl_pro =& new Template('main_homelist_pro');
		$tpl_pro->set('title_cat', $title_cat);
		//echo $conditpro." ORDER BY ".$getsortby;
		if($list_images != "")
			$tpl_pro->set('list_all_images_procat', $list_images);
		else{
			$fet_boxno = & new Template('noresult');
			$text = "Kh&ocirc;ng t&igrave;m th&#7845;y s&#7843;n ph&#7849;m!";
			$fet_boxno->set("text", $text);
			$fb = $fet_boxno->fetch('noresult');
			$tpl_pro->set('list_all_images_procat', $fb);
		}
		
		// paging
		$total_get = $common->CountRecord("products",$conditpro,"ID_PRODUCT");
		$class_link = "linkpage";
		if ($num_record_on_page < $total_get){			
			$tpl_pro->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		}	
		else
			$tpl_pro->set('paging' , '');			
		
		$main_contents = $tpl_pro->fetch('main_homelist_pro');
	}
	else{

		$main_contents = "";	
	}
	
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
// picture slide show
		$tpl_intro= new Template('m_main_slide');
		$idps = 1;// banner trang chu
		$list_img = $print->getimg_slide_mobi($idps);
		//echo $list_img;
		$tpl_intro->set('list_img', $list_img);
		//$slide_mobi = $tpl_intro->fetch('m_main_slide');
		$temp_contents->set('intro', $tpl_intro);

	} else {
	// picture slide show
		$tpl_intro= new Template('main_slide');
	$idps = 1;// banner trang chu
	$list_img = $print->getimg_slide($idps);
	//echo $list_img;
	$tpl_intro->set('list_img', $list_img);
	$temp_contents->set('intro', $tpl_intro);
}	

$temp_contents->set('main_contents', $main_contents);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
if($_SESSION['dv_Type']=="phone"){
	// get san pham tieu bieu
	$field_name_other = array('CATALOG','IMAGE_LARGE','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'PRICE_SALE', 'SALE', 'INFO_SHORT', 'CODE', 'COUNTVIEW');
	$cdit = "STATUS = 'Active' AND TIEUBIEU = 1 ";
	$listsptb = $print_2->list_row_column_multifield("products", $cdit, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby, "m_listsptb_rs", 2, 0, 8, $page, $idmnselected, $pre, 1 );	
	$tpl_sp = new Template('m_listsptb');	
	$tpl_sp->set('list_sptb_rs', $listsptb);
	$temp_contents->set('listsptb', $tpl_sp);
	// get hot news
	$newsrun = $print->getnews_ud_home();
	$temp_contents->set('news_uudai', $newsrun);
}
else{
	
	//get video clip 
	//$get_video = $print->get_videoclip(30);
	//$temp_contents->set('video',$get_video);
		//get san pham noi bat
	$sanphamnoibat = $print->get_data('common',2,$pre,1,'list_sanphamnoibat','list_sanphamnoibat_rs');
	$temp_contents->set('sanphamnoibat', $sanphamnoibat);
	//Vùng nguyên liệu
	$nguyenlieu = $print->get_data_cat('common_cat',2,$pre,0,'list_cat','list_cat_rs');
	$temp_contents->set('nguyenlieu', $nguyenlieu);
	//get gia tri dinh duong
	$giatridinhduong = $print->get_giatridinhduong($pre);
	$temp_contents->set('giatridinhduong', $giatridinhduong);
		//get banner quang cao
	$bannerqc = $print->get_bannerqc($pre);
	
	$bannerqc = $common->gethonhop(111);
	if($bannerqc == "")
	$temp_contents->set('bannerqc', "");
	else{
		//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
	if($bannerqc['SCONTENTS'.$pre]=="")
		$temp_contents->set('bannerqc', base64_decode($bannerqc['SCONTENTS']));		
	else
		$temp_contents->set('bannerqc', base64_decode($bannerqc['SCONTENTS'.$pre]));
		//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
	}

}	


	// get tieu de san pham 1
$cat_titlesp1 = $print->getcat_title(1, $pre);
$temp_contents->set('cat_titlesp1', $cat_titlesp1);
	// get san pham list 1 moi nhat
$idmnselected = $name_query_string;		
$field_name_other = array('CATALOG','IMAGE_LARGE','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'PRICE_SALE', 'SALE', 'INFO_SHORT', 'CODE', 'COUNTVIEW');
$conditcat1 = "STATUS = 'Active'";
	// get tin moi		
$field_name_other1 = array('STATUS','PICTURE','SCONTENTSHORT', 'ID_CAT_SUB');
$conditcatsv = "ID_TYPE NOT IN(1,4,5) AND STATUS = 'Active'";

	// get san pham list 1 moi nhat
$list_1 = $print_2->list_row_column_multifield("products", $conditcat1, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby, "list_3all_images_procat", 3, 0, 9, $page, $idmnselected, $pre, 1 );
	// get san pham list 2 xem nhieu nhat	
$list_2 = $print_2->list_row_column_multifield("products", $conditcat1, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY COUNTVIEW DESC", "list_7all_images_procat", 7, 0, 14, $page, $idmnselected, $pre, 1 );		
	// get tin moi
$list_3 = $print_2->list_row_column_multifield("common", $conditcatsv, "ID_COMMON", "STITLE", $field_name_other1, " ORDER BY DATE_UPDATED DESC ", "list_svhome_rs", 2, 0, 4, $page, $idmnselected, $pre );


$temp_contents->set('list_1', $list_1);
$temp_contents->set('list_2', $list_2);
$temp_contents->set('list_3', $list_3);

$clip = $print->getclip();
$temp_contents->set('clip', $clip);

	// get tin moi		
//+++++++++++++++++++ check mobile +++++++++++++++++++++	
if($_SESSION['dv_Type']=="phone"){	
	$gethomenews = $print->m_gethomenews($pre, 1);
	$temp_contents->set('gethomenews', $gethomenews);
	// get hot news	
	$gethot_news = $print->m_gethomenews_right($pre, 6);
	$temp_contents->set('gethot_news', $gethot_news);
	
}
else {
	// get hot news		
	$hotnews_right = $print->gethot_news_right_h($pre, 12);
	$temp_contents->set('hotnews_right', $hotnews_right);	
	
}

		//$support_online = $print->getSupportOnline(66);
	//$temp_contents->set('support_online', $support_online);
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$temp_contents->set('logo_partner', $logo_partner);

	// get statistic
$temp_contents->set('statistics', $print->statistics());
$temp_contents->set('todayaccess', $print->get_today( ));
$temp_contents->set('counter', $print->get_counter());

	// get welcome
$welcome = $common->gethonhop(4);
if($welcome == "")
	$temp_contents->set('welcome', "");
else{
		//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
	if($welcome['SCONTENTS'.$pre]=="")
		$temp_contents->set('welcome', base64_decode($welcome['SCONTENTS']));		
	else
		$temp_contents->set('welcome', base64_decode($welcome['SCONTENTS'.$pre]));
		//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}
	// get dichvu
$service = $common->gethonhop(4);
if($service == "")
	$temp_contents->set('service', "");
else{
		//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
	if($service['SCONTENTS'.$pre]=="")
		$temp_contents->set('service', base64_decode($service['SCONTENTS']));		
	else
		$temp_contents->set('service', base64_decode($service['SCONTENTS'.$pre]));
		//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}
$sologan = $common->gethonhop(112);
if($sologan == "")
	$temp_contents->set('sologan', "");
else{
		//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
	if($sologan['SCONTENTS'.$pre]=="")
		$temp_contents->set('sologan', base64_decode($sologan['SCONTENTS']));		
	else
		$temp_contents->set('sologan', base64_decode($sologan['SCONTENTS'.$pre]));
		//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}

	// get sortby	
$temp_contents->set('idc', $idc);
$temp_contents->set('idt', $idt);
$temp_contents->set('idprocat', $idprocat);
$temp_contents->set('ida', $ida);
$temp_contents->set('idsk', $idsk);
$temp_contents->set('keyword', $keyword);
$temp_contents->set('ph', $ph);
for($i=1;$i<=8;$i++){
	if(!$sapxep && $i==1) {
		$temp_contents->set('s'.$i, "selected");
	}
	elseif($i == $sapxep) $temp_contents->set('s'.$i, "selected");
	else $temp_contents->set('s'.$i, "");

}
	// tong so ket qua	
$temp_contents->set('total_get', $total_get);
	$temp_contents->set('forpro', 1);// nhan dien sort by chi danh cho product, cac phan khac dung chung main products khong xuat hien sort by nay

	$main->set('main2', $temp_contents); 




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
	// get link cty
	$linkf = $common->gethonhop(89);
	if($cty == "")
		$footer->set('linkf', "");
	else{
		$footer->set('title_welcome', $linkf['STITLE'.$pre]);
		if($linkf['SCONTENTS'.$pre]=="")
			$footer->set('linkf', base64_decode($linkf['SCONTENTS']));
		else
			$footer->set('linkf', base64_decode($linkf['SCONTENTS'.$pre]));
	}
	
	

	//all
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	
	// get support
	$sport = $common->gethonhop(66);
	if($sport == "")
		$tpl->set('sport', "");
	else{		
		if($sport['SCONTENTS']=="")
			$tpl->set('sport', base64_decode($sport['SCONTENTS']));
		else
			$tpl->set('sport', base64_decode($sport['SCONTENTS'.$pre]));
	}

	$tpl->set('footer', $footer);
	
// get img fb
$pathimg = $itech->vars['pathimgfb'];
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