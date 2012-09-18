<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/functions.php
*  Usage: Main Functions (Page defination related)
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: functions.php 51 2007-04-29 06:18:46Z ericfish $
*  $LastChangedDate: 2007-04-29 14:18:46 +0800 (Sun, 29 Apr 2007) $
*  $LastChangedRevision: 51 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/gc-includes/functions.php $
*/

/*
* Page define functions
* Posts functions
* Template functions
* DateTime Format function
* Options functions
* Tags functions
* Archive functions
* Links functions
* About page functions
* Search functions
* Counter functions
* Feed functions
* Security functions
* Install
* URL Rewrite
*/

/***** Page define functions (check which page it is) *****/

function is_home () {
    global $db_query;
    return $db_query->is_home;
}

function is_page () {
    global $db_query;
    return $db_query->is_page;
}

function is_archive () {
    global $db_query;
    return $db_query->is_archive;
}

function is_tags () {
    global $db_query;
    return $db_query->is_tags;
}

function is_search () {
    global $db_query;
    return $db_query->is_search;
}

function is_about () {
    global $db_query;
    return $db_query->is_about;
}

function is_links () {
    global $db_query;
    return $db_query->is_links;
}

function is_tag () {
    global $db_query;
    return $db_query->is_tag;
}

function is_month () {
    global $db_query;
    return $db_query->is_month;
}

function is_feed () {
    global $db_query;
    return $db_query->is_feed;
}

function is_x () {
    global $db_query;
    return $db_query->is_x;
}

/***** Posts functions (They will all be called by template pages) *****/

function have_posts() {
    global $db_query;

    return $db_query->have_posts();
}

function the_post() {
    global $db_query;
    $db_query->the_post();
}

function the_content() {
    global $post;
	$the_content = $post->post_content;
    echo $the_content;
}

function the_ID($echo = true) {
    global $post;
	$the_ID = $post->ID;
	if ($echo)
    	echo $the_ID;
	else
		return $the_ID;
}

function the_post_date($echo = true) {
    global $post;
	$the_post_date = $post->post_date;
	if ($echo)
    	echo $the_post_date;
	else
		return $the_post_date;
}


function the_post_title($echo = true) {
    global $post;
	$the_post_title = $post->post_title;
	if ($echo)
    	echo $the_post_title;
	else
		return $the_post_title;
}

function the_date($d='', $echo = true) {
    global $post;
    $the_date = '';

	if ($d=='') {
	$the_date .= mysql2date('d.m.y', $post->post_date);	// default date format
	} else {
	$the_date .= mysql2date($d, $post->post_date);
	}

    if ($echo) {
        echo $the_date;
    } else {
        return $the_date;
    }
}

function the_comment() {
	
	global $db_query,$error_message,$comm_name,$comm_e_mail,$comm_website;
	
	if (!$db_query->is_comment) {
		
		// $post_time = the_date("g:i A",false);
		$comment_link = show_comment_link();
		echo("<a name='comments'></a><span class='blogkommlink'>$comment_link</span>");
	}
	else {
		// if postback from comment
		if (isset($_REQUEST['button'])) {
			//$comm_name,$comm_e_mail,$comm_website,$comm_content,$notify_comment);
			$db_query->save_comment();
		}
		// Get comment template
		get_comment();
		
		echo $error_message;
	}
}

function the_title($echo=true) {
    global $gcdb,$db_query;
    if(is_page()) {	
		$post_id = $gcdb->escape($db_query->query_vars['q']);
		$request = "SELECT post_title FROM $gcdb->posts WHERE ID=$post_id";
		
		$post_title = $gcdb->get_var($request);
		if(isset($post_title))
		  $blog_title = get_option('blog_title').": ".$post_title;
		else
		  $blog_title = "";
    }
    elseif(is_x()) {	
		$x = $gcdb->escape($db_query->query_vars['x']);
		$request2 = "SELECT post_ID FROM $gcdb->x WHERE x_name='$x'";
		$q = $gcdb->get_var($request2);
		$q = (int)$gcdb->escape($q);
		$request = "SELECT post_title FROM $gcdb->posts WHERE ID=$q";
		
		$post_title = $gcdb->get_var($request);
		if(isset($post_title))
		  $blog_title = get_option('blog_title').": ".$post_title;
		else
		  $blog_title = "";
    }
    elseif(is_archive()) {	
    	$month = "";
    	if(isset($db_query->query_vars['month']))
			$month .= $db_query->query_vars['month'];
		
		$blog_title = get_option('blog_title').": Archive ".$month;
    }
    elseif(is_search()) {	
		
		$blog_title = get_option('blog_title').": Search";
    }
    elseif(is_about()) {	
		
		$blog_title = get_option('blog_title').": About";
    }
    elseif(is_tags()) {	
		
		$blog_title = get_option('blog_title').": Tags";
    }
    elseif(is_links()) {	
		
		$blog_title = get_option('blog_title').": Links";
    }
    elseif(is_tag()) {	
		$the_tag = "";
		$the_tag .= $db_query->query_vars['tag'];
		
		$blog_title = get_option('blog_title')." -> tag -> ".$the_tag;
    }
    else {
    	$blog_title = get_option('blog_title');
    }
    
	if ($echo)
    	echo $blog_title;
	else
		return $blog_title;

}

/***** Get Template functions *****/

function get_blog_title() {
	echo(get_option('blog_title'));
}

function get_blog_subtitle() {
	echo(get_option('blog_subtitle'));
}

function get_blog_base_url() {
    echo(get_option('base_url'));
}

function get_blog_about_text() {
    echo(get_option('about_text'));
}

function get_blog_about_title() {
    echo(get_option('about_title'));
}

function get_blog_author() {
    echo(get_option('blog_author'));
}

function get_blog_keywords() {
    echo(get_option('keywords'));
}

function get_blog_footer_text() {
    echo(get_option('footer_text'));
}

// Get the rss link from option
function get_blog_sidebar()
{    
    if(is_page()||is_home()) 
        echo("JOURNAL\n");
	else
	   echo("<a href=\"".baseurl_permalink()."\">journal</a>\n");
    
    if(is_archive()) 
        echo("<BR><BR>ARCHIVE\n");
	else
	   echo("<BR><BR><a href=\"".archive_permalink()."\">archive</a>\n");
    
    if(is_search()) 
        echo("<BR><BR>SEARCH\n");
	else
	   echo("<BR><BR><a href=\"".search_permalink()."\">search</a>\n");
    
    if(is_about()) 
        echo("<BR><BR>ABOUT\n");
	else
	   echo("<BR><BR><a href=\"".about_permalink()."\">about</a>\n");
    
    if(is_links()) 
        echo("<BR><BR>LINKS\n");
	else
	   echo("<BR><BR><a href=\"".links_permalink()."\">links</a>\n");
    
    if(is_tags()) 
        echo("<BR><BR>TAGS\n");
	else
	   echo("<BR><BR><a href=\"".tags_permalink()."\">tags</a>\n");
}

function get_all_tags_edit(){
    global $gcdb;
    $request = "SELECT tag_id,tag_name FROM $gcdb->tags";
	
	$tags = $gcdb->get_results($request);
	$numbers = count($tags);
	
	for($i=0;$i<$numbers;$i++)
	{
		$tag = $tags[$i];
		$tag_id = $tag->tag_id;
		$tag_name = $tag->tag_name;
		
		echo " <A href='javascript:swap(\"$tag_name\")'>$tag_name</A>";
	}
}

// Get the rss link from option
function get_blog_rsslink()
{
    $rss_link = get_option('rss_link');
    if($rss_link==""){
        $rss_link = feed_permalink();
    }
    echo $rss_link;
}

// if the post is private, display the lock icon
function get_lock_icon(){
    global $post;
    $show_in_home = $post->show_in_home;
    if($show_in_home=='no')
    {
        echo("<img align=\"absmiddle\" src=\"".TPBASEPATH."/lock.gif\" alt=\"[Private] Only you can see this post.\"/>");
    }
}

function get_lastpostmodifiedcomment() {
    global $gcdb;
    $lastpostmodified = $gcdb->get_var("SELECT comment_date FROM $gcdb->comments WHERE comment_approved='1' ORDER BY comment_date DESC LIMIT 1");
	return $lastpostmodified;
}

function get_lastpostmodified($timezone = 'server') {
	global $cache_lastpostmodified, $pagenow, $gcdb;
	$add_seconds_blog = get_settings('gmt_offset') * 3600;
	$add_seconds_server = date('Z');
	$now = current_time('mysql', 1);
	if ( !isset($cache_lastpostmodified[$timezone]) ) {
		switch(strtolower($timezone)) {
			case 'gmt':
				$lastpostmodified = $gcdb->get_var("SELECT post_date_gmt FROM $gcdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
				break;
			case 'blog':
				$lastpostmodified = $gcdb->get_var("SELECT post_date FROM $gcdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
				break;
			case 'server':
				$lastpostmodified = $gcdb->get_var("SELECT DATE_ADD(post_date_gmt, INTERVAL '$add_seconds_server' SECOND) FROM $gcdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
				break;
		}
		$lastpostdate = $gcdb->get_var("SELECT post_date FROM $gcdb->posts WHERE post_status = 'publish' ORDER BY post_date DESC LIMIT 1");
		if ($lastpostdate > $lastpostmodified) {
			$lastpostmodified = $lastpostdate;
		}
		$cache_lastpostmodified[$timezone] = $lastpostmodified;
	} else {
		$lastpostmodified = $cache_lastpostmodified[$timezone];
	}
	return $lastpostmodified;
}

function get_header() {
	if ( file_exists( TEMPLATEPATH . '/header.php') )
		require_once( TEMPLATEPATH . '/header.php');
	else
		require_once( ABSPATH . 'gc-themes/default/header.php');
}

function get_leftbar() {
	if ( file_exists( TEMPLATEPATH . '/sidebar.php') )
		require_once( TEMPLATEPATH . '/sidebar.php');
	else
		require_once( ABSPATH . 'gc-themes/default/sidebar.php');
}

function get_comment() {
	if ( file_exists( TEMPLATEPATH . '/comment.php') )
		require_once( TEMPLATEPATH . '/comment.php');
	else
		require_once( ABSPATH . 'gc-themes/default/comment.php');
}

function get_footer() {
	if ( file_exists( TEMPLATEPATH . '/footer.php') )
		require_once( TEMPLATEPATH . '/footer.php');
	else
		require_once( ABSPATH . 'gc-themes/default/footer.php');
}

// Get prv post link and nxt post link
function recent_post_links(){
	
	global $gcdb,$db_query;
	$cuurent_post_date = the_post_date(false);
	
	if(!is_x()){
    	if(isset($db_query->query_vars['tagid'])) {
    		$tag_id = $gcdb->escape($db_query->query_vars['tagid']);
    		
    		$request_prv = "SELECT a.ID, a.post_title FROM $gcdb->posts a,$gcdb->post2tag b";
    		$request_prv .= " WHERE a.post_date < '".$cuurent_post_date;
    		$request_prv .= "' AND post_status = 'publish'";
    		$request_prv .= " AND ping_status = 'open'";
    		$request_prv .= " AND b.tag_id = ".$tag_id;
    		$request_prv .= " AND a.ID = b.post_id";
    		if(!user_is_auth())
    		{
    		     $request_prv .= " AND show_in_home = 'yes'";
    		}
    		$request_prv .= " ORDER BY a.post_date DESC,a.ID DESC";
    		$request_prv .= " LIMIT ".get_option('prev_links');
    		$prv_posts = $gcdb->get_results($request_prv);
    		
    		$prv_posts_num = count($prv_posts);
    		
    		if($prv_posts_num==0)
    		{
    			return "";
    		}
    		
    		else{
    			if(is_home()||is_page()) {
    			    for ($i=0;$i<$prv_posts_num;$i++) { 
    			        $prv_post = $prv_posts[$i];	    
    				    echo("&laquo;&laquo;&laquo; <a href='".get_permalink($prv_post->ID,$tag_id)."' title='click to view previous blog'>$prv_post->post_title</a><br/><br/>");
    			    }
    			}
    		}
    	}
    	
    	else {
    		$request_prv = "SELECT ID, post_title FROM $gcdb->posts";
    		$request_prv .= " WHERE post_date < '".$cuurent_post_date;
    		$request_prv .= "' AND post_status = 'publish'";
    		$request_prv .= " AND ping_status = 'open'";
    		if(!user_is_auth())
    		{
    		     $request_prv .= " AND show_in_home = 'yes'";
    		}
    		$request_prv .= " ORDER BY post_date DESC";
    		$request_prv .= " LIMIT ".get_option('prev_links');
    	
    		$prv_posts = $gcdb->get_results($request_prv);
    		
    		$prv_posts_num = count($prv_posts);
    		
    		if($prv_posts_num==0)
    		{
    			return "";
    		}
    		
    		else{
    			if(is_home()||is_page()) {
    			    for ($i=0;$i<$prv_posts_num;$i++) { 
    			        $prv_post = $prv_posts[$i];
    				    echo("&laquo;&laquo;&laquo; <a href='".get_permalink($prv_post->ID)."' title='click to view previous blog'>$prv_post->post_title</a><br/><br/>");
    			    }
    			}
    		}
    	}
    }
}

// Get the rss link from option
function get_blog_charset()
{
    $charset = get_option('charset');
    if($charset==""){
        $charset = "gb2312";
    }
    echo $charset;
}

/***** DateTime Format functions *****/

function mysql2date($dateformatstring, $mysqlstring, $translate = true) {
	global $month, $weekday, $month_abbrev, $weekday_abbrev;
	$m = $mysqlstring;
	if (empty($m)) {
		return false;
	}
	$i = mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4)); 
	if (!empty($month) && !empty($weekday) && $translate) {
		$datemonth = $month[date('m', $i)];
		$datemonth_abbrev = $month_abbrev[$datemonth];
		$dateweekday = $weekday[date('w', $i)];
		$dateweekday_abbrev = $weekday_abbrev[$dateweekday]; 		
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit($dateweekday_abbrev), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit($datemonth_abbrev), $dateformatstring);
	
		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @date($dateformatstring, $i);
	if (!$j) {
	// for debug purposes
	//	echo $i." ".$mysqlstring;
	}
	return $j;
}

function current_time($type, $gmt = 0) {
	switch ($type) {
		case 'mysql':
			if ( $gmt ) $d = gmdate('Y-m-d H:i:s');
			else $d = gmdate('Y-m-d H:i:s', (time() + (get_option('gmt_offset') * 3600)));
			return $d;
			break;
		case 'timestamp':
			if ( $gmt ) $d = time();
			else $d = time() + (get_option('gmt_offset') * 3600);
			return $d;
			break;
	}
}

// give it a date, it will give you the same date as GMT
function get_gmt_from_date($string) {
  // note: this only substracts $time_difference from the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_gmt = gmdate('Y-m-d H:i:s', $string_time - get_option('gmt_offset') * 3600);
  return $string_gmt;
}

// give it a GMT date, it will give you the same date with $time_difference added
function get_date_from_gmt($string) {
  // note: this only adds $time_difference to the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_localtime = gmdate('Y-m-d H:i:s', $string_time + get_option('gmt_offset')*3600);
  return $string_localtime;
}


// computes an offset in seconds from an iso8601 timezone
function iso8601_timezone_to_offset($timezone) {
  // $timezone is either 'Z' or '[+|-]hhmm'
  if ($timezone == 'Z') {
    $offset = 0;
  } else {
    $sign    = (substr($timezone, 0, 1) == '+') ? 1 : -1;
    $hours   = intval(substr($timezone, 1, 2));
    $minutes = intval(substr($timezone, 3, 4)) / 60;
    $offset  = $sign * 3600 * ($hours + $minutes);
  }
  return $offset;
}

// converts an iso8601 date to MySQL DateTime format used by post_date[_gmt]
function iso8601_to_datetime($date_string, $timezone = USER) {
  if ($timezone == GMT) {
    preg_match('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', $date_string, $date_bits);
    if (!empty($date_bits[7])) { // we have a timezone, so let's compute an offset
      $offset = iso8601_timezone_to_offset($date_bits[7]);
    } else { // we don't have a timezone, so we assume user local timezone (not server's!)
      $offset = 3600 * get_settings('gmt_offset');
    }
    $timestamp = gmmktime($date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1]);
    $timestamp -= $offset;
    return gmdate('Y-m-d H:i:s', $timestamp);
  } elseif ($timezone == USER) {
    return preg_replace('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', '$1-$2-$3 $4:$5:$6', $date_string);
  }
}

/* Options functions */

function get_settings($setting) {
	global $gcdb;

	$option = $gcdb->get_var("SELECT option_value FROM $gcdb->options WHERE option_name = '$setting'");

	if (!$option) :
		return false;
	endif;
/*
	@ $kellogs = unserialize($option);
	if ($kellogs !== FALSE)
		return $kellogs;
	else return $option;*/
    return $option;

}

function get_option($option) {
	return get_settings($option);
}

function update_option($option_name, $newvalue) {
	global $gcdb;
	
	if ( is_array($newvalue) || is_object($newvalue) )
		$newvalue = serialize($newvalue);

	$newvalue = trim($newvalue); // I can't think of any situation we wouldn't want to trim

    // If the new and old values are the same, no need to update.
    if ($newvalue == get_option($option_name)) {
        return true;
    }

	// If it's not there add it
	if ( !$gcdb->get_var("SELECT option_name FROM $gcdb->options WHERE option_name = '$option_name'") )
		add_option($option_name);

	$newvalue = $gcdb->escape($newvalue);
	$gcdb->query("UPDATE $gcdb->options SET option_value = '$newvalue' WHERE option_name = '$option_name'");
	return true;
}

function add_option($name, $value = '', $description = '', $autoload = 'yes') {
	global $gcdb;
	$original = $value;
	if ( is_array($value) || is_object($value) )
		$value = serialize($value);

	if( !$gcdb->get_var("SELECT option_name FROM $gcdb->options WHERE option_name = '$name'") ) {
		$name = $gcdb->escape($name);
		$value = $gcdb->escape($value);
		$description = $gcdb->escape($description);
		$gcdb->query("INSERT INTO $gcdb->options (option_name, option_value, option_description, autoload) VALUES ('$name', '$value', '$description', '$autoload')");
		
	}
	return;
}

function delete_option($name) {
	global $gcdb;
	// Get the ID, if no ID then return
	$option_id = $gcdb->get_var("SELECT option_id FROM $gcdb->options WHERE option_name = '$name'");
	if (!$option_id) return false;
	$gcdb->query("DELETE FROM $gcdb->options WHERE option_name = '$name'");
	return true;
}

/***** Tags functions *****/
function get_post_tags(){
	
	global $gcdb;
	$cuurent_postID = the_ID(false);
	$cuurent_postID = $gcdb->escape($cuurent_postID);
	
	$request = "SELECT tag_name,tag_nicename FROM $gcdb->tags LEFT JOIN $gcdb->post2tag ON $gcdb->tags.tag_id = $gcdb->post2tag.tag_id WHERE $gcdb->post2tag.post_id = $cuurent_postID";
	
	$tags = $gcdb->get_results($request);
	$numbers = count($tags);
	
	for($i=0;$i<$numbers;$i++)
	{
		$tag = $tags[$i];
		$tag_name = $tag->tag_name;
		$tag_nicename = $tag->tag_nicename;
		echo " <A class='grey' HREF='".tag_permalink($tag_name)."' title='$tag_nicename'>$tag_name</A>";
	}
}

// tp get tag links
function get_tags(){
	global $gcdb;
	$request = "SELECT a.tag_name,a.tag_nicename, count(b.post_id) AS title FROM $gcdb->tags a, $gcdb->post2tag b,$gcdb->posts c WHERE a.tag_id=b.tag_id AND b.post_id=c.ID AND c.post_status='publish' GROUP BY b.tag_id ORDER BY title DESC";
	
	$tags = $gcdb->get_results($request);
	$numbers = count($tags);
	
	for($i=0;$i<$numbers;$i++)
	{
		$tag = $tags[$i];
		$tag_name = $tag->tag_name;
		$tag_nicename = $tag->tag_nicename;
		$title = $tag->title;
		
		//echo " <a href='?tag=$tag_name' title='$title'>$tag_name</a> <a href='./?feed=$tag_name' class='grey' target='_blank'><img src='./gc-themes/o_rss.gif' border='0' height='16' width='16' align='absbottom'/></a> <br><br> ";
		echo " <a href='".tag_permalink($tag_name)."' title='$title'>$tag_name</a><br><br> ";
	}
}

// tp get links for 1 tag
function get_tag(){
	global $gcdb,$db_query;
	
	$tag_name = $gcdb->escape($db_query->query_vars['tag']);
	$request = "SELECT a.ID,a.post_date,a.post_title,b.tag_ID FROM $gcdb->posts a, $gcdb->tags b, $gcdb->post2tag c WHERE b.tag_id=c.tag_id AND b.tag_name = '$tag_name' AND c.post_id=a.ID AND a.post_status='publish'";
	$request .= " ORDER BY a.post_date DESC";
	
	$posts = $gcdb->get_results($request);
	$numbers = count($posts);
	
	for($i=0;$i<$numbers;$i++)
	{
		$post = $posts[$i];
		$post_ID = $post->ID;
		$post_date = $post->post_date;
		$post_date = mysql2date("l, F d, Y", $post_date);
		$post_title = $post->post_title;
		$post_tagid = $post->tag_ID;
		$result = "<div class='date'><img src='./".TPPATH."/pic/sq.gif' width='7' height='7'> $post_date</div><div class='archivepage'><a href='".get_permalink($post_ID,$post_tagid)."' title='permanent link'>$post_title</a></div>\n";
		echo $result;
	}
}

/***** Archive functions *****/
function get_archives(){
	
	global $gcdb;
	$request = "SELECT DISTINCT DATE_FORMAT(post_date, '%M %Y') AS date_dis,DATE_FORMAT(post_date, '%Y%m') AS date_url FROM $gcdb->posts WHERE post_status='publish' AND show_in_home='yes' ORDER BY date_url DESC";
	
	$months = $gcdb->get_results($request);
	$numbers = count($months);
	
	for($i=0;$i<$numbers;$i++)
	{
		$month = $months[$i];
		$date_dis = $month->date_dis;
		$date_url = $month->date_url;
		echo " <a href='".archivemonth_permalink($date_url)."'>$date_dis</a><br><br> ";
	}
}

/***** Links functions *****/
function get_links(){
	global $gcdb;
	$request = "SELECT link_name,link_url FROM $gcdb->links ORDER BY link_rating";
	
	$links = $gcdb->get_results($request);
	$numbers = count($links);
	
	for($i=0;$i<$numbers;$i++)
	{
		$link = $links[$i];
		$link_name = $link->link_name;
		$link_url = $link->link_url;
		echo "<a href='$link_url' target='_blank'>$link_name</a><br><br>\n";
	}
}

/***** About page functions *****/
function get_about() {
	$about_text = get_option('about_text');
	echo $about_text;
}

/***** Search functions *****/
function processSearchForm($aFormValues)
{
	global $gcdb;
	
	$keyword = trim($aFormValues['keyword']);
	
	if($keyword!=""){
	    //if(get_option('charset')!='gb2312')
    	  $keyword = iconv( "UTF-8", "gb2312" , $keyword);
    	$keyword = $gcdb->escape($keyword);
    	$keywords = explode(" ", $keyword);
    	$keyword_count = count($keywords);
    
    
    	$request = "SELECT ID,post_title from $gcdb->posts WHERE (";
    	
    	for($i=0;$i < $keyword_count;$i++)
    	{
    		$kw = $keywords[$i];
    		if($i!=0)
    			$request .= " OR ";
    		$request .= "post_title LIKE '%$kw%' OR post_content LIKE '%$kw%'";
    	}
    	$request .= ") AND post_status='publish'";
    	
        $gcdb->query("SET NAMES 'gb2312'");
    	$search_results = $gcdb->get_results($request);
    	$numbers = count($search_results);
    	
    	$text = '';
    	
    	for($i=0;$i<$numbers;$i++)
    	{
    		$search_result = $search_results[$i];
    		$post_ID = $search_result->ID;
    		$post_title = $search_result->post_title;
    		$text.= "<a href='".get_permalink($post_ID)."'>$post_title</a><br><br>\n";
    	}
    	
    	$objResponse = new xajaxResponse();
    	$objResponse->addAssign("div1","innerHTML",$text);
    	$objResponse->addAssign("submit","value","search");
    	$objResponse->addAssign("submit","disabled",false);
	}
	else{
    	$objResponse = new xajaxResponse();
    	$objResponse->addAssign("div1","innerHTML","请在搜索框中输入想要查找的内容<br/><br/>");
	}
	
	return $objResponse;
}

/**** Counter functions ****/
function count_first_post_date($echo=true){
    global $gcdb;
    $request = "SELECT MIN(post_date) FROM $gcdb->posts WHERE post_status = 'publish'";
    if($echo)
        echo(mysql2date("F d, Y", $gcdb->get_var($request)));
    else
        return ($gcdb->get_var($request));
}
function count_last_post_date($echo=true){
    global $gcdb;
    $request = "SELECT MAX(post_date) FROM $gcdb->posts WHERE post_status = 'publish'";
    if($echo)
        echo(mysql2date("F d, Y", $gcdb->get_var($request)));
    else
        return ($gcdb->get_var($request));
}
function count_total_posts($echo=true){
    global $gcdb;
    $request = "SELECT COUNT(*) FROM $gcdb->posts WHERE post_status = 'publish'";
    if($echo)
        echo($gcdb->get_var($request));
    else
        return ($gcdb->get_var($request));
}
function count_total_comments($echo=true){
    global $gcdb;
    $request = "SELECT COUNT(*) FROM $gcdb->comments WHERE comment_approved != 'spam'";
    if($echo)
        echo($gcdb->get_var($request));
    else
        return ($gcdb->get_var($request));
}
function count_total_days($echo=true){
    $datetime=count_first_post_date(false);
    
	$leaves_starttime_hours = mysql2date('G', $datetime);
	$leaves_starttime_mins = mysql2date('i', $datetime);
	$leaves_starttime_month = mysql2date('n', $datetime);
	$leaves_starttime_day = mysql2date('d', $datetime);
	$leaves_starttime_year = mysql2date('Y', $datetime);
	
	$start_time_value=mktime($leaves_starttime_hours,$leaves_starttime_mins,1,
                            $leaves_starttime_month,$leaves_starttime_day,$leaves_starttime_year);
	$end_time_value=time();

	$total_secs=(int)$end_time_value-(int)$start_time_value;
	$total_mins=(int)$total_secs/60;
	$total_hours=(int)$total_mins/60;

	$total_days=(int)($total_hours/24); // 即????的相差天?
	
    if($echo)
        echo($total_days);
    else
        return $total_days;
}
function count_posts_per_week(){
    echo(sprintf("%.2f",count_total_posts(false)/count_total_days(false)*7));
}
function count_comments_per_post(){
    echo(sprintf("%.2f",count_total_comments(false)/count_total_posts(false)));
}

/**** Feed functions ****/
/*
function createFeed(){
	global $gcdb,$PHP_SELF,$db_query;
	
	$tag_name = $gcdb->escape($db_query->query_vars['feed']);

	$rss = new UniversalFeedCreator();

	if($tag_name == 'comment'){
		$rss->title = get_option('blog_title')." new comments rss"; 
		$rss->description = "blog comments"; 
		$rss->link = get_option('base_url'); 
		$rss->syndicationURL = get_option('base_url').$PHP_SELF; 
		$rss->cssStyleSheet = "http://www.w3.org/2000/08/w3c-synd/style.css";
		
		$request = "SELECT a.comment_post_id, a.comment_author, a.comment_content, UNIX_TIMESTAMP(a.comment_date) AS comment_date FROM $gcdb->comments a";
		$request .= " WHERE a.comment_approved = '1'";
		$request .= " ORDER BY a.comment_date DESC LIMIT 20";
		$posts = $gcdb->get_results($request);
		$post_num = count($posts);
		
		for ($i=0;$i<$post_num;$i++) { 
			$item = new FeedItem();
			$post = $posts[$i];
			$item->title = 'comment by '.$post->comment_author;
			$item->link = get_option('base_url')."/?q=".$post->comment_post_id;
			$item->description = $post->comment_content;
			$item->date = (int)$post->comment_date;
			$item->author = $post->comment_author;
			$item->authorEmail = get_option('admin_email');
			 
			$rss->addItem($item); 
		}
	}
	else{
		if($tag_name == ''){
			$tag_name = '%';
			$rss->title = get_option('blog_title');
		}
		else
			$rss->title = get_option('blog_title').": ".$tag_name; 
		$rss->description = get_option('blog_title'); 
		$rss->link = get_option('base_url'); 
		$rss->syndicationURL = get_option('base_url').$PHP_SELF; 
		$rss->cssStyleSheet = "http://www.w3.org/2000/08/w3c-synd/style.css";
		
		//$image = new FeedImage(); 
		//$image->title = get_option('blog_title'); 
		//$image->url = ""; 
		//$image->link = get_option('base_url'); 
		//$image->description = "...";
		//$rss->image = $image;
		
		$request = "SELECT a.ID,a.post_title,a.post_content,UNIX_TIMESTAMP(a.post_date) AS post_date FROM $gcdb->posts a, $gcdb->tags b, $gcdb->post2tag c ";
		$request .= "WHERE b.tag_id=c.tag_id AND b.tag_name LIKE '$tag_name' AND c.post_id=a.ID AND a.post_status='publish' ";
		$request .= "GROUP BY a.ID ";
		$request .= "ORDER BY a.post_date DESC LIMIT 10";
		$posts = $gcdb->get_results($request);
		$post_num = count($posts);
		
		for ($i=0;$i<$post_num;$i++) { 
			$item = new FeedItem(); 
			$post = $posts[$i];
			$item->title = $post->post_title;
			$item->link = get_option('base_url')."/?q=".$post->ID; 
			$item->description = $post->post_content;
			$item->date = (int)$post->post_date;
			$item->source = get_option('base_url'); 
			$item->author = get_option('admin_email');
			$item->comments = get_option('base_url')."/?q=".$post->ID."&comment#comment";
			 
			$rss->addItem($item); 
		}
	}
	
	$rss->saveFeed("RSS2.0");
}*/

/***** Security functions *****/

// Look for serverconfiguration "get_magic_quotes_gpc"
function proofAddSlashes($in_string)
{
	if (get_magic_quotes_gpc()==1) {
		return $in_string;
	} else {
		return AddSlashes($in_string);
	}
}

function wp_specialchars( $text, $quotes = 0 ) {
	// Like htmlspecialchars except don't double-encode HTML entities
	$text = str_replace('&&', '&#038;&', $text);
	$text = str_replace('&&', '&#038;&', $text);
	$text = preg_replace('/&(?:$|([^#])(?![a-z1-4]{1,8};))/', '&#038;$1', $text);
	$text = str_replace('<', '&lt;', $text);
	$text = str_replace('>', '&gt;', $text);
	if ( 'double' === $quotes ) {
		$text = str_replace('"', '&quot;', $text);
	} elseif ( 'single' === $quotes ) {
		$text = str_replace("'", '&#039;', $text);
	} elseif ( $quotes ) {
		$text = str_replace('"', '&quot;', $text);
		$text = str_replace("'", '&#039;', $text);
	}
	return $text;
}

function add_magic_quotes($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) {
			$array[$k] = add_magic_quotes($v);
		} else {
			$array[$k] = addslashes($v);
		}
	}
	return $array;
}

function printr($var, $do_not_echo = false) {
	// from php.net/print_r user contributed notes 
	ob_start();
	print_r($var);
	$code =  htmlentities(ob_get_contents());
	ob_clean();
	if (!$do_not_echo) {
	  echo "<pre>$code</pre>";
	}
	return $code;
}

/**** Install ****/

function gc_die($message, $title = '') {
	global $wp_locale;

	header('Content-Type: text/html; charset=utf-8');

	if ( empty($title) )
		$title = __('Graceric &rsaquo; Error');

	if ( strstr($_SERVER['PHP_SELF'], 'admin') )
		$admin_dir = '';
	else
		$admin_dir = 'admin/';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ( function_exists('language_attributes') ) language_attributes(); ?>>
<head>
	<title><?php echo $title ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo $admin_dir; ?>install.css" type="text/css" />
</head>
<body>
	<h1 id="logo">Graceric Blog</h1>
	<p><?php echo $message; ?></p>
</body>
</html>
<?php

	die();
}

function is_blog_installed() {
	global $gcdb;
	$gcdb->hide_errors();
	$installed = $gcdb->get_var("SELECT option_value FROM $gcdb->options WHERE option_name = 'base_url'");
	$gcdb->show_errors();
	return $installed;
}

/**** URL Rewrite ****/

function baseurl_permalink(){
    return get_option('base_url');
}

function get_permalink($id=0,$tagid=0){
    $taglink="";
    if($id==0)
        $id=the_ID(false);
    if($tagid!=0)
        $taglink="&tagid=$tagid";
    return baseurl_permalink().'/?q='.$id.$taglink;
}

function comment_permalink($id=0,$tagid=0){
    $taglink="";
    if($id==0)
        $id=the_ID(false);
    if($tagid!=0)
        $taglink="&tagid=$tagid";
    return baseurl_permalink().'/?q='.$id.'&comment'.$taglink;
}

function comments_permalink($id=0){
    if($id==0)
        $id=the_ID(false);
    return baseurl_permalink().'/?q='.$id."&comment#comment";
}

function archive_permalink(){
    return baseurl_permalink()."/?archive";
}

function archivemonth_permalink($month){
    return baseurl_permalink()."/?archive&month=$month";
}

function search_permalink(){
    return baseurl_permalink()."/?search";
}

function about_permalink(){
    return baseurl_permalink()."/?about";
}

function links_permalink(){
    return baseurl_permalink()."/?links";
}

function tags_permalink(){
    return baseurl_permalink()."/?tags";
}

function tag_permalink($tag){
    return baseurl_permalink()."/?tag=$tag";
}

function feed_permalink(){
    return baseurl_permalink()."/?feed";
}

function tagfeed_permalink($tag){
    return baseurl_permalink()."/?feed=$tag";
}

/*
function post_permalink($post_id = 0, $mode = '') {
	return get_settings('base_url').'/?q='.$post_id;
}

function get_category_link($tag_name) { 
	return get_settings('base_url').'/?tag='.$tag_name;
}

function get_category_rss_link($tag_name) { 
	return get_settings('base_url').'/?feed='.$tag_name;
}*/


?>