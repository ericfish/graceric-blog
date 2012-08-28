<?php
/* Graceric
*  Author: ericfish
*  File: /admin/editx.php
*  Usage: Edit Shorcut Links
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: editx.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/editx.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

$xajax = new xajax(); 
//$xajax->debugOn(); // Uncomment this line to turn debugging on
$xajax->registerFunction("saveEditX");
$xajax->registerFunction("saveDeleteX");
$xajax->registerFunction("saveAddX");
$xajax->processRequests();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Edit Shortcut Links (X)</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php get_blog_charset();?>">
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
    <th class="short">Post ID</th>
    <th class="short"><span class="info_s">Shortcut Name</span></th>
    <th class="short">&nbsp;</th>
    <th class="short">&nbsp;</th>
</tr>
	<?php initEditX(); ?>
</table>
</DIV>
<br/>
<DIV id=tr_grid-view class="withover">
&nbsp; &nbsp;<b>Add New X:</b><br/>
<table>
<tr class="withover">
<td class="short">&nbsp; &nbsp;Post ID:</td>
    <td class="short"><input type="text" name="a_x_postid" id="a_x_postid" tabindex="1" size="15" >
</tr>
<tr class="withover">
<td class="short">&nbsp; &nbsp;Shortcut Name:</td>
    <td class="short"><input type="text" name="a_x_name" id="a_x_name" tabindex="2" size="50" value="" ></td>
</tr>
<tr class="withover">
    <td class="short">&nbsp; &nbsp;<input type="submit" value="Add X..." onclick="javascript:xajax_saveAddX(document.getElementById('a_x_postid').value,document.getElementById('a_x_name').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';" /></td>
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
