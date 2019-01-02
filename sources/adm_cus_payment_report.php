<?php

     
     if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	// set language
	$pre = "";
	// get sub mennu
	$submenu = & new Template('submenu_company'); 
	$submenu->set('sub','');
     //main
     $main = & new Template('cus_payment_list_report'); 
		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(6), "V")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
			
			//check role type
		$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
		$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
		$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
		$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
		//check consultant incharge for company		
			//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
			// check permission com incharge owner
			if($roleinfo['ROLE_TYPE'] != "Operation"){
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);				
				exit;
			}

		// set for customer
		$page = $itech->input['page'];
		$idc = $itech->input['idc'];
		$datefrom = $common->dateen($itech->input['datefrom']);
		$dateto = $common->dateen($itech->input['dateto']);
		
		
		
		$cus_info = $common->getInfo("consultant","ID_CONSULTANT = '".$idc."'");
		$rolecus_info = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$idc."'");
		$idrole = $rolecus_info['ROLE_ID'];		
		//check role type		
		$roleinfocus = $common->getInfo("role_lookup","ROLE_ID ='".$rolecus_info['ROLE_ID']."'");
		$type_trigia = "";
		if($roleinfocus['ROLE_ID'] == 3) $type_trigia = "ck";// chiet khau
		elseif($roleinfocus['ROLE_ID'] == 4) $type_trigia = "kg";// ky gui
		elseif($roleinfocus['ROLE_ID'] == 5) $type_trigia = "vc";// van chuyen
		
		$pagetitle = $itech->vars['site_name'];
		$main->set("title", $pagetitle);
		// set header for operation
		if($roleinfo['ROLE_TYPE'] == "Operation"){
		$_SESSION['tab'] = 2;// menu Company selected
		$header = $common->getHeader('adm_header');
		$submenu2 = & new Template('submenu2_customer'); 
		for($i=3;$i<=5;$i++){
				if($idrole == $i) {
				$submenu2->set('icon_selected'.$i,'icon_arr2');
				$submenu2->set('cl'.$i,'title_do');
				}
				else {
				$submenu2->set('icon_selected'.$i,'icon_arr1');
				$submenu2->set('cl'.$i,'title_xam');
				}
			}
		$main->set('submenu2',$submenu2);			
		}
		else{
		$_SESSION['tab'] = "";
		$header = $common->getHeader('header');			
		$main->set('submenu2','');
		}
			

		// setup for paging						
			
			if ($page == 0 || $page == "")
				$page=1;

			$num_record_on_page = $itech->vars['num_record_on_page_admin'];
			$maxpage = 10; // group 10 link on paging list
			
			$pageoffset =($page-1)* $num_record_on_page;
		
			$field_name_other = array('CODE','ID_CONSULTANT','TOTAL_PAY', 'IP', 'ID_PROPAY', 'CONTENT_MAIL', 'CREATED_DATE', 'APPROVE', 'UPDATED_DATE','TOTAL_PAY_DISC','TOTAL_PAY_FINAL','FEE_TRANSFER');			
			$fet_box = & new Template('cus_paymentlist_report_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$sum_money_banhang = 0;
			$sum_money_chietkhau = 0;
			$sum_money_thanhtoan = 0;
			$sum_money_vanchuyen = 0;
			$sum_money_hoahong = 0;
			$sum_money_vanchuyen_dl = "";// check for unknow
			$sum_vanchuyen_dl = 0;// check for number
			
			if($itech->input['datefrom'] != "" && $itech->input['dateto'] != "")
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = '".$idc."' AND ( CREATED_DATE >= '".$datefrom."' AND CREATED_DATE <= '".$dateto."' )";
			elseif($itech->input['datefrom'] != "" && $itech->input['dateto'] == "")
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = '".$idc."' AND ( CREATED_DATE >= '".$datefrom."' AND CREATED_DATE <= CURDATE() )";
			elseif($itech->input['datefrom'] == "" && $itech->input['dateto'] != "")
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = '".$idc."' AND ( CREATED_DATE <= '".$dateto."' )";
			else
			$condit = " STATUS <> 'Deleted' AND ID_CONSULTANT = '".$idc."'";
			//echo $condit;
			$sql="SELECT * FROM payments  WHERE ".$condit." ORDER BY UPDATED_DATE DESC LIMIT ".$pageoffset.",".$num_record_on_page;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
				$fet_box->set( "paymentname", $rs['CODE']);
					$fet_box->set( "ID_PAYMENT", $rs['ID_PAYMENT']);
					
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
					
					$fet_box->set( "CREATED_DATE", $common->datetimevn($rs['CREATED_DATE']));
					$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = ".$rs['ID_CONSULTANT']);
					$fet_box->set("FULL_NAME", $consultantinfo['FULL_NAME']);
					$fet_box->set("EMAIL", $consultantinfo['EMAIL']);
					$hoahong = $rs['TOTAL_PAY'] - $rs['TOTAL_PAY_DISC'];
					$fet_box->set("hoahong", $hoahong);
					
					// get total money
					$sum_money_banhang += $rs['TOTAL_PAY'];
					$sum_money_chietkhau += $rs['TOTAL_PAY_DISC'];// tinh sau cho khach hang dai ly, 30% (tam tinh)
					$sum_money_thanhtoan += $rs['TOTAL_PAY_FINAL'];
					$sum_money_vanchuyen += $rs['FEE_TRANSFER'];
					$sum_money_hoahong += ($rs['TOTAL_PAY'] - $rs['TOTAL_PAY_DISC']);// tinh sau cho khach hang dai ly
					
					// get total money for reseller
						// process fee transfer
					$fee_transfer = 0;
					$fee_transfer_dl = "";							
								
						if($roleinfocus['ROLE_TYPE'] != "Operation"){							
							// check total money order to get fee transfer (tong gia tri don hang tinh theo gia goc)
							// get id money transfer
							$condit_mn = " ((FROM_MONEY > ".$rs['TOTAL_PAY']." AND TO_MONEY = 0 ) OR ( FROM_MONEY <= ".$rs['TOTAL_PAY']." AND TO_MONEY >= ".$rs['TOTAL_PAY']." ) OR (TO_MONEY < ".$rs['TOTAL_PAY']." AND  FROM_MONEY = 0 ))";
							$condit_mn .= " AND TYPE_TRIGIA = '".$type_trigia."' ";
							
							//echo $condit_mn."<br>";

							$idmn_info = $common->getInfo("money_transfer_lookup",$condit_mn);
							$id_money_trans = $idmn_info['ID_MONEY_TRANS'];
							
							//echo " TYPE_ACCOUNT = '".$roleinfocus['ROLE_ID']."' AND ID_PROVINCE ='".$cus_info['ID_PROVINCE']."' AND ID_DIST ='".$cus_info['ID_DIST']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".$rs['ID_TRANSFER']."'<br>";
							
							// get fee follow province + id_transfer + type_account
							$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = '".$roleinfocus['ROLE_ID']."' AND ID_PROVINCE ='".$cus_info['ID_PROVINCE']."' AND ID_DIST ='".$cus_info['ID_DIST']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".$rs['ID_TRANSFER']."'");
							// user normal and user reseller the same (recaculator in admin for reseller)
							//$fee_info = $common->getInfo("discount_transfer_lookup"," TYPE_ACCOUNT = 5 AND ID_PROVINCE ='".@$_SESSION['fprovince_get']."' AND ID_DIST ='".@$_SESSION['fdist_get']."' AND  ID_MONEY_TRANS ='".$id_money_trans."' AND ID_TRANSFER = '".@$_SESSION['transfer_get']."'");
							if($fee_info == ""){							
								$fee_transfer_dl =	100000001;
								$fee = 100000001;
								$sale = 100000001;
								//echo "<br> fee_transfer=".$fee_transfer;
							}
							else{
								//echo "<br> da vao day =".$fee_transfer;
								// check transfer ATM direct
								if($rs['ID_PROPAY'] == 1){
									$fee_transfer = $fee_info['FEE'] - ($fee_info['FEE']*$fee_info['PERCENT']/100);
									$fee = $fee_info['FEE'];
									$sale = $fee_info['PERCENT'];
									//echo "<br> - fee2=".$fee_info['FEE'];
								}
								else {
									$fee_transfer = $fee_info['FEE'];
									$fee = $fee_info['FEE'];
									$sale = 0;
									
								}
							}
							
						}
						
						if($fee_transfer_dl == 100000001) {
						$sum_money_vanchuyen_dl = 100000001;
						//echo "<br> - Vao cho nay =".$sum_money_vanchuyen_dl;
						}
						else {
						$sum_vanchuyen_dl += $fee_transfer;
						//echo "<br> - mn2=".$sum_vanchuyen_dl;
						}
					
					// Get info datasub
					$datasub .= $fet_box->fetch("cus_paymentlist_report_rs");
	
				}
				
							
				if($datasub != "")
				$main->set('cus_paymentlist_report_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('cus_paymentlist_report_rs', $datasub);
				}
				
				
				// Paging
					$totalpro = $common->countRecord("payments",$condit,"ID_PAYMENT");
					
					$link_page = "index.php?act=adm_payments";
					$class_link = "do";
					if ($num_record_on_page < $totalpro){						
						$main->set('paging',$print_2->pagingphp( $totalpro, $num_record_on_page, $maxpage, $link_page, $page, $class_link ));
					}
					else
						$main->set('paging' , '');
									
			$main->set('page', $page);
			$main->set("EMAIL", $cus_info['EMAIL']);
			$main->set("fullname", $cus_info['FULL_NAME']);
			$main->set('idc', $idc);
			// set for normal customer
			$main->set('sum_money_banhang', $sum_money_banhang);
			$main->set('sum_money_chietkhau', $sum_money_chietkhau);
			$main->set('sum_money_thanhtoan', $sum_money_thanhtoan);
			$main->set('sum_money_vanchuyen', $sum_money_vanchuyen);
			$main->set('sum_money_hoahong', $sum_money_hoahong);
						
//++++++++++++++++++++ set for reseller customer tam tinh chiet khau 30% ky gui 20%			
			$sum_money_banhang_dl = $sum_money_banhang;
			if($roleinfocus['ROLE_ID'] == 3)
			$sum_money_chietkhau_dl = $sum_money_banhang_dl - $sum_money_banhang_dl*$itech->vars['discount_chietkhau_default']/100;// tam tinh chiet khau 30%
			elseif($roleinfocus['ROLE_ID'] == 4)
			$sum_money_chietkhau_dl = $sum_money_banhang_dl - $sum_money_banhang_dl*$itech->vars['discount_kygui_default']/100;// tam tinh chiet khau 20%
			else $sum_money_chietkhau_dl = 100000001;
			//+++++++++ chu y: khong so sanh so nguyen voi chuoi. vd: 0=="unknow" se tra ve true
			if($sum_money_vanchuyen_dl == 100000001){			
			$sum_money_thanhtoan_dl = 100000001;
			//echo "-aa1 vao day";			
			}
			else {			
			$sum_money_thanhtoan_dl = $sum_money_chietkhau_dl + $sum_vanchuyen_dl;// tong tien da chiet khau + tien van chuyen cho dai ly
			//echo "-aa2=".$sum_money_vanchuyen_dl;
			$sum_money_vanchuyen_dl = "ok";
			}
			
			
			$sum_money_hoahong_dl = $sum_money_banhang_dl - $sum_money_chietkhau_dl;
			
			$main->set('sum_money_banhang_dl', $sum_money_banhang_dl);
			$main->set('sum_money_chietkhau_dl', $sum_money_chietkhau_dl);
			$main->set('sum_money_thanhtoan_dl', $sum_money_thanhtoan_dl);
			$main->set('sum_money_vanchuyen_dl', $sum_money_vanchuyen_dl);
			$main->set('sum_vanchuyen_dl', $sum_vanchuyen_dl);
			$main->set('sum_money_hoahong_dl', $sum_money_hoahong_dl);
			
//+++++++++++++++ apply for policy reseller (real)
			$sum_money_banhang_dl_real = $sum_money_banhang;
			$condit_mn = " ((FROM_MONEY > ".$sum_money_banhang_dl_real." AND TO_MONEY = 0 ) OR ( FROM_MONEY <= ".$sum_money_banhang_dl_real." AND TO_MONEY >= ".$sum_money_banhang_dl_real." ) OR (TO_MONEY < ".$sum_money_banhang_dl_real." AND  FROM_MONEY = 0 ))";
			$condit_mn .= " AND TYPE_TRIGIA = '".$type_trigia."' ";			
			//echo $condit_mn."<br>";

			$idmn_info = $common->getInfo("money_transfer_lookup",$condit_mn);
			$id_money_trans = $idmn_info['ID_MONEY_TRANS'];						
			
			// get fee follow province + id_transfer + type_account
			$discount_info = $common->getInfo("discount_reseller_lookup"," TYPE_ACCOUNT = '".$roleinfocus['ROLE_ID']."' AND  ID_MONEY_TRANS ='".$id_money_trans."'");
			if($discount_info == "") {
			$discount_dl_str = 100000001;
			$sum_money_chietkhau_dl_real_str = 100000001;
			$sum_money_hoahong_dl_real	= 100000001;	
			}
			else {
			$discount_dl = $discount_info['PERCENT'];
			$sum_money_chietkhau_dl_real = $sum_money_banhang_dl_real - $sum_money_banhang_dl_real*$discount_dl/100;
			$sum_money_hoahong_dl_real = $sum_money_banhang_dl_real - $sum_money_chietkhau_dl_real;
			}
			//+++++++++ chu y: khong so sanh so nguyen voi chuoi. vd: 0=="unknow" se tra ve true
			if($sum_money_vanchuyen_dl == 100000001 || $sum_money_chietkhau_dl_real_str == 100000001){			
			$sum_money_thanhtoan_dl_real = 100000001;			
			}
			else {			
			$sum_money_thanhtoan_dl_real = $sum_money_chietkhau_dl_real + $sum_vanchuyen_dl;// tong tien da chiet khau + tien van chuyen cho dai ly
			//echo "-aa2=".$sum_money_vanchuyen_dl;
			$sum_money_vanchuyen_dl = "ok";
			$sum_money_chietkhau_dl_real_str = "ok";
			}

			$main->set('sum_money_banhang_dl_real', $sum_money_banhang_dl_real);
			$main->set('sum_money_chietkhau_dl_real', $sum_money_chietkhau_dl_real);
			$main->set('sum_money_chietkhau_dl_real_str', $sum_money_chietkhau_dl_real_str);
			$main->set('sum_money_thanhtoan_dl_real', $sum_money_thanhtoan_dl_real);
			$main->set('sum_money_vanchuyen_dl', $sum_money_vanchuyen_dl);
			$main->set('sum_vanchuyen_dl', $sum_vanchuyen_dl);
			$main->set('sum_money_hoahong_dl_real', $sum_money_hoahong_dl_real);
			// set display type reseller
			$main->set('idrole', $roleinfocus['ROLE_ID']);
			
	
     //footer
     $footer = & new Template('adm_footer');
	 $footer->set('statistics1', '');

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
	 $tpl->set('submenu', $submenu);
     $tpl->set('main', $main);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('adm_home');
?>
