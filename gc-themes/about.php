<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/about.php
*  Usage: Default About Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: about.php 23 2007-04-05 17:24:37Z ericfish $
*  $LastChangedDate: 2007-04-06 01:24:37 +0800 (Fri, 06 Apr 2007) $
*  $LastChangedRevision: 23 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/gc-themes/default/about.php $
*/
?>

<?php get_header(); ?>

<?php get_leftbar(); ?>

	<div id="contentcenter">
	


<div class="date">	
		<img src="./<?=TPPATH?>/pic/sq.gif" width="7" height="7"> ContactMe
	</div>	

<div class="blogbody">
<p>
	<p> <img src="./<?=TPPATH?>/pic/gmail.png" align="absmiddle"></p><br/>
</p>
</div>


<div class="date">	
		<img src="./<?=TPPATH?>/pic/sq.gif" width="7" height="7"> SiteStat
	</div>
<div class="blogbody">
<p>
	<p>
	First post: <?php count_first_post_date(); ?><br/>
	Last post: <?php count_last_post_date(); ?><br/>
	Total days: <?php count_total_days(); ?><br/>
	Total posts: <?php count_total_posts(); ?><br/>
	Total comments: <?php count_total_comments(); ?><br/>
	Posts/week: <?php count_posts_per_week(); ?><br/>
	Comments/post: <?php count_comments_per_post(); ?>
	</p><br/>
</div>
	
<br/><br/><br/><br/>
</p>
</div>

	</div>	

<?php get_footer(); ?>