<?php
/* Graceric
*  Author: ericfish
*  File: /admin/logout.php
*  Usage: Clear session and Logout
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: logout.php 26 2007-04-07 05:40:34Z ericfish $
*  $LastChangedDate: 2007-04-07 13:40:34 +0800 (星期六, 07 四月 2007) $
*  $LastChangedRevision: 26 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/logout.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');
user_logout();
user_redirect('login.php');
?>