<?php
if(user_is_auth())
{
    $xajax = new xajax();
    //$xajax->debugOn(); // Uncomment this line to turn debugging on
    $xajax->registerFunction("saveSpamComment");
    $xajax->processRequests();
}
?>
<?php get_header(); ?>

      <div id="main-content">
        <div class="wrapper">
          <div class="content-item"><div id='g_body'>

	<!-- // loop start -->
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
		<div class="date">	
			<img src="./<?php echo(TPPATH);?>/pic/bullet.gif" border="0"> <?php the_date("l, F d, Y"); ?>
		</div>

		<div class="blogbody">
		<P><?php get_lock_icon();?> <font color="#999999"><b><?php the_post_title(); ?></b></font>
		
		<span class='archivepage'><font color="#999999">
		(tags:<?php get_post_tags();?>)
		</font></span></P>
		
			<P>
				<?php the_content(); ?>
			</P>
			
			<div id="comments">
				<br/><p>
					<?php the_comment(); ?>
				</p>
				<BR>
			</div>
		</div>

	<?php endwhile; else: ?>
		<?php header("location:404.php"); ?>
	<?php endif; ?>
				
				
	</div></div>
          <div style="clear: both"></div>
        </div>
      </div>
      
<?php get_leftbar(); ?>

<?php get_footer(); ?>