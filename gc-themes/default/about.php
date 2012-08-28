<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/about.php
*  Usage: Default About Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: about.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/about.php $
*/
?>

<?php get_header(); ?>

<?php get_leftbar(); ?>

	<div id="contentcenter">

<div class="date">	
		<img src="./<?php echo(TPPATH);?>/pic/sq.gif" width="7" height="7"> <?php get_blog_about_title(); ?>
	</div>	

<div class="blogbody">
<p>
	<?php get_blog_about_text(); ?><br/><br/>
</p>


<div class="date">	
		<img src="./<?php echo(TPPATH);?>/pic/sq.gif" width="7" height="7"> Site Stat
	</div>	
<p>
	<p>
	First post: <?php count_first_post_date(); ?><br/>
	Last post: <?php count_last_post_date(); ?><br/>
	Total days: <?php count_total_days(); ?><br/>
	Total posts: <?php count_total_posts(); ?><br/>
	Total comments: <?php count_total_comments(); ?><br/>
	Posts/week: <?php count_posts_per_week(); ?><br/>
	Comments/post: <?php count_comments_per_post(); ?>
	</p><br/><br/><br/><br/>
</p>
</div>

	</div>	

<?php get_footer(); ?>