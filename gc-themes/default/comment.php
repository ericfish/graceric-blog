<?php
/* Graceric
*  Author: ericfish
*  File: /gc-themes/default/comment.php
*  Usage: Default Comment Template
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: comment.php 58 2008-02-12 12:32:02Z ericfish@gmail.com $
*  $LastChangedDate: 2012-02-05 20:32:02 +0800 (星期二, 12 二月 2008) $
*  $LastChangedRevision: 58 $
*  $LastChangedBy: ericfish@gmail.com $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-themes/default/comment.php $
*/
?>

<span class="lastpost">[<a class="blogkommlink" href="<?php echo(get_permalink()); ?>">Hide comment</a>]</span>
<form method="post" name="comm" action="<?php echo(comment_permalink()); ?>#comment">

	<a name="comment"></a>
	<?php show_comments(); ?>
	
	<?php if(allow_comment()): ?>
	<a name="addcomment"></a>

		<div class="blogkbox">
			<div class="blogkrow">
			<strong>Add comment here</strong><br/>
  			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_name" value="<? get_cookie_name(); ?>" size="32" maxlength="30"/> (name)</span>
		</div>
		<div class="blogkrow">
 			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_e_mail" value="<?get_cookie_mail(); ?>" size="32" maxlength="30"/> (email: not visible to others)</span>
		</div>
    	<div class="blogkrow">
  			<span class="blogkRightClmn"><input class="formfield2" type="text" name="comm_website"  size="32" value="<? get_cookie_www();?>" /> (url)</span>
		</div>
		<div class="blogkrow">
  			<span class="blogkRightClmn">
  				<textarea class="textarea" name="comm_content" rows="12" cols="40" tabindex="4" ></textarea>

<br/><br/>
<img src="./gc-includes/comment-code.php" border="0"> 
<input class="formfield2" type="text" name="comm_code"  size="6" value="" /> (validation code)

				<br/><br/>
        		<input style="font-size:9px; font-weight: bold;" type="submit" name="button" value="I'm finish, post it!"/><br/>

			</span>
		</div>
		<div class="blogkrow">
 			<input type="checkbox" name="rememberMe" value="1" checked="checked"/>remember me<br/>
		</div>
 	</div>
 	<?php endif; ?>
</form>

