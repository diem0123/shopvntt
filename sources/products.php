<?php

// kiem tra logged in
	
	$sapxep = $itech->input['sapxep'];
	//echo "sapxep=".$sapxep;
	$idc = $itech->input['idc'];
	$idt = $itech->input['idt'];
	$fil_idtype = $itech->input['fil_idtype'];
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	$keyword = $itech->input['keyword'];
	
	$ph = $itech->input['ph'];
	
	

	
	$keyword1 = $itech->input['keyword1'];
	if($idc !='' && $idt ==''){
		$data_cate = $common->getInfo("categories","ID_CATEGORY = ".$idc);
		$name_cat = $data_cate['NAME_CATEGORY'];
		$name_link = $print_2->vn_str_filter($name_cat);
		$_SESSION['name_link']=$name_link;
		unset($_SESSION['idtype']);
		$_SESSION['idcategory'] = $idc;
	}
	elseif($idc !='' && $idt !=''){
		$data_cate = $common->getInfo("categories_sub","ID_CATEGORY = ".$idc." AND ID_TYPE = ".$idt);
		$name_cat = $data_cate['NAME_TYPE'];
		$name_link = $print_2->vn_str_filter($name_cat);
		$_SESSION['idcategory'] = $idc;
		$_SESSION['idtype'] = $idt;
		$_SESSION['name_link']=$name_link;
	}
	// get menuselect
	
	$menuinfo = $common->getInfo("menu1","IDMUC = '1000'");
	if(isset($menuinfo['IDMUC'])) $_SESSION['tab'] = $menuinfo['IORDER'];
	else $_SESSION['tab'] = 1;
	
	
	//-----------End get list ID Itech-------------
	
	
	
	if($keyword1 && !$keyword) 
	{
		$keyword = $keyword1;
		
	}
	
	
	// get sort by
	switch($sapxep){
		case 1: $getsortby = " DATE_UPDATE DESC ";// san pham moi nhat $ph = 1;
		break;
/*		
		case 2: $ph = 2;$getsortby = " DATE_UPDATE DESC ";// san pham yeu thich nhat	
		break;
		case 3: $ph = 3;$getsortby = " DATE_UPDATE DESC ";// san pham ban chay nhat	
		break;
		case 4: $ph = 4;$getsortby = " DATE_UPDATE DESC ";// san pham co qua tang	
		break;
*/		
		case 2: $getsortby = " PRICE ";// gia thap - cao	
		break;
		case 3: $getsortby = " PRICE DESC ";// gia cao - thap	
		break;
		case 4: $getsortby = " PRODUCT_NAME  ";// ten a - z	
		break;
		case 5: $getsortby = " PRODUCT_NAME DESC ";// ten z - a	
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
			$tempkey = " ( PRODUCT_NAME LIKE '%".$word[0]."%' OR PRICE LIKE '%".$word[0]."%' OR SIZE LIKE '%".$word[0]."%')";    
			for ($i=1;$i<count($word);$i++)
			{
				$keyword_criteria .= " AND ( PRODUCT_NAME LIKE '%".$word[$i]."%' OR PRICE LIKE '%".$word[$i]."%' OR SIZE LIKE '%".$word[$i]."%')";
			}
			
			$keyword_criteria = $t1.$tempkey.$keyword_criteria;
			//echo $keyword_criteria;exit;
		}
		
		
	
	//if($ida && !$idsk && !$idprocat) $idprocat = $ida;
	//elseif(!$ida && $idsk && !$idprocat) $idprocat = $idsk;
	
	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	if($_SESSION['dv_Type']=="phone") $num_record_on_page = 10;// danh cho di dong
	else $num_record_on_page = 30;
	
	//$num_record_on_page = 16;//$itech->vars['num_record_on_page_column'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
	// get language
	$pre = $_SESSION['pre'];
    
	//header
	//$_SESSION['tab'] = 3; // set menu active: products
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header',0,1);
	
	//main
	$main = & new Template('main');      

	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
		  
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone") $tplmain = "m_main_products";
	else $tplmain = "main_products";
	
	$temp_contents= new Template($tplmain);
	$temp_contents->set("idcategory", $idc);
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
	elseif($idc ==0){
		$conditpro = " STATUS = 'Active'";
		$display_list = 1;					
		// get paging for default
		// get title name link
		//$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY'.$pre]);			
		$name_link = "danh-sach";
		$_SESSION['get_title'] = "Sản phẩm";
		$idc = 0;
		//$link_page = "sanpham-".$idc."-".$name_link.".html?keyword=".$keyword;//."&sapxep=".$sapxep
		$link_page = "tim-kiem-san-pham.html?keyword=".$keyword;
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc;
	
	}
	elseif($idc && !$idt && !$idprocat && !$idsk){	

		// Display Info products allows idc				
			$conditpro = "ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
			$display_list = 1;					
		
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY']);
		$name_link = $print->getlangvalue($info_title,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_CATEGORY'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?";//?sapxep=".$sapxep;
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc; 	

	}
	elseif($idc && $idt && !$idprocat && !$idsk){		
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_TYPE']);
		$name_link = $print->getlangvalue($info_title,"NAME_TYPE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_TYPE'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt;		
		
	}
	elseif($idc && $idt && $idprocat && !$idsk){
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_TYPE = '".$idt."' AND ID_PRO_CAT = '".$idprocat."' AND STATUS = 'Active'";
		$display_list = 1;
		// get paging for default
		// get title name link
		$info_title = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_PRO_CAT']);
		$name_link = $print->getlangvalue($info_title,"NAME_PRO_CAT",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);		
		$_SESSION['get_title'] = $info_title['NAME_PRO_CAT'.$pre];
		$link_page = "sanpham-".$idc."-".$name_link.".html?idt=".$idt."&idprocat=".$idprocat;//."&sapxep=".$sapxep
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc."&idt=".$idt."&idprocat=".$idprocat;			
		
	}
	elseif($idsk){
			// get list id products with idsk
			if($idsk==5000)
			$listidp = $common->getListValue("list_tinhnang"," ID_MT >0 ","ID_PRODUCT");
			else
			$listidp = $common->getListValue("list_tinhnang","ID_MT = '".$idsk."'","ID_PRODUCT");
			//echo "listidp=".$listidp;
			if($listidp=="") $listidp =0;
					
			$conditpro = " ID_PRODUCT IN(".$listidp.") AND STATUS = 'Active'";
			
			$display_list = 1;
			// get paging for default
			// get title name link
			$info_title = $common->getInfo("material_lookup","ID_MT = '".$idsk."'");
			$name_link = $print_2->vn_str_filter($info_title['NAME'.$pre]);
			$_SESSION['get_title'] = $info_title['NAME'.$pre];
			$link_page = "sanpham-".$idc."-".$name_link.".html?idsk=".$idsk;
			// get menu selected when view product detail
			$name_query_string = "idc=".$idc."&idsk=".$idsk;
			$lkidsk = '<a href="sanpham-'.$idc.'-'.$name_link.'.html?idsk='.$idsk.'" >'.$info_title['NAME'.$pre].'</a>';
		}
	else{
		$conditpro = " STATUS = 'Active'";
		$display_list = 1;					
		// get paging for default
		// get title name link
		//$info_title = $common->getInfo("categories","ID_CATEGORY = '".$idc."' AND STATUS = 'Active'");
		//$name_link = $print_2->vn_str_filter($info_title['NAME_CATEGORY'.$pre]);			
		$name_link = "danh-sach";
		$_SESSION['get_title'] = "Search product";
		$idc = 0;
		//$link_page = "sanpham-".$idc."-".$name_link.".html?keyword=".$keyword;//."&sapxep=".$sapxep
		$link_page = "tim-kiem-san-pham.html?keyword=".$keyword;
		// get menu selected when view product detail
		$name_query_string = "idc=".$idc;
	
	}

	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "list_menur_pro";
	$tpl_menur_pro= new Template($tpll);
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
		// get tite category
		$title_cat = "";
		$tpl_title = new Template('title_cat');
		
		$name_titlecat = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");
		$name_titlesubcat = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		$name_titlesubprocat = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		
		//$name_linkcat = $print_2->vn_str_filter($name_titlecat['NAME_CATEGORY']);
		$name_linkcat = $print->getlangvalue($name_titlecat,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		//$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['NAME_TYPE']);
		$name_linksubcat = $print->getlangvalue($name_titlesubcat,"NAME_TYPE",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		//$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['NAME_PRO_CAT']);
		$name_linksubprocat = $print->getlangvalue($name_titlesubprocat,"NAME_PRO_CAT",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		
		//$lkcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a><span class="arrow"><span></span></span></span>';
		//$lksubcat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
		//$lksubprocat = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a><span class="arrow"><span>&gt;</span></span></span>';
		//$linkT = '<span class="crust" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb" href="sanpham-0-danh-sach.html" >'.$lang['tl_prod'].'</a><span class="arrow"><span></span></span></span>';
		
		$lkcat = '<a href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a>';
		//$lkcat = $name_titlecat['NAME_CATEGORY'.$pre];
		$lksubcat = '<a href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a>';
		//$lksubcat = $name_titlesubcat['NAME_TYPE'.$pre];
		$lksubprocat = '<a href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a>';
		//$lksubprocat = $name_titlesubprocat['NAME_PRO_CAT'.$pre];
		//$linkT = '<a href="sanpham.html" >'.$lang['tl_sanpham'].'</a>';
		$linkT = $lang['tl_sanpham'];
		//$linkT = '';
		
		//if($idc) $linkT = "";		
		
		if(!$idc && !$idt && !$idprocat && !$idsk)
		$tpl_title->set('namecat', "<li><a href='#'>".$linkT."</a></li>");	
		elseif($idc && !$idt && !$idprocat && !$idsk)
		$tpl_title->set('namecat',"<li>".$lkcat."</li>");		
		elseif($idc && $idt && !$idprocat && !$idsk)
		$tpl_title->set('namecat',"<li> ".$lkcat." </li><li>".$lksubcat."</li>");
		elseif($idc && $idt && $idprocat && !$idsk)
		$tpl_title->set('namecat', "<li> ".$lkcat." </li><li> ".$lksubcat."</li> <li> ".$lksubprocat."</li>");
		elseif($idsk)
		$tpl_title->set('namecat', "<li>".$linkT." </li><li> ".$lkcat." </li><li> ".$lkidsk."</li>");
		
		$tpl_title->set('title_sp', "title_breadcrumb");
		
	/*	
		$name_titlecat = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");
		$name_titlesubcat = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");
		$name_titlesubprocat = $common->getInfo("product_categories","ID_PRO_CAT = '".$idprocat."'");
		$title_cat = "";
		$tpl_title =& new Template('title_subcat');
		
		$name_linkcat = $print_2->vn_str_filter($name_titlecat['NAME_CATEGORY'.$pre]);
		$name_linksubcat = $print_2->vn_str_filter($name_titlesubcat['NAME_TYPE'.$pre]);
		$name_linksubprocat = $print_2->vn_str_filter($name_titlesubprocat['NAME_PRO_CAT'.$pre]);
		$lkcat = '<a href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a>';
		$lksubcat = '<a href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a>';
		$lksubprocat = '<a href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a>';
	
	
		
		
		if(!$idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', "S&#7842;N PH&#7848;M");	
		elseif($idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $lkcat);		
		elseif($idc && $idt && !$idprocat)
		$tpl_title->set('namecat', $lkcat." -> ".$lksubcat);
		elseif($idc && $idt && $idprocat)
		$tpl_title->set('namecat', $lkcat." -> ".$lksubcat." -> ".$lksubprocat);
		
		$tpl_title->set('title_sp', "title");
		
		*/
		
		//$title_cat = $tpl_title->fetch('title_subcat');
		
		$tpl_title->set('idc', $idc);
		$title_cat = $tpl_title->fetch('title_cat');		
		$conditpro .= $keyword_criteria;

		$field_name_other = array('ID_CATEGORY','IMAGE_LARGE','IMAGE_BIG','NAME_CATEGORY','INFO_SHORT','COUNTVIEW');
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY ".$getsortby.", ID_PRODUCT DESC", "list_3all_images_procat", 5, $pageoffset, $num_record_on_page, $page, $idmnselected, $pre, 1 );		
		
		$tplpro_temp = 'main_list_product';
		$tpl_pro = new Template($tplpro_temp);
		//$tpl_pro->set('title_cat', $title_cat);
		
		if($list_images != "")
			$tpl_pro->set('list_all_images_procat', $list_images);
		else{
			$fet_boxno = & new Template('nokq');
			$text = $lang['tl_khongthayspham'];
			$fet_boxno->set("text", $text);
			$fb = $fet_boxno->fetch('nokq');
			$tpl_pro->set('list_all_images_procat', $fb);
		}
		
		$tpl_pro->set('idc', $idc);
		
		
		// paging
		$total_get = $common->CountRecord("products",$conditpro,"ID_PRODUCT");
		
		$class_link = "linkpage";
		if ($num_record_on_page < $total_get){			
			$tpl_pro->set('paging',$print_2->pagingphpA( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		}	
		else
			$tpl_pro->set('paging' , '');			
		
		// tong so ket qua	
		$tpl_pro->set('total_get', $total_get);
		$tpl_pro->set('idc', $idc);
		// get sortby	
		$tpl_pro->set('idc', $idc);
		$tpl_pro->set('idt', $idt);
		$tpl_pro->set('idprocat', $idprocat);
		$tpl_pro->set('ida', $ida);
		$tpl_pro->set('idsk', $idsk);
		$tpl_pro->set('keyword', $keyword);
		$tpl_pro->set('ph', $ph);
		for($i=1;$i<=5;$i++){
			if(!$sapxep && $i==1) {
			$tpl_pro->set('s'.$i, "selected");
			}
			elseif($i == $sapxep) $tpl_pro->set('s'.$i, "selected");
			else $tpl_pro->set('s'.$i, "");
			
		}
		// get list brand		
		//$list_brand = $print_2->GetDropDown($brand, "brand_lookup","" ,"ID_BR", "NAME", "IORDER");
		//$tpl_pro->set('list_brand', $list_brand);
		$main_contents = $tpl_pro->fetch($tplpro_temp);
	}
	else{
	
		$main_contents = "";	
	}
		// get gioi thiệu
	$gioithieu = $common->gethonhop(161);
	if($gioithieu == "")
		$temp_contents->set('gioithieu', "");
	else{
			//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($gioithieu['SCONTENTS'.$pre]=="")
			$temp_contents->set('gioithieu', base64_decode($gioithieu['SCONTENTS']));		
		else
			$temp_contents->set('gioithieu', base64_decode($gioithieu['SCONTENTS'.$pre]));
			//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
	}
	//List slide quảng cáo top right 1
	$list_qc_top_right1 = $print->get_data('common',26,117,$pre,0,'list_qc_top_right1','list_qc_top_right1_rs',0,3);
	$temp_contents->set('list_qc_top_right1',$list_qc_top_right1);

	//List slide quảng cáo top right 2
	$list_qc_top_right2 = $print->get_data('common',26,118,$pre,0,'list_qc_top_right2','list_qc_top_right2_rs',0,3);
	$temp_contents->set('list_qc_top_right2',$list_qc_top_right2);
	$temp_contents->set('idt',$idt);
	$temp_contents->set('idc',$idc);
	//End set color
	
	//Tin tức slide top 
	$list_news = $print->get_data('common',25,103,$pre,1,'list_home_pro','list_home_pro_rs',1,3);
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
	
	//------------Get size-------------- 
	$size = $print->get_size($idc,$idt,'list_size','list_size_rs');
	$temp_contents->set('size',$size);
	//----------End get Size------------
	
	
	
	if($idc and !$idt and !$idprocat){
		$get_cate = $common->getInfo('categories','ID_CATEGORY = '.$idc.' AND STATUS = "Active"');
		$name_cat = $get_cate['NAME_CATEGORY'];
	}
	elseif($idc and $idt and !$idprocat){
		$get_cate = $common->getInfo('categories_sub','ID_TYPE = '.$idt.' AND ID_CATEGORY = '.$idc.' AND STATUS = "Active"');
		$name_cat = $get_cate['NAME_TYPE'];
	}
	elseif($idc and $idt and $idprocat){
		$get_cate = $common->getInfo('product_categories','ID_TYPE = '.$idt.' AND ID_CATEGORY = '.$idc.' AND ID_PRO_CAT = '.$idprocat.' AND STATUS = "Active"');
		$name_cat = $get_cate['NAME_PRO_CAT'];
	}
	
	$temp_contents->set('name_cat',$name_cat);
	// Set quảng cáo Image
		
	
	//Sản phẩm mới
	//$list_sp_new = $print->get_sp_moi($pre,'list_sanphamhome','list_sanphamhome_rs');// Lấy cate không trùng để load sản phẩm tiêu biểu
	//$temp_contents->set('list_sp_new',$list_sp_new);
	//End sản phẩm mới
	$temp_contents->set('title_cat', $title_cat);
	$temp_contents->set('main_contents', $main_contents);
	
	//$support_online = $print->getSupportOnline(66);
	//$temp_contents->set('support_online', $support_online);
	
	$clip = $print->getclip();
	$temp_contents->set('clip', $clip);
	
	// get tin moi		
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){	
		$gethomenews = $print->m_gethomenews($pre, 1);
		$temp_contents->set('gethomenews', $gethomenews);	
	}
	else {
		// get hot news		
	$hotnews_right = $print->gethot_news_right_h($pre, 12);
	$temp_contents->set('hotnews_right', $hotnews_right);	
	
	}
	
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$temp_contents->set('logo_partner', $logo_partner);
	
	// get statistic
	$temp_contents->set('statistics', $print->statistics());
	$temp_contents->set('todayaccess', $print->get_today( ));
	$temp_contents->set('counter', $print->get_counter());
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$temp_contents->set('logo_partner', $logo_partner);
	
	if($idc){
	// get san pham tieu bieu
	//$list_sptb = $print->get_sptb($pre,$idmnselected);
	$temp_contents->set('abc', "");
	}
	$temp_contents->set("idc", $idc);
	

	$main->set('main2', $temp_contents); 	 

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	$footer = $common->getFooter('footer');
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