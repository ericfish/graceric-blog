<?php
/* Graceric
*  Author: ericfish
*  File: /gc-setting.php
*  Usage: include libararies
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: gc-settings.php 73 2008-07-22 07:31:57Z ericfish $
*  $LastChangedDate: 2008-07-22 15:31:57 +0800 (星期二, 22 七月 2008) $
*  $LastChangedRevision: 73 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-settings.php $
*/

require (ABSPATH . WPINC . '/functions.php');
require (ABSPATH . WPINC . '/xajax.class.php');
require (ABSPATH . WPINC . '/inputfilter.class.php');


// Used to guarantee unique hash cookies
$cookiehash = md5(get_settings('siteurl')); // Remove in 1.4
define('COOKIEHASH', $cookiehash);


require (ABSPATH . WPINC . '/user-functions.php');
require (ABSPATH . WPINC . '/comment-functions.php');
require (ABSPATH . WPINC . '/dbquery.class.php');

require (ABSPATH . WPINC . '/feed-functions.php');
require (ABSPATH . WPINC . '/xmlrpc-functions.php');

require (ABSPATH . WPINC . '/admin-functions.php');

// Template values
define('TPBASEPATH', 'gc-themes');
define('TEMPLATEPATH', ABSPATH . '/gc-themes/'.get_option('template'));
define('TPPATH', 'gc-themes/'.get_option('template'));

// Path for cookies
define('COOKIEPATH', preg_replace('|https?://[^/]+|i', '', '/' ) );
define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_settings('base_url') . '/' ) );


?>
