<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {	 	
		$url_redidrect='index.php?act=adm_login';
		$common->redirect_url($url_redidrect);
	}	
	 
      	 
		$pathimg = $itech->vars['pathimg'];
		
		$idcom = 1;// default for tomiki
		// set for wysywyg insert images
		$_SESSION['idcom'] = $idcom;
		$ac = $itech->input['ac'];
		$submit = $itech->input['submit'];
		
		$idr = $itech->input['idr'];
		$rolename = $itech->input['rolename'];
		$roletype = $itech->input['roletype'];
		$level = $itech->input['level'];
	
		$sl1 = "";
		$sl2 = "";
		$s1 = "";
		$s2 = "";
		$s3 = "";
		$s4 = "";
		$s5 = "";
		
	// set permission	
		if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], 'adm_roles', "AE")){
			$url_redidrect='index.php?act=error403';
			$common->redirect_url($url_redidrect);				
			exit;		
		}	
	
	$pagetitle = $itech->vars['site_name'];	

		if($submit == "") {
			if($ac=="a"){
					//main
					$main = & new Template('adm_addrole');
					$main->set("title", $pagetitle);
					$main->set('rolename', "");
					$main->set('sl1', "selected");
					$main->set('sl2', "");
					$main->set('s1', "");
					$main->set('s2', "");
					$main->set('s3', "");
					$main->set('s4', "selected");
					$main->set('s5', "");
					$main->set('idr', $idr);
					$main->set('ac', "a");
					$main->set('title_action', $lang['addrole']);
					$main->set('button_action', $lang['add']);

				}
			elseif($ac=="e" && $idr){
					//main
					$main = & new Template('adm_addrole');
					$main->set("title", $pagetitle);
					$rl = $common->getInfo("role_lookup","ROLE_ID = '".$idr."'");
			
					$main->set('rolename', $rl['NAME']);
					if($rl['ROLE_TYPE'] == "Consultant") $sl1 = "selected";
					elseif($rl['ROLE_TYPE'] == "Operation") $sl2 = "selected"; 
					for($i=1;$i<=5;$i++){
						if($rl['LEVEL'] == $i) $s[$i] = "selected";
						else $s[$i] = "";
						$main->set('s'.$i, $s[$i]);
					}
					$main->set('sl1', $sl1);
					$main->set('sl2', $sl2);
					$main->set('idr', $idr);
					$main->set('ac', "e");
					$main->set('title_action', $lang['editrole']);
					$main->set('button_action', $lang['save']);
				}
			elseif($ac=="v" && $idr){
				//main
					$main = & new Template('adm_viewrole');
					$main->set("title", $pagetitle);
				$rl = $common->getInfo("role_lookup","ROLE_ID = '".$idr."'");
			
					$main->set('rolename', $rl['NAME']);
					for($i=1;$i<=5;$i++){
						if($i==1) $s[$i] = "Diamon";
						elseif($i==2) $s[$i] = "Gold";
						elseif($i==3) $s[$i] = "Silver";
						elseif($i==4) $s[$i] = "Cu";
						else $s[$i] = "Trial";
						if($rl['LEVEL'] == $i)
						$main->set('level', $s[$i]);
					}
					if($rl['ROLE_TYPE'] == 'Consultant' ) $namerl = 'Nhân viên';
					elseif($rl['ROLE_TYPE'] == 'Operation' ) $namerl = 'Quản lý';
					$main->set('roletype', $namerl);
					$main->set('title_action', $lang['viewrole']);

				}
		$list_module = $common->getListRecord("module_lookup","1","IORDER");
		$data="";
		$num=1;
		$tmp = & new Template('adm_permission_list');
		
		while ($rs_module=$DB->fetch_row($list_module))
		{
			$list_feature = $common->getListRecord("feature_lookup","MODULE_ID='".$rs_module['MODULE_ID']."'","IORDER");
			$tmp->set('module',$rs_module['NAME']);
			$tmp->set('num',$num);
			$data.=$tmp->fetch('adm_permission_list');
			
			while ($rs_feature=$DB->fetch_row($list_feature))
			{
				$tmp->set('feature_name',$rs_feature['NAME']);
				$tmp->set('module',"");
				$tmp->set('feature_id',$rs_feature['FEATURE_ID']);
				$ae = $common->getListValue("role_has_permission","ROLE_ID = '".$idr."' AND FEATURE_ID = '".$rs_feature['FEATURE_ID']."'","AE");
				if ($ae==1)
					$tmp->set('cka',"checked");
				else
					$tmp->set('cka',"");
				
				$v = $common->getListValue("role_has_permission","ROLE_ID = '".$idr."' AND FEATURE_ID = '".$rs_feature['FEATURE_ID']."'","V");
				if ($v==1)
					$tmp->set('ckv',"checked");
				else
					$tmp->set('ckv',"");
				
				$d = $common->getListValue("role_has_permission","ROLE_ID = '".$idr."' AND FEATURE_ID = '".$rs_feature['FEATURE_ID']."'","D");
				if ($d==1)
					$tmp->set('ckd',"checked");
				else
					$tmp->set('ckd',"");
				
				$ex = $common->getListValue("role_has_permission","ROLE_ID = '".$idr."' AND FEATURE_ID = '".$rs_feature['FEATURE_ID']."'","EX");
				if ($ex==1)
					$tmp->set('ckex',"checked");
				else
					$tmp->set('ckex',"");
					
				$da = $common->getListValue("role_has_permission","ROLE_ID = '".$idr."' AND FEATURE_ID = '".$rs_feature['FEATURE_ID']."'","DATA_ACCESS");
				if ($da=="Own")
				{
					$tmp->set('rd1',"checked");
					$tmp->set('rd2',"");
				}
				else
				if ($da=="All")
				{
					$tmp->set('rd1',"");
					$tmp->set('rd2',"checked");
				}
				else 
				{
					$tmp->set('rd1',"");
					$tmp->set('rd2',"");
				}
				$data.=$tmp->fetch('adm_permission_list');
			}
			$num++;
		}
		//$main->set('M016',$this->errormsg->getError("M016"));
		$main->set('list_permission',$data);
		if($ac == "v") echo $main->fetch("adm_viewrole");
		else echo $main->fetch("adm_addrole");
			exit;
		}

?>
