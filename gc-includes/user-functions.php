<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/user-functions.php
*  Usage: User Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: user-functions.php 26 2007-04-07 05:40:34Z ericfish $
*  $LastChangedDate: 2007-04-07 13:40:34 +0800 (æ˜ŸæœŸå…­, 07 å››æœˆ 2007) $
*  $LastChangedRevision: 26 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-includes/user-functions.php $
*/

/***** Get User Data functions *****/

// query user data by user login name from database
// return $user object
function get_userdatabylogin($user_login) {
	global $cache_userdata, $gcdb;
	if ( !empty($user_login) && empty($cache_userdata[$user_login]) ) {
		$user = $gcdb->get_row("SELECT * FROM $gcdb->users WHERE user_login = '$user_login'"); /* todo: get rid of this intermediate var */
		$cache_userdata[$user->ID] = $user;
		$cache_userdata[$user_login] =& $cache_userdata[$user->ID];
	} else {
		$user = $cache_userdata[$user_login];
	}
	return $user;
}

// query user data by user ID from database
// return $user object
function get_userdata($userid) {
	global $gcdb, $cache_userdata;
	$userid = (int) $userid;
	if ( empty($cache_userdata[$userid]) && $userid != 0) {
		$cache_userdata[$userid] = $gcdb->get_row("SELECT * FROM $gcdb->users WHERE ID = $userid");
		$cache_userdata[$cache_userdata[$userid]->user_login] =& $cache_userdata[$userid];
	} 

	return $cache_userdata[$userid];
}

/***** User login and logout *****/

// logout function
function user_logout() {
	user_clearcookie();
}

// login function
if ( !function_exists('user_login') ) :
function user_login($username, $password, $already_md5 = false) {
	global $gcdb, $error;

	if ( !$username )
		return false;

	if ( !$password ) {
		$error = 'ÃÜÂë²»ÄÜÎª¿Õ';
		return false;
	}

	$login = $gcdb->get_row("SELECT ID, user_login, user_pass FROM $gcdb->users WHERE user_login = '$username'");

	if (!$login) {
		$error = 'ÓÃ»§Ãû²»´æÔÚ';
		return false;
	} else {
		// If the password is already_md5, it has been double hashed.
		// Otherwise, it is plain text.
		if ( ($already_md5 && $login->user_login == $username && md5($login->user_pass) == $password) || ($login->user_login == $username && $login->user_pass == md5($password)) ) {
			return true;
		} else {
			$error = 'ÃÜÂë´íÎó.';
			$pwd = '';
			return false;
		}
	}
}
endif;

/***** Check Auth and Redirect Functions *****/

// check username and password
// if correct return ture, else return false
// call by xmlrpc.php
function user_pass_ok($user_login,$user_pass) {
	global $cache_userdata;
	if ( empty($cache_userdata[$user_login]) ) {
		$userdata = get_userdatabylogin($user_login);
	} else {
		$userdata = $cache_userdata[$user_login];
	}
	return (md5($user_pass) == $userdata->user_pass);
}

// check if user has auth successfully
//if not redirect to login page
if ( !function_exists('auth_redirect') ) :
function auth_redirect() {
	// Checks if a user is logged in, if not redirects them to the login page
	if ( (!empty($_COOKIE['gracericuser_' . COOKIEHASH]) && 
				!user_login($_COOKIE['gracericuser_' . COOKIEHASH], $_COOKIE['gracericpass_' . COOKIEHASH], true)) ||
			 (empty($_COOKIE['gracericuser_' . COOKIEHASH])) ) {
		header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
	
		header('Location: ' . get_settings('base_url') . '/admin/login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
		exit();
	}
}
endif;

// check user has auth or not
// if login return true, else return false
if ( !function_exists('user_is_auth') ) :
function user_is_auth() {
	// Checks if a user is logged in
	if ( (!empty($_COOKIE['gracericuser_' . COOKIEHASH]) && 
				!user_login($_COOKIE['gracericuser_' . COOKIEHASH], $_COOKIE['gracericpass_' . COOKIEHASH], true)) ||
			 (empty($_COOKIE['gracericuser_' . COOKIEHASH])) ) {
		return false;
	}
	else
		return true;
}
endif;

// redirect user location to
if ( !function_exists('user_redirect') ) :
function user_redirect($location) {
	header("Location: $location");
}
endif;

/***** Cookie functions *****/
if ( !function_exists('user_setcookie') ) :
function user_setcookie($username, $password, $already_md5 = false, $home = '', $siteurl = '') {
	if ( !$already_md5 )
		$password = md5( md5($password) ); // Double hash the password in the cookie.

	if ( empty($home) )
		$cookiepath = COOKIEPATH;
	else
		$cookiepath = preg_replace('|https?://[^/]+|i', '', $home . '/' );

	if ( empty($siteurl) ) {
		$sitecookiepath = SITECOOKIEPATH;
		$cookiehash = COOKIEHASH;
	} else {
		$sitecookiepath = preg_replace('|https?://[^/]+|i', '', $siteurl . '/' );
		$cookiehash = md5($siteurl);
	}

	setcookie('gracericuser_'. $cookiehash, $username, time() + 31536000, $cookiepath);
	setcookie('gracericpass_'. $cookiehash, $password, time() + 31536000, $cookiepath);

	if ( $cookiepath != $sitecookiepath ) {
		setcookie('gracericuser_'. $cookiehash, $username, time() + 31536000, $sitecookiepath);
		setcookie('gracericpass_'. $cookiehash, $password, time() + 31536000, $sitecookiepath);
	}
}
endif;

if ( !function_exists('user_clearcookie') ) :
function user_clearcookie() {
	setcookie('gracericuser_' . COOKIEHASH, ' ', time() - 31536000, COOKIEPATH);
	setcookie('gracericpass_' . COOKIEHASH, ' ', time() - 31536000, COOKIEPATH);
	setcookie('gracericuser_' . COOKIEHASH, ' ', time() - 31536000, SITECOOKIEPATH);
	setcookie('gracericpass_' . COOKIEHASH, ' ', time() - 31536000, SITECOOKIEPATH);
}
endif;

?>