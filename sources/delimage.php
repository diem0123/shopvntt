<?php
	$infomess = array();
	
	if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		//echo "You don't have permission to delete this image!";
		$infomess['mess1'] = "error";
		$infomess['mess2'] = "You don't have permission to delete this file!";
		echo json_encode($infomess);
		exit;
	}
	$role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
	$roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
	$list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
	$list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
	//check consultant incharge for company		
		//$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
		// check permission com incharge owner
		if($roleinfo['ROLE_TYPE'] != "Operation"){
		$infomess['mess1'] = "error";
		$infomess['mess2'] = "You don't have permission to delete this file!";
		echo json_encode($infomess);
		exit;
		}
		
		$idcom = $itech->input['idcom'];
		$imgname = $itech->input['imgname'];
		$ac = $itech->input['ac'];
		$page = $itech->input['page'];
		$vlid = $itech->input['vlid'];
		$thumuc = $itech->input['thumuc'];
		
		$pathimg = $itech->vars['pathimg'];
		$dir_img = $pathimg."/".$thumuc."/";
		
		
		
		if($imgname != "" && $vlid == ""){	
			try {
				// check exist file image on directory
				if (file_exists($dir_img.$imgname)) {
					unlink($dir_img.$imgname);					
					//echo "success";
					$infomess['mess1'] = "success";
					$infomess['mess2'] = "Delete okie!";
				}
				else {
				//echo "Image not exist!";
				$infomess['mess1'] = "error";
				$infomess['mess2'] = $dir_img.$imgname;//"File không tồn tại!";
				}
			} catch (Exception $e) {
				//echo $e;
				$infomess['mess1'] = "error";
				$infomess['mess2'] = $e;
			}
			
			
			echo json_encode($infomess);
			exit;
		}
		elseif($imgname == "" && $ac=="d" && $vlid != ""){
		
			// split vlid
			$list_temp = explode("]]o[[",$vlid);
			$flag = true;
			$file_not_exist = "";
			foreach($list_temp as $key => $value){
				if (file_exists($dir_img.$value)) {
						unlink($dir_img.$value);						
						//echo "deleted file:".$value."<br>";
				}
				else {
					$flag = false;
					$file_not_exist . "File không tồn tại:<br>";					
					$file_not_exist .= $value."<br>";
				}			
			}
			
			if($flag){
			//echo "success";
			$infomess['mess1'] = "success";
			$infomess['mess2'] = "Delete okie!";
			}
			else {
			//echo $file_not_exist;
			$infomess['mess1'] = "error";
			$infomess['mess2'] = $file_not_exist;
			}
			
			echo json_encode($infomess);
			exit;

		}
		else{
		//echo $file_not_exist;
			$infomess['mess1'] = "error";
			$infomess['mess2'] = "File không tồn tại:<br>";
			echo json_encode($infomess);
			exit;
		}
				
?>