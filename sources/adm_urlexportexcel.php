<?php
	session_start();
	$idp   = $itech->input['idp'];
	$vlid  = $itech->input['vlid'];
	if($vlid !=''){
		$_SESSION['listid'] = $vlid;
		
	}
	
?>