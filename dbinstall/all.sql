CREATE TABLE gcdb_tags (
  tag_ID bigint(20) NOT NULL auto_increment,
  tag_name varchar(55) NOT NULL default '',
  tag_nicename varchar(200) NOT NULL default '',
  tag_description longtext NOT NULL,
  tag_parent int(4) NOT NULL default '0',
  PRIMARY KEY  (tag_ID),
  KEY tag_nicename (tag_nicename)
);

CREATE TABLE gcdb_comments (
  comment_ID bigint(20) unsigned NOT NULL auto_increment,
  comment_post_ID bigint(20) NOT NULL default '0',
  comment_author tinytext NOT NULL,
  comment_author_email varchar(100) NOT NULL default '',
  comment_author_url varchar(200) NOT NULL default '',
  comment_author_IP varchar(100) NOT NULL default '',
  comment_date datetime NOT NULL default '0000-00-00 00:00:00',
  comment_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  comment_content text NOT NULL,
  comment_karma int(11) NOT NULL default '0',
  comment_approved enum('0','1','spam') NOT NULL default '1',
  comment_agent varchar(255) NOT NULL default '',
  comment_type varchar(20) NOT NULL default '',
  comment_parent int(11) NOT NULL default '0',
  request_email enum('no','yes') NOT NULL default 'no',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (comment_ID),
  KEY comment_approved (comment_approved),
  KEY comment_post_ID (comment_post_ID)
);

CREATE TABLE gcdb_links (
  link_id bigint(20) NOT NULL auto_increment,
  link_url varchar(255) NOT NULL default '',
  link_name varchar(255) NOT NULL default '',
  link_image varchar(255) NOT NULL default '',
  link_target varchar(25) NOT NULL default '',
  link_tag int(11) NOT NULL default '0',
  link_description varchar(255) NOT NULL default '',
  link_visible enum('Y','N') NOT NULL default 'Y',
  link_owner int(11) NOT NULL default '1',
  link_rating int(11) NOT NULL default '0',
  link_updated datetime NOT NULL default '0000-00-00 00:00:00',
  link_rel varchar(255) NOT NULL default '',
  link_notes mediumtext NOT NULL,
  link_rss varchar(255) NOT NULL default '',
  PRIMARY KEY  (link_id),
  KEY link_tag (link_tag),
  KEY link_visible (link_visible)
);

CREATE TABLE gcdb_options (
  option_id bigint(20) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  option_name varchar(64) NOT NULL default '',
  option_can_override enum('Y','N') NOT NULL default 'Y',
  option_type int(11) NOT NULL default '1',
  option_value longtext NOT NULL,
  option_width int(11) NOT NULL default '20',
  option_height int(11) NOT NULL default '8',
  option_description tinytext NOT NULL,
  option_admin_level int(11) NOT NULL default '1',
  autoload enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (option_id,blog_id,option_name),
  KEY option_name (option_name)
);

CREATE TABLE gcdb_post2tag (
  rel_id bigint(20) NOT NULL auto_increment,
  post_id bigint(20) NOT NULL default '0',
  tag_id bigint(20) NOT NULL default '0',
  PRIMARY KEY  (rel_id),
  KEY post_id (post_id,tag_id)
);

CREATE TABLE gcdb_posts (
  ID bigint(20) unsigned NOT NULL,
  post_author int(4) NOT NULL default '0',
  post_date datetime NOT NULL default '0000-00-00 00:00:00',
  post_date_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content longtext NOT NULL,
  post_title text NOT NULL,
  post_tag int(4) NOT NULL default '0',
  post_excerpt text NOT NULL,
  post_status enum('publish','draft','private','static','object') NOT NULL default 'publish',
  comment_status enum('open','closed','registered_only') NOT NULL default 'open',
  ping_status enum('open','closed') NOT NULL default 'open',
  post_password varchar(20) NOT NULL default '',
  post_name varchar(200) NOT NULL default '',
  to_ping text NOT NULL,
  pinged text NOT NULL,
  post_modified datetime NOT NULL default '0000-00-00 00:00:00',
  post_modified_gmt datetime NOT NULL default '0000-00-00 00:00:00',
  post_content_filtered text NOT NULL,
  post_parent int(11) NOT NULL default '0',
  guid varchar(255) NOT NULL default '',
  menu_order int(11) NOT NULL default '0',
  show_in_home enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (ID),
  KEY post_name (post_name)
);

CREATE TABLE gcdb_users (
  ID bigint(20) unsigned NOT NULL auto_increment,
  user_login varchar(60) NOT NULL default '',
  user_pass varchar(64) NOT NULL default '',
  user_firstname varchar(50) NOT NULL default '',
  user_lastname varchar(50) NOT NULL default '',
  user_nickname varchar(50) NOT NULL default '',
  user_nicename varchar(50) NOT NULL default '',
  user_icq int(10) unsigned NOT NULL default '0',
  user_email varchar(100) NOT NULL default '',
  user_url varchar(100) NOT NULL default '',
  user_ip varchar(15) NOT NULL default '',
  user_domain varchar(200) NOT NULL default '',
  user_browser varchar(200) NOT NULL default '',
  user_registered datetime NOT NULL default '0000-00-00 00:00:00',
  user_level int(2) unsigned NOT NULL default '0',
  user_aim varchar(50) NOT NULL default '',
  user_msn varchar(100) NOT NULL default '',
  user_yim varchar(50) NOT NULL default '',
  user_idmode varchar(20) NOT NULL default '',
  user_activation_key varchar(60) NOT NULL default '',
  user_status int(11) NOT NULL default '0',
  user_description longtext NOT NULL default '',
  PRIMARY KEY  (ID),
  UNIQUE KEY user_login (user_login)
);

CREATE TABLE gcdb_id (
  ID bigint(20) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (ID)
);

CREATE TABLE gcdb_spams (
  spam_ID bigint(20) NOT NULL auto_increment,
  spam_value varchar(200) NOT NULL default '',
  spam_type enum('name','email','text','ip') NOT NULL default 'text',  
  PRIMARY KEY  (spam_ID)
);

CREATE TABLE gcdb_x (
  x_ID bigint(20) NOT NULL auto_increment,
  post_ID bigint(20) NOT NULL,
  x_name varchar(200) NOT NULL,  
  PRIMARY KEY  (x_ID),
  UNIQUE KEY x_name (x_name)
);

INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'admin_email', 'Y', '1', 'ericfish@gmail.com', '20', '8', '管理员邮件地址', '1', 'yes'
), (
NULL , '0', 'base_url', 'Y', '1', 'http://www.playwithvista.com/gc', '20', '8', '首页地址(最后没有斜杠)', '1', 'yes'
), (
NULL , '0', 'home_post_number', 'Y', '1', '1', '20', '8', '首页显示的blog数', '1', 'yes'
), (
NULL , '0', 'blog_title', 'Y', '1', 'Graceric Blog', '20', '8', '网站标题', '1', 'yes'
), (
NULL , '0', 'about_text', 'Y', '1', '<p> Write your self introduction in the <a href=admin>Admin</a> -&gt; <a href=admin/editabout.php>Edit About</a> Page.</p><p> 	请到<a href=admin>管理页面</a> -&gt; <a href=admin/editabout.php>编辑个人简介</a> 中编辑你的个人简介。</p><p>有问题请联系ericfish[at]gmail.com</p>', '20', '8', '自我介绍内容', '1', 'yes'
), (
NULL , '0', 'prev_links', 'Y', '1', '1', '20', '8', '链接到以前的blog的链接个数', '1', 'yes'
), (
NULL , '0', 'blog_subtitle', 'Y', '1', '', '20', '8', '网站子标题', '1', 'yes'
), (
NULL , '0', 'template', 'Y', '1', 'default', '20', '8', 'Blog的模板', '1', 'yes'
), (
NULL , '0', 'admin_post_number', 'Y', '1', '10', '20', '8', '管理界面每页显示的Blog数', '1', 'yes'
), (
NULL , '0', 'about_title', 'Y', '1', 'About Me', '20', '8', '个人简介标题(可选)', '1', 'yes'
), (
NULL , '0', 'rss_link', 'Y', '1', '', '20', '8', 'RSS链接地址', '1', 'yes'
), (
NULL , '0', 'charset', 'Y', '1', 'utf-8', '20', '8', '字符集', '1', 'yes'
), (
NULL , '0', 'keywords', 'Y', '1', 'blog website', '20', '8', '网站关键字(空格分隔)', '1', 'yes'
), (
NULL , '0', 'blog_author', 'Y', '1', 'anonymous', '20', '8', '作者姓名', '1', 'yes'
), (
NULL , '0', 'comment_email', 'Y', '1', 'no', '20', '8', '是否发送新留言邮件,yes:发送', '1', 'yes'
), (
NULL , '0', 'change_password_msg', 'Y', '1', '您未更改过admin的初始密码，这将给您的网站带来安全隐患。请给admin用户设定新的密码。', '20', '8', '初次登录的更改密码提示', '1', 'yes'
), (
NULL , '0', 'gmt_offset', 'Y', '1', '8', '20', '8', '你所在的时区', '1', 'yes'
), (
NULL , '0', 'rss_language', 'Y', '1', 'zh-CHS', '20', '8', 'Rssfeed语言:en,zh-CHS', '1', 'yes'
), (
NULL , '0', 'rss_post_number', 'Y', '1', '10', '20', '8', 'Rss文章数', '1', 'yes'
), (
NULL , '0', 'footer_text', 'Y', '1', '', '20', '8', '版权信息', '1', 'yes'
);

INSERT INTO `gcdb_users` ( `ID` , `user_login` , `user_pass` , `user_firstname` , `user_lastname` , `user_nickname` , `user_nicename` , `user_icq` , `user_email` , `user_url` , `user_ip` , `user_domain` , `user_browser` , `user_registered` , `user_level` , `user_aim` , `user_msn` , `user_yim` , `user_idmode` , `user_activation_key` , `user_status` , `user_description` ) 
VALUES (
NULL , 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', '0', '', '', '', '', '', '0000-00-00 00:00:00', '0', '', '', '', '', '', '0', ''
);

INSERT INTO `gcdb_posts` ( `ID` , `post_author` , `post_date` , `post_date_gmt` , `post_content` , `post_title` , `post_tag` , `post_excerpt` , `post_status` , `comment_status` , `ping_status` , `post_password` , `post_name` , `to_ping` , `pinged` , `post_modified` , `post_modified_gmt` , `post_content_filtered` , `post_parent` , `guid` , `menu_order` , `show_in_home` ) 
VALUES (
1 , '0', '2006-02-23 12:12:00', '0000-00-00 00:00:00', 'This is the first test page.', 'Hello world!', '0', '', 'publish', 'open', 'open', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '', '0', 'yes'
);

INSERT INTO `gcdb_id` ( `ID` ) 
VALUES (
2
);