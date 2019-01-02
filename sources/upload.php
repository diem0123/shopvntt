<?php
	
	
	$pathimg = $itech->vars['pathimg'];
	$dirpathimg = $pathimg."/imagenews/";
	//@chmod($dirpathimg, 777);
	
	$nameCKEditor = $itech->input['nameCKEditor'];
	$fnum = $itech->input['fnum'];
	$langCode = $itech->input['langCode'];
	$allowedExtensions = array('jpg','jpeg','gif','png','bmp','pdf','doc','docx','xls','xlsx','ppt','pptx','txt','mp3','wmv','mp4','avi','wav','wma','mpg','mpeg','swf','flv');
    
    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number} (chu y: gioi han set trong file custom.js)
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => $allowedExtensions, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => $dirpathimg, //Upload directory {String}
        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'perms' => 0777, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));	
	
	//print_r($data);exit;
	//print_r($_FILES['files']);exit;
	$files = $_FILES['files'];
	//echo $files['name'][0];
	//$files = $data['data'];		
	//echo json_encode($files['metas'][0]['name']);
	
	/*
	
	$url_redidrect='thu-vien.html?CKEditor='.$nameCKEditor.'&CKEditorFuncNum='.$fnum.'&langCode='.$langCode;		
	$common->redirect_url($url_redidrect);
	*/
/*
    if($data['isComplete']){
	
        $files = $data['data'];		
		//echo json_encode($files['metas'][0]['name']);
        //print_r($files['metas'][0]['name']);exit;
		//$url_redidrect='thu-vien.html?CKEditor='.$nameCKEditor.'&CKEditorFuncNum='.$fnum.'&langCode='.$langCode;		
		//$common->redirect_url($url_redidrect);
		
	}
*/
    if($data['hasErrors']){	
        $errors['name'] = $files['name'][0];
		$errors['err'] = $data['errors'];		
		echo json_encode($errors);
        //print_r($errors);
		//$url_redidrect='thu-vien.html?CKEditor='.$nameCKEditor.'&CKEditorFuncNum='.$fnum.'&langCode='.$langCode;
		//$common->redirect_url($url_redidrect);
		
    }
	else{
	$errors['name'] = $files['name'][0];
	$errors['err'] = 'ok';
	echo json_encode($errors);	
	}


	
?>
