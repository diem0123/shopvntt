<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
     //main
     $main = & new Template('adm_addaccount'); 	 
	 $pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
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
	if(!$home){	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_account', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}
	}
	//else register for public user	
	
	$pagetitle = $itech->vars['site_name'];
	$main->set("title", $pagetitle);

		if($ac=="a" && $submit == "") {
					// set permission conditional for role
					//check role type
					$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
					$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
					// don't allow user access company
					if($roleinfo['ROLE_TYPE'] != "Operation"){
						$url_redidrect='index.php?act=error403';
						$common->redirect_url($url_redidrect);				
						exit;
					}
					
					$main->set('username', "");
					$role_list = $print_2->GetDropDown("", "role_lookup", "ROLE_ID <> 1", "ROLE_ID", "NAME", "IORDER");
					$main->set('role_list', $role_list);
					$main->set('consultantname', "");
					$main->set('email', "");
					$main->set('tel', "");
					$main->set('website', "");
					$main->set('address', "");
					$main->set('mobi', "");
					$main->set('chatyahoo', "");
					$main->set('chatskype', "");
					$main->set('list_gender', $list_gender);
					$main->set('sl1', "selected");
					$main->set('sl2', "");
					$main->set('sl3', "");
					$main->set('s1', "selected");
					$main->set('s2', "");
					$main->set('s3', "");
					$main->set('mess1', "");
					$main->set('mess2', "");
					$main->set('emailnganluong', "");					
					$main->set('emailbaokim', "");
					$main->set('emailonepay', "");

			echo $main->fetch("adm_addaccount");
			exit;
		}
		
		elseif($ac=="a" && $submit != "") {
		
		$infomess = array();
		$checkfile = true;
		
		// get temp path to file
		if(!$home){
		$tmp_name = $_FILES['file_ul']['name'];
		$file_ul = $_FILES['file_ul']['name'];
		}
		else {
		$tmp_name = "";
		$file_ul = "";
		}
		// path file upload
		$dir_upload = $pathimg."/avatar/";
		// if file empty or upload failed
		$filesucess = "";
		$mess1 = ""; // check email exist
		$mess2 = ""; // check file upload
		// check account duplicate
		$check_account = $common->getListValue("consultant","EMAIL = '".$email."'","ID_CONSULTANT");
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
				if(!$home){
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
					$main->set('emailnganluong', $emailnganluong);
					$main->set('emailbaokim', $emailbaokim);
					$main->set('emailonepay', $emailonepay);

					echo $main->fetch("adm_addaccount");
					exit;
				}
				else {
				$infomess['mess1'] = "error";
				$infomess['mess2'] = $lang['exist_email'];
				echo json_encode($infomess);
				exit;				
				}				

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
				try {
					$ArrayData = array( 1 => array(1 => "GENDER", 2 => $gender),
										2 => array(1 => "FULL_NAME", 2 => $consultantname),
										3 => array(1 => "TEL", 2 => $tel),
										4 => array(1 => "EMAIL", 2 => $email),
										5 => array(1 => "PASSWORD", 2 => hash('sha256',$password)),
										6 => array(1 => "STATUS", 2 => $status),
										7 => array(1 => "CREATED_DATE", 2 => date('Y-m-d H:i:s')),
										8 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
										9 => array(1 => "ADDRESS", 2 => $address),
										10 => array(1 => "WEBSITE", 2 => $website),
										11 => array(1 => "MOBI", 2 => $mobi),
										12 => array(1 => "CHAT_YAHOO", 2 => $chatyahoo),
										13 => array(1 => "CHAT_SKYPE", 2 => $chatskype),
										14 => array(1 => "USER_NAME", 2 => $username),
										15 => array(1 => "AVATAR", 2 => $filesucess),
										16 => array(1 => "EMAIL_NGANLUONG", 2 => $emailnganluong),
										17 => array(1 => "EMAIL_BAOKIM", 2 => $emailbaokim),
										18 => array(1 => "EMAIL_ONEPAY", 2 => $emailonepay)
									  );
							  
					$id_const = $common->InsertDB($ArrayData,"consultant");
					
					$ArrayData2 = array( 1 => array(1 => "ROLE_ID", 2 => $idrole),
										2 => array(1 => "ID_CONSULTANT", 2 => $id_const)																			
									  );
					
					$idc_hasrole = $common->InsertDB($ArrayData2,"consultant_has_role");
					
				} catch (Exception $e) {
						echo $e;
					}						
			
			echo "<script language=javascript>window.close();window.opener.location.reload();</script>";					
		}

?>
