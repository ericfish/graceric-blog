<?php
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

      <div id="main-content">
        <div class="wrapper">
          <div class="content-item"><div id='g_body'>
          
	<div class="time">
	(space separated keywords)
	<br/>
	<!-- Begin PicoSearch Query Box --> 
  <form id="searchForm" action="javascript:void(null);" onsubmit="submitSearch();">
   <input class="formfield2" id="keyword" name="keyword" id="keyword" size="25" />
   &nbsp;&nbsp;&nbsp;<input style="font-size:9px; font-weight: bold;" type="submit" name="submit" id="submit" value="Search"/><br/><br/>
  </form>
  
    </div>	
    
	<div id="div1" name="div1" class="archivepage">&#160;</div>
	
	<div class="time">
	This page is powered by <a target='_blank' href='http://www.xajaxproject.org/'>xajax</a>.
	</div>
	<br/>

          </div></div>
          <div style="clear: both"></div>
        </div>
      </div>

<?php get_leftbar(); ?>

<?php get_footer(); ?>