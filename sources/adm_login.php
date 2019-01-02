<?php
	$username = $itech->input['username'];
	$password = $itech->input['password'];
	//echo "id=".$_SESSION['ID_CONSULTANT'];
     if(isset($itech->input['Login']))
	 {
	 	if ($common->login($username,$password))
		{		
			$module = $common->checkpermission($_SESSION['ID_CONSULTANT'], "", "");
			if ($module=="")
			{				
				unset($_SESSION['ID_CONSULTANT']);
				unset($_SESSION['TITLE']);
				unset($_SESSION['FULL_NAME']);
				unset($_SESSION['EMAIL']);
				unset($_SESSION['LAST_LOGIN']);
				unset($_SESSION['MESSENGER']);
				$url_redidrect='index.php?act=error403';
				$common->redirect_url($url_redidrect);
				
			}
			else 
			{					
				$url_redidrect='index.php?act='.$module;
				$common->redirect_url($url_redidrect);
			}
		}
		else {
			$msg = "Email ho&#7863;c m&#7853;t kh&#7849;u kh&ocirc;ng &#273;&uacute;ng!";			
		}
	 }
	elseif(isset($_SESSION['ID_CONSULTANT']))
	{		
		$module = $common->checkpermission($_SESSION['ID_CONSULTANT'], "", "");		
		$url_redidrect='index.php?act='.$module;
		$common->redirect_url($url_redidrect);
	}
	else 
	{
		$username = "";
		$msg = "";
	}
	 
     $main = & new Template('adm_login');
	 $main->set('title', $INFO['site_name']);
	 $main->set('footer', $lang['copyright']);
	 $main->set('username', $username);
	 $main->set('password', '');
	 $main->set('msg', $msg);

     echo $main->fetch('adm_login');
?>