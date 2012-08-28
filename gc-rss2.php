<?php
/* Graceric
*  Author: ericfish
*  File: /gc-rss2.php
*  Usage: Lastest posts rss feed
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: gc-rss2.php 72 2008-07-22 07:30:33Z ericfish $
*  $LastChangedDate: 2008-07-22 15:30:33 +0800 (星期二, 22 七月 2008) $
*  $LastChangedRevision: 72 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-rss2.php $
*/

if (empty($feed)) {
	$blog = 1;
	$feed = 'rss2';
	$doing_rss = 1;
	require('gc-header.php');
}

header('Content-type: text/xml; charset=' . get_settings('charset'), true);
$more = 1;

?>
<?php echo '<?xml version="1.0" encoding="'.get_settings('charset').'"?'.'>'; ?>

<!-- generator="graceric blog" -->
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
>

<channel>
	<title><?php bloginfo_rss('blog_title'); ?></title>
	<link><?php bloginfo_rss('base_url') ?></link>
	<description><?php bloginfo_rss("blog_subtitle") ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
	<generator>http://www.ericfish.com/graceric/</generator>
	<language><?php echo get_option('rss_language'); ?></language>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<item>
		<title><?php the_title_rss(); ?></title>
		<link><?php permalink_single_rss(); ?></link>
		<comments><?php comments_permalink_rss(); ?></comments>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', the_date('Y-m-d H:i:s', false), false); ?></pubDate>
		<dc:creator><?php get_blog_author() ?></dc:creator>
		<?php the_category_rss() ?>

		<guid><?php permalink_single_rss(); ?></guid>

		<description><![CDATA[<?php the_content() ?>]]></description>

	</item>
	<?php endwhile; ?>
	<?php endif; ?>
</channel>
</rss>
