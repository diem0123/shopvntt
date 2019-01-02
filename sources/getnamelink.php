<?php
		
		
		$idt = $itech->input['idt'];
		$cap = $itech->input['cap'];
		$infomess = array();
		$pre = $_SESSION['pre'];

		if($cap == 1){
		$condit = " STATUS = 'Active' AND ID_TYPE = '".$idt."'";
		$catinfo = $common->getInfo("common_type",$condit);
	
		$linkname = $print_2->vn_str_filter($catinfo['SNAME']);
		
        $titlename = $catinfo['SNAME'.$pre];	  
		$link = "info-".$idt."-".$linkname.".html";		
		$infomess['namelink'] = $link;
		$infomess['titlename'] = $titlename;
		$infomess['idmuc'] = $idt;
		$infomess['nametb'] = "common_type";
		}
		elseif($cap == 2){
		$condit = " STATUS = 'Active' AND ID_CAT = '".$idt."'";
		$catinfo = $common->getInfo("common_cat",$condit);
		
		$linkname = $print_2->vn_str_filter($catinfo['SNAME']);
		
        $titlename = $catinfo['SNAME'.$pre];	  
		$link = "info-".$catinfo['ID_TYPE']."-".$linkname.".html?idcat=".$idt;		
		$infomess['namelink'] = $link;
		$infomess['titlename'] = $titlename;
		$infomess['idmuc'] = $catinfo['ID_TYPE'];
		$infomess['nametb'] = "common_cat";
		}
		elseif($cap == 3){
		$condit = " STATUS = 'Active' AND ID_CAT_SUB = '".$idt."'";
		$catinfo = $common->getInfo("common_cat_sub",$condit);
		
		$linkname = $print_2->vn_str_filter($catinfo['SNAME']);
		
        $titlename = $catinfo['SNAME'.$pre];
		$condit1 = " STATUS = 'Active' AND ID_CAT = '".$catinfo['ID_CAT']."'";
		$typeinfo = $common->getInfo("common_cat",$condit1);
		
		$link = "info-".$typeinfo['ID_TYPE']."-".$linkname.".html?idcat=".$catinfo['ID_CAT']."&idcatsub=".$idt;		
		$infomess['namelink'] = $link;
		$infomess['titlename'] = $titlename;
		$infomess['idmuc'] = $typeinfo['ID_TYPE'];
		$infomess['nametb'] = "common_cat_sub";
		}
		else{		
		$infomess['namelink'] = "";
		$infomess['titlename'] = "";
		$infomess['idmuc'] = "";
		$infomess['nametb'] = "";
		
		}
		

		echo json_encode($infomess);
		exit;
?>