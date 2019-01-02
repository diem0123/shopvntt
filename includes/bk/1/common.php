<?php

class common_function {

	public function getFeature($i){
		
	// get permission
	$feature = array(1=>'adm_home',2=>'adm_displayads',3=>'adm_company',4=>'adm_products',
	5=>'adm_comments',6=>'adm_payments',7=>'adm_account',8=>'adm_roles',
	9=>'adm_common',10=>'adm_menu',11=>'adm_outgoing_email',12=>'adm_provinces',13=>'adm_transfer',14=>'adm_payonline',15=>'adm_contacts',16=>'adm_email_templates',
	17=>'adm_templates_sent',18=>'adm_emk_report');
		
	return $feature[$i];
	}
	
	function datevn ($date) {
          return strftime("%d-%m-%Y", strtotime($date));
     }
	 
	 function dateen ($date) {
          return strftime("%Y-%m-%d", strtotime($date));
     }
	 
	 function datetimevn ($date) {
          return strftime("%d-%m-%Y %H:%M:%S", strtotime($date));
     }
	
	function InsertDB( $ArrayData, $TableName )
    {
        global $DB;
        $str_01a = "INSERT INTO ".$TableName." (";
        $str_01b = " VALUES(";
        
        for ($i = 1; $i <= count( $ArrayData ); $i++ )
        {
            if ( $i != count( $ArrayData ) )
            {
                $str_01a .= $ArrayData[$i][1].",";
                $str_01b .= " '".$ArrayData[$i][2]."',";
            }
            else
            {
                $str_01a .= $ArrayData[$i][1].")";
                $str_01b .= "'".$ArrayData[$i][2]."')";
            }
        }
		
        $DB->query( $str_01a.$str_01b );
		$id = $DB->get_insert_id( );
		//echo $str_01a.$str_01b;
		return $id;
    }

    function UpdateDB( $ArrayData, $TableName, $Condition )
    {
        global $DB;
        $str_01 = "UPDATE ".$TableName." SET ";
        $str_02 = "";
        
        for ($i = 1; $i <= count( $ArrayData ); $i++ )
        {
            if ( $i != count( $ArrayData ) )
            {
                $str_02 .= $ArrayData[$i][1]."='".$ArrayData[$i][2]."',";
            }
            else
            {
                $str_02 .= $ArrayData[$i][1]."='".$ArrayData[$i][2]."' ";
            }
        }
        $str_01 .= $str_02." WHERE ";
        $str_02 = "";
        
        for ($y = 1; $y <= count( $Condition ); $y++ )
        {
            if ( $y != count( $Condition ) )
            {
                $str_02 .= $Condition[$y][1]."='".$Condition[$y][2]."' AND ";
            }
            else
            {
                $str_02 .= $Condition[$y][1]."='".$Condition[$y][2]."'";
            }
        }
        $str_01 .= $str_02;
        $DB->query( $str_01 );
    }
	
	public function getInfo($tb_name,$condition){
		global $DB, $lang, $itech, $std;
		$sql="SELECT * FROM ".$tb_name." WHERE ".$condition;		
		$query=$DB->query($sql);
		$numrow = @mysqli_num_rows($query);
		if($numrow >0) $result = $DB->fetch_row($query);
		else $result = "";
		return $result;
	}

	public function getListValue($class_name,$condition,$fieldname,$char=","){
	global $DB, $lang, $itech, $std;		
		$sql="SELECT * FROM ".$class_name." WHERE ".$condition;    
		$query=$DB->query($sql); 							
		$num=0;
		$str="";
		
		while ($rs=$DB->fetch_row($query))
		{
			if ($num==0)
				$str.=$rs[$fieldname];
			else
				$str.=$char.$rs[$fieldname];
			$num++;
		}
		return $str;
	}
	
	function CountRecord($tb_name,$condition,$field){
	global $DB, $lang, $itech, $std;
		$sql="SELECT count(distinct(".$field.")) as total_count FROM ".$tb_name." WHERE ".$condition;
		$query=$DB->query($sql);
		$numrow = @mysqli_num_rows($query);
		if($numrow >0) {
		$result = $DB->fetch_row($query);
		$total = $result['total_count'];
		}
		else $total = 0;
		
		return $total;
	}
	
	public function getListRecord($tb_name,$condition,$iorder,$from=0,$to=0){
	global $DB, $lang, $itech, $std;
		if ($from==0 && $to==0)
		$sql="SELECT * FROM ".$tb_name."  WHERE ".$condition." ORDER BY ".$iorder; 			
		else 
		$sql="SELECT * FROM ".$tb_name."  WHERE ".$condition." ORDER BY ".$iorder." LIMIT ".$from.",".$to;		
		$query = $DB->query($sql);
		
		return $query;
	}

  public function login($username,$password) {
	 global $DB, $lang, $itech, $std;	 		
		$sql="SELECT * FROM consultant WHERE EMAIL = '".$username."' AND PASSWORD = '".hash('sha256',$password)."'";//AND STATUS = 'Active' 
		$query=$DB->query($sql); 					
		if($rs=$DB->fetch_row($query)){
			$_SESSION['ID_CONSULTANT'] = $rs['ID_CONSULTANT'];
			$_SESSION['TITLE'] = $rs['TITLE'];
			$_SESSION['FULL_NAME'] = $rs['FULL_NAME'];
			$_SESSION['EMAIL'] = $rs['EMAIL'];
			$_SESSION['LAST_LOGIN']= $rs['LAST_LOGIN'];
			$_SESSION['MESSENGER']= $rs['MESSENGER'];
			$_SESSION['USER_MEMBER']= $rs['USER_MEMBER'];
			return  true;
		}
		return false;
	}
	
	public function logout(){
				unset($_SESSION['ID_CONSULTANT']);
				unset($_SESSION['TITLE']);
				unset($_SESSION['FULL_NAME']);
				unset($_SESSION['EMAIL']);
				unset($_SESSION['LAST_LOGIN']);
				unset($_SESSION['MESSENGER']);
				session_destroy();
				$url_redidrect='/';
				$this->redirect_url($url_redidrect);				
	}
	
	public function redirect_url($url_redidrect){				
				echo "<script language=javascript>";
				echo "window.location='".$url_redidrect."'";
				echo "</script>";
	}
	
	public function checkpermission($id_consultant, $featured, $action)
	{
	global $DB, $lang, $itech, $print_2;		
		//$role = $this->getInfo($conn,"ConsultantHasRole","ID_CONSULTANT ='".$id_consultant."'");
		$role_id = $this->getListValue("consultant_has_role","ID_CONSULTANT = '".$id_consultant."'",'ROLE_ID');		
		

	if ($featured == "")
		{
			if ($role_id == 1){
				return "adm_home";
			}	
			elseif ($role_id == 2){
				return "adm_home";// for operation
			}	
			elseif ($role_id == 3){	// for user
				return "adm_home";
			}
			else return "adm_home";			
		}
		else 
		{
			$featured_id = $this->getListValue("feature_lookup","LINK = '".$featured."'",'FEATURE_ID');						
			
			$permission = $this->getListValue("role_has_permission","ROLE_ID = '".$role_id."' AND FEATURE_ID ='".$featured_id."'",$action);						
			
			if ($action == 'DATA_ACCESS')
			{
				if ($permission == "")
					return false;
				else 
				if ($permission == "All")
					return "All";
				else 
					return "Own";
			}
			else 
			{
				if ($permission == 1){
					return true;
				}
				else{ 
					return false;
				}	
			}
		}
	}
	
	public function getHeader($templ='header') {
	global $DB, $lang, $itech, $print_2, $print;	
	$pre = $_SESSION['pre'];
	
		if (!isset($_SESSION['tab']))
			$_SESSION['tab'] = 1;
						
	// set for menu active front-end
	if($templ =='header' || $templ =='m_header' || $templ =='m_pheader'){
		$tpl =  new Template($templ); 
		
		for ($i=1; $i<=9; $i++)
		{
			if ($i==@$_SESSION['tab']){
				//$tpl->set('tab'.$i,'currenta');
				$selected = 'class="active"';
				$tpl->set('st_select'.$i,$selected);				
				
				}
			else{
				//$tpl->set('tab'.$i,'notcurrent');
				$selected = '';
				$tpl->set('st_select'.$i,$selected);
				}
				
		}
				
		
		// get menu sub gioi thieu
		$list_menugt = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 1);// idtype=1 goi thieu
		$tpl->set('list_menugt', $list_menugt);
		$list_menusanpham = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 2);// idtype=2 san pham
		$tpl->set('list_menusanpham', $list_menusanpham);
		$list_menunhamay = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 9);// idtype=9 nha may
		$tpl->set('list_menunhamay', $list_menunhamay);
		//get menu sub vung nguyen lieu
		$list_menuvnl = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 4);// idtype=5 vung nguyen lieu
		$tpl->set('list_menuvnl', $list_menuvnl);
		//get menu tin tuc
		$list_tintuc = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 10);// idtype=10 tin tuc
		$tpl->set('list_tintuc', $list_tintuc);
		//get menu tuyen dung
		$list_tuyendung = $print->getmenugt($pre, 0, 'list_menugt', 'list_menugt_rs', 11);// idtype=11 tuyen dung
		$tpl->set('list_tuyendung', $list_tuyendung);
		
		// get menu sub BÁO GIÁ
		//$list_menu_bao_gia = $print->getmenu_baogia($pre, 0, 'list_menudl', 'list_menudl_rs',2);// idtype=1 san pham
		//$tpl->set('list_menu_bao_gia', $list_menu_bao_gia);
		// get menu sub san pham
		$list_menusp = $print->getmenusp($pre, 0, 'list_menusp', 'list_menusp_rs');
		$tpl->set('list_menusp', $list_menusp);
		
		// get menu sub dich vu
		//$list_menusanpham = $print->getmenusv($pre, 0, 'list_menusv', 'list_menusv_rs', 2);// idtype=2 dich vu
		//$tpl->set('list_menusanpham', $list_menusanpham);
		
		// get menu sub kien thuc
		$list_menutulieu = $print->getmenusv($pre, 0, 'list_menusv', 'list_menusv_rs', 7);// idtype=2 dich vu
		$tpl->set('list_menutulieu', $list_menutulieu);
		
		// set for user loggedin
		//+++++++++++++++++++ check mobile +++++++++++++++++++++	
		if($_SESSION['dv_Type']=="phone") $tlg = 'm_info_login';
		else $tlg = 'info_login';
		$tpl_login =  new Template($tlg);
		if(isset($_SESSION['ID_CONSULTANT']) && $_SESSION['ID_CONSULTANT'] != ""){			
			$tpl_login->set('full_name', $_SESSION['FULL_NAME']);
			$tpl_login->set('username', $_SESSION['EMAIL']);
			$tpl_login->set('user_member', $_SESSION['USER_MEMBER']);			
			$tpl_login->set('idu', $_SESSION['ID_CONSULTANT']);
			$userinfo = $this->getInfo("ConsultantIncharge","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
			$tpl_login->set('idcompany', $userinfo['ID_COM']);
		}
		else{
			$tpl_login->set('full_name', "");
			$tpl_login->set('username', "");
			$tpl_login->set('user_member', "");
			$tpl_login->set('idu', "");	
			$tpl_login->set('idcompany', "");
		}
		
		$tpl->set('info_login',$tpl_login);
		$tpl->set('plang',$pre);
		// get chat yahoo, skype
		$yahoo = $this->gethonhop(2);
		$linkchat_yahoo = "ymsgr:sendIM?".$yahoo['SCONTENTSHORT'];
		$tpl->set('linkchat_yahoo',$linkchat_yahoo);
		$tpl->set('nickchat',$yahoo['SCONTENTSHORT']);
		$sky = $this->gethonhop(3);
		$linkchat_sky = "skype:".$sky['SCONTENTSHORT']."?chat";
		$tpl->set('linkchat_sky',$linkchat_sky);
		// get hotline
		$hotline = $this->gethonhop(1);
		$tpl->set('hotline',base64_decode($hotline['SCONTENTS']));
		//get logo
		$logo = $this->gethonhop(2);
		$tpl->set('logo',base64_decode($logo['SCONTENTS']));
			//get logo
		$link = $this->gethonhop(3);
		$tpl->set('linkface',base64_decode($link['SCONTENTS']));
		// set shopping cart
		if(!isset($_SESSION['shoppingcart'])) {
		$_SESSION['shoppingcart'] = "";
		$countcart = 0;
		}
		elseif($_SESSION['shoppingcart'] != ""){
		$binshop = explode(",",$_SESSION['shoppingcart']);
		$countcart = count($binshop);		
		}
		else{
		$countcart = 0;		
		}
		$tpl->set('countcart',$countcart);
	}
	else{
	// set for menu active back-end
		$tpl =  new Template($templ);
		if (!isset($_SESSION['tab']) || $_SESSION['tab'] == '')	$_SESSION['tab'] = 1;
		for ($i=1; $i<=5; $i++)
		{
			if ($i==@$_SESSION['tab']){			
				//$tpl->set('tab'.$i,'currenta');
				$selected = 'class="stay"';
				$tpl->set('st_select'.$i,$selected);
				
				}
			else{
				//$tpl->set('tab'.$i,'notcurrent');
				$selected = '';
				$tpl->set('st_select'.$i,$selected);
				}
		}
		
		// set for loggedin back-end
		$tpl->set('full_name', $_SESSION['FULL_NAME']);
		$tpl->set('idu', $_SESSION['ID_CONSULTANT']);
		
	}	
		
	
		return $tpl->fetch($templ);
	}
	
	public function gethonhop($idcm) {
	 global $DB, $lang, $itech, $std;	 		
		$info = $this->getInfo("common","ID_COMMON = '".$idcm."' AND STATUS = 'Active' ");
		return $info;
	}
	
 
} //end class func




?>
