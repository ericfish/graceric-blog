<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/header.php
*  Usage: Default Header Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: header.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/header.php $
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml/DTD/xhtml-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php get_blog_charset();?>" />
  <meta name="author" content="<?php get_blog_author();?>" />
  <meta name="copyright" content="<?php get_blog_author();?>" />
  <meta name="title" content="<?php get_blog_title(); ?>" />
  <meta name="description" content="<?php get_blog_subtitle(); ?>" />
  <meta name="keywords" content="<?php get_blog_keywords(); ?>" />
  <link rel="stylesheet" href="./<?php echo(TPPATH);?>/frame.css" type="text/css" />
  <link rel="stylesheet" href="./<?php echo(TPPATH);?>/color.css" type="text/css" />
  <link id="RSSLink" title="RSS" type="application/rss+xml" rel="alternate" href="<?php get_blog_rsslink();?>"></link>
  <title><?php the_title(); ?></title>
  
<?php 
global $xajax;
if(isset($xajax)) $xajax->printJavascript(WPINC.'/');
?>
<script src="./<?php echo(TPBASEPATH);?>/ajax.js" type="text/javascript"></script>
</head>

<body>
<div id="frame">
	<div id="contentheader">
		<A href="<?php get_blog_base_url();?>" class="grey"><img src="./<?php echo(TPPATH);?>/pic/title.gif" border="0"></A>
	</div>