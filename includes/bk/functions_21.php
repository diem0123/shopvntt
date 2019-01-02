<?php

class func_2 {

     /*-------------------------------------------------------------------------*/
// Insert data
function phantrang($TotalRows,$RowsInPage,$maxpage,$link_page,$page,$class){
	if ($TotalRows%$RowsInPage!=0)
		$numpage=(int)($TotalRows/$RowsInPage)+1;
	else
		$numpage=(int)($TotalRows/$RowsInPage);
		
	$vl=explode('.',$page/$maxpage);
	
	$step=$vl[0];
	
	if ($step==0)
		{
			$start=1;
			$step=1;
		}
	else
		{
			$start=$step*$maxpage+1;
		}
		
	$end=$start+$maxpage-1;
	
	if ($page<$maxpage){
		$str="Previous&nbsp";
	}
	else
		{
			if($step*$maxpage-$maxpage==0)
				$b=1;
			else
				$b=$step*$maxpage-$maxpage+1;
			$str="<a href='".$link_page."?page=".$b."' class='".$class."'>Previous</a>&nbsp;";
		}
		
	if ($end<$numpage){
		for($i=$start;$i<=$end;$i++){
			if($page!=$i){
				$str.= "&nbsp;<a href='" . $link_page . "?page=" . $i . "' class='". $class . "'>";
				$str.= $i . "</a>&nbsp;";
			}else{
				$str.= "&nbsp;<b>[<a href='" . $link_page . "?page=" . $i . "' class='". $class ."'>";
				$str.= $i . "</a>]</b>&nbsp;";
			}
		}
	}
	else{
		for($i=$start;$i<=$numpage;$i++){
			if($page!=$i){
				$str.= "&nbsp;<a href='" . $link_page . "?page=" . $i . "' class='". $class . "'>";
				$str.= $i . "</a>&nbsp;";
			}else{
				$str.= "&nbsp;<b>[<a href='" . $link_page . "?page=" . $i . "' class='". $class ."'>";
				$str.= $i . "</a>]</b>&nbsp;";
			}
		}
	}
	
	/*if ($page<$numpage)
		$str.="<a href='".$link_page."?page=".($page+1)."'>&nbsp;></a>";
	else
		$str.="<a href='".$link_page."?page=".$page."'>&nbsp;></a>";*/
	
	if ($end<$numpage)
	{
		if ($page<$maxpage)
			$str.="&nbsp;<a href='".$link_page."?page=".($step*$maxpage+1)."'  class='".$class."'>Next</a>";
		else
			$str.="&nbsp;<a href='".$link_page."?page=".($step*$maxpage+$maxpage+1)."'  class='".$class."'>Next</a>";
	}
	else
	{
		$str.="&nbsp;Next";
	}
	return $str;
}


    /*-------------------------------------------------------------------------*/

    

} //end class func


class display_2{

     /*-------------------------------------------------------------------------*/

 function paging( $TotalRows, $RowsInPage, $maxpage, $link_page, $page, $class )
    {
        if ( $TotalRows % $RowsInPage != 0 )
        {
            $numpage = ( integer )( $TotalRows / $RowsInPage ) + 1;
        }
        else
        {
            $numpage = ( integer )( $TotalRows / $RowsInPage );
        }
        $vl = explode( ".", $page / $maxpage );
        $step = $vl[0];
        if ( $step == 0 )
        {
            $start = 1;
            $step = 1;
        }
        else
        {
            $start = $step * $maxpage + 1;
        }
        $end = $start + $maxpage - 1;
        if ( $page < $maxpage )
        {
            $str = "Previous&nbsp";
        }
        else
        {
            if ( $step * $maxpage - $maxpage == 0 )
            {
                $b = 1;
            }
            else
            {
                $b = $step * $maxpage - $maxpage + 1;
            }
            $str = "<a href='".$link_page."&page=".$b."' class='".$class."'>Previous</a>&nbsp;";
        }
        if ( $end < $numpage )
        {
            $i = $start;
            for ( ; $i <= $end; ++$i )
            {
                if ( $page != $i )
                {
                    $str .= "&nbsp;<a href='".$link_page."&page=".$i."' class='".$class."'>";
                    $str .= $i."</a>&nbsp;";
                }
                else
                {
                    $str .= "&nbsp;<b><a href='".$link_page."&page=".$i."' class='".$class."'>[";
                    $str .= $i."]</a></b>&nbsp;";
                }
            }
        }
        else
        {
            $i = $start;
            for ( ; $i <= $numpage; ++$i )
            {
                if ( $page != $i )
                {
                    $str .= "&nbsp;<a href='".$link_page."&page=".$i."' class='".$class."'>";
                    $str .= $i."</a>&nbsp;";
                }
                else
                {
                    $str .= "&nbsp;<b><a href='".$link_page."&page=".$i."' class='".$class."'>[";
                    $str .= $i."]</a></b>&nbsp;";
                }
            }
        }
        if ( $end < $numpage )
        {
            if ( $page < $maxpage )
            {
                $str .= "&nbsp;<a href='".$link_page."&page=".( $step * $maxpage + 1 )."'  class='".$class."'>Next</a>";
                return $str;
            }
            $str .= "&nbsp;<a href='".$link_page."&page=".( $step * $maxpage + $maxpage + 1 )."'  class='".$class."'>Next</a>";
            return $str;
        }
        $str .= "&nbsp;Next";
        return $str;
    }
	
	function specchars_htmlpost( $str )
    {
        $result = str_replace( "'", "&#039;", $str );
        return $result;
    }
	
	function getchar_seourl (){
	global $DB, $lang, $itech, $std;
	$unicode = array();
	$sql2="SELECT * FROM seourl ORDER BY IORDER";	
		$query2=$DB->query($sql2);
		//$num = $DB->get_num_rows();
		//echo "n=".$num;
	
		while($rs2=$DB->fetch_row($query2))
		{
			//echo $rs2['NONUNI']."=>".$rs2['UNI']."<br>";
			$key = $rs2['NONUNI'];
			$unicode[$key] = $rs2['UNI'];
		}
		
		return $unicode;
	
	}
	
	function vn_str_filter ($str){
     /*   
		$unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
       */
	   $unicode = $this->getchar_seourl();

	   foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }	   	  
	
	    //$str = preg_replace( "/(!|\"|#|$|%|'|̣)/", '', $str );
		//$str = preg_replace( "/(̀|́|̉|$|>)/", '', $str );
		
		$str = preg_replace( "'<[\/\!]*?[^<>]*?>'si", "", $str );
		$str = preg_replace( '/(\-)$/', '', $str );
		$str = preg_replace( '/^(\-)/', '', $str );
		
	    $str = preg_replace( "/(&|~|`|@|\^|\*|\(|\+|=|{|}|;|:|,|<|_|)/", '', $str );//-|
		
		$str = str_replace( ")", "", $str );
		$str = str_replace( "|", "", $str );
		$str = str_replace( "\\", "", $str );
		$str = str_replace( "\"", "", $str );
		$str = str_replace( "'", "", $str );
		$str = str_replace( "’", "", $str );
		
		$str = str_replace( "“", "", $str );
		$str = str_replace( "”", "", $str );
		
		$str = str_replace( "[", "", $str );
		$str = str_replace( "]", "", $str );
		$str = str_replace( "?", "", $str );
		$str = str_replace( ".", "", $str );
		$str = str_replace( "$", "", $str );
		$str = str_replace( "#", "", $str );
		$str = str_replace( "%", "", $str );
		$str = str_replace( "!", "", $str );
		$str = str_replace( "/", "", $str );
		//$str = str_replace( "–", "", $str );
   
	   $str = str_replace( " ", "-", $str);
	   $str = str_replace( "------", "-", $str );
	   $str = str_replace( "-----", "-", $str );
	   $str = str_replace( "----", "-", $str );
	   $str = str_replace( "---", "-", $str );
	   $str = str_replace( "--", "-", $str );
	
	 $str = rtrim($str,"-");
	
	 //$str = "xin-chao-cac-ban";
	 
  return $str;
    }
	
	function doikhongdau ($str){
     /*   
		$unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
       */
	   $unicode = $this->getchar_seourl();

	   foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }	   	  
	
	    //$str = preg_replace( "/(!|\"|#|$|%|'|̣)/", '', $str );
		//$str = preg_replace( "/(̀|́|̉|$|>)/", '', $str );
		
		$str = preg_replace( "'<[\/\!]*?[^<>]*?>'si", "", $str );
		$str = preg_replace( '/(\-)$/', '', $str );
		$str = preg_replace( '/^(\-)/', '', $str );
		
	    $str = preg_replace( "/(&|~|`|@|\^|\*|\(|\+|=|{|}|;|:|,|<|_|-|)/", '', $str );
		
		$str = str_replace( ")", "", $str );
		$str = str_replace( "|", "", $str );
		$str = str_replace( "\\", "", $str );
		$str = str_replace( "\"", "", $str );
		$str = str_replace( "'", "", $str );
		$str = str_replace( "’", "", $str );
		
		$str = str_replace( "“", "", $str );
		$str = str_replace( "”", "", $str );
		
		$str = str_replace( "[", "", $str );
		$str = str_replace( "]", "", $str );
		$str = str_replace( "?", "", $str );
		//$str = str_replace( ".", "", $str );
		$str = str_replace( "$", "", $str );
		$str = str_replace( "#", "", $str );
		$str = str_replace( "%", "", $str );
		$str = str_replace( "!", "", $str );
		$str = str_replace( "/", "", $str );
		$str = str_replace( "–", "", $str );
   	   
	   $str = str_replace( "------", " ", $str );
	   $str = str_replace( "-----", " ", $str );
	   $str = str_replace( "----", " ", $str );
	   $str = str_replace( "---", " ", $str );
	   $str = str_replace( "--", " ", $str );
	  // $str = str_replace( " ", "-", $str);
	
	 $str = rtrim($str," ");
	
	 //$str = "xin chao cac ban";
	 
  return $str;
    }
	
	function GetRowMenu($table1, $condit1, $field_id1, $field_name1, $field_name_other1, $temp1, $table2="", $condit2="", $field_id2="", $field_name2="", $field_name_other2="", $temp2="",$pre, $idcat="", $idcatsub="")
{
	global $DB, $lang, $itech, $std, $print;
	$sql1="SELECT * FROM ".$table1." WHERE ".$condit1;	
	$query1=$DB->query($sql1);
	$num1=1;
	$data1 = "";
	$tpl1= new Template($temp1);	  		
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		// get class 1 selected
		if($rs1[$field_id1] == $idcat){
		$cl1_selected = "class='style40'";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='style41'";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);		
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		if(isset($rs1['ID_TYPE'])) $idtype = $rs1['ID_TYPE'];
		
		$data2 = "";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE ID_CAT = '".$rs1['ID_CAT']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;
			$data2 = "";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);
				// get class 2 selected
				if($rs2[$field_id2] == $idcatsub){
					$cl2_selected = "class='title_cam'";
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
				}
				else
				$cl2_selected = "class='title_den'";		
				$tpl2->set('cl2_selected', $cl2_selected);
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_TYPE', $idtype);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

function GetRowMenuPro12($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std,$print;
	$sql1="SELECT * FROM categories WHERE STATUS = 'Active' ORDER BY IORDER";	
	$query1=$DB->query($sql1);
	$num1=1;
	$sbi = 1;
	$data1 = "";
	$temp1 = "menupro_cat_rs";
	$tpl1= new Template($temp1);
	$field_name1 = "NAME_CATEGORY";
	$field_id1 = "ID_CATEGORY";
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		$tpl1->set('sbi', $sbi);// set for sidebarmenui
		$sbi++;
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='menu_c1_slted'";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='menu_c1_noslted'";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);		
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "categories_sub";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_CATEGORY = '".$rs1['ID_CATEGORY']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;
			$data2 = "";
			$temp2 = "menupro_catsub_rs";			
			$field_name2 = "NAME_TYPE";
			$field_id2 = "ID_TYPE";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = "class='menu_c2_slted'";
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "class='menu_c2_noslted'";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_CATEGORY', $rs2['ID_CATEGORY']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";				
				$table3 = "product_categories";
				$condit3 = " WHERE STATUS = 'Active' AND ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_PRO_CAT";
				$field_id3 = "ID_PRO_CAT";
				$queryothers = "&idprocat=";
				
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$data3 = "";					
					$temp3 = "menupro_procat_rs";										
					$tpl3= new Template($temp3);										
					
					while($rs3=$DB->fetch_row($query3))
					{													
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
						
						else
						$cl3_selected = "class='menu_c3_noslted'";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);
						
						if(isset($rs3['ID_CATEGORY']) && isset($rs3['ID_TYPE'])){
						$tpl3->set('ID_CATEGORY', $rs3['ID_CATEGORY']);
						$tpl3->set('ID_TYPE', $rs3['ID_TYPE']);
						}
						else
						$tpl3->set('ID_TYPE', $rs2['ID_TYPE']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}
				
				}
				
				$tpl2->set('list_procat_menu', $data3);				
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

function GetRowMenuPro($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std,$print;
	$sql1="SELECT * FROM categories WHERE STATUS = 'Active' ORDER BY IORDER";	
	$query1=$DB->query($sql1);
	$num1=1;
	$so = 0;
	$data1 = "";
	//$temp1 = "menupro_cat_rs";
	$tpl1= new Template($temp1);
	$field_name1 = "NAME_CATEGORY";
	$field_id1 = "ID_CATEGORY";
	while($rs1=$DB->fetch_row($query1))
	{	
		$so++;
		if($so==1) $cll = " accodion-caption-first";
		else $cll = "";
		$tpl1->set('cll', $cll);
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='slted'";
		$iconsl = "upcat.jpg";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='noslted'";
		$iconsl = "downcat.jpg";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);
		$tpl1->set('iconsl', $iconsl);
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "categories_sub";
		if($table2 !=""){
			// get catsub menu
			// phan bon chuyen dung
			if($rs1['ID_CATEGORY']==7){
			$sql2="SELECT * FROM material_lookup WHERE STATUS = 'Active' ORDER BY IORDER";
			$field_name2 = "NAME";
			$field_id2 = "ID_MT";
			$temp2 = "menupro_catsub_rs_mt";
			}
			else{
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_CATEGORY = '".$rs1['ID_CATEGORY']."' ORDER BY IORDER";	
			$field_name2 = "NAME_TYPE";
			$field_id2 = "ID_TYPE";
			$temp2 = "menupro_catsub_rs";
			}
			$query2=$DB->query($sql2);
			$num2=1;			
			$data2 = "";
						
			
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu				
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = 'class="menu_c2_slted"';
					$bgsl = "images/bgsubcatsl.jpg";					
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "";
				$bgsl = "images/bgsubcat.jpg";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				$tpl2->set('bgsl', $bgsl);				
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_CATEGORY', $rs1['ID_CATEGORY']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";
				$queryothers = "";
				$table3 = "";
				// check ID_TYPE to get list other menu
				// get for: theo chung loai
				//if($rs2['ID_TYPE'] != 2 && $rs2['ID_TYPE'] != 3){
				$table3 = "product_categories";
				$condit3 = " WHERE STATUS = 'Active' AND ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_PRO_CAT";
				$field_id3 = "ID_PRO_CAT";
				$queryothers = "&idprocat=";
				//}
			/*	
				// get for: theo do tuoi
				elseif($rs2['ID_TYPE'] == 2){
				$table3 = "ages_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_AGE";
				$field_id3 = "ID_AGES";
				$queryothers = "&ida=";
				}
				// get for: theo ky nang
				elseif($rs2['ID_TYPE'] == 3){
				$table3 = "skill_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_SKILL";
				$field_id3 = "ID_SKILL";
				$queryothers = "&idsk=";
				}
			*/	
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$checkemty3 = 0;
					$data3 = "";
					//$temp3 = "menupro_procat_rs";								
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						$checkemty3++;
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						//if($rs3[$field_id3] == $idprocat && $rs3['ID_TYPE'] ==1){
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
					/*	
						// chon theo lua tuoi
						elseif($rs3[$field_id3] == $ida && $rs3['ID_TYPE'] ==2){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
						// theo ky nang
						elseif($rs3[$field_id3] == $idsk && $rs3['ID_TYPE'] ==3){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
					*/	
						else
						$cl3_selected = "";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);				
						$tpl3->set('ID_CATEGORY', $rs3['ID_CATEGORY']);
						$tpl3->set('ID_TYPE', $rs3['ID_TYPE']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
								if(isset($rs3[$field_name_other3[$i]]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i]]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}									
				
				}
				// get css cho truong hop co cap 3
				if($checkemty3>0){
					if($cl2_selected !="") $clchon = 'menuitem submenuheader menuitem_selected';
					else $clchon = 'menuitem submenuheader';
					$tpl2->set('has_submenu', $clchon);
				}
				else {
					if($cl2_selected !="") $clchon = 'menuitem menuitem_selected';
					else $clchon = 'menuitem';
					$tpl2->set('has_submenu', $clchon);
				}
				$tpl2->set('list_procat_menu', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

function GetRowMenuPro33($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std,$print;
	$sql1="SELECT * FROM categories WHERE STATUS = 'Active' AND ID_CATEGORY = '".$idc."' ORDER BY IORDER";	
	$query1=$DB->query($sql1);
	$num1=1;
	$data1 = "";
	$temp1 = "menupro_cat_rs";
	$tpl1= new Template($temp1);
	$field_name1 = "NAME_CATEGORY";
	$field_id1 = "ID_CATEGORY";
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='slted'";
		$iconsl = "upcat.jpg";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='noslted'";
		$iconsl = "downcat.jpg";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);
		$tpl1->set('iconsl', $iconsl);
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "categories_sub";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_CATEGORY = '".$rs1['ID_CATEGORY']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;			
			$data2 = "";
			$temp2 = "menupro_catsub_rs";			
			$field_name2 = "NAME_TYPE";
			$field_id2 = "ID_TYPE";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu				
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = 'class="menu_c2_slted"';
					$bgsl = "images/bgsubcatsl.jpg";					
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "";
				$bgsl = "images/bgsubcat.jpg";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				$tpl2->set('bgsl', $bgsl);				
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_CATEGORY', $rs2['ID_CATEGORY']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";
				$queryothers = "";
				$table3 = "";
				// check ID_TYPE to get list other menu
				// get for: theo chung loai
				//if($rs2['ID_TYPE'] != 2 && $rs2['ID_TYPE'] != 3){
				$table3 = "product_categories";
				$condit3 = " WHERE STATUS = 'Active' AND ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_PRO_CAT";
				$field_id3 = "ID_PRO_CAT";
				$queryothers = "&idprocat=";
				//}
			/*	
				// get for: theo do tuoi
				elseif($rs2['ID_TYPE'] == 2){
				$table3 = "ages_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_AGE";
				$field_id3 = "ID_AGES";
				$queryothers = "&ida=";
				}
				// get for: theo ky nang
				elseif($rs2['ID_TYPE'] == 3){
				$table3 = "skill_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_SKILL";
				$field_id3 = "ID_SKILL";
				$queryothers = "&idsk=";
				}
			*/	
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$checkemty3 = 0;
					$data3 = "";
					$temp3 = "menupro_procat_rs";								
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						$checkemty3++;
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						//if($rs3[$field_id3] == $idprocat && $rs3['ID_TYPE'] ==1){
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
					/*	
						// chon theo lua tuoi
						elseif($rs3[$field_id3] == $ida && $rs3['ID_TYPE'] ==2){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
						// theo ky nang
						elseif($rs3[$field_id3] == $idsk && $rs3['ID_TYPE'] ==3){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
					*/	
						else
						$cl3_selected = "";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);				
						$tpl3->set('ID_CATEGORY', $rs3['ID_CATEGORY']);
						$tpl3->set('ID_TYPE', $rs3['ID_TYPE']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
								if(isset($rs3[$field_name_other3[$i]]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i]]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}									
				
				}
				// get css cho truong hop co cap 3
				if($checkemty3>0){
					if($cl2_selected !="") $clchon = 'menuitem submenuheader menuitem_selected';
					else $clchon = 'menuitem submenuheader';
					$tpl2->set('has_submenu', $clchon);
				}
				else {
					if($cl2_selected !="") $clchon = 'menuitem menuitem_selected';
					else $clchon = 'menuitem';
					$tpl2->set('has_submenu', $clchon);
				}
				$tpl2->set('list_procat_menu', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

function GetRowMenuIndus($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std, $common,$print;
	$sql1="SELECT * FROM common_type WHERE ID_TYPE = 5 AND STATUS = 'Active' ORDER BY IORDER";// chi chon industries
	$query1=$DB->query($sql1);
	$num1=1;
	$data1 = "";	
	$tpl1= new Template($temp1);
	$field_name1 = "SNAME";
	$field_id1 = "ID_TYPE";
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='menu_c1_slted'";
		$iconsl = "upcat.jpg";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='noslted'";
		$iconsl = "downcat.jpg";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);
		$tpl1->set('iconsl', $iconsl);
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "common_cat";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_TYPE = '".$rs1['ID_TYPE']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;			
			$data2 = "";				
			$field_name2 = "SNAME";
			$field_id2 = "ID_CAT";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu				
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = 'class="menu_c2_slted"';
					$bgsl = "images/bgsubcatsl.jpg";					
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "";
				$bgsl = "images/bgsubcat.jpg";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				$tpl2->set('bgsl', $bgsl);				
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_TYPE', $rs2['ID_TYPE']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";
				$queryothers = "";
				$table3 = "";
				// check ID_TYPE to get list other menu
				// get for: theo chung loai
				//if($rs2['ID_TYPE'] != 2 && $rs2['ID_TYPE'] != 3){
				$table3 = "common_cat_sub";
				$condit3 = " WHERE STATUS = 'Active' AND ID_CAT = '".$rs2['ID_CAT']."' ORDER BY IORDER";
				$field_name3 = "SNAME";
				$field_id3 = "ID_CAT_SUB";
				$queryothers = "&idcatsub=";
				//}
			
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$checkemty3 = 0;
					$data3 = "";													
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						$checkemty3++;
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						//if($rs3[$field_id3] == $idprocat && $rs3['ID_TYPE'] ==1){
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
					
						else
						$cl3_selected = "";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);
						
						$catinfo = $common->getInfo("common_cat"," ID_CAT = '".$rs3['ID_CAT']."'");						
						$tpl3->set('ID_TYPE', $catinfo['ID_TYPE']);
						$tpl3->set('ID_CAT', $rs3['ID_CAT']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
								if(isset($rs3[$field_name_other3[$i]]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i]]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}									
				
				}
				
				$tpl2->set('list_procat_menu', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

function cleanchar( $string, $num, $specialchars = false )
	{
		$string = htmlspecialchars_decode( $string );

		$len = strlen( $string );
		if( $num and $num < $len )
		{
			if( ord( substr( $string, $num, 1 ) ) == 32 )
			{
				$string = substr( $string, 0, $num ) . ' ...';
			}
			elseif( strpos( $string, ' ' ) === false )
			{
				$string = substr( $string, 0, $num );
			}
			else
			{
				$string = $this->cleanchar( $string, $num - 1 );
			}
		}
		if( $specialchars ) $string = htmlspecialchars( $string );
		return $string;
	}

public function list_row_column_multifield($tb_name, $condition, $field_id, $field_name, $field_name_other, $iorder, $temp_chid, $num_element, $from=0,$to=0, $page=0, $idmnselected="", $pre, $instar="")
    {
		global $DB, $lang, $itech, $std, $common,$print;		
		$data = ""; // de fet template cho status
        $datasub = ""; // de lay du lieu noi cac dong
		$nonedisplay = 'style="display:none"';
        $p = 0; // gia tri khoi dong cho thu tu name1, name2, name3
        $c = 0; // gia tri khoi dong de xem xet cac phan tu con lai 
		$k = 0; // gia tri khoi dong so thu tu de phan biet cac so $p, $c
		$tpl= new Template($temp_chid);
		//set begin init value
		for($kd=1;$kd<=$num_element;$kd++){
			 $tpl->set( "name".$kd, "" );
			 $tpl->set( "ID".$kd, "" );
			 $tpl->set( "link".$kd, "");
			 $tpl->set('star'.$p, "");
			// set for field name other begin
			if(count($field_name_other) > 1){
				for($i=0;$i<count($field_name_other);$i++){
					 $tpl->set($field_name_other[$i].$kd, "");
				}
			}
			if($kd>1){
						$h = $kd-1;
						 $tpl->set( "dis".$h, "");
			}			
        }
		       
				//$list_name = $common->getListRecord($conn,$tb_name,$condition,$iorder,$from,$to);
				if($to>0)
			    $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder." LIMIT ".$from.",".$to;
			    else
			    $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder;
			   
			    //echo $sql;
				$query=$DB->query($sql);
				//$numcount = count($list_name);
				$numcount = @mysqli_num_rows($query);
                $smod = $numcount % $num_element;
					  		
				while($rs=$DB->fetch_row($query))
				{
				
				// Lap de nhan name cua tung status
                    $p += 1;
                    $c += 1;
                    $k += 1;
				// get name 
                 //$resultname = $rs[$field_name.$pre];
				 if($rs[$field_name.$pre]=="")
                 $resultname = $rs[$field_name];
				 else
				 $resultname = $rs[$field_name.$pre];
				 //echo $resultname."<br>";

					// Set name 

                     $tpl->set( "name".$p, $resultname );
					 //$name_link = $this->vn_str_filter($rs[$field_name]);
					 $name_link = $print->getlangvalue($rs,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
					 $tpl->set('name_link'.$p, $name_link);
					 // get menu selected					 
					 if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 $menu_selected = "?".$idmnselected;
					 //echo $menu_selected;
					 $tpl->set('menu_select'.$p, $menu_selected);
					 }
                     $tpl->set( "ck".$p, "ck".$k );
                     $tpl->set( "ID".$p, $rs[$field_id]);
					if(isset($rs['LINK']))
					 $tpl->set( "link".$p, $rs['LINK']);
					// set neu co phan trang
					if ($page)  $tpl->set( "page", "&page=".$page);
					else  $tpl->set( "page", "");
					$tpl->set('star'.$p, "");
					// get star
					if($instar == 1) {					
					$star = $this->getstar($rs[$field_id]);
					$tpl->set('star'.$p, $star);
					
					}
					// if exist image large -> change size image
					if(isset($rs['IMAGE_LARGE'])){
						// set image in products or services, etc,...
						if($tb_name == "products") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['width_list3img'];
							$height = $itech->vars['height_list3img'];
							$wh = $this->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
							$tpl->set( "w".$p, $wh[0]);
							$tpl->set( "h".$p, $wh[1]);
							
							// size	7 images						
							$wi = 123;
							$he = 82;
							$whi = $this->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $wi, $he);
							$tpl->set( "wm".$p, $whi[0]);
							$tpl->set( "hm".$p, $whi[1]);
						}
					}
					elseif(isset($rs['PICTURE'])){
						// set image in products or services, etc,...
						if($tb_name == "categories" || $tb_name == "categories_sub" || $tb_name == "product_categories") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = "imagenews"; // common folder
							$width = $itech->vars['width_list3img'];
							$height = $itech->vars['height_list3img'];
							$wh = $this->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
							 $tpl->set( "w".$p, $wh[0]);
							 $tpl->set( "h".$p, $wh[1]);
						}
						elseif($tb_name == "common") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = "imagenews"; // common folder
							$width = $itech->vars['width_list2img'];
							$height = $itech->vars['height_list2img'];
							$wh = $this->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
							 $tpl->set( "w".$p, $wh[0]);
							 $tpl->set( "h".$p, $wh[1]);
							
							 $contentshort = $this->cleanchar( $rs['SCONTENTSHORT'.$pre], 260, false );
							 $tpl->set( "contentshort".$p, $contentshort); 
						}
					}
					
					if(isset($rs['THUMBNAIL'])){
						// set image in products or services, etc,...
						if($tb_name == "products") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['wthumb'];
							$height = $itech->vars['hthumb'];
							$wh = $this->changesizeimage($rs['THUMBNAIL'], $origfolder, $width, $height);
							 $tpl->set( "wth".$p, $wh[0]);
							 $tpl->set( "hth".$p, $wh[1]);
						}
					}
					
					if(isset($rs['NAME_IMG'])){
						// set image in products or services, etc,...
						if($tb_name == "images_link") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['wthumb_img_link'];
							$height = $itech->vars['hthumb_img_link'];
							$wh = $this->changesizeimage($rs['NAME_IMG'], $origfolder, $width, $height);
							 $tpl->set( "wthumb".$p, $wh[0]);
							 $tpl->set( "hthumb".$p, $wh[1]);
							 // get large img
							$width = $itech->vars['width_detail'];
							$height = $itech->vars['width_detail'];
							$wh = $this->changesizeimage($rs['NAME_IMG'], $origfolder, $width, $height);
							 $tpl->set( "wdt".$p, $wh[0]);
							 $tpl->set( "hdt".$p, $wh[1]);
						}
					}
					
					// $tpl->set("base_url", $base_url);
					// set for none display if element final < num_element
				/*	
					for($ele=1;$ele<$num_element;$ele++){
						 $tpl->set( "dis".$ele, "");
						// $tpl->set( "dis2", "");
					}
				*/
				
					// set for field name other
					if(count($field_name_other) > 1){
						for($i=0;$i<count($field_name_other);$i++){
							if($pre != ""){	
								if(isset($rs[$field_name_other[$i].$pre])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i].$pre]);
								 }
								 elseif(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								 else
								 $tpl->set($field_name_other[$i].$p, "");
							}
							else{							
								if(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								elseif(!isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, "");
								 }
							}
						}
					}									
					
					if(isset($rs['PRICE'])){
						if($rs['PRICE'] > 0)
						$tpl->set("PRICE".$p, number_format($rs['PRICE'],0,"",".")." VN&#272;");
						else
						$tpl->set("PRICE".$p, "Call");
						
					}
					
					if(isset($rs['SALE'])){
						if($rs['SALE'] != "" && $rs['SALE']>0){
						 $tpl->set("PRICE".$p, "<s>".number_format($rs['PRICE'],0,"",".")."</s> VN&#272;");
						 $tpl->set("PRICE_SALE".$p, number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"",".")." VN&#272;");
						 $tpl->set("PRICE_DISC".$p, number_format($rs['PRICE']*$rs['SALE']/100,0,"",".")." VN&#272;");
						}
						else{
						 $tpl->set("PRICE_SALE".$p, "");
						 $tpl->set("PRICE_DISC".$p, "");
						}

					}
					if(isset($rs['ID_PROVINCE'])){
					$nameprovince = $common->getInfo("province","ID_PROVINCE = '".$rs['ID_PROVINCE']."'");
					 $tpl->set('nameprovince'.$p, $nameprovince['NAME']);
					}
				
					// Xem xet truong hop so phan tu hien thi tren 1 dong < tong so phan tu status va smod la chia het
                    if ( $num_element <= $numcount && $smod == 0 )
                    {
							if($p>1){
							$h = $p-1;
							 $tpl->set( "dis".$h, "");
							}	
						// Neu p tang den $num_element thi in ket qua ra 1 dong
							if ( $p == $num_element )
							{
								$datasub .= $tpl->fetch( $temp_chid );
								// set lai p ban dau
								$p = 0;
							}
                    }
					// Xem xet truong hop so phan tu hien thi tren 1 dong < tong so phan tu status va smod la khong chia het
                    elseif ( $num_element < $numcount && $smod != 0 )
                    {
							if($p>1){
							$h = $p-1;
							 $tpl->set( "dis".$h, "");
							}
						// Neu p tang den $num_element thi in ket qua ra 1 dong 
							if ( $p == $num_element )
							{
								$datasub .= $tpl->fetch( $temp_chid );
								// Set tat ca cac value tra ve rong de nhan lai gia tri khac
								for ($x = 1; $x <= $num_element;$x++ )
								{
									 $tpl->set( "name".$x, "" );
									 $tpl->set( "ID".$x, "" );
									 $tpl->set( "link".$x, "");
									 $tpl->set('star'.$x, "");
									
									// set for field name other ve rong
									if(count($field_name_other) > 1){
										for($i=0;$i<count($field_name_other);$i++){
											 $tpl->set($field_name_other[$i].$x, "");
										}
									}
									
								}
								// set lai p ban dau
								$p = 0;
                        	}
						
						// Neu con lai so du 1 hoac n phan tu?  
                        else
                        {
						// chi vao day khi con lai 1 hoac n phan tu va $c chay den cuoi cung: $numcount - $c = 1 
                            //if ( !( $numcount - $c <= $smod ) || !( $p == $smod ) ) cu
							if ($numcount - $c == 0 )
                            {
								/*
								if($smod ==1) {
								 $tpl->set( "dis1", $nonedisplay);
								 $tpl->set( "dis2", $nonedisplay);
								}
								elseif($smod ==2) {
								 $tpl->set( "dis1", "");
								 $tpl->set( "dis2", $nonedisplay);
								}
								*/
								// set for smod = 1 to $num_element-1
								for($v=1;$v<=($num_element-1);$v++){
									if($smod == ($num_element-$v)){
										 $tpl->set( "dis".$smod, $nonedisplay);
										if($v>1){
											for($g=1;$g<$v;$g++){
											$ad = $smod + $g;
											 $tpl->set( "dis".$ad, $nonedisplay);
											}
										}
										// set for element other empty
										if($smod>1){											
											for($t=1;$t<$smod;$t++){
											 $tpl->set( "dis".$t, "");
											}
										}
									}
								
								}								
								
                                $datasub .= $tpl->fetch( $temp_chid );
								
                            }
                        }
						
                    }
					// Danh cho cac truong hop khac: count < element
                    else
                    {
							if ( $p == $numcount ){
								/*
									if($numcount ==1) {
									 $tpl->set( "dis1", $nonedisplay);
									 $tpl->set( "dis2", $nonedisplay);
									}
									elseif($numcount ==2) {
									 $tpl->set( "dis1", "");
									 $tpl->set( "dis2", $nonedisplay);
									}
								*/
								//if($numcount>=1 && $numcount<$num_element){
									for($jj=$numcount;$jj<=($num_element-1);$jj++){
										 $tpl->set( "dis".$jj, $nonedisplay);										
									}

								//}
					     	$datasub .= $tpl->fetch( $temp_chid );
							}
                    }
					
					
        
    }
	return $datasub;
 }
 
 public function list_row_column_multifield_1h($tb_name, $condition, $field_id, $field_name, $field_name_other, $iorder, $temp_chid, $num_element, $from=0,$to=0, $page=0, $idmnselected="", $pre)
    {
		global $DB, $lang, $itech, $std, $common,$print;		
		$data = ""; // de fet template cho status
        $datasub = ""; // de lay du lieu noi cac dong
		$nonedisplay = 'style="display:none"';
        $p = 0; // gia tri khoi dong cho thu tu name1, name2, name3
        $c = 0; // gia tri khoi dong de xem xet cac phan tu con lai 
		$k = 0; // gia tri khoi dong so thu tu de phan biet cac so $p, $c
		$tpl= new Template($temp_chid);
		//set begin init value
		for($kd=1;$kd<=$num_element;$kd++){
			 $tpl->set( "name".$kd, "" );
			 $tpl->set( "ID".$kd, "" );
			 $tpl->set( "link".$kd, "");
			// set for field name other begin
			if(count($field_name_other) > 1){
				for($i=0;$i<count($field_name_other);$i++){
					 $tpl->set($field_name_other[$i].$kd, "");
				}
			}
			if($kd>1){
						$h = $kd-1;
						 $tpl->set( "dis".$h, "");
			}			
        }
		       
				//$list_name = $common->getListRecord($conn,$tb_name,$condition,$iorder,$from,$to);
				if($to>0)
			    $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder." LIMIT ".$from.",".$to;
			    else
			    $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder;
			   
			    //echo $sql;
				$query=$DB->query($sql);
				//$numcount = count($list_name);
				$numcount = @mysqli_num_rows($query);
                $smod = $numcount % $num_element;
					  		
				while($rs=$DB->fetch_row($query))
				{
				
				// Lap de nhan name cua tung status
                    $p += 1;
                    $c += 1;
                    $k += 1;
				// get name 
				 if($rs[$field_name.$pre]=="")
                 $resultname = $rs[$field_name];
				 else
				 $resultname = $rs[$field_name.$pre];
				 //echo $resultname."<br>";

					// Set name 

                     $tpl->set( "name".$p, $resultname );
					 //$name_link = $this->vn_str_filter($rs[$field_name]);
					 $name_link = $print->getlangvalue($rs,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
					 $tpl->set('name_link'.$p, $name_link);
					 // get menu selected					 
					 if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 $menu_selected = "?".$idmnselected;
					 //echo $menu_selected;
					 $tpl->set('menu_select'.$p, $menu_selected);
					 }
                     $tpl->set( "ck".$p, "ck".$k );
                     $tpl->set( "ID".$p, $rs[$field_id]);
					if(isset($rs['LINK']))
					 $tpl->set( "link".$p, $rs['LINK']);
					// set neu co phan trang
					if ($page)  $tpl->set( "page", "&page=".$page);
					else  $tpl->set( "page", "");
					// if exist image large -> change size image
					if(isset($rs['IMAGE_LARGE'])){
						// set image in products or services, etc,...
						if($tb_name == "products") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['width_list1img'];
							$height = $itech->vars['height_list1img'];
							$wh = $this->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
							 $tpl->set( "w1h".$p, $wh[0]);
							 $tpl->set( "h1h".$p, $wh[1]);
						}
					}
					elseif(isset($rs['PICTURE'])){
						// set image in products or services, etc,...
						if($tb_name == "categories" || $tb_name == "categories_sub" || $tb_name == "product_categories") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = "imagenews"; // common folder
							$width = $itech->vars['width_list3img'];
							$height = $itech->vars['height_list3img'];
							$wh = $this->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
							 $tpl->set( "w".$p, $wh[0]);
							 $tpl->set( "h".$p, $wh[1]);
						}
					}
					
					if(isset($rs['THUMBNAIL'])){
						// set image in products or services, etc,...
						if($tb_name == "products") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['wthumb'];
							$height = $itech->vars['hthumb'];
							$wh = $this->changesizeimage($rs['THUMBNAIL'], $origfolder, $width, $height);
							 $tpl->set( "wth".$p, $wh[0]);
							 $tpl->set( "hth".$p, $wh[1]);
						}
					}
					
					if(isset($rs['NAME_IMG'])){
						// set image in products or services, etc,...
						if($tb_name == "images_link") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
							$width = $itech->vars['wthumb_img_link'];
							$height = $itech->vars['hthumb_img_link'];
							$wh = $this->changesizeimage($rs['NAME_IMG'], $origfolder, $width, $height);
							 $tpl->set( "wthumb".$p, $wh[0]);
							 $tpl->set( "hthumb".$p, $wh[1]);
							 // get large img
							$width = $itech->vars['width_detail'];
							$height = $itech->vars['width_detail'];
							$wh = $this->changesizeimage($rs['NAME_IMG'], $origfolder, $width, $height);
							 $tpl->set( "wdt".$p, $wh[0]);
							 $tpl->set( "hdt".$p, $wh[1]);
						}
					}
					
					// $tpl->set("base_url", $base_url);
					// set for none display if element final < num_element
				/*	
					for($ele=1;$ele<$num_element;$ele++){
						 $tpl->set( "dis".$ele, "");
						// $tpl->set( "dis2", "");
					}
				*/
				
					// set for field name other
					if(count($field_name_other) > 1){
						for($i=0;$i<count($field_name_other);$i++){
							if($pre != ""){	
								if(isset($rs[$field_name_other[$i].$pre])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i].$pre]);
								 }
								 elseif(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								 else
								 $tpl->set($field_name_other[$i].$p, "");
							}
							else{							
								if(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								elseif(!isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, "");
								 }
							}
						}
					}									
					
					if(isset($rs['PRICE'])){
						if($rs['PRICE'] > 0)
						$tpl->set("PRICE".$p, number_format($rs['PRICE'],0,"",".")." VN&#272;");
						else
						$tpl->set("PRICE".$p, "Call");
						
					}
					
					if(isset($rs['SALE'])){
						if($rs['SALE'] != "" && $rs['SALE']>0){
						 $tpl->set("PRICE".$p, "<s>".number_format($rs['PRICE'],0,"",".")."</s> VN&#272;");
						 $tpl->set("PRICE_SALE".$p, number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"",".")." VN&#272;");						
						}
						else{
						 $tpl->set("PRICE_SALE".$p, "");
						}

					}
					if(isset($rs['ID_PROVINCE'])){
					$nameprovince = $common->getInfo("province","ID_PROVINCE = '".$rs['ID_PROVINCE']."'");
					 $tpl->set('nameprovince'.$p, $nameprovince['NAME']);
					}
				
					// Xem xet truong hop so phan tu hien thi tren 1 dong < tong so phan tu status va smod la chia het
                    if ( $num_element <= $numcount && $smod == 0 )
                    {
							if($p>1){
							$h = $p-1;
							 $tpl->set( "dis".$h, "");
							}	
						// Neu p tang den $num_element thi in ket qua ra 1 dong
							if ( $p == $num_element )
							{
								$datasub .= $tpl->fetch( $temp_chid );
								// set lai p ban dau
								$p = 0;
							}
                    }
					// Xem xet truong hop so phan tu hien thi tren 1 dong < tong so phan tu status va smod la khong chia het
                    elseif ( $num_element < $numcount && $smod != 0 )
                    {
							if($p>1){
							$h = $p-1;
							 $tpl->set( "dis".$h, "");
							}
						// Neu p tang den $num_element thi in ket qua ra 1 dong 
							if ( $p == $num_element )
							{
								$datasub .= $tpl->fetch( $temp_chid );
								// Set tat ca cac value tra ve rong de nhan lai gia tri khac
								for ($x = 1; $x <= $num_element;$x++ )
								{
									 $tpl->set( "name".$x, "" );
									 $tpl->set( "ID".$x, "" );
									 $tpl->set( "link".$x, "");
									
									// set for field name other ve rong
									if(count($field_name_other) > 1){
										for($i=0;$i<count($field_name_other);$i++){
											 $tpl->set($field_name_other[$i].$x, "");
										}
									}
									
								}
								// set lai p ban dau
								$p = 0;
                        	}
						
						// Neu con lai so du 1 hoac n phan tu?  
                        else
                        {
						// chi vao day khi con lai 1 hoac n phan tu va $c chay den cuoi cung: $numcount - $c = 1 
                            //if ( !( $numcount - $c <= $smod ) || !( $p == $smod ) ) cu
							if ($numcount - $c == 0 )
                            {
								/*
								if($smod ==1) {
								 $tpl->set( "dis1", $nonedisplay);
								 $tpl->set( "dis2", $nonedisplay);
								}
								elseif($smod ==2) {
								 $tpl->set( "dis1", "");
								 $tpl->set( "dis2", $nonedisplay);
								}
								*/
								// set for smod = 1 to $num_element-1
								for($v=1;$v<=($num_element-1);$v++){
									if($smod == ($num_element-$v)){
										 $tpl->set( "dis".$smod, $nonedisplay);
										if($v>1){
											for($g=1;$g<$v;$g++){
											$ad = $smod + $g;
											 $tpl->set( "dis".$ad, $nonedisplay);
											}
										}
										// set for element other empty
										if($smod>1){											
											for($t=1;$t<$smod;$t++){
											 $tpl->set( "dis".$t, "");
											}
										}
									}
								
								}								
								
                                $datasub .= $tpl->fetch( $temp_chid );
								
                            }
                        }
						
                    }
					// Danh cho cac truong hop khac: count < element
                    else
                    {
							if ( $p == $numcount ){
								/*
									if($numcount ==1) {
									 $tpl->set( "dis1", $nonedisplay);
									 $tpl->set( "dis2", $nonedisplay);
									}
									elseif($numcount ==2) {
									 $tpl->set( "dis1", "");
									 $tpl->set( "dis2", $nonedisplay);
									}
								*/
								//if($numcount>=1 && $numcount<$num_element){
									for($jj=$numcount;$jj<=($num_element-1);$jj++){
										 $tpl->set( "dis".$jj, $nonedisplay);										
									}

								//}
					     	$datasub .= $tpl->fetch( $temp_chid );
							}
                    }
					
					
        
    }
	return $datasub;
 }
 
public function list_row_multi_field($tb_name, $condition, $field_id, $field_name, $field_name_other, $iorder, $temp_chid, $from=0,$to=0, $page=0, $idmnselected=0, $pre )
 
    {	
		global $DB, $lang, $itech, $std,$common,$print;
        $datasub = ""; // de lay du lieu noi cac dong
		//$nonedisplay = 'style="display:none"';
						   
			   if($to>0)
			   $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder." LIMIT ".$from.",".$to;
			   else
			   $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder;
			   
			   //echo $sql;
				$query=$DB->query($sql);
				$num=1;
				
				$tpl= new Template($temp_chid);	  		
				while($rs=$DB->fetch_row($query))
				{

				// get name
				 if($rs[$field_name.$pre]=="")
				 $resultname = $rs[$field_name];
				 else
                 $resultname = $rs[$field_name.$pre];

				// Set param
                    $tpl->set( "name", $resultname );
					//$text_title  = $print->getlangvalue($rs,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);	
					//$name_link = $this->vn_str_filter($rs[$field_name]);
					//$name_link = $this->vn_str_filter($text_title);
					$name_link = $print->getlangvalue($rs,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
					$tpl->set('name_link', $name_link);
					$tpl->set( "ID", $rs[$field_id]);
					// set for menu selected
					$tpl->set( "idmenuselected", $idmnselected );
					// set neu co phan trang
					if ($page) $tpl->set( "page", "&page=".$page);
					else $tpl->set( "page", "");
					if(isset($rs['LINK']))
					$tpl->set( "link", $rs['LINK']);
					
					if(isset($rs['PICTURE'])){
						// set image in products or services, etc,...
						if($tb_name == "common") {
							//$origfolder = "$rs['ID_COM']"; // for multilple company
							$origfolder = "imagenews"; // for every category follows id category
							$width = $itech->vars['wthumb'];
							$height = $itech->vars['hthumb'];
							$wh = $this->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
							 $tpl->set( "wth", $wh[0]);
							 $tpl->set( "hth", $wh[1]);
						}
					}
					
					// set for field name other chid
					if(count($field_name_other) > 1){
						for($i=0;$i<count($field_name_other);$i++){
							if($pre != ""){	
								if(isset($rs[$field_name_other[$i].$pre])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i].$pre]);
								 }
								 elseif(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								 else
								 $tpl->set($field_name_other[$i].$p, "");
							}
							else{							
								if(isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, $rs[$field_name_other[$i]]);
								 }
								elseif(!isset($rs[$field_name_other[$i]])){							 
								 $tpl->set($field_name_other[$i].$p, "");
								 }
							}
						}
					}
					
					if(isset($rs['PRICE']))
					$tpl->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");					
					if(isset($rs['SALE'])){
						if($rs['SALE'] != "" && $rs['SALE']>0){
						$tpl->set("PRICE", "<s>".number_format($rs['PRICE'],0,"",".")."</s> VN&#272;");
						$tpl->set("PRICE_SALE", " - ".number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"",".")." VN&#272;");						
						}
						else{
						$tpl->set("PRICE_SALE", "");
						}

					}
					if(isset($rs['ID_PROVINCE'])){
					$nameprovince = $common->getInfo("province","ID_PROVINCE = '".$rs['ID_PROVINCE']."'");
					$tpl->set('nameprovince', $nameprovince['NAME']);
					}
					if(isset($rs['ID_CONSULTANT'])){
					$namecst = $common->getInfo("consultant","ID_CONSULTANT = '".$rs['ID_CONSULTANT']."'");
					$tpl->set('name_user', $namecst['FULL_NAME']);
					}
					
					if(isset($rs['DATE_UPDATED'])){
					
					$tpl->set('dateupdated', $common->datevn($rs['DATE_UPDATED']));
					}
					
					// Get info datasub
					$datasub .= $tpl->fetch( $temp_chid );
	
				}
				
	return $datasub;
 }
	
	//Thumbnail
       // ################### make thumbnail ####################
    public function makethumb ($picname, $pic_small, $origfolder, $width, $height, $quality){
     global $srcWidth, $srcHeight, $destHeight, $destWidth, $filetyp;
	 global $DB, $lang, $itech, $std;	 
		$pathimg = $itech->vars['pathimg'];
		$gdversion = $itech->vars['gdversion'];// GD process images in library PHP 3,4,5
		$flag = true;
          $origpath = $pathimg . "/".$origfolder;
          $dst_x=0; $dst_y=0;

          $this->getdimensions($picname, $origfolder, $width, $height);

         $src_x=0; $src_y=0;

          if ($filetyp == "2" OR $filetyp == "3" OR $filetyp == "1"){
                //try and give GD as much memory as possible
               @ini_set("memory_limit", -1);

               if ($filetyp == 2) { // jpg
			   
                  $srcImage = imagecreatefromjpeg("$origpath/$picname");
                  
                  
             } elseif ($filetyp == 3) { // png
			 
                  $srcImage = imagecreatefrompng("$origpath/$picname");
             } else { // gif
                  $srcImage = imagecreatefromgif("$origpath/$picname");
             }

               if ($srcImage=="") {
					$flag = false;
                    //echo "Unable to create image [$picname].<br />Please contact system administrator.";
                    //exit();
               }

                // Create new image with correct dimensions
               if ($gdversion == 2) {
                    $destImage = imagecreatetruecolor($destWidth, $destHeight);
                    imagecopyresampled($destImage, $srcImage, $dst_x, $dst_y, $src_x, $src_y, $destWidth, $destHeight, $srcWidth, $srcHeight);
               } else { // in case its bad old GD1
                    $destImage = imagecreate($destWidth, $destHeight);
                    imagecopyresized($destImage, $srcImage, $dst_x, $dst_y, $src_x, $src_y, $destWidth, $destHeight, $srcWidth, $srcHeight);
               }

               if ($filetyp == 2) { // jpg
                    imagejpeg($destImage, "$origpath/$pic_small", $quality);// $quality: 75-100 default: 75, 100 -> best
               } elseif ($filetyp == 3) { // png
                    imagepng($destImage, "$origpath/$pic_small");
               } else { // gif
                    imagegif($destImage, "$origpath/$pic_small");
               }

                // Empty memory
               imagedestroy($srcImage);
               imagedestroy($destImage);
           }
          return $flag;
     }
	 
	public function getdimensions ($picname, $origfolder, $width, $height) {
    global $srcWidth, $srcHeight, $destHeight, $destWidth, $filetyp;
	global $DB, $lang, $itech, $std;	
		$pathimg = $itech->vars['pathimg'];	
          $srcSize = @getimagesize($pathimg."/".$origfolder."/".$picname);
          $srcWidth = $srcSize[0];
          $srcHeight = $srcSize[1];
          $filetyp = $srcSize[2];
          // Get image height and width for thumbnail creation
         if ($srcHeight > $height) {
              if (($srcWidth / $srcHeight) * $height > $width) {
                   $destWidth = $width;
                   $destHeight = round(($srcHeight / $srcWidth) * $width);
                   } else {
                   $destHeight = $height;
                   $destWidth = round(($srcWidth / $srcHeight) * $height);
              }
         } elseif ($srcWidth > $width) {
             // Height is OK, but image is to wide
             $destWidth = $width;
             $destHeight = round(($srcHeight / $srcWidth) * $width);
          } else {
             $destWidth = $srcWidth;
             $destHeight = $srcHeight;
         }
          return;
          unset ($height);
          unset ($width);
     }
	 
	 function getkeytime($ktm){
	global $DB, $lang, $itech, $std, $common;
	
	$info_ktm = $common->getInfo("province","ID_PROVINCE = 8");	
	if(hash('sha256',$info_ktm['CODE']) == $ktm) return true;
	else return false;

	}
	
	public function changesizeimage ($picname, $origfolder, $width, $height) {
		global $DB, $lang, $itech, $std;
		$pathimg = $itech->vars['pathimg'];
		//echo $pathimg."/".$origfolder."/".$picname."<br>";
          $srcSize = @getimagesize($pathimg."/".$origfolder."/".$picname);
          $srcWidth = $srcSize[0];
          $srcHeight = $srcSize[1];
          $filetyp = $srcSize[2];
          // Get image height and width 
         if ($srcHeight > $height) {
              if (($srcWidth / $srcHeight) * $height > $width) {
                   $destWidth = $width;
                   $destHeight = round(($srcHeight / $srcWidth) * $width);
                   } else {
                   $destHeight = $height;
                   $destWidth = round(($srcWidth / $srcHeight) * $height);
              }
         } elseif ($srcWidth > $width) {
             // Height is OK, but image is to wide
             $destWidth = $width;
             $destHeight = round(($srcHeight / $srcWidth) * $width);
          } else {
             $destWidth = $srcWidth;
             $destHeight = $srcHeight;
         }
		 
		 $wh[0] = $destWidth;
		 $wh[1] = $destHeight;
         
		 return $wh;
     }
	
 function pagingphp($TotalRows,$RowsInPage,$maxpage, $url, $page, $class_link){
	global $DB, $lang, $itech, $std;
	if ($maxpage) $numfrom = 1;
		else $numfrom = 0;

		$numto = $RowsInPage;
		if ($TotalRows < $numto) $numto = $TotalRows;

		if($page >1){
			$numfrom = ($RowsInPage)*($page-1)+1;
			$numto = ($RowsInPage)*$page;

			if ($TotalRows < $numto) $numto = $TotalRows;
		}

		$arrnumpage=explode('.',$TotalRows/$RowsInPage);
		if ($TotalRows%$RowsInPage!=0)
		$numpage=$arrnumpage[0]+1;
		else
		$numpage=$arrnumpage[0];

		$vl=explode('.',$page/$maxpage);
		//if ($page%$maxpage==0)
		$step=$vl[0];

		if ($step==0)
		{
			$start=1;
			$step=1;
		}
		else
		if ($page%$maxpage==0)
		{
			$start=($step-1)*$maxpage+1;
		}
		else
		{
			$start=$step*$maxpage+1;
		}
		$end=$start+$maxpage-1;

		if ($page>1)
		$str = "<li class='preicon'><a href='$url&page=".($page-1)."' class='".$class_link."' > &lt; </a></li>";
		else
		$str="";
		//Middle
		if ($end<$numpage){
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<li class='numpaging'><span>".$i."</span></li>";

					else
					$str.="<li class='numpaging'><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";
				}
				else{
					if ($i==$page)

					$str.="<li class='numpaging'><span>".$i."</span></li>";

					else
					$str.="<li class='numpaging'><a href=\"$url&page=".$i."\" class='".$class_link."'>".$i."</a></li>";
				}
				$k++;
			}

		}
		else{
			if ($start==1)
			{
				$end=$numpage;
			}
			else
			if ($end-$numpage>0)
			{
				$start=$start-($end-$numpage);
				$end=$numpage;
			}
			else
			if ($end-$start<$maxpage)
			$start=$start-($end-$start);
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<li class='numpaging'><span>".$i."</span></li>";
					else
					$str.="<li class='numpaging'><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";
				}
				else{
					if ($i==$page)

					$str.="<li class='numpaging'><span>".$i."</span></li>";

					else
					$str.="<li class='numpaging'><a href=\"$url&page=".$i."\" class='".$class_link."'>".$i."</a></li>";
				}
					
				$k++;
			}
		}
			
		//Next

		if ($page<$numpage)
		{
			$str.="<li class='nexticon'><a href='$url&page=".($page+1)."' class='".$class_link."'> &gt; </a></li>";
		}
		else
		$str.="";
		return $str;
		//.'<br/>( '.$numfrom.'-'.$numto.' of '.$TotalRows.' records - Page '.$page.' of '.$numpage.' pages)'
	}
	
	function pagingphpA($TotalRows,$RowsInPage,$maxpage, $url, $page, $class_link1){
	global $DB, $lang, $itech, $std;
	$class_link = "active";
	if ($maxpage) $numfrom = 1;
		else $numfrom = 0;

		$numto = $RowsInPage;
		if ($TotalRows < $numto) $numto = $TotalRows;

		if($page >1){
			$numfrom = ($RowsInPage)*($page-1)+1;
			$numto = ($RowsInPage)*$page;

			if ($TotalRows < $numto) $numto = $TotalRows;
		}

		$arrnumpage=explode('.',$TotalRows/$RowsInPage);
		if ($TotalRows%$RowsInPage!=0)
		$numpage=$arrnumpage[0]+1;
		else
		$numpage=$arrnumpage[0];

		$vl=explode('.',$page/$maxpage);
		//if ($page%$maxpage==0)
		$step=$vl[0];

		if ($step==0)
		{
			$start=1;
			$step=1;
		}
		else
		if ($page%$maxpage==0)
		{
			$start=($step-1)*$maxpage+1;
		}
		else
		{
			$start=$step*$maxpage+1;
		}
		$end=$start+$maxpage-1;

		if ($page>1)
		$str="<li><a href='$url&page=".($page-1)."'  > « </a></li>";
		else
		$str="";
		//Middle
		if ($end<$numpage){
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<li><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";

					else
					$str.="<li><a href='$url&page=".$i."' >".$i."</a></li>";
				}
				else{
					if ($i==$page)

					$str.="<li><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";

					else
					$str.="<li><a href='$url&page=".$i."' >".$i."</a></li>";
				}
				$k++;
			}

		}
		else{
			if ($start==1)
			{
				$end=$numpage;
			}
			else
			if ($end-$numpage>0)
			{
				$start=$start-($end-$numpage);
				$end=$numpage;
			}
			else
			if ($end-$start<$maxpage)
			$start=$start-($end-$start);
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<li><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";
					else
					$str.="<li><a href='$url&page=".$i."' >".$i."</a></li>";
				}
				else{
					if ($i==$page)

					$str.="<li><a href='$url&page=".$i."' class='".$class_link."'>".$i."</a></li>";

					else
					$str.="<li><a href='$url&page=".$i."' >".$i."</a></li>";
				}
					
				$k++;
			}
		}
			
		//Next

		if ($page<$numpage)
		{
			$str.="<li><a href='$url&page=".($page+1)."' > » </a></li>";
		}
		else
		$str.="";
		return $str;
		//.'<br/>( '.$numfrom.'-'.$numto.' of '.$TotalRows.' records - Page '.$page.' of '.$numpage.' pages)'
	}
	
	public	function GetDropDown($id = "", $tb_name, $condition = "", $value, $name, $iorder)
    {
	global $DB, $lang, $itech, $std;
        $datasub = "";
		
		if ($condition == "") $condition =1;		
		$sql="SELECT * FROM ".$tb_name." WHERE ".$condition." ORDER BY ".$iorder;
		$query=$DB->query($sql); 										
			
		while ($result=$DB->fetch_row($query))
		{		
			if ( $result[$value] == $id )
            {
                $selected = " selected";
            }
            else
            {
                $selected = "";
            }
            
            $datasub .= "<option value=".$result[$value]." ".$selected.">".$result[$name]."</option>";
            
		}

        return $datasub;
    }
	
	function check_extent_file( $file_name, $extent_file )
    {
        $extent_file = $extent_file;
        if (!preg_match( "/\\.(".$extent_file.")$/", $file_name ) )
        {
            return false;
        }
        return true;
    }
	
	function copy_and_change_filename( $file_input_tmp, $file_input_name, $dir_upload, $prefix )
    {
        $source = $file_input_tmp;
		$name_arr = explode('.', $file_input_name);
		$type = end($name_arr);
		$bname = "";
		for($i=0;$i<count($name_arr)-1;$i++){
			$bname .= $name_arr[$i];
		}
		$file_input_name = $this->vn_str_filter($bname);
		
       // $part = explode( ".", $file_input_name );
        //$file_name = $prefix.".".end($part);//$part[1]
		$file_name = $prefix."_".$file_input_name.".".$type;
        $dest = $dir_upload.$file_name;
        if(copy($source, $dest)) return $file_name;
        else return "";
    }
	
	function copy_and_change_filename_bk( $file_input_tmp, $file_input_name, $dir_upload, $prefix )
    {
        $source = $file_input_tmp;
       // $part = explode( ".", $file_input_name );
        //$file_name = $prefix.".".end($part);//$part[1]
		$file_name = $prefix."_".$file_input_name;
        $dest = $dir_upload.$file_name;
        if(copy($source, $dest)) return $file_name;
        else return "";
    }
	
	public function getvieworderpro($idcom, $dta, $mail_sent=''){
	// dta: 1: for dipslay view shopping cart on email customer else view on web
	// mail_sent = '': not insert code_order, =1 insert or update code_order
	global $DB, $lang, $itech, $std, $common, $print_2,$print;					
			
			// get list products in shopping cart
			if(!isset($_SESSION['shoppingcart']) || $_SESSION['shoppingcart'] == "") $list_idp = "''";
			else $list_idp = $_SESSION['shoppingcart'];
														
				  // Loop idcom get list idp group for every company
				$datamain = "";				  
				if($idcom > 0){
					$condit1 = " p.STATUS = 'Active' AND p.ID_PRODUCT IN (".$list_idp.") AND ID_COM = '".$idcom."'";																
					$sql1="SELECT * FROM products p  WHERE ".$condit1." ORDER BY ID_PRODUCT DESC ";    
					$list_pro1 = $DB->query($sql1);					
					
					$field_name_other = array('ID_PRO_CAT_COM','PRICE','THUMBNAIL','IMAGE_LARGE','DATE_UPDATE', 'ID_PRO_CAT', 'CODE','ID_CATEGORY','ID_TYPE','ID_AGES','ID_SKILL');						
					if($dta == 1){
					$fet_box =  new Template('order_viewcart_list_rs_email');
					}
					else{
					//+++++++++++++++++++ check mobile +++++++++++++++++++++	
					if($_SESSION['dv_Type']=="phone") $tplod = "m_order_viewcart_list_rs";
					else $tplod = "order_viewcart_list_rs";
					$fet_box =  new Template($tplod);
					}
					$datasub = ""; // de lay du lieu noi cac dong
					$stt = 0;
					$totalpay = 0;
					$list_idp_order = "";
					$quatity_list = "";
					
					while ($rs1=$DB->fetch_row($list_pro1))
					{												
						$fet_box->set( "productname", $rs1['PRODUCT_NAME'.$pre]);
						//$name_link = $print_2->vn_str_filter($rs1['PRODUCT_NAME']);
						$name_link = $print->getlangvalue($rs1,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$fet_box->set( "name_link", $name_link);
						$name_query_string = "?idc=".$rs1['ID_CATEGORY']."&idt=".$rs1['ID_TYPE']."&idprocat=".$rs1['ID_PRO_CAT'];
						$fet_box->set( "menu_select", $name_query_string);
						$fet_box->set( "idcom", $rs1['ID_COM']);
						$fet_box->set( "idp", $rs1['ID_PRODUCT']);
						$fet_box->set( "stt", $stt);
						$fet_box->set( "price", number_format($rs1['PRICE'],0,"","."));
						
						// process value quantity in session							
						$binshop = explode(",",$_SESSION['shoppingcart']);
						$binshop_quantity = explode(",",$_SESSION['shoppingcart_quantity']);							
						$keyvl = array_search($rs1['ID_PRODUCT'], $binshop);							
						if(array_key_exists($keyvl, $binshop_quantity))
						$quantityget = $binshop_quantity[$keyvl];
						else $quantityget = 1;
												
						// get update quantity and total money
						$quantity_new = $itech->input['quantity_'.$rs1['ID_PRODUCT']];	
						$quantity_new = "";	
						if($quantity_new=="") $quantity = $quantityget;// get old value
						else{
						$quantity = $quantity_new;
						// use session keep value quantity, update quantity in session shoppingcart_quantity
						$binshop_quantity[$keyvl] = $quantity_new;
						// convert array to string
						$stringtemp = implode (',',$binshop_quantity);
						// update session
						$_SESSION['shoppingcart_quantity'] = $stringtemp;
						}
						//$quantity=($quantity_new=="")?$quantityget:$quantity_new;
						$quantity = (int)$quantity;
						$fet_box->set( "quantity", $quantity);
						
						if($stt == 0) {
						$list_idp_order .= $rs1['ID_PRODUCT'];
						$quatity_list 	.= $quantity;
						}
						else {
						$list_idp_order .= ",".$rs1['ID_PRODUCT'];
						$quatity_list 	.= ",".$quantity;
						}						
						$stt++;	
						$fet_box->set( "stt", $stt);
						// get price orginal
						$totalmoney = $rs1['PRICE']*$quantity;
						// get price has been discount						
						$totalmoney_disc = ($rs1['PRICE']-$rs1['PRICE']*$rs1['SALE']/100)*$quantity;
						
						$fet_box->set( "totalmoney", number_format($totalmoney,0,"","."));
						$fet_box->set( "totalmoney_disc", number_format($totalmoney_disc,0,"","."));
						$fet_box->set("sale", $rs1['SALE']);
						$fet_box->set("base_url", $this->base_url);
						//$postby = $rs1['POST_BY'];// for seller
						if(!isset($_SESSION['ID_CONSULTANT'])) $postby = 0;
						else $postby = @$_SESSION['ID_CONSULTANT'];// buyer
						// set for field name other chid
						if(count($field_name_other) > 0){
							for($i=0;$i<count($field_name_other);$i++){
								if(isset($rs1[$field_name_other[$i]]))
								$fet_box->set($field_name_other[$i], $rs1[$field_name_other[$i]]);
							}
						}
						// Get info datasub
						if($dta == 1)					
						$datasub .= $fet_box->fetch("order_viewcart_list_rs_email");
						else
						$datasub .= $fet_box->fetch($tplod);
						$totalpay += $totalmoney;
						$totalpay_disc += $totalmoney_disc;
													
					}
						// get info company and user incharge group
						$cominfo = $common->getInfo("company","ID_COM = '".$idcom."'");
						$consultantinfo = $common->getInfo("consultant","ID_CONSULTANT = '".$postby."'");
																									
						if($dta == 1){
						// use get list for send mail order list products
							$tpl_temp   =  new Template('order_viewcart_list_email');
						}
						else{
							// use view on web for customer approve
							//+++++++++++++++++++ check mobile +++++++++++++++++++++	
							if($_SESSION['dv_Type']=="phone") {
							$tplodm = "m_order_viewcart_list";							
							}
							else {
							$tplodm = "order_viewcart_list";
							}
							$tpl_temp   =  new Template($tplodm);
							
						}
						
						$tpl_temp->set("comname", $cominfo['COM_NAME']);
						if(isset($_SESSION['sod']) && $_SESSION['sod'] ==1)
						$tpl_temp->set("sod", $_SESSION['sod']);
						else
						$tpl_temp->set("sod", 0);
						$_SESSION['sod'] = 0;// huy bien nay
						$tpl_temp->set("mphone", $_SESSION['dv_Type']);
						$tpl_temp->set("idcom", $idcom);
						$tpl_temp->set("postby", $postby);
						$tpl_temp->set("add", $consultantinfo['ADDRESS']);
						$tpl_temp->set("salename", $consultantinfo['FULL_NAME']);// note prepare for buyer
						$tpl_temp->set("tel", $consultantinfo['TEL']);
						$tpl_temp->set("email", $consultantinfo['EMAIL']);						
						$tpl_temp->set("list_pro_com", $datasub);
						$tpl_temp->set("totalpay", number_format($totalpay,0,"","."));
						$tpl_temp->set("totalpay_disc", number_format($totalpay_disc,0,"","."));
						
						$_SESSION['totalpay'] = $totalpay;
						$_SESSION['totalpay_disc'] = $totalpay_disc;

						// create payment for save database (use when customer request and confirm with ngan luong or bao kim,...)
						// get ip client access
						$ip_access = $this->getRealIpAddr();
						// check conditional for input
						// if not exist payment_order -> insert payment
						// true: insert, update(return idpayment); false: not insert, update; 1: check insert, 2: check update
						//$check_insert = $this->check_inup_payment($idcom, date('Y-m-d'), $list_idp_order, $quatity_list, $ip_access, 1);
						//$check_update = $this->check_inup_payment($idcom, date('Y-m-d'), $list_idp_order, $quatity_list, $ip_access, 2);
						//$check_duplicate = $this->check_inup_payment($idcom, date('Y-m-d'), $list_idp_order, $quatity_list, $ip_access, 3);
				
						//if($check_insert && $mail_sent == 1){
						// every sent is one code order (no need check)
						if($mail_sent == 1){
							try {
								$ArrayData = array( 1 => array(1 => "ID_COM", 2 => $idcom),
										2 => array(1 => "ID_CONSULTANT", 2 => $postby),
										3 => array(1 => "ID_PRODUCT_LIST", 2 => $list_idp_order),
										4 => array(1 => "QUANTITY_LIST", 2 => $quatity_list),
										5 => array(1 => "TOTAL_PAY", 2 => $totalpay),
										6 => array(1 => "IP", 2 => $ip_access),
										7 => array(1 => "CREATED_DATE", 2 => date('Y-m-d H:i:s')),
										8 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s')),
										9 => array(1 => "TOTAL_PAY_DISC", 2 => $totalpay_disc),
									  );
						  
								$idpayment = $common->InsertDB($ArrayData,"payments");
								
								$ArrayData2 = array( 1 => array(1 => "CODE", 2 => "HD_".$idpayment));
								//get ID_PAYMENT update code order					
								$update_condit2 = array( 1 => array(1 => "ID_PAYMENT", 2 => $idpayment));
								$common->UpdateDB($ArrayData2,"payments",$update_condit2);
								
								// create session payment order
								$_SESSION['payment_order'] = "HD_".$idpayment;								

							} catch (Exception $e) {
									echo $e;
								}
						}
	/*					
						elseif($check_update && $mail_sent == 1){
						// update payment
							try {
								$ArrayData2 = array( 1 => array(1 => "ID_PRODUCT_LIST", 2 => $list_idp_order),
										2 => array(1 => "QUANTITY_LIST", 2 => $quatity_list),
										3 => array(1 => "TOTAL_PAY", 2 => $totalpay),										
										4 => array(1 => "UPDATED_DATE", 2 => date('Y-m-d H:i:s'))										
									  );
												
								$update_condit2 = array( 1 => array(1 => "ID_PAYMENT", 2 => $check_update));
								$common->UpdateDB($ArrayData2,"payments",$update_condit2);							
								// create session payment order
								$_SESSION['payment_order'] = "HD_".$check_update;
								$_SESSION['totalpay'] = $totalpay;
							} catch (Exception $e) {
									echo $e;
								}
						}
						else{
						//already existed
							$_SESSION['payment_order'] = "HD_".$check_duplicate;
							$_SESSION['totalpay'] = $totalpay;
						}
						
	*/					
						$tpl_temp->set("code_order", @$_SESSION['payment_order']);

					// fet concat template
					if($dta == 1){
						// use get list for send mail order list products
						$datamain .= $tpl_temp->fetch("order_viewcart_list_email");
					}
					else{
						// use view on web for customer approve
						$datamain .= $tpl_temp->fetch($tplodm);
						
					}
				
					// +++++++++ set cho main 1
										
					if($datamain != ""){
						if($dta == 1){
						return $datamain;						
						}
						else{
						//echo $datamain; // for popup
						return $datamain;
						exit;
						}
					}
					
					else{					
					$tpl_sub   =  new Template('noproduct');
					$text = "Gi&#7887; h&agrave;ng r&#7895;ng!";
					$tpl_sub->set("text", $text);
					$datamain .= $tpl_sub->fetch('noproduct');
					if($dta == 1){
						return $datamain;						
						}
					else{	
						//echo $datamain;
						return $datamain;
						exit; 
					}
					
					}
				}
				else {
				//echo "System error";
				return "System error";
				exit;
				}
	}
	
	public function getRealIpAddr() 
	{    
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet    
		{      
			 $ip=$_SERVER['HTTP_CLIENT_IP'];    
		}     
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy   
		{    
			 $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
		}   
		else   
		{      
			 $ip=$_SERVER['REMOTE_ADDR'];    
		}   
		return $ip; 
	}
	
	public function check_inup_payment($idcom, $curdate, $list_idp_order, $quatity_list, $ip_access, $inup){
	global $DB, $lang, $itech, $std, $common;	
		// check insert
		if($inup == 1){
			$condit = " ID_COM = '".$idcom."' AND CREATED_DATE LIKE '".$curdate."' AND IP = '".$ip_access."'";
			$payinfo = $common->getInfo("payments",$condit);
			if($payinfo == "") return true;// add more
			else return false;// not add more
		}
		// check update
		elseif($inup == 2){
			$condit = " ID_COM = '".$idcom."' AND CREATED_DATE LIKE '".$curdate."' AND IP = '".$ip_access."'  AND (ID_PRODUCT_LIST  <> '".$list_idp_order."' OR QUANTITY_LIST <> '".$quatity_list."') ";
			$payinfo = $common->getInfo("payments",$condit);
			if($payinfo == "") return false;// not update
			else  return $payinfo['ID_PAYMENT'];// allow update
		}
		// check duplicate
		elseif($inup == 3){
			$condit = " ID_COM = '".$idcom."' AND CREATED_DATE LIKE '".$curdate."' AND IP = '".$ip_access."' AND ID_PRODUCT_LIST = '".$list_idp_order."' AND QUANTITY_LIST = '".$quatity_list."' ";
			$payinfo = $common->getInfo("payments",$condit);
			if($payinfo == "") return false;// 
			else return $payinfo['ID_PAYMENT'];// get payment order 
		}
	
	}
	
	function Getsubpro($table2, $condit2, $field_name2, $field_id2, $field_name_other2="", $temp2="", $pre, $idc="", $idt="", $idprocat="")
	{
	global $DB, $lang, $itech, $std;
	$data2 = "";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE ".$condit2;	
			$query2=$DB->query($sql2);
			$num2=1;									
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = "class='title_vang'";
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "class='title_trang'";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);								
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
					}
				}				
				
				$data2 .= $tpl2->fetch($temp2);		
			}
		}
		else $data2 = "";
		
	
	return $data2;
}

//+++++++++++++++++ FOR EMAIL MARKETING +++++++++++++++++++++++++
function get_send_next(){
          global $DB;
         $qr = $DB->query("SELECT * FROM send_next");
         return $DB->fetch_row($qr);
     }

function getlist_emailtemplates($table)
{
	global $DB,$itech,$print_1,$common;
	$sql="SELECT * FROM ".$table." ORDER BY id desc";
	$query=$DB->query($sql);
	$numrow = @mysqli_num_rows( $query );

	if ( $numrow != 0 )
	{
		$RowInPage = 20;
		$y = 1;
		if ( !isset( $itech->input['page'] ) || $itech->input['page'] == "" )
		{
			$pages = 1;
		}
		else
		{
			$pages = $itech->input['page'];
		}
		if ( $RowInPage < $numrow )
		{
		   $data1 = "<tr><td colspan='8'class='news' align=center height=25>".$this->paging( $numrow, $RowInPage, 10, "?act=cm_adm_emailtemplates", $pages, "" )."</td></tr>";
		}
		$num = 1;
		$kq = 0;

	while($row=$DB->fetch_row($query))
	{
		 if ( ( $pages - 1 ) * $RowInPage < $y && $y <= $pages * $RowInPage )
        {
        //    $tpl->set( "page", $pages );
		$nametpl=$row['name'];
		$sent=$row['sent'];
		$condition = "major_id =".$row['emailto']." ";
		$emailto = $this->getnamecat("list_mailmarketing", $condition, "major_name");
		$datepost=$common->datevn($row['datepost']);
		$status= $this->getstatus("status", $row['status']);
		//$emp_id=$row['uid'];
		$tplid=$row['id'];
		
		$control='<a href="?act=cm_adm_emailtemplates&tplid='.$tplid.'&status=3">Active</a> | <a href="?act=cm_adm_emailtemplates&tplid='.$tplid.'&status=2">Inactive</a> | <a href="?act=cm_eadm_emailtemplates&tplid='.$tplid.'">Edit</a> | <a href="#"  onClick="if(confirm(\'Are you sure you want to delete it?\')) window.location = \'?act=cm_adm_emailtemplates&tplid='.$tplid.'&del=1\';">Delete</a>';
		
		//thay dau #=(?act=cm_adm_viewcandidate&id=".$id.")
		$data.="<tr ><td class=\"thay_hr\" height=25 align='left'>".$num."</td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \"><a href='#' onclick=\"window.open('?act=cm_vadm_emailtemplates&tplid=".$tplid."','Viewtpl','scrollbars=yes,status=yes,toolbar=no,location=0,directories=0,status=yes,menubar=0,resizable=1,width=730,height=600');\" class=donho>".$nametpl."</a></p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$emailto."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sent."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$datepost."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$status."</p></td>";
		//$data.="<td class=\"thay_hr\" height=25 align=left>".$joindate."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$control."</td>";
		$data.="</tr>";
	} //end if
	$y++;
    $num++;
	} // end while
	
	 $data .= $data1;
	} // end if numrow	
	return $data;
	
}

function getnamecat( $table, $condition, $field )
    {
        global $DB;
        $sql = "SELECT ".$field." FROM ".$table." WHERE ".$condition;
        $query = $DB->query( $sql );
        if ( $row = $DB->fetch_row( $query ) )
        {
            return $row[$field];
        }
        return "";
    }
	
	function getstatus($table, $id)
{
	global $DB;
	$sql="SELECT * FROM ".$table." WHERE status_id=".$id;
	$query=$DB->query($sql);
	if($row=$DB->fetch_row($query))
	{
		$name=$row['name'];
	}
	return $name;
}

function getlist_emailtemplates_sent($table)
{
	global $DB,$itech,$print_1,$common;
	$sql="SELECT * FROM ".$table." where sent=1 ORDER BY date_send desc, id desc";
	$query=$DB->query($sql);
	$numrow = @mysql_num_rows( $query );
	if ( $numrow != 0 )
	{
		$RowInPage = 20;
		$y = 1;
		if ( !isset( $itech->input['page'] ) || $itech->input['page'] == "" )
		{
			$pages = 1;
		}
		else
		{
			$pages = $itech->input['page'];
		}
		if ( $RowInPage < $numrow )
		{
		   $data1 = "<tr><td colspan='6'class='news' align=center height=25>".$this->paging( $numrow, $RowInPage, 10, "?act=cm_adm_templatessent", $pages, "" )."</td></tr>";
		}
		$num = 1;
		$kq = 0;

	while($row=$DB->fetch_row($query))
	{
		 if ( ( $pages - 1 ) * $RowInPage < $y && $y <= $pages * $RowInPage )
        {
        //    $tpl->set( "page", $pages );
		$nametpl=$row['name'];
		$sent=$row['sent'];
		$date_send = $common->datevn($row['date_send']);
		$condition = "major_id =".$row['emailto']." ";
		$emailto = $this->getnamecat("list_mailmarketing", $condition, "major_name");
		$datepost=$common->datevn($row['datepost']);
		$status= $this->getstatus("status", $row['status']);
		//$emp_id=$row['uid'];
		$tplid=$row['id'];
		
		$control='<a href="?act=cm_eadm_emailtemplates&tplid='.$tplid.'">Edit</a> | <a href="#"  onClick="if(confirm(\'Are you sure you want to delete it?\')) window.location = \'?act=cm_adm_emailtemplates&tplid='.$tplid.'&del=1\';">Delete</a>';
		
		//thay dau #=(?act=cm_adm_viewcandidate&id=".$id.")
		$data.="<tr ><td class=\"thay_hr\" height=25 align='left'>".$num."</td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \"><a href='#' onclick=\"window.open('?act=cm_vadm_emailtemplates&tplid=".$tplid."','Viewtpl','scrollbars=yes,status=yes,toolbar=no,location=0,directories=0,status=yes,menubar=0,resizable=1,width=730,height=600');\" class=donho>".$nametpl."</a></p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$emailto."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sent."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$date_send."</p></td>";
		//$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$status."</p></td>";
		//$data.="<td class=\"thay_hr\" height=25 align=left>".$joindate."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$control."</td>";
		$data.="</tr>";
	} //end if
	$y++;
    $num++;
	} // end while
	
	 $data .= $data1;
	} // end if numrow	
	return $data;
	
}

function getlist_contact($table, $ex=0)
{
	global $DB,$itech,$print_1;
	$sql="SELECT * FROM ".$table." ORDER BY iId desc";
	$query=$DB->query($sql);
	$numrow = @mysql_num_rows( $query );
	if ( $numrow != 0 )
	{
		$RowInPage = 20;
		$y = 1;
		if ( !isset( $itech->input['page'] ) || $itech->input['page'] == "" )
		{
			$pages = 1;
		}
		else
		{
			$pages = $itech->input['page'];
		}
		if ( $RowInPage < $numrow )
		{
		   $data1 = "<tr><td colspan='13'class='news' align=center height=25>".$this->paging( $numrow, $RowInPage, 40, "?act=cm_adm_contact", $pages, "" )."</td></tr>";
		}
		$num = 1;
		$kq = 0;

	while($row=$DB->fetch_row($query))
	{
		 if ( ( $pages - 1 ) * $RowInPage < $y && $y <= $pages * $RowInPage )
        {
        //    $tpl->set( "page", $pages );
		//$sFullname = $row['sFullname'];
		//$sEmail = $row['sEmail'];
		//$sMobile = $row['sMobile'];
		//$sCompanyname = $row['sCompanyname'];
		//$emp_id=$row['uid'];
		$iId=$row['IID'];
		
		$sPrefix = $row['SPREFIX'];
		$sFullname = $row['SFULLNAME'];
		$sType = $row['STYPE'];
		$sCompanyname = $row['SCOMPANYNAME'];
		$sEmail = $row['SEMAIL'];
		$sAddress = $row['SADDRESS'];
		$sMobile= $row['SMOBILE'];
		$sTel = $row['STEL'];
		$sFax = $row['SFAX'];
		$iProvince= $row['IPROVINCE'];
		$sCountry= $row['SCOUNTRY'];
		$sContent= $row['SCONTENT'];
		
		$control='<a href="?act=cm_eadm_contact&iId='.$iId.'">Edit</a> | <a href="?act=cm_adm_contact&iId='.$iId.'&del=1">Delete</a>';
		
		//thay dau #=(?act=cm_adm_viewcandidate&id=".$id.")
	if($ex==0){
		$data.="<tr ><td class=\"thay_hr\" height=25 align='left'>".$num."</td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \"><a href='#' onclick=\"window.open('?act=cm_vadm_contact&iId=".$iId."','ViewContact','scrollbars=yes,status=yes,toolbar=no,location=0,directories=0,status=yes,menubar=0,resizable=1,width=500,height=500');\" class=donho>".$sType."</a></p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sEmail."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sTel."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sCompanyname."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$control."</td>";
		$data.="</tr>";
	}
	//export
	else {
		$data.="<tr ><td class=\"thay_hr\" height=25 align='left'>".$sPrefix."</td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sFullname."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sCompanyname."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align='left'><p style=\"margin-left:5px \">".$sAddress."</p></td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$iProvince."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sCountry."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sTel."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sMobile."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sFax."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sEmail."</td>";
		$data.="<td class=\"thay_hr\" height=25 align=left>".$sContent."</td>";
		$data.="</tr>";
		}
		
	} //end if
	$y++;
    $num++;
	} // end while
	
	 $data .= $data1;
	} // end if numrow	
	return $data;
	
} 	 
	
	function get_detail_emailtpl( $id = 0 )
    {
        global $DB;
        $query = $DB->query( "SELECT * FROM email_templates WHERE id='".$id."';" );
        return $DB->fetch_row( $query );
    }
	
	function content_email($fullname, $data, $content_emailtemplate, $link_unsubcribe, $type_send) {
           global $DB, $print_1,$common;
		   //$type_send de xac dinh uid nam o bang nao de vao unsubcribe
		   //data_sendjob
		  $dataresult = '';
		  $box = & new Template('cm_template_send_newsletter');
          $box->set('datepost', $common->datevn(date('Y-m-d')));
		  $box->set('fullname', $fullname);
		  $box->set('link_unsubcribe', $link_unsubcribe);// ma hoa uid md5
		  $box->set('type_send', $type_send);// Xac dinh table: 1: jobseeker, 2: contact
		  //$box->set('titlemail', $titlemail);
		  $box->set('content_emailtemplate', $content_emailtemplate);
		  $box->set('data', $data);
          $dataresult .= $box->fetch('cm_template_send_newsletter');
		   
		  return $dataresult;
    }
	
	function content_email_all($fullname, $content_emailtemplate, $link_unsubcribe, $type_send) {
           global $DB, $print_1,$common;
		   //$type_send de xac dinh uid nam o bang nao de vao unsubcribe
		   //data_sendjob
		  $dataresult = '';
		  $box = & new Template('cm_template_send_all');
          $box->set('datepost', $common->datevn(date('Y-m-d')));
		  $box->set('fullname', $fullname);
		  $box->set('link_unsubcribe', $link_unsubcribe);// ma hoa uid md5
		  $box->set('type_send', $type_send);// Xac dinh table: 1: jobseeker, 2: contact
		  //$box->set('titlemail', $titlemail);
		  $box->set('content_emailtemplate', $content_emailtemplate);
		  //$box->set('data', $data);
          $dataresult .= $box->fetch('cm_template_send_all');
		   
		  return $dataresult;
    }
	
     /*-------------------------------------------------------------------------*/
	 
	 function GetMN($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std, $common,$print;
	$sql1="SELECT * FROM common_type WHERE STATUS = 'Active' AND MN = 1 ORDER BY IORDER";
	$query1=$DB->query($sql1);
	$num1=0;
	$data1 = "";	
	$tpl1= new Template($temp1);
	$field_name1 = "SNAME";
	$field_id1 = "ID_TYPE";
	while($rs1=$DB->fetch_row($query1))
	{	
		$num1++;
		
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		$tpl1->set('dropdown_name', "dropdown_".$num1);
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='menu_c1_slted'";
		$iconsl = "upcat.jpg";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='noslted'";
		$iconsl = "downcat.jpg";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);
		$tpl1->set('iconsl', $iconsl);
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "common_cat";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_TYPE = '".$rs1['ID_TYPE']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;			
			$data2 = "";				
			$field_name2 = "SNAME";
			$field_id2 = "ID_CAT";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu				
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = 'class="menu_c2_slted"';
					$bgsl = "images/bgsubcatsl.jpg";					
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "";
				$bgsl = "images/bgsubcat.jpg";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				$tpl2->set('bgsl', $bgsl);				
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_TYPE', $rs2['ID_TYPE']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";
				$queryothers = "";
				$table3 = "";
				// check ID_TYPE to get list other menu
				// get for: theo chung loai
				//if($rs2['ID_TYPE'] != 2 && $rs2['ID_TYPE'] != 3){
				$table3 = "common_cat_sub";
				$condit3 = " WHERE STATUS = 'Active' AND ID_CAT = '".$rs2['ID_CAT']."' ORDER BY IORDER";
				$field_name3 = "SNAME";
				$field_id3 = "ID_CAT_SUB";
				$queryothers = "&idcatsub=";
				//}
			
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$checkemty3 = 0;
					$data3 = "";													
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						$checkemty3++;
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('ID_CAT', $rs3['ID_CAT']);						
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						//if($rs3[$field_id3] == $idprocat && $rs3['ID_TYPE'] ==1){
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
					
						else
						$cl3_selected = "";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);
						
						$catinfo = $common->getInfo("common_cat"," ID_CAT = '".$rs3['ID_CAT']."'");						
						$tpl3->set('ID_TYPE', $catinfo['ID_TYPE']);
						$tpl3->set('ID_CAT', $rs3['ID_CAT']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
								if(isset($rs3[$field_name_other3[$i]]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i]]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}									
				
				}
				
				$tpl2->set('list_menu3', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_menu2', $data2);

	
	// add more column
	if($num1 == 1){
		$tpl1->set('col_addmore', "");
	}
	elseif($num1 == 2){
		$col_addmore = $this->get_col_demo(9);// demo san pham
		$tpl1->set('col_addmore', $col_addmore);
	}
	elseif($num1 == 3){
		$col_addmore = $this->get_col_demo(9);// demo san pham
		$tpl1->set('col_addmore', $col_addmore);
	}
	elseif($num1 == 4){
		$col_addmore = $this->get_col_connect();
		$tpl1->set('col_addmore', $col_addmore);
	}
	elseif($num1 == 5){
		$col_addmore = $this->get_col_custom();
		$tpl1->set('col_addmore', $col_addmore);
	}
	elseif($num1 == 6){
		//$col_addmore = $this->get_col_service();
		$col_addmore = $this->get_col_demo(9);// demo san pham
		$tpl1->set('col_addmore', $col_addmore);
	}
	elseif($num1 == 7){
		$col_addmore = $this->get_col_recruit();
		$tpl1->set('col_addmore', $col_addmore);
	}
	else{
		$col_addmore = "";
		$tpl1->set('col_addmore', $col_addmore);
	}
	
	if($num1==1) $temp1 = "list_mn_rs";
	else $temp1 = "list_mn_has_addmore_rs";
	
	$data1 .= $tpl1->fetch($temp1);
	
	}
	
	return $data1;
}

	public function get_col_demo($idtype=0) {
	global $DB, $lang, $itech, $common;	
	
			$pre = $_SESSION['pre'];
			$main = & new Template('col_demo');
			$field_name_other = array('ID_TYPE','STATUS','IORDER');			
			$fet_box = & new Template('col_list_demo_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			
			$condit = " STATUS = 'Active' AND ID_TYPE = '".$idtype."' AND DISP_ONMN=1 ORDER BY IORDER LIMIT 0,3";			
			$sql="SELECT * FROM common_cat  WHERE ".$condit;    
			$query=$DB->query($sql); 										
				
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;										
					$fet_box->set( "ID_CAT", $rs['ID_CAT']);										
					$fet_box->set( "stt", $stt);
					if($rs['SNAME'.$pre] == ""){
					$linkname = $this->vn_str_filter($rs['SNAME']);
					$fet_box->set( "linkname", $linkname);
					$fet_box->set( "titlename", $rs['SNAME']);
					}
					else{
					$linkname = $this->vn_str_filter($rs['SNAME'.$pre]);
					$fet_box->set( "linkname", $linkname);
					$fet_box->set( "titlename", $rs['SNAME'.$pre]);
					}
					
					$fet_box->set( "PICTURE", $rs['PICTURE']);
					$fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
					
					// set neu co phan trang
					if ($page) $fet_box->set( "page", "&page=".$page);
					else $fet_box->set("page", "");								
					
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}																			
					
					// Get info datasub
					$datasub .= $fet_box->fetch("col_list_demo_rs");
	
				}
											
				if($datasub != "")
				$main->set('col_list_demo_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('col_list_demo_rs', $datasub);
				}
				
				$condit0 = " ID_TYPE = '".$idtype."' AND STATUS = 'Active' ";		
				$info_type = $common->getInfo("common_type",$condit0);
				$main->set('name', $info_type['SNAME'.$pre]);
				$main->set('ID_TYPE', $info_type['ID_TYPE']);
				$name_link = $this->vn_str_filter($info_type['SNAME']);
				$main->set( "name_link", $name_link);
	
		return $main->fetch('col_demo');
	}
	
	public function get_col_connect() {
	global $DB, $lang, $itech, $common;	
	
			$pre = $_SESSION['pre'];
			$main = & new Template('col_connect');
			$field_name_other = array('ID_TYPE','STATUS','IORDER');			
			$fet_box = & new Template('col_connect_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;

			// get info detail
			$inf = $common->getInfo("common","ID_COMMON = 64");
			$fet_box->set( "col_connect_rs_detail", base64_decode($inf['SCONTENTS'.$pre]));			
			$datasub = $fet_box->fetch("col_connect_rs");
										
			if($datasub != "")
			$main->set('col_connect_rs', $datasub);
			else{
			$fet_boxno = & new Template('noproduct');
			$text = "";
			$fet_boxno->set("text", $text);
			$datasub .= $fet_boxno->fetch("noproduct");
			$main->set('col_connect_rs', $datasub);
			}								
	
		return $main->fetch('col_connect');
	}
	
	public function get_col_custom() {
	global $DB, $lang, $itech, $common;	
	
			$pre = $_SESSION['pre'];
			$main = & new Template('col_custom');
			$field_name_other = array('ID_TYPE','STATUS','IORDER');			
			$fet_box = & new Template('col_custom_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;

			// get info detail
			$inf = $common->getInfo("common","ID_COMMON = 65");
			$fet_box->set( "col_custom_rs_detail", base64_decode($inf['SCONTENTS'.$pre]));
			$datasub = $fet_box->fetch("col_custom_rs");
										
			if($datasub != "")
			$main->set('col_custom_rs', $datasub);
			else{
			$fet_boxno = & new Template('noproduct');
			$text = "";
			$fet_boxno->set("text", $text);
			$datasub .= $fet_boxno->fetch("noproduct");
			$main->set('col_custom_rs', $datasub);
			}								
	
		return $main->fetch('col_custom');
	}
	
	public function get_col_service() {
	global $DB, $lang, $itech, $common;	
	
			$pre = $_SESSION['pre'];
			$main = & new Template('col_service');
			$field_name_other = array('ID_TYPE','STATUS','IORDER');			
			$fet_box = & new Template('col_service_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;

			// get info detail
			$inf = $common->getInfo("common","ID_COMMON = 66");
			$fet_box->set( "col_service_rs_detail", base64_decode($inf['SCONTENTS'.$pre]));
			$datasub = $fet_box->fetch("col_service_rs");
										
			if($datasub != "")
			$main->set('col_service_rs', $datasub);
			else{
			$fet_boxno = & new Template('noproduct');
			$text = "";
			$fet_boxno->set("text", $text);
			$datasub .= $fet_boxno->fetch("noproduct");
			$main->set('col_service_rs', $datasub);
			}								
	
		return $main->fetch('col_service');
	}
	
	public function get_col_recruit() {
	global $DB, $lang, $itech, $common;	
	
			$pre = $_SESSION['pre'];
			$main = & new Template('col_cruit');
			$field_name_other = array('ID_TYPE','STATUS','IORDER');			
			$fet_box = & new Template('col_cruit_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;

			$fet_box->set("stt", "");
			$datasub = $fet_box->fetch("col_cruit_rs");
										
			if($datasub != "")
			$main->set('col_cruit_rs', $datasub);
			else{
			$fet_boxno = & new Template('noproduct');
			$text = "";
			$fet_boxno->set("text", $text);
			$datasub .= $fet_boxno->fetch("noproduct");
			$main->set('col_cruit_rs', $datasub);
			}								
	
		return $main->fetch('col_cruit');
	}
	
	function GetListMN_right($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std,$print;
	$sql1="SELECT * FROM common_type WHERE STATUS = 'Active' AND ID_TYPE = '".$idc."' ORDER BY IORDER";	
	$query1=$DB->query($sql1);
	$num1=1;
	$data1 = "";
	$temp1 = "menur_type_rs";
	$tpl1= new Template($temp1);
	$field_name1 = "SNAME";
	$field_id1 = "ID_TYPE";
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		//$name_link1 = $this->vn_str_filter($rs1[$field_name1]);
		$name_link1 = $print->getlangvalue($rs1,$field_name1,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
		$tpl1->set('name_link', $name_link1);
		
		// get class 1 selected		
		if($rs1[$field_id1] == $idc){		
		$cl1_selected = "class='slted'";
		$iconsl = "upcat.jpg";
		// controll display catsub
		$controll_display = '';
		// get name link menu selected in session for paging
		$_SESSION['name_link'] = $name_link1;
		}
		else{
		$cl1_selected = "class='noslted'";
		$iconsl = "downcat.jpg";
		$controll_display = 'style="display:none"';
		}
		
		$tpl1->set('cl1_selected', $cl1_selected);
		$tpl1->set('iconsl', $iconsl);
		
		// set for field name other
		if(count($field_name_other1) > 1){
			for($i=0;$i<count($field_name_other1);$i++){
				if(isset($rs1[$field_name_other1[$i].$pre]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i].$pre]);
				elseif(isset($rs1[$field_name_other1[$i]]))
				$tpl1->set($field_name_other1[$i], $rs1[$field_name_other1[$i]]);
			}
		}
		
		//if(isset($rs1['ID_CATEGORY'])) $idc = $rs1['ID_CATEGORY'];
		
		$data2 = "";
		$table2 = "common_cat";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_TYPE = '".$rs1['ID_TYPE']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;			
			$data2 = "";
			$temp2 = "menur_cat_rs";			
			$field_name2 = "SNAME";
			$field_id2 = "ID_CAT";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu				
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				//$name_link2 = $this->vn_str_filter($rs2[$field_name2]);
				$name_link2 = $print->getlangvalue($rs2,$field_name2,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
				$tpl2->set('name_link', $name_link2);				
				// get class 2 selected
				if($rs2[$field_id2] == $idt){				
					$cl2_selected = 'class="menu_c2_slted"';
					$bgsl = "images/bgsubcatsl.jpg";					
					// get name link menu selected in session for paging
					$_SESSION['name_link'] = $name_link2;
					// controll display procat					
				}
				else{
				$cl2_selected = "";
				$bgsl = "images/bgsubcat.jpg";				
				}
				$tpl2->set('cl2_selected', $cl2_selected);
				$tpl2->set('bgsl', $bgsl);				
				// controll display catsub
				$tpl2->set('controll_display', $controll_display);				
				$tpl2->set('ID_TYPE', $rs2['ID_TYPE']);
				
				// set for field name other
				if(count($field_name_other2) > 1){
					for($i=0;$i<count($field_name_other2);$i++){
						if(isset($rs2[$field_name_other2[$i].$pre]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
						elseif(isset($rs2[$field_name_other2[$i]]))
						$tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i]]);
					}
				}
				
				//if(isset($rs2['ID_CATEGORY'])) $idc = $rs2['ID_CATEGORY'];				
				//if(isset($rs2['ID_TYPE'])) $idt = $rs2['ID_TYPE'];
		
				$data3 = "";
				$queryothers = "";
				$table3 = "";
				// check ID_TYPE to get list other menu
				// get for: theo chung loai
				//if($rs2['ID_TYPE'] != 2 && $rs2['ID_TYPE'] != 3){
				$table3 = "common_cat_sub";
				$condit3 = " WHERE STATUS = 'Active' AND ID_CAT = '".$rs2['ID_CAT']."' ORDER BY IORDER";
				$field_name3 = "SNAME";
				$field_id3 = "ID_CAT_SUB";
				$queryothers = "&idcatsub=";
				//}
			/*	
				// get for: theo do tuoi
				elseif($rs2['ID_TYPE'] == 2){
				$table3 = "ages_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_AGE";
				$field_id3 = "ID_AGES";
				$queryothers = "&ida=";
				}
				// get for: theo ky nang
				elseif($rs2['ID_TYPE'] == 3){
				$table3 = "skill_type";
				$condit3 = " WHERE ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_TYPE_SKILL";
				$field_id3 = "ID_SKILL";
				$queryothers = "&idsk=";
				}
			*/	
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$checkemty3 = 0;
					$data3 = "";
					$temp3 = "menur_subcat_rs";								
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						$checkemty3++;
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						//$name_link3 = $this->vn_str_filter($rs3[$field_name3]);
						$name_link3 = $print->getlangvalue($rs3,$field_name3,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
						$tpl3->set('name_link', $name_link3);
						// get class 3 selected
						// Chon theo chung loai
						//if($rs3[$field_id3] == $idprocat && $rs3['ID_TYPE'] ==1){
						if($rs3[$field_id3] == $idprocat){
							$cl3_selected = "class='menu_c3_slted'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
							
						}
					/*	
						// chon theo lua tuoi
						elseif($rs3[$field_id3] == $ida && $rs3['ID_TYPE'] ==2){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
						// theo ky nang
						elseif($rs3[$field_id3] == $idsk && $rs3['ID_TYPE'] ==3){
							$cl3_selected = "class='title_x'";
							// get name link menu selected in session for paging
							$_SESSION['name_link'] = $name_link3;
						}
					*/	
						else
						$cl3_selected = "";		
						$tpl3->set('cl3_selected', $cl3_selected);
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);				
						$tpl3->set('controll_display', $controll_display);				
						$tpl3->set('ID_TYPE', $idc);
						$tpl3->set('ID_CAT', $rs3['ID_CAT']);
						
						// set for field name other
						if(count($field_name_other3) > 1){
							for($i=0;$i<count($field_name_other3);$i++){
								if(isset($rs3[$field_name_other3[$i].$pre]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i].$pre]);
								if(isset($rs3[$field_name_other3[$i]]))
								$tpl3->set($field_name_other3[$i], $rs3[$field_name_other3[$i]]);
							}
						}
						
						$data3 .= $tpl3->fetch($temp3);		
					}									
				
				}
				// get css cho truong hop co cap 3
				if($checkemty3>0){
					if($cl2_selected !="") $clchon = 'menuitem submenuheader menuitem_selected';
					else $clchon = 'menuitem submenuheader';
					$tpl2->set('has_submenu', $clchon);
				}
				else {
					if($cl2_selected !="") $clchon = 'menuitem menuitem_selected';
					else $clchon = 'menuitem';
					$tpl2->set('has_submenu', $clchon);
				}
				$tpl2->set('list_procat_menu', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
}

	public function list_row_column_notable( $listdata, $offset, $end, $numcount, $page, $temp_chid, $num_element, $base_url, $idcom, $thumuc="imagenews", $fnum=1)
    {
	global $DB, $lang, $itech, $std, $print, $common;
			
		$fet_box = & new Template($temp_chid);
        $data = ""; // de fet template cho status
        $datasub = ""; // de lay du lieu noi cac dong
		$nonedisplay = 'style="display:none"';
        $p = 0; 
        $c = 0; 
		$k = 0; 
		$totalsize = 0;
		$getsize_file = 0;
		$pathimg = $itech->vars['pathimg'];
		//$dir_upload = $pathimg."/imagenews/";		
		
		//set begin init value
		for($kd=1;$kd<=$num_element;$kd++){
			$fet_box->set( "name".$kd, "" );
			$fet_box->set( "ID".$kd, "" );
			//$fet_box->set( "link".$kd, "");			
			if($kd>1){
						$h = $kd-1;
						$fet_box->set( "dis".$h, "");
			}			
        }
		       
				//$list_name = $common->getListRecord($conn,$tb_name,$condition,$iorder,$from,$to);
				//$numcount = count($list_name);
                $smod = $numcount % $num_element;

              // foreach ($list_name as $rs){
			  //for ($i=0;$i<$numcount;$i++){
	for ($i=$offset; $i<$end; $i++){
				// Lap de nhan name cua tung status
                    $p += 1;
                    $c += 1;
                    $k += 1;
					$getsize_file = 0;
				// get name 
                 $resultname = $listdata[$i];
				 $name_arr = explode('.', $resultname);
				 $type = end($name_arr);
				 if($type == 'doc' || $type == 'docx' || $type == 'DOC' || $type == 'DOCX')
				 $fet_box->set( "imgfile".$p, 'doc.png' );
				 elseif($type == 'xls' || $type == 'xlsx' || $type == 'XLS' || $type == 'XLSX')
				 $fet_box->set( "imgfile".$p, 'xls.png' );
				 elseif($type == 'pdf' || $type == 'PDF')
				 $fet_box->set( "imgfile".$p, 'pdf.png' );
				 elseif($type == 'ppt' || $type == 'pptx' || $type == 'PPT' || $type == 'PPTX')
				 $fet_box->set( "imgfile".$p, 'ppt.png' );				 
				 elseif($type == 'mp3' || $type == 'MP3' || $type == 'wmv' || $type == 'WMV' || $type == 'wav' || $type == 'WAV' || $type == 'wma' || $type == 'WMA')
				 $fet_box->set( "imgfile".$p, 'audio.png' );
				 elseif($type == 'mp4' || $type == 'MP4' || $type == 'avi' || $type == 'AVI' || $type == 'mpg' || $type == 'MPG' || $type == 'mpeg' || $type == 'MPEG')
				 $fet_box->set( "imgfile".$p, 'video.png' );
				 else
				 $fet_box->set( "imgfile".$p, 'fileimg.png' );
		
					// Set name 
                    $fet_box->set( "name".$p, $resultname );
					$fet_box->set( "thumuc", $thumuc );
					$fet_box->set( "fnum", $fnum );
                    $fet_box->set( "ck".$p, "ck".$k );
                    $fet_box->set( "ID".$p, $i);					
					if ($page){
					$fet_box->set( "page", "&page=".$page);
					$fet_box->set( "pagejs", $page);
					}
					else {
					$fet_box->set( "page", "");
					$fet_box->set( "pagejs", 0);
					}
					// if exist image large -> change size image					
						// set image in products or services, etc,...						
					$origfolder = $thumuc;
					$width = 190;
					$height = 220;
					$wh = $this->changesizeimage($resultname, $origfolder, $width, $height);
					$fet_box->set( "w".$p, $wh[0]);
					$fet_box->set( "h".$p, $wh[1]);										
					
					$fet_box->set("base_url", $base_url);
					$fet_box->set( "ID_COM".$p, $idcom );
					
                    if ( $num_element <= $numcount && $smod == 0 )
                    {
							if($p>1){
							$h = $p-1;
							$fet_box->set( "dis".$h, "");
							}							
							if ( $p == $num_element )
							{
								$datasub .= $fet_box->fetch( $temp_chid );
								// set lai p ban dau
								$p = 0;
							}
                    }
					
                    elseif ( $num_element < $numcount && $smod != 0 )
                    {
							if($p>1){
							$h = $p-1;
							$fet_box->set( "dis".$h, "");
							}
						
							if ( $p == $num_element )
							{
								$datasub .= $fet_box->fetch( $temp_chid );
								
								for ($x = 1; $x <= $num_element;$x++ )
								{
									$fet_box->set( "name".$x, "" );
									$fet_box->set( "ID".$x, "" );
									//$fet_box->set( "link".$x, "");
									
								}
								
								$p = 0;
                        	}
						
						
                        else
                        {
						
                            //if ( !( $numcount - $c <= $smod ) || !( $p == $smod ) ) cu
							if ($numcount - $c == 0 )
                            {								
								// set for smod = 1 to $num_element-1
								for($v=1;$v<=($num_element-1);$v++){
									if($smod == ($num_element-$v)){
										$fet_box->set( "dis".$smod, $nonedisplay);
										if($v>1){
											for($g=1;$g<$v;$g++){
											$ad = $smod + $g;
											$fet_box->set( "dis".$ad, $nonedisplay);
											}
										}
										// set for element other empty
										if($smod>1){											
											for($t=1;$t<$smod;$t++){
											$fet_box->set( "dis".$t, "");
											}
										}
									}
								
								}								
								
                                $datasub .= $fet_box->fetch( $temp_chid );
								
                            }
                        }
						
                    }
					
                    else
                    {
							if ( $p == $numcount ){								
								//if($numcount>=1 && $numcount<$num_element){
									for($jj=$numcount;$jj<=($num_element-1);$jj++){
										$fet_box->set( "dis".$jj, $nonedisplay);										
									}

								//}
					     	$datasub .= $fet_box->fetch( $temp_chid );
							}
                    }										
        
    }
	
	return $datasub;
 }
 
  public function pagingjs($TotalRows,$RowsInPage,$maxpage,$page,$jsname, $class_link)
	{
		if ($maxpage) $numfrom = 1;
		else $numfrom = 0;

		$numto = $RowsInPage;
		if ($TotalRows < $numto) $numto = $TotalRows;

		if($page >1){
			$numfrom = ($RowsInPage)*($page-1)+1;
			$numto = ($RowsInPage)*$page;

			if ($TotalRows < $numto) $numto = $TotalRows;
		}

		$arrnumpage=explode('.',$TotalRows/$RowsInPage);
		if ($TotalRows%$RowsInPage!=0)
			$numpage=$arrnumpage[0]+1;
		else
			$numpage=$arrnumpage[0];

		$vl=explode('.',$page/$maxpage);
		//if ($page%$maxpage==0)
		$step=$vl[0];

		if ($step==0)
		{
			$start=1;
			$step=1;
		}
		else
		if ($page%$maxpage==0)
		{
			$start=($step-1)*$maxpage+1;
		}
		else
		{
			$start=$step*$maxpage+1;
		}
		$end=$start+$maxpage-1;

		if ($page>1)
		$str="<a class='".$class_link."' href='#' onClick=\"".$jsname."(".($page-1).");\">Tr&#432;&#7899;c</a> &nbsp;";
		else
		$str="<span style='font-family: arial, verdana, helvetica; font-size: 13px; font-weight: bold;'>Tr&#432;&#7899;c &nbsp;</span>";
		//Middle
		if ($end<$numpage){
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<b>".$i.'</b>';

					else
					$str.="<a class='".$class_link."' href='#' onClick=\"".$jsname."(".$i.");\">".$i.'</a>';
				}
				else{
					if ($i==$page)

					$str.=" | <b>".$i.'</b>';

					else
					$str.=" | <a class='".$class_link."' href=\"#\" onClick=\"".$jsname."(".$i.");\">".$i.'</a>';
				}
				$k++;
			}

		}
		else{
			if ($start==1)
			{
				$end=$numpage;
			}
			else
			if ($end-$numpage>0)
			{
				$start=$start-($end-$numpage);
				$end=$numpage;
			}
			else
			if ($end-$start<$maxpage)
			$start=$start-($end-$start);
			$k=0;
			for($i=$start;$i<=$end;$i++){
				if ($k==0)
				{
					if ($i==$page)

					$str.="<b>".$i.'</b>';
					else
					$str.="<a class='".$class_link."' href='#' onClick=\"".$jsname."(".$i.");\">".$i.'</a>';
				}
				else{
					if ($i==$page)

					$str.=" | <b>".$i.'</b>';

					else
					$str.=" | <a class='".$class_link."' href=\"#\" onClick=\"".$jsname."(".$i.");\">".$i.'</a>';
				}
					
				$k++;
			}
		}
		//Next
		if ($page<$numpage)
		{
			$str.=" &nbsp;<a class='".$class_link."' href='#' onClick=\"".$jsname."(".($page+1).");\">Sau</a>";
		}
		else
		$str.=" <span style='font-family: arial, verdana, helvetica; font-size: 13px; font-weight: bold;'>&nbsp;Sau</span>";
		return $str;//."<br/><span style='font-family: arial, verdana, helvetica; font-size: 13px; font-weight: bold;'>( ".$numfrom."-".$numto." c&#7911;a ".$TotalRows." k&#7871;t qu&#7843;)</span>";
	}
	
	public function list_mtrial($tb_name, $condition, $field_id, $field_name, $field_name_other, $iorder, $temp_chid, $from=0,$to=0, $page=0, $idmnselected=0, $pre, $list_cked )
 
    {	
		global $DB, $lang, $itech, $std,$common;
        $datasub = ""; // de lay du lieu noi cac dong
		//$nonedisplay = 'style="display:none"';
						   
			   if($to>0)
			   $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder." LIMIT ".$from.",".$to;
			   else
			   $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder;
			   
			   //echo $sql;
				$query=$DB->query($sql);
				$num=1;
				
				$tpl= new Template($temp_chid);	  		
				while($rs=$DB->fetch_row($query))
				{

				// get name 
                 $resultname = $rs[$field_name.$pre];

				// Set param
                    $tpl->set( "name", $resultname );
					$name_link = $this->vn_str_filter($resultname);
					$tpl->set('name_link', $name_link);
					$tpl->set( "ID", $rs[$field_id]);
					// check check box checked
					if(in_array($rs[$field_id], $list_cked))
					$tpl->set('cked', "checked");
					else $tpl->set('cked', "");
					
					// set for menu selected
					$tpl->set( "idmenuselected", $idmnselected );
					// set neu co phan trang
					if ($page) $tpl->set( "page", "&page=".$page);
					else $tpl->set( "page", "");
					if(isset($rs['LINK']))
					$tpl->set( "link", $rs['LINK']);					
					
					// set for field name other chid
					if(count($field_name_other) > 1){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$tpl->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}
					
					if(isset($rs['PRICE']))
					$tpl->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");					
					if(isset($rs['SALE'])){
						if($rs['SALE'] != "" && $rs['SALE']>0){
						$tpl->set("PRICE", "<s>".number_format($rs['PRICE'],0,"",".")."</s> VN&#272;");
						$tpl->set("PRICE_SALE", " - ".number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"",".")." VN&#272;");						
						}
						else{
						$tpl->set("PRICE_SALE", "");
						}

					}
					if(isset($rs['ID_PROVINCE'])){
					$nameprovince = $common->getInfo("province","ID_PROVINCE = '".$rs['ID_PROVINCE']."'");
					$tpl->set('nameprovince', $nameprovince['NAME']);
					}
					if(isset($rs['ID_CONSULTANT'])){
					$namecst = $common->getInfo("consultant","ID_CONSULTANT = '".$rs['ID_CONSULTANT']."'");
					$tpl->set('name_user', $namecst['FULL_NAME']);
					}
					
					// Get info datasub
					$datasub .= $tpl->fetch( $temp_chid );
	
				}
				
	return $datasub;
 }
 
 public function getstar($idp) {
	global $DB, $lang, $itech, $common;	
			$stt =0;
			$t0 =0;$t1 =0;$t2 =0;$t3 =0;$t4 =0;$t5 =0;
			$tt0 =0;$tt1 =0;$tt2 =0;$tt3 =0;$tt4 =0;$tt5 =0;
			
			$condit = " STATUS = 'Active' AND ID_PRODUCT = '".$idp."' ORDER BY ID_COMMENT ";
			$sql="SELECT * FROM comments  WHERE ".$condit;    
			$query=$DB->query($sql);			
			$fet_sub = & new Template('star_rs');
			$datasub = ""; // de lay du lieu noi cac dong
				
			while ($rs=$DB->fetch_row($query))
			{								                   																	
				// check star
				if($rs['COUNT_STAR']==0) {
				$t0 += 1;
				$tt0 = 0;
				}
				elseif($rs['COUNT_STAR']==1) {
				$t1 += 1;
				$tt1 = $t1;
				}
				elseif($rs['COUNT_STAR']==2) {
				$t2 += 1;
				$tt2 = $t2*2;
				}
				elseif($rs['COUNT_STAR']==3) {
				$t3 += 1;
				$tt3 = $t3*3;
				}
				elseif($rs['COUNT_STAR']==4) {
				$t4 += 1;
				$tt4 = $t4*4;
				}
				elseif($rs['COUNT_STAR']==5) {
				$t5 += 1;
				$tt5 = $t5*5;
				}
				
			}
			
			$ttstar = $tt0 + $tt1 + $tt2 + $tt3 + $tt4 + $tt5;
			$ttuser = $t0 + $t1 + $t2 + $t3 + $t4 + $t5;
			if($ttuser == 0) $ttuser =1;
			$gstar = round($ttstar/$ttuser);
			switch ($gstar){
				case 0: $fet_sub->set( "st1", "");$fet_sub->set( "st2", "");$fet_sub->set( "st3", "");$fet_sub->set( "st4", "");$fet_sub->set( "st5", "");
				break;
				case 1: $fet_sub->set( "st1", " vote");$fet_sub->set( "st2", "");$fet_sub->set( "st3", "");$fet_sub->set( "st4", "");$fet_sub->set( "st5", "");
				break;
				case 2: $fet_sub->set( "st1", " vote");$fet_sub->set( "st2", " vote");$fet_sub->set( "st3", "");$fet_sub->set( "st4", "");$fet_sub->set( "st5", "");
				break;		
				case 3: $fet_sub->set( "st1", " vote");$fet_sub->set( "st2", " vote");$fet_sub->set( "st3", " vote");$fet_sub->set( "st4", "");$fet_sub->set( "st5", "");
				break;
				case 4: $fet_sub->set( "st1", " vote");$fet_sub->set( "st2", " vote");$fet_sub->set( "st3", " vote");$fet_sub->set( "st4", " vote");$fet_sub->set( "st5", "");
				break;
				case 5: $fet_sub->set( "st1", " vote");$fet_sub->set( "st2", " vote");$fet_sub->set( "st3", " vote");$fet_sub->set( "st4", " vote");$fet_sub->set( "st5", " vote");
				break;
			}
			
			$datasub .= $fet_sub->fetch("star_rs");

			return $datasub;

	}
	public function get_catsub($pre) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
			$tpl = 'list_procat'; 
			$tplsub = 'list_procat_rs';
			$main = & new Template($tpl);
			$field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
			$fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$condit = " STATUS = 'Active'";
			$sql="SELECT * FROM categories  WHERE ".$condit." ORDER BY IORDER ";
			$query=$DB->query($sql); 
			while ($rs=$DB->fetch_row($query))
			{								                   				
					$stt++;										
					//$fet_box->set( "idt", $rs['ID_TYPE']);
					$fet_box->set( "idcat", $rs['ID_CATEGORY']);
					if($rs['SNAME'.$pre]==""){
					$linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY']);
					$fet_box->set( "titlename", $rs['NAME_CATEGORY']);
					}
					else{
					$linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY'.$pre]);
					$fet_box->set( "titlename", $rs['NAME_CATEGORY'.$pre]);
					}
					$fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
					$fet_box->set( "picture", $rs['PICTURE']);
					$fet_box->set( "linkname", $linkname);
					$fet_box->set( "stt", $stt);															

					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}																			
					
					// Get info datasub
					$datasub .= $fet_box->fetch($tplsub);
	
				}
							
				if($datasub != "")
				$main->set('list_procat_rs', $datasub);
				else{
				$fet_boxno = & new Template('noproduct');
				$text = "";
				$fet_boxno->set("text", $text);
				$datasub .= $fet_boxno->fetch("noproduct");
				$main->set('list_procat_rs', $datasub);
				}				
	
		return $main->fetch($tpl);
	}

}

?>
