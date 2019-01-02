<?php

// kiem tra logged in	
	$now = getdate(); 
	$idp = $itech->input['idp'];
	if($idp != "" )
	$DB->query( "UPDATE products SET COUNTVIEW = COUNTVIEW+1 WHERE ID_PRODUCT = '".$idp."'" );
	$idcomment = $itech->input['idcomment'];
	$where = "STATUS = 'Active' AND ID_PRODUCT = ".$idp;



	$get_data_id = $common->getInfo('products',$where);
	$get_idcat = $get_data_id['ID_CATEGORY'];
	$get_idtype = $get_data_id['ID_TYPE'];
	$idbrand = $get_data_id['ID_BRAND'];
	//Get name brand 
	$get_data_brand = $common->getInfo('brand','ID_BRAND = '.$idbrand.' AND STATUS = "Active"');
	$name_brand = $get_data_brand['NAME_BRAND'];
	//End get name brand

	$idcapacity = $itech ->input['idcapacity'];
		$where1= "STATUS = 'Active' AND ID_CAPACITY = ".$idcapacity;
	$get_data_id_capacity = $common->getInfo('capacity_product',$where1);
	$get_idp = $get_data_id_capacity['ID_PRODUCT'];

	$wheresub = "STATUS = 'Active' AND ID_TYPE = ".$get_idtype;
	$get_data_sub = $common->getInfo('categories_sub',$wheresub);
	if($get_data_sub['NAME_TYPE'.$pre]=='')
		$name_type = $get_data_sub['NAME_TYPE'];
	else
		$name_type = $get_data_sub['NAME_TYPE'.$pre];
	//Get list order
	$condit_list_order = "ID_TYPE = ".$get_data_id['ID_TYPE'].' AND ID_PRODUCT <>'.$idp." AND STATUS = 'Active'" ;
	// check for get menu selected
	$idc_mn = $itech->input['idc'];
	$idt_mn = $itech->input['idt'];
	$idprocat_mn = $itech->input['idprocat'];
	$ida_mn = $itech->input['ida'];
	$idsk_mn = $itech->input['idsk'];
	if($idc_mn && !$idt_mn && (!$idprocat_mn && !$ida_mn && !$idsk_mn)) $name_query_string = "idc=".$idc_mn;
	elseif($idc_mn && $idt_mn && (!$idprocat_mn && !$ida_mn && !$idsk_mn)) $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn;
	elseif($idc_mn && $idt_mn && ($idprocat_mn || $ida_mn || $idsk_mn)){
		if($ida_mn){ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&ida=".$ida_mn; }
		elseif($idsk_mn){ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&idsk=".$idsk_mn; }
		else{ $name_query_string = "idc=".$idc_mn."&idt=".$idt_mn."&idprocat=".$idprocat_mn; }
	}

	$page = $itech->input['page'];
	if ($page == 0 || $page == "")
		$page=1;

	$num_record_on_page = $itech->vars['num_record_on_page_column'];
	$maxpage = 10; // group 10 link on paging list
	
	$pageoffset =($page-1)* $num_record_on_page;
	// get language
	$pre = $_SESSION['pre'];
    
	//header
	$_SESSION['tab'] = 3; // set menu active: products
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
		$header = $common->getHeader('header');
	else
		$header = $common->getHeader('header',0,1);
	
	//main
	$main = new Template('main');      

	$tempres=new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 
	
	$tplmain = "main_prodetail";
	$tplmain_detail = "product_detail";

	// Get data main 2
	$main_out= new Template($tplmain);
	$temp_contents= new Template($tplmain_detail);	
	
	
	//get detail
	if($idp){
	$info_pro = $common->getInfo("products","ID_PRODUCT = '".$idp."' AND STATUS = 'Active'");	
	$idc = $info_pro['ID_CATEGORY'];
	$idt = $info_pro['ID_TYPE'];
	$idbrand = $info_pro['ID_BRAND'];
	$idprocat = $info_pro['ID_PRO_CAT'];
	$_SESSION['descript'] = $info_pro['INFO_SHORT'.$pre];
	
		// get tite category
		$title_cat = "";
		$tpl_title = new Template('title_subcat');
		
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
		
		//$lkcat = '<li><a href="sanpham-'.$idc.'-'.$name_linkcat.'.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a></li>';
		$lkcat = '<li><a href="sanpham.html" >'.$name_titlecat['NAME_CATEGORY'.$pre].'</a></li>';
		$lksubcat = '<li><a href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a></li>';
		$lksubprocat = '<li><a href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a></li>';
		$linkT = '<li><a href="sanpham.html" >'.$lang['tl_sanpham'].'</a></li>';
		//$linkT = '';
		
		if(!$idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $linkT);	
		elseif($idc && !$idt && !$idprocat)
		$tpl_title->set('namecat', $linkT.$lkcat);		
		elseif($idc && $idt && !$idprocat)
		$tpl_title->set('namecat', $lkcat.$lksubcat);
		elseif($idc && $idt && $idprocat)
		$tpl_title->set('namecat', $lkcat.$lksubcat.$lksubprocat);
		
		$tpl_title->set('title_sp', "title_breadcrumb");
		
		$title_cat = $tpl_title->fetch('title_subcat');
	
		$temp_contents->set('title_cat', $title_cat);
		$temp_contents->set('lksubcat_imagelink', $lksubcat_imagelink);
		if($info_pro['PRODUCT_NAME'.$pre]=="")
		$temp_contents->set('product_name',$info_pro['PRODUCT_NAME']);
		else
		$temp_contents->set('product_name',$info_pro['PRODUCT_NAME'.$pre]);
		$temp_contents->set('product_code',$info_pro['CODE']);
		if($info_pro['PRICE']>0)
		$temp_contents->set('price', number_format($info_pro['PRICE'],0,"",".")." VN&#272;");
		else $temp_contents->set('price', "Call");
		
		if($info_pro['SALE'] != "" && $info_pro['SALE']>0){
			$temp_contents->set("price", "<s>".number_format($info_pro['PRICE'],0,"",".")."</s> VN&#272;");
			$temp_contents->set("PRICE_SALE", number_format(($info_pro['PRICE']-$info_pro['PRICE']*$info_pro['SALE']/100),0,"",".")." VN&#272;");						
			$temp_contents->set('SALE',$info_pro['SALE']);
		}
		else{
			$temp_contents->set("PRICE_SALE", "");
			$temp_contents->set('SALE',"");
		}
		
		if($info_pro['EXPIRE'] == 0){
		$expire = "C&ograve;n h&agrave;ng";
		$temp_contents->set('count_quantity',$info_pro['COUNT_QUANTITY']);
		}
		else {
		$expire = "H&#7871;t h&agrave;ng";
		$temp_contents->set('count_quantity',0);
		}
		$temp_contents->set('expire',$expire);
		
		$temp_contents->set('age',$info_pro['AGE']);
		$temp_contents->set('size',$info_pro['SIZE']);
		$temp_contents->set('standard',$info_pro['STANDARD']);
		$temp_contents->set('material',$info_pro['MATERIAL']);
		$temp_contents->set('ean_code',$info_pro['EAN_CODE']);
		
		
		//$temp_contents->set('count_quantity',$info_pro['COUNT_QUANTITY']);
					
			// +++++++++++++++++++++++++++++++++++++++++++ Get detail sub product
										
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		
			$templ = 'detail_subpro_rs';
		//$templ = 'detail_subpro_rs';// templ for toys
		
		$detail_subpro = new Template($templ);
		//$detail_subpro->set('idp',$idp);
		$detail_subpro->set('list_all_images_procat', $list_images);
		$detail_subpro->set('age',$info_pro['AGE']);
		$detail_subpro->set('picture',$info_pro['IMAGE_LARGE']);
		$detail_subpro->set('picture_big',$info_pro['IMAGE_BIG']);
		$detail_subpro->set('ID_CATEGORY',$info_pro['ID_CATEGORY']);

		if($info_pro['PRODUCT_NAME'.$pre]=="")
		$detail_subpro->set('product_name',$info_pro['PRODUCT_NAME']);
		else
		$detail_subpro->set('product_name',$info_pro['PRODUCT_NAME'.$pre]);
		$detail_subpro->set('product_code',$info_pro['CODE']);
		$detail_subpro->set('EXPIRE',$info_pro['EXPIRE']);
		$detail_subpro->set('size',$info_pro['SIZE']);
		$detail_subpro->set('standard',$info_pro['STANDARD']);					
		$detail_subpro->set('USE_IN',$info_pro['USE_IN']);
		
		$info_mt = $common->getInfo("material_lookup","ID_MT = '".$info_pro['ID_MT']."' AND STATUS = 'Active'");
		$detail_subpro->set('material',$info_mt['NAME']);
		$detail_subpro->set('ORIGIN',$info_pro['ORIGIN']);
		$detail_subpro->set('COUNTVIEW',$info_pro['COUNTVIEW']);

		
		// set star
		$star = $print_2->getstar($idp);
		$detail_subpro->set('star', $star);					
		
		$detail_subpro->set('gender',$info_pro['SEX']);
		if($info_pro['SEX']==1){
			$detail_subpro->set('gender',"Nam");
		}
		elseif($info_pro['SEX']==0){
			$detail_subpro->set('gender',"Nữ");
		}
		if($info_pro['PRICE']>0)
		$detail_subpro->set('price', number_format($info_pro['PRICE'],0,"","."));
		else
		$detail_subpro->set('price', "Call");
		
		if($info_pro['SALE'] != "" && $info_pro['SALE']>0){
			$detail_subpro->set("price", number_format($info_pro['PRICE'],0,"","."));
			$detail_subpro->set("PRICE_SALE", number_format(($info_pro['PRICE']-$info_pro['PRICE']*$info_pro['SALE']/100),0,"",".")." VN&#272;");						
			$detail_subpro->set('SALE',$info_pro['SALE']);
			$detail_subpro->set("PRICE_DISC", number_format($info_pro['PRICE']*$info_pro['SALE']/100,0,"",".")." VN&#272;");
		}
		else{
			$detail_subpro->set("PRICE_SALE", "");
			$detail_subpro->set('SALE',"");
			$detail_subpro->set("PRICE_DISC", "");
		}
		
		if($info_pro['EXPIRE'] == 0){
		$expire = "C&ograve;n h&agrave;ng";
		$detail_subpro->set('count_quantity',$info_pro['COUNT_QUANTITY']);
		}
		else {
		$expire = "H&#7871;t h&agrave;ng";
		$detail_subpro->set('count_quantity',0);
		}
		$detail_subpro->set('expire',$expire);
		$detail_subpro->set('idp',$idp);

		if($info_pro['INFO_SHORT'.$pre]=="")
		$content_short = $info_pro['INFO_SHORT'];
		else
		$content_short = $info_pro['INFO_SHORT'.$pre];
		$detail_subpro->set('content_short', $content_short);
		
		if($info_pro['INFO_DETAIL'.$pre]=="")
		$content_decode = base64_decode($info_pro['INFO_DETAIL']);
		else
		$content_decode = base64_decode($info_pro['INFO_DETAIL'.$pre]);
		$detail_subpro->set('info_detail', $content_decode);
		if($info_pro['CATALOG'] == ""){
			$detail_subpro->set('hasfile', 0);
		}
		else{
			$detail_subpro->set('hasfile', 1);
			$detail_subpro->set('CATALOG', $info_pro['CATALOG']);
			$detail_subpro->set('ID_CATEGORY', $info_pro['ID_CATEGORY']);
			// check login to download
			if (isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] != "")
			$detail_subpro->set('checkdown', 1);
			else
			$detail_subpro->set('checkdown', 0);
		}
		//Get img liên quan
		if($_SESSION['dv_Type']=="phone"){
		$tsl1 = "m_images_link_slide";
		$tsl2 = "m_images_link";
		}
		else {
		$tsl1 = "images_link_slide";
		$tsl2 = "images_link";
		}
		$idmnselected_linnk = '';
		$field_name_other_link = array('NAME_IMG','ID_PRODUCT','ID_CATEGORY','NAME');
		$condit_link = "ID_PRODUCT = '".$idp."'";
		$list_images_link_slide = $print_2->list_row_column_multifield("images_link", $condit_link, "ID_IMG", "NAME_IMG", $field_name_other_link, " ORDER BY IORDER", $tsl1, 4, 0, 0, 0, '', $pre );
		//print_r($list_images_link_slide);exit;
		$list_images_link = $print_2->list_row_column_multifield("images_link", $condit_link, "ID_IMG", "NAME_IMG", $field_name_other_link, " ORDER BY IORDER", $tsl2, 3, 0, 0, 0, '', $pre );

		$detail_subpro->set('list_images_link_slide',$list_images_link_slide);
		
		
	
		// get hotline
		$hotline = $common->gethonhop(1);
		$infocm = $common->getInfo("company","ID_COM = 1 ");
		$detail_subpro->set('hotline',$hotline['SCONTENTSHORT']);
		$detail_subpro->set('mailcom',$infocm['EMAIL']);
		
		$name_link_pro = $print->getlangvalue($info_pro,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
		$url_like = "spchitiet-".$idp."-".$name_link_pro.".html";
		$detail_subpro->set('url_like',$url_like);
		
		//$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
		//$tpll = "list_menur_pro";
		//$tpl_menur_pro= new Template($tpll);
		//$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
		//$detail_subpro->set('list_menu_sp',$tpl_menur_pro);
		
		//List sản phẩm liên quan
		$listsp_splq    = $print->listsp_lq($pre,$idp);
		$detail_subpro->set('listsp_splq',$listsp_splq);
		
		
		$temp_contents->set('detail_subpro', $detail_subpro->fetch($templ));									
		
	// +++++++++++++++++++++++++++++++++++ end detai subpro +++++++++++++++
		
		$temp_contents->set('image_display',$info_pro['IMAGE_LARGE']);
		$temp_contents->set('image_big',$info_pro['IMAGE_BIG']);
		$temp_contents->set('file_catalog',$info_pro['CATALOG']);
		$idcom = $info_pro['ID_COM'];
		// set change size image
		$origfolder = $info_pro['ID_CATEGORY'];
		$width = $itech->vars['width_detail'];
		$height = $itech->vars['height_detail'];
		$wh = $print_2->changesizeimage($info_pro['IMAGE_LARGE'], $origfolder, $width, $height);
		$temp_contents->set( "w", $wh[0]);
		$temp_contents->set( "h", $wh[1]);
		
		$temp_contents->set('idcom',$idcom);
		$temp_contents->set('idp',$idp);
		$temp_contents->set('idc',$idc);
		if($info_pro['INFO_SHORT'.$pre]=="")
		$content_short = $info_pro['INFO_SHORT'];
		else
		$content_short = $info_pro['INFO_SHORT'.$pre];
		$temp_contents->set('content_short', $content_short);
		
		if($info_pro['INFO_DETAIL'.$pre]=="")
		$content_decode = base64_decode($info_pro['INFO_DETAIL']);
		else
		$content_decode = base64_decode($info_pro['INFO_DETAIL'.$pre]);
		$temp_contents->set('info_detail', $content_decode);
		//$temp_contents->set('info_detail', $info_pro['INFO_DETAIL']);
		
		//set for url like icon					
		//$name_link_pro = $print_2->vn_str_filter($info_pro['PRODUCT_NAME']);
		$name_link_pro = $print->getlangvalue($info_pro,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
		$url_like = "spchitiet-".$idp."-".$name_link_pro.".html";
		$temp_contents->set('url_like',$url_like);
		/*
		// getlist images link
		$sql_link = "SELECT * FROM images_link WHERE ID_PRODUCT = '".$idp."' ORDER BY IORDER";			   			    
		$query_link = $DB->query($sql_link);					
		$list_images_link = "";
		while($rs=$DB->fetch_row($query_link))
		{
		$list_images_link .= '<a href="javascript:void(0);" class="linkopacity" onmouseover="document.getElementById(\'change_img\').src=\'images/bin/'.$idc.'/'.$rs['NAME_IMG'].'\';" ><img class="border_line" src="images/bin/'.$idc.'/'.$rs['NAME_IMG'].'" width="80" height="80"  /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';					
		}
		*/
		
		// getlist images link
		
		// get menu selected
		
		// kiem tra xem co danh sach hinh kem theo khong
		$checkid = $common->getListValue("images_link",$condit_link,'ID_IMG');
		$temp_contents->set('list_images_link',$list_images_link);
		$temp_contents->set('checkid',$checkid);
		
		$temp_contents->set('hotline',$hotline['SCONTENTSHORT']);
		$temp_contents->set('mailcom',$infocm['EMAIL']);
		
		//AND ID_TYPE = '".$idt."' 
		// get list products relevant
		$conditpro = "ID_CATEGORY = '".$idc."' AND ID_PRODUCT <> '".$idp."' AND STATUS = 'Active'";//
		// get menu selected
		$idmnselected = $name_query_string;
		$field_name_other = array('CATALOG','IMAGE_LARGE','IMAGE_BIG','PRICE','EXPIRE', 'ID_CATEGORY', 'ID_TYPE','ID_PRO_CAT', 'PRICE_SALE', 'SALE', 'INFO_SHORT', 'CODE', 'COUNTVIEW');
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type']=="phone"){						
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY DATE_UPDATE DESC", "m_list_2all_images_procat", 2, 0, 6, $page, $idmnselected, $pre );					             								 
		$tpl_relv = "m_main_list_pro_relevant";
		}
		else {
		$list_images = $print_2->list_row_column_multifield("products", $conditpro, "ID_PRODUCT", "PRODUCT_NAME", $field_name_other, " ORDER BY DATE_UPDATE DESC", "list_7all_images_procat", 7, 0, 14, $page, $idmnselected, $pre );					             								 
		$tpl_relv = "main_list_pro_relevant";
		}
		
		$tpl_pro = new Template($tpl_relv);
		$tpl_pro->set('list_all_images_procat', $list_images);
		
		// paging
		//$total_get = $common->CountRecord("products",$conditpro,"ID_PRODUCT");
		//$class_link = "do";
		//if ($num_record_on_page < $total_get){			
		//	$tpl_pro->set('paging',$print_2->pagingphp( $total_get, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
		//}	
		//else
		//	$tpl_pro->set('paging' , '');			
		
		$pro_relevant = $tpl_pro->fetch($tpl_relv);
		$main_out->set('pro_relevant',$pro_relevant);
	
	}
	//List slide quảng cáo top right 1
	$list_qc_top_right1 = $print->get_data('common',26,117,$pre,0,'list_qc_top_right1','list_qc_top_right1_rs',0,3);
	$main_out->set('list_qc_top_right1',$list_qc_top_right1);
	//List slide quảng cáo top right 2
	$list_qc_top_right2 = $print->get_data('common',26,118,$pre,0,'list_qc_top_right2','list_qc_top_right2_rs',0,3);
	$main_out->set('list_qc_top_right2',$list_qc_top_right2);
	// List danh sách sản phẩm mới nhất
	$list_sp_new_right = $print->get_sp_home($pre,'list_sanpham_detail_right','list_sanpham_detail_right_rs',0,6);// Sản phẩm mới nhất
	$main_out->set('list_sp_new_right',$list_sp_new_right);
	
	//Get name sub categories
	$main_out->set('nametype',$name_type);	
	$main_out->set('title_cat', $title_cat);
	$main_out->set('main_contents', $temp_contents);
	$main_out->set('pro_name', $info_pro['PRODUCT_NAME'.$pre]);
	$main_out->set('idc',$idc);
	
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type']=="phone"){
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "m_menupro_cat_rs",  $field_name_other2, "m_menupro_catsub_rs", $field_name_other3, "m_menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "m_list_menur_pro_detail";
	} else {
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "list_menur_pro";
	}
	$tpl_menur_pro= new Template($tpll);
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$temp_contents->set('list_menur_pro', $tpl_menur_pro);
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_out->set('logo_partner', $logo_partner);

	
	// get statistic
	$main_out->set('statistics', $print->statistics());
	$main_out->set('todayaccess', $print->get_today( ));
	$main_out->set('counter', $print->get_counter());
	
	$main->set('main2', $main_out); 	 

	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	
	$footer =  $common->getFooter('footer');	
	
	// get cong ty footer
	
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