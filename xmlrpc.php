<?php
/* Graceric
*  Author: ericfish
*  File: /xmlrpc.php
*  Usage: XML-RPC interface
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: xmlrpc.php 77 2008-07-22 07:34:15Z ericfish $
*  $LastChangedDate: 2008-07-22 15:34:15 +0800 (星期二, 22 七月 2008) $
*  $LastChangedRevision: 77 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/xmlrpc.php $
*/

define('XMLRPC_REQUEST', true);

// Some browser-embedded clients send cookies. We don't want them.
$_COOKIE = array();

# fix for mozBlog and other cases where '<?xml' isn't on the very first line
if ( isset($HTTP_RAW_POST_DATA) )
	$HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA);

include('./gc-header.php');

if ( isset( $_GET['rsd'] ) ) { // http://archipelago.phrasewise.com/rsd 
header('Content-type: text/xml; charset=' . get_option('charset'), true);

?>
<?php echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd">
  <service>
    <engineName>Graceric</engineName>
    <engineLink>http://www.ericfish.com/graceric</engineLink>
    <homePageLink><?php bloginfo_rss('base_url') ?></homePageLink>
    <apis>
      <api name="Movable Type" blogID="1" preferred="true" apiLink="<?php bloginfo_rss('base_url') ?>/xmlrpc.php" />
      <api name="MetaWeblog" blogID="1" preferred="false" apiLink="<?php bloginfo_rss('base_url') ?>/xmlrpc.php" />
      <api name="Blogger" blogID="1" preferred="false" apiLink="<?php bloginfo_rss('base_url') ?>/xmlrpc.php" />
    </apis>
  </service>
</rsd>
<?php
exit;
}

include_once(ABSPATH . WPINC . '/class-IXR.php');

// Turn off all warnings and errors.
// error_reporting(0);

$post_default_title = ""; // posts submitted via the xmlrpc interface get that title

$xmlrpc_logging = 0;

function logIO($io,$msg) {
	global $xmlrpc_logging;
	if ($xmlrpc_logging) {
		$fp = fopen("../xmlrpc.log","a+");
		$date = gmdate("Y-m-d H:i:s ");
		$iot = ($io == "I") ? " Input: " : " Output: ";
		fwrite($fp, "\n\n".$date.$iot.$msg);
		fclose($fp);
	}
	return true;
	}

function starify($string) {
	$i = strlen($string);
	return str_repeat('*', $i);
}

if ( isset($HTTP_RAW_POST_DATA) )
  logIO("I", $HTTP_RAW_POST_DATA);


function mkdir_p($target) {
	// from php.net/mkdir user contributed notes 
	if (file_exists($target)) {
	  if (!is_dir($target)) {
	    return false;
	  } else {
	    return true;
	  }
	}

	// Attempting to create the directory may clutter up our display.
	if (@mkdir($target)) {
	  return true;
	}

	// If the above failed, attempt to create the parent node, then try again.
	if (mkdir_p(dirname($target))) {
	  return mkdir_p($target);
	}

	return false;
}


class wp_xmlrpc_server extends IXR_Server {

	function wp_xmlrpc_server() {
		$this->methods = array(
		  // Blogger API
		  'blogger.getUsersBlogs' => 'this:blogger_getUsersBlogs',
		  'blogger.getUserInfo' => 'this:blogger_getUserInfo',
		  'blogger.getPost' => 'this:blogger_getPost',
		  'blogger.getRecentPosts' => 'this:blogger_getRecentPosts',
		  'blogger.getTemplate' => 'this:blogger_getTemplate',
		  'blogger.setTemplate' => 'this:blogger_setTemplate',
		  'blogger.newPost' => 'this:blogger_newPost',
		  'blogger.editPost' => 'this:blogger_editPost',
		  'blogger.deletePost' => 'this:blogger_deletePost',

		  // MetaWeblog API (with MT extensions to structs)
		  'metaWeblog.newPost' => 'this:mw_newPost',
		  'metaWeblog.editPost' => 'this:mw_editPost',
		  'metaWeblog.getPost' => 'this:mw_getPost',
		  'metaWeblog.getRecentPosts' => 'this:mw_getRecentPosts',
		  'metaWeblog.getCategories' => 'this:mw_getCategories',
		  'metaWeblog.newMediaObject' => 'this:mw_newMediaObject',

		  // MetaWeblog API aliases for Blogger API
		  // see http://www.xmlrpc.com/stories/storyReader$2460
		  'metaWeblog.deletePost' => 'this:blogger_deletePost',
		  'metaWeblog.getTemplate' => 'this:blogger_getTemplate',
		  'metaWeblog.setTemplate' => 'this:blogger_setTemplate',
		  '
' => 'this:blogger_getUsersBlogs',

		  // MovableType API
		  'mt.getCategoryList' => 'this:mt_getCategoryList',
		  'mt.getRecentPostTitles' => 'this:mt_getRecentPostTitles',
		  'mt.getPostCategories' => 'this:mt_getPostCategories',
		  'mt.setPostCategories' => 'this:mt_setPostCategories',
		  'mt.supportedMethods' => 'this:mt_supportedMethods',
		  'mt.supportedTextFilters' => 'this:mt_supportedTextFilters',
		  'mt.getTrackbackPings' => 'this:mt_getTrackbackPings',
		  'mt.publishPost' => 'this:mt_publishPost',

		  'demo.sayHello' => 'this:sayHello',
		  'demo.addTwoNumbers' => 'this:addTwoNumbers'
		);
		//$this->methods = apply_filters('xmlrpc_methods', $this->methods);
		$this->IXR_Server($this->methods);
	}

	function sayHello($args) {
		return 'Hello!';
	}

	function addTwoNumbers($args) {
		$number1 = $args[0];
		$number2 = $args[1];
		return $number1 + $number2;
	}

	function login_pass_ok($user_login, $user_pass) {
	  if (!user_pass_ok($user_login, $user_pass)) {
	    $this->error = new IXR_Error(403, 'Bad login/pass combination.');
	    return false;
	  }
	  return true;
	}

	function escape(&$array) {
		global $gcdb;

		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$this->escape($array[$k]);
			} else if (is_object($v)) {
				//skip
			} else {
				$array[$k] = $gcdb->escape($v);
			}
		}
	}

	/* Blogger API functions
	 * specs on http://plant.blogger.com/api and http://groups.yahoo.com/group/bloggerDev/
	 */


	/* blogger.getUsersBlogs will make more sense once we support multiple blogs */
	function blogger_getUsersBlogs($args) {

		$this->escape($args);

	  $user_login = $args[1];
	  $user_pass  = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $struct = array(
	    'isAdmin'  => true,
	    'url'      => get_settings('base_url') . '/',
	    'blogid'   => '1',
	    'blogName' => get_settings('blog_title')
	  );

	  return array($struct);
	}


	/* blogger.getUsersInfo gives your client some info about you, so you don't have to */
	function blogger_getUserInfo($args) {

		$this->escape($args);

	  $user_login = $args[1];
	  $user_pass  = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $struct = array(
	    'nickname'  => $user_data->user_login,
	    'userid'    => $user_data->ID,
	    'url'       => $user_data->user_url,
	    'email'     => $user_data->user_email,
	    'lastname'  => $user_data->user_lastname,
	    'firstname' => $user_data->user_firstname
	  );

	  return $struct;
	}


	/* blogger.getPost ...gets a post */
	function blogger_getPost($args) {

		$this->escape($args);

	  $post_ID    = $args[1];
	  $user_login = $args[2];
	  $user_pass  = $args[3];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);
	  $post_data = gc_get_single_post($post_ID, ARRAY_A);

	  $categories = implode(',', gc_get_post_cats(1, $post_ID));

	  $content  = '<title>'.stripslashes($post_data['post_title']).'</title>';
	  $content .= '<category>'.$categories.'</category>';
	  $content .= stripslashes($post_data['post_content']);

	  $struct = array(
	    'userid'    => $post_data['post_author'],
	    'dateCreated' => new IXR_Date(mysql2date('Ymd\TH:i:s', $post_data['post_date'])),
	    'content'     => $content,
	    'postid'  => $post_data['ID']
	  );

	  return $struct;
	}


	/* blogger.getRecentPosts ...gets recent posts */
	function blogger_getRecentPosts($args) {

	  global $gcdb;

		$this->escape($args);

	  $blog_ID    = $args[1]; /* though we don't use it yet */
	  $user_login = $args[2];
	  $user_pass  = $args[3];
	  $num_posts  = $args[4];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $posts_list = gc_get_recent_posts($num_posts);

	  if (!$posts_list) {
	    $this->error = new IXR_Error(500, 'Either there are no posts, or something went wrong.');
	    return $this->error;
	  }

	  foreach ($posts_list as $entry) {
	  
	    $post_date = mysql2date('Ymd\TH:i:s', $entry['post_date']);
	    $categories = implode(',', gc_get_post_cats(1, $entry['ID']));

	    $content  = '<title>'.stripslashes($entry['post_title']).'</title>';
	    $content .= '<category>'.$categories.'</category>';
	    $content .= stripslashes($entry['post_content']);

	    $struct[] = array(
	      'userid' => $entry['post_author'],
	      'dateCreated' => new IXR_Date($post_date),
	      'content' => $content,
	      'postid' => $entry['ID'],
	    );

	  }

	  $recent_posts = array();
	  for ($j=0; $j<count($struct); $j++) {
	    array_push($recent_posts, $struct[$j]);
	  }

	  return $recent_posts;
	}


	/* blogger.getTemplate returns your blog_filename */
	function blogger_getTemplate($args) {

		$this->escape($args);

	  $blog_ID    = $args[1];
	  $user_login = $args[2];
	  $user_pass  = $args[3];
	  $template   = $args[4]; /* could be 'main' or 'archiveIndex', but we don't use it */

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  if ($user_data->user_level < 3) {
	    return new IXR_Error(401, 'Sorry, users whose level is less than 3, can not edit the template.');
	  }

	  /* warning: here we make the assumption that the weblog's URI is on the same server */
	  $filename = get_settings('base_url') . '/';
	  $filename = preg_replace('#http://.+?/#', $_SERVER['DOCUMENT_ROOT'].'/', $filename);

	  $f = fopen($filename, 'r');
	  $content = fread($f, filesize($filename));
	  fclose($f);

	  /* so it is actually editable with a windows/mac client */
	  // FIXME: (or delete me) do we really want to cater to bad clients at the expense of good ones by BEEPing up their line breaks? commented.     $content = str_replace("\n", "\r\n", $content); 

	  return $content;
	}


	/* blogger.setTemplate updates the content of blog_filename */
	function blogger_setTemplate($args) {

		$this->escape($args);

	  $blog_ID    = $args[1];
	  $user_login = $args[2];
	  $user_pass  = $args[3];
	  $content    = $args[4];
	  $template   = $args[5]; /* could be 'main' or 'archiveIndex', but we don't use it */

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  if ($user_data->user_level < 3) {
	    return new IXR_Error(401, 'Sorry, users whose level is less than 3, can not edit the template.');
	  }

	  /* warning: here we make the assumption that the weblog's URI is on the same server */
	  $filename = get_settings('base_url') . '/';
	  $filename = preg_replace('#http://.+?/#', $_SERVER['DOCUMENT_ROOT'].'/', $filename);

	  if ($f = fopen($filename, 'w+')) {
	    fwrite($f, $content);
	    fclose($f);
	  } else {
	    return new IXR_Error(500, 'Either the file is not writable, or something wrong happened. The file has not been updated.');
	  }

	  return true;
	}


	/* blogger.newPost ...creates a new post */
	function blogger_newPost($args) {

	  global $gcdb;

		$this->escape($args);

	  $blog_ID    = $args[1]; /* though we don't use it yet */
	  $user_login = $args[2];
	  $user_pass  = $args[3];
	  $content    = $args[4];
	  $publish    = $args[5];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $post_status = ($publish) ? 'publish' : 'draft';

	  $post_author = $user_data->ID;

	  $post_title = xmlrpc_getposttitle($content);
	  $post_category = xmlrpc_getpostcategory($content);

	  $content = xmlrpc_removepostdata($content);
	  $post_content = $content;

	  $post_date = current_time('mysql');
	  $post_date_gmt = current_time('mysql', 1);

	  $post_data = compact('blog_ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_category', 'post_status');

	  $post_ID = gc_insert_post($post_data);

	  if (!$post_ID) {
	    return new IXR_Error(500, 'Sorry, your entry could not be posted. Something wrong happened.');
	  }

	  logIO('O', "Posted ! ID: $post_ID");

	  return $post_ID;
	}


	/* blogger.editPost ...edits a post */
	function blogger_editPost($args) {

	  global $gcdb;

		$this->escape($args);

	  $post_ID     = $args[1];
	  $user_login  = $args[2];
	  $user_pass   = $args[3];
	  $new_content = $args[4];
	  $publish     = $args[5];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $actual_post = gc_get_single_post($post_ID,ARRAY_A);

	  if (!$actual_post) {
	  	return new IXR_Error(404, 'Sorry, no such post.');
	  }

		$this->escape($actual_post);

	  $post_author_data = get_userdata($actual_post['post_author']);
	  $user_data = get_userdatabylogin($user_login);

	  extract($actual_post);

	  $content = $newcontent;

	  $post_title = xmlrpc_getposttitle($content);
	  $post_category = xmlrpc_getpostcategory($content);

	  $content = xmlrpc_removepostdata($content);
	  $post_content = $content;

	  $postdata = compact('ID', 'post_content', 'post_title', 'post_category', 'post_status', 'post_excerpt');

	  $result = gc_update_post($postdata);

	  if (!$result) {
	  	return new IXR_Error(500, 'For some strange yet very annoying reason, this post could not be edited.');
	  }

	  return true;
	}


	/* blogger.deletePost ...deletes a post */
	function blogger_deletePost($args) {

	  global $gcdb;

		$this->escape($args);

	  $post_ID     = $args[1];
	  $user_login  = $args[2];
	  $user_pass   = $args[3];
	  $publish     = $args[4];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $actual_post = gc_get_single_post($post_ID,ARRAY_A);

	  if (!$actual_post) {
	  	return new IXR_Error(404, 'Sorry, no such post.');
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $result = gc_delete_post($post_ID);

	  if (!$result) {
	  	return new IXR_Error(500, 'For some strange yet very annoying reason, this post could not be deleted.');
	  }

	  return true;
	}



	/* MetaWeblog API functions
	 * specs on wherever Dave Winer wants them to be
	 */

	/* metaweblog.newPost creates a post */
	function mw_newPost($args) {

	  global $gcdb, $post_default_category;

		$this->escape($args);

	  $blog_ID     = $args[0]; // we will support this in the near future
	  $user_login  = $args[1];
	  $user_pass   = $args[2];
	  $content_struct = $args[3];
	  $publish     = $args[4];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $post_author = $user_data->ID;

	  $post_title = transcode_bak($content_struct['title']);
	  $post_content = transcode_bak($content_struct['description']);
	  $post_status = $publish ? 'publish' : 'draft';

	  // Do some timestamp voodoo
	  if(isset($content_struct['dateCreated']))
		$dateCreatedd = $content_struct['dateCreated'];
	  else
		  $dateCreatedd = "";
	  if (!empty($dateCreatedd)) {
	    $dateCreated = $dateCreatedd->getIso();
	    $post_date     = get_date_from_gmt(iso8601_to_datetime($dateCreated));
	    $post_date_gmt = iso8601_to_datetime($dateCreated, GMT);
	  } else {
	    $post_date     = current_time('mysql');
	    $post_date_gmt = current_time('mysql', 1);
	  }

	  $catnames = $content_struct['categories'];
	  logIO('O', 'Post cats: ' . printr($catnames,true));
	  $post_category = array();

	  if (is_array($catnames)) {
	    foreach ($catnames as $cat) {
	      $post_category[] = get_cat_ID($cat);
	    }
	  }
		
	  // We've got all the data -- post it:
	  $postdata = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_category', 'post_status');

	  $post_ID = gc_insert_post($postdata);

	  if (!$post_ID) {
	    return new IXR_Error(500, 'Sorry, your entry could not be posted. Something wrong happened.');
	  }

	  logIO('O', "Posted ! ID: $post_ID");

	  // FIXME: do we pingback always? pingback($content, $post_ID);
	  // trackback_url_list($content_struct['mt_tb_ping_urls'],$post_ID);

	  return strval($post_ID);
	}


	/* metaweblog.editPost ...edits a post */
	function mw_editPost($args) {

	  global $gcdb, $post_default_category;

		$this->escape($args);

	  $post_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];
	  $content_struct = $args[3];
	  $publish     = $args[4];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $postdata = gc_get_single_post($post_ID, ARRAY_A);
	  extract($postdata);
		$this->escape($postdata);

	  $post_title = transcode_bak($content_struct['title']);
	  $post_content = transcode_bak($content_struct['description']);
	  $catnames = $content_struct['categories'];

	  $post_category = array();
		
	  if (is_array($catnames)) {
	    foreach ($catnames as $cat) {
	      $post_category[] = get_cat_ID($cat);
	    }
	  }
	  $post_status = $publish ? 'publish' : 'draft';

	  // We've got all the data -- post it:
	  $newpost = compact('ID', 'post_content', 'post_title', 'post_category', 'post_status');

	  $result = gc_update_post($newpost);
	  if (!$result) {
	    return new IXR_Error(500, 'Sorry, your entry could not be edited. Something wrong happened.');
	  }

	  // FIXME: do we pingback always? pingback($content, $post_ID);
	  // trackback_url_list($content_struct['mt_tb_ping_urls'], $post_ID);

	  return true;
	}


	/* metaweblog.getPost ...returns a post */
	function mw_getPost($args) {

	  global $gcdb;

		$this->escape($args);

	  $post_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $postdata = gc_get_single_post($post_ID, ARRAY_A);

	  if ($postdata['post_date'] != '') {

	    $post_date = mysql2date('Ymd\TH:i:s', $postdata['post_date']);

	    $categories = array();
	    $catids = gc_get_post_cats('', $post_ID);
	    foreach($catids as $catid) {
	      $categories[] = get_cat_name($catid);
	    }

	    $post = get_extended($postdata['post_content']);
	    $link = get_permalink($postdata['ID']);

	    $allow_comments = 1;
	    $allow_pings = 1;

	    $resp = array(
	      'dateCreated' => new IXR_Date($post_date),
	      'userid' => $postdata['post_author'],
	      'postid' => $postdata['ID'],
	      'description' => $post['main'],
	      'title' => $postdata['post_title'],
	      'link' => $link,
	      'permaLink' => $link,
// commented out because no other tool seems to use this
//	      'content' => $entry['post_content'],
	      'categories' => $categories,
	      'mt_excerpt' => $postdata['post_excerpt'],
	      'mt_text_more' => $post['extended'],
	      'mt_allow_comments' => $allow_comments,
	      'mt_allow_pings' => $allow_pings
	    );

	    return $resp;
	  } else {
	  	return new IXR_Error(404, 'Sorry, no such post.');
	  }
	}


	/* metaweblog.getRecentPosts ...returns recent posts */
	function mw_getRecentPosts($args) {

		$this->escape($args);

	  $blog_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];
	  $num_posts   = $args[3];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $posts_list = gc_get_recent_posts($num_posts);

	  if (!$posts_list) {
	    $this->error = new IXR_Error(500, 'Either there are no posts, or something went wrong.');
	    return $this->error;
	  }

	  foreach ($posts_list as $entry) {
	  
	    $post_date = mysql2date('Ymd\TH:i:s', $entry['post_date']);
	    $categories = array();
	    $catids = gc_get_post_cats('', $entry['ID']);
	    foreach($catids as $catid) {
	      $categories[] = get_cat_name($catid);
	    }

	    $post = get_extended($entry['post_content']);
	    $link = get_permalink($entry['ID']);

	    $allow_comments = 1;
	    $allow_pings = 1;

	    $struct[] = array(
	      'dateCreated' => new IXR_Date($post_date),
	      'userid' => $entry['post_author'],
	      'postid' => $entry['ID'],
	      'description' => $post['main'],
	      'title' => transcode($entry['post_title']),
	      'link' => $link,
	      'permaLink' => $link,
// commented out because no other tool seems to use this
//	      'content' => $entry['post_content'],
	      'categories' => $categories,
	      'mt_excerpt' => $entry['post_excerpt'],
	      'mt_text_more' => $post['extended'],
	      'mt_allow_comments' => $allow_comments,
	      'mt_allow_pings' => $allow_pings
	    );

	  }

	  $recent_posts = array();
	  for ($j=0; $j<count($struct); $j++) {
	    array_push($recent_posts, $struct[$j]);
	  }
	  
	  return $recent_posts;
	}


	/* metaweblog.getCategories ...returns the list of categories on a given weblog */
	function mw_getCategories($args) {

	  global $gcdb;

		$this->escape($args);

	  $blog_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $categories_struct = array();

	  // FIXME: can we avoid using direct SQL there?
	  if ($cats = $gcdb->get_results("SELECT tag_ID,tag_name FROM $gcdb->tags", ARRAY_A)) {
	    foreach ($cats as $cat) {
	      $struct['categoryId'] = $cat['tag_ID'];
	      $struct['description'] = transcode($cat['tag_name']);
	      $struct['categoryName'] = transcode($cat['tag_name']);
	      $struct['htmlUrl'] = tag_permalink(transcode($cat['tag_name']));
	      $struct['rssUrl'] = tagfeed_permalink(transcode($cat['tag_name']));
	      
	      $categories_struct[] = $struct;
	    }
	  }

	  return $categories_struct;
	}


	/* metaweblog.newMediaObject uploads a file, following your settings */
	function mw_newMediaObject($args) {
	  // adapted from a patch by Johann Richard
	  // http://mycvs.org/archives/2004/06/30/file-upload-to-wordpress-in-ecto/

	    // Uploads not allowed
	    logIO('O', '(MW) Uploads not allowed');
	    $this->error = new IXR_Error(405, 'No uploads allowed for this site.');
	    return $this->error;

	}



	/* MovableType API functions
	 * specs on http://www.movabletype.org/docs/mtmanual_programmatic.html
	 */

	/* mt.getRecentPostTitles ...returns recent posts' titles */
	function mt_getRecentPostTitles($args) {

		$this->escape($args);

	  $blog_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];
	  $num_posts   = $args[3];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $posts_list = gc_get_recent_posts($num_posts);

	  if (!$posts_list) {
	    $this->error = new IXR_Error(500, 'Either there are no posts, or something went wrong.');
	    return $this->error;
	  }

	  foreach ($posts_list as $entry) {
	  
	    $post_date = mysql2date('Ymd\TH:i:s', $entry['post_date']);

	    $struct[] = array(
	      'dateCreated' => new IXR_Date($post_date),
	      'userid' => $entry['post_author'],
	      'postid' => $entry['ID'],
	      'title' => transcode($entry['post_title']),
	    );

	  }

	  $recent_posts = array();
	  for ($j=0; $j<count($struct); $j++) {
	    array_push($recent_posts, $struct[$j]);
	  }
	  
	  return $recent_posts;
	}


	/* mt.getCategoryList ...returns the list of categories on a given weblog */
	function mt_getCategoryList($args) {

	  global $gcdb;

		$this->escape($args);

	  $blog_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $categories_struct = array();

	  // FIXME: can we avoid using direct SQL there?
	  if ($cats = $gcdb->get_results("SELECT tag_ID,tag_name FROM $gcdb->tags WHERE tag_description !='noshow'", ARRAY_A)) {
	    foreach ($cats as $cat) {
	      $struct['categoryId'] = $cat['tag_ID'];
	      $struct['categoryName'] = transcode($cat['tag_name']);

	      $categories_struct[] = $struct;
	    }
	  }

	  return $categories_struct;
	}


	/* mt.getPostCategories ...returns a post's categories */
	function mt_getPostCategories($args) {

		$this->escape($args);

	  $post_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $categories = array();
	  $catids = gc_get_post_cats('', intval($post_ID));
	  // first listed category will be the primary category
	  $isPrimary = true;
	  foreach($catids as $catid) {
	    $categories[] = array(
	      'categoryName' => transcode(get_cat_name($catid)),
	      'categoryId' => $catid,
	      'isPrimary' => $isPrimary
	    );
	    $isPrimary = false;
	  }
 
	  return $categories;
	}


	/* mt.setPostCategories ...sets a post's categories */
	function mt_setPostCategories($args) {

		$this->escape($args);

	  $post_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];
	  $categories  = $args[3];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  foreach($categories as $cat) {
	    $catids[] = $cat['categoryId'];
	  }
	
	  gc_set_post_cats('', $post_ID, $catids);

	  return true;
	}


	/* mt.supportedMethods ...returns an array of methods supported by this server */
	function mt_supportedMethods($args) {

	  $supported_methods = array();
	  foreach($this->methods as $key=>$value) {
	    $supported_methods[] = $key;
	  }

	  return $supported_methods;
	}


	/* mt.supportedTextFilters ...returns an empty array because we don't
	   support per-post text filters yet */
	function mt_supportedTextFilters($args) {
	  return array();
	}


	/* mt.getTrackbackPings ...returns trackbacks sent to a given post */
	function mt_getTrackbackPings($args) {

	    // TrackbackPings not allowed
	    $this->error = new IXR_Error(405, 'No TrackbackPings allowed for this site.');
	    return $this->error;
	}


	/* mt.publishPost ...sets a post's publish status to 'publish' */
	function mt_publishPost($args) {

		$this->escape($args);

	  $post_ID     = $args[0];
	  $user_login  = $args[1];
	  $user_pass   = $args[2];

	  if (!$this->login_pass_ok($user_login, $user_pass)) {
	    return $this->error;
	  }

	  $user_data = get_userdatabylogin($user_login);

	  $postdata = gc_get_single_post($post_ID,ARRAY_A);

	  $postdata['post_status'] = 'publish';

	  // retain old cats
	  $cats = gc_get_post_cats('',$post_ID);
	  $postdata['post_category'] = $cats;
		$this->escape($postdata);

	  $result = gc_update_post($postdata);

	  return $result;
	}



	/* PingBack functions
	 * specs on www.hixie.ch/specs/pingback/pingback
	 */

	/* pingback.ping gets a pingback and registers it */
	function pingback_ping($args) {
		
		return "Pingback is not allowed here. Keep the web talking! :-)";
	}


	/* pingback.extensions.getPingbacks returns an array of URLs
	   that pingbacked the given URL
	   specs on http://www.aquarionics.com/misc/archives/blogite/0198.html */
	function pingback_extensions_getPingbacks($args) {

		return "pingbacks";
	}
}


$wp_xmlrpc_server = new wp_xmlrpc_server();

?>
