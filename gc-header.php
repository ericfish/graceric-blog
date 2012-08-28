<?php
/* Graceric
*  Author: ericfish
*  File: /gc-header.php
*  Usage: template configuration
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: gc-header.php 71 2008-07-22 07:17:36Z ericfish $
*  $LastChangedDate: 2008-07-22 15:17:36 +0800 (星期二, 22 七月 2008) $
*  $LastChangedRevision: 71 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-header.php $
*/

if ( !file_exists( dirname(__FILE__) . '/gc-config.php') ) {
	if ( strstr( $_SERVER['PHP_SELF'], 'admin') ) $path = '';
	else $path = 'admin/';

  require_once( dirname(__FILE__) . '/gc-includes/functions.php');
  gc_die("There doesn't seem to be a <code>gc-config.php</code> file. I need this before we can get started. Need more help? <a href='http://www.ericfish.com/graceric'>We got it</a>. You can <a href='{$path}setup-config.php'>create a <code>gc-config.php</code> file through a web interface</a>, but this doesn't work for all server setups. The safest way is to manually create the file.", "Graceric &rsaquo; Error");
}

require_once( dirname(__FILE__) . '/gc-config.php');

require_once(ABSPATH.'gc-settings.php');

// Template redirection
if ( defined('WP_USE_THEMES') && constant('WP_USE_THEMES') ) {
	if (is_archive()&&is_month()) {
		include(TEMPLATEPATH . '/index.php');
	}
	elseif (is_archive()&&!is_month()) {
		include(TEMPLATEPATH . '/archive.php');
	}
	elseif (is_tags()) {
		include(TEMPLATEPATH . '/tags.php');
	}
	elseif (is_about()) {
		include(TEMPLATEPATH . '/about.php');
	}
	elseif (is_tag()) {
		include(TEMPLATEPATH . '/tag.php');
	}
	elseif (is_search()) {
		include(TEMPLATEPATH . '/search.php');
	}
	elseif (is_links()) {
		include(TEMPLATEPATH . '/links.php');
	}
	elseif (is_feed()) {
		include(ABSPATH. 'gc-feed.php');
	}
	elseif( file_exists(TEMPLATEPATH . "/index.php") ) {
		include(TEMPLATEPATH . '/index.php');
	}
}

?>
