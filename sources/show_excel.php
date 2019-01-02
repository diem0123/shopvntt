<?php
	//$icat = $itech->input['icat'];
	$header = $common->getHeader('header');
	
	//main
	$main = & new Template('main');      
	$idcat = $itech->input['idcat'];
	$idtype = $itech->input['idc'];
	$idprocat = $itech->input['idprocat'];
	$ida = $itech->input['ida'];
	$idsk = $itech->input['idsk'];
	$tempres=& new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 
	$pre = $_SESSION['pre'];
	// Get data main 2
		  
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
    $tplmain = "main_baogia";	
	$temp_contents=& new Template($tplmain);
	//++++++++++++++++++++++++++++++++++++++++++++++
	$title_cat = "";
		$tpl_title =& new Template('title_subcat');
		
		$name_titlecat = $common->getInfo("categories","ID_CATEGORY = '".$idcat."'");
		$name_titlesubcat = $common->getInfo("categories_sub","ID_TYPE = '".$idtype."'");
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
		$lksubcat = '<a href="sanpham-'.$idc.'-'.$name_linksubcat.'.html?idt='.$idt.'" >'.$name_titlesubcat['NAME_TYPE'.$pre].'</a>';
		$lksubprocat = '<a href="sanpham-'.$idc.'-'.$name_linksubprocat.'.html?idt='.$idt.'&idprocat='.$idprocat.'" >'.$name_titlesubprocat['NAME_PRO_CAT'.$pre].'</a>';
		$linkT = '<a href="sanpham.html" >'.$lang['tl_sanpham'].'</a>';
		//$linkT = '';
		
		//if($idc) $linkT = "";		
		
		if(!$idcat && !$idtype && !$idprocat && !$idsk)
		$tpl_title->set('namecat', $linkT);	
		elseif($idcat && !$idtype && !$idprocat && !$idsk)
		$tpl_title->set('namecat', $linkT." <span>></span> ".$lkcat);		
		elseif($idcat && $idtype && !$idprocat && !$idsk)
		$tpl_title->set('namecat', $linkT." <span>></span> ".$lkcat." <span>></span> ".$lksubcat);
		elseif($idcat && $idtype && $idprocat && !$idsk)
		$tpl_title->set('namecat', $linkT." <span>></span> ".$lkcat." <span>></span> ".$lksubcat." <span>></span> ".$lksubprocat);
		elseif($idsk)
		$tpl_title->set('namecat', $linkT." <span>></span> ".$lkcat." <span>></span> ".$lkidsk);
		
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
		
		$tpl_title->set('idcat', $idcat);
		// xu ly title trong truong hop tim kiem va binh thuong
		if($idcat){
		$title_cat = $tpl_title->fetch('title_subcat');		
		}
		else{			
			//if ($keyword != "" && $keyword != "T&igrave;m ki&#7871;m" )
			$tpl_title->set('namecat', '<a href="sanpham.html" >'.$lang['tl_sanpham'].'</a>');
			//$tpl_title->set('namecat', $linkT);			
			//$tpl_title->set('title_sp', "title");
			$title_cat = $tpl_title->fetch('title_subcat');			
		}
	
	
	
	
	//++++++++++++++++++++++++++++++++++++++++++++++
	
	
	
	
	
	$temp_contents->set('titlecat',$title_cat);
	$baogia=$print->get_list_bao_gia($pre,$idcat,$idtype,0);
	$temp_contents->set('baogia', $baogia);
	//$temp_contents->set('main_contents', $main_contents);
	//echo 'dadasda';
	$main->set('main2', $temp_contents); 	
	$footer = & new Template('footer');
	
	//$footer->set('totaltime', $Debug->endTimer());
	$footer->set('counter', $print->get_counter());
	$footer->set('statistics', $print->statistics());
	$footer->set('todayaccess', $print->get_today());
	$tpl = & new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('footer', $footer);
	echo $tpl->fetch('home');


?>