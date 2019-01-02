<?php
/*
+--------------------------------------------------------------------------
|   by Le Minh Than |      
|   ========================================
|   Email: htktinternet@yahoo.com
+---------------------------------------------------------------------------
*/
class func_1 {

     /*-------------------------------------------------------------------------*/
    // Sets a cookie, abstract layer allows us to do some checking, etc
    /*-------------------------------------------------------------------------*/

    function my_setcookie($name, $value = "", $sticky = 0) {
        global $INFO;

        if ($sticky == 1)
        {
             $expires = time() + 60*60*24*365;
        }

        $INFO['cookie_domain'] = $INFO['cookie_domain'] == "" ? ""  : $INFO['cookie_domain'];
        $INFO['cookie_path']   = $INFO['cookie_path']   == "" ? "/" : $INFO['cookie_path'];

        $name = $INFO['cookie_id'].$name;

        @setcookie($name, $value, $expires, $INFO['cookie_path'], $INFO['cookie_domain']);
    }

    /*-------------------------------------------------------------------------*/
    // Cookies, cookies everywhere and not a byte to eat.
    /*-------------------------------------------------------------------------*/

     function my_getcookie($name)
    {
         global $_COOKIE;

         if (isset($_COOKIE[$name]))
         {
              return urldecode($_COOKIE[$name]);
         }
         else
         {
              return FALSE;
         }

    }

     /*-------------------------------------------------------------------------*/
     // txt_stripslashes
     // ------------------
     // Make Big5 safe - only strip if not already...
     /*-------------------------------------------------------------------------*/

   

} //end class func


class display_1 {
	
	 function redirect($to,$what,$where) {
        $box =  new Template('tl_redirect');
          $box->set('title', '');
          $box->set('to', $to);
          $box->set('what', $what);

          print $box->fetch('redirect');
        exit();
    }

	function datevn ($date) {
          return strftime("%d-%m-%Y", strtotime($date));
     }	

  function get_dropdown_province($province_id = 0, $type = 0){
          global $DB, $lang;
          $data = '';
          $tbl = $lang['tbl_province'];
          
               $query = $DB->query("SELECT * FROM ".$tbl." ORDER BY c_oder_ex, name");
          while ($result = $DB->fetch_row($query)){
               if ($result['province_id'] == $province_id){
                    if ($type==1)
                         return $result['name'];
                    else
                         $selected = ' selected';
               }
               else
                    $selected = '';
               $data .= '<option value="'.$result['province_id'].'"'.$selected.'>'.$result['name'].'</option>';
          }
          $DB->free_result($query);
          return $data;
     }
   function get_dropdown_country($country_id = 0, $type = 0){
          global $DB, $lang;
          $data = '';
          $tbl = $lang['tbl_countries'];
          
          $query = $DB->query("SELECT * FROM ".$tbl." WHERE limits=1 ORDER BY c_oder, countries_name");
          while ($result = $DB->fetch_row($query)){
               if ($result['countries_id'] == $country_id){
                    if ($type==1)
                         return $result['countries_name'];
                    else
                         $selected = ' selected';
               }
               else
                    $selected = '';
               $data .= '<option value="'.$result['countries_id'].'"'.$selected.'>'.$result['countries_name'].'</option>';
          }
          $DB->free_result($query);
          return $data;
     }
     
	

     
   
     
     
}

?>
