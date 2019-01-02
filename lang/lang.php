<?php

/*
if(isset($itech->input['lang'])){
	$to_set = ($itech->input['lang']=='en') ? 'en' : 'vn';
	$std->my_setcookie('language', $to_set);
	header("Location:".$_SERVER["HTTP_REFERER"]); //refresh
}
if($std->my_getcookie('language')!="")
	$to_require = ($std->my_getcookie('language')=='en') ? 'en' : 'vn';
else{//set default to Vietnamese
	$to_require = 'vn';
	$std->my_setcookie('language', 'vn');
	header("Location:".$_SERVER["PHP_SELF"]);
}
require ROOT_PATH."lang/".$to_require.".php";
//require ROOT_PATH."/lang/en.php";
*/

if(isset($itech->input['lang'])){
	if($itech->input['lang']==1) $to_set = 'vn';
	elseif($itech->input['lang']==2) $to_set = 'en';
	elseif($itech->input['lang']==3) $to_set = 'jp';
	elseif($itech->input['lang']==4) $to_set = 'kr';
	else $to_set = 'en';

	//$to_set = ($itech->input['lang']==2) ? 'en' : 'vn';
	$std->my_setcookie('language', $to_set);	
	header("Location:".$_SERVER["HTTP_REFERER"]); //refresh	
}
if($std->my_getcookie('language')!=''){
	if($std->my_getcookie('language')=='vn') $to_require = 'vn';
	elseif($std->my_getcookie('language')=='en') $to_require = 'en';
	elseif($std->my_getcookie('language')=='jp') $to_require = 'jp';
	elseif($std->my_getcookie('language')=='kr') $to_require = 'kr';
	else $to_require = 'en';
	
	//$to_require = ($std->my_getcookie('language')=='en') ? 'en' : 'vn';
}	
else{//set default to Vietnamese
	$to_require = 'vn';
	$std->my_setcookie('language', 'vn');
	//header("Location:".$_SERVER["PHP_SELF"]);
}

if ($to_require == 'en') $pre="_e";
elseif ($to_require == 'jp') $pre="_jp";
elseif ($to_require == 'kr') $pre="_kr";	
else $pre="";
	
$_SESSION['pre'] = $pre;


//session_register('pre');

require ROOT_PATH."lang/".$to_require.".php";


?>