<?php

// kiem tra logged in
	 if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=login';
		$common->redirect_url($url_redidrect);
	}
	
	$idtype = $itech->input['idtype'];
	//$idtype = 6;// default menu selected account
	$idcat = $itech->input['idcat'];
	//$idcat = 22;// default view_account
	$idcatsub = $itech->input['idcatsub'];	
/*	
	// redirect to url for account references
	switch($idcat){
		case 23: $common->redirect_url('doi-mat-khau.html');
		break;
		case 24: $common->redirect_url('quanlydonhang.html');	
		break;	
	}
*/	

	//header
	//$pre = '';// get language
	$_SESSION['tab'] = 1;// Account
	$name_title = $lang['tl_atkhoan'];
	
	//header
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	$header = $common->getHeader('m_header');
	else
	$header = $common->getHeader('header');

	//main
	$main =  new Template('main');      

	$tempres= new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 

	// Get data main 2
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone"){
	$tplmain = "m_main_viewcart";
	$tplct = "m_manage_order";
	}
	else {
	$tplmain = "main_viewcart";
	$tplct = "manage_order";
	}
	
	$temp_contents=& new Template($tplmain);
	
	
	// get main content 
	$tpl1 =& new Template($tplct);			
	
	// setup for paging			
			$page = $itech->input['page'];
			$idtype = $itech->input['idtype'];
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('CODE','ID_CONSULTANT','TOTAL_PAY', 'IP', 'ID_PROPAY', 'CONTENT_MAIL', 'TOTAL_PAY_DISC', 'FEE_TRANSFER');			
			//+++++++++++++++++++ check mobile +++++++++++++++++++++	
			if($_SESSION['dv_Type']=="phone") $tplvod = "m_manage_order_rs";
			else $tplvod = "manage_order_rs";
	
			$fet_box = & new Template($tplvod);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt = 0;
			
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = ".$_SESSION['ID_CONSULTANT'];
			$sql="SELECT * FROM payments  WHERE ".$condit." ORDER BY UPDATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql);
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;
					$fet_box->set( "paymentname", $rs['CODE']);
					$fet_box->set( "ID_PAYMENT", $rs['ID_PAYMENT']);
					$fet_box->set( "stt", $stt);
					$fet_box->set( "CREATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					$fet_box->set( "TOTAL_PAY_FINAL",number_format($rs['TOTAL_PAY_FINAL'],0,"","."));
					
					// set neu co phan trang
					if ($page) $fet_box->set( "page", "&page=".$page);
					else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
					
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}
					
					$fet_box->set( "APPROVE", $rs['APPROVE']);
										
					// Get info datasub
					$datasub .= $fet_box->fetch($tplvod);
	
				}
							
				if($datasub != "")
				$tpl1->set('paymentlist_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = $lang['tl_anodonhang'];
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$tpl1->set('paymentlist_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("payments",$condit,"ID_PAYMENT");
					
					$link_page = "donhang-".$idtype."-quan-ly-don-hang.html?idcat=".$idcat;
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$tpl1->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$tpl1->set('paging' , '');
									
			$tpl1->set('page', $page);

	$main_contents = $tpl1->fetch($tplct);
		
	$temp_contents->set('main_contents', $main_contents);
	
	//*************** Chu y: Dong lenh get menu list nay phai dat duoi cac lenh tren de lay duoc $idt, $idprocat highlight menu chon *************
	// get menu list
	$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
	$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
	$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
	//+++++++++++++++++++ check mobile +++++++++++++++++++++
	if($_SESSION['dv_Type']=="phone"){
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "m_menupro_cat_rs",  $field_name_other2, "m_menupro_catsub_rs", $field_name_other3, "m_menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "m_list_menur_pro";
	} else {
	$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
	$tpll = "list_menur_pro";
	}
	
	$tpl_menur_pro= new Template($tpll);
	$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	$temp_contents->set('list_menur_pro', $tpl_menur_pro);
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$temp_contents->set('logo_partner', $logo_partner);
	
	$temp_contents->set('name_title', $name_title);
		

	$main->set('main2', $temp_contents); 


	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	
	$footer =  $common->getFooter('footer');	
	

	//all
	$tpl =  new Template();
	$tpl->set('header', $header);
	//$tpl->set('left', $left);
	$tpl->set('main', $main);
	//$tpl->set('maintab', $maintab);
	//$tpl->set('right', $right);
	$tpl->set('footer', $footer);

	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	if($_SESSION['dv_Type']=="phone")
	echo $tpl->fetch('m_home');
	else
	echo $tpl->fetch('home');
?>