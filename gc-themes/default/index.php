<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/index.php
*  Usage: Default Index Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: index.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2008-02-12 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/index.php $
*/
?>
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

<?php get_leftbar(); ?>

	<div id="contentcenter">

	<!-- // loop start -->
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
		<div class="date">	
			<img src="./<?php echo(TPPATH);?>/pic/sq.gif" width="7" height="7"> <?php the_date("l, F d, Y"); ?>
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
				<p>
					<?php the_comment(); ?>
				</p>
				<BR>
			</div>
		</div>

	<?php endwhile; else: ?>
		<?php //echo("<div class=\"blogbody\"><P>����ʵ�ҳ�治���ڣ�<br/>��ȥ<a href=\"./?search\">����</a>ҳ�������ص����ݡ�</P></div><br/><br/><br/><br/><br/><br/><br/>");
        header("location:404.php"); ?>
	<?php endif; ?>

				<span class="lastpost">
					<?php recent_post_links(); ?>
				</span>
				<BR><BR><BR><BR><BR><BR>
	</div>

<?php get_footer(); ?>