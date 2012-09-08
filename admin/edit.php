<?php
/* Graceric
*  Author: ericfish
*  File: /admin/edit.php
*  Usage: Edit Post & Create New Post
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: edit.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/admin/edit.php $
*/
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

// edit page init
if(isset($_REQUEST['q'])) {
	$post_id=$_REQUEST['q'];
	global $gcdb;
	$request = "SELECT post_title,post_content,show_in_home,comment_status,ping_status  FROM $gcdb->posts WHERE ID = $post_id";
	$post = $gcdb->get_row($request);
	$post_title = $post->post_title;
	$post_content = $post->post_content;
	$post_tags = getTagsText($post_id);
	if($post->show_in_home == 'yes')
		$is_show = true;
	else
		$is_show = false;
	if($post->comment_status == 'open')
		$allow_comment = true;
	else
		$allow_comment = false;
	if($post->ping_status == 'open')
		$home_hide = true;
	else
		$home_hide = false;
}
// create new page init
else {
	$postArray = &$HTTP_POST_VARS;
	if(isset($postArray['new-page-title']))
		$post_title = $postArray['new-page-title'];
	else
		$post_title = "";
	$post_content = "";
	$post_id = getNextId();
	$post_tags = "";
	$is_show = true;
	$allow_comment = true;
	$home_hide = true;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Edit Post</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php get_blog_charset();?>">
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

function swap(tag){
	tagbox = document.getElementById('post_tags');
	tagbox.value += tag+" ";
	focusTo(tagbox);
}

// focus the caret to end of a form input (+ optionally select some text)
var range=0 //ie
function focusTo(obj, selectFrom) {
	if (typeof selectFrom == 'undefined') selectFrom = obj.value.length
	if(obj.createTextRange){ //ie + opera
		if (range == 0) range = obj.createTextRange()
		range.moveEnd("character",obj.value.length)
		range.moveStart("character",selectFrom)
		//obj.select()
		//range.select()
		setTimeout('range.select()', 10)
	} else if (obj.setSelectionRange){ //ff
		obj.select()
		obj.setSelectionRange(selectFrom,obj.value.length)
	} else { //safari :(
	 obj.blur()
}}
</script>
<!-- end: tinyMCE -->

</HEAD>
<BODY>
<form id="postForm" action="save.php" method="post">
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
            <DIV id=tr_grid-view>
            <TABLE class=tr_sort-bar>
              <THEAD>
              <TR id=tr_new-page-2-list-view>
                  <TD>
                  
                  Title: <INPUT id="post_title" name="post_title" size="40" value="<?php echo($post_title);?>">
                 <input type="hidden" id="post_id" name="post_id" value='<?php echo($post_id);?>'>
                  </TD></TR></THEAD>
              <TBODY></TBODY></TABLE>
            </DIV>
            
            
            <DIV id=tr_list-view>
            
            <TABLE id=tr_list-view>
           
              <THEAD id="tr_list-view-tbody">
<tr><td>
<textarea rows="9" name="EditorAccessibility" id="EditorAccessibility" style="width: 100%; word-break: break-all;"><?php echo($post_content); ?></textarea>
</td></tr>
              </THEAD>
              <TBODY>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width="100%">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <b>Can everyone see it?</b> Public <input type="radio" value="yes" <?php if($is_show)echo('checked')?> name="show_in_home"> Private <input type="radio" name="show_in_home" value="no" <?php if(!$is_show)echo('checked')?>> | 
                      <b>Allow comments?</b> Yes <input type="radio" value="open" <?php if($allow_comment)echo('checked')?> name="allow_comment"> No <input type="radio" name="allow_comment" value="closed" <?php if(!$allow_comment)echo('checked')?>> | 
                      <b>Display on Homepage?</b> Show <input type="radio" value="open" <?php if($home_hide)echo('checked')?> name="home_hide"> Hide <input type="radio" name="home_hide" value="closed" <?php if(!$home_hide)echo('checked')?>>                      
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD> &nbsp;&nbsp;&nbsp;Tags:
                <INPUT id="post_tags" name="post_tags" size="50" value="<?php echo($post_tags);?>"><br/>&nbsp;&nbsp;&nbsp;<?php get_all_tags_edit();?>
                </TD></TR></TBODY>
                
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
                      &nbsp;<INPUT class=tr_submit name="btnSubmit" type=submit value="Publish">
                      <input type="checkbox" name="D1" value="draft">
                      &nbsp;save as draft&nbsp;</TD>
                <TD style="PADDING-RIGHT: 0px" align="right">
                <INPUT class=tr_submit name="btnBack" onclick="javascript:location.href='index.php'" type=button value="Discard"></TD></TR></TBODY></TABLE></DIV></TD>
          <TD class="tr_enclosure tr_br">.</TD></TR></TBODY></TABLE></TD>
    	<TD style="VERTICAL-ALIGN: top" width="1%">
      </TD></TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px"><?php getFooterBar(); ?></span></span></DIV>
</form></BODY></HTML>
