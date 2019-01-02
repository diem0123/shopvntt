<?php
//buffer
ob_start();
ob_start( 'ob_gzhandler' );
session_start();
$_SESSION['descript'] = "";// mo ta cho the description

// Root path
//define( 'ROOT_PATH', "./" );
//define('DIR_RS',str_replace('public_html','rs/',$_SERVER['DOCUMENT_ROOT']));
define( 'ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/shopvntt/');//
//error_reporting (E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', false);
date_default_timezone_set('Asia/Ho_Chi_Minh'); 

//set_magic_quotes_runtime(0);
@set_magic_quotes_runtime(false);
ini_set('magic_quotes_runtime', 0);
//echo 'test';
class Debug {
    function startTimer() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
    }
    function endTimer() {
        global $starttime;
        $mtime = microtime ();
        $mtime = explode (' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = round (($endtime - $starttime), 5);
        return $totaltime;
    }
}

class info {

     var $member     = array();
     var $input      = array();
     var $session_id = "";
     var $base_url   = "";
     var $vars       = "";

     function info() {
          global $std, $DB, $DBf, $INFO ;
          $this->vars = &$INFO;
     }
}
//--------------------------------
// Import $INFO, now!
//--------------------------------

$INFO = array();
require ROOT_PATH."conf_global.php";



//--------------------------------
// The clocks a' tickin'
//--------------------------------

$Debug = new Debug;
$Debug->startTimer();

//--------------------------------
// Require our global functions
//--------------------------------

require ROOT_PATH."includes/functions.php";
require ROOT_PATH."includes/functions_1.php";
require ROOT_PATH."includes/templates.php";
require ROOT_PATH."includes/user_1.php";
require ROOT_PATH."includes/functions_2.php";
require ROOT_PATH."includes/common.php";
require ROOT_PATH."includes/user_2.php";
require ROOT_PATH."includes/emailfunctions.php";
require ROOT_PATH."includes/class.phpmailer.php";
require ROOT_PATH."includes/class.smtp.php";
require ROOT_PATH."includes/Mobile_Detect.php";
require ROOT_PATH."includes/rest.client.class.php";
require ROOT_PATH."includes/common.class.php";
require ROOT_PATH."includes/class.uploader.php";
require ROOT_PATH."includes/Classes/PHPExcel.php";
require ROOT_PATH."captcha/simple-php-captcha.php";
//require ROOT_PATH."includes/Classes/excel_reader.php";

//require ROOT_PATH."includes/images_cpr.php";

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$_SESSION['thietbi'] = 'computer';

// function public
$std = new func;
$print = new display;

// function private
//----* for member 1 *----
$std_1 = new func_1;
$print_1 = new display_1;
//----* for member 2 *----
$std_2 = new func_2;
$print_2 = new display_2;
$common = new common_function;
$uploader = new Uploader;
//--------------------------------
// Load the DB driver and such
//--------------------------------

require ROOT_PATH."includes/mySQL.php";


$DB = new db_driver;

$DB->obj['sql_host']         = $INFO['sql_host'];
$DB->obj['sql_user']         = $INFO['sql_user'];
$DB->obj['sql_pass']         = $INFO['sql_pass'];
$DB->obj['sql_database']     = $INFO['sql_database'];

// Get a DB connection

$DB->connect();

//--------------------------------
// Wrap it all up in a nice easy to
// transport super class
//--------------------------------

$itech = new info();

// for private
$user_1 = new useraut_1();
$user_2 = new useraut_2();
//--------------------------------
//  Set up our vars
//--------------------------------

$itech->input = $std->parse_incoming();

//--------------------------------------------
// Require our language, default is english
//--------------------------------------------

require ROOT_PATH."lang/lang.php";

//--------------------------------
// Decide what to do
//--------------------------------

$choice = array(
                   'home'                      => 'home',
				   'gallery'            	   => 'gallery',
				   'checkmailexist'            => 'checkmailexist',
				   'send_comment'              => 'send_comment',
				   'sendtofriend'              => 'sendtofriend',
				   'contact'                   => 'contact',
				   'newsletter_reg'            => 'newsletter_reg',
				   'login'                     => 'login',
				   'info_loggedin'             => 'info_loggedin',
				   'logout'                    => 'logout',
				   'register'                  => 'register',
				   'register_success'          => 'register_success',				   
				   'forgot'                    => 'forgot',
				   'forgot_success'            => 'forgot_success',
				   'mess'            			=> 'mess',
				   'pro_expire'            	   => 'pro_expire',
				   'view_account'              => 'view_account',
				   'edit_account'              => 'edit_account',
				   'changepass'                => 'changepass',
				   'manage_order'              => 'manage_order',
				   'account_view_order'        => 'account_view_order',
				   'delete_order'              => 'delete_order',
				   
				   'getfooter'             	   => 'getfooter',
				   'textfooter'                => 'textfooter',
				   'texthonhop'                => 'texthonhop',
				   'info'                      => 'info',
				   'detail'                    => 'detail',
				   'products'                  => 'products',
				   'prodetail'                 => 'prodetail',
				   'savecartpro'               => 'savecartpro',
				   'getcartpro'                => 'getcartpro',
				   'viewcart'                  => 'viewcart',
				   'removecartpro'             => 'removecartpro',
				   'emptycart'             	   => 'emptycart',
				   'vieworderpro'              => 'vieworderpro',
				   'paysendcontact'            => 'paysendcontact',
				   'changedist'            	   => 'changedist',
				   'procedurepay'              => 'procedurepay',
				   'paytransfer'               => 'paytransfer',
				   'sendorder'                 => 'sendorder',
				   'update_total_fee'          => 'update_total_fee',
				   'completeorder'             => 'completeorder',
				   'do'             		   => 'do',
				   'dr'             		   => 'dr',
				   'show_excel'                => 'show_excel',
				   'list_info'				   => 'list_info',
				   

				   'adm_login'				=> 'adm_login',
				   'adm_logout'				=> 'adm_logout',
				   'adm_home'				=> 'adm_home',
				   'adm_company'			=> 'adm_company',
				   'adm_user'				=> 'adm_user',
				   'adm_chat'				=> 'adm_chat',
				   'adm_update_nick'		=> 'adm_update_nick',
				   'adm_deletenick'			=> 'adm_deletenick',
				   'adm_viewcapacity'		=> 'adm_viewcapacity',
				   
				   'adm_displayads'			=> 'adm_displayads',
				   'adm_products'			=> 'adm_products',
				   'adm_addproduct'			=> 'adm_addproduct',
				   'adm_change_cat'			=> 'adm_change_cat',
				   'adm_change_type'		=> 'adm_change_type',
				   'adm_change_cat_other'	=> 'adm_change_cat_other',
				   
				   'adm_viewproduct'		=> 'adm_viewproduct',
				   'adm_editproduct'		=> 'adm_editproduct',
				   'adm_deleteproduct'		=> 'adm_deleteproduct',
				   
				   'adm_comments'			=> 'adm_comments',
				   
				   'adm_payments'			=> 'adm_payments',
				   
				   'adm_account'			=> 'adm_account',
				   'adm_addaccount'			=> 'adm_addaccount',
				   'adm_editaccount'		=> 'adm_editaccount',
				   
				   'adm_rolelist'			=> 'adm_rolelist',
				   'adm_updaterole'			=> 'adm_updaterole',
				   'adm_saverole'			=> 'adm_saverole',
				   
				   'adm_roles'				=> 'adm_roles',
				   
				   'adm_common'				=> 'adm_common',
				   'adm_commonlist'			=> 'adm_commonlist',
				   'adm_addcommon'			=> 'adm_addcommon',
				   'adm_editcommon'			=> 'adm_editcommon',
				   'adm_viewcommon'			=> 'adm_viewcommon',
				   'adm_change_cat_common'	=> 'adm_change_cat_common',
				   'adm_change_type_common'	=> 'adm_change_type_common',
				   
				   
				   'adm_menu'				=> 'adm_menu',
				   'adm_outgoing_email'		=> 'adm_outgoing_email',				   
				   'adm_transfer'			=> 'adm_transfer',
				   'adm_payonline'			=> 'adm_payonline',
				   'adm_contacts'			=> 'adm_contacts',
				   'adm_email_templates'	=> 'adm_email_templates',
				   'adm_templates_sent'		=> 'adm_templates_sent',
				   'adm_emk_report'			=> 'adm_emk_report',
				   
				   'adm_customer'			=> 'adm_customer',
				   'adm_cus_payment'		=> 'adm_cus_payment',
				   'approve_comments'		=> 'approve_comments',
				   'upload_images'			=> 'upload_images',
				   'getimg_link'			=> 'getimg_link',
				   'adm_delimage'			=> 'adm_delimage',
				   'adm_category'			=> 'adm_category',
				   'updatecategory'			=> 'updatecategory',
				   'subcategorylist'		=> 'subcategorylist',
				   'updatesubcategory'		=> 'updatesubcategory',
				   'subcatelementlist'		=> 'subcatelementlist',
				   'updatesubcatelement'	=> 'updatesubcatelement',
				   'adm_change_cat1'		=> 'adm_change_cat1',
				   'adm_provinces'			=> 'adm_provinces',
				   'update_provinces'		=> 'update_provinces',
				   'adm_dist'				=> 'adm_dist',
				   'update_dist'			=> 'update_dist',
				   'adm_type_transfer'		=> 'adm_type_transfer',
				   'update_type_transfer'	=> 'update_type_transfer',
				   'adm_money_order'		=> 'adm_money_order',
				   'update_money_order'		=> 'update_money_order',
				   'adm_disc_transfer'		=> 'adm_disc_transfer',
				   'update_disc_transfer'	=> 'update_disc_transfer',
				   'changedist2'			=> 'changedist2',
				   'adm_reseller'			=> 'adm_reseller',
				   'update_reseller'		=> 'update_reseller',
				   'adm_cus_payment_report'	=> 'adm_cus_payment_report',
				   'adm_account_view_order'	=> 'adm_account_view_order',
				   'adm_delete_order'		=> 'adm_delete_order',
				   
				   'adm_companyupdate'		=> 'adm_companyupdate',
				   'adm_viewaccount'		=> 'adm_viewaccount',
				   'adm_deleteaccount'		=> 'adm_deleteaccount',
				   'updatecommontype'		=> 'updatecommontype',
				   'adm_deletecommon'		=> 'adm_deletecommon',
				   'adm_deletecontact'		=> 'adm_deletecontact',
				   'adm_banner'				=> 'adm_banner',
				   'updatebanner'			=> 'updatebanner',
				   'adm_hotnews'			=> 'adm_hotnews',
				   'adm_update_hotnews'		=> 'adm_update_hotnews',
				   'adm_delete_hotnews'		=> 'adm_delete_hotnews',
				   'adm_propay'				=> 'adm_propay',
				   'updatepropay'			=> 'updatepropay',
				   'update_mailsmtp'		=> 'update_mailsmtp',
				   'adm_photolist'			=> 'adm_photolist',
				   'adm_addphoto'			=> 'adm_addphoto',
				   'adm_editphoto'			=> 'adm_editphoto',
				   'adm_viewphoto'			=> 'adm_viewphoto',
				   'adm_deletephoto'		=> 'adm_deletephoto',
				   'adm_edittext'			=> 'adm_edittext',
				   'adm_importexcel'        => 'adm_importexcel',
				   'adm_import_excel_cap'   => 'adm_import_excel_cap',
				   'adm_exportexcel'		=> 'adm_exportexcel',
				   'adm_brand'				=> 'adm_brand',
				   'updatebrand'			=> 'updatebrand',
				   'adm_deletebrand'		=> 'adm_deletebrand',
				   'adm_exportexcellist'    => 'adm_exportexcellist',
				   'adm_urlexportexcel'     => 'adm_urlexportexcel',
				   'adm_capacity'			=> 'adm_capacity',
				   'adm_addcapacity'		=> 'adm_addcapacity',
					'adm_editcapacity'		=> 'adm_editcapacity',
					'adm_deletecapacity'	=> 'adm_deletecapacity',

				   'adm_material'			=> 'adm_material',
				   'updatematerial'			=> 'updatematerial',
				   'adm_deletematerial'		=> 'adm_deletematerial',
				   
				   'updatemt'				=> 'updatemt',
				   
				   'adm_change_type1'		=> 'adm_change_type1',
				   'adm_commontype'			=> 'adm_commontype',
				   'updatecommoncat'		=> 'updatecommoncat',
				   'adm_commoncat'			=> 'adm_commoncat',
				   'updatecommoncatsub'		=> 'updatecommoncatsub',
				   
				   'sendrequest'			=> 'sendrequest',
				   'getsub_banner'			=> 'getsub_banner',
				   'getinfo_text'			=> 'getinfo_text',
				   'get_count'				=> 'get_count',
				   'sendm'					=> 'sendm',
				   'mypro'					=> 'mypro',
				   'savepro_link'			=> 'savepro_link',
				   'delete_prolike'			=> 'delete_prolike',
				   'adm_library'			=> 'adm_library',				   
				   'getimagelibrary'		=> 'getimagelibrary',
				   'delimage'				=> 'delimage',
				   'adm_listfile'			=> 'adm_listfile',
				   'getthuvien'				=> 'getthuvien',
				   'upload'					=> 'upload',
				   'remove_file'			=> 'remove_file',
				   'apply'					=> 'apply',
				   'adm_menu'				=> 'adm_menu',
				   'menu_sub'				=> 'menu_sub',
				   'menu_subcat'			=> 'menu_subcat',
				   'updatemenu'				=> 'updatemenu',
				   'updatesubmenu'			=> 'updatesubmenu',
				   'updatesubcatmenu'		=> 'updatesubcatmenu',
				   'getnamelink'			=> 'getnamelink',
				   'adm_change_menu1'		=> 'adm_change_menu1',
				   'adm_delmenu'			=> 'adm_delmenu',
				   'setmenu'				=> 'setmenu',
				   'savecartpro_pro'		=> 'savecartpro_pro',
				   
				   'queryOrder'				=> 'queryOrder',
				   'checkoutResult'			=> 'checkoutResult',
				   'createOrder'			=> 'createOrder',
				   'notify'					=> 'notify',
				   
				   
				   'error403'				=> 'error403',
				   'error404'				=> 'error404',
				   'error500'				=> 'error500'

               );

/***************************************************/

$vs = $itech->input['vs'];
if($vs == 1) { $_SESSION['dv_Type'] = 'computer'; $_SESSION['chonpb'] = 1;}
elseif($vs == 2) { $_SESSION['dv_Type'] = 'phone'; $_SESSION['chonpb'] = 2;}
elseif(!isset($_SESSION['chonpb'])) {
	$_SESSION['dv_Type'] = 'computer';
}
$_SESSION['dv_Type'] = 'computer';
$_SESSION['dv_Type_giohang'] = $deviceType;
//$_SESSION['dv_Type'] = 'phone';

if (! isset($choice[ $itech->input['act'] ]) )
{
     $itech->input['act'] = 'home';
}
// Require and run

require ROOT_PATH."sources/".$choice[ $itech->input['act'] ].".php";

?>
