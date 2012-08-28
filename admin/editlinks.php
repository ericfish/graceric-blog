<?php
/* Graceric
*  Author: ericfish
*  File: /admin/editlinks.php
*  Usage: Edit Links
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: editlinks.php 26 2007-04-07 05:40:34Z ericfish $
*  $LastChangedDate: 2007-04-07 13:40:34 +0800 (星期六, 07 四月 2007) $
*  $LastChangedRevision: 26 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/editlinks.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

$xajax = new xajax(); 
//$xajax->debugOn(); // Uncomment this line to turn debugging on
$xajax->registerFunction("saveEditLink");
$xajax->registerFunction("saveDeleteLink");
$xajax->registerFunction("saveAddLink");
$xajax->processRequests();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Edit Links</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?get_blog_charset();?>">
<LINK href="style/style.css" rel=stylesheet>

<?php $xajax->printJavascript('../gc-includes/'); ?>
<script language="javascript" type="text/javascript">
</script>

</HEAD>
<BODY>
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
          <TD class="tr_enclosure tr_tr"><div id=lo style="position:absolute;z-index:3;background:#c44;color:white;font-size:75%;top:1;right:16;padding:2;display:none">Saving...</div></TD></TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
          
<DIV id=tr_grid-view><br/>
<table>
<tr class="withover">
    <th class="short">ID</th>
    <th class="short">Link Name</th>
    <th class="short"><span class="info_s">Link URL</span></th>
    <th class="short">&nbsp;</th>
    <th class="short">&nbsp;</th>
</tr>
	<?php initEditLink(); ?>
</table>
</DIV>
<br/>
<DIV id=tr_grid-view class="withover">
&nbsp; &nbsp;<b>Add New Link:</b><br/>
<table>
<tr class="withover">
<td class="short">&nbsp; &nbsp;Link Name:</td>
    <td class="short"><input type="text" name="a_linkname" id="a_linkname" tabindex="1" size="40" >
</tr>
<tr class="withover">
<td class="short">&nbsp; &nbsp;Link URL:</td>
    <td class="short"><input type="text" name="a_linkurl" id="a_linkurl" tabindex="2" size="80" value="http://" ></td>
</tr>
<tr class="withover">
    <td class="short">&nbsp; &nbsp;<input type="submit" value="Add Link" onclick="javascript:xajax_saveAddLink(document.getElementById('a_linkname').value,document.getElementById('a_linkurl').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';" /></td>
</tr>
</table>

</DIV>
</TD>
          <TD class="tr_enclosure tr_br">.</TD></TR></TBODY></TABLE></TD>
    	<TD style="VERTICAL-ALIGN: top" width="1%">
      </TD></TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px"><?php getFooterBar(); ?></span></span></DIV>
</BODY></HTML>
