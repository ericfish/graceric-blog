<?php
// ** MySQL settings ** //
define('DB_NAME', 'graceric');    // The name of the database
define('DB_USER', 'username');     // Your MySQL username
define('DB_PASSWORD', 'password'); // ...and password
define('DB_HOST', 'localhost');    // 99% chance you won't need to change this value

// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  ='gcdb_';   // Only numbers, letters, and underscores please!
$table_charset  ='utf8';   // example: 'utf8' or 'gb2312' or 'gbk'

/* That's all, stop editing! Happy blogging. */

define('ABSPATH', dirname(__FILE__).'/');

define('WPINC', 'gc-includes');
require_once (ABSPATH . WPINC . '/gcdb.class.php');

// Table names
$gcdb->posts            = $table_prefix . 'posts';
$gcdb->users            = $table_prefix . 'users';
$gcdb->tags       		= $table_prefix . 'tags';
$gcdb->post2tag         = $table_prefix . 'post2tag';
$gcdb->comments         = $table_prefix . 'comments';
$gcdb->links            = $table_prefix . 'links';
$gcdb->options          = $table_prefix . 'options';
$gcdb->id          		= $table_prefix . 'id';
$gcdb->spams          	= $table_prefix . 'spams';
$gcdb->x          	    = $table_prefix . 'x';

$gcdb->query("SET NAMES '$table_charset'");
?>