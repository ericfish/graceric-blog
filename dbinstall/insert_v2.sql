INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'prev_links', 'Y', '1', '1', '20', '8', '���ӵ���ǰ��blog�����Ӹ���', '1', 'yes'
), (
NULL , '0', 'blog_subtitle', 'Y', '1', '', '20', '8', '��վ�ӱ���', '1', 'yes'
), (
NULL , '0', 'template', 'Y', '1', 'default', '20', '8', 'Blog��ģ��', '1', 'yes'
), (
NULL , '0', 'admin_post_number', 'Y', '1', '10', '20', '8', '�������ÿҳ��ʾ��Blog��', '1', 'yes'
), (
NULL , '0', 'about_title', 'Y', '1', 'About Me', '20', '8', '���˼�����(��ѡ)', '1', 'yes'
);

INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_link', 'Y', '1', '', '20', '8', 'RSS���ӵ�ַ', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'charset', 'Y', '1', 'gb2312', '20', '8', '�ַ���:utf-8|gb2312', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'keywords', 'Y', '1', 'blog website', '20', '8', '��վ�ؼ���(�ո�ָ�)', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'blog_author', 'Y', '1', 'anonymous', '20', '8', '��������', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'comment_email', 'Y', '1', 'no', '20', '8', '�Ƿ����������ʼ�:yes|no', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'change_password_msg', 'Y', '1', '��δ���Ĺ�admin�ĳ�ʼ���룬�⽫��������վ������ȫ���������admin�û��趨�µ����롣', '20', '8', '���ε�¼�ĸ���������ʾ', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'gmt_offset', 'Y', '1', '8', '20', '8', '�����ڵ�ʱ��', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_language', 'Y', '1', 'zh-CHS', '20', '8', 'Rssfeed����:en|zh-CHS', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'rss_post_number', 'Y', '1', '10', '20', '8', 'Rss������', '1', 'yes'
);
INSERT INTO `gcdb_options` ( `option_id` , `blog_id` , `option_name` , `option_can_override` , `option_type` , `option_value` , `option_width` , `option_height` , `option_description` , `option_admin_level` , `autoload` ) 
VALUES (
NULL , '0', 'footer_text', 'Y', '1', '', '20', '8', '��Ȩ��Ϣ', '1', 'yes'
);

UPDATE `gcdb_users` SET `user_pass` = '21232f297a57a5a743894a0e4a801fc3' WHERE `ID` =1 LIMIT 1 ;

UPDATE `gcdb_options` SET `option_description` = '����Ա�ʼ���ַ' WHERE `option_id` =1 AND `blog_id` =0 AND `option_name`= 'admin_email' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '��ҳ��ַ(���û��б��)' WHERE `option_id` =2 AND `blog_id` =0 AND `option_name`= 'base_url' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '��ҳ��ʾ��blog��' WHERE `option_id` =3 AND `blog_id` =0 AND `option_name` = 'home_post_number' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '��վ����' WHERE `option_id` =4 AND `blog_id` =0 AND `option_name`= 'blog_title' LIMIT 1 ;
UPDATE `gcdb_options` SET `option_description` = '���ҽ�������',`option_value` = '<p> Write your self introduction in the <a href="admin/">Admin</a> -&gt; <a href="admin/editabout.php">Edit About</a> Page.</p><p> 	�뵽<a href="admin/">����ҳ��</a> -&gt; <a href="admin/editabout.php">�༭���˼��</a> �б༭��ĸ��˼�顣</p><p>����������ϵericfish[at]gmail.com</p>' WHERE `option_id` =5 AND `blog_id` =0 AND `option_name` = 'about_text' LIMIT 1 ;