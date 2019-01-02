<?php 

	$pathimg = $itech->vars['pathimg'];
	$dirpathimg = $pathimg."/imagenews/";
	
	if(isset($_POST['file'])){
		$file = $dirpathimg . $_POST['file'];
		if(file_exists($file)){
			unlink($file);
		}
	}
?>