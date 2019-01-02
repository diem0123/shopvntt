<?php
/*
+--------------------------------------------------------------------------
|   SA DA NA REE M&E CORP
|   ========================================
|   by Le Minh Than 
|      
|   ========================================
|   Web: http://www.sdnree.com
|   Time: Thursday, june 05, 2008 
|   Email: htktinternet@yahoo.com
+---------------------------------------------------------------------------
*/ 
     if (isset($itech->input['Submit'])){
        $email=$itech->input['emailnews'];
        $user_1->addemail($email);
        $print_1->redirect('index.php?act=home', $lang['emailbaogia'], 'register email');
     }    
     //header
   
      $header = & new Template('header_home');
      $header->set('title', $INFO['site_name']);
      
     //main
       $main = & new Template('main');
      
       //************************************************ Doi tuong oj5
     
     //set for template
     $arrtemplate5 = $print_1->arraytemp ('main1', 'main1_1', 'mainvar1_1'); // danh sach template
     $object_num5 = array('home', 'main', ''); // Ten file, vi tri de chon title?
     $main->set('main1', $print_1->ShowTemplatehome($arrtemplate5, $object_num5, 3, 'home')); // 3 la vi tri main
     
     
     
     // thanks
     
     //set for template
     $arrtemplatem1 = $print_1->arraytemp ('cm_thanks1', 'cm_thanks1_1', 'cm_thanksvar1_1'); // danh sach template
     $object_numm1 = array('cm_contact', 'main', 't1'); // Ten file, vi tri de chon title?
     //$main->set('main1', $print_1->ShowTemplate($arrtemplatem1, $object_numm1)); // 6
     
     
      $main_temp = & new Template('mainsub10');
      $main_temp->set('main_thanks', $print_1->ShowTemplate($arrtemplatem1, $object_numm1)); // 6
      
     //$main->set('main2', $print_1->ShowObject($arrtemplate6, $arrquery6, $arrVar6, $object_num6)); // 6
     
     // set for query
     $tb7 = $lang['tbl_common'];// Lay ten table;
     $field7 = '*'; // chon cac field // chon cac field (chon lai it field de cho nhanh )
     $condition7 = ' where iIdCommon =6 and iStatus=1';
     $order7 = ' order by iIdCommon desc';
     $num7 = ' limit 0,1';
     $arrquery7 = $print_1->arrquerytemp ($tb7, $field7, $condition7, $order7, $num7); // danh sach dieu kien
     //set for template
     $arrtemplate7 = $print_1->arraytemp ('home_dichvu', 'dichvu_sub', 'dichvu_subvar'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic6 = './images/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar7 = $print_1->ArrayVarTemp ('', '', 'sContentShort', '', 'iIdCommon'); // danh sach ten bien
     $object_num7 = array('home', 'main', 't3'); // Ten file, vi tri de chon title?
    // $main->set('main3', $print_1->ShowObject($arrtemplate7, $arrquery7, $arrVar7, $object_num7)); // 7
     $main_temp->set('dichvu', $print_1->ShowObject($arrtemplate7, $arrquery7, $arrVar7, $object_num7)); 
    
   
    
    
     // set for query
     $tb8 = $lang['tbl_common'];// Lay ten table;
     $field8 = '*'; // chon cac field // chon cac field (chon lai it field de cho nhanh )
     $condition8 = ' where iIdCommon =7 and iStatus=1';
     $order8 = ' order by iIdCommon desc';
     $num8 = ' limit 0,1';
     $arrquery8 = $print_1->arrquerytemp ($tb8, $field8, $condition8, $order8, $num8); // danh sach dieu kien
     //set for template
     $arrtemplate8 = $print_1->arraytemp ('home_cttieubieu', 'cttieubieu_sub', 'cttieubieu_subvar'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic6 = './images/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar8 = $print_1->ArrayVarTemp ('', '', 'sContentShort', '', 'iIdCommon'); // danh sach ten bien
     $object_num8 = array('home', 'main', 't4'); // Ten file, vi tri de chon title?
   //  $main->set('main4', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8)); // 8
    
   // tuong tu cho cac cttieubieu, lay o duoi.
      
       $main_temp->set('submitresume', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8)); 
     
    
     // set for query
     $tb9 = $lang['tbl_common'];// Lay ten table;
     $field9 = '*'; // chon cac field // chon cac field (chon lai it field de cho nhanh )
     $condition9 = ' where iIdCommon =10 and iStatus=1';
     $order9 = ' order by iIdCommon desc';
     $num9 = ' limit 0,1';
     $arrquery9 = $print_1->arrquerytemp ($tb9, $field9, $condition9, $order9, $num9); // danh sach dieu kien
     //set for template
     $arrtemplate9 = $print_1->arraytemp ('home_ctthamgia', 'ctthamgia_sub', 'ctthamgia_subvar'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic6 = './images/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar9 = $print_1->ArrayVarTemp ('', '', 'sContentShort', '', 'iIdCommon'); // danh sach ten bien
     $object_num9 = array('home', 'main', 't4'); // Ten file, vi tri de chon title?
   //  $main->set('main4', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8)); // 8
    
   // tuong tu cho cac cttieubieu, lay o duoi.
      
     $main_temp->set('ctthamgia', $print_1->ShowObject($arrtemplate9, $arrquery9, $arrVar9, $object_num9)); 
        
     
     // set for query
     $tb10 = $lang['tbl_common'];// Lay ten table;
     $field10 = '*'; // chon cac field // chon cac field (chon lai it field de cho nhanh )
     $condition10 = ' where iIdCommon =8 and iStatus=1';
     $order10 = ' order by iIdCommon desc';
     $num10 = ' limit 0,1';
     $arrquery10 = $print_1->arrquerytemp ($tb10, $field10, $condition10, $order10, $num10); // danh sach dieu kien
     //set for template
     $arrtemplate10 = $print_1->arraytemp ('home_doitac', 'doitac_sub', 'doitac_subvar'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic6 = './images/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar10 = $print_1->ArrayVarTemp ('', '', 'sContentShort', '', 'iIdCommon'); // danh sach ten bien
     $object_num10 = array('home', 'main', 't4'); // Ten file, vi tri de chon title?
   //  $main->set('main4', $print_1->ShowObject($arrtemplate8, $arrquery8, $arrVar8, $object_num8)); // 8
    
   // tuong tu cho cac cttieubieu, lay o duoi.
      
     $main_temp->set('listjob', $print_1->ShowObject($arrtemplate10, $arrquery10, $arrVar10, $object_num10));   
     
     //$iType=$itech->input['iType'];
     // set for query
     $tbr11 = $lang['tbl_product_type'];// Lay ten table;
     $fieldr11 = '*'; // chon cac field
    // $conditionr1 = ' where iType='.$iType;
     $orderr11 = ' order by iId desc';
     //$numr11 = ' limit 0,7';
     $arrqueryr11 = $print_1->arrquerytemp ($tbr11, $fieldr11, '', $orderr11, ''); // danh sach dieu kien
     //set for template
     $arrtemplater11 = $print_1->arraytemp ('home_ctthuchien', 'ctthuchien_sub', 'ctthuchien_subvar'); // danh sach template  
     //$path_picr1 = './images/imagenews/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVarr11 = $print_1->ArrayVarTemp ('sName', '', '', '', 'iId'); // danh sach ten bien
     $object_numr11 = array('cm_newsdetail', 'right', 't2'); // Ten file, vi tri de chon title?
     $_SESSION['nav_news'] = 0; // cho phep hien thi phan trang; =0 ko cho phep
     $main_temp->set('ctthuchien', $print_1->ShowNews($arrtemplater11, $arrqueryr11, $arrVarr11, $object_numr11, '', ''));   
       
     $main->set('main2', $main_temp); // 6
     
/*
     $query=$print_2->getData1Table($lang['tbl_common'],'iIdCommon =1','iIdCommon');
	 $row=$DB->fetch_row($query);
	 $main3=&new Template('main2_1');
	 $main3->set('title',$row['sTitle'.$pre]);
	 $main3->set('sContentShort',$row['sContentShort'.$pre]);
	 $main->set('main3', $main3->fetch('main2_1')); */
      //************************************************ Doi tuong oj6
     
     
     
      //right
     $right = & new Template('right');
      
     // set for query
     $tb1 = $lang['tbl_menuleft'];// Lay ten table;
     $field1 = '*'; // chon cac field
     //$condition = ' where sC<>0 ';
     $order1 = ' order by iOrder';
     //$num = ' limit 0,3';
     $arrquery1 = $print_1->arrquerytemp ($tb1, $field1, '', $order1, ''); // danh sach dieu kien
     //set for template
     $arrtemplate1 = $print_1->arraytemp ('right1', 'right1_1', 'rightvar1_1'); // danh sach template
     // set danh sach cac bien lay thong tin
     //$path_pic = './images/products/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVar1 = $print_1->ArrayVarTemp ('sName', '', '', '', 'iId'); // danh sach ten bien
     $object_num1 = array('home', 'right', 't1'); // Ten file, vi tri, ten title de chon title?
     $right->set('right1', $print_1->ShowMenuleft($arrtemplate1, $arrquery1, $arrVar1, $object_num1)); // left1
      
     // set for query
     $tbr1 = $lang['tbl_news'];// Lay ten table;
     $fieldr1 = '*'; // chon cac field
     $conditionr1 = ' where iType=1 and iStatus=1';
     $orderr1 = ' order by iNewsID desc';
     $numr1 = ' limit 0,3';
     $arrqueryr1 = $print_1->arrquerytemp ($tbr1, $fieldr1, $conditionr1, $orderr1, $numr1); // danh sach dieu kien
     //set for template
     $arrtemplater1 = $print_1->arraytemp ('right2', 'right2_1', 'rightvar2_1'); // danh sach template  
     $path_picr1 = './images/imagenews/'; // duong dan cua image
     //ArrayVarTemp ($name, $image, $shortcontent, $path_pic, $idnum);// function truyen ten bien
     $arrVarr1 = $print_1->ArrayVarTemp ('sNewsTitle', 'sThumbnail', 'sContentShort', $path_picr1, 'iNewsID'); // danh sach ten bien
     $object_numr1 = array('home', 'right', 't2'); // Ten file, vi tri de chon title?
     $_SESSION['nav_news'] = 0; // cho phep hien thi phan trang; =0 ko cho phep
     $right->set('right2', $print_1->ShowNews($arrtemplater1, $arrqueryr1, $arrVarr1, $object_numr1, '', ''));
	
  
     //footer
     $footer = & new Template('footer');
     $footer->set('totaltime', $Debug->endTimer());
     $footer->set('counter', $print->get_counter());
     $footer->set('statistics', $print->statistics());

     //all
     $tpl = & new Template();
     $tpl->set('header', $header);
     //$tpl->set('left', $left);
     $tpl->set('main', $main);
     //$tpl->set('maintab', $maintab);
     $tpl->set('right', $right);
     $tpl->set('footer', $footer);

     echo $tpl->fetch('home');
?>