<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/feed-functions.php
*  Usage: Feed Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id$
*  $LastChangedDate$
*  $LastChangedRevision$
*  $LastChangedBy$
*  $URL$
*/

function get_bloginfo_rss($show = '') {
	$info = strip_tags(get_option($show));
	return convert_chars($info);
}

function bloginfo_rss($show = '') {
	echo get_bloginfo_rss($show);
}

function the_title_rss() {
	$title = the_post_title(false);
	echo $title;
}

function comments_permalink_rss() {
	$info = comments_permalink();
	echo convert_chars($info);
}

function permalink_single_rss() {
    echo get_permalink();
}

function comment_author_rss() {
    global $post;
	$comment_author = $post->comment_author;
	echo convert_chars($comment_author);
}

function comment_text_rss() {
    global $post;
	$comment_content = $post->comment_content;
	echo $comment_content;
}

function comment_link_rss() {
    global $post;
	$comment_post_ID = $post->comment_post_ID;
	echo convert_chars(comments_permalink($comment_post_ID));
}

function comment_date_rss($d='', $echo = true) {
    global $post;
    $the_date = '';

	if ($d=='') {
	$the_date .= mysql2date('d.m.y', $post->comment_date);	// default date format
	} else {
	$the_date .= mysql2date($d, $post->comment_date);
	}

    if ($echo) {
        echo $the_date;
    } else {
        return $the_date;
    }
}

function the_category_rss($type = 'rss') {
	global $gcdb;
	$cuurent_postID = the_ID(false);
	$cuurent_postID = $gcdb->escape($cuurent_postID);
	
	$request = "SELECT tag_name,tag_nicename FROM $gcdb->tags LEFT JOIN $gcdb->post2tag ON $gcdb->tags.tag_id = $gcdb->post2tag.tag_id WHERE $gcdb->post2tag.post_id = $cuurent_postID";
	
	$categories = $gcdb->get_results($request);
    $the_list = '';
    foreach ($categories as $category) {
        if ('rdf' == $type) {
            $the_list .= "\n\t<dc:subject>$category->tag_name</dc:subject>";
        } else {
            $the_list .= "\n\t<category>$category->tag_name</category>";
        }
    }
    echo $the_list;
}

function convert_chars($content, $flag = 'obsolete') { 
	// Translation of invalid Unicode references range to valid range
	$wp_htmltranswinuni = array(
	'&#128;' => '&#8364;', // the Euro sign
	'&#129;' => '',
	'&#130;' => '&#8218;', // these are Windows CP1252 specific characters
	'&#131;' => '&#402;',  // they would look weird on non-Windows browsers
	'&#132;' => '&#8222;',
	'&#133;' => '&#8230;',
	'&#134;' => '&#8224;',
	'&#135;' => '&#8225;',
	'&#136;' => '&#710;',
	'&#137;' => '&#8240;',
	'&#138;' => '&#352;',
	'&#139;' => '&#8249;',
	'&#140;' => '&#338;',
	'&#141;' => '',
	'&#142;' => '&#382;',
	'&#143;' => '',
	'&#144;' => '',
	'&#145;' => '&#8216;',
	'&#146;' => '&#8217;',
	'&#147;' => '&#8220;',
	'&#148;' => '&#8221;',
	'&#149;' => '&#8226;',
	'&#150;' => '&#8211;',
	'&#151;' => '&#8212;',
	'&#152;' => '&#732;',
	'&#153;' => '&#8482;',
	'&#154;' => '&#353;',
	'&#155;' => '&#8250;',
	'&#156;' => '&#339;',
	'&#157;' => '',
	'&#158;' => '',
	'&#159;' => '&#376;'
	);

	// Remove metadata tags
	$content = preg_replace('/<title>(.+?)<\/title>/','',$content);
	$content = preg_replace('/<category>(.+?)<\/category>/','',$content);

	// Converts lone & characters into &#38; (a.k.a. &amp;)
	$content = preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $content);

	// Fix Word pasting
	$content = strtr($content, $wp_htmltranswinuni);

	// Just a little XHTML help
	$content = str_replace('<br>', '<br />', $content);
	$content = str_replace('<hr>', '<hr />', $content);

	return $content;
}

?>