<?php 
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Upload File</TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php get_blog_charset();?>">
<LINK href="style/style.css" rel=stylesheet>
</HEAD>
<BODY>
<TABLE width="100%">
  <TBODY>
  <TR>
    <TD style="VERTICAL-ALIGN: top">
      <TABLE cellSpacing=0 cellPadding=0 width="100%">
        <TBODY>
        <TR>
          <TD class=tr_enclosure>
            <TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>&nbsp;<a style="color:white; TEXT-DECORATION: none" href="<?php get_blog_base_url(); ?>"><?php get_blog_title();?></a></TD></TR></TBODY></TABLE></TD>
</TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
            <DIV id=tr_grid-view>
            <form ENCTYPE="multipart/form-data" action="upsave.php" method="post">
<table border='0' >

<tr>
<td><br>
<INPUT NAME="MyFile" TYPE="File"  size="50">
</td>
</tr>
<tr>
<td colspan='2' align="center">
<input type="Submit" value=" �ϴ� " class=iwhite> 
<input type="reset" value=" ���� " class=iwhite>
</td>
</tr>
</table>
<form>
            </DIV>
            </TD>
</TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px">&copy;</span></span><SPAN class=tr_footer-text style="FONT-SIZE: 10px"> 2006 ericfish.com</SPAN></DIV>
 </BODY></HTML>

