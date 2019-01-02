<?php
		$infomess = array();
		//echo "test";exit;
		// set permission		
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "D")){
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "B&#7841;n kh&ocirc;ng c&oacute; quy&#7873;n truy c&#7853;p!";		
		}
		
		$id = $itech->input['id'];
		$img_info = $common->getInfo("images_link","ID_IMG ='".$id."'");		
		$imgname = $img_info['NAME_IMG'];
		// path file upload
		$pathimg = $itech->vars['pathimg'];
		$dir_upload = $pathimg."/".$img_info['ID_CATEGORY']."/";
		if($imgname != "" ){	
			try {
				// check exist file image on directory
				if (file_exists($dir_upload.$imgname)) {
					unlink($dir_upload.$imgname);
					$sql = "DELETE FROM images_link WHERE ID_IMG = '".$id."'";
					$DB->query($sql);
					$infomess['mess1'] = "success";
					$infomess['mess2'] = "X&oacute;a th&agrave;nh c&ocirc;ng!";
				}
				else {
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "&#7842;nh kh&ocirc;ng t&#7891;n t&#7841;i!";
				}
			} catch (Exception $e) {
				//echo $e;
				$infomess['mess1'] = "nosuccess";
				$infomess['mess2'] = "System error!";
			}
		}
		else{
			$infomess['mess1'] = "nosuccess";
			$infomess['mess2'] = "File empty!";
		}
			
		echo json_encode($infomess);
		exit;	
					
?>