<?php

class func
{

    public function my_setcookie( $name, $value = "", $sticky = 0 )
    {
        global $INFO;
        if ( $sticky == 1 )
        {
            $expires = time( ) + 31536000;
        }
        $INFO['cookie_domain'] = $INFO['cookie_domain'] == "" ? "" : $INFO['cookie_domain'];
        $INFO['cookie_path'] = $INFO['cookie_path'] == "" ? "/" : $INFO['cookie_path'];
        $name = $INFO['cookie_id'].$name;
        @setcookie( $name, $value, $expires, @$INFO['cookie_path'], @$INFO['cookie_domain'] );
    }

    public function my_getcookie( $name )
    {
        global $_COOKIE;
        if ( isset( $_COOKIE[$name] ) )
        {
            return urldecode( $_COOKIE[$name] );
        }
        else
        {
            return FALSE;
        }
    }

    public function txt_stripslashes( $t )
    {
        if ( get_magic_quotes_gpc( ) )
        {
            $t = stripslashes( $t );
        }
        return $t;
    }

    public function txt_raw2form( $t = "" )
    {
        $t = str_replace( "\$", "&#036;", $t );
        if ( get_magic_quotes_gpc( ) )
        {
            $t = stripslashes( $t );
        }
        $t = preg_replace( "/\\\\(?!&amp;#|\\?#)/", "&#092;", $t );
        return $t;
    }

    public function txt_safeslashes( $t = "" )
    {
        return str_replace( "\\", "\\\\", $this->txt_stripslashes( $t ) );
    }

    public function txt_htmlspecialchars( $t = "" )
    {
        $t = preg_replace( "/&(?!#[0-9]+;)/s", "&amp;", $t );
        $t = str_replace( "<", "&lt;", $t );
        $t = str_replace( ">", "&gt;", $t );
        $t = str_replace( "\"", "&quot;", $t );
        return $t;
    }

    public function txt_UNhtmlspecialchars( $t = "" )
    {
        $t = str_replace( "&amp;", "&", $t );
        $t = str_replace( "&lt;", "<", $t );
        $t = str_replace( "&gt;", ">", $t );
        $t = str_replace( "&quot;", "\"", $t );
        return $t;
    }

    public function my_nl2br( $t = "" )
    {
        return str_replace( "\n", "<br />", $t );
    }

    public function my_br2nl( $t = "" )
    {
        $t = preg_replace( "#(?:\n|\r)?<br />(?:\n|\r)?#", "\n", $t );
        $t = preg_replace( "#(?:\n|\r)?<br>(?:\n|\r)?#", "\n", $t );
        return $t;
    }

    public function make_password( )
    {
        $pass = "";
        $chars = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z" );
        $count = count( $chars ) - 1;
        srand( ( double ) microtime() * 1000000 );
        
        for ($i = 0; $i < 8; ++$i )
        {
            $pass .= $chars[rand( 0, $count )];
        }
        return $pass;
    }

    public function parse_incoming( )
    {
        global $_GET;
        global $_POST;
        global $HTTP_CLIENT_IP;
        global $REQUEST_METHOD;
        global $REMOTE_ADDR;
        global $HTTP_PROXY_USER;
        global $HTTP_X_FORWARDED_FOR;
        $return = array( );
        if ( is_array( $_GET ) )
        {
            while ( list( $k, $v ) = each( $_GET ) )
            {
                if ( $k == "INFO" )
                {
                    continue;
                }
                if ( is_array( $_GET[$k] ) )
                {
                    do
                    {
                        if ( list( $k2, $v2 ) = each( $_GET[$k] ) )
                        {
                            $return[$k][$this->clean_key( $k2 )] = $this->clean_value( $v2 );
                        }
                    } while ( 1 );
                }
                else
                {
                    $return[$k] = $this->clean_value( $v );
                }
            }
        }
        if ( is_array( $_POST ) )
        {
            while ( list( $k, $v ) = each( $_POST ) )
            {
                if ( is_array( $_POST[$k] ) )
                {
                    do
                    {
                        if ( list( $k2, $v2 ) = each( $_POST[$k] ) )
                        {
                            $return[$k][$this->clean_key( $k2 )] = $this->clean_value( $v2 );
                        }
                    } while ( 1 );
                }
                else
                {
                    $return[$k] = $this->clean_value( $v );
                }
            }
        }
        $addrs = array( );
        foreach ( array_reverse( explode( ",", $HTTP_X_FORWARDED_FOR ) ) as $x_f )
        {
            $x_f = trim( $x_f );
            if ( preg_match( "/^\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}$/", $x_f ) )
            {
                $addrs[] = $x_f;
            }
        }
        $addrs[] = $_SERVER['REMOTE_ADDR'];
        $addrs[] = $HTTP_PROXY_USER;
        $addrs[] = $REMOTE_ADDR;
        $return['IP_ADDRESS'] = $this->select_var( $addrs );
        $return['IP_ADDRESS'] = preg_replace( "/^([0-9]{1,3})\\.([0-9]{1,3})\\.([0-9]{1,3})\\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return['IP_ADDRESS'] );
        $return['request_method'] = $_SERVER['REQUEST_METHOD'] != "" ? strtolower( $_SERVER['REQUEST_METHOD'] ) : strtolower( $REQUEST_METHOD );
        return $return;
    }

    public function clean_key( $key )
    {
        if ( $key == "" )
        {
            return "";
        }
        $key = preg_replace( "/\\.\\./", "", $key );
        $key = preg_replace( "/\\_\\_(.+?)\\_\\_/", "", $key );
        $key = preg_replace( "/^([\\w\\.\\-\\_]+)$/", "$1", $key );
        return $key;
    }

    public function clean_value( $val )
    {
        if ( $val == "" )
        {
            return "";
        }
        $val = str_replace( "&#032;", " ", $val );
        $val = str_replace( chr( 202 ), "", $val );
        $val = str_replace( "&", "&amp;", $val );
        $val = str_replace( "<!--", "&#60;&#33;--", $val );
        $val = str_replace( "-->", "--&#62;", $val );
        $val = preg_replace( "/<script/i", "&#60;script", $val );
        $val = str_replace( ">", "&gt;", $val );
        $val = str_replace( "<", "&lt;", $val );
        $val = str_replace( "\"", "&quot;", $val );
        $val = preg_replace( "/\\$/", "&#036;", $val );
        $val = preg_replace( "/\r/", "", $val );
        $val = str_replace( "!", "&#33;", $val );
        $val = str_replace( "'", "&#39;", $val );
        $val = preg_replace( "/&amp;#([0-9]+);/s", "&#\\1;", $val );
        if ( get_magic_quotes_gpc( ) )
        {
            $val = stripslashes( $val );
        }
        $val = preg_replace( "/\\\\(?!&amp;#|\\?#)/", "&#092;", $val );
        return $val;
    }

    public function is_number( $number = "" )
    {
        if ( $number == "" )
        {
            return -1;
        }
        if ( preg_match( "/^([0-9]+)$/", $number ) )
        {
            return $number;
        }
        else
        {
            return "";
        }
    }

    public function select_var( $array )
    {
        if ( !is_array( $array ) )
        {
            return -1;
        }
        ksort( $array );
        $chosen = -1;
        foreach ( $array as $k => $v )
        {
            if ( isset( $v ) )
            {
                $chosen = $v;
                break;
            }
        }
        return $chosen;
    }

    public function cut_string( $str, $len, $more )
    {
        if ( $str == "" || $str == NULL )
        {
            return $str;
        }
        if ( is_array( $str ) )
        {
            return $str;
        }
        $str = trim( $str );
        if ( strlen( $str ) <= $len )
        {
            return $str;
        }
        $str = substr( $str, 0, $len );
        if ( $str != "" )
        {
            if ( !substr_count( $str, " " ) )
            {
                if ( $more )
                {
                    $str .= " ...";
                }
                return $str;
            }
            while ( strlen( $str ) && $str[strlen( $str ) - 1] != " " )
            {
                $str = substr( $str, 0, -1 );
            }
            $str = substr( $str, 0, -1 );
            if ( $more )
            {
                $str .= " ...";
            }
        }
        return $str;
    }

    public function get_extension( $type )
    {
        if ( $type == "image/pjpeg" )
        {
            return ".jpg";
        }
        else if ( $type == "image/bmp" )
        {
            return ".bmp";
        }
        else if ( $type == "image/gif" )
        {
            return ".gif";
        }
        else
        {
            return 0;
        }
    }

    public function authenticate( )
    {
        global $INFO;
        if ( !isset( $_SERVER['PHP_AUTH_USER'] ) || $_SERVER['PHP_AUTH_USER'] != $INFO['admin_user'] || $_SERVER['PHP_AUTH_PW'] != $INFO['admin_pass'] )
        {
            header( "WWW-Authenticate: Basic realm=\"iTech Group Authentication System\"" );
            header( "HTTP/1.0 401 Unauthorized" );
            echo "You must enter a valid login ID and password to access this page";
            exit( );
        }
    }

    public function makethumb( $picname, $pic_small, $origfolder, $width, $height, $quality )
    {
        global $srcWidth;
        global $srcHeight;
        global $destHeight;
        global $destWidth;
        global $filetyp;
        global $itech;
        $origpath = $itech->vars['document_root']."/{$origfolder}";
        $dst_x = 0;
        $dst_y = 0;
        $this->getdimensions( $picname, $origfolder, $width, $height );
        $src_x = 0;
        $src_y = 0;
        if ( $filetyp == "2" || $filetyp == "3" || $filetyp == "1" )
        {
            @ini_set( "memory_limit", -1 );
            if ( $filetyp == 2 )
            {
                $srcImage = imagecreatefromjpeg( "{$origpath}/{$picname}" );
            }
            else if ( $filetyp == 3 )
            {
                $srcImage = imagecreatefrompng( "{$origpath}/{$picname}" );
            }
            else
            {
                $srcImage = imagecreatefromgif( "{$origpath}/{$picname}" );
            }
            if ( $srcImage == "" )
            {
                echo "Unable to create image [{$picname}].<br />Please contact system administrator.";
                exit( );
            }
            if ( $itech->vars['gdversion'] == 2 )
            {
                $destImage = imagecreatetruecolor( $destWidth, $destHeight );
                imagecopyresampled( $destImage, $srcImage, $dst_x, $dst_y, $src_x, $src_y, $destWidth, $destHeight, $srcWidth, $srcHeight );
            }
            else
            {
                $destImage = imagecreate( $destWidth, $destHeight );
                imagecopyresized( $destImage, $srcImage, $dst_x, $dst_y, $src_x, $src_y, $destWidth, $destHeight, $srcWidth, $srcHeight );
            }
            if ( $filetyp == 2 )
            {
                imagejpeg( $destImage, "{$origpath}/{$pic_small}", $quality );
            }
            else if ( $filetyp == 3 )
            {
                imagepng( $destImage, "{$origpath}/{$pic_small}" );
            }
            else
            {
                imagegif( $destImage, "{$origpath}/{$pic_small}" );
            }
            imagedestroy( $srcImage );
            imagedestroy( $destImage );
        }
        return;
    }

    public function getdimensions( $picname, $origfolder, $width, $height )
    {
        global $srcWidth;
        global $srcHeight;
        global $destHeight;
        global $destWidth;
        global $filetyp;
        global $itech;
        $srcSize = @getimagesize( @$itech->vars['document_root'].@"/{$origfolder}/{$picname}" );
        $srcWidth = $srcSize[0];
        $srcHeight = $srcSize[1];
        $filetyp = $srcSize[2];
        if ( $height < $srcHeight )
        {
            if ( $width < $srcWidth / $srcHeight * $height )
            {
                $destWidth = $width;
                $destHeight = round( $srcHeight / $srcWidth * $width );
            }
            else
            {
                $destHeight = $height;
                $destWidth = round( $srcWidth / $srcHeight * $height );
            }
        }
        else if ( $width < $srcWidth )
        {
            $destWidth = $width;
            $destHeight = round( $srcHeight / $srcWidth * $width );
        }
        else
        {
            $destWidth = $srcWidth;
            $destHeight = $srcHeight;
        }
        return;
        unset( $height );
        unset( $width );
    }

    public function getuserinfo( )
    {
        $arr = array( );
        if ( !isset( $_SERVER['REMOTE_ADDR'] ) )
        {
            $arr['ip'] = "unknown";
        }
        else
        {
            $arr['ip'] = $_SERVER['REMOTE_ADDR'];
        }
        if ( $arr['ip'] == "unknown" || preg_match( "!^\\d+\\.\\d+\\.\\d+\\.\\d+$!", $arr['ip'], $foo ) )
        {
            if ( preg_match( "!^(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)$!", $_SERVER['REMOTE_ADDR'], $foo ) )
            {
                $arr['remotehost'] = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
            }
        }
        if ( !isset( $_SERVER['HTTP_USER_AGENT'] ) )
        {
            $arr['useragent'] = "unknown";
        }
        else
        {
            $arr['useragent'] = $_SERVER['HTTP_USER_AGENT'];
        }
        return $arr;
    }

    public function format_filesize( $totalfilesize )
    {
        if ( 1073741824 <= $totalfilesize )
        {
            $show_filesize = number_format( $totalfilesize / 1073741824, 1 )." GB";
        }
        else if ( 1048576 <= $totalfilesize )
        {
            $show_filesize = number_format( $totalfilesize / 1048576, 1 )." MB";
        }
        else if ( 1024 <= $totalfilesize )
        {
            $show_filesize = number_format( $totalfilesize / 1024, 1 )." KB";
        }
        else if ( 0 <= $totalfilesize )
        {
            $show_filesize = $totalfilesize." bytes";
        }
        else
        {
            $show_filesize = "0 bytes";
        }
        return $show_filesize;
    }

}

class display
{

    public function my_dolar( $t = "" )
    {
        return str_replace( "&#036;", "\$", $t );
    }

    public function get_dropdown_nationality( $nationality = "", $type = 0 )
    {
        global $DB;
        $data = "";
        $query = $DB->query( "SELECT * FROM nationality ORDER BY c_order" );
        while ( $result = $DB->fetch_row( $query ) )
        {
            if ( $result['nationality'] == html_entity_decode( $nationality ) )
            {
                if ( $type == 1 )
                {
                    return $result['nationality'];
                }
                else
                {
                    $selected = " selected";
                }
            }
            else
            {
                $selected = "";
            }
            $data .= "<option value=\"".$result['nationality']."\"".$selected.">".$result['nationality']."</option>";
        }
        $DB->free_result( $query );
        return $data;
    }

    public function get_checkedbox( $checkbox = "" )
    {
        if ( $checkbox == "on" )
        {
            $data = " checked";
        }
        else
        {
            $data = "";
        }
        return $data;
    }

    public function statistics1( )
    {
        global $DB;
        $tpl = new Template( "counter_rs" );
        $data = "";
        $query = $DB->query( "SELECT num FROM counter" );
        while ( $result = $DB->fetch_row( $query ) )
        {
            $tpl->set( "counter", $result['num'] );
        }
        $limit_time = time( ) - 0;
        $query = $DB->query( "SELECT COUNT(*) as num FROM online" );
        while ( $result = $DB->fetch_row( $query ) )
        {
            $tpl->set( "online", $result['num'] + 0 );
        }
        $data .= $tpl->fetch( "counter_rs" );
        $DB->free_result( $query );
        return $data;
    }

    public function redirect( $to, $what, $where )
    {
        $box = new Template( "redirect" );
        $box->set( "title", "" );
        $box->set( "to", $to );
        $box->set( "what", $what );
        print $box->fetch( "redirect" );
        exit( );
    }

    public function datevn( $date )
    {
        return strftime( "%d-%m-%Y", strtotime( $date ) );
    }

    public function get_counter( )
    {
        global $DB;
        $qrs = $DB->query( "SELECT * FROM counter" );
        $result = $DB->fetch_row( $qrs );
        return $result['NUM'];
    }

    public function get_today( )
    {
        global $DB;		
        $qrs = $DB->query( "SELECT * FROM counter" );
        $result = $DB->fetch_row( $qrs );
        return $result['INTODAY'];
    }

    public function statistics( )
    {
        global $DB;        
        $limit_time = time( ) - 0;
        $query = $DB->query( "SELECT COUNT(*) as num FROM online" );
        $result = $DB->fetch_row( $query );
        return $result['num'] + 0;				
    }
    public function get_IDTYPE($idcat){
      global $DB;
      $query = $DB->query("SELECT ID_TYPE FROM COMMON_CAT WHERE ID_CAT = ".$idcat);
      $result = $DB->fetch_row($query);
      $idtype = $result['ID_TYPE'];
      return $idtype;
  }
  public function getNickchat() {
   global $DB, $lang, $itech, $print_2, $common;	

   $main = & new Template('nickchat_list');
   $field_name_other = array('TYPECHAT','IMG_NAME','MESSENGER_ON','NICK_NAME','NAME');			
   $fet_box = & new Template('nickchat_list_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			$sql="SELECT * FROM consultant_has_chat ORDER BY IORDER LIMIT 0,10";    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								                   					
               $fet_box->set( "idnick", $rs['CONSULTANT_CHAT_ID']);
               if($rs['TYPECHAT'] == 'YAHOO') $linkchat = "ymsgr:sendIM?".$rs['NICK_NAME'];
               elseif($rs['TYPECHAT'] == 'SKYPE') $linkchat = "skype:".$rs['NICK_NAME']."?chat";

               $fet_box->set( "linkchat", $linkchat);					

					// set for field name other chid
               if(count($field_name_other) > 0){
                  for($i=0;$i<count($field_name_other);$i++){
                     if(isset($rs[$field_name_other[$i.$pre]]))
                         $fet_box->set($field_name_other[$i], $rs[$field_name_other[$i.$pre]]);
                 }
             }									

             if($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'yahoo-messenger-icon2.png';
             elseif($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'yahoo-messenger-icon2-gray.gif';
             elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'skype2.png';
             elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'skype1.png';
             $fet_box->set( "iconchat", $iconchat);

					// Get info datasub
             $datasub .= $fet_box->fetch("nickchat_list_rs");

         }

         if($datasub != "")
            $main->set('list_chat_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_chat_rs', $datasub);
        }

        return $main->fetch('nickchat_list');
    }

    public function getSupport() {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_support');
       $field_name_other = array('TYPECHAT','IMG_NAME','MESSENGER_ON','NICK_NAME','NAME');			
       $fet_box = & new Template('list_support_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt = 0;

			$sql="SELECT * FROM consultant_has_chat ORDER BY IORDER LIMIT 0,4";    
			$query=$DB->query($sql); 										
			
			while ($rs=$DB->fetch_row($query))
			{								                   					
               $stt++;
               $fet_box->set( "idnick", $rs['CONSULTANT_CHAT_ID']);
               if($rs['TYPECHAT'] == 'YAHOO') $linkchat = "ymsgr:sendIM?".$rs['NICK_NAME'];
               elseif($rs['TYPECHAT'] == 'SKYPE') $linkchat = "skype:".$rs['NICK_NAME']."?call";



					// set for field name other chid
               if(count($field_name_other) > 0){
                  for($i=0;$i<count($field_name_other);$i++){
                     if(isset($rs[$field_name_other[$i.$pre]]))
                         $fet_box->set($field_name_other[$i], $rs[$field_name_other[$i.$pre]]);
                 }
             }									

             if($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'y_online.gif';
             elseif($rs['TYPECHAT'] == 'YAHOO' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'y_offline.gif';
             elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Active') $iconchat = 'icon-skype.png';
             elseif($rs['TYPECHAT'] == 'SKYPE' && $rs['MESSENGER_ON'] == 'Inactive') $iconchat = 'icon-skype.png';

             $info_cst = $common->getInfo("consultant","ID_CONSULTANT = '".$rs['ID_CONSULTANT']."'");


					// Get info datasub
             if($stt == 1) {
               $fet_box->set( "phone1", $info_cst['TEL']);
               $fet_box->set( "linkchat_ya1", $linkchat);
               $fet_box->set( "iconchat_ya", $iconchat);
           }
           elseif($stt == 2) {
               $fet_box->set( "linkchat_sky1", $linkchat);
               $fet_box->set( "iconchat_sky", $iconchat);
           }
           elseif($stt == 3) {
               $fet_box->set( "phone2", $info_cst['TEL']);
               $fet_box->set( "linkchat_ya2", $linkchat);
               $fet_box->set( "iconchat_ya", $iconchat);
           }
           elseif($stt == 4) {
               $fet_box->set( "linkchat_sky2", $linkchat);
               $fet_box->set( "iconchat_sky", $iconchat);
           }

           if($stt == 2)
               $datasub .= $fet_box->fetch("list_support_rs");
           elseif($stt == 4)
               $datasub .= $fet_box->fetch("list_support_rs2");

       }

       if($datasub != "")
        $main->set('list_support_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_support_rs', $datasub);
    }

    return $main->fetch('list_support');
}

public function get_list_contact($pre) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_contact');
 $field_name_other = array('TYPECHAT','IMG_NAME','MESSENGER_ON','NICK_NAME','NAME');			
 $fet_box = & new Template('list_contact_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt = 0;
			
			// get info company
			$info_com = $common->getInfo("company","ID_COM = 1");
			$fet_box->set( "comname", $info_com['COM_NAME'.$pre]);
			$fet_box->set( "tel", $info_com['TEL']);
			$fet_box->set( "fax", $info_com['FAX']);
			$fet_box->set( "email", $info_com['EMAIL']);
			$fet_box->set( "address", $info_com['ADDRESS'.$pre]);
			$fet_box->set( "hotline", $info_com['MOBI']);

			$datasub .= $fet_box->fetch("list_contact_rs");

            if($datasub != "")
                $main->set('list_contact_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_contact_rs', $datasub);
            }

            return $main->fetch('list_contact');
        }

        public function getimg_slide($idps) {
           global $DB, $lang, $itech, $print_2, $common;	

           $main = & new Template('list_img_slide');
           $field_name_other = array('IORDER','NAME_IMG','TITLE_IMG','ID_PS','LINK');			
           $fet_box = & new Template('list_img_slide_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_PS = '".$idps."'";
			$sql="SELECT * FROM banner  WHERE ".$condit." ORDER BY IORDER LIMIT 0,8";    
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idbn", $rs['ID_BN']);
               $fet_box->set( "stt", $stt);
               $fet_box->set( "LINK", $rs['LINK']);
               $fet_box->set( "NAME_IMG", $rs['NAME_IMG']);
               $fet_box->set( "TITLE_IMG", $rs['TITLE_IMG']);
               $fet_box->set( "mota", $rs['INFO']);	

					// set neu co phan trang
               if ($page) $fet_box->set( "page", "&page=".$page);
               else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
			/*		
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}																			
				*/	
					// Get info datasub
					$datasub .= $fet_box->fetch("list_img_slide_rs");
					

				}

				if($datasub != "")
                    $main->set('list_img_slide_rs', $datasub);
                else{
                    $fet_boxno = & new Template('noproduct');
                    $text = "";
                    $fet_boxno->set("text", $text);
                    $datasub .= $fet_boxno->fetch("noproduct");
                    $main->set('list_img_slide_rs', $datasub);
                }

                return $main->fetch('list_img_slide');
            }


            public function getlogo_partner_ngang($pre) {
               global $DB, $lang, $itech, $print_2, $common;	

               $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
               $fet_box = & new Template('logo_partner');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = 8 AND ID_CAT = 29";
			$sql="SELECT * FROM common  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$fet_box->set( "idn", $rs['ID_COMMON']);									
				//$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
				$lk = $rs['SCONTENTSHORT'.$pre];
				$lklogo .= '<li><table height="100" width="180" border="1" cellspacing="0" cellpadding="0">
             <tr>
              <td valign="middle" align="center"><a class="linkopacity" href="'.$lk.'" target="_blank" ><img class="border_rounded" border="0" src="images/bin/imagenews/'.$rs['PICTURE'].'" alt="logopartner" ></a></td>
          </tr>
      </table></li>';

  }

  $fet_box->set( "list_provider", $lklogo);
			// Get info datasub
  $datasub .= $fet_box->fetch("logo_partner");


  return $datasub;

}

public function getlogo_partner_doc($pre) {
	global $DB, $lang, $itech, $print_2, $common;	

 $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
 $fet_box = & new Template('logo_partner');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = 8 AND ID_CAT = 29";
			$sql="SELECT * FROM common  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('logo_partner_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$fet_sub->set( "idn", $rs['ID_COMMON']);									
				//$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
				$lk = $rs['SCONTENTSHORT'.$pre];
				//$lklogo .= '<a class="linkopacity" href="'.$lk.'" target="_blank" ><img class="border_rounded" border="0" src="images/bin/imagenews/'.$rs['PICTURE'].'" alt="logopartner" ></a>';
				$fet_sub->set( "lk", $lk);
				$fet_sub->set( "PICTURE", $rs['PICTURE']);
				$datasub .= $fet_sub->fetch("logo_partner_rs");

			}
			
			$fet_box->set( "list_partner", $datasub);
			// Get info datasub
			$data = $fet_box->fetch("logo_partner");

			
			return $data;

       }

       public function getlogo_partner($pre) {
           global $DB, $lang, $itech, $print_2, $common;	

           $main = & new Template('logo_partner');
           $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
           $fet_box = & new Template('logo_partner_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = 6 AND ID_CAT = 18";
			$sql="SELECT * FROM common  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$fet_box->set( "idn", $rs['ID_COMMON']);									
				//$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
				$lk = $rs['SCONTENTSHORT'.$pre];
				$fet_box->set( "lk", $lk);
				$fet_box->set( "PICTURE", $rs['PICTURE']);
				$datasub .= $fet_box->fetch("logo_partner_rs");

			}
			
			$main->set( "list_partner", $datasub);
			// Get info datasub
			$data = $main->fetch("logo_partner");

			return $data;

       }

       public function get_bannerqc($pre) {
            global $DB, $lang, $itech, $print_2, $common;   

            $main = & new Template('list_bannerqc');
            $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
            $fet_box = & new Template('list_bannerqc_rs');
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            
            $condit = " STATUS = 'Active' ID_COMMON =111";
            $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_CAT_SUB DESC Limit 0,3";    
            $query=$DB->query($sql);
            $lklogo = "";

            while ($rs=$DB->fetch_row($query))
            {                                                                                                                   
                $fet_box->set( "idn", $rs['ID_COMMON']);                                    
                $name_link = $print_2->vn_str_filter($rs['STITLE']);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
                $fet_box->set( "linklienket", $rs['SCONTENTSHORT'.$pre]);
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch("list_bannerqc_rs");

            }
            
            $main->set( "list_bannerqc_rs", $datasub);
            // Get info datasub
            $data = $main->fetch("list_bannerqc");

            return $data;

        }

//lay chi tiet
     public function get_tintuc($pre,$id_common) {
        global $DB, $lang, $itech, $print_2, $common;   

        $main = & new Template('list_tintuc');
        $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
        $fet_box = & new Template('list_tintuc_rs');
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            
            $condit = "ID_COMMON =  ".$id_common;
            $sql="SELECT * FROM common  WHERE ".$condit;    
            $query=$DB->query($sql);
            $lklogo = "";

            while ($rs=$DB->fetch_row($query))
            {                                                                                                                   
                $fet_box->set( "idn", $rs['ID_COMMON']);                                    
                //$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
                //$lk = $rs['SCONTENTSHORT'.$pre];
                //$fet_box->set( "lk", $lk);
                $name_link = $print_2->vn_str_filter($rs['STITLE']);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
                $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
                $fet_box->set( "noidung", base64_decode($rs['SCONTENTS'.$pre]));
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch("list_tintuc_rs");

            }
            
            $main->set( "list_tintuc_rs", $datasub);
            // Get info datasub
            $data = $main->fetch("list_tintuc");

            return $data;

        }
        public function get_tintuclq($pre,$id_cat) {
            global $DB, $lang, $itech, $print_2, $common;   

            $main = & new Template('list_tintuclq');
            $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
            $fet_box = & new Template('list_tintuclq_rs');
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            
            $condit = "ID_CAT =  ".$id_cat;
            $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_CAT DESC Limit 0,5";
            $query=$DB->query($sql);
            $lklogo = "";

            while ($rs=$DB->fetch_row($query))
            {                                                                                                                   
                $fet_box->set( "idn", $rs['ID_COMMON']);                                    
                //$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
                //$lk = $rs['SCONTENTSHORT'.$pre];
                //$fet_box->set( "lk", $lk);
                $name_link = $print_2->vn_str_filter($rs['STITLE']);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
                $fet_box->set( "linkvideo", $rs['SCONTENTSHORT'.$pre]);
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch("list_tintuclq_rs");

            }
            
            $main->set( "list_tintuclq_rs", $datasub);
            // Get info datasub
            $data = $main->fetch("list_tintuclq");

            return $data;

        }

       public function getlangvalue($inf,$fname,$pre,$listlang,$langdefault) {
           global $DB, $lang, $itech, $print_2, $common;	
           $lc = false;
           $kq ="";
           $num = 0;		
           $langget  = "";
           $arrlang = explode(",",$listlang);
           $numlang = count($arrlang);
           $filter_result = "";

           if($inf != ""){
              for($i=0;$i<count($arrlang);$i++){		
			// test tat ca
                 if($inf[$fname.$arrlang[$i]] != "") {
                     $lc = true;
                     $num++;
                     $langget = $arrlang[$i];
                 }

             }

		//1.tat ca khong co
             if(!$lc) {
              $kq ="info";
		//echo "kq=".$kq;
          }
		//2.tat ca deu co nhung can chon theo default
          elseif($num == $numlang) {
             $kq = $inf[$fname.$langdefault];
         }
		//3.lang dang chon khong co chuyen sang chon lang co khac
         elseif($inf[$fname.$pre] == "" && $lc) {
             if($langget == "_jp" || $langget == "_kr") {
                if($inf[$fname.$langdefault] != "") $kq = $inf[$fname.$langdefault];
                elseif($inf[$fname] != "") $kq = $inf[$fname];
                elseif($inf[$fname."_en"] != "") $kq = $inf[$fname."_en"];
                else $kq = "info";
            }
            else $kq = $inf[$fname.$langget];
        }
		//4.lang dang chon co
        elseif($inf[$fname.$pre] != "") {
         if($pre == "_jp" || $pre == "_kr") {
            if($inf[$fname.$langdefault] != "") $kq = $inf[$fname.$langdefault];
            elseif($inf[$fname] != "") $kq = $inf[$fname];
            elseif($inf[$fname."_en"] != "") $kq = $inf[$fname."_en"];
            else $kq = "info";				
        }
        else {
            if($inf[$fname.$langdefault] != "") $kq = $inf[$fname.$langdefault];
            else $kq = $inf[$fname.$pre];
        }

    }
    else $kq ="info";		

    $filter_result = $print_2->vn_str_filter($kq);
}	

return 	$filter_result;
}

public function get_sptb($pre,$idmnselected) {
	global $DB, $lang, $itech, $print_2, $common;	

 $fet_box = & new Template('list_sptb');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' ORDER BY IORDER LIMIT 0,4 ";
			$sql="SELECT * FROM categories  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('list_sptb_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$stt++;
				if($stt==4){
                    $fet_sub->set( "itemlast", " item-last");
                    $stt=0;
                }
                else
                    $fet_sub->set( "itemlast", "");

                if($rs['NAME_CATEGORY'.$pre]=="")
                    $resultname = $rs['NAME_CATEGORY'];
                else
                    $resultname = $rs['NAME_CATEGORY'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['NAME_CATEGORY']);
                $name_link = $this->getlangvalue($rs,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_CATEGORY']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['PICTURE'] != ""){						
					$origfolder = "imagenews";//$rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['PICTURE']);
              $fet_sub->set( "shortcontent", $rs['SCONTENTSHORT'.$pre] );

              $datasub .= $fet_sub->fetch("list_sptb_rs");

          }

          $fet_box->set( "list_sptb_rs", $datasub);
			// Get info datasub
          $data = $fet_box->fetch("list_sptb");


          return $data;

      }

      public function qc_list($pre, $idcm) {
       global $DB, $lang, $itech, $print_2, $common;	
	//Neu idcm =54 thi lay 3 hinh, neu idcm =1 thi lay 1 hinh
       $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
       $fet_box = & new Template('qc_list');
			$data = ""; // de lay du lieu
			$stt =0;
			if($idcm == 1)
             $condit = " STATUS = 'Active' AND ID_COMMON = 56 ";
         elseif($idcm == 2)
             $condit = " STATUS = 'Active' AND ID_COMMON IN (55,56) ";
         else
             $condit = " STATUS = 'Active' AND ID_COMMON IN (54,55,56) ";


         $sql="SELECT * FROM common  WHERE ".$condit;    
         $query=$DB->query($sql);
         $lklogo = "";
         $fet_sub = & new Template('qc_list_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$total_get = $common->CountRecord("common",$condit,"ID_COMMON");

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$stt++;
				$fet_sub->set( "idn", $rs['ID_COMMON']);									
				//$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
				$lk = $rs['SCONTENTSHORT'.$pre];
				//$lklogo .= '<a class="linkopacity" href="'.$lk.'" target="_blank" ><img class="border_rounded" border="0" src="images/bin/imagenews/'.$rs['PICTURE'].'" alt="logopartner" ></a>';
				$fet_sub->set( "lk", $lk);
				if($rs['STITLE'.$pre]=="")
                    $fet_sub->set( "title_qc", $rs['STITLE']);
                else
                    $fet_sub->set( "title_qc", $rs['STITLE'.$pre]);
                $fet_sub->set( "PICTURE", $rs['PICTURE']);
                if($rs['SCONTENTS'.$pre]=="")
                    $content_decode = base64_decode($rs['SCONTENTS']);
                else
                    $content_decode = base64_decode($rs['SCONTENTS'.$pre]);
                $fet_sub->set( "content", $content_decode);
                $numlast = $total_get - $stt;
                $fet_sub->set( "numlast", $numlast);
                $datasub .= $fet_sub->fetch("qc_list_rs");

            }

            $fet_box->set( "qc_list_rs", $datasub);
			// Get info datasub
            $data = $fet_box->fetch("qc_list");							

            return $data;			
        }

        public function menuleft_list_sub($tb, $condit, $field_name, $field_id, $field_name_other2, $idc, $idt, $pre) {
           global $DB, $lang, $itech, $print_2, $common;	

           $data = "";
           $stt = 0;
           if($tb !=""){			
             $temp1 = "menu_leftsub";

             $main = & new Template($temp1);
			// get catsub menu
             $sql2="SELECT * FROM ".$tb." WHERE ".$condit;	
             $query2=$DB->query($sql2);
             $num2=1;
             $data2 = "";			
             $temp2 = "menu_leftsub_rs";
             $tpl2= new Template($temp2);
             $total_get = $common->CountRecord($tb,$condit,"ID_TYPE");

             while($rs2=$DB->fetch_row($query2))
             {	
                $stt++;
                $numlast = $total_get - $stt;
                $tpl2->set( "numlast", $numlast);
				// get cat menu
                $sname2=$rs2[$field_name.$pre];
                $tpl2->set($field_id, $rs2[$field_id]);
                $tpl2->set('name', $sname2);
				//$name_link2 = $print_2->vn_str_filter($rs2[$field_name]);
                $name_link2 = $this->getlangvalue($rs2,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $tpl2->set('name_link', $name_link2);

				// get class 2 selected
                if($rs2[$field_id] == $idt){				
                   $cl3_selected = 'menu_c3_slted';
					// get name link menu selected in session for paging
                   $_SESSION['name_link'] = $name_link2;
					// controll display procat					
               }
               else{
                $cl3_selected = "";				
            }
            $tpl2->set('cl3_selected', $cl3_selected);					
            $tpl2->set('ID_CATEGORY', $idc);
				//$tpl2->set('ID_TYPE', $idt);				

				// set for field name other
            if(count($field_name_other2) > 1){
               for($i=0;$i<count($field_name_other2);$i++){
                  if(isset($rs2[$field_name_other2[$i].$pre]))
                      $tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
              }
          }



          $data2 .= $tpl2->fetch($temp2);
      }

			//$title_sub = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");			
			//$main->set( "title_sub", $title_sub['NAME_TYPE']);
      $title_sub = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");						
      $main->set( "title_sub", $title_sub['NAME_CATEGORY'.$pre]);

      $main->set( "list_sub_rs", $data2);
			// Get info datasub
      $data = $main->fetch($temp1);
  }

  return $data;

}

public function menuleft_list($tb, $condit, $field_name, $field_id, $field_name_other2, $idc, $idt, $pre) {
	global $DB, $lang, $itech, $print_2, $common;	

  $data = "";
  $stt = 0;
  if($tb !=""){			
     $temp1 = "menu_left";

     $main = & new Template($temp1);
			// get catsub menu
     $sql2="SELECT * FROM ".$tb." WHERE ".$condit;	
     $query2=$DB->query($sql2);
     $num2=1;
     $data2 = "";			
     $temp2 = "menu_left_rs";
     $tpl2= new Template($temp2);
     $total_get = $common->CountRecord($tb,$condit,$field_id);

     while($rs2=$DB->fetch_row($query2))
     {	
        $stt++;
        $numlast = $total_get - $stt;
        $tpl2->set( "numlast", $numlast);
				// get cat menu
        $sname2=$rs2[$field_name.$pre];
        $tpl2->set($field_id, $rs2[$field_id]);
        $tpl2->set('name', $sname2);

				//$name_link2 = $print_2->vn_str_filter($rs2[$field_name]);
        $name_link2 = $this->getlangvalue($rs2,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
        $tpl2->set('name_link', $name_link2);

				// get class 2 selected
        if($rs2[$field_id] == $idc){				
           $cl3_selected = 'menu_c3_slted';
					// get name link menu selected in session for paging
           $_SESSION['name_link'] = $name_link2;
					// controll display procat					
       }
       else{
        $cl3_selected = "";				
    }
    $tpl2->set('cl3_selected', $cl3_selected);					
				//$tpl2->set('ID_CATEGORY', $idc);
				//$tpl2->set('ID_TYPE', $idt);				

				// set for field name other
    if(count($field_name_other2) > 1){
       for($i=0;$i<count($field_name_other2);$i++){
          if(isset($rs2[$field_name_other2[$i].$pre]))
              $tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
      }
  }



  $data2 .= $tpl2->fetch($temp2);
}

			//$title_sub = $common->getInfo("categories_sub","ID_TYPE = '".$idt."'");			
			//$main->set( "title_sub", $title_sub['NAME_TYPE']);
			//$title_sub = $common->getInfo("categories","ID_CATEGORY = '".$idc."'");						
$main->set( "title_sub", $lang['tl_dmucsanpham']);

$main->set( "list_sub_rs", $data2);
			// Get info datasub
$data = $main->fetch($temp1);
}

return $data;

}

public function menungang_list_sub_pro($tb, $condit, $field_name, $field_id, $field_name_other2, $pre ) {
	global $DB, $lang, $itech, $print_2, $common;	

  $data = "";
  $stt = 0;
  if($tb !=""){			
     $temp1 = "menu_li";						

     $main = & new Template($temp1);
			// get catsub menu
     $sql2="SELECT * FROM ".$tb." WHERE ".$condit;	
     $query2=$DB->query($sql2);
     $num2=1;
     $data2 = "";			
     $temp2 = "menu_lipro_rs";

     $tpl2= new Template($temp2);			

     while($rs2=$DB->fetch_row($query2))
     {	

				// get cat menu
        $sname2=$rs2[$field_name.$pre];
        $tpl2->set($field_id, $rs2[$field_id]);
        $tpl2->set('name', $sname2);
				//$name_link2 = $print_2->vn_str_filter($rs2[$field_name]);
        $name_link2 = $this->getlangvalue($rs2,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
        $tpl2->set('name_link', $name_link2);																	

				// set for field name other
        if(count($field_name_other2) > 1){
           for($i=0;$i<count($field_name_other2);$i++){
              if(isset($rs2[$field_name_other2[$i].$pre]))
                  $tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
          }
      }


      $data2 .= $tpl2->fetch($temp2);
  }

  $main->set( "list_sub_rs", $data2);
			// Get info datasub
  $data = $main->fetch($temp1);
}

return $data;

}

public function menungang_list_sub_info($tb, $condit, $field_name, $field_id, $field_name_other2, $muc, $pre ) {
	global $DB, $lang, $itech, $print_2, $common;	

  $data = "";
  $stt = 0;
  if($tb !=""){			
     $temp1 = "menu_li";						

     $main = & new Template($temp1);
			// get catsub menu
     $sql2="SELECT * FROM ".$tb." WHERE ".$condit;	
     $query2=$DB->query($sql2);
     $num2=1;
     $data2 = "";			
     $temp2 = "menu_liinfo_rs";

     $tpl2= new Template($temp2);			

     while($rs2=$DB->fetch_row($query2))
     {	

				// get cat menu
        $sname2=$rs2[$field_name.$pre];
        $tpl2->set($field_id, $rs2[$field_id]);
        $tpl2->set('name', $sname2);
				//$name_link2 = $print_2->vn_str_filter($rs2[$field_name]);
        $name_link2 = $this->getlangvalue($rs2,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
        $tpl2->set('name_link', $name_link2);
        $tpl2->set('muc', $muc);
        $tpl2->set('idcat', $rs2['ID_CAT']);

				//$tpl2->set('ID_CATEGORY', $idc);
				//$tpl2->set('ID_TYPE', $idt);				

				// set for field name other
        if(count($field_name_other2) > 1){
           for($i=0;$i<count($field_name_other2);$i++){
              if(isset($rs2[$field_name_other2[$i].$pre]))
                  $tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
          }
      }


      $data2 .= $tpl2->fetch($temp2);
  }

  $main->set( "list_sub_rs", $data2);
			// Get info datasub
  $data = $main->fetch($temp1);
}

return $data;

}


//get san pham noi bat
    public function get_data($table,$idtype,$pre,$tieudiem,$tpl,$tpl_rs) {
        global $DB, $lang, $itech, $print_2, $common;   
        $main = & new Template($tpl);
        $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
        $fet_box = & new Template($tpl_rs);
            $datasub1 = ""; // de lay du lieu noi cac dong
            $datasub2 = "";
			$stt =0;
            $condit = " STATUS = 'Active'  AND ID_TYPE =".$idtype." AND HOME = '".$tieudiem."'";
            $sql="SELECT * FROM ".$table."  WHERE ".$condit." ORDER BY DATE_UPDATED DESC limit 0,12"; 
            $query=$DB->query($sql);
            $lklogo = "";
            while ($rs=$DB->fetch_row($query))
            {      
                
				$fet_box->set( "idn", $rs['ID_COMMON']);   
				if($rs['STITLE'.$pre]=='')
					$name_link = $print_2->vn_str_filter($rs['STITLE']);
				else 
					$name_link = $print_2->vn_str_filter($rs['STITLE'.$pre]);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
				//$contentshort = substr($rs['SCONTENTSHORT'.$pre],0,00).'...';
				$contentshort = $print_2->cleanchar( $rs['SCONTENTSHORT'.$pre], 240, false );
                $fet_box->set( "SCONTENTSHORT",$contentshort);
                $fet_box->set( "SCONTENTS", base64_decode($rs['SCONTENTS'.$pre ]));
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                if($stt < 6)
				$datasub1 .= $fet_box->fetch($tpl_rs);
				else
				$datasub2 .= $fet_box->fetch($tpl_rs);
				
				$stt++;
            }
            //printf($datasub);
            $main->set("list_sanphamnoibat_rs1", $datasub1);
			$main->set("list_sanphamnoibat_rs2", $datasub2);
            // Get info datasub
            $data = $main->fetch($tpl);
            // return json_encode($DB->fetch_row($query));
            return $data;
        }

		public function get_data_lq($idcat,$pre,$tpl,$tpl_rs) {
        global $DB, $lang, $itech, $print_2, $common;   
        $main = & new Template($tpl);
        $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
        $fet_box = & new Template($tpl_rs);
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            $condit = " STATUS = 'Active' AND ID_CAT =".$idcat;
            $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC limit 1,4"; 
            $query=$DB->query($sql);
            $lklogo = "";
            while ($rs=$DB->fetch_row($query))
            {      
                $fet_box->set( "idn", $rs['ID_COMMON']);   
				if($rs['STITLE'.$pre]=='')
					$name_link = $print_2->vn_str_filter($rs['STITLE']);
				else 
					$name_link = $print_2->vn_str_filter($rs['STITLE'.$pre]);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
				$contentshort = $print_2->cleanchar( $rs['SCONTENTSHORT'.$pre], 260, false );							 
                $fet_box->set( "SCONTENTSHORT", $contentshort);
                $fet_box->set( "SCONTENTS", base64_decode($rs['SCONTENTS'.$pre ]));
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch($tpl_rs);
            }
            //printf($datasub);
            $main->set( $tpl_rs, $datasub);
            // Get info datasub
            $data = $main->fetch($tpl);
            // return json_encode($DB->fetch_row($query));
            return $data;
        }
//get gia tri dinh duong
    public function get_giatridinhduong($pre) {
        global $DB, $lang, $itech, $print_2, $common;   
        $main = & new Template('list_giatridinhduong');
        $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
        $fet_box = & new Template('list_giatridinhduong_rs');
            $datasub = "";
            $stt =0;
            $condit = " STATUS = 'Active' AND ID_TYPE = 10 AND ID_CAT=45";
            $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC"; 
            $query=$DB->query($sql);
            $lklogo = "";
            while ($rs=$DB->fetch_row($query))
            {     
                $fet_box->set( "idn", $rs['ID_COMMON']);
				if ($rs['STITLE'.$pre]=='')
					$name_link = $print_2->vn_str_filter($rs['STITLE'.$pre]);
				else
					$name_link = $print_2->vn_str_filter($rs['STITLE']);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['STITLE'.$pre]);
                $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
                //$fet_box->set( "SCONTENTS", base64_decode($rs['SCONTENTS'.$pre ]));
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch("list_giatridinhduong_rs");
            }
            $main->set( "list_giatridinhduong_rs", $datasub);
            // Get info datasub
            $data = $main->fetch("list_giatridinhduong");
            // return json_encode($DB->fetch_row($query));
            return $data;
        }

public function menuleft_list_sub_info($tb, $condit, $field_name, $field_id, $field_name_other2, $muc, $idtype, $idcat, $idn, $pre ) {
	global $DB, $lang, $itech, $print_2, $common;	

  $data = "";
  $stt = 0;
  if($tb !=""){			
     $temp1 = "menu_left_info";						

     $main = & new Template($temp1);
			// get catsub menu
     $sql2="SELECT * FROM ".$tb." WHERE ".$condit;	
     $query2=$DB->query($sql2);
     $num2=1;
     $data2 = "";			
     $temp2 = "menu_left_info_rs";

     $tpl2= new Template($temp2);			
     $total_get = $common->CountRecord($tb,$condit,$field_id);

     while($rs2=$DB->fetch_row($query2))
     {	
        $stt++;
        $numlast = $total_get - $stt;
        $tpl2->set( "numlast", $numlast);
				// get cat menu
        $sname2=$rs2[$field_name.$pre];
        $tpl2->set($field_id, $rs2[$field_id]);
        $tpl2->set('name', $sname2);
				//$name_link2 = $print_2->vn_str_filter($rs2[$field_name]);
        $name_link2 = $this->getlangvalue($rs2,$field_name,$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
        $tpl2->set('name_link', $name_link2);
        $tpl2->set('muc', $muc);
        $tpl2->set('idcat', $rs2['ID_CAT']);
        $cl3_selected = "";
				// get for idcat menu
        if($idcat){
					// get class 2 selected
           if($rs2[$field_id] == $idcat){				
              $cl3_selected = 'menu_c3_slted';
						// get name link menu selected in session for paging
              $_SESSION['name_link'] = $name_link2;
						// controll display procat					
          }
          else{
           $cl3_selected = "";				
       }
   }
				// get for idcommon menu
   elseif($idn){
					// get class 2 selected
       if($rs2['ID_COMMON'] == $idn){				
          $cl3_selected = 'menu_c3_slted';
						// get name link menu selected in session for paging
          $_SESSION['name_link'] = $name_link2;
						// controll display procat					
      }
      else{
       $cl3_selected = "";				
   }
}

$tpl2->set('cl3_selected', $cl3_selected);

				//$tpl2->set('ID_CATEGORY', $idc);
				//$tpl2->set('ID_TYPE', $idt);				

				// set for field name other
if(count($field_name_other2) > 1){
   for($i=0;$i<count($field_name_other2);$i++){
      if(isset($rs2[$field_name_other2[$i].$pre]))
          $tpl2->set($field_name_other2[$i], $rs2[$field_name_other2[$i].$pre]);
  }
}


$data2 .= $tpl2->fetch($temp2);
}

$main->set( "list_sub_rs", $data2);
			// get title
$title_sub = $common->getInfo("common_type","ID_TYPE = '".$muc."'");
$main->set( "title_sub", $title_sub['SNAME'.$pre]);
			// Get info datasub
$data = $main->fetch($temp1);
}

return $data;

}

public function gethot_news_left($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " HOME=1 AND ID_TYPE = 3 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "PICTURE", $rs['PICTURE']);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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
         $datasub .= $fet_box->fetch("list_hotnews_rs");

     }

     if($datasub != "")
        $main->set('list_hotnews_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_hotnews_rs', $datasub);
    }

    return $main->fetch('list_hotnews');
}

public function gethot_news_right($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews_right');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_right_rs');
 $datasub = ""; 
 $stt =0;
			// de lay du lieu golf cornor
 $condit = " ID_TYPE = 6 AND STATUS = 'Active'";
 if($num == 0)
     $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
 else
     $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
 $query=$DB->query($sql); 										

 while ($rs=$DB->fetch_row($query))
 {								                   				
   $stt++;										
   $fet_box->set( "idn", $rs['ID_COMMON']);
   if($rs['STITLE'.$pre]==""){
       $linkname = $print_2->vn_str_filter($rs['STITLE']);
       $fet_box->set( "titlename", $rs['STITLE']);
   }
   else{
       $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
       $fet_box->set( "titlename", $rs['STITLE'.$pre]);
   }
   $fet_box->set( "linkname", $linkname);
   $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
   $fet_box->set( "PICTURE", $rs['PICTURE']);
   $fet_box->set( "stt", $stt);										
   $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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
 $datasub .= $fet_box->fetch("list_hotnews_right_rs");

}

if($datasub != "")
    $main->set('list_hotnews_right_rs', $datasub);
else{
    $fet_boxno = & new Template('noproduct');
    $text = "";
    $fet_boxno->set("text", $text);
    $datasub .= $fet_boxno->fetch("noproduct");
    $main->set('list_hotnews_right_rs', $datasub);
}

return $main->fetch('list_hotnews_right');
}

public function gethot_news_right_tintuc($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews_right_tintuc');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_right_rs');
 $datasub = ""; 
 $stt =0;
			// de lay du lieu golf cornor
 $condit = " ID_TYPE = 3 AND STATUS = 'Active'";
 if($num == 0)
     $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
 else
     $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
 $query=$DB->query($sql); 										

 while ($rs=$DB->fetch_row($query))
 {								                   				
   $stt++;										
   $fet_box->set( "idn", $rs['ID_COMMON']);
   if($rs['STITLE'.$pre]==""){
       $linkname = $print_2->vn_str_filter($rs['STITLE']);
       $fet_box->set( "titlename", $rs['STITLE']);
   }
   else{
       $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
       $fet_box->set( "titlename", $rs['STITLE'.$pre]);
   }
   $fet_box->set( "linkname", $linkname);
   $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
   $fet_box->set( "PICTURE", $rs['PICTURE']);
   $fet_box->set( "stt", $stt);										
   $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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
 $datasub .= $fet_box->fetch("list_hotnews_right_rs");

}

if($datasub != "")
    $main->set('list_hotnews_right_rs', $datasub);
else{
    $fet_boxno = & new Template('noproduct');
    $text = "";
    $fet_boxno->set("text", $text);
    $datasub .= $fet_boxno->fetch("noproduct");
    $main->set('list_hotnews_right_rs', $datasub);
}

return $main->fetch('list_hotnews_right_tintuc');
}

public function gethot_news_right_h($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews_right_h');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_right_h_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$ttal =0;
			
			$condit = " ID_TYPE = 3 AND ID_CAT NOT IN(19,20) AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql);
         $numrow = @mysqli_num_rows($query);

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;
           $ttal++;
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "PICTURE", $rs['PICTURE']);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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
         $datasub .= $fet_box->fetch("list_hotnews_right_h_rs");


     }

     if($datasub != "")
        $main->set('list_hotnews_right_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_hotnews_right_rs', $datasub);
    }

    return $main->fetch('list_hotnews_right_h');
}

public function gethot_news_right_group2($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews_rightg2');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_rightg2_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$ttal =0;
			
			$condit = " ID_TYPE = 3 AND ID_CAT NOT IN(19,20) AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql);
         $numrow = @mysqli_num_rows($query);

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;
           $ttal++;
           $fet_box->set( "idn".$stt, $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename".$stt, $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename".$stt, $rs['STITLE'.$pre]);
           }
           $fet_box->set( "linkname".$stt, $linkname);
           $fet_box->set( "SCONTENTSHORT".$stt, $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "PICTURE".$stt, $rs['PICTURE']);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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

         if($numrow >= 2 && $stt==2){
					// Get info datasub
           $datasub .= $fet_box->fetch("list_hotnews_rightg2_rs");
           $stt = 0;
       }
       elseif($numrow == $ttal){
					// Get info datasub
           $fet_box->set( "dis2", 'style="display:none"');
           $datasub .= $fet_box->fetch("list_hotnews_rightg2_rs");
           $stt = 0;
       }

   }

   if($datasub != "")
    $main->set('list_hotnews_right_rs', $datasub);
else{
    $fet_boxno = & new Template('noproduct');
    $text = "";
    $fet_boxno->set("text", $text);
    $datasub .= $fet_boxno->fetch("noproduct");
    $main->set('list_hotnews_right_rs', $datasub);
}

return $main->fetch('list_hotnews_rightg2');
}

public function gethot_news_right_group4($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hotnews_rightg4');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hotnews_rightg4_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$ttal =0;
			
			$condit = " ID_TYPE = 3 AND ID_CAT = 19 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql);
         $numrow = @mysqli_num_rows($query);

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;
           $ttal++;
           $fet_box->set( "idn".$stt, $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename".$stt, $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename".$stt, $rs['STITLE'.$pre]);
           }
           $fet_box->set( "linkname".$stt, $linkname);
           $fet_box->set( "SCONTENTSHORT".$stt, $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "PICTURE".$stt, $rs['PICTURE']);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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

         if($numrow == 2 && $stt==2){
					// Get info datasub
           $fet_box->set( "dis3", 'style="display:none"');
           $fet_box->set( "dis4", 'style="display:none"');
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }
       elseif($numrow == 3 && $stt==3){
					// Get info datasub					
           $fet_box->set( "dis4", 'style="display:none"');
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }
       elseif($numrow == 4 && $stt==4){
					// Get info datasub										
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }
       elseif($numrow > 4 && $stt==4){
					// Get info datasub										
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }
       elseif($numrow > 4 && $stt==4){
					// Get info datasub										
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }
       elseif($numrow > 4 && $stt==3 && $numrow == $ttal){
					// Get info datasub										
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $fet_box->set( "dis4", 'style="display:none"');
           $stt = 0;
       }
       elseif($numrow > 4 && $stt==2 && $numrow == $ttal){
					// Get info datasub										
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $fet_box->set( "dis3", 'style="display:none"');
           $fet_box->set( "dis4", 'style="display:none"');
           $stt = 0;
       }
       elseif($numrow == $ttal){
					// Get info datasub
           $fet_box->set( "dis2", 'style="display:none"');
           $fet_box->set( "dis3", 'style="display:none"');
           $fet_box->set( "dis4", 'style="display:none"');
           $datasub .= $fet_box->fetch("list_hotnews_rightg4_rs");
           $stt = 0;
       }

   }

   if($datasub != "")
    $main->set('list_hotnews_right_rs', $datasub);
else{
    $fet_boxno = & new Template('noproduct');
    $text = "";
    $fet_boxno->set("text", $text);
    $datasub .= $fet_boxno->fetch("noproduct");
    $main->set('list_hotnews_right_rs', $datasub);
}

return $main->fetch('list_hotnews_rightg4');
}

public function get_hdongttich($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_hdong_ttich');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('list_hdong_ttich_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " TIEUDIEM = 1 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "SCONTENTSHORT", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "PICTURE", $rs['PICTURE']);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

           if($rs['PICTURE'] != ""){
              $origfolder = "imagenews"; 
              $width = 250;
              $height = 215;
              $wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
              $fet_box->set( "w", $wh[0]);
              $fet_box->set( "h", $wh[1]);
          }
          else{
              $fet_box->set( "PICTURE", "noimages.jpg");
              $fet_box->set( "w", 186);
              $fet_box->set( "h", 172);					
          }
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
         $datasub .= $fet_box->fetch("list_hdong_ttich_rs");

     }

     if($datasub != "")
        $main->set('list_hdong_ttich_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_hdong_ttich_rs', $datasub);
    }

    return $main->fetch('list_hdong_ttich');
}

public function get_sanphamtieubieu($pre, $num = 0) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_sanphamtb');
 $field_name_other = array('DATE_UPDATE','TIEUBIEU','DATE_UPDATE');			
 $fet_box = & new Template('list_sanphamtb_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;			
			
			$condit = " TIEUBIEU = 1 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM products  WHERE ".$condit." ORDER BY DATE_UPDATE DESC, ID_PRODUCT DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM products  WHERE ".$condit." ORDER BY DATE_UPDATE DESC, ID_PRODUCT DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
            $stt++;										
            if($rs['PRODUCT_NAME'.$pre]=="")
                $resultname = $rs['PRODUCT_NAME'];
            else
                $resultname = $rs['PRODUCT_NAME'.$pre];
            $fet_box->set( "name", $resultname );
            $name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
            $fet_box->set('name_link', $name_link);									
            $fet_box->set( "ID", $rs['ID_PRODUCT']);
            $fet_box->set( "ID_CATEGORY", $rs['ID_CATEGORY']);

            $fet_box->set( "stt", $stt);										
            $fet_box->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);	

            if($rs['IMAGE_LARGE'] != ""){
              $origfolder = $rs['ID_CATEGORY']; 
              $width = 240;
              $height = 215;
              $wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
              $fet_box->set( "w", $wh[0]);
              $fet_box->set( "h", $wh[1]);
          }
          else{
              $fet_box->set( "IMAGE_LARGE", "noimages.jpg");
              $fet_box->set( "w", 186);
              $fet_box->set( "h", 172);					
          }
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

         if(isset($rs['PRICE'])){
          if($rs['PRICE'] > 0)
              $fet_box->set("PRICE".$p, number_format($rs['PRICE'],0,"",".")." VN&#272;");
          else
              $fet_box->set("PRICE".$p, "Call");

      }


					// Get info datasub
      $datasub .= $fet_box->fetch("list_sanphamtb_rs");

  }

  if($datasub != "")
    $main->set('list_sanphamtb_rs', $datasub);
else{
    $fet_boxno = & new Template('noproduct');
    $text = "";
    $fet_boxno->set("text", $text);
    $datasub .= $fet_boxno->fetch("noproduct");
    $main->set('list_sanphamtb_rs', $datasub);
}

return $main->fetch('list_sanphamtb');
}

public function get_sphot($pre,$idmnselected) {
	global $DB, $lang, $itech, $print_2, $common;	

 $fet_box = & new Template('list_sphot');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' AND TIEUBIEU = 1 ORDER BY DATE_UPDATE DESC LIMIT 0,10 ";
			$sql="SELECT * FROM products  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('list_sphot_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				if($rs['PRODUCT_NAME'.$pre]=="")
                    $resultname = $rs['PRODUCT_NAME'];
                else
                    $resultname = $rs['PRODUCT_NAME'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
                $name_link = $this->getlangvalue($rs,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_PRODUCT']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['IMAGE_LARGE'] != ""){						
					$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);

              $datasub .= $fet_sub->fetch("list_sphot_rs");

          }

          $fet_box->set( "list_sphot_rs", $datasub);
			// Get info datasub
          $data = $fet_box->fetch("list_sphot");


          return $data;

      }

      public function getimg_intro_banner($idps,$idps_sub) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('intro_main_products');
       $field_name_other = array('IORDER','NAME_IMG','TITLE_IMG','ID_PS','LINK');			
       $fet_box = & new Template('intro_main_products_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			if($idps_sub)
             $condit = " ID_PS = '".$idps."' AND ID_PS_SUB = '".$idps_sub."'";
         else
             $condit = " ID_PS = '".$idps."'";

         $sql="SELECT * FROM banner  WHERE ".$condit." ORDER BY IORDER LIMIT 0,10";    
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;					
           $fet_box->set( "idbn", $rs['ID_BN']);
           $fet_box->set( "stt", $stt);
           $fet_box->set( "LINK", $rs['LINK']);
           $fet_box->set( "NAME_IMG", $rs['NAME_IMG']);
           $fet_box->set( "TITLE_IMG", $rs['TITLE_IMG']);										

					// set neu co phan trang
           if ($page) $fet_box->set( "page", "&page=".$page);
           else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
			/*		
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}																			
				*/	
					// Get info datasub
					$datasub .= $fet_box->fetch("intro_main_products_rs");
					

				}

				if($datasub != "")
                    $main->set('list_banner', $datasub);
                else{
                    $fet_boxno = & new Template('noproduct');
                    $text = "";
                    $fet_boxno->set("text", $text);
                    $datasub .= $fet_boxno->fetch("noproduct");
                    $main->set('intro_main_products_rs', $datasub);
                }

                return $main->fetch('intro_main_products');
            }

            public function gettab_name($idps) {
               global $DB, $lang, $itech, $print_2, $common;	
               $pre = $_SESSION['pre'];

               $main = & new Template('main_tab');
               $field_name_other = array('ID_TYPE','IORDER','STATUS');			
               $fet_box = & new Template('list_tab_rs');
               $fet_content = & new Template('list_tabcontent_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$datacontent = "";
			$stt =0;
			
			$condit = " STATUS = 'Active' AND  ID_TYPE = '".$idps."'";
			$sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER LIMIT 0,4";    
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               if($stt==1) $fet_box->set( "cl", 'class="active"');
               else $fet_box->set( "cl", "");
               $fet_box->set( "idcat", $rs['ID_CAT']);
               $fet_box->set( "stt", $stt);
               $fet_box->set( "name", $rs['SNAME'.$pre]);
               $fet_content->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
               $fet_content->set( "content", base64_decode($rs['SCONTENTS'.$pre]));

					// Get info datasub
               $datasub .= $fet_box->fetch("list_tab_rs");
               $datacontent .= $fet_content->fetch("list_tabcontent_rs");

           }

           $main->set('list_tab_name', $datasub);
           $main->set('list_tabcontent_rs', $datacontent);


           return $main->fetch('main_tab');
       }

       public function getpro_tabcontent($idn) {
           global $DB, $lang, $itech, $print_2, $common;	
           $pre = $_SESSION['pre'];

           $main = & new Template('product_tab');
           $field_name_other = array('ID_TYPE','IORDER','STATUS');			
			//$fet_box = & new Template('list_tab_rs');
           $fet_content = & new Template('pro_tabcontent_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$datacontent = "";
			$stt =0;
			$contenti = array();
			
			//get detail
			if($idn){
             $pro_detail = $common->getInfo("products","ID_COMMON = '".$idn."' AND STATUS = 'Active'");
             $contenti[0] = base64_decode($pro_detail['INFO_DETAIL'.$pre]);
             $contenti[1] = base64_decode($pro_detail['PARAM'.$pre]);
             $contenti[2] = base64_decode($pro_detail['GALLERY'.$pre]);
             $contenti[3] = base64_decode($pro_detail['VIDEO'.$pre]);
             $contenti[4] = base64_decode($pro_detail['CHOSE'.$pre]);
             $contenti[5] = base64_decode($pro_detail['DOWNLOAD'.$pre]);
         }

         for($i=0;$i<=count($contenti);$i++){
             $fet_content->set( "content", $contenti[$i]);
             $datacontent .= $fet_content->fetch("pro_tabcontent_rs");
         }

         $main->set('pro_tabcontent_rs', $datacontent);

         return $main->fetch('product_tab');
     }

     public function get_tieudiem($pre, $num = 0) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_tieudiem');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_tieudiem_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " TIEUDIEM = 1 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC, ID_COMMON DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

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
         $datasub .= $fet_box->fetch("list_tieudiem_rs");

     }

     if($datasub != "")
        $main->set('list_tieudiem_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_tieudiem_rs', $datasub);
    }
				// get text_title
    $inf = $common->getInfo("common","ID_COMMON = 59");
    $main->set( "text_title", $inf['STITLE'.$pre]);

    return $main->fetch('list_tieudiem');
}

public function get_ask_ost($pre) {
	global $DB, $lang, $itech, $print_2, $common;					
 $fet_box = & new Template('ask_ost');
 $datasub = ""; 
 $stt = 0;
			//text_ask_ost
 $inf1 = $common->getInfo("common_cat_sub","ID_CAT_SUB = 78");
 $fet_box->set( "text_ask_ost", $inf1['SNAME'.$pre]);
			//phone
 $inf2 = $common->getInfo("common","ID_COMMON = 43");
 $fet_box->set( "text_phone", $inf2['STITLE'.$pre]);
 $fet_box->set( "linkchat_sky", $inf2['SCONTENTSHORT']);
			//email
 $inf3 = $common->getInfo("common","ID_COMMON = 44");
 $fet_box->set( "text_mail", $inf3['STITLE'.$pre]);
 $fet_box->set( "mail", $inf3['SCONTENTSHORT']);
			//chat
 $inf4 = $common->getInfo("common","ID_COMMON = 45");
 $fet_box->set( "text_chat", $inf4['STITLE'.$pre]);
 $fet_box->set( "linkchat_ya", $inf4['SCONTENTSHORT']);

 $datasub .= $fet_box->fetch("ask_ost");

 return $datasub;
}
public function get_box_multimedia($pre) {
	global $DB, $lang, $itech, $print_2, $common;					
 $fet_box = & new Template('multimedia');
 $datasub = ""; 
 $stt = 0;
			//multimedia_text
 $inf1 = $common->getInfo("common","ID_COMMON = 46");
 $fet_box->set( "multimedia_text", $inf1['STITLE'.$pre]);
 $fet_box->set( "multimedia_contentshort", $inf1['SCONTENTSHORT'.$pre]);			
 $fet_box->set( "namelink1", $print_2->vn_str_filter($inf1['STITLE']));
			//tour_text
 $inf2 = $common->getInfo("common","ID_COMMON = 47");
 $fet_box->set( "tour_text", $inf2['STITLE'.$pre]);
 $fet_box->set( "tour_contentshort", $inf2['SCONTENTSHORT'.$pre]);
 $fet_box->set( "namelink2", $print_2->vn_str_filter($inf2['STITLE']));
			//retail_text
 $inf3 = $common->getInfo("common","ID_COMMON = 48");
 $fet_box->set( "retail_text", $inf3['STITLE'.$pre]);
 $fet_box->set( "retail_contentshort", $inf3['SCONTENTSHORT'.$pre]);
 $fet_box->set( "namelink3", $print_2->vn_str_filter($inf3['STITLE']));
			//webcasts_text
 $inf4 = $common->getInfo("common","ID_COMMON = 49");
 $fet_box->set( "webcasts_text", $inf4['STITLE'.$pre]);
 $fet_box->set( "webcasts_contentshort", $inf4['SCONTENTSHORT'.$pre]);
 $fet_box->set( "namelink4", $print_2->vn_str_filter($inf4['STITLE']));
			//survey_text
 $inf5 = $common->getInfo("common","ID_COMMON = 67");
 $fet_box->set( "survey_text", $inf5['STITLE'.$pre]);
 $fet_box->set( "survey_contentshort", $inf5['SCONTENTSHORT'.$pre]);
 $fet_box->set( "namelink5", $print_2->vn_str_filter($inf5['STITLE']));

 $datasub .= $fet_box->fetch("multimedia");

 return $datasub;
}

public function get_box_mxh($pre) {
	global $DB, $lang, $itech, $print_2, $common;					
 $fet_box = & new Template('box_mxh');
 $datasub = ""; 
 $stt = 0;
			//mxh1
 $inf1 = $common->getInfo("common","ID_COMMON = 50");
 $fet_box->set( "text_mxh1", $inf1['STITLE'.$pre]);
 $fet_box->set( "contentshort1", $inf1['SCONTENTSHORT'.$pre]);			
			//mxh2
 $inf2 = $common->getInfo("common","ID_COMMON = 51");
 $fet_box->set( "text_mxh2", $inf2['STITLE'.$pre]);
 $fet_box->set( "contentshort2", $inf2['SCONTENTSHORT'.$pre]);
			//mxh3
 $inf3 = $common->getInfo("common","ID_COMMON = 52");
 $fet_box->set( "text_mxh3", $inf3['STITLE'.$pre]);
 $fet_box->set( "contentshort3", $inf3['SCONTENTSHORT'.$pre]);
			//mxh4
 $inf4 = $common->getInfo("common","ID_COMMON = 53");
 $fet_box->set( "text_mxh4", $inf4['STITLE'.$pre]);
 $fet_box->set( "contentshort4", $inf4['SCONTENTSHORT'.$pre]);
			//mxh5
 $inf5 = $common->getInfo("common","ID_COMMON = 54");
 $fet_box->set( "text_mxh5", $inf5['STITLE'.$pre]);
 $fet_box->set( "contentshort5", $inf5['SCONTENTSHORT'.$pre]);

 $datasub .= $fet_box->fetch("box_mxh");

 return $datasub;
}

public function getboxqcao($pre) {
	global $DB, $lang, $itech, $print_2, $common;					
 $fet_box = & new Template('box-qcao');
 $datasub = ""; 
 $stt = 0;
			//box qcao
 $inf1 = $common->getInfo("common","ID_COMMON = 69 AND STATUS='Active' ");
 if($inf1=="") $datasub = "";
 else {
     $fet_box->set( "content", base64_decode($inf1['SCONTENTS'.$pre]));
     $fet_box->set( "contentshort1", $inf1['SCONTENTSHORT'.$pre]);															
     $datasub .= $fet_box->fetch("box-qcao");
 }

 return $datasub;
}

public function get_video_cus($pre) {
	global $DB, $lang, $itech, $print_2, $common;	

 $main = & new Template('video_cus');
 $field_name_other = array('STITLE','ID_COMMON','IORDER','SCONTENTSHORT','PICTURE');
 $fet_box = & new Template('video_cus_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CAT_SUB = 24";
			$sql="SELECT * FROM common  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				$fet_box->set( "idn", $rs['ID_COMMON']);									
				//$fet_box->set( "list_provider", base64_decode($rs['SCONTENTS'.$pre]));
				$lk = $rs['SCONTENTSHORT'.$pre];
				$fet_box->set( "lk", $lk);
				$fet_box->set( "PICTURE", $rs['PICTURE']);
				$datasub .= $fet_box->fetch("video_cus_rs");

			}
			
			$main->set( "list_video_rs", $datasub);
			// get text_title
			$inf = $common->getInfo("common","ID_COMMON = 70");
			$main->set( "text_title", $inf['STITLE'.$pre]);
			// Get info datasub
			$data = $main->fetch("video_cus");

			return $data;

       }

       public function getlist_contact($tb_name, $condition, $field_id, $field_name, $field_name_other, $iorder, $temp_chid, $pre, $list_cked)

       {	
          global $DB, $lang, $itech, $std, $print_2, $common;
        $datasub = ""; // de lay du lieu noi cac dong

        $sql="SELECT * FROM ".$tb_name." WHERE ".$condition.$iorder;

			   //echo $sql;
        $query=$DB->query($sql);
        $num=0;

        $tpl= new Template($temp_chid);	  		
        while($rs=$DB->fetch_row($query))
        {
            $num++;

				// get name 
            $resultname = $rs[$field_name.$pre];

				// Set param
            $tpl->set( "name", $resultname );
            $name_link = $print_2->vn_str_filter($resultname);
            $tpl->set('name_link', $name_link);
            $tpl->set( "ID", $rs[$field_id]);
					// check check box checked
					//if(in_array($rs[$field_id], $list_cked))
					//$tpl->set('cked', "checked");
					//else $tpl->set('cked', "");
            $tpl->set( "num", $num );

            if(isset($rs['LINK']))
               $tpl->set( "link", $rs['LINK']);					

					// set for field name other chid
           if(count($field_name_other) > 1){
              for($i=0;$i<count($field_name_other);$i++){
                 if(isset($rs[$field_name_other[$i].$pre]))
                     $tpl->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
             }
         }


					// Get info datasub
         $datasub .= $tpl->fetch( $temp_chid );

     }

     return $datasub;
 }
 
 public function getmenu_account($pre, $num = 0, $member=0) {
   global $DB, $lang, $itech, $print_2, $common;	

   $main = & new Template('menu_account');					
   $tpl_login = & new Template('menu_account_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			if($member >0){
				$userinfo = $common->getInfo("consultant","ID_CONSULTANT = '".$member."'");
				$tpl_login->set('full_name', $userinfo['FULL_NAME']);
				$tpl_login->set('username', $userinfo['EMAIL']);
				$tpl_login->set('idu', $_SESSION['ID_CONSULTANT']);
				$tpl_login->set('CREATED_DATE', $common->datetimevn($userinfo['CREATED_DATE']));
				$tpl_login->set('LAST_LOGIN', $common->datetimevn($userinfo['LAST_LOGIN']));
				$tpl_login->set('AVATAR', $userinfo['AVATAR']);
				$owner = 0;
			}
			elseif(isset($_SESSION['ID_CONSULTANT']) && $_SESSION['ID_CONSULTANT'] != ""){
				$userinfo = $common->getInfo("consultant","ID_CONSULTANT = '".$_SESSION['ID_CONSULTANT']."'");
				$tpl_login->set('full_name', $userinfo['FULL_NAME']);
				$tpl_login->set('username', $userinfo['EMAIL']);
				$tpl_login->set('idu', $_SESSION['ID_CONSULTANT']);
				$tpl_login->set('CREATED_DATE', $common->datetimevn($userinfo['CREATED_DATE']));
				$tpl_login->set('LAST_LOGIN', $common->datetimevn($userinfo['LAST_LOGIN']));
				$tpl_login->set('AVATAR', $userinfo['AVATAR']);
				$owner = 1;
				
			}
			else{
				$tpl_login->set('full_name', "");
				$tpl_login->set('username', "");
				$tpl_login->set('idu', "");
				$tpl_login->set('CREATED_DATE', "");
				$tpl_login->set('LAST_LOGIN', "");
				$tpl_login->set('AVATAR', "");
				$owner = 0;
			}
			
			$datasub = $tpl_login->fetch("menu_account_rs");				
			$main->set('menu_account_rs', $datasub);
			$main->set('full_name', $userinfo['FULL_NAME']);
			$main->set('postby', $userinfo['ID_CONSULTANT']);
			$main->set('owner', $owner);

          return $main->fetch('menu_account');
      }

      public function getcat_title($idcat, $pre='') {
          global $DB, $lang, $itech, $std, $common;	 		
          $info = $common->getInfo("categories","ID_CATEGORY = '".$idcat."' AND STATUS = 'Active' ");
          $tpl = & new Template('cat_title_rs');
          $tpl->set('idcat', $idcat);
          $name_link = $this->getlangvalue($info,"NAME_CATEGORY",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
          $tpl->set('name_link', $name_link);
          $tpl->set('catname', $info['NAME_CATEGORY'.$pre]);
          $tpl->set('SCONTENTSHORT', $info['SCONTENTSHORT'.$pre]);

          return $tpl->fetch('cat_title_rs');
      }

      public function getcat_titlesv($idcat, $pre='') {
          global $DB, $lang, $itech, $std, $common;	 		
          $info = $common->getInfo("common_cat","ID_CAT = '".$idcat."' AND STATUS = 'Active' ");
          $tpl = & new Template('cat_titlesv_rs');
          $tpl->set('idcat', $idcat);
          $tpl->set('idtype', $info['ID_TYPE']);
          $name_link = $this->getlangvalue($info,"SNAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);
          $tpl->set('name_link', $name_link);
          $tpl->set('catname', $info['SNAME'.$pre]);
          $tpl->set('SCONTENTSHORT', $info['SCONTENTSHORT'.$pre]);

          return $tpl->fetch('cat_titlesv_rs');
      }

      public function gethomenews($pre, $num = 0) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_home_news');
       $field_name_other = array('STATUS','HT','IORDER','PICTURE');			
       $fet_box = & new Template('list_home_news_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_TYPE = 3 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,1";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "stt", $stt);															

					$origfolder = "imagenews"; // common folder
					$width = 251;
					$height = 151;
					$wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
					$fet_box->set( "w", $wh[0]);
					$fet_box->set( "h", $wh[1]);
					
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
                 $datasub .= $fet_box->fetch("list_home_news_rs");

             }

             if($datasub != "")
                $main->set('list_hotnews_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_hotnews_rs', $datasub);
            }				

            return $main->fetch('list_home_news');
        }

        public function gethomenews_right($pre, $num = 0, $tpl = 'list_hotnews', $tplsub = 'list_hotnews_rs') {
           global $DB, $lang, $itech, $print_2, $common;	

           $main = & new Template($tpl);
           $field_name_other = array('STITLE','ID_COMMON','IORDER','PICTURE');			
           $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_TYPE = 3 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

					$origfolder = "imagenews"; // common folder
					$width = 57;
					$height = 42;
					$wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
					$fet_box->set( "w", $wh[0]);
					$fet_box->set( "h", $wh[1]);
					
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
                 $datasub .= $fet_box->fetch($tplsub);

             }

             if($datasub != "")
                $main->set('list_hotnews_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_hotnews_rs', $datasub);
            }
				// get text_title
            $inf = $common->getInfo("common","ID_COMMON = 60");
            $main->set( "text_title", $inf['STITLE'.$pre]);

            return $main->fetch($tpl);
        }

        public function getmenusp($pre, $num = 0, $tpl = 'list_menusp', $tplsub = 'list_menusp_rs') {
           global $DB, $lang, $itech, $print_2, $common, $print;	

           $main = & new Template($tpl);
           $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
           $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM categories  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM categories  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_CATEGORY']);
           if($rs['NAME_CATEGORY'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY']);
               $fet_box->set( "titlename", $rs['NAME_CATEGORY']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY'.$pre]);
               $fet_box->set( "titlename", $rs['NAME_CATEGORY'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
        $main->set('list_menusp_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menusp_rs', $datasub);
    }				

    return $main->fetch($tpl);
}
public function get_list_bao_gia($pre,$id_cat,$id_type,$num = 0, $tpl = 'list_bao_gia', $tplsub = 'list_bao_gia_rs') {
	global $DB, $lang, $itech, $print_2, $common, $print;	
	
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','INFO_SHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CATEGORY = '".$id_cat."' AND ID_TYPE = '".$id_type."'";
			if($num == 0)
             $sql="SELECT * FROM products  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM products  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_CATEGORY']);
           if($rs['PRODUCT_NAME'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
               $fet_box->set( "titlename", $rs['PRODUCT_NAME']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['PRODUCT_NAME'.$pre]);
               $fet_box->set( "titlename", $rs['PRODUCT_NAME'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['INFO_SHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "detail",base64_decode($rs['INFO_DETAIL'.$pre]));
           $fet_box->set( "stt", $stt);															
           $fet_box->set( "price",$rs['PRICE'.$pre]);
           $fet_box->set( "param",base64_decode($rs['PARAM'.$pre]));
           $fet_box->set( "sale",$rs['SALE'.$pre]);
           $fet_box->set( "code",$rs['CODE'.$pre]);

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
        $main->set('list_bao_gia_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_bao_gia_rs', $datasub);
    }				

    return $main->fetch($tpl);
}




public function getmenu_baogia($pre, $num = 0, $tpl = 'list_menudl', $tplsub = 'list_menudl_rs') {
	global $DB, $lang, $itech, $print_2, $common, $print;	
	
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','NAME_TYPE');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM categories_sub  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM categories_sub  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_TYPE']);
           $fet_box->set( "idcat", $rs['ID_CATEGORY']);
           if($rs['NAME_TYPE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['NAME_TYPE']);
               $fet_box->set('linkname',$linkname);
               $fet_box->set( "titlename", $rs['NAME_TYPE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['NAME_TYPE'.$pre]);
               $fet_box->set( "titlename", $rs['NAME_TYPE'.$pre]);
               $fet_box->set('linkname',$linkname);
           }
					//$fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
					//$fet_box->set( "linkname", $linkname);
					//$fet_box->set( "stt", $stt);															


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
        $main->set('list_menubg_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menubg_rs', $datasub);
    }				

    return $main->fetch($tpl);
}

public function getmenu_common($pre, $num = 0, $tpl = 'list_menugt', $tplsub = 'list_menugt_rs', $idtype) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
	
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = '".$idtype."'";
			if($num == 0)
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_TYPE']);
           $fet_box->set( "idcat", $rs['ID_CAT']);
           if($rs['SNAME'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['SNAME']);
               $fet_box->set( "titlename", $rs['SNAME']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
               $fet_box->set( "titlename", $rs['SNAME'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
        $main->set('list_menusp_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menusp_rs', $datasub);
    }				

    return $main->fetch($tpl);
}

public function getmenugt($pre, $num = 0, $tpl = 'list_menugt', $tplsub = 'list_menugt_rs', $idtype) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
	
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = '".$idtype."'";
			if($num == 0)
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_TYPE']);
           $fet_box->set( "idcat", $rs['ID_CAT']);
           if($rs['SNAME'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['SNAME']);
               $fet_box->set( "titlename", $rs['SNAME']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
               $fet_box->set( "titlename", $rs['SNAME'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
	 
	 if($idtype==11) $datasub = "";

    
    $main->set('list_menusp_rs', $datasub);
   
	// get name type
	$typeinfo = $common->getInfo("common_type","ID_TYPE = '".$idtype."'");
	$main->set('nametype', $typeinfo['SNAME'.$pre]);
	$linktype = $print_2->vn_str_filter($typeinfo['SNAME']);
	$main->set('linktype', $linktype);
	$main->set('idtype', $idtype);
	
	if($idtype == 1) $i = 2;
	elseif($idtype == 2) $i = 3;
	elseif($idtype == 9) $i = 4;
	elseif($idtype == 4) $i = 5;
	elseif($idtype == 10) $i = 6;
	elseif($idtype == 11) $i = 7;
	
	if ($i==@$_SESSION['tab']){				
		$selected = 'class="active"';
		$main->set('st_select',$selected);								
		}
	else{				
		$selected = '';
		$main->set('st_select',$selected);
		}

    return $main->fetch($tpl);
}


public function getmenuvnl($pre, $num = 0, $tpl = 'list_menuvnl', $tplsub = 'list_menuvnl_rs', $idtype) {
    global $DB, $lang, $itech, $print_2, $common, $print;   
    
    $main = & new Template($tpl);
    $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');         
    $fet_box = & new Template($tplsub);
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            
            $condit = " STATUS = 'Active' AND ID_TYPE = '".$idtype."'";
            if($num == 0)
                $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER ";
            else
                $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
            $query=$DB->query($sql);                                        

            while ($rs=$DB->fetch_row($query))
            {                                                               
                $stt++;                                     
                $fet_box->set( "idn", $rs['ID_TYPE']);
                $fet_box->set( "idcat", $rs['ID_CAT']);
                if($rs['SNAME'.$pre]==""){
                    $linkname = $print_2->vn_str_filter($rs['SNAME']);
                    $fet_box->set( "titlename", $rs['SNAME']);
                }
                else{
                    $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
                    $fet_box->set( "titlename", $rs['SNAME'.$pre]);
                }
                $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
                $main->set('list_menusp_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_menusp_rs', $datasub);
            }               

            return $main->fetch($tpl);
        }

        public function getmenusv($pre, $num = 0, $tpl = 'list_menusv', $tplsub = 'list_menusv_rs', $idtype) {
           global $DB, $lang, $itech, $print_2, $common, $print;	

           $main = & new Template($tpl);
           $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
           $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = '".$idtype."'";
			if($num == 0)
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_TYPE']);
           $fet_box->set( "idcat", $rs['ID_CAT']);
           if($rs['SNAME'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['SNAME']);
               $fet_box->set( "titlename", $rs['SNAME']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
               $fet_box->set( "titlename", $rs['SNAME'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
        $main->set('list_menusp_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menusp_rs', $datasub);
    }
	
	$typein = $common->getInfo("common_type"," STATUS = 'Active' AND ID_TYPE = '".$idtype."'");
	$main->set('titletype', $typein['SNAME'.$pre]);

    return $main->fetch($tpl);
}

public function getmenusp_sub($pre, $num = 0, $tpl = 'list_menusp', $tplsub = 'list_menusp_rs', $idc) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
	
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_CATEGORY = '".$idc."' AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM categories_sub  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM categories_sub  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idc", $rs['ID_CATEGORY']);
           $fet_box->set( "idt", $rs['ID_TYPE']);
           if($rs['NAME_TYPE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['NAME_TYPE']);
               $fet_box->set( "titlename", $rs['NAME_TYPE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['NAME_TYPE'.$pre]);
               $fet_box->set( "titlename", $rs['NAME_TYPE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
        $main->set('list_menusp_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menusp_rs', $datasub);
    }				

    return $main->fetch($tpl);
}
public function get_menu_lq($pre) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
 $tpl = 'list_menu_lq';
 $tplsub = 'list_menu_lq_rs';
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;			
			$condit = "STATUS = 'Active'";
			$sql="SELECT * FROM categories  WHERE ".$condit." ORDER BY IORDER ";
			$query=$DB->query($sql); 										
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;										
               $fet_box->set( "idc", $rs['ID_CATEGORY']);
               if($rs['NAME_CATEGORY'.$pre]==""){
                   $linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY']);
                   $fet_box->set( "titlename", $rs['NAME_CATEGORY']);
               }
               else{
                   $linkname = $print_2->vn_str_filter($rs['NAME_CATEGORY'.$pre]);
                   $fet_box->set( "titlename", $rs['NAME_CATEGORY'.$pre]);
               }
               $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
            $main->set('list_menu_lq_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_menu_lq_rs', $datasub);
        }				

        return $main->fetch($tpl);
    }

    public function getmenusv_sub($pre, $num = 0, $tpl = 'list_menusv', $tplsub = 'list_menusv_rs', $idcat) {
       global $DB, $lang, $itech, $print_2, $common, $print;	

       $main = & new Template($tpl);
       $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
       $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$infotype = $common->getInfo("common_cat","ID_CAT = '".$idcat."'");
			
			$condit = " STATUS = 'Active' AND ID_CAT = '".$idcat."'";
			if($num == 0)
             $sql="SELECT * FROM common_cat_sub  WHERE ".$condit." ORDER BY IORDER ";
         else
             $sql="SELECT * FROM common_cat_sub  WHERE ".$condit." ORDER BY IORDER LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idt", $infotype['ID_TYPE']);
           $fet_box->set( "idcat", $rs['ID_CAT']);
           $fet_box->set( "ids", $rs['ID_CAT_SUB']);
           if($rs['SNAME'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['SNAME']);
               $fet_box->set( "titlename", $rs['SNAME']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
               $fet_box->set( "titlename", $rs['SNAME'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
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
        $main->set('list_menusp_rs', $datasub);
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $main->set('list_menusp_rs', $datasub);
    }				

    return $main->fetch($tpl);
}
public function get_catsub($pre,$idtype) {
	global $DB, $lang, $itech, $print_2, $common, $print;	
 $tpl = 'list_dv'; 
 $tplsub = 'list_dv_rs';
 $main = & new Template($tpl);
 $field_name_other = array('STATUS','PICTURE','IORDER','SCONTENTSHORT');			
 $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			$condit = " STATUS = 'Active' AND ID_TYPE = ".$idtype;
			$sql="SELECT * FROM common_cat  WHERE ".$condit." ORDER BY IORDER ";
			$query=$DB->query($sql); 
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;										
               $fet_box->set( "idt", $rs['ID_TYPE']);
               $fet_box->set( "idcat", $rs['ID_CAT']);
               if($rs['SNAME'.$pre]==""){
                   $linkname = $print_2->vn_str_filter($rs['SNAME']);
                   $fet_box->set( "titlename", $rs['SNAME']);
               }
               else{
                   $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
                   $fet_box->set( "titlename", $rs['SNAME'.$pre]);
               }
               $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
               $fet_box->set( "content", $rs['SCONTENTS'.$pre]);
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
            $main->set('list_dv_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_dv_rs', $datasub);
        }				

        return $main->fetch($tpl);
    }
	

        public function listsptb($pre,$idmnselected) {
           global $DB, $lang, $itech, $print_2, $common;	

           $fet_box = & new Template('listsptb');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = 2 ORDER BY DATE_UPDATE DESC LIMIT 0,16 ";
			$sql="SELECT * FROM products  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('listsptb_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				if($rs['PRODUCT_NAME'.$pre]=="")
                    $resultname = $rs['PRODUCT_NAME'];
                else
                    $resultname = $rs['PRODUCT_NAME'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
                $name_link = $this->getlangvalue($rs,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_PRODUCT']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
				// get star danh gia comment
                $star = $print_2->getstar($rs['ID_PRODUCT']);
                $fet_sub->set('star', $star);

				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['IMAGE_LARGE'] != ""){						
					$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);

              if(isset($rs['PRICE'])){
                  if($rs['PRICE'] > 0)
                      $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");
                  else
                      $fet_sub->set("PRICE", "Call");

              }

              if(isset($rs['SALE'])){
                  if($rs['SALE'] != "" && $rs['SALE']>0){
                     $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"","."));
                     $fet_sub->set("PRICE_SALE", number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"","."));
                     $fet_sub->set("SALE", $rs['SALE']);
                     $fet_sub->set("PRICE_DISC", number_format($rs['PRICE']*$rs['SALE']/100,0,"","."));
                 }
                 else{
                     $fet_sub->set("PRICE_SALE", "");
                     $fet_sub->set("SALE", "");
                     $fet_sub->set("PRICE_DISC", "");
                 }

             }

             $datasub .= $fet_sub->fetch("listsptb_rs");

         }

         $fet_box->set( "list_sptb_rs", $datasub);
			// Get info datasub
         $data = $fet_box->fetch("listsptb");


         return $data;

     }


     public function getclip() {
       global $DB, $lang, $itech, $print_2, $common;	

       if($_SESSION['dv_Type']=="phone"){
         $tmp = "m_list_clip";
         $tmp_rs = "m_list_clip_rs";
     }
     else {
         $tmp = "list_clip";
         $tmp_rs = "list_clip_rs";
     }

     $main = & new Template($tmp);
     $fet_box = & new Template($tmp_rs);
			//$clip_rs = $common->gethonhop($idn);
     $datasub = "";

     $condit = " ID_TYPE=3 AND ID_CAT=20 AND STATUS = 'Active' ";
     $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,1";    
     $query=$DB->query($sql); 										

     while ($rs=$DB->fetch_row($query))
     {								                   				
       $stt++;										
					//$fet_box->set( "clip_rs", base64_decode($rs['SCONTENTS']));
       $fet_box->set( "clip_rs", $rs['SCONTENTSHORT']);

					// Get info datasub
       $datasub .= $fet_box->fetch($tmp_rs);

   }

   $main->set('list_clip_rs', $datasub);

   return $main->fetch($tmp);
}

public function getSupportOnline($idn) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('list_support');
 $fet_box = & new Template('list_support_rs');
 $cty = $common->gethonhop($idn);
 if($cty == "")
     $fet_box->set('cty', "");
 else{				
     $fet_box->set('cty', base64_decode($cty['SCONTENTS']));
 }

 $main->set('list_support_rs', $fet_box);

 return $main->fetch('list_support');
}

public function getimg_slide_mobi($idps) {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('m_list_img_slide');
 $field_name_other = array('IORDER','NAME_IMG','TITLE_IMG','ID_PS','LINK');			
 $fet_box = & new Template('m_list_img_slide_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_PS = '".$idps."'";
			$sql="SELECT * FROM banner  WHERE ".$condit." ORDER BY IORDER LIMIT 0,8";    
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idbn", $rs['ID_BN']);
               $fet_box->set( "stt", $stt);
               $fet_box->set( "LINK", $rs['LINK']);
               $fet_box->set( "NAME_IMG", $rs['NAME_IMG']);
               $fet_box->set( "TITLE_IMG", $rs['TITLE_IMG']);										

					// set neu co phan trang
               if ($page) $fet_box->set( "page", "&page=".$page);
               else $fet_box->set("page", "");
					//if(isset($rs['LINK']))
					//$fet_box->set( "link", $rs['LINK']);					
			/*		
					// set for field name other chid
					if(count($field_name_other) > 0){
						for($i=0;$i<count($field_name_other);$i++){
							if(isset($rs[$field_name_other[$i].$pre]))
							$fet_box->set($field_name_other[$i], $rs[$field_name_other[$i].$pre]);
						}
					}																			
				*/	
					// Get info datasub
					$datasub .= $fet_box->fetch("m_list_img_slide_rs");
					

				}

				if($datasub != "")
                    $main->set('list_img_slide_rs', $datasub);
                else{
                    $fet_boxno = & new Template('noproduct');
                    $text = "";
                    $fet_boxno->set("text", $text);
                    $datasub .= $fet_boxno->fetch("noproduct");
                    $main->set('list_img_slide_rs', $datasub);
                }

                return $main->fetch('m_list_img_slide');
            }

            public function m_gethomenews($pre, $num = 0) {
               global $DB, $lang, $itech, $print_2, $common;	

               $main = & new Template('m_list_home_news');
               $field_name_other = array('STATUS','HT','IORDER','PICTURE');			
               $fet_box = & new Template('m_list_home_news_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " ID_TYPE = 3 AND STATUS = 'Active'";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,1";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "stt", $stt);															

					$origfolder = "imagenews"; // common folder
					$width = 251;
					$height = 151;
					$wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
					$fet_box->set( "w", $wh[0]);
					$fet_box->set( "h", $wh[1]);
					
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
                 $datasub .= $fet_box->fetch("m_list_home_news_rs");

             }

             if($datasub != "")
                $main->set('list_hotnews_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_hotnews_rs', $datasub);
            }				

            return $main->fetch('m_list_home_news');
        }

        public function m_gethomenews_right($pre, $num = 0, $tpl = 'm_list_hotnews', $tplsub = 'm_list_hotnews_rs') {
           global $DB, $lang, $itech, $print_2, $common;	

           $main = & new Template($tpl);
           $field_name_other = array('STITLE','ID_COMMON','IORDER','PICTURE');			
           $fet_box = & new Template($tplsub);
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE = 3";
			if($num == 0)
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,4";
         else
             $sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1,".$num;
         $query=$DB->query($sql); 										

         while ($rs=$DB->fetch_row($query))
         {								                   				
           $stt++;										
           $fet_box->set( "idn", $rs['ID_COMMON']);
           if($rs['STITLE'.$pre]==""){
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "titlename", $rs['STITLE']);
           }
           else{
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
           }
           $fet_box->set( "contentshort", $rs['SCONTENTSHORT'.$pre]);
           $fet_box->set( "linkname", $linkname);
           $fet_box->set( "stt", $stt);										
           $fet_box->set( "dateupdated", $common->datevn($rs['DATE_UPDATED']));

					$origfolder = "imagenews"; // common folder
					$width = 57;
					$height = 42;
					$wh = $print_2->changesizeimage($rs['PICTURE'], $origfolder, $width, $height);
					$fet_box->set( "w", $wh[0]);
					$fet_box->set( "h", $wh[1]);
					
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
                 $datasub .= $fet_box->fetch($tplsub);

             }

             if($datasub != "")
                $main->set('list_hotnews_rs', $datasub);
            else{
                $fet_boxno = & new Template('noproduct');
                $text = "";
                $fet_boxno->set("text", $text);
                $datasub .= $fet_boxno->fetch("noproduct");
                $main->set('list_hotnews_rs', $datasub);
            }
				// get text_title
				//$inf = $common->getInfo("common","ID_COMMON = 60");
				//$main->set( "text_title", $inf['STITLE'.$pre]);

            return $main->fetch($tpl);
        }

        public function m_listsptb($pre,$idmnselected) {
           global $DB, $lang, $itech, $print_2, $common;	

           $fet_box = & new Template('m_listsptb');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' AND TIEUBIEU = 1 ORDER BY DATE_UPDATE DESC LIMIT 0,16 ";
			$sql="SELECT * FROM products  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('m_listsptb_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				if($rs['PRODUCT_NAME'.$pre]=="")
                    $resultname = $rs['PRODUCT_NAME'];
                else
                    $resultname = $rs['PRODUCT_NAME'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
                $name_link = $this->getlangvalue($rs,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_PRODUCT']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['IMAGE_LARGE'] != ""){						
					$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);

              if(isset($rs['PRICE'])){
                  if($rs['PRICE'] > 0)
                      $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");
                  else
                      $fet_sub->set("PRICE", "Call");

              }

              if(isset($rs['SALE'])){
                  if($rs['SALE'] != "" && $rs['SALE']>0){
                     $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"","."));
                     $fet_sub->set("PRICE_SALE", number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"","."));
                     $fet_sub->set("SALE", $rs['SALE']);
                 }
                 else{
                     $fet_sub->set("PRICE_SALE", "");
                     $fet_sub->set("SALE", "");
                 }

             }

             $datasub .= $fet_sub->fetch("m_listsptb_rs");

         }

         $fet_box->set( "list_sptb_rs", $datasub);
			// Get info datasub
         $data = $fet_box->fetch("m_listsptb");


         return $data;

     }

     public function getnews_ud_home() {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_newsrun');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_newsrun_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=9 AND ID_CAT=28  ";
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1";     
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("list_newsrun_rs");

         }

         if($datasub != "")
            $main->set('list_newsrun_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_newsrun_rs', $datasub);
        }

        return $main->fetch('list_newsrun');
    }
    public function getnews_ud_lq() {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_new_udlq');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_news_udlq_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=9 AND ID_CAT=28  ";
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1,4";     
			$query=$DB->query($sql); 										
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("list_news_udlq_rs");

         }

         if($datasub != "")
            $main->set('list_news_udlq_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_news_udlq_rs', $datasub);
        }

        return $main->fetch('list_new_udlq');
    }
    public function getnews_kienthuc_lamdep($idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('lamdep');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('lamdep_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=7 AND ID_CAT=".$idcat;
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC";     
			$query=$DB->query($sql); 										
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("lamdep_rs");

         }

         if($datasub != "")
            $main->set('lamdep_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('lamdep_rs', $datasub);
        }

        return $main->fetch('lamdep');
    }
    public function get_ykien($idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('ykien');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('ykien_rs');
       $datasub = "";
			$datasub_row = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=9 AND ID_CAT=".$idcat;
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,3"; 
			$sql_row="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 3,6"; 			
			$query=$DB->query($sql); 	
			$query_row=$DB->query($sql_row);			
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("ykien_rs");

         }
         while ($rs_row=$DB->fetch_row($query_row))
         {								                   				
           $stt++;					
           $fet_box->set( "idhot", $rs_row['IDHOT']);
           $fet_box->set( "idn", $rs_row['ID_COMMON']);
           $linkname_row = $print_2->vn_str_filter($rs_row['STITLE']);
           $fet_box->set( "linkname", $linkname_row);
           $fet_box->set( "stt", $stt);					
           $fet_box->set( "titlename", $rs_row['STITLE']);
           $fet_box->set("shortcontent",$rs_row['SCONTENTSHORT']);
           $fet_box->set("picture",$rs_row['PICTURE']);
					// set neu co phan trang
           if ($page) $fet_box->set( "page", "&page=".$page);
           else $fet_box->set("page", "");								

					// set for field name other chid
           if(count($field_name_other) > 0){
              for($i=0;$i<count($field_name_other);$i++){
                 if(isset($rs_row[$field_name_other[$i].$pre]))
                     $fet_box->set($field_name_other[$i], $rs_row[$field_name_other[$i].$pre]);
             }
         }																			

					// Get info datasub
         $datasub_row .= $fet_box->fetch("ykien_rs");

     }

     if($datasub_row != ""){
        $main->set('ykien_rs', $datasub_row);	
        $main->set('ykien_row_rs', $datasub_row);				
    }
    else{
        $fet_boxno = & new Template('noproduct');
        $text = "";
        $fet_boxno->set("text", $text);
        $datasub .= $fet_boxno->fetch("noproduct");
        $datasub_row .= $fet_boxno->fetch("noproduct");
        $main->set('ykien_rs',$datasub);
        $main->set('ykien_row_rs', $datasub_row);
    }

    return $main->fetch('ykien');
}
public function get_ykien_info() {
	global $DB, $lang, $itech, $print_2, $common;	
	
 $main = & new Template('get_ykien');
 $field_name_other = array('STITLE','ID_COMMON','IORDER');			
 $fet_box = & new Template('get_ykien_rs');
 $datasub = "";
			$datasub_row = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=9 AND ID_CAT= 29";
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 0,2"; 
			//$sql_row="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 3,6"; 			
			$query=$DB->query($sql); 	
			//$query_row=$DB->query($sql_row);			
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("get_ykien_rs");

         }

         if($datasub_row != ""){
            $main->set('get_ykien_rs', $datasub_row);				
        }
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('get_ykien_rs',$datasub);
        }

        return $main->fetch('get_ykien');
    }
    public function get_videoclip($idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('video');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('video_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=9 AND ID_CAT=".$idcat;
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC";     
			$query=$DB->query($sql); 										
			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
					//$fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("video_rs");

         }

         if($datasub != "")
            $main->set('video_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('video_rs', $datasub);
        }

        return $main->fetch('video');
    }
    public function get_khuyenmai() {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_khuyenmai');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_khuyenmai_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_TYPE=4 AND ID_CAT=32  ";
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1";     
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE']);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE']);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT']);
               $fet_box->set("picture",$rs['PICTURE']);
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
             $datasub .= $fet_box->fetch("list_khuyenmai_rs");

         }

         if($datasub != "")
            $main->set('list_khuyenmai_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_khuyenmai_rs', $datasub);
        }

        return $main->fetch('list_newsrun');
    }
    public function get_list_data($pre,$idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_data');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_data_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CAT = ".$idcat;
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 1";     
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT'.$pre]);
               $fet_box->set("picture",$rs['PICTURE']);
               $fet_box->set("content",base64_decode($rs['SCONTENTS'.$pre]));
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
             $datasub .= $fet_box->fetch("list_data_rs");

         }

         if($datasub != "")
            $main->set('list_data_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_data_rs', $datasub);
        }

        return $main->fetch('list_data');
    }
    public function get_list_data_cat($pre,$idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_data');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_data_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CAT = ".$idcat;
			$sql="SELECT * FROM common_cat  WHERE ".$condit;     
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_CAT']);
               $linkname = $print_2->vn_str_filter($rs['SNAME'.$pre]);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['SNAME'.$pre]);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT'.$pre]);
               $fet_box->set("picture",$rs['PICTURE']);
               $fet_box->set("content",base64_decode($rs['SCONTENTS'.$pre]));
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
             $datasub .= $fet_box->fetch("list_data_rs");

         }

         if($datasub != "")
            $main->set('list_data_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_data_rs', $datasub);
        }

        return $main->fetch('list_data');
    }
    public function get_dv_lienquan($pre,$idcat) {
       global $DB, $lang, $itech, $print_2, $common;	

       $main = & new Template('list_dv_lienquan');
       $field_name_other = array('STITLE','ID_COMMON','IORDER');			
       $fet_box = & new Template('list_dv_lienquan_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CAT = ".$idcat;
			$sql="SELECT * FROM common  WHERE ".$condit." ORDER BY DATE_UPDATED DESC LIMIT 3";     
			$query=$DB->query($sql); 										

			while ($rs=$DB->fetch_row($query))
			{								                   				
               $stt++;					
               $fet_box->set( "idhot", $rs['IDHOT']);
               $fet_box->set( "idn", $rs['ID_COMMON']);
               $linkname = $print_2->vn_str_filter($rs['STITLE'.$pre]);
               $fet_box->set( "linkname", $linkname);
               $fet_box->set( "stt", $stt);					
               $fet_box->set( "titlename", $rs['STITLE'.$pre]);
               $fet_box->set("shortcontent",$rs['SCONTENTSHORT'.$pre]);
               $fet_box->set("picture",$rs['PICTURE']);
               $fet_box->set("content",base64_decode($rs['SCONTENTS'.$pre]));
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
             $datasub .= $fet_box->fetch("list_dv_lienquan_rs");

         }

         if($datasub != "")
            $main->set('list_dv_lienquan_rs', $datasub);
        else{
            $fet_boxno = & new Template('noproduct');
            $text = "";
            $fet_boxno->set("text", $text);
            $datasub .= $fet_boxno->fetch("noproduct");
            $main->set('list_dv_lienquan_rs', $datasub);
        }

        return $main->fetch('list_dv_lienquan');
    }
    public function listsp($pre,$id) {
       global $DB, $lang, $itech, $print_2, $common;	

       $fet_box = & new Template('list_sp');
			$data = ""; // de lay du lieu
			$stt =0;
			
			$condit = " STATUS = 'Active' AND ID_CATEGORY = ".$id;
			$sql="SELECT * FROM products  WHERE ".$condit;    
			$query=$DB->query($sql);
			$lklogo = "";
			$fet_sub = & new Template('list_sp_rs');
			$datasub = ""; // de lay du lieu noi cac dong

			while ($rs=$DB->fetch_row($query))
			{								                   																	
				if($rs['PRODUCT_NAME'.$pre]=="")
                    $resultname = $rs['PRODUCT_NAME'];
                else
                    $resultname = $rs['PRODUCT_NAME'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
                $name_link = $this->getlangvalue($rs,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_PRODUCT']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
                $fet_sub->set( "SCONTENTSHORT", $rs['INFO_SHORT']);
                $fet_sub->set("thumbnail",$rs['THUMBNAIL']);
				// get star danh gia comment
                $star = $print_2->getstar($rs['ID_PRODUCT']);
                $fet_sub->set('star', $star);
				//echo $rs['SCONTENTSHORT'];
				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['IMAGE_LARGE'] != ""){						
					$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);

              if(isset($rs['PRICE'])){
                  if($rs['PRICE'] > 0)
                      $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");
                  else
                      $fet_sub->set("PRICE", "Call");

              }

              if(isset($rs['SALE'])){
                  if($rs['SALE'] != "" && $rs['SALE']>0){
                     $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"","."));
                     $fet_sub->set("PRICE_SALE", number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"","."));
                     $fet_sub->set("SALE", $rs['SALE']);
                     $fet_sub->set("PRICE_DISC", number_format($rs['PRICE']*$rs['SALE']/100,0,"","."));
                 }
                 else{
                     $fet_sub->set("PRICE_SALE", "");
                     $fet_sub->set("SALE", "");
                     $fet_sub->set("PRICE_DISC", "");
                 }

             }

             $datasub .= $fet_sub->fetch("list_sp_rs");

         }

         $fet_box->set( "list_sp_rs", $datasub);
			// Get info datasub
         $data = $fet_box->fetch("list_sp");			
         return $data;

     }
     public function listsp_new($pre) {
       global $DB, $lang, $itech, $print_2, $common;	

       $fet_box = & new Template('list_new_sp');
       $stt =0;
       $data ="";
       $condit = " STATUS = 'Active' ";
       $sql="SELECT * FROM products  WHERE ".$condit." ORDER BY DATE_UPDATE DESC LIMIT 0,3"; 		
       $query=$DB->query($sql);
       $lklogo = "";
       $fet_sub = & new Template('list_new_sp_rs');
			$datasub = ""; // de lay du lieu noi cac dong
			while ($rs=$DB->fetch_row($query))
			{								                   																	
				if($rs['PRODUCT_NAME'.$pre]=="")
                    $resultname = $rs['PRODUCT_NAME'];
                else
                    $resultname = $rs['PRODUCT_NAME'.$pre];
                $fet_sub->set( "name", $resultname );
				//$name_link = $print_2->vn_str_filter($rs['PRODUCT_NAME']);
                $name_link = $this->getlangvalue($rs,"PRODUCT_NAME",$pre,$itech->vars['listlang'],$itech->vars['langdefault']);					
                $fet_sub->set('name_link', $name_link);									
                $fet_sub->set( "ID", $rs['ID_PRODUCT']);
                $fet_sub->set( "ID_CATEGORY", $rs['ID_CATEGORY']);
                $fet_sub->set( "SCONTENTSHORT", $rs['INFO_SHORT']);
                $fet_sub->set("thumbnail",$rs['THUMBNAIL']);
				// get star danh gia comment
                $star = $print_2->getstar($rs['ID_PRODUCT']);
                $fet_sub->set('star', $star);
				//echo $rs['SCONTENTSHORT'];
				// get menu selected					 
					 //if($idmnselected != ""){
					 //$name_id = $idmnselected[0];
					 //$vl_id = $idmnselected[1];
					 //$menu_selected = "?".$idmnselected;
                $menu_selected = "?idc=".$rs['ID_CATEGORY'];
					 //echo $menu_selected;
                $fet_sub->set('menu_select', $menu_selected);
					// }
					// else
					// $fet_sub->set('menu_select', "");


                if($rs['IMAGE_LARGE'] != ""){						
					$origfolder = $rs['ID_CATEGORY']; // for every category follows id category
					$width = $itech->vars['width_list3img'];
					$height = $itech->vars['height_list3img'];
					$wh = $print_2->changesizeimage($rs['IMAGE_LARGE'], $origfolder, $width, $height);
                  $fet_sub->set( "w", $wh[0]);
                  $fet_sub->set( "h", $wh[1]);

              }

              $fet_sub->set( "IMAGE_LARGE", $rs['IMAGE_LARGE']);

              if(isset($rs['PRICE'])){
                  if($rs['PRICE'] > 0)
                      $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"",".")." VN&#272;");
                  else
                      $fet_sub->set("PRICE", "Call");

              }

              if(isset($rs['SALE'])){
                  if($rs['SALE'] != "" && $rs['SALE']>0){
                     $fet_sub->set("PRICE", number_format($rs['PRICE'],0,"","."));
                     $fet_sub->set("PRICE_SALE", number_format(($rs['PRICE']-$rs['PRICE']*$rs['SALE']/100),0,"","."));
                     $fet_sub->set("SALE", $rs['SALE']);
                     $fet_sub->set("PRICE_DISC", number_format($rs['PRICE']*$rs['SALE']/100,0,"","."));
                 }
                 else{
                     $fet_sub->set("PRICE_SALE", "");
                     $fet_sub->set("SALE", "");
                     $fet_sub->set("PRICE_DISC", "");
                 }

             }				
             $datasub .= $fet_sub->fetch("list_new_sp_rs");

         }
			//print_r($datasub);
         $fet_box->set( "list_new_sp_rs", $datasub);
			// Get info datasub
         $data .= $fet_box->fetch("list_new_sp");		
         return $data;

     }
	 
	 public function get_data_cat($table,$idtype,$pre,$tieudiem,$tpl,$tpl_rs) {
        global $DB, $lang, $itech, $print_2, $common;   
        $main = & new Template($tpl);
        $field_name_other = array('SNAME','ID_TYPE','IORDER','SCONTENTSHORT','PICTURE');
        $fet_box = & new Template($tpl_rs);
            $datasub = ""; // de lay du lieu noi cac dong
            $stt =0;
            $condit = " STATUS = 'Active'  AND ID_TYPE =".$idtype;
            $sql="SELECT * FROM ".$table."  WHERE ".$condit; 
            $query=$DB->query($sql);
            $lklogo = "";
            while ($rs=$DB->fetch_row($query))
            {    
				$fet_box->set("id_type",$rs['ID_TYPE']);
                $fet_box->set( "idn", $rs['ID_CAT']);   
				if($rs['STITLE'.$pre]=='')
					$name_link = $print_2->vn_str_filter($rs['SNAME']);
				else 
					$name_link = $print_2->vn_str_filter($rs['SNAME'.$pre]);
                $fet_box->set( "name_link", $name_link);
                $fet_box->set( "titlename", $rs['SNAME'.$pre]);
				//$contentshort = substr($rs['SCONTENTSHORT'.$pre],0,200).'...';
                $contentshort = $print_2->cleanchar( $rs['SCONTENTSHORT'.$pre], 305, false );
				$fet_box->set( "SCONTENTSHORT",$contentshort);
                $fet_box->set( "SCONTENTS", base64_decode($rs['SCONTENTS'.$pre ]));
                $fet_box->set( "PICTURE", $rs['PICTURE']);
                $datasub .= $fet_box->fetch($tpl_rs);
            }
            //printf($datasub);
            $main->set( $tpl_rs, $datasub);
            // Get info datasub
            $data = $main->fetch($tpl);
            // return json_encode($DB->fetch_row($query));
            return $data;
        }
		
	function Getmenumain($field_name_other1, $temp1,  $field_name_other2="", $temp2="", $field_name_other3="", $temp3="", $pre, $idc="", $idt="", $idprocat="", $ida="", $idsk="")
{
	global $DB, $lang, $itech, $std, $print_2, $common;
	$sql1="SELECT * FROM menu1 WHERE STATUS = 'Active' ORDER BY IORDER";	
	$query1=$DB->query($sql1);
	$num1=1;
	$stt=0;
	$data1 = "";
	//$temp1 = "menupro_cat_rs";
	$tpl1= new Template($temp1);
	$field_name1 = "NAME_CATEGORY";
	$field_id1 = "ID_CATEGORY";
	while($rs1=$DB->fetch_row($query1))
	{	
		// get cat menu
		$stt++;
		$tpl1->set('stt', $stt);
		
		if ($stt==@$_SESSION['tab']){				
		$selected = 'class="active"';
		$tpl1->set('st_select',$selected);								
		}
		else{				
		$selected = '';
		$tpl1->set('st_select',$selected);
		}

		$sname1=$rs1[$field_name1.$pre];
		$tpl1->set($field_id1, $rs1[$field_id1]);
		$tpl1->set('name', $sname1);
		$name_link1 = $print_2->vn_str_filter($sname1);
		$tpl1->set('name_link', $name_link1);
		$tpl1->set('link1', $rs1['LINK']);
		
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
		$table2 = "menu2";
		if($table2 !=""){
			// get catsub menu
			$sql2="SELECT * FROM ".$table2." WHERE STATUS = 'Active' AND ID_CATEGORY = '".$rs1['ID_CATEGORY']."' ORDER BY IORDER";	
			$query2=$DB->query($sql2);
			$num2=1;
			$data2 = "";
			//$temp2 = "menupro_catsub_rs";			
			$field_name2 = "NAME_TYPE";
			$field_id2 = "ID_TYPE";
			$tpl2= new Template($temp2);	  		
			while($rs2=$DB->fetch_row($query2))
			{	
				// get cat menu
				$sname2=$rs2[$field_name2.$pre];
				$tpl2->set($field_id2, $rs2[$field_id2]);
				$tpl2->set('name', $sname2);
				$name_link2 = $print_2->vn_str_filter($sname2);
				$tpl2->set('name_link', $name_link2);												
				$tpl2->set('ID_CATEGORY', $rs2['ID_CATEGORY']);
				$tpl2->set('link2', $rs2['LINK']);
				
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
				$table3 = "menu3";
				$condit3 = " WHERE STATUS = 'Active' AND ID_TYPE = '".$rs2['ID_TYPE']."' ORDER BY IORDER";
				$field_name3 = "NAME_PRO_CAT";
				$field_id3 = "ID_PRO_CAT";
				$queryothers = "&idprocat=";
				//}
				
				if($table3 !=""){
					// get catsub menu
					$sql3="SELECT * FROM ".$table3.$condit3;	
					$query3=$DB->query($sql3);
					$num3=1;
					$data3 = "";
					//$temp3 = "menupro_procat_rs";								
					$tpl3= new Template($temp3);	  		
					while($rs3=$DB->fetch_row($query3))
					{	
						// query nay de phan biet cac loai idprocat, ida, idsk
						$queryothers1 = "";
						// get procat menu
						$sname3=$rs3[$field_name3.$pre];
						$tpl3->set($field_id3, $rs3[$field_id3]);
						$tpl3->set('name', $sname3);
						$name_link3 = $print_2->vn_str_filter($sname3);
						$tpl3->set('name_link', $name_link3);
						$tpl3->set('link3', $rs3['LINK']);
												
						// set for query others idprocat, ida, idsk
						$queryothers1 .= $queryothers;
						$queryothers1 .= $rs3[$field_id3];
						$tpl3->set('queryothers', $queryothers1);													
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
				
				$tpl2->set('list_procat_menu', $data3);
				$data2 .= $tpl2->fetch($temp2);		
			}
		
		}
		
		$tpl1->set('list_catsub_menu', $data2);

	$data1 .= $tpl1->fetch($temp1);		
	}
	
	return $data1;
	}	

 }
 

 ?>
