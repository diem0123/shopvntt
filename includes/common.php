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
	
	public function getFooter($templ_footer='footer') 
	{
		global $DB, $lang, $itech, $print_2, $print;	
		$pre = $_SESSION['pre'];
		if($templ_footer =='footer'){
				$tpl_footer =  new Template($templ_footer);
				//Get logo 
				$thongtin = $this->gethonhop(7);
				if($thongtin == "")
				$thongtin->set('thongtin', "");
				else{
					//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
				if($thongtin['SCONTENTS'.$pre]=="")
					$tpl_footer->set('thongtin', base64_decode($thongtin['SCONTENTS']));		
				else
					$tpl_footer->set('thongtin', base64_decode($thongtin['SCONTENTS'.$pre]));
					//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
				}
				// get address
				$address = $this->gethonhop(6);
				if($address == "")
				$tpl_footer->set('address', "");
				else{
					//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
				if($address['SCONTENTS'.$pre]=="")
					$tpl_footer->set('address', base64_decode($address['SCONTENTS']));		
				else
					$tpl_footer->set('address', base64_decode($address['SCONTENTS'.$pre]));
					//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
				}
				// social
				$social = $this->gethonhop(15);
				if($social == "")
				$tpl_footer->set('social', "");
				else{
					//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
				if($social['SCONTENTS'.$pre]=="")
					$tpl_footer->set('social', base64_decode($social['SCONTENTS']));		
				else
					$tpl_footer->set('social', base64_decode($social['SCONTENTS'.$pre]));
					//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
				}

				// Logo thông báo bộ công thương
				$bct = $this->gethonhop(5);
				if($bct == "")
				$tpl_footer->set('bct', "");
				else{
				if($bct['SCONTENTS'.$pre]=="")
					$tpl_footer->set('bct', base64_decode($bct['SCONTENTS']));		
				else
					$tpl_footer->set('bct', base64_decode($bct['SCONTENTS'.$pre]));
				}
				
				
				//Menu truy cập nhanh
				//$menu_truycap = $print->get_data_menu('menu1',$pre,'list_menucs','list_menucs_rs');
				//$tpl_footer->set('menu_truycap', $menu_truycap);
				
				$tpl_footer->set('counter', $print->get_counter());
				$tpl_footer->set('statistics', $print->statistics());
				$tpl_footer->set('todayaccess', $print->get_today());
			}
	return $tpl_footer->fetch($templ_footer);
	}
	
	public function getHeader($templ='header',$idtype,$idcategory) {
	global $DB, $lang, $itech, $print_2, $print;	
	$pre = $_SESSION['pre'];
	
		if (!isset($_SESSION['tab']))
			$_SESSION['tab'] = 1;
						
	// set for menu active front-end
	if($templ =='header' || $templ =='header_bk' || $templ =='m_pheader'){
		$tpl =  new Template($templ); 
		
	
	for ($i=1; $i<=8; $i++)
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

		$tmp = 'list_menu_main';
		$tmp_rs = 'list_menu_main_rs';
		$temp3 = 'list_menu_mainsub_rs';
		$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
		$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK");
		$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
		$list_menu_main = $print->Getmenumain($field_name_other1, $tmp,  $field_name_other2, $tmp_rs, $field_name_other3, $temp3, $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="");
		$tpl->set('list_menu_main', $list_menu_main);
		
		
		//Menu sản phẩm
		
		$field_name_other1 = array("ID_CATEGORY","IORDER","LINK");
		$field_name_other2 = array("ID_CATEGORY","ID_TYPE","IORDER","LINK","PICTURE");
		$field_name_other3 = array("ID_PRO_CAT","ID_CATEGORY","ID_TYPE","IORDER","LINK");
		
		$list_cat_menu = $print_2->GetRowMenuPro($field_name_other1, "menupro_cat_rs",  $field_name_other2, "menupro_catsub_rs", $field_name_other3, "menupro_procat_rs", $pre, $idc, $idt, $idprocat, $ida, $idsk, $pri, $idbr, $idmt);
		$tpll = "list_menur_pro";
		$tpl_menur_pro= new Template($tpll);
		$tpl_menur_pro->set('list_menur_pro_rs', $list_cat_menu);
		$tpl->set('list_menur_pro', $tpl_menur_pro);
		//End get menu sản phẩm
		//option category sản phẩm
		$list_cat = $print_2->GetDropDown(6, "categories_sub"," STATUS = 'Active' " ,"ID_TYPE", "NAME_TYPE".$pre, "IORDER");
		$tpl->set('list_cat',$list_cat);
		
		$banner = $this->gethonhop(1);
		if($banner == "")
		$tpl->set('banner', "");
		else{
			//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($banner['SCONTENTS'.$pre]=="")
			$tpl->set('banner', base64_decode($banner['SCONTENTS']));		
		else
			$tpl->set('banner', base64_decode($banner['SCONTENTS'.$pre]));
			//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
		}
		
		$logo = $this->gethonhop(2);
		if($logo == "")
		$tpl->set('logo', "");
		else{
			//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($logo['SCONTENTS'.$pre]=="")
			$tpl->set('logo', base64_decode($logo['SCONTENTS']));		
		else
			$tpl->set('logo', base64_decode($logo['SCONTENTS'.$pre]));
			//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
		}
		//Get tonghop
		$tonghop = $this->gethonhop(14);
		if($tonghop == "")
		$tpl->set('tonghop', "");
		else{
			//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($tonghop['SCONTENTS'.$pre]=="")
			$tpl->set('tonghop', base64_decode($tonghop['SCONTENTS']));		
		else
			$tpl->set('tonghop', base64_decode($tonghop['SCONTENTS'.$pre]));
			//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
		}
		//Hotline
		$hotline = $this->gethonhop(3);
		if($hotline == "")
		$tpl->set('hotline', "");
		else{
			//$tpl->set('title_welcome', $welcome['STITLE'.$pre]);
		if($hotline['SCONTENTS'.$pre]=="")
			$tpl->set('hotline', base64_decode($hotline['SCONTENTS']));		
		else
			$tpl->set('hotline', base64_decode($hotline['SCONTENTS'.$pre]));
			//echo (base64_decode($welcome['SCONTENTS'.$pre]));	
		}
		
		
		if($idcategory ==""){
			$idps = 1;// banner trang chu
			$list_img = $print->getimg_slide(1,'list_img_slide','list_img_slide_rs');
			if(isset($idtype) and $idtype ==1){
				$list_img = $print->getimg_slide_pro(4,'list_slide_pro','list_slide_gt_rs',1);//slide banenr giới thiệu
			}
			elseif(isset($idtype) and $idtype !=1){
				$list_img = $print->getimg_slide_pro(5,'list_slide_pro','list_slide_info_rs',1);//Slide banner tin tức
			}
		}
		else
		{
			
			if($idcategory ==1)
			{
				$idps = 3;
				if($idtype > 0)
				$list_img = $print->getimg_slide_pro($idps,'list_slide_pro','list_slide_pro_rs',$idtype);
				else
				$list_img = "";
				

			}
		}
		$tpl->set('intro', $list_img);
		
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
			if(isset($userinfo['ID_COM']))
			$tpl_login->set('idcompany', $userinfo['ID_COM']);
			else
			$tpl_login->set('idcompany', 1);
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
		for ($i=1; $i<=4; $i++)
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
		$tpl->set('plang',$pre);
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
