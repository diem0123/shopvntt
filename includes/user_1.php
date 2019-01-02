<?php

class useraut_1
{

    var $session_uid = "iMemberID";
    var $session_type = "type";
    var $session_ip = "ip";
    var $cookie_username = "username";
    var $cookie_email = "email";
    var $cookie_password = "password";
    var $cookie_fullname = "fullname";
    var $cookie_ukind = "ukind";
    var $post_username = "username";
    var $post_email = "email";
    var $post_password = "password";
    var $DB_table_name = "member";
    var $DB_table_emp_name = "employer";
    var $DB_table_contact_emp_name = "employer_contact";
    var $DB_field_uid = "iMemberID";
    var $DB_field_password = "sPassword";
    var $DB_field_email = "sEmail";
    var $DB_field_type = "iType";
    var $DB_field_fullname = "sFullname";
    var $member_area = "index.php";
    var $error_form = "We either had trouble understanding those fields, or need additional information.";
    var $error_user = "Username or password does not match.";
    var $iMemberID;
    var $password;
    var $username;
    var $email;
    var $type;
    var $fullname;
    var $ukind = 0;

    function useraut_1( )
    {
        global $DB, $common;		
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		$daystart = mktime(0,0,0,$month,$day,$year);
		$monthstart = mktime(0,0,0,$month,1,$year);
        if ( !isset( $_SESSION['online'] ) )
        {
            // update for counter
			$DB->query( "UPDATE counter SET NUM = NUM+1" );
			$ol_info = $common->getInfo("counter","NUM > 0");
			if($ol_info['ACTIVITY'] < date('Y-m-d 0:0:0')){
				$DB->query( "UPDATE counter SET ACTIVITY = now(), INTODAY =1 " );
			}
			else {
				$DB->query( "UPDATE counter SET INTODAY = INTODAY+1 " );
			}
			// insert for online in a firs time
            $DB->query( "INSERT INTO online (SESSION_ID, ACTIVITY, IP_ADDRESS, REFURL, USER_AGENT) VALUES ('".session_id( )."', now(), '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_REFERER']."', '".$_SERVER['HTTP_USER_AGENT']."')" );
            $_SESSION['online'] = 1;
        }
	/*	
        else if ( $this->loggedincm( ) )
        {
            $DB->query( "UPDATE online SET activity=now(), member='y' WHERE session_id='".session_id( )."'" );
        }
	*/	
        // cap nhat thoi gian lai cho user de tranh bi xoa khi vuot thoi gian qui dinh
		if ( isset( $_SESSION['online'] ) )
        {
            // kiem tra trong truong hop da bi xoa thi can insert vao lai
			$uinfo = $common->getInfo("online","SESSION_ID='".session_id( )."'");
			if($uinfo==""){
				$DB->query( "INSERT INTO online (SESSION_ID, ACTIVITY, IP_ADDRESS, REFURL, USER_AGENT) VALUES ('".session_id( )."', now(), '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_REFERER']."', '".$_SERVER['HTTP_USER_AGENT']."')" );
				// Neu chua bi xoa thi update thoi gian lai cho user. Chinh viec update lai thoi gian lien tuc nay khi user truy cap se tao cam giac nhu user dang online				
			}
			else{
				// Phan thong ke online se lay tat ca cac user con du thoi gian qui dinh chua bi xoa
				$DB->query( "UPDATE online SET ACTIVITY=now() WHERE SESSION_ID='".session_id( )."'" );
			}
        }
		
        $maxtime = time( ) - 300;
        $DB->query( "DELETE FROM online WHERE UNIX_TIMESTAMP(ACTIVITY) < '".$maxtime."'" );
    }

    function verifysession( )
    {
        if ( @$_SESSION[$this->session_ip] != $_SERVER['REMOTE_ADDR'] )
        {
            return false;
        }
        return true;
    }

    function verifycookie( )
    {
        global $std;
        if ( isset($_COOKIE[$this->cookie_ukind], $_COOKIE[$this->cookie_username], $_COOKIE, $_COOKIE ) )
        {
            $this->username = $std->my_getcookie( $this->cookie_username );
            $this->password = $std->my_getcookie( $this->cookie_password );
            $this->ukind = $std->my_getcookie( $this->cookie_ukind );
            return true;
        }
        return false;
    }

    function verifydb( )
    {
        global $DB;
        if ( $this->ukind )
        {
            $sql = "SELECT ".$this->DB_field_uid.", ".$this->DB_field_email.", ".$this->DB_field_type.", ".$this->DB_field_fullname.", hasinfo, dLastlogin FROM ".$this->DB_table_emp_name." WHERE sUsername = '".trim( $this->username )."' AND ".$this->DB_field_password." = '".trim( $this->password )."';";
        }
        else
        {
            $sql = "SELECT ".$this->DB_field_uid.", ".$this->DB_field_email.", ".$this->DB_field_type.", ".$this->DB_field_fullname.", hasinfo, dLastlogin FROM ".$this->DB_table_name." WHERE sUsername = '".trim( $this->username )."' AND ".$this->DB_field_password." = '".trim( $this->password )."';";
        }
        $query = $DB->query( $sql );
        $row = $DB->fetch_row( );
        $num = $DB->get_num_rows( );
        if ( $num == 1 )
        {
            $this->iMemberID = $row[$this->DB_field_uid];
            $this->email = $row[$this->DB_field_email];
            $this->type = $row[$this->DB_field_type];
            $this->fullname = $row[$this->DB_field_fullname];
            $_SESSION['hasinfo'] = $row['hasinfo'];
            $_SESSION['lastlogin'] = $row['dLastlogin'];
            return true;
        }
        return false;
    }

    function writesession( )
    {
        $_SESSION[$this->session_uid] = $this->iMemberID;
        $_SESSION[$this->session_type] = $this->type;
        $_SESSION[$this->session_ip] = $_SERVER['REMOTE_ADDR'];
    }

    function writecookie( )
    {
        global $std;
        $std->my_setcookie( $this->cookie_username, $this->username );
        $std->my_setcookie( $this->cookie_email, $this->email );
        $std->my_setcookie( $this->cookie_password, $this->password );
        $std->my_setcookie( $this->cookie_fullname, $this->fullname );
        $std->my_setcookie( $this->cookie_ukind, $this->ukind );
    }

    function verifyform( )
    {
        global $itech;
        if ( $itech->input[$this->post_password] != "" )
        {
            $this->username = trim( $itech->input[$this->post_username] );
            $this->password = md5( trim( $itech->input[$this->post_password] ) );
            return true;
        }
        return false;
    }

    function changepass( $oldPass, $newPass )
    {
        global $DB;
        global $std;
        if ( $std->my_getcookie( $this->cookie_password ) == md5( $oldPass ) )
        {
            $sql = $this->ukind ? "UPDATE ".$this->DB_table_emp_name." SET sPassword ='".md5( $newPass )."' WHERE ".$this->DB_field_uid." = '".$_SESSION[$this->session_uid]."' LIMIT 1;" : ( $sql = "UPDATE ".$this->DB_table_name." SET sPassword ='".md5( $newPass )."' WHERE ".$this->DB_field_uid." = '".$_SESSION[$this->session_uid]."' LIMIT 1;" );
            $DB->query( $sql );
            return true;
        }
        return false;
    }

    function resetpass( $who, $username, $email )
    {
        global $DB;
        global $itech;
        global $print_1;
        global $lang;
        if ( $who == "emp" )
        {
            $this->ukind = 1;
        }
        if ( $this->checkuser( $email, $username ) )
        {
            $print_1->redirect( "index.php?act=cm_forgetpass&who=".$who, $lang['messemail'], "forgetpass page" );
        }
        if ( $username )
        {
            $newPass = $this->randompass( );
            $table = $this->ukind ? $this->DB_table_emp_name : $this->DB_table_name;
            $sql = "UPDATE ".$table." SET sPassword='".md5( $newPass )."' WHERE sUsername = '".$username."' LIMIT 1;";
            $DB->query( $sql );
            $sql = "SELECT ".$this->DB_field_email.", sFullname FROM ".$table." WHERE sUsername='".$username."';";
            $query = $DB->query( $sql );
            while ( $result = $DB->fetch_row( $query ) )
            {
                $mailto = $result['sEmail'];
                $fullname = $result['sFullname'];
            }
        }
        if ( $email )
        {
            $mailto = $email;
            $newPass = $this->randompass( );
            $table = $this->ukind ? $this->DB_table_emp_name : $this->DB_table_name;
            $sql = "UPDATE ".$table." SET sPassword='".md5( $newPass )."' WHERE ".$this->DB_field_email." = '".$email."' LIMIT 1;";
            $DB->query( $sql );
            $sql = "SELECT sUsername, sFullname FROM ".$table." WHERE ".$this->DB_field_email." = '".$email."';";
            $query = $DB->query( $sql );
            while ( $result = $DB->fetch_row( $query ) )
            {
                $username = $result['sUsername'];
                $fullname = $result['sFullname'];
            }
        }
        $message = "<html>\r\n          <head>\r\n\t\t\t<title>HIJET-PARTS.COM.VN - CHU MINH Co.,Ltd</title>\r\n\t\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\r\n\t\t\t</head>\r\n          <body>\r\n            <br>\r\n                  <p>".$lang['dear'].$fullname.", </p>\r\n                  <p>".$lang['introresetpass']."</p>\r\n                  <p>".$lang['textloginemail'].$email." </p>\r\n                  <p>".$lang['textpassword'].$newPass." </p>\r\n                  <p>".$lang['textthanks']."</p>\r\n                  <p>".$lang['textregards']."</p>\r\n\t\t\t<p> ".$lang['textteam']."\r\n\t\t\t  Website: <a href=http://www.hijet-parts.com.vn >www.hijet-parts.com.vn</a>/ Email: <a href=\"mailto:".$itech->vars['email_webmaster']."\">".$itech->vars['email_webmaster']."</a></p>              </td>\r\n\t\t\t</body>\r\n\t\t\t</html>";
        $subject = "Your Login Information - Hijet-parts.com.vn";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "To: ".$username." <".$mailto.">\r\n";
        $headers .= "From: hijet-parts.com.vn <".$itech->vars['email_webmaster'].">\r\n";
        $ok = @mail( $mailto, $subject, $message, $headers );
        if ( $ok )
        {
            $print_1->redirect( "index.php?act=cm_forgetpass&who=".$who, $lang['passsent'], "main page" );
        }
        $print_1->redirect( "index.php?act=cm_forgetpass&who=".$who, $lang['passnotsent'], "main page" );
    }

    function randompass( )
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand( ( double ) microtime() * 1000000 );
        $i = 0;
        $pass = "";
        while ( $i <= 7 )
        {
            $num = rand( ) % 33;
            $tmp = substr( $chars, $num, 1 );
            $pass .= $tmp;
            ++$i;
        }
        return $pass;
    }

    function changeemail( $oldpass, $newemail, $ukind )
    {
        global $DB;
        global $std;
        $this->ukind = $ukind;
        if ( $std->my_getcookie( $this->cookie_password ) == md5( $oldpass ) )
        {
            $sql = $this->ukind ? "UPDATE ".$this->DB_table_emp_name." SET sUsername='".$newemail."', sEmail='".$newemail."' WHERE ".$this->DB_field_uid." = '".$_SESSION[$this->session_uid]."' LIMIT 1;" : ( $sql = "UPDATE ".$this->DB_table_name." SET sUsername='".$newemail."', sEmail='".$newemail."' WHERE ".$this->DB_field_uid." = '".$_SESSION[$this->session_uid]."' LIMIT 1;" );
            $DB->query( $sql );
            return true;
        }
        return false;
    }

    function adduser( $who, $username, $email, $password, $fullname, $gender, $address, $province, $country, $phone, $cellphone, $fax )
    {
        global $DB;
        global $print_1;
        global $lang;
        if ( $this->checkuser( $email, $username ) )
        {
            $print_1->redirect( "index.php?act=cm_register&who=".$who, $lang['Register4'], "register page" );
        }
        else
        {
            $DB->query( "INSERT INTO ".$this->DB_table_name."(sUsername, sEmail, sPassword, sFullname, iGender, sAddress, iProvince, iCountry, sTel, sMobile, sFax, dJoindate) VALUES('".$username."','".$email."', '".md5( $password )."', '".$fullname."', '".$gender."','".$address."','".$province."','".$country."','".$phone."',\r\n                  '".$cellphone."', '".$fax."', CURDATE());" );
        }
    }

    function updateuser( $fullname, $gender, $address, $province, $country, $phone, $cellphone, $fax )
    {
        global $DB;
        global $print_1;
        global $lang;
        $member = $_SESSION[$this->session_uid];
        $DB->query( "UPDATE ".$this->DB_table_name." SET\r\n                                          sFullname ='".$fullname."',\r\n                                          iGender   ='".$gender."',\r\n                                          sAddress  ='".$address."',\r\n                                          iProvince ='".$province."',\r\n                                          iCountry  ='".$country."',\r\n                                          sTel      ='".$phone."',\r\n                                          sMobile   ='".$cellphone."',\r\n                                          sFax      ='".$fax."'\r\n                                          WHERE ".$this->DB_field_uid." = '".$member."' LIMIT 1;" );
    }

    function checkuser( $email, $username )
    {
        global $DB;
        if ( $this->ukind )
        {
            $sql = "SELECT ".$this->DB_field_email." FROM ".$this->DB_table_emp_name." WHERE ".$this->DB_field_email." = '".$email."' OR sUsername='".$username."';";
        }
        else
        {
            $sql = "SELECT ".$this->DB_field_email." FROM ".$this->DB_table_name." WHERE sUsername='".$username."';";
        }
        $DB->query( $sql );
        if ( 0 < $DB->get_num_rows( ) )
        {
            return false;
        }
        return true;
    }

    function logincm( $ukind )
    {
        global $DB;
        global $print;
        global $lang;
        $v_session = $this->verifysession( );
        if ( $v_session )
        {
            $print->redirect( $this->member_area, $lang['hlogin'], "main page" );
        }
        $v_cookie = $this->verifycookie( );
        if ( $v_cookie )
        {
            $this->ukind = $ukind;
            $v_db = $this->verifydb( );
            if ( $v_db )
            {
                $this->writesession( );
                $print->redirect( $this->member_area, $lang['hlogin'], "main page" );
            }
        }
        $v_form = $this->verifyform( );
        do
        {
            if ( $v_form )
            {
                break;
            }
            else
            {
                $print->redirect( "?act=cm_register", $lang['hnotenought'], "main page" );
            }
            if ( $v_form )
            {
            }
        } while ( 0 );
        $this->ukind = $ukind;
        $v_db = $this->verifydb( );
        if ( $v_db )
        {
            $print->redirect( "?act=cm_register", $lang['huserorpass'], "main page" );
        }
        else
        {
            $this->writesession( );
            $this->writecookie( );
            $tb_name = $ukind ? $this->DB_table_emp_name : $this->DB_table_name;
            $DB->query( "UPDATE ".$tb_name." SET dLastlogin=CURDATE() WHERE iMemberID=".$this->iMemberID." LIMIT 1;" );
            if ( $ukind )
            {
                $this->member_area = $_SESSION['hasinfo'] ? "index.php?act=emphomeloged" : "index.php?act=emphomeloged";
            }
            else
            {
                $this->member_area = $_SESSION['hasinfo'] ? "index.php?act=home" : "index.php?act=home";
            }
            if ( $_SESSION[urlvip] != "" )
            {
                $print->redirect( $_SESSION[urlvip], $lang['hlogin1']." <strong>".$this->fullname."</strong>".$lang['hlogin2'], "Continuous".( $_SESSION[urlvip] = "" ) );
            }
            $print->redirect( $this->member_area, $lang['login1']." <strong>".$this->fullname."</strong>".$lang['login2'], "main page" );
        }
    }

    function logoutcm( )
    {
        global $DB;
        global $print;
        global $std;
        global $lang;
        $tb_name = $std->my_getcookie( $this->cookie_ukind ) ? $this->DB_table_emp_name : $this->DB_table_name;
        if ( $_SESSION['iMemberID'] )
        {
            $DB->query( "UPDATE ".$tb_name." SET dLastlogin=CURDATE() WHERE iMemberID=".$_SESSION['iMemberID']." LIMIT 1;" );
            $DB->query( "DELETE FROM online WHERE session_id='".session_id( )."'" );
            $emp = $std->my_getcookie( $this->cookie_ukind );
        }
        $_SESSION = array( );
        session_destroy( );
        $std->my_setcookie( $this->cookie_username, "" );
        $std->my_setcookie( $this->cookie_email, "" );
        $std->my_setcookie( $this->cookie_password, "" );
        $std->my_setcookie( $this->cookie_fullname, "" );
        $std->my_setcookie( $this->cookie_ukind, "" );
        if ( $emp )
        {
            $print->redirect( $this->member_area_emp, $lang['logout'], "main page" );
        }
        else
        {
            $print->redirect( $this->member_area, $lang['logout'], "main page" );
        }
    }

    function loggedincm( )
    {
        global $std;
        $v_session = $this->verifysession( );
        if ( $v_session )
        {
            $v_cookie = $this->verifycookie( );
            if ( $v_cookie )
            {
                $v_db = $this->verifydb( );
                if ( $v_db )
                {
                    $this->writesession( );
                    return true;
                }
            }
            return false;
        }
        $this->ukind = $std->my_getcookie( $this->cookie_ukind );
        return true;
    }

    function getusercm( $iMemberID = 0 )
    {
        global $DB;
        if ( $iMemberID )
        {
            $DB->query( "SELECT * FROM ".$this->DB_table_name." WHERE ".$this->DB_field_uid."='".$iMemberID."';" );
        }
        else
        {
            $DB->query( "SELECT * FROM ".$this->DB_table_name." WHERE ".$this->DB_field_uid."='".$_SESSION[$this->session_uid]."';" );
        }
        return $DB->fetch_row( );
    }

    function checkemail( $email )
    {
        global $DB;
        $sql = "SELECT * FROM nhanbaogia WHERE sEmail='".$email."';";
        $DB->query( $sql );
        if ( 0 < $DB->get_num_rows( ) )
        {
            return false;
        }
        return true;
    }

    function addemail( $email )
    {
        global $DB;
        global $print_1;
        global $lang;
        if ( $this->checkemail( $email ) )
        {
            $print_1->redirect( "index.php?act=home", $lang['emailbaogia'], "register email" );
        }
        else
        {
            $DB->query( "INSERT INTO nhanbaogia (sEmail) VALUES('".$email."');" );
        }
    }

}

?>
