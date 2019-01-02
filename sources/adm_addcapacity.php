<?php

    if (!isset($_SESSION['ID_CONSULTANT']) || $_SESSION['ID_CONSULTANT'] == "") {   
    $url_redidrect='index.php?act=adm_login';
    $common->redirect_url($url_redidrect);
  } 
     //main
     $main = & new Template('adm_addcapacity');   
    $idcom = 1;// default for tomiki
    // set for wysywyg insert images
    $_SESSION['idcom'] = $idcom;
    $ac = $itech->input['ac'];
    $submit = $itech->input['submit'];

    $id_capacity = $itech->input['id_capacity'];
    $name_capacity = $itech->input['name_capacity'];
    $id_product = $itech ->input['id_product'];
    
    
    $PRICE = $itech->input['PRICE'];
    // process string to int
    $price_tem = explode(".",$PRICE);
    $price_str = "";
    for($i1=0;$i1<count($price_tem);$i1++){
      $price_str .= $price_tem[$i1];
    }
    
    if(!$PRICE) $PRICE = 0;
    else $PRICE = (int)$price_str;
    
    $SALE = $itech->input['SALE'];
    if($SALE == "") $SALE = 0;
    
    $TINHTRANG = $itech->input['TINHTRANG'];    
    if($TINHTRANG == "") $TINHTRANG = 0;
    
    $iorder = $itech->input['iorder'];    
    if($iorder == "") $iorder = 0;
    
    $status = $itech->input['status'];
    $contentencode = $itech->input['contentencode'];
    //echo "content=".$contentencode;
    
    $content = $itech->input['content'];
    $contentencodeparam = $itech->input['contentencodeparam'];
    //echo "<br> param=".$contentencodeparam;
    $param = $itech->input['param'];
    
    $user_incharge = $itech->input['cst'];
    $addmore = $itech->input['addmore'];

    
    $sl1 = "";
    $sl2 = "";
    $sl3 = "";
    
  // set permission   
    /*
    if (!$common->checkpermission($_SESSION['ID_CONSULTANT'], $common->getFeature(4), "AE")){
      $url_redidrect='index.php?act=error403';
      $common->redirect_url($url_redidrect);        
      exit;   
    }
    */
    //check role type
    $role = $common->getInfo("consultant_has_role","ID_CONSULTANT ='".$_SESSION['ID_CONSULTANT']."'");
    $roleinfo = $common->getInfo("role_lookup","ROLE_ID ='".$role['ROLE_ID']."'");
    $list_role_operation = $common->getListValue("role_lookup","ROLE_TYPE = 'Operation' ORDER BY IORDER ",'ROLE_ID');
    $list_consultant_operation = $common->getListValue("consultant_has_role","ROLE_ID IN (".$list_role_operation.") ORDER BY CONSULTANT_ROLE_ID",'ID_CONSULTANT');
    //check consultant incharge for company
    
      //$user_incharge_com = $common->getInfo("consultant_incharge","ID_COM ='".$idcom."'");
      // check permission com incharge owner
      if($roleinfo['ROLE_TYPE'] != "Operation"){
        $url_redidrect='index.php?act=error403';
        $common->redirect_url($url_redidrect);        
        exit;
      }
  
  // get form company
  // if add company

    $list_product = $print_2->GetDropDown($id_product, "products","STATUS = 'Active'" ,"ID_PRODUCT", "PRODUCT_NAME", "IORDER");

  
    if($idcom && $ac=="a" && $submit == "") {   
          $main->set('list_product', $list_product);        
                   
          $main->set('PRICE', "");
          $main->set('SALE', "");
          $main->set('TINHTRANG', "");
          $main->set('iorder', "");
          if($roleinfo['ROLE_TYPE'] == "Operation"){
          $main->set('sl1', "selected");
          $main->set('sl2', "");
          $main->set('sl3', "");
          $main->set('dispr', "");
          }
          else{
          $main->set('sl1', "");
          $main->set('sl2', "selected");
          $main->set('sl3', "");
          $dispr = 'style = "display:none"';
          $main->set('dispr', $dispr);
          }
          $main->set('content', "");
          $main->set('param', "");
          //check role type
          //$user_incharge_com['ID_CONSULTANT'] 
          if($roleinfo['ROLE_TYPE'] == "Operation"){
            $list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
            $ronly = "disabled";
          }
          else{
            $list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
            $ronly = "disabled";
          }
          
          $main->set('list_consultant', $list_consultant);
          $main->set('ronly', $ronly);
      echo $main->fetch("adm_addcapacity");
      exit;
    }
    elseif($idcom && $ac=="a" && $submit != "") {     
        try {
          $ArrayData = array(
                    1 => array(1 => "NAME_CAPACITY", 2 => $name_capacity ),
                    2 => array(1 => "PRICE", 2 => $PRICE),
                    3 => array(1 => "SALE", 2 => $SALE),                   
                    4 => array(1 => "STATUS", 2 => $status),
                    5 => array(1 => "CONTENT", 2 => $content),                   
                    6 => array(1 => "IORDER", 2 => $iorder),
                    7 => array(1 => "TINHTRANG", 2 => $TINHTRANG),
                    8 => array(1 => "ID_PRODUCT", 2 => $id_product)
                   
                    );
          // echo '<pre>';      
          // print_r($ArrayData);exit;
          $idrt = $common->InsertDB($ArrayData,"capacity_product");
          //echo "idrt=".$idrt;exit;
          //$sql = "INSERT INTO products (PRODUCT_NAME,ID_PRO_CAT,POST_BY) value ( 'tin tuc', '1', '1') ";
          //$DB->query($sql);         
        } catch (Exception $e) {
            echo $e;
          }
        // check if addmore
        if($addmore == "addmore"){
          $main->set('list_product', $list_product);
          $main->set('PRICE', number_format($PRICE,0,"","."));
          $main->set('SALE', $SALE);
          
          if($TINHTRANG) $tbchecked = "checked";
          $main->set('tbchecked', $tbchecked);
          //$main->set('COUNT_QUANTITY', $COUNT_QUANTITY);
          
          $main->set('iorder', $iorder);
          
          if($roleinfo['ROLE_TYPE'] == "Operation"){
          if($status == 'Active') $sl1 = "selected";
          elseif($status == 'Inactive') $sl2 = "selected";
          elseif($status == 'Deleted') $sl3 = "selected";
          $main->set('sl1', $sl1);
          $main->set('sl2', $sl2);
          $main->set('sl3', $sl3);
          $main->set('dispr', "");
          }
          else{
          $main->set('sl1', "");
          $main->set('sl2', "selected");
          $main->set('sl3', "");
          $dispr = 'style = "display:none"';
          $main->set('dispr', $dispr);
          }

          $main->set('content', "");
          $main->set('param', "");
          //check role type
          if($roleinfo['ROLE_TYPE'] == "Operation"){
            $list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
            $ronly = "disabled";
          }
          else{
            $list_consultant = $print_2->GetDropDown($_SESSION['ID_CONSULTANT'], "consultant","ID_CONSULTANT NOT IN (".$list_consultant_operation.")" ,"ID_CONSULTANT", "USER_NAME", "UPDATED_DATE DESC");
            $ronly = "disabled";
          }
          
          $main->set('list_consultant', $list_consultant);
          $main->set('ronly', $ronly);
      
        echo $main->fetch("adm_addproduct");
        exit;       
        }           
      
      echo "<script language=javascript>window.close();window.opener.location.reload();</script>";          
    }

?>
