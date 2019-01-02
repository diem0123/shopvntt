<?php

// kiem tra logged in
// chua kiem tra, bo sung sau

	$idp = $itech->input['idp'];
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
	
	// get language
	$pre = $_SESSION['pre'];
    
	//header
	$_SESSION['tab'] = 1; // set menu active: products
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	
	$header = $common->getHeader('header');
	
	$lkcat = '<li><a href="thong-tin-gio-hang.html" >'.$lang['tl_infocart'].'</a></li>';
	$lkcat = $lkcat;
	// get tite category
	$title_cat = "";
	$tpl_title =new Template('title_subcat_info');
	$tpl_title->set('namecat', $lkcat);			
	$tpl_title->set('title_sp', "title_breadcrumb");	
	$title_cat = $tpl_title->fetch('title_subcat_info');

	//main
	$main = new Template('main');      

	$tempres=new Template('main1');	  
	$tempres->set('quote', ""); 
	$main->set('main1', ""); 
	
	$tplmain = "main_viewcart";
	$tplct = "viewcart";
	
	// Get data main 2
	$main_products=new Template($tplmain);	  
	// get content view cart
	$temp_contents=new Template($tplct);		
	
	// get list products in shopping cart
			if(!isset($_SESSION['shoppingcart']) || $_SESSION['shoppingcart'] == "") $list_idp = "''";
			else $list_idp = $_SESSION['shoppingcart'];
				// split group for company			
				$condit = " p.STATUS = 'Active' AND p.ID_PRODUCT IN (".$list_idp.") ";
				$sql="SELECT * FROM products p  WHERE ".$condit." ORDER BY ID_PRODUCT DESC ";    
				$list_pro = $DB->query($sql);
					
				$i = 0;
				$list_idcom = array();
				$binshop_quantity = array();
				$binshop = array();								
				while ($rs=$DB->fetch_row($list_pro))
				{
				  // Get list idcom
				  if($i == 0) $list_idcom[] = $rs['ID_COM'];
				  elseif(!in_array($rs['ID_COM'], $list_idcom)) $list_idcom[] = $rs['ID_COM'];
				  $i++;
				}											
				  // Loop idcom get list idp group for every company
				  $datamain = "";				  
				  if(!empty($list_idcom)){
					for($k=0;$k<count($list_idcom);$k++){
						$condit1 = " p.STATUS = 'Active' AND p.ID_PRODUCT IN (".$list_idp.") AND ID_COM = '".$list_idcom[$k]."'";									
						$sql1="SELECT * FROM products p  WHERE ".$condit1." ORDER BY ID_PRODUCT DESC ";    
						$list_pro1 = $DB->query($sql1);
			
						$field_name_other = array('ID_PRO_CAT_COM','PRICE','THUMBNAIL','IMAGE_LARGE','DATE_UPDATE', 'ID_PRO_CAT', 'CODE','ID_CATEGORY','ID_TYPE','ID_AGES','ID_SKILL');						
				//+++++++++++++++++++ check mobile +++++++++++++++++++++	
				if($_SESSION['dv_Type']=="phone" || $_SESSION['dv_Type_giohang']=="phone") $temboxrs = "m_viewcart_list_rs";
				else $temboxrs = "viewcart_list_rs";
						$fet_box = new Template($temboxrs);
						$datasub = ""; // de lay du lieu noi cac dong
						$stt = 0;
						$totalpay = 0;
						$totalpay_disc = 0;
						
						while ($rs1=$DB->fetch_row($list_pro1))
						{
							$stt++;
							$fet_box->set( "productname", $rs1['PRODUCT_NAME'.$pre]);
							$name_link = $print_2->vn_str_filter($rs1['PRODUCT_NAME'.$pre]);
							$fet_box->set( "name_link", $name_link);
							$name_query_string = "?idc=".$rs1['ID_CATEGORY']."&idt=".$rs1['ID_TYPE']."&idprocat=".$rs1['ID_PRO_CAT'];
							$fet_box->set( "menu_select", $name_query_string);
							$fet_box->set( "idcom", $rs1['ID_COM']);
							$fet_box->set( "idp", $rs1['ID_PRODUCT']);
							$fet_box->set( "stt", $stt);
							$fet_box->set( "price", number_format($rs1['PRICE'],0,"","."));
							// process value quantity in session							
							$binshop = explode(",",$_SESSION['shoppingcart']);
							$binshop_quantity = explode(",",$_SESSION['shoppingcart_quantity']);							
							$keyvl = array_search($rs1['ID_PRODUCT'], $binshop);							
							if(array_key_exists($keyvl, $binshop_quantity))
							$quantityget = $binshop_quantity[$keyvl];
							else $quantityget = 1;
													
							// get update quantity and total money
							$quantity_new = $itech->input['quantity_'.$rs1['ID_PRODUCT']];	
							if($quantity_new=="") $quantity = $quantityget;// get old value
							else{
							$quantity = $quantity_new;
							// use session keep value quantity, update quantity in session shoppingcart_quantity
							$binshop_quantity[$keyvl] = $quantity_new;
							// convert array to string
							$stringtemp = implode (',',$binshop_quantity);
							// update session
							$_SESSION['shoppingcart_quantity'] = $stringtemp;
							}
							//$quantity=($quantity_new=="")?$quantityget:$quantity_new;
							$quantity = (int)$quantity;
							$fet_box->set( "quantity", $quantity);
							$totalmoney = $rs1['PRICE']*$quantity;
							// get price has been discount						
							$totalmoney_disc = ($rs1['PRICE']-$rs1['PRICE']*$rs1['SALE']/100)*$quantity;
							$fet_box->set( "totalmoney", number_format($totalmoney,0,"","."));
							$fet_box->set( "totalmoney_disc", number_format($totalmoney_disc,0,"","."));
							$fet_box->set("sale", $rs1['SALE']);
							//$fet_box->set("base_url", $this->base_url);
							$postby = $rs1['POST_BY'];
							// set for field name other chid
							if(count($field_name_other) > 0){
								for($i=0;$i<count($field_name_other);$i++){
									if(isset($rs1[$field_name_other[$i].$pre]))
									$fet_box->set($field_name_other[$i], $rs1[$field_name_other[$i].$pre]);
								}
							}
							// Get info datasub
							$datasub .= $fet_box->fetch($temboxrs);
							//***** important: only for 1 company, if multiple company then totalpay = all company or should remove it
							$totalpay += $totalmoney;
							$totalpay_disc += $totalmoney_disc;
														
						}
							// get info company and user incharge group
							$cominfo = $common->getInfo("company","ID_COM = '".$list_idcom[$k]."'");
							$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = '".$postby."'");
														
				//+++++++++++++++++++ check mobile +++++++++++++++++++++	
				if($_SESSION['dv_Type']=="phone" || $_SESSION['dv_Type_giohang']=="phone") $tembox = "m_viewcart_list";
				else $tembox = "viewcart_list";
							$tpl_temp   = new Template($tembox);														
							$tpl_temp->set("comname", $cominfo['COM_NAME']);
							$tpl_temp->set("add", $consultantinfo['ADDRESS']);
							$tpl_temp->set("salename", $consultantinfo['FULL_NAME']);
							$tpl_temp->set("tel", $consultantinfo['TEL']);
							$tpl_temp->set("email", $consultantinfo['EMAIL']);
							$tpl_temp->set("idcom", $list_idcom[$k]);
							$tpl_temp->set("list_pro_com", $datasub);
							$tpl_temp->set("totalpay", number_format($totalpay,0,"","."));
							$tpl_temp->set("totalpay_disc", number_format($totalpay_disc,0,"","."));

						// fet concat template
						$datamain .= $tpl_temp->fetch($tembox);
					}
				  }			  			 
												
				if($datamain != "")				
				$temp_contents->set('viewcart_list_rs', $datamain);
				else{
				$tpl_sub = new Template('noproduct');				
				$text = $lang['tl_giohangrong'];
				$tpl_sub->set("text", $text);
				$datamain .= $tpl_sub->fetch("noproduct");
				$temp_contents->set('viewcart_list_rs', $datamain);
				}	
	
/*	
	// set intro on top
	// get intro top main content
	$tpl_intro =new Template('intro_viewcart');
	$idps = $_SESSION['tab'];
	$info_bn = $common->getInfo("banner","ID_PS = '".$idps."'");
	$tpl_intro->set('imgname', $info_bn['NAME_IMG']);
	$tpl_intro->set('info', $info_bn['INFO']);
	$intro = $tpl_intro->fetch('intro_viewcart');
	
	$main_products->set('intro', $intro);
*/	
	
	$main_products->set('main_contents', $temp_contents);
	
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
	
	//$tpl_menur_pro= new Template($tpll);
	//$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
	
	//$main_products->set('list_menur_pro', $tpl_menur_pro);
	
	$main_products->set('list_cat_menu', $list_cat_menu);
	$main_products->set('title_cat', $title_cat);
	
	
	// get logo partner
	//$logo_partner = $print->getlogo_partner($pre);
	//$main_products->set('logo_partner', $logo_partner);
	
	// Get main 2
	$main->set('main2', $main_products); 	 

	//footer
	//footer
	//+++++++++++++++++++ check mobile +++++++++++++++++++++	

	$footer = $common->getFooter('footer');	
	
	//all
	$tpl = new Template();
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