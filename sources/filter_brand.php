<?php
		
		$idc = $itech->input['idc'];//ID_CATEGORY
		$idtype = $itech->input['idtype'];//ID_TYPE
		$id_br = $itech->input['id_brand'];//Thương hiệu
		$nongdo = $itech->input['nongdo'];//nồng độ
		$year = $itech->input['year'];//số năm
		$scent = $itech->input['scent'];//Nhóm hương
		$sex = $itech->input['sex'];//giới tính
		$price = $itech->input['price'];
		if($idc and $idtype)
		{
			$getdatacat = $common->getInfo('categories_sub','ID_CATEGORY='.$idc.' AND ID_TYPE='.$idtype.' AND STATUS =  "Active"');
			$link = $print_2->vn_str_filter($getdatacat['NAME_TYPE']);
		}elseif($idc and !isset($idtype)){
			$getdatacat = $common->getInfo('categories','ID_CATEGORY='.$idc.' AND STATUS =  "Active"');
			$link = $print_2->vn_str_filter($getdatacat['NAME_CATEGORY']);
		}
		$infomess = array();
		//chưa nhận đc id_br
		if($link){
			$infomess['mess1'] = 'success';
			if($idtype)
				$infomess['mess2'] = 'sanpham-'.$idc.'-'.$link.'.html?idt='.$idtype.'&id_brand='.$id_br.'&nongdo='.$nongdo.'&year='.$year.'&scent='.$scent.'&sex='.$sex.'&price='.$price;
			else
				$infomess['mess2'] = 'sanpham-'.$idc.'-'.$link.'.html?id_brand='.$id_br.'&nongdo='.$nongdo.'&year='.$year.'&scent='.$scent.'&sex='.$sex.'&price='.$price;
		}
		else{
			$infomess['mess1'] = 'nosuccess';
			$getdatacat = $common->getInfo('categories','ID_CATEGORY='.$idc.' AND STATUS =  "Active"');
			$link = $print_2->vn_str_filter($getdatacat['NAME_CATEGORY']);
			$infomess['mess2'] = 'sanpham-'.$idc.'-'.$link.'.html?id_brand='.$id_br;
		}
		echo json_encode($infomess);
		exit;

?>