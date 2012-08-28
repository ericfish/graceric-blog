<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/comment-functions.php
*  Usage: Comment Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: comment-functions.php 41 2007-04-10 09:15:18Z ericfish $
*  $LastChangedDate: 2007-04-10 17:15:18 +0800 (Tue, 10 Apr 2007) $
*  $LastChangedRevision: 41 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/gc-includes/comment-functions.php $
*/

// Show the comment link on page
// Click to show comments part
// Called by the_comment()
function show_comment_link() {
	global $db_query,$gcdb;
	
	$current_postID = the_ID(false);
	$comments_number = $db_query->get_comments_number();
	
	//$request = "SELECT tag_id FROM $gcdb->tags WHERE tag_name = 'tech'";
	//$tag_id = $gcdb->get_var($request);
	
	$text = "<a href='".comments_permalink($current_postID)."'>Comment ($comments_number)</a>";
	return $text;
}

// Show comments detail on page, call by the_comment
function show_comments() {
	global $db_query;
	$comments = $db_query->get_comment();
	$comment_ID="";
	$comment_author = "";
	$comment_author_email = "";
	$comment_author_url = "";
	$comment_date = "";
	
	for($i=0; $i<count($comments); $i++)
	{
	    $comment_ID = $comments[$i]->comment_ID;
		$comment_author = $comments[$i]->comment_author;
		$comment_date = mysql2date('d.m.Y, g:iA', $comments[$i]->comment_date);
		$comment_author_email = $comments[$i]->comment_author_email;
		$comment_author_url = $comments[$i]->comment_author_url;
		if ($comment_author_email != "")
			$comment_author_email = "[<a href='mailto:$comment_author_email'>@</a>]";
		if ($comment_author_url != "http://")
			$comment_author_url = "[<a href='$comment_author_url'>H</a>]";
		else
			$comment_author_url = "";
		echo("<div class='blogkcomments'>");
		echo("<div class='blogkrow'>");
		echo("<a id='".bin2hex($comment_date)."' name='".bin2hex($comment_date)."'></a>");
		echo($comments[$i]->comment_content);
		echo("</div><div class='blogkrow'>");
		echo("<strong>$comment_author</strong>");
		// only admin can see comment's email
		if(user_is_auth())
		{
		  echo($comment_author_email);
		}
		echo("$comment_author_url, $comment_date");
		echo(" <a href='".comment_permalink()."#".bin2hex($comment_date)."'>link</a>");
		if(user_is_auth())
		{
		    echo(" <span onclick=\"javascript:xajax_saveSpamComment($comment_ID);\"><a href=\"javascript://\">spam?</a></span>");
		}
		echo("</div></div>");
	}
}

// call by ajax, mark spam comment
function saveSpamComment($comment_ID){
    
	global $gcdb;
	$objResponse = new xajaxResponse();

	$option_value = trim($comment_ID);
	//$option_value = apply_filters($option_value);

	$request1 = "UPDATE $gcdb->comments SET comment_approved='spam' WHERE comment_ID=$comment_ID";
	$gcdb->query($request1);

	$objResponse->addAlert("Spam comment marked!");

	return $objResponse;
}

/**** check comment validations ****/
function is_comment_valid($comm_name,$comm_e_mail,$comm_website,$comm_content,$comm_code){
    global  $gcdb,$error_message;
	$is_comment_valid = true;
		
	//require name
	if ($comm_name == '') {
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Please enter your name.</b></div>";
	}
		
	//require content
	if ($comm_content == '') {
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Please enter some content.</b></div>";
	}
		
	//comment code is correct, ericyu
	session_start();
	if ($comm_code <> $_SESSION["authnum"]){
		$is_comment_valid = false;
		$error_message = "<div class='blogkrow' style='color: red'><b>Please enter correct validate code.</b></div>";
	}
	
	//check name allowed
	$request1 = "SELECT spam_value FROM $gcdb->spams WHERE spam_type = 'name'";
	$name_blacklist = $gcdb->get_col($request1);
	for($i=0;$i<count($name_blacklist);$i++)
	{
    	if (is_garbage($comm_name,$name_blacklist[$i]))
    	{
    		$is_comment_valid = false;
    		$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this name is not allow to post comment.</b></div>";
    		break;
    	}
	}
	
	//check email allowed
	$request2 = "SELECT spam_value FROM $gcdb->spams WHERE spam_type = 'email'";
	$email_blacklist = $gcdb->get_col($request2);
	for($i=0;$i<count($email_blacklist);$i++)
	{
    	if (is_garbage($comm_e_mail,$email_blacklist[$i]))
    	{
    		$is_comment_valid = false;
    		$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, this email is not allow to post comment.</b></div>";
    		break;
    	}
	}
	
	//check user ip allowed
	$request3 = "SELECT spam_value FROM $gcdb->spams WHERE spam_type = 'ip'";
	$ip_blacklist = $gcdb->get_col($request3);
	for($i=0;$i<count($ip_blacklist);$i++)
	{
    	if (is_garbage(get_visitor_ip(),$ip_blacklist[$i]))
    	{
    		$is_comment_valid = false;
    		$error_message = "<div class='blogkrow' style='color: red'><b>Sorry, your IP address is not allow to post comment.</b></div>";
    		break;
    	}
	}
	
	//check comment content for garbage information
	$request4 = "SELECT spam_value FROM $gcdb->spams WHERE spam_type = 'text'";
	$content_blacklist = $gcdb->get_col($request4);
	for($i=0;$i<count($content_blacklist);$i++)
	{
    	if (is_garbage($comm_content,$content_blacklist[$i]))
    	{
    		$is_comment_valid = false;
    		$error_message = "<div class='blogkrow' style='color: red'><b>对不起您输入的内容中包含非法内容，比如'http://'等，为了避免垃圾信息，请去掉这些字符后重新提交。谢谢。</b></div>";
    		break;
    	}
	}
	
	return $is_comment_valid;
}

// find garbage information in comment content
// if found garbage in input return true
// else return false
function is_garbage($content,$garbage) {
	$pos_found = strpos($content,$garbage);
	if ($pos_found !== false)
		return true;
	else
		return false;
		
}

// filter comment content input
function filter_comment($comment_content) {
	
    $comment_content= htmlspecialchars($comment_content, ENT_QUOTES);

    $comment_content= str_replace ("\n"," ", $comment_content);
    $comment_content= str_replace ("\r","<br/>", $comment_content);
    
    $comment_content= proofAddSlashes($comment_content);
    
    return $comment_content;
}

// get user info in cookies
// if no cookies, set the default values
function get_cookie_name(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_name"]))
		echo $HTTP_COOKIE_VARS["blogKo_name"];
	else
		echo "";
}

function get_cookie_mail(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_mail"]))
		echo $HTTP_COOKIE_VARS["blogKo_mail"];
	else
		echo "";
}

function get_cookie_www(){
	global $HTTP_COOKIE_VARS;
	if(isset($HTTP_COOKIE_VARS["blogKo_www"]))
		echo $HTTP_COOKIE_VARS["blogKo_www"];
	else
		echo "http://";
}

// check if this post allow comment
// if allow return true
// if not return false
function allow_comment(){
    global $post;
	$comment_status = $post->comment_status;
	if ($comment_status == 'open')
    	return true;
	else
		return false;
}

// get the commentor's ip address
function get_visitor_ip() {
	
    //For 512j apache server
    /*
	global $HTTP_X_FORWARDED_FOR;
	
	if($HTTP_X_FORWARDED_FOR!="")
		$REMOTE_ADDR=$HTTP_X_FORWARDED_FOR;

	if(isset($REMOTE_ADDR))
	{
		$tmp_ip=explode(",",$REMOTE_ADDR);

		$REMOTE_ADDR=$tmp_ip[0];
	}
	else
		$REMOTE_ADDR="";
	
	return $REMOTE_ADDR;*/
	
	//For other servers, you can just use the following sentence to replace all above
	return $_SERVER['REMOTE_ADDR'];
}

?>