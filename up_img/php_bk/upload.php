<?php
    	
	include('class.uploader.php');
	
	$nameCKEditor = $_POST['nameCKEditor'];
	$fnum = $_POST['fnum'];
	$langCode = $_POST['langCode'];
    
    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../../images/bin/imagenews/', //Upload directory {String}
        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'perms' => null, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));
	
    
    if($data['isComplete']){
	
        $files = $data['data'];
		//echo json_encode($files['metas'][0]['name']);
        //print_r($files);
		echo "<script>window.location.href='".$pahroot."/gcfood3/thu-vien.html';</script>";		
		//echo "<script language=javascript>window.location.href='".$pahroot."/gcfood3/thu-vien.html?CKEditor=".$nameCKEditor."&CKEditorFuncNum=".$fnum."&langCode=".$langCode."';</script>";
    exit;
	}

    if($data['hasErrors']){	
        $errors = $data['errors'];
		echo json_encode($errors);
        //print_r($errors);
		echo "<script language=javascript>window.location.href='".$pahroot."/gcfood3/thu-vien.html?CKEditor=".$nameCKEditor."&CKEditorFuncNum=".$fNum."&langCode=".$langCode."';</script>";
    }
    
    function onFilesRemoveCallback($removed_files){
        foreach($removed_files as $key=>$value){
            $file = '../../images/bin/imagenews/' . $value;
            if(file_exists($file)){
                unlink($file);
            }
        }
        
        return $removed_files;
    }
?>
