<?php
/* Graceric
*  Author: ericfish
*  File: /admin/checklogin.php
*  Usage: Check Username and Password
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: checklogin.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/checklogin.php $
*/

require_once('../gc-config.php');
require_once('../gc-settings.php');

global $gcdb,$error;

function gpc2sql($str) {
    if(get_magic_quotes_gpc()==1) 
        return $str;
    else 
        return addslashes($str);
}

$username = gpc2sql($_POST['username']);
$password = gpc2sql($_POST['password']);

if(user_login($username,$password))
{
	// set cookie
	user_setcookie($username, $password);

	// redirect
	if ( isset($_REQUEST['redirect_to']) )
		//$redirect_to = preg_replace('|[^a-z0-9-~+_.?#=&;,/:]|i', '', $_REQUEST['redirect_to']);
		$redirect_to = $_REQUEST['redirect_to'];
	else
        $redirect_to = "index.php";
    user_redirect($redirect_to);
}
else
{
    header("location:login.php?info=$error");
}
?>