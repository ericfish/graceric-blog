<?php
// postback and save
require_once('../gc-config.php');
require_once('../gc-settings.php');

auth_redirect();

$postArray = &$_POST;
$post_id = $postArray['post_id'];
$post_title = trim($postArray['post_title']);
$post_tags = trim($postArray['post_tags']);
$post_content = $postArray['EditorAccessibility'];
$show_in_home = $postArray['show_in_home'];
$allow_comment = $postArray['allow_comment'];
$ping_status = $postArray['home_hide'];

if(isset($postArray['D1']))
{
    $post_status = 'draft';
    
    savePost($post_id,$post_title,$post_content,$post_tags,$show_in_home,$post_status,$allow_comment,$ping_status);
    
    // create atom.xml
    // createAtom();
    
    header("location:edit.php?q=$post_id");
}
else
{
    $post_status = 'publish';
    
    savePost($post_id,$post_title,$post_content,$post_tags,$show_in_home,$post_status,$allow_comment,$ping_status);
    
    // create atom.xml
    // createAtom();
    
    header("location:index.php");

}

?>