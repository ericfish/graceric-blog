<?php
/* Graceric
*  Author: ericfish
*  File: /admin/index.php
*  Usage: List All Post
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: index.php 26 2007-04-07 05:40:34Z ericfish $
*  $LastChangedDate: 2007-04-07 13:40:34 +0800 (星期六, 07 四月 2007) $
*  $LastChangedRevision: 26 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/index.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

// get the base url from db
define('BASEURL', get_option('base_url'));

// define how many posts are listed in the page
global $end_id;
$end_id = 10;

// Instantiate the xajax object.  No parameters defaults requestURI to this page, method to POST, and debug to off

$xajax = new xajax();

//$xajax->debugOn(); // Uncomment this line to turn debugging on

// Specify the PHP functions to wrap. The JavaScript wrappers will be named xajax_functionname
$xajax->registerFunction("processSearch");
$xajax->registerFunction("refreshPage");
$xajax->registerFunction("nextPage");
$xajax->registerFunction("prevPage");
$xajax->registerFunction("updateDraft");
$xajax->registerFunction("updatePublish");
$xajax->registerFunction("updateDelete");
$xajax->registerFunction("resetSelect");

// Process any requests.  Because our requestURI is the same as our html page,
// this must be called before any headers or HTML output have been sent
$xajax->processRequests();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Post Manager</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?get_blog_charset();?>">
<LINK href="style/style.css" rel=stylesheet>
<?php $xajax->printJavascript('../gc-includes/'); ?>
  
  <script type="text/javascript">
function checkAll(itemName)
{
  var aa = document.getElementsByName(itemName);
  for (var i=0; i<aa.length; i++)
   aa[i].checked = true;
}
function checkNone(itemName)
{
  var aa = document.getElementsByName(itemName);
  for (var i=0; i<aa.length; i++)
   aa[i].checked = false;
}
function updateCheck(itemName)
{
    document.getElementById('lo').style.display='block';
	var aa = document.getElementsByName(itemName);
	for (var i=0; i<aa.length; i++)
	{
		if(aa[i].checked)
		{
			if(getSelectedValue() == 'draft')
				xajax_updateDraft(aa[i].value);
			if(getSelectedValue() == 'publish')
				xajax_updatePublish(aa[i].value);
			if(getSelectedValue() == 'delete')
			{
				if(confirm("Delete it?"))
					xajax_updateDelete(aa[i].value);
			}
		}
	}
	xajax_refreshPage(document.getElementById('begin_post_id').value);
	xajax_resetSelect();
}

function getSelectedValue()
{
	var bb = document.getElementsByName('tr_bulk-action-dropdown');
	
	return bb[0].value;
}
  </script>
</HEAD>
<BODY>
<form action="edit.php" method="post">
<TABLE width="100%">
  <TBODY>
  <TR>
    <TD style="VERTICAL-ALIGN: top">
      <TABLE cellSpacing=0 cellPadding=0 width="100%">
        <TBODY>
        <TR>
          <TD class="tr_enclosure tr_tl"></TD>
          <TD class=tr_enclosure>
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD><?php getNavBar(); ?></TD></TR></TBODY></TABLE></TD>
          <TD class="tr_enclosure tr_tr"><div id=lo style="position:absolute;z-index:3;background:#c44;color:white;font-size:75%;top:1;right:16;padding:2;display:none">Loading...</div></TD></TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
          
            <DIV id=tr_grid-view>
            <TABLE class=tr_sort-bar>
              <THEAD>
              <TR id=tr_new-page-2-list-view>
                      <TD><IMG alt="" src="style/create-small.gif">
                      <INPUT type="text"  name="new-page-title" value=""> <INPUT class=tr_submit id=new-page-button type=submit value="Create and Edit"></TD></TR></THEAD><TBODY></TBODY></TABLE></DIV>
            <DIV id="tr_list-view" name="tr_list-view">
            
            <TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width="100%">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value="">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick="javascript:xajax_processSearch(document.getElementById('tr_search_keyword').value);javascript:document.getElementById('lo').style.display='block';" type=button value="Search">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>
              
<?php
initPage();
?>
              </TBODY></TABLE></DIV>
            </TD>
          <TD class=tr_enclosure></TD></TR>
        <TR>
          <TD class="tr_enclosure tr_bl">.</TD>
          <TD class=tr_enclosure>
          <DIV id="tr_select-view" name="tr_select-view">
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>&nbsp;
                <SELECT id=tr_bulk-action-dropdown name="tr_bulk-action-dropdown" onchange=updateCheck('C1');>
                <OPTION value="" selected>More Actions ...</OPTION>
                <OPTION value=publish>&nbsp; &nbsp; Publish</OPTION>
                <OPTION value=draft>&nbsp; &nbsp; Draft</OPTION>
                <OPTION value=delete>&nbsp; &nbsp; Delete</OPTION>
                </SELECT>
                <SPAN class=tr_selectors>Select: 
                  <SPAN class=tr_pseudo-link onclick="checkAll('C1');">All</SPAN>,&nbsp;
                  <SPAN class=tr_pseudo-link onclick="checkNone('C1');">None</SPAN></SPAN>
                </TD>
                <TD style="PADDING-RIGHT: 0px" align="right">
                <SPAN class=tr_pseudo-link onclick="javascript:xajax_prevPage(document.getElementById('begin_post_id').value);javascript:document.getElementById('lo').style.display='block';">&#8249; Newer</SPAN>
                 | 
                 <SPAN class=tr_pseudo-link onclick="javascript:xajax_nextPage(document.getElementById('begin_post_id').value);javascript:document.getElementById('lo').style.display='block';";>Older &#8250;</SPAN>
                </TD></TR></TBODY></TABLE></DIV></TD>
          <TD class="tr_enclosure tr_br">.</TD></TR></TBODY></TABLE></TD>
    	<TD style="VERTICAL-ALIGN: top" width="1%">
      </TD></TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px"><?php getFooterBar(); ?></span></span></DIV>
</form></BODY></HTML>
