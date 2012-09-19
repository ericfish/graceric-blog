<?php
/* Graceric
*  Author: ericfish
*  File: /admin/editabout.php
*  Usage: Edit About Me Content
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: editabout.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/editabout.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Edit About</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?get_blog_charset();?>">
<LINK href="style/style.css" rel=stylesheet>

<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
// Notice: The simple theme does not use all options some of them are limited to the advanced theme
tinyMCE.init({
mode : "textareas",
theme : "advanced",
//elements : "EditorAccessibility",
//save_callback : "customSave",
//handle_event_callback: "ctlent",
// mode : "textareas",
width : "100%",
theme_advanced_buttons1 : "bold, italic, strikethrough, separator, bullist, numlist, outdent, indent, separator, justifyleft, justifycenter, justifyright ,separator, link, unlink, image, wordpress, separator, undo, redo, separator,forecolor,backcolor, separator, code",
theme_advanced_buttons2 : "",
theme_advanced_buttons3 : "",
paste_use_dialog : false,
theme_advanced_resizing : true,
theme_advanced_resize_horizontal : false,
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
dialog_type : "modal",
entity_encoding : "raw",
relative_urls : false,
remove_script_host : false,
force_p_newlines : true,
force_br_newlines : false,
convert_newlines_to_brs : false,
remove_linebreaks : true
});

// Custom save callback, gets called when the contents is to be submitted
function customSave(id, content) {
// alert(id + "=" + content);
}
</script>
<!-- end: tinyMCE -->

</HEAD>
<BODY>
<form id="postForm" action="saveabout.php" method="post">
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
          <TD class="tr_enclosure tr_tr"></TD></TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>           
            
            <DIV id=tr_list-view>
            
            <TABLE id=tr_list-view>
           
              <THEAD id="tr_list-view-tbody">
<tr><td>
<textarea rows="9" name="EditorAccessibility" id="EditorAccessibility" style="width: 100%; word-break: break-all"><?php getAboutOption(); ?></textarea>
</td></tr>
              </THEAD>
                              
                </TABLE></DIV>
            </TD>
          <TD class=tr_enclosure></TD></TR>
        <TR>
          <TD class="tr_enclosure tr_bl">.</TD>
          <TD class=tr_enclosure>
          <DIV id=tr_select-view>
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>
                      &nbsp;<INPUT class=tr_submit name="btnSubmit" type=submit value="Save"></TD>
                <TD style="PADDING-RIGHT: 0px" align="right"></TD></TR></TBODY></TABLE></DIV></TD>
          <TD class="tr_enclosure tr_br">.</TD></TR></TBODY></TABLE></TD>
    	<TD style="VERTICAL-ALIGN: top" width="1%">
      </TD></TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px"><?php getFooterBar(); ?></span></span></DIV>
</form></BODY></HTML>
