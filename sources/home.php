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
	$main = new Template('main');      

	$tempres=new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
		$tplmain = 'main_home';
	}
	else
		$tplmain = 'main_home';

	$temp_contents=new Template($tplmain);
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

/*	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpl_menur_pro= new Template('list_menur_pro');
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$temp_contents->set('list_menur_pro', $tpl_menur_pro);
*/	
	
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
		$tpl_title =new Template('title_subcat');		
		$tpl_title->set('namecat', "S&#7842;N PH&#7848;M M&#7898;I");
		$tpl_title->set('title_sp', "title");
		$title_cat = $tpl_title->fetch('title_subcat');		
	/*	
		$conditpro .= " AND TIEUBIEU = 1 ";
		$conditpro .= $keyword_criteria;
//echo $conditpro;		
		//$idmnselected = array(0 => $name_id, 1 => $vl_id);
		$idmnselected = $name_query_string;		
		$field_name_other = array('CATALOG','IMAGE_LARGE','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'ID_AGES', 'ID_SKILL', 'INFO_SHORT', 'CODE', 'COUNTVIEW','COUNT_QUANTITY', 'EAN_CODE', 'SIZE', '');
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby, "list_3all_images_procat", 3, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre );					             								 
		$tpl_pro =new Template('main_homelist_pro');
		$tpl_pro->set('title_cat', $title_cat);
		//echo $conditpro." ORDER BY ".$getsortby;
		if($list_images != "")
			$tpl_pro->set('list_all_images_procat', $list_images);
		else{
			$fet_boxno = new Template('noresult');
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
			$tpl_pro->set('paging' , '');		*/	
		
		//$main_contents = $tpl_pro->fetch('main_homelist_pro');
		$main_contents = $print->get_data_pro('products',1,$pre,'list_home_pro','list_home_pro_rs');
		
	}
	else{

		$main_contents = "";	
	}
	
$temp_contents->set('main_contents', $main_contents);



// ------------ Load sản phẩm tiêu biểu ------------

//$list_sp_tieubieu = $print->listsp_new(1000,$pre,'list_sp_tieu_bieu','list_sp_tieu_bieu_rs',1,0);// Lấy cate không trùng để load sản phẩm tiêu biểu
//$temp_contents->set('list_sp_tieubieu',$list_sp_tieubieu);

// --------- End Load sản phẩm tiêu biểu -----------


//5 sản phẩm mới nhất show trên cùng
$list_sp_home_top = $print->get_sp_home($pre,'list_sanphamhome','list_sanphamhome_rs',0,5);// Sản phẩm mới nhất
$temp_contents->set('list_sp_home_top',$list_sp_home_top);
//Sản phẩm cũ hơn
$list_sp_home_old = $print->get_sp_home($pre,'list_sanphamhome_old','list_sanphamhome_old_rs',5,25);// Sản phẩm cũ hơn
$temp_contents->set('list_sp_home_old',$list_sp_home_old);

//Tin tức slide top 
$list_news = $print->get_data('common',25,103,$pre,1,'list_home_pro','list_home_pro_rs',0,3);
$temp_contents->set('listnews_tieubieu',$list_news);

//List slide banner quảng cáo
$list_qc_banner = $print->get_data('common',26,119,$pre,0,'list_qc_banner','list_qc_banner_rs',0,3);
$temp_contents->set('list_qc_banner',$list_qc_banner);

//List slide quảng cáo top right 1
$list_qc_top_right1 = $print->get_data('common',26,117,$pre,0,'list_qc_top_right1','list_qc_top_right1_rs',0,3);
$temp_contents->set('list_qc_top_right1',$list_qc_top_right1);

//List slide quảng cáo top right 2
$list_qc_top_right2 = $print->get_data('common',26,118,$pre,0,'list_qc_top_right2','list_qc_top_right2_rs',0,3);
$temp_contents->set('list_qc_top_right2',$list_qc_top_right2);




//Quảng cáo (config banner quảng cáo)
$config_qc = $common->gethonhop(30);
if($config_qc == "")
	$config_qc->set('config_qc', "");
else{
	//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
if($config_qc['SCONTENTS'.$pre]=="")
	$temp_contents->set('config_qc', base64_decode($config_qc['SCONTENTS']));		
else
	$temp_contents->set('config_qc', base64_decode($config_qc['SCONTENTS'.$pre]));
	//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}

//Thông tin hữu ích
$info_huuich = $print->get_data('common',25,111,$pre,0,'list_thong_tin_huu_ich','list_thong_tin_huu_ich_rs',0,7);
$temp_contents->set('info_huuich',$info_huuich);

//Tiêu dùng thông thái
$info_thongthai = $print->get_data('common',25,112,$pre,0,'list_tieu_dung_thong_thai','list_tieu_dung_thong_thai_rs',0,7);
$temp_contents->set('info_thongthai',$info_thongthai);

//Quảng cáo phần content dang slide
$list_qc_content = $print->get_data('common',26,120,$pre,0,'list_qc_content','list_qc_content_rs',0,3);
$temp_contents->set('list_qc_content',$list_qc_content);

//List thương hiệu nổi bật
$list_thuong_hieu = $print->get_data('common',25,113,$pre,0,'list_thuong_hieu','list_thuong_hieu_rs',0,3);
$temp_contents->set('list_thuong_hieu',$list_thuong_hieu);


//Góc chuyên gia
$gocchuyengia = $common->gethonhop(54);
if($gocchuyengia == "")
	$gocchuyengia->set('gocchuyengia', "");
else{
	//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
if($gocchuyengia['SCONTENTS'.$pre]=="")
	$temp_contents->set('gocchuyengia', base64_decode($gocchuyengia['SCONTENTS']));		
else
	$temp_contents->set('gocchuyengia', base64_decode($gocchuyengia['SCONTENTS'.$pre]));
	//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}
//Trải nghiệm người dùng 
$trainghiem = $print->get_data('common',25,114,$pre,0,'list_trai_nghiem','list_trai_nghiem_rs',0,4);
$temp_contents->set('trainghiem',$trainghiem);

//Bí quyết kinh doanh online
//Tin mới nhất
$tinmoinhat = $print->get_data('common',25,121,$pre,0,'list_home_news','list_home_news_rs');
$temp_contents->set('tinmoinhat',$tinmoinhat);
//2 tin liên quan tiếp theo
$tinmoi = $print->get_data('common',25,121,$pre,0,'list_home_news2','list_home_news2_rs',1,2);
$temp_contents->set('tinmoi',$tinmoi);
//Show ra 4 tin cũ hơn
$old_news = $print->get_data('common',25,121,$pre,0,'list_home_news4','list_home_news4_rs',3,4);
$temp_contents->set('old_news',$old_news);

//Video giới thiệu sản phẩm
$list_video = $print->get_data('common',25,115,$pre,0,'list_videoclip','list_videoclip_rs',0,4);
$temp_contents->set('list_video',$list_video);

//Dịch vụ
$dichvu = $common->gethonhop(55);
if($dichvu == "")
	$dichvu->set('dichvu', "");
else{
	//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
if($dichvu['SCONTENTS'.$pre]=="")
	$temp_contents->set('dichvu', base64_decode($dichvu['SCONTENTS']));		
else
	$temp_contents->set('dichvu', base64_decode($dichvu['SCONTENTS'.$pre]));
	//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
}
//Học hỏi người thành công làm giàu
$list_thanhcong = $print->get_data('common',25,116,$pre,0,'list_thanhcong','list_thanhcong_rs',0,6);
$temp_contents->set('list_thanhcong',$list_thanhcong);


	// get statistic
$temp_contents->set('statistics', $print->statistics());
$temp_contents->set('todayaccess', $print->get_today( ));
$temp_contents->set('counter', $print->get_counter());

	
	$main->set('main2', $temp_contents); 

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	$footer = $common->getFooter('footer');
	

	//all
	$tpl = new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	$tpl->set('footer', $footer);
	// get img fb
	$pathimg = $itech->vars['pathimgfb'];
	if(isset($info_news['PICTURE']) && $info_news['PICTURE'] != "")
	$urlpic = $pathimg."/imagenews/".$info_news['PICTURE'];
	else
	$urlpic = $pathimg."../logof.jpg";
	$tenurl = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$tpl->set('nameimg', $urlpic);
	$tpl->set('nameurl', $tenurl);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
		echo $tpl->fetch('m_home');
	else
		echo $tpl->fetch('home');
	?>