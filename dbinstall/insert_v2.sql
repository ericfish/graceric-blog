INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'prev_links', 'Y', '1', '1', '20', '8', '链接到以前的blog的链接个数', '1', 'yes'
), (
NULL , '0', 'blog_subtitle', 'Y', '1', '', '20', '8', '网站子标题', '1', 'yes'
), (
NULL , '0', 'template', 'Y', '1', 'default', '20', '8', 'Blog的模板', '1', 'yes'
), (
NULL , '0', 'admin_post_number', 'Y', '1', '10', '20', '8', '管理界面每页显示的Blog数', '1', 'yes'
), (
NULL , '0', 'about_title', 'Y', '1', 'About Me', '20', '8', '个人简介标题(可选)', '1', 'yes'
);

INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_link', 'Y', '1', '', '20', '8', 'RSS链接地址', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'charset', 'Y', '1', 'gb2312', '20', '8', '字符集:utf-8|gb2312', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'keywords', 'Y', '1', 'blog website', '20', '8', '网站关键字(空格分隔)', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'blog_author', 'Y', '1', 'anonymous', '20', '8', '作者姓名', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'comment_email', 'Y', '1', 'no', '20', '8', '是否发送新留言邮件:yes|no', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'change_password_msg', 'Y', '1', '您未更改过admin的初始密码，这将给您的网站带来安全隐患。请给admin用户设定新的密码。', '20', '8', '初次登录的更改密码提示', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'gmt_offset', 'Y', '1', '8', '20', '8', '你所在的时区', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_language', 'Y', '1', 'zh-CHS', '20', '8', 'Rssfeed语言:en|zh-CHS', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_post_number', 'Y', '1', '10', '20', '8', 'Rss文章数', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'footer_text', 'Y', '1', '', '20', '8', '版权信息', '1', 'yes'
);

UPDATE `gcdb_users` SET `user_pass` = '21232f297a57a5a743894a0e4a801fc3' WHERE `ID` =1 LIMIT 1 ;

UPDATE `gcdb_options` SET `option_description` = '管理员邮件地址' WHERE `option_id` =1 AND `blog_id` =0 AND `option_name`= 'admin_email' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '首页地址(最后没有斜杠)' WHERE `option_id` =2 AND `blog_id` =0 AND `option_name`= 'base_url' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '首页显示的blog数' WHERE `option_id` =3 AND `blog_id` =0 AND `option_name` = 'home_post_number' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '网站标题' WHERE `option_id` =4 AND `blog_id` =0 AND `option_name`= 'blog_title' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '自我介绍内容',`option_value` = '<p> Write your self introduction in the <a href="admin/">Admin</a> -&gt; <a href="admin/editabout.php">Edit About</a> Page.</p><p> 	请到<a href="admin/">管理页面</a> -&gt; <a href="admin/editabout.php">编辑个人简介</a> 中编辑你的个人简介。</p><p>有问题请联系ericfish[at]gmail.com</p>' WHERE `option_id` =5 AND `blog_id` =0 AND `option_name` = 'about_text' LIMIT 1 ;