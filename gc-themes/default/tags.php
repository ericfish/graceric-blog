<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/tags.php
*  Usage: Default Tags Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: tags.php 13 2007-03-30 07:52:56Z ericfish $
*  $LastChangedDate: 2007-03-30 15:52:56 +0800 (星期五, 30 三月 2007) $
*  $LastChangedRevision: 13 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/tags.php $
*/
?>

<?php get_header(); ?>

<?php get_leftbar(); ?>

	<div id="contentcenter">

	<div class="time">	 

<?php get_tags(); ?>
	
<script language=javascript>

e = document.getElementsByTagName("A");

for(i=0; i < e.length; i++)
{
	if(e[i].title != "")
	{
		t = e[i].title;
		if(t > 256) e[i].style.fontSize = "150%";
		else if(t > 126) e[i].style.fontSize = "140%";
		else if(t > 68) e[i].style.fontSize = "130%";
		else if(t > 16) e[i].style.fontSize = "120%";
		else if(t > 8) e[i].style.fontSize = "110%";
		else if(t >= 2) e[i].style.fontSize = "100%";
		else if(t = 1) { e[i].style.fontSize = "80%"; }
	}
}
</script>

	</div>	

	</div>	
<?php get_footer(); ?>