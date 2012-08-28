运行环境：

PHP 4 & MySQL 4

=============

安装：

打开index.php自动进入安装页面。

=============

手动初始化：

如果自动安装失败 或者 您需要从Graceric Blog Release version 1.00 升级，请执行如下步骤：

-------------------------

a) 新安装：

1. 在mysql中执行 dbinstall/all.sql 脚本 (如有需要请更新脚本中数据库前缀等数据)。

2. 打开gc-config-sample.php文件，更新数据库信息、用户、密码、数据库前缀名称、字符集，并将该文件另存为gc-config.php。

-------------------------

b) 升级

1. 在mysql中执行 dbinstall/upgrade-v-100.sql 脚本 (如有需要请更新脚本中数据库前缀等数据)。

=============

管理：

浏览http://localhost/admin/，默认用户/密码: admin/admin。
安全起见，请在第一次登录后更改admin的默认密码。

=============

有问题联系: ericfish@gmail.com 
或访问: http://www.ericfish.com/graceric/