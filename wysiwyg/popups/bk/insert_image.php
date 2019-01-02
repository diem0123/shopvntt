<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>openWYSIWYG | Select Color</title>
<link rel="stylesheet" type="text/css" href="../../css/wstyle.css" media="screen,print" />
<?php 
$INFO = array();
require "../../conf_global.php";

$server = $INFO['sql_host'];
$username = $INFO['sql_user'];
$password = $INFO['sql_pass'];
$sql_database = $INFO['sql_database'];
$base_url = $INFO['roothttp'];
//$base_url = $_SERVER['DOCUMENT_ROOT'].'/'; 
?>
<script type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<input type="hidden" name="base_url" id="base_url" value="<?=$base_url?>">
<input type="hidden" id="selectfile" name="selectfile" value="I">
<script type="text/javascript">
function getlibrary(page) {	
	//alert($("#base_url").val());
	document.getElementById("selectfile").value = "I";
	$.ajax({
		url: $("#base_url").val()+'index.php',
		type: 'POST',
		data: 'act=getimagelibrary&page='+ page,
		success: function(data) {
		//alert('111');
		$('#showlibrary').html(data);
		}
	});
}

getlibrary(0);	
</script>
</head>
<script language="JavaScript" type="text/javascript">
var qsParm = new Array();
/* ---------------------------------------------------------------------- *\
  Function    : retrieveWYSIWYG()
  Description : Retrieves the textarea ID for which the image will be inserted into.
\* ---------------------------------------------------------------------- */
function retrieveWYSIWYG() {
  var query = window.location.search.substring(1);
  var parms = query.split('&');
  for (var i=0; i<parms.length; i++) {
    var pos = parms[i].indexOf('=');
    if (pos > 0) {
       var key = parms[i].substring(0,pos);
       var val = parms[i].substring(pos+1);
       qsParm[key] = val;
    }
  }
}


/* ---------------------------------------------------------------------- *\
  Function    : insertImage()
  Description : Inserts image into the WYSIWYG.
\* ---------------------------------------------------------------------- */
function insertImage() {
//DoCloseCheckUserName();

  var image = '<img src="' + document.getElementById('imageurl').value + '" alt="' + document.getElementById('alt').value + '" alignment="' + document.getElementById('alignment').value + '" border="' + document.getElementById('borderThickness').value + '" hspace="' + document.getElementById('horizontal').value + '" vspace="' + document.getElementById('vertical').value + '">';
  window.opener.insertHTML(image, qsParm['wysiwyg']);
  //window.close();
}

function DoCloseCheckUserName(){	
		var dataTemp = document.uploadimg.pic.value;
		if (window.opener.document.news.picture.value=="")
			window.opener.document.news.picture.value = dataTemp;
		else
			window.opener.document.news.picture.value =window.opener.document.news.picture.value+ ";"+ dataTemp;
		window.close();
		//window.opener.document.frm_regist.password.focus();
	}

</script>
<?php
function check_extent_file($file_name,$extent_file){
	$extent_file= $extent_file;//"jpg|gif";	
	if(!preg_match("/\\.(" . $extent_file . ")$/",$file_name)){ 
    	 return false;
	}else{
 		return true;
	}
}
function copy_and_change_filename($file_input_tmp, $file_input_name, $dir_upload, $prefix){

		$source = $file_input_tmp;
		$part=explode(".", $file_input_name);
		//$file_name = $prefix . "." . $part[1];
		$file_name = $prefix."_".$file_input_name;
		$dest = $dir_upload . $file_name;
		copy( $source, $dest );
		return $file_name;
}



//$db=mysql_connect($server, $username, $password);
$db=mysqli_connect($server, $username, $password, $sql_database);
if(!$db){
	echo "Can not connect mysql server !";
}

//$sqldb=mysql_select_db($sql_database,$db);
$sqldb=mysqli_select_db($db, $sql_database);
if(!$sqldb){
	echo "Can not found database !";
}
$picture = "";
$pinsert= $_GET['wysiwyg'];	
if (isset($_GET['act']))
{
	if ($_GET['act']=='upload'){
		$picture = $_FILES["picture"]["name"];			
		if($picture!="" && check_extent_file($picture,"jpg|JPG|gif|GIF|bmp|BMP|png|PNG|pdf|PDF|xls|xlsx|doc|docx")==false){			
			$picture = "";
		}else
			if($picture!="" && check_extent_file($picture,"jpg|JPG|gif|GIF|bmp|BMP|png|PNG|pdf|PDF|xls|xlsx|doc|docx")==true){
				$file_input_tmp = $_FILES["picture"]["tmp_name"];
				$file_input_name =$_FILES["picture"]["name"];
				$prefix =time();//date('dmY').
				$picture = copy_and_change_filename($file_input_tmp, $file_input_name,"../../images/bin/imagenews/", $prefix);
				//echo DIR_IMG_UPLOAD.$intro_img1;
			}
			else{
					$picture = "";
				}
	}else
	{
		if ($_GET['pic']!="")
			unlink("../../images/imagenews/".$_GET['pic']);	
			
		echo "<script language='javascript'>";
		echo "window.close();";
		echo "</script>";
	}
}
?>
<body bgcolor="#EEEEEE" marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" onLoad="retrieveWYSIWYG();">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="404" valign="top">
<table border="0" cellpadding="0" cellspacing="0" style="padding:10px;"><tr><td>
<form name="uploadimg" method="post" action="insert_image.php?wysiwyg=<?=$pinsert?>&act=upload" enctype="multipart/form-data">
<div style="height:10px;"></div>
<span style="font-family: arial, verdana, helvetica; font-size: 11px; font-weight: bold;">Upload file:</span><span style="font-family: arial, verdana, helvetica; font-size: 11px; "> Chọn ảnh Upload hoặc click vào ảnh bên phải để chèn link</span>
<div style="height:10px;"></div>
<table width="380" border="0" cellpadding="0" cellspacing="0" style="background-color: #F7F7F7; border: 2px solid #FFFFFF; padding: 5px;">
 <tr>
 	<td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;" width="80">Select file:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;" width="300"><input type="file" name="picture" value=""  style="font-size: 10px; width:70% ">&nbsp;&nbsp;<input name="upload" type="submit" class="button" style="font-size: 10px; height:19px; width:60px" value="Upload"></td>
 </tr>
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;" width="80">Image URL:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;" width="300"><input type="text" name="imageurl" id="imageurl" value="images/bin/imagenews/<?=$picture?>"  style="font-size: 10px; width: 100%;"></td>
 </tr>
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;">Alternate Text:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;"><input type="text" name="alt" id="alt" value=""  style="font-size: 10px; width: 100%;"></td>
 </tr>
</table>
	


<table width="380" border="0" cellpadding="0" cellspacing="0" style="margin-top: 10px;"><tr><td>

<span style="font-family: arial, verdana, helvetica; font-size: 11px; font-weight: bold;">Layout:</span>
<table width="185" border="0" cellpadding="0" cellspacing="0" style="background-color: #F7F7F7; border: 2px solid #FFFFFF; padding: 5px;">
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;" width="100">Alignment:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;" width="85">
	<select name="alignment" id="alignment" style="font-family: arial, verdana, helvetica; font-size: 11px; width: 100%;">
	 <option value="">Not Set</option>
	 <option value="left">Left</option>
	 <option value="right">Right</option>
	 <option value="texttop">Texttop</option>
	 <option value="absmiddle">Absmiddle</option>
	 <option value="baseline">Baseline</option>
	 <option value="absbottom">Absbottom</option>
	 <option value="bottom">Bottom</option>
	 <option value="middle">Middle</option>
	 <option value="top">Top</option>
	</select>
	</td>
 </tr>
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;">Border Thickness:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;"><input type="text" name="borderThickness" id="borderThickness" value=""  style="font-size: 10px; width: 100%;"></td>
 </tr>
</table>	

</td>
<td width="10">&nbsp;</td>
<td>

<span style="font-family: arial, verdana, helvetica; font-size: 11px; font-weight: bold;">Spacing:</span>
<table width="185" border="0" cellpadding="0" cellspacing="0" style="background-color: #F7F7F7; border: 2px solid #FFFFFF; padding: 5px;">
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;" width="80">Horizontal:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;" width="105"><input type="text" name="horizontal" id="horizontal" value=""  style="font-size: 10px; width: 100%;"></td>
 </tr>
 <tr>
  <td style="padding-bottom: 2px; padding-top: 0px; font-family: arial, verdana, helvetica; font-size: 11px;">Vertical:</td>
	<td style="padding-bottom: 2px; padding-top: 0px;"><input type="text" name="vertical" id="vertical" value=""  style="font-size: 10px; width: 100%;"></td>
 </tr>
</table>	
</td></tr></table>
<input type="hidden" name="pic" value="<?=$picture?>">
<div align="right" style="padding-top: 5px;"><input type="button" value="  Submit  " onClick="insertImage();window.close();" style="font-size: 12px;" >&nbsp;<input type="button" value="  Cancel  " onClick="window.location='insert_image.php?wysiwyg=contentout&act=cancel&pic=<?=$picture?>'" style="font-size: 12px;" ></div>
</form>
<div style="margin-top:20px; margin-left:10px;width:380px;">
<span style="font-family: arial, verdana, helvetica; font-size: 11px; font-weight: bold;">Đường dẫn file upload tại đây:</span>
<div style="height:10px;"></div>
<?php if($picture == "") { ?>
<input type="text" name="fileurl" id="fileurl" value=""  style="font-size: 10px; width:100%; height:20px;">
<?php } else { ?>
<input type="text" name="fileurl" id="fileurl" value="<?=$base_url?>images/bin/imagenews/<?=$picture?>"  style="font-size: 10px; width: 100%;height:20px;">
<?php } ?>
</div>
</table>
</td>
		<td valign="top">
		<div id="showlibrary"></div>
		</td>
	</tr>
</table>
</body>
</html>
