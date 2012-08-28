<?php
define('WP_INSTALLING', true);
if (!file_exists('../gc-config.php')) {
  require_once('../gc-includes/functions.php');
  gc_die("There doesn't seem to be a <code>gc-config.php</code> file. I need this before we can get started. Need more help? <a href='http://www.ericfish.com/graceric'>We got it</a>. You can <a href='setup-config.php'>create a <code>gc-config.php</code> file through a web interface</a>, but this doesn't work for all server setups. The safest way is to manually create the file.", "Graceric &rsaquo; Error");
}

require_once('../gc-config.php');
require_once('../gc-includes/functions.php');
require_once('../gc-includes/install-functions.php');
//require_once('./upgrade-functions.php');

if (isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;
	
if (isset($_GET['prefix']))
	$prefix = $_GET['prefix'];
else
	$prefix = 'gcdb_';
	
if (isset($_GET['charset'])){
	$charset = $_GET['charset'];
	if($charset=='utf8'){
	   $charset = 'utf-8';
	}
}
else{
	$charset = 'utf-8';
}
	
header( 'Content-Type: text/html; charset=gb2312' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<title>Graceric &rsaquo; Installation</title>
	<link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
<h1 id="logo">Graceric Blog</h1>
<?php
// Let's check to make sure WP isn't already installed.
if ( is_blog_installed() ) die('<h1>Already Installed</h1><p>You appear to have already installed Graceric. To reinstall please clear your old database tables first.</p></body></html>');

switch($step) {
	case 0:
?>
<p>Welcome to Graceric installation. We&#8217;re now going to go through a few steps to get you up and running with the latest in personal publishing platforms. You may want to peruse the <a href="readme.html">ReadMe documentation</a> at your leisure.</p>
<h2 class="step"><a href="install.php?step=1&prefix=<?php echo($prefix);?>&charset=<?php echo($charset);?>">First Step &raquo;</a></h2>
<?php
		break;
	case 1:
?>
<h1>First Step</h1>
<p>Before we begin we need a little bit of information. Don't worry, you can always change these later.</p>

<form id="setup" method="post" action="install.php?step=2&prefix=<?php echo($prefix);?>&charset=<?php echo($charset);?>">
	<table width="100%">
		<tr>
			<th width="33%">Weblog title:</th>
			<td><input name="weblog_title" type="text" id="weblog_title" size="25" /></td>
		</tr>
		<tr>
			<th>Your e-mail:</th>
			<td><input name="admin_email" type="text" id="admin_email" size="25" /></td>
		</tr>
	</table>
	<p><em>Double-check that email address before continuing.</em></p>
	<h2 class="step"><input type="submit" name="Submit" value="Continue to Second Step &raquo;" /></h2>
</form>

<?php
		break;
	case 2:
		// Fill in the data we gathered
		$weblog_title = stripslashes($_POST['weblog_title']);
		$admin_email = stripslashes($_POST['admin_email']);
		// check e-mail address
		if (empty($admin_email)) {
			die("<strong>ERROR</strong>: please type your e-mail address");
		} else if (empty($admin_email)) {
			die("<strong>ERROR</strong>: the e-mail address isn't correct");
		} else if (empty($prefix)) {
			die("<strong>ERROR</strong>: the prefix isn't exist");
		} else if (empty($charset)) {
			die("<strong>ERROR</strong>: the charset isn't exist");
		}

?>
<h1>Second Step</h1>
<p>Now we&#8217;re going to create the database tables and fill them with some default data.</p>


<?php
	$result = gc_install($weblog_title, 'admin', $admin_email,$prefix,$charset);
	extract($result);
?>

<p><em>Finished!</em></p>

<p><?php printf('Now you can <a href="%1$s">log in</a> with the <strong>username</strong> "<code>admin</code>" and <strong>password</strong> "<code>%2$s</code>"', './login.php', $password); ?></p>


<dl>
	<dt>Username</dt>
		<dd><code>admin</code></dd>
	<dt>Password</dt>
		<dd><code><?php echo $password; ?></code></dd>
	<dt>Login address</dt>
		<dd><a href="./login.php">login.php</a></dd>
</dl>
<p>Were you expecting more steps? Sorry to disappoint. All done! :)</p>

<?php
		break;
}
?>

<p id="footer"><a href="http://www.ericfish.com/graceric">Graceric</a>, will be the simplest blog ever.</p>
</body>
</html>