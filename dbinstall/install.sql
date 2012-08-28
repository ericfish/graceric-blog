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