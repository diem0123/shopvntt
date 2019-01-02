<?php
/*
+--------------------------------------------------------------------------
|   Focus Consulting
|   ========================================
|   by Le Minh Than 
|      
|   ========================================
|   Web: http://www.focusconsulting.com.vn
|   Time: Friday, October 01, 2010 
|   Email: htktinternet@yahoo.com
+---------------------------------------------------------------------------
*/ 
// kiem tra logged in
	if(isset($_SESSION['uidadm'])){
		$sql = "SELECT * FROM ".$lang['tbl_jobseeker']." WHERE uid='".$_SESSION['uidadm']."'";
		$query = $DB->query( $sql );
		if ($row = $DB->fetch_row( $query ) )
		{
			$fullname=$row['fullname'];
		}
	}
      
     //header
   
      $header = & new Template('header_home');
      $header->set('title', $INFO['site_name']);
	  $header->set('fullname', $fullname);
      
     //main
       $main = & new Template('main');
      
       //************************************************ Doi tuong oj5
     
     //set for template
	/* 
     $arrtemplate5 = $print_1->arraytemp ('main1', 'main1_1', 'mainvar1_1'); // danh sach template
     $object_num5 = array('home', 'main', ''); // Ten file, vi tri de chon title?
     $main->set('main1', $print_1->ShowTemplatehome($arrtemplate5, $object_num5, 3, 'home')); 
	 */
	 
	  $tempres=& new Template('main1');
	  $ContentShort = $print_2->GetQuote($lang['tbl_common']);		
	  $tempres->set('quote', $ContentShort); // 6

	  $main->set('main1', $tempres); 
         
     $main_temp = & new Template('mainsub2');
	 
	 // terms
     // set for query
     $tb8 = $lang['tbl_common'];// Lay ten table;
     $field8 = '*'; // chon cac field // chon cac field (chon lai it field de cho nhanh )
     $condition8 = ' where iIdCommon =12 and iStatus=1';
    // $order8 = ' order by iIdCommon desc';
     //$num8 = ' limit 0,1';
     $arrquery8 = $print_1->arrquerytemp ($tb8, $field8, $condition8, '', ''); // danh sach dieu kien
     //set for template
     $arrtemplate8 = $print_1->arraytemp ('home_term', 'term1_1', 'termvar1_1'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic6 = './images/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar8 = $print_1->ArrayVarTemp ('', '', 'sContents', '', 'iIdCommon'); // danh sach ten bien
     $object_num8 = array('home', 'main', 't4'); // Ten file, vi tri de chon title?
   //  $main->set('main4', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8)); // 8
         
       $main_temp->set('main_dichvu', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8));
	        
     $main->set('main2', $main_temp); // 6
     
     //footer
     $footer = & new Template('footer');
     //$footer->set('totaltime', $Debug->endTimer());
     $footer->set('counter', $print->get_counter());
     $footer->set('statistics', $print->statistics());

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
     //$tpl->set('left', $left);
     $tpl->set('main', $main);
     //$tpl->set('maintab', $maintab);
     //$tpl->set('right', $right);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('home');
?>