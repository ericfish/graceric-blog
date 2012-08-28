<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/search.php
*  Usage: Default Search Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: search.php 20 2007-04-05 09:31:09Z ericfish $
*  $LastChangedDate: 2007-04-05 17:31:09 +0800 (星期四, 05 四月 2007) $
*  $LastChangedRevision: 20 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/search.php $
*/

// Instantiate the xajax object.  No parameters defaults requestURI to this page, method to POST, and debug to off

$xajax = new xajax(); 

//$xajax->debugOn(); // Uncomment this line to turn debugging on

// Specify the PHP functions to wrap. The JavaScript wrappers will be named xajax_functionname
$xajax->registerFunction("processSearchForm");

// Process any requests.  Because our requestURI is the same as our html page,
// this must be called before any headers or HTML output have been sent
$xajax->processRequests();
?>

<?php get_header(); ?>

<?php get_leftbar(); ?>

	<div id="contentcenter">
	<div class="time">
	(space separated keywords)
	<br/>
	<!-- Begin PicoSearch Query Box --> 
  <form id="searchForm" action="javascript:void(null);" onsubmit="submitSearch();">
   <input class="formfield2" id="keyword" name="keyword" size="25" />
   &nbsp;&nbsp;&nbsp;<input style="font-size:9px; font-weight: bold;" type="submit" id="submit" name="submit" value="Search"/><br/><br/>
  </form></div>	
    
	<div id="div1" name="div1" class="archivepage">&#160;</div>
	
	<div class="time">
	This page is powered by <a target='_blank' href='http://www.xajaxproject.org/'>xajax</a>.
	</div>
	<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

	
	
	</div>

<?php get_footer(); ?>