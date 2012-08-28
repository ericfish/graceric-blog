INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'admin_email', 'Y', '1', 'a@b.com', '20', '8', '', '1', 'yes'
), (
NULL , '0', 'base_url', 'Y', '1', 'http://localhost/gc', '20', '8', '', '1', 'yes'
), (
NULL , '0', 'home_post_number', 'Y', '1', '1', '20', '8', '', '1', 'yes'
), (
NULL , '0', 'blog_title', 'Y', '1', 'abc', '20', '8', '', '1', 'yes'
), (
NULL , '0', 'about_text', 'Y', '1', 'abc', '20', '8', '', '1', 'yes'
);

INSERT INTO `gcdb_users` ( `ID` , `user_login` , `user_pass` , `user_firstname` , `user_lastname` , `user_nickname` , `user_nicename` , `user_icq` , `user_email` , `user_url` , `user_ip` , `user_domain` , `user_browser` , `user_registered` , `user_level` , `user_aim` , `user_msn` , `user_yim` , `user_idmode` , `user_activation_key` , `user_status` , `user_description` ) 
VALUES (
NULL , 'admin', 'admin', '', '', '', '', '0', '', '', '', '', '', '0000-00-00 00:00:00', '0', '', '', '', '', '', '0', ''
);

INSERT INTO `gcdb_posts` ( `ID` , `post_author` , `post_date` , `post_date_gmt` , `post_content` , `post_title` , `post_tag` , `post_excerpt` , `post_status` , `comment_status` , `ping_status` , `post_password` , `post_name` , `to_ping` , `pinged` , `post_modified` , `post_modified_gmt` , `post_content_filtered` , `post_parent` , `guid` , `menu_order` , `show_in_home` ) 
VALUES (
1 , '0', '2007-04-06 17:10:00', '0000-00-00 00:00:00', 'This is the first test page.', 'Hello world!', '0', '', 'publish', 'open', 'open', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0', '', '0', 'yes'
);

INSERT INTO `gcdb_id` ( `ID` ) 
VALUES (
2
);