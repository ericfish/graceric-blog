<?php get_header(); ?>


      <div id="main-content">
        <div class="wrapper">
          <div class="content-item"><div id='g_body'>
          
<div class="date">	
		<img src="./<?php echo(TPPATH);?>/pic/bullet.gif" width="7" height="7"> <?php get_blog_about_title(); ?>
	</div>	
<BR>
    <!-- Begin .post -->
<div class="blogbody">
<p>
	<?php get_blog_about_text(); ?><br/><br/>
</p>


<div class="date">	
		<img src="./<?php echo(TPPATH);?>/pic/bullet.gif" width="7" height="7"> Site Stat
	</div>	
<p>
	<p><br/>
	First post: <?php count_first_post_date(); ?><br/>
	Last post: <?php count_last_post_date(); ?><br/>
	Total days: <?php count_total_days(); ?><br/>
	Total posts: <?php count_total_posts(); ?><br/>
	Total comments: <?php count_total_comments(); ?><br/>
	Posts/week: <?php count_posts_per_week(); ?><br/>
	Comments/post: <?php count_comments_per_post(); ?>
	</p><br/><br/><br/>
</p>
</div>
          
          </div></div>
          <div style="clear: both"></div>
        </div>
      </div>
	

<?php get_leftbar(); ?>

<?php get_footer(); ?>