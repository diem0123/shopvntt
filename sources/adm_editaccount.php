<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}
	
	$rolemn = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
	$roleinfomn = $common->getInfo("role_lookup","ROLE_ID ='".$rolemn['ROLE_ID']."'");
	 
     //main
     $main = & new Template('adm_editaccount'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		$idc = $itech->input['idc'];
		
		$username = $itech->input['email'];
		$password = $itech->input['password'];
		$idrole = $itech->input['role'];
		
		//$pre_title = $itech->input['pre'];
		$gender = $itech->input['gender'];// gioi tinh
		$consultantname = $itech->input['consultantname'];
		$email = $itech->input['email'];
		$tel = $itech->input['tel'];
		$website = $itech->input['website'];
		$address = $itech->input['address'];
		$status = $itech->input['status'];
		$mobi = $itech->input['mobi'];
		$chatyahoo = $itech->input['chatyahoo'];
		$chatskype = $itech->input['chatskype'];
		$emailnganluong = $itech->input['emailnganluong'];
		$emailbaokim = $itech->input['emailbaokim'];
		$emailonepay = $itech->input['emailonepay'];
		// get register from home page front-end
		$home = $itech->input['home'];
	
		$sl1 = "";
		$sl2 = "";
		$sl3 = "";
		$s1 = "";
		$s2 = "";
		$s3 = "";
		$list_gender = $print_2->GetDropDown($gender, "gender","" ,"ID_GENDER", "NAME", "IORDER");
		
	// set permission	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_account', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}	
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="e" && $submit == "" && $idc) {
					$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$idc."'");
					$main->set('username', $cst['USER_NAME']);
					$chr = $common->getInfo("consultant_has_role","ID_CONSULTANT = '".$idc."'");
					if($roleinfomn['ROLE_ID'] ==1 && $chr['ROLE_ID'] == 1) $role_list = $print_2->GetDropDown($chr['ROLE_ID'], "role_lookup", "", "ROLE_ID", "NAME", "IORDER");
					else $role_list = $print_2->GetDropDown($chr['ROLE_ID'], "role_lookup", "ROLE_ID <>1", "ROLE_ID", "NAME", "IORDER");
					$main->set('role_list', $role_list);
					$rolecus = $common->getInfo("role_lookup","ROLE_ID ='".$chr['ROLE_ID']."'");
					if($rolecus['ROLE_TYPE'] != "Operation" && $roleinfomn['ROLE_TYPE'] != "Operation"){
						$dis = 'style="display: none;"';
					}
					else $dis = "";
					$main->set('dis', $dis);
					$main->set('consultantname', $cst['FULL_NAME']);
					$main->set('email', $cst['EMAIL']);
					$main->set('tel', $cst['TEL']);
					$main->set('website', $cst['WEBSITE']);
					$main->set('address', $cst['ADDRESS']);
					$main->set('mobi', $cst['MOBI']);
					$main->set('chatyahoo', $cst['CHAT_YAHOO']);
					$main->set('chatskype', $cst['CHAT_SKYPE']);
					if($cst['STATUS'] == 'Active') $sl1 = "selected";
					elseif($cst['STATUS'] == 'Inactive') $sl2 = "selected";
					elseif($cst['STATUS'] == 'Deleted') $sl3 = "selected";
					$list_gender = $print_2->GetDropDown($cst['GENDER'], "gender","" ,"ID_GENDER", "NAME", "IORDER");
					$main->set('list_gender', $list_gender);
					
					if($cst['TITLE'] == 'Mr.') $s1 = "selected";
					elseif($cst['TITLE'] == 'Mrs.') $s2 = "selected";
					elseif($cst['TITLE'] == 'Ms.') $s3 = "selected";
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					$main->set('s3', $s3);
					$main->set('mess1', "");
					$main->set('mess2', "");
					if($cst['AVATAR'] == "")
					$main->set('avatar', "noavatar.jpg");
					else 
					$main->set('avatar', $cst['AVATAR']);
					$main->set('idc', $idc);
					if($cst['EMAIL_NGANLUONG'] == "NO")
					$main->set('emailnganluong', "");
					else
					$main->set('emailnganluong', $cst['EMAIL_NGANLUONG']);
					if($cst['EMAIL_BAOKIM'] == "NO")
					$main->set('emailbaokim', "");
					else
					$main->set('emailbaokim', $cst['EMAIL_BAOKIM']);
					if($cst['EMAIL_ONEPAY'] == "NO")
					$main->set('emailonepay', "");
					else
					$main->set('emailonepay', $cst['EMAIL_ONEPAY']);
					
					$main->set('idc', $idc);

			echo $main->fetch("adm_editaccount");
			exit;
		}
		
		
		// submit edit 				
		elseif($ac=="e" && $submit != "" && $idc) {
		
		$infomess = array();
		$checkfile = true;
		
		// get temp path to file
		
		$tmp_name = $_FILES['file_ul']['name'];
		$file_ul = $_FILES['file_ul']['name'];
		
		// path file upload
		$dir_upload = $pathimg."/avatar/";
		// if file empty or upload failed
		$filesucess = "";
		$mess1 = ""; // check email exist
		$mess2 = ""; // check file upload
		// check account duplicate
		$check_account = $common->getListValue("consultant","EMAIL = '".$email."' AND ID_CONSULTANT <> '".$idc."'","ID_CONSULTANT");
		if($check_account != "") $mess1 = $lang['exist_email'];
		
		// check if file upload not empty
		if($file_ul != "")	{
			if($file_ul!="" && !$print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG")){			
			$file_ul ="";
			$checkfile = false;
			$mess2 = $lang['err_upload1'];
			}
			elseif($file_ul!="" && $print_2->check_extent_file($file_ul,"jpg|gif|bmp|png|JPG|GIF|BMP|PNG"))
			{
					$file_input_tmp = $_FILES['file_ul']['tmp_name'];				
					$prefix = time();
					$checkfile = true;
					//$file_name = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
			}		
		}
		
		// if checkupload not successfull or email is exist return form add or edit
			if($mess1 != "" || $mess2 != "" ){				
					// set param return value for form
					if($status == 'Active') $sl1 = "selected";
					elseif($status == 'Inactive') $sl2 = "selected";
					elseif($status == 'Deleted') $sl3 = "selected";
					
					if($pre_title == 'Mr.') $s1 = "selected";
					elseif($pre_title == 'Mrs.') $s2 = "selected";
					elseif($pre_title == 'Ms.') $s3 = "selected";
					
					$main->set('username', $username);
					$role_list = $print_2->GetDropDown($idrole, "role_lookup", "ROLE_ID <> 1", "ROLE_ID", "NAME", "IORDER");
					$main->set('role_list', $role_list);
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$dis = 'style="display: none;"';
					}
					else $dis = "";
					$main->set('dis', $dis);
					$main->set('consultantname', $consultantname);
					$main->set('email', $email);
					$main->set('tel', $tel);
					$main->set('website', $website);
					$main->set('address', $address);
					$main->set('mobi', $mobi);
					$main->set('chatyahoo', $chatyahoo);
					$main->set('chatskype', $chatskype);
					$main->set('list_gender', $list_gender);
					
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('sl3', $sl3);
					$main->set('s1', $s1);
					$main->set('s2', $s2);
					$main->set('s3', $s3);
					$main->set('mess1', $mess1);
					$main->set('mess2', $mess2);
					$cst = $common->getInfo("consultant","ID_CONSULTANT = '".$idc."'");
					if($cst['AVATAR'] == "")
					$main->set('avatar', "noavatar.jpg");
					else 
					$main->set('avatar', $cst['AVATAR']);
					$main->set('idc', $idc);
					$main->set('emailnganluong', $emailnganluong);
					$main->set('emailbaokim', $emailbaokim);
					$main->set('emailonepay', $emailonepay);

					echo $main->fetch("adm_editaccount");
					exit;						

				//echo $checkupload[0];
			} // end checkupload
			
			if($file_ul != "" && $checkfile){
			// upload receive file
			// file2 = $file_name la file goc chua resize
			
				$file2 = $print_2->copy_and_change_filename($file_input_tmp, $file_ul, $dir_upload, $prefix);				
					if($file2 != ""){						
					// resize image file 1 for thumbnail													
							//$name1 = "small_".time()."_".rand()."_".$name_thumb[0];
							$name1 = "avatar_".$file2;
							// process get thumbnail resize image
							$picname = $file2;
							$pic_small = $name1;
							$origfolder = "avatar";							
							$width = $itech->vars['wavatar'];
							$height = $itech->vars['havatar'];
							$quality = $itech->vars['qualityavatar'];
							$check_thumb = $print_2->makethumb($picname, $pic_small, $origfolder, $width, $height, $quality);
							// if resize successfully
							if($check_thumb) {
							$file1 = $pic_small;
							// check exist file old image on directory
								if (file_exists($dir_upload.$file2) && file_exists($dir_upload.$pic_small)) {
									unlink($dir_upload.$file2);					
								}
							}
							else $file1 = $file2;

						$filesucess = $file1;
						
						//echo "upload successfully!";
					}
					else {
						  echo $lang['err_upload3'];
						  exit;
					}

			}
																				
			// input db
			// check exist file images
					$cs = $common->getInfo("consultant","ID_CONSULTANT ='".$idc."'");
					$old_avatar = $cs['AVATAR'];
					$password_old = $cs['PASSWORD'];
					if($password == "") $password = $password_old;
					else $password = hash('sha256',$password);
					if($filesucess == ""){
					$filesucess = $old_avatar;
					$file1_check = false;
					}
					else $file1_check = true;
					
				try {
					$ArrayData = array( 1 => array(1 => "GENDER", 2 => $gender),
										2 => array(1 => "FULL_NAME", 2 => $consultantname),
										3 => array(1 => "TEL", 2 => $tel),
										4 => array(1 => "EMAIL", 2 => $email),
										5 => array(1 => "PASSWORD", 2 => $password),
										6 => array(1 => "STATUS", 2 => $status),										
										7 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
										8 => array(1 => "ADDRESS", 2 => $address),
										9 => array(1 => "WEBSITE", 2 => $website),
										10 => array(1 => "MOBI", 2 => $mobi),
										11 => array(1 => "CHAT_YAHOO", 2 => $chatyahoo),
										12 => array(1 => "CHAT_SKYPE", 2 => $chatskype),
										13 => array(1 => "USER_NAME", 2 => $username),
										14 => array(1 => "AVATAR", 2 => $filesucess),
										15 => array(1 => "EMAIL_NGANLUONG", 2 => $emailnganluong),
										16 => array(1 => "EMAIL_BAOKIM", 2 => $emailbaokim),
										17 => array(1 => "EMAIL_ONEPAY", 2 => $emailonepay)
										
									  );
							  					
					$update_condit = array( 1 => array(1 => "ID_CONSULTANT", 2 => $idc));
					$common->UpdateDB($ArrayData,"consultant",$update_condit);
					
					$ArrayData2 = array( 1 => array(1 => "ROLE_ID", 2 => $idrole),
										2 => array(1 => "ID_CONSULTANT", 2 => $idc)																			
									  );
									  
									  
					// update consultant_has_role
					$cst_hr = $common->getInfo("consultant_has_role","ID_CONSULTANT = '".$idc."'");					
					$update_condit2 = array( 1 => array(1 => "CONSULTANT_ROLE_ID", 2 => $cst_hr['CONSULTANT_ROLE_ID']));
					$common->UpdateDB($ArrayData2,"consultant_has_role",$update_condit2);
					
					//echo date('Y-m-d H:i:s');
					
				} catch (Exception $e) {
						echo $e;
					}
					
					// check remove old image
						if($file1_check && $old_avatar != ""){
						// check exist file old image on directory
							if (file_exists($dir_upload.$old_avatar)) {
								unlink($dir_upload.$old_avatar);					
							} 
						}
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
