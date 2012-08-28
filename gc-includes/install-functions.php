<?php

if ( !function_exists('gc_install') ) :
function gc_install($blog_title, $user_name, $user_email, $prefix, $charset, $meta='') {
	global $gc_rewrite;

	gc_check_mysql_version();
	
	$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
	$guessurl = preg_replace('|/admin/.*|i', '', $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

	gc_install_defaults($blog_title, $user_name, $user_email, $guessurl, $prefix, $charset);

	return array('url' => $guessurl, 'user_id' => $user_name, 'password' => 'admin');
}
endif;

function trans_sql_utf8($charset,$query){
    if($charset=='utf-8')
        $query = iconv("gb2312", "UTF-8//IGNORE" , $query);
    return $query;
}

if ( !function_exists('gc_install_defaults') ) :
function gc_install_defaults($blog_title, $user_name, $user_email, $guessurl, $prefix, $charset) {
    $db_charstring = "";
    $db_charset = "";
    $db_collate = "";
    
    if($charset=="utf-8"){
        $db_charset = "utf8";
        $db_collate = "utf8_general_ci";
        $db_charstring = " CHARACTER SET $db_charset COLLATE $db_collate";
    }
    elseif($charset=="gb2312"){
        $db_charset = "gb2312";
        $db_collate = "gb2312_chinese_ci";
        $db_charstring = " CHARACTER SET $db_charset COLLATE $db_collate";
    }
    elseif($charset=="gbk"){
        $db_charset = "gbk";
        $db_collate = "gbk_chinese_ci";
        $db_charstring = " CHARACTER SET $db_charset COLLATE $db_collate";
    }
    else{
        $db_charstring = "";
    }
    
	global $gcdb;
	
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."tags (
  tag_ID bigint(20) NOT NULL auto_increment,
  tag_name varchar(55)$db_charstring NOT NULL default '',
  tag_nicename varchar(200)$db_charstring NOT NULL default '',
  tag_description longtext$db_charstring NOT NULL,
  tag_parent int(4) NOT NULL default '0',
  PRIMARY KEY  (tag_ID),
  KEY tag_nicename (tag_nicename)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."comments (
  comment_ID bigint(20) unsigned NOT NULL auto_increment,
  comment_post_ID bigint(20) NOT NULL default '0',
  comment_author tinytext$db_charstring NOT NULL,
  comment_author_email varchar(100)$db_charstring NOT NULL default '',
  comment_author_url varchar(200)$db_charstring NOT NULL default '',
  comment_author_IP varchar(100)$db_charstring NOT NULL default '',
  comment_date datetime NOT NULL default '0000-00-00 00:00:00',
  comment_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  comment_content text$db_charstring NOT NULL,
  comment_karma int(11) NOT NULL default '0',
  comment_approved enum('0','1','spam')$db_charstring NOT NULL default '1',
  comment_agent varchar(255)$db_charstring NOT NULL default '',
  comment_type varchar(20)$db_charstring NOT NULL default '',
  comment_parent int(11) NOT NULL default '0',
  request_email enum('no','yes')$db_charstring NOT NULL default 'no',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (comment_ID),
  KEY comment_approved (comment_approved),
  KEY comment_post_ID (comment_post_ID)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."links (
  link_id bigint(20) NOT NULL auto_increment,
  link_url varchar(255)$db_charstring NOT NULL default '',
  link_name varchar(255)$db_charstring NOT NULL default '',
  link_image varchar(255)$db_charstring NOT NULL default '',
  link_target varchar(25)$db_charstring NOT NULL default '',
  link_tag int(11) NOT NULL default '0',
  link_description varchar(255)$db_charstring NOT NULL default '',
  link_visible enum('Y','N')$db_charstring NOT NULL default 'Y',
  link_owner int(11) NOT NULL default '1',
  link_rating int(11) NOT NULL default '0',
  link_updated datetime NOT NULL default '0000-00-00 00:00:00',
  link_rel varchar(255)$db_charstring NOT NULL default '',
  link_notes mediumtext$db_charstring NOT NULL,
  link_rss varchar(255)$db_charstring NOT NULL default '',
  PRIMARY KEY  (link_id),
  KEY link_tag (link_tag),
  KEY link_visible (link_visible)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."options (
  option_id bigint(20) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  option_name varchar(64)$db_charstring NOT NULL default '',
  option_can_override enum('Y','N')$db_charstring NOT NULL default 'Y',
  option_type int(11) NOT NULL default '1',
  option_value longtext$db_charstring NOT NULL,
  option_width int(11) NOT NULL default '20',
  option_height int(11) NOT NULL default '8',
  option_description tinytext$db_charstring NOT NULL,
  option_admin_level int(11) NOT NULL default '1',
  autoload enum('yes','no')$db_charstring NOT NULL default 'yes',
  PRIMARY KEY  (option_id,blog_id,option_name),
  KEY option_name (option_name)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."post2tag (
  rel_id bigint(20) NOT NULL auto_increment,
  post_id bigint(20) NOT NULL default '0',
  tag_id bigint(20) NOT NULL default '0',
  PRIMARY KEY  (rel_id),
  KEY post_id (post_id,tag_id)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."posts (
  ID bigint(20) unsigned NOT NULL,
  post_author int(4) NOT NULL default '0',
  post_date datetime NOT NULL default '0000-00-00 00:00:00',
  post_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content longtext$db_charstring NOT NULL,
  post_title text$db_charstring NOT NULL,
  post_tag int(4) NOT NULL default '0',
  post_excerpt text$db_charstring NOT NULL,
  post_status enum('publish','draft','private','static','object')$db_charstring NOT NULL default 'publish',
  comment_status enum('open','closed','registered_only')$db_charstring NOT NULL default 'open',
  ping_status enum('open','closed')$db_charstring NOT NULL default 'open',
  post_password varchar(20)$db_charstring NOT NULL default '',
  post_name varchar(200)$db_charstring NOT NULL default '',
  to_ping text$db_charstring NOT NULL,
  pinged text$db_charstring NOT NULL,
  post_modified datetime NOT NULL default '0000-00-00 00:00:00',
  post_modified_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content_filtered text$db_charstring NOT NULL,
  post_parent int(11) NOT NULL default '0',
  guid varchar(255)$db_charstring NOT NULL default '',
  menu_order int(11) NOT NULL default '0',
  show_in_home enum('yes','no')$db_charstring NOT NULL default 'yes',
  PRIMARY KEY  (ID),
  KEY post_name (post_name)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."users (
  ID bigint(20) unsigned NOT NULL auto_increment,
  user_login varchar(60)$db_charstring NOT NULL default '',
  user_pass varchar(64)$db_charstring NOT NULL default '',
  user_firstname varchar(50)$db_charstring NOT NULL default '',
  user_lastname varchar(50)$db_charstring NOT NULL default '',
  user_nickname varchar(50)$db_charstring NOT NULL default '',
  user_nicename varchar(50)$db_charstring NOT NULL default '',
  user_icq int(10) unsigned NOT NULL default '0',
  user_email varchar(100)$db_charstring NOT NULL default '',
  user_url varchar(100)$db_charstring NOT NULL default '',
  user_ip varchar(15)$db_charstring NOT NULL default '',
  user_domain varchar(200)$db_charstring NOT NULL default '',
  user_browser varchar(200)$db_charstring NOT NULL default '',
  user_registered datetime NOT NULL default '0000-00-00 00:00:00',
  user_level int(2) unsigned NOT NULL default '0',
  user_aim varchar(50)$db_charstring NOT NULL default '',
  user_msn varchar(100)$db_charstring NOT NULL default '',
  user_yim varchar(50)$db_charstring NOT NULL default '',
  user_idmode varchar(20)$db_charstring NOT NULL default '',
  user_activation_key varchar(60)$db_charstring NOT NULL default '',
  user_status int(11) NOT NULL default '0',
  user_description longtext$db_charstring NOT NULL default '',
  PRIMARY KEY  (ID),
  UNIQUE KEY user_login (user_login)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."id (
  ID bigint(20) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (ID)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."spams (
  spam_ID bigint(20) NOT NULL auto_increment,
  spam_value varchar(200)$db_charstring NOT NULL default '',
  spam_type enum('name','email','text','ip')$db_charstring NOT NULL default 'text',  
  PRIMARY KEY  (spam_ID)
);"));
	$gcdb->query(trans_sql_utf8($charset,"CREATE TABLE ".$prefix."x (
  x_ID bigint(20) NOT NULL auto_increment,
  post_ID bigint(20) NOT NULL,
  x_name varchar(200)$db_charstring NOT NULL,  
  PRIMARY KEY  (x_ID),
  UNIQUE KEY x_name (x_name)
);"));
	$gcdb->query(trans_sql_utf8($charset,"INSERT INTO `".$prefix."options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'admin_email', 'Y', '1', '$user_email', '20', '8', '����Ա�ʼ���ַ', '1', 'yes'
), (
NULL , '0', 'base_url', 'Y', '1', '$guessurl', '20', '8', '��ҳ��ַ(���û��б��)', '1', 'yes'
), (
NULL , '0', 'home_post_number', 'Y', '1', '1', '20', '8', '��ҳ��ʾ��blog��', '1', 'yes'
), (
NULL , '0', 'blog_title', 'Y', '1', '$blog_title', '20', '8', '��վ����', '1', 'yes'
), (
NULL , '0', 'about_text', 'Y', '1', '<p> Write your self introduction in the <a href=admin>Admin</a> -&gt; <a href=admin/editabout.php>Edit About</a> Page.</p><p> 	�뵽<a href=admin>����ҳ��</a> -&gt; <a href=admin/editabout.php>�༭���˼��</a> �б༭��ĸ��˼�顣</p><p>����������ϵericfish[at]gmail.com</p>', '20', '8', '���ҽ�������', '1', 'yes'
), (
NULL , '0', 'prev_links', 'Y', '1', '1', '20', '8', '���ӵ���ǰ��blog�����Ӹ���', '1', 'yes'
), (
NULL , '0', 'blog_subtitle', 'Y', '1', '', '20', '8', '��վ�ӱ���', '1', 'yes'
), (
NULL , '0', 'template', 'Y', '1', 'default', '20', '8', 'Blog��ģ��', '1', 'yes'
), (
NULL , '0', 'admin_post_number', 'Y', '1', '10', '20', '8', '�������ÿҳ��ʾ��Blog��', '1', 'yes'
), (
NULL , '0', 'about_title', 'Y', '1', 'About Me', '20', '8', '���˼�����(��ѡ)', '1', 'yes'
), (
NULL , '0', 'rss_link', 'Y', '1', '', '20', '8', 'RSS���ӵ�ַ', '1', 'yes'
), (
NULL , '0', 'charset', 'Y', '1', '$charset', '20', '8', '�ַ���:utf-8|gb2312', '1', 'yes'
), (
NULL , '0', 'keywords', 'Y', '1', 'blog website', '20', '8', '��վ�ؼ���(�ո�ָ�)', '1', 'yes'
), (
NULL , '0', 'blog_author', 'Y', '1', 'anonymous', '20', '8', '��������', '1', 'yes'
), (
NULL , '0', 'comment_email', 'Y', '1', 'no', '20', '8', '�Ƿ����������ʼ�,yes:����', '1', 'yes'
), (
NULL , '0', 'change_password_msg', 'Y', '1', '��δ���Ĺ�admin�ĳ�ʼ���룬�⽫��������վ������ȫ���������admin�û��趨�µ����롣', '20', '8', '���ε�¼�ĸ���������ʾ', '1', 'yes'
), (
NULL , '0', 'gmt_offset', 'Y', '1', '8', '20', '8', '�����ڵ�ʱ��', '1', 'yes'
), (
NULL , '0', 'rss_language', 'Y', '1', 'zh-CHS', '20', '8', 'Rssfeed����:en|zh-CHS', '1', 'yes'
), (
NULL , '0', 'rss_post_number', 'Y', '1', '10', '20', '8', 'Rss������', '1', 'yes'
), (
NULL , '0', 'footer_text', 'Y', '1', '', '20', '8', '��Ȩ��Ϣ', '1', 'yes'
);"));
	$gcdb->query(trans_sql_utf8($charset,"INSERT INTO `".$prefix."users` ( `ID` , `user_login` , `user_pass` , `user_firstname` , `user_lastname` , `user_nickname` , `user_nicename` , `user_icq` , `user_email` , `user_url` , `user_ip` , `user_domain` , `user_browser` , `user_registered` , `user_level` , `user_aim` , `user_msn` , `user_yim` , `user_idmode` , `user_activation_key` , `user_status` , `user_description` ) 
VALUES (
NULL , '$user_name', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', '0', '', '', '', '', '', '0000-00-00 00:00:00', '0', '', '', '', '', '', '0', ''
);"));
	$gcdb->query(trans_sql_utf8($charset,"INSERT INTO `".$prefix."posts` ( `ID` , `post_author` , `post_date` , `post_date_gmt` , `post_content` , `post_title` , `post_tag` , `post_excerpt` , `post_status` , `comment_status` , `ping_status` , `post_password` , `post_name` , `to_ping` , `pinged` , `post_modified` , `post_modified_gmt` , `post_content_filtered` , `post_parent` , `guid` , `menu_order` , `show_in_home` ) 
VALUES (
1 , '0', '2007-04-08 12:14:16', '2007-04-08 04:14:16', 'This is the first test page.', 'Hello world!', '0', '', 'publish', 'open', 'open', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '', '0', 'yes'
);"));
	$gcdb->query(trans_sql_utf8($charset,"INSERT INTO `".$prefix."id` ( `ID` ) 
VALUES (
2
);"));

}
endif;

function gc_check_mysql_version() {
	global $gc_version;

	// Make sure the server has MySQL 4.0
	$mysql_version = preg_replace('|[^0-9\.]|', '', @mysql_get_server_info());
	if ( version_compare($mysql_version, '4.0.0', '<') )
		die(sprintf(__('<strong>ERROR</strong>: WordPress %s requires MySQL 4.0.0 or higher'), $gc_version));
}

?>
