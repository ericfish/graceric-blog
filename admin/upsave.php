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
                <TD>&nbsp;<a style="color:white; TEXT-DECORATION: none" href="<?php get_blog_base_url(); ?>/admin/">Back to Admin Home</a></TD></TR></TBODY></TABLE></TD>
</TR>
        <TR>
          <TD style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" colSpan=2>
            <DIV id=tr_grid-view>
<?php 
//�����ϴ����ļ�  
$filefolder="upload";
$filename="$MyFile_name";

if (copy($MyFile,"../$filefolder/$filename")) {

   echo "<h2><font color=#ff0000><a href=\"http://www.ericfish.com/albums/$filefolder/$filename\">$filename</a>�ļ��ϴ��ɹ���</font></h2><br><br>";

}else {

   echo "<h2><font color=#ff0000>�ļ��ϴ�ʧ�ܣ�</font></h2><br><br>";

}
unlink($MyFile);
?>
            </DIV>
            </TD>
</TR></TBODY></TABLE>
<DIV class=tr_footer>
<span class="tr_footer-text"><span style="font-size: 10px">&copy;</span></span><SPAN class=tr_footer-text style="FONT-SIZE: 10px"> 2006 ericfish.com</SPAN></DIV>
 </BODY></HTML>

