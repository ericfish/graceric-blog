<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/feed.php
*  Usage: Default Feed Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: gc-feed.php 70 2008-07-22 05:25:25Z ericfish $
*  $LastChangedDate: 2008-07-22 13:25:25 +0800 (星期二, 22 七月 2008) $
*  $LastChangedRevision: 70 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-feed.php $
*/

global $db_query;
$feed = $db_query->query_vars['feed'];

// Remove the pad, if present.
$feed = preg_replace('/^_+/', '', $feed);

if ($feed == '' || $feed == 'feed') {
    $feed = 'rss2';
}

switch ($feed) {
    case 'rss2':
        require(ABSPATH . 'gc-rss2.php');
        break;
    case 'comment':
        require(ABSPATH . 'gc-commentsrss2.php');
        break;
    default:
        require(ABSPATH . 'gc-rss2.php');
        break;        
    }
?>