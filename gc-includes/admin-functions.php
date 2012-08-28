<?php
/* Graceric
*  Author: ericfish
*  File: /gc-includes/admin-functions.php
*  Usage: Admin Pages Functions
*  Format: 1 tab indent(4 spaces), LF, GB2312, no-BOM
*
*  Subversion Keywords:
*
*  $Id: admin-functions.php 38 2007-04-10 05:28:52Z ericfish $
*  $LastChangedDate: 2007-04-10 13:28:52 +0800 (æ˜ŸæœŸäºŒ, 10 å››æœˆ 2007) $
*  $LastChangedRevision: 38 $
*  $LastChangedBy: ericfish $
*  $URL: https://graceric.googlecode.com/svn/trunk/Blank/gc-includes/admin-functions.php $
*/

/***** index.php *****/

// load posts grid when the index page is first load
function initPage() {
	global $gcdb,$begin_id,$end_id;
	
	$begin_id = 0;
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY ID DESC LIMIT $begin_id, $end_id";
	
	$base_url = get_option('base_url');
	
	global $gcdb; 
	
	$p1_posts = $gcdb->get_results($request);
		
	for($i=0;$i<get_option('admin_post_number');$i++)
	{
		if(isset($p1_posts[$i]))
		{
			$p1_post = $p1_posts[$i];
			$post_ID = $p1_post->ID;
			$post_title = $p1_post->post_title;
			$post_status = $p1_post->post_status;
			$post_date = $p1_post->post_date_fmt;
			
			echo("<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">");
			echo("<A href=\"edit.php?q=$post_ID\">$post_title</A>");
			echo("</span><span class=\"tr_revision-state tr_revision-state-3\">");
			echo(" $post_status");
			echo("</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".get_permalink($post_ID)."\">");
			echo(get_permalink($post_ID));
			echo("</a></td><td>");
			echo("$post_date");
			echo("</td></tr>");
		}
	}
	echo("<input type=\"hidden\" name=\"begin_post_id\" id=\"begin_post_id\" value='$begin_id'>");
}

// get the search result grid
function processSearch($keyword){
	
	global $gcdb;
	
	$keyword = trim($keyword);
	//if(get_option('charset')=='gb2312')
	  $keyword = iconv( "UTF-8", "gb2312//IGNORE" , $keyword);
	$keyword = $gcdb->escape($keyword);
	$keywords = explode(" ", $keyword);
	$keyword_count = count($keywords);


	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_status from $gcdb->posts WHERE ";
	
	for($i=0;$i < $keyword_count;$i++)
	{
		$kw = $keywords[$i];
		if($i!=0)
			$request .= " OR ";
		$request .= "post_title LIKE '%$kw%' OR post_content LIKE '%$kw%'";
	}
	
    $gcdb->query("SET NAMES 'gb2312'");
	$search_results = $gcdb->get_results($request);
	$numbers = count($search_results);
	
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"javascript:xajax_processSearch(document.getElementById('tr_search_keyword').value);javascript:document.getElementById('lo').style.display='block';\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
	
	for($i=0;$i<$numbers;$i++)
	{
		if(isset($search_results[$i]))
		{
    		$s_post = $search_results[$i];
    		$post_ID = $s_post->ID;
    		$post_title = $s_post->post_title;
    		$post_status = $s_post->post_status;
    		$post_date = $s_post->post_date_fmt;
    		
    		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
    		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
    		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
    		$text.=" $post_status";
    		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".get_permalink($post_ID)."\">";
    		$text.=get_permalink($post_ID);
    		$text.="</a></td><td>";
    		$text.="$post_date";
    		$text.="</td></tr>";
		}
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" id=\"begin_post_id\" value='-".get_option('admin_post_number')."'>";
	$text .= "</TBODY></TABLE>";
	
	$objResponse = new xajaxResponse();
	//$objResponse->addAssign("admin_search","value","please wait...");
	//$objResponse->addAssign("admin_search","disabled",true);
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	//$objResponse->addAssign("admin_search","value","Search");
	//$objResponse->addAssign("admin_search","disabled",false);
	$objResponse->addAssign("lo","style.display",'none');
	
	return $objResponse;
}

// get the next page grid
function nextPage($begin_post_id){
	global $gcdb,$end_id;
	
	$begin_post_id += get_option('admin_post_number');
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY ID DESC LIMIT $begin_post_id, $end_id";
	
    $gcdb->query("SET NAMES 'gb2312'");
	$p1_posts = $gcdb->get_results($request);
	if(count($p1_posts)==0)
	{
	   $begin_post_id -= get_option('admin_post_number');
	
	   $request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY ID DESC LIMIT $begin_post_id, $end_id";
	
	   $p1_posts = $gcdb->get_results($request);
	}
	
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"javascript:xajax_processSearch(document.getElementById('tr_search_keyword').value);javascript:document.getElementById('lo').style.display='block';\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<get_option('admin_post_number');$i++)
	{
		if(isset($p1_posts[$i]))
		{
    		$p1_post = $p1_posts[$i];
    		$post_ID = $p1_post->ID;
    		$post_title = $p1_post->post_title;
    		$post_status = $p1_post->post_status;
    		$post_date = $p1_post->post_date_fmt;
    		
    		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
    		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
    		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
    		$text.=" $post_status";
    		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".get_permalink($post_ID)."\">";
    		$text.=get_permalink($post_ID);
    		$text.="</a></td><td>";
    		$text.="$post_date";
    		$text.="</td></tr>";
		}
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" id=\"begin_post_id\" value='$begin_post_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	$objResponse->addAssign("lo","style.display",'none');
	
	// for test
	//$objResponse->addAlert("done");
	
	return $objResponse;
}

// get the prevpage grid
function prevPage($begin_post_id){
	global $gcdb,$end_id;
	
	$begin_post_id -= get_option('admin_post_number');
	if($begin_post_id<0)
		$begin_post_id = 0;
	
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY ID DESC LIMIT $begin_post_id, $end_id";
	
    $gcdb->query("SET NAMES 'gb2312'");
	$p1_posts = $gcdb->get_results($request);
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"javascript:xajax_processSearch(document.getElementById('tr_search_keyword').value);javascript:document.getElementById('lo').style.display='block';\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<get_option('admin_post_number');$i++)
	{
		if(isset($p1_posts[$i]))
		{
    		$p1_post = $p1_posts[$i];
    		$post_ID = $p1_post->ID;
    		$post_title = $p1_post->post_title;
    		$post_status = $p1_post->post_status;
    		$post_date = $p1_post->post_date_fmt;
    		
    		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
    		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
    		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
    		$text.=" $post_status";
    		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".get_permalink($post_ID)."\">";
    		$text.=get_permalink($post_ID);
    		$text.="</a></td><td>";
    		$text.="$post_date";
    		$text.="</td></tr>";
		}
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" id=\"begin_post_id\" value='$begin_post_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	$objResponse->addAssign("lo","style.display",'none');
	
	return $objResponse;
}

// do draft action when click draft option
function updateDraft($post_id) {
	global $gcdb;
	
	$request = "Update $gcdb->posts SET post_status = 'draft' WHERE ID = $post_id";
	$gcdb->query($request);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// do publish action when click publish option
function updatePublish($post_id) {
	global $gcdb;
	
	$request = "Update $gcdb->posts SET post_status = 'publish' WHERE ID = $post_id";
	$gcdb->query($request);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// do delete action when click delete option
function updateDelete($post_id) {
	global $gcdb;
	
	$request = "Delete FROM $gcdb->posts WHERE ID = $post_id";
	$gcdb->query($request);
	
	delTagRelated($post_id);
	
	$objResponse = new xajaxResponse();
	
	return $objResponse;
}

// refresh the posts on the page to the newest update status
function refreshPage($begin_id) {
	global $gcdb,$end_id;
		
	$request = "SELECT ID,post_title,DATE_FORMAT(post_date, '%b %d, %Y') AS post_date_fmt,post_date,post_status FROM $gcdb->posts ORDER BY ID DESC LIMIT $begin_id, $end_id";
	
	$base_url = get_option('base_url');
	
	global $gcdb;
	
    $gcdb->query("SET NAMES 'gb2312'");
	$p1_posts = $gcdb->get_results($request);
		
	$text = "<TABLE id=tr_list-view>
              <THEAD>
              <TR>
                <TD colSpan=4>
                  <TABLE cellSpacing=0 cellPadding=0 width=\"100%\">
                    <TBODY>
                    <TR id=tr_new-page-2-list-view>
                      <TD>
                      <INPUT id=tr_search_keyword value=\"\">
                      <INPUT class=tr_submit id=admin_search name=admin_search onclick=\"javascript:xajax_processSearch(document.getElementById('tr_search_keyword').value);javascript:document.getElementById('lo').style.display='block';\" type=button value=\"Search\">
                      </TD></TR></TBODY></TABLE></TD></TR>
              <TR id=tr_list-view-sortRow>
                <TD></TD>
                <TD><SPAN id=tr_title-sort-switcher-list-view>Post Title</SPAN> / 
                <SPAN id=tr_revision-state-sort-switcher-list-view>Status</SPAN></TD>
                <TD><SPAN id=tr_url-sort-switcher-list-view>Web Address 
                  (URL)</SPAN> </TD>
                <TD><SPAN id=tr_modified-sort-switcher-list-view>Posted Date</SPAN> </TD></TR></THEAD>
           
              <TBODY id=tr_list-view-tbody>";
		
	for($i=0;$i<get_option('admin_post_number');$i++)
	{
	    
		if(isset($p1_posts[$i]))
		{
    		$p1_post = $p1_posts[$i];
    		$post_ID = $p1_post->ID;
    		$post_title = $p1_post->post_title;
    		$post_status = $p1_post->post_status;
    		$post_date = $p1_post->post_date;
    		
    		$text.="<tr><td><input type=\"checkbox\" name=\"C1\" value=\"$post_ID\"></td><td><span class=\"tr_pseudo-link\" title=\"Click to edit this page\">";
    		$text.="<A href=\"edit.php?q=$post_ID\">$post_title</A>";
    		$text.="</span><span class=\"tr_revision-state tr_revision-state-3\">";
    		$text.=" $post_status";
    		$text.="</span></td><td><a class=\"tr_published-page-url\" title=\"Click to see this page\" target=\"_blank\" href=\"".get_permalink($post_ID)."\">";
    		$text.=get_permalink($post_ID);
    		$text.="</a></td><td>";
    		$text.="$post_date";
    		$text.="</td></tr>";
		}
	}
	$text .= "<input type=\"hidden\" name=\"begin_post_id\" id=\"begin_post_id\" value='$begin_id'>";
	$text .= "</TBODY></TABLE>";

	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_list-view","innerHTML",$text);
	$objResponse->addAssign("lo","style.display",'none');
	
	return $objResponse;
}

// after click draft or publish or delete option in the selection, refresh the select to the original one
function resetSelect() {
	$text = "<TABLE class=tr_option-bar>
              <TBODY>
              <TR>
                <TD>&nbsp;
                <SELECT id=tr_bulk-action-dropdown name=\"tr_bulk-action-dropdown\" onchange=updateCheck('C1');>
                <OPTION value=\"\" selected>More Actions ...</OPTION>
                <OPTION value=publish>&nbsp; &nbsp; Publish</OPTION>
                <OPTION value=draft>&nbsp; &nbsp; Draft</OPTION>
                <OPTION value=delete>&nbsp; &nbsp; Delete</OPTION>
                </SELECT>
                <SPAN class=tr_selectors>Select: 
                  <SPAN class=tr_pseudo-link onclick=\"checkAll('C1');\">All</SPAN>,&nbsp;
                  <SPAN class=tr_pseudo-link onclick=\"checkNone('C1');\">None</SPAN></SPAN>
                </TD>
                <TD style=\"PADDING-RIGHT: 0px\" align=\"right\">
                <SPAN class=tr_pseudo-link onclick=\"javascript:xajax_prevPage(document.getElementById('begin_post_id').value);javascript:document.getElementById('lo').style.display='block';\">&#8249; Newer</SPAN>
                 | 
                 <SPAN class=tr_pseudo-link onclick=\"javascript:xajax_nextPage(document.getElementById('begin_post_id').value);javascript:document.getElementById('lo').style.display='block';\";>Older &#8250;</SPAN>
                </TD></TR></TBODY></TABLE>";
	
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("tr_select-view","innerHTML",$text);
	
	return $objResponse;
}

/***** edit.php *****/

// get tag name for 1 post based on post id, the result seperated by space
function getTagsText($post_id){
	global $gcdb;
	
	$prerequest = "SELECT count(*) tot FROM $gcdb->post2tag WHERE post_id = $post_id";
	$tot = $gcdb->get_var($prerequest);
	
	if($tot != 0)
	{
		$request = "SELECT a.tag_name FROM $gcdb->tags a, $gcdb->post2tag b, $gcdb->posts c WHERE a.tag_id=b.tag_id AND b.post_id = c.ID AND c.ID = $post_id";
		$tags = $gcdb->get_col($request);
	
		$number = count($tags);
	}
	else
		$number=0;
	
	$tag_text = "";
	for($i=0;$i < $number;$i++)
	{
		$tag_text .= $tags[$i]." ";
	}
	return $tag_text;
}

// Get a new id for next post in the gc_id table
function getNextId(){
	global $gcdb;
	$request = "SELECT ID FROM $gcdb->id LIMIT 1";
	$id = $gcdb->get_var($request);
	return $id;
}

function idAddOne(){
	global $gcdb;
	$request = "UPDATE $gcdb->id SET ID = ID +1";
	$gcdb->query($request);
}

// save post - new and edit
function savePost($post_id,$post_title,$post_content,$post_tags,$show_in_home,$post_status,$comment_status,$ping_status) {
	global $gcdb;
		
	// check is id exist
	$querycheckid = "SELECT COUNT(*) FROM $gcdb->posts WHERE ID = $post_id";
	$exist_no = $gcdb->get_var($querycheckid);
	$post_date = current_time('mysql');
	$post_date_gmt = current_time('mysql', 1);
	
	// id not exist - create new - insert
	if($exist_no == 0) {
		// insert post
		$requestnewpost = "INSERT INTO $gcdb->posts (ID,post_title,post_content,post_date,post_date_gmt,show_in_home,post_status,comment_status,ping_status) VALUES ($post_id,'$post_title','$post_content','$post_date','$post_date_gmt','$show_in_home','$post_status','$comment_status','$ping_status')";
		$gcdb->query($requestnewpost);
		
		// gc_db id + 1
		idAddOne();
		
		// insert tag related
		updateTags($post_id,$post_tags);
		
	}
	
	// id exist - edit - update
	else {
		// update post
		$requesteditpost = "UPDATE $gcdb->posts SET post_title='$post_title',post_content='$post_content',post_modified='$post_date',post_modified_gmt='$post_date_gmt',show_in_home='$show_in_home',post_status='$post_status',comment_status='$comment_status',ping_status='$ping_status' WHERE ID=$post_id";
		$gcdb->query($requesteditpost);
	
		// delete already tags related
		delTagRelated($post_id);
	
		// insert new related tags
		updateTags($post_id,$post_tags);
	}
	
}

function updateTags($post_id,$post_tags){
	
	global $gcdb;
	// get tags 
	$tags = explode(" ", $post_tags);
	$tag_count = count($tags);
	
	// do while to circle all the tags
	for($i=0;$i < $tag_count;$i++)
	{
		// check is tag exist
		$tag = $tags[$i];
		$query1 = "SELECT COUNT(*) FROM $gcdb->tags WHERE tag_name = '$tag'";
		$exist_tag = $gcdb->get_var($query1);
		
		// if tag not exist, insert tag into gc_tags
		if($exist_tag == '0') {
			$query_insert_tag = "INSERT INTO $gcdb->tags (tag_name) VALUES ('$tag')";
			$gcdb->query($query_insert_tag);
		}
		
		// select tag_ID
		$query2 = "SELECT tag_ID FROM $gcdb->tags WHERE tag_name = '$tag'";
		$tagid = $gcdb->get_var($query2);
		
		// insert record to relate post to tag
		$insert_post2tag = "INSERT INTO $gcdb->post2tag (tag_id, post_id) VALUES ($tagid, $post_id)";
		$gcdb->query($insert_post2tag);
		
	}
}

function delTagRelated($post_id) {
	global $gcdb;
	$request = "DELETE FROM $gcdb->post2tag WHERE post_id = $post_id";
	$gcdb->query($request);
	
}

/***** settings.php *****/

// load settings grid, but no template and about_text
function initSettings() {
	global $gcdb;
	
	$request = "SELECT * FROM $gcdb->options WHERE option_name NOT IN ('template','about_text') ORDER BY option_id";
	
	$settings = $gcdb->get_results($request);
		
	for($i=0;$i<count($settings);$i++)
	{
		if(isset($settings[$i]))
		{
			$setting = $settings[$i];
			$setting_ID = $setting->option_id;
			$setting_name = $setting->option_name;
			$setting_des = $setting->option_description;
			$setting_value = $setting->option_value;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$setting_ID<span></td>");
			echo("<td class=\"short\"><span>$setting_name<span></td>");
			echo("<td class=\"short\"><span>$setting_des<span></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"value$setting_ID\" name=\"value$setting_ID\" value=\"$setting_value\" size=\"70\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditOption($setting_ID,document.getElementById('value$setting_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function get_theme_root() {
	return ABSPATH . "gc-themes";
}

function get_themes() {
	global $gc_themes;

	if (isset($gc_themes)) {
		return $gc_themes;
	}

	$themes = array();
	$theme_root = get_theme_root();
	$theme_loc = str_replace(ABSPATH, '', $theme_root);

	// Files in gc-themes directory
	$themes_dir = @ dir($theme_root);
	if ($themes_dir) {
		while(($theme_dir = $themes_dir->read()) !== false) {
			if (is_dir($theme_root . '/' . $theme_dir)) {
				if ($theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS') {
					continue;
				}

				$themes[$theme_dir] = array('Name' => $theme_dir, 'Title' => $theme_dir);
			}
		}
	}

	$gc_themes = $themes;

	return $themes;
}

function init_themes_settings(){
	global $gcdb;
	
	$query1 = "SELECT  option_value FROM $gcdb->options WHERE option_name='template'";
	$tpl_now = $gcdb->get_var($query1);
	
	$themes = get_themes();
	$theme_names = array_keys($themes);
	natcasesort($theme_names);
	
	echo("<SELECT id=\"tpl-dropdown\" name=\"tpl-dropdown\" onchange=\"javascript:xajax_saveTplOption(document.getElementById('tpl-dropdown').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\">");

	foreach ($theme_names as $theme_name) {
		$template = $themes[$theme_name]['Title'];
		$selected="";
		if($tpl_now==$template)
		{
		    $selected="selected";
		}
    	echo("<OPTION value=\"$template\" $selected>&nbsp; &nbsp; $template</OPTION>");
	}
	echo("</SELECT>");
    
}

function saveEditOption($option_ID, $option_value)
{
	global $gcdb;
	$objResponse = new xajaxResponse();

	if(get_option('charset')=='gb2312')
	   $option_value = iconv( "UTF-8", "GB2312//IGNORE" , $option_value);
	$option_value = trim($option_value);
	//$option_value = apply_filters($option_value);

	$request1 = "UPDATE $gcdb->options SET option_value='$option_value' WHERE option_id=$option_ID";
	$gcdb->query($request1);

	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
	$objResponse->addAssign("lo","innerHTML",'Saved');
	$objResponse->addAssign("lo","style.background",'green');

	return $objResponse;
}

function saveTplOption($option_value)
{
	global $gcdb;
	$objResponse = new xajaxResponse();

	if(get_option('charset')=='gb2312')
	   $option_value = iconv( "UTF-8", "GB2312//IGNORE" , $option_value);
	$option_value = trim($option_value);
	//$option_value = apply_filters($option_value);

	$request1 = "UPDATE $gcdb->options SET option_value='$option_value' WHERE option_name='template'";
	$gcdb->query($request1);

	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
	$objResponse->addAssign("lo","innerHTML",'Saved');
	$objResponse->addAssign("lo","style.background",'green');

	return $objResponse;
}

/***** editabout.php *****/

function getAboutOption()
{
	global $gcdb;
	
	$query1 = "SELECT  option_value FROM $gcdb->options WHERE option_name='about_text'";
	$about_content = $gcdb->get_var($query1);
	echo $about_content;
}

function saveAboutOption($option_value)
{
	global $gcdb;

	$request1 = "UPDATE $gcdb->options SET option_value='$option_value' WHERE option_name='about_text'";
	$gcdb->query($request1);
}

/***** edituser.php *****/
function initEditUser() {
	global $gcdb;
	
	$request = "SELECT * FROM $gcdb->users ORDER BY ID";
	
	$users = $gcdb->get_results($request);
		
	for($i=0;$i<count($users);$i++)
	{
		if(isset($users[$i]))
		{
			$user = $users[$i];
			$user_ID = $user->ID;
			$user_name = $user->user_login;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$user_ID<span></td>");
			echo("<td class=\"short\"><span>$user_name<span></td>");
			echo("<td class=\"short\"><input type=\"password\" id=\"value$user_ID\" name=\"value$user_ID\" value=\"\" size=\"20\" maxlength=\"30\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditUser($user_ID,document.getElementById('value$user_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function saveEditUser($user_id,$user_pass)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if(get_option('charset')=='gb2312'){
	   $user_id = iconv( "UTF-8", "GB2312//IGNORE" , $user_id);
	   $user_pass = iconv( "UTF-8", "GB2312//IGNORE" , $user_pass);
	}
	if($user_pass!="")
	{
    	$user_pass=md5($user_pass);
    
    	$request1 = "UPDATE $gcdb->users SET user_pass='$user_pass' WHERE ID=$user_id";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÃÜÂë²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}

	return $objResponse;
}

function saveAddUser($user_name,$user_pass)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if ($user_name=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'ÓÃ»§Ãû²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	elseif($user_pass=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'ÃÜÂë²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	else {
	   if(get_option('charset')=='gb2312'){
	       $user_name = iconv( "UTF-8", "GB2312//IGNORE" , $user_name);
	       $user_pass = iconv( "UTF-8", "GB2312//IGNORE" , $user_pass);
	   }
    	$user_pass=md5($user_pass);
    
    	$request1 = "INSERT INTO $gcdb->users (user_login,user_pass) VALUES ('$user_name','$user_pass')";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'User Added');
    	$objResponse->addAssign("lo","style.background",'green');
	}

	return $objResponse;
}

//alter user to change his admin password if password='admin'
function get_user_msg(){
    
	global $gcdb;
	$msg="";
	$query1 = "SELECT user_pass FROM $gcdb->users WHERE user_login='admin'";
	$user_pass = $gcdb->get_var($query1);
	if(md5('admin')==$user_pass)
	   $msg="<font color='red'>".get_option('change_password_msg')."</font>";
	echo $msg;
}

/**** editlinks.php ****/
function initEditLink() {
	global $gcdb;
	
	$request = "SELECT  link_id,link_url,link_name FROM $gcdb->links ORDER BY link_id";
	
	$links = $gcdb->get_results($request);
		
	for($i=0;$i<count($links);$i++)
	{
		if(isset($links[$i]))
		{
			$link = $links[$i];
			$link_ID = $link->link_id;
			$link_name = $link->link_name;
			$link_url = $link->link_url;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$link_ID<span></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"name$link_ID\" name=\"name$link_ID\" value=\"$link_name\" size=\"40\" /></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"url$link_ID\" name=\"url$link_ID\" value=\"$link_url\" size=\"80\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditLink($link_ID,document.getElementById('name$link_ID').value,document.getElementById('url$link_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Delete\" onclick=\"javascript:xajax_saveDeleteLink($link_ID);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Deleting';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function saveEditLink($link_ID,$link_name,$link_url){
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if($link_name!=""&&$link_url!="")
	{    
	   if(get_option('charset')=='gb2312'){
	       $link_name = iconv( "UTF-8", "GB2312//IGNORE" , $link_name);
	       $link_url = iconv( "UTF-8", "GB2312//IGNORE" , $link_url);
	   }
    	$request1 = "UPDATE $gcdb->links SET link_name='$link_name',link_url='$link_url' WHERE link_ID=$link_ID";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'Ãû³ÆºÍURL²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}

	return $objResponse;
}

function saveDeleteLink($link_ID){
	global $gcdb;
	$objResponse = new xajaxResponse();
	$request1 = "SELECT COUNT(link_ID) FROM $gcdb->links WHERE link_ID=$link_ID";
	$num=$gcdb->get_var($request1);
	
	if($num==1)
	{    
    	$request2 = "DELETE FROM $gcdb->links WHERE link_ID=$link_ID";
    	$gcdb->query($request2);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Deleted');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÎÞ·¨É¾³ý¸ÃÁ´½Ó');
    	$objResponse->addAssign("lo","style.background",'#c44');
	}

	return $objResponse;
}

function saveAddLink($link_name,$link_url)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if ($link_name=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'Á´½ÓÃû³Æ²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	elseif($link_url=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'URL²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	else {
	   if(get_option('charset')=='gb2312'){
	       $link_name = iconv( "UTF-8", "GB2312//IGNORE" , $link_name);
	       $link_url = iconv( "UTF-8", "GB2312//IGNORE" , $link_url);
	   }
    
    	$request1 = "INSERT INTO $gcdb->links (link_name,link_url) VALUES ('$link_name','$link_url')";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("a_linkname","value",'');
    	$objResponse->addAssign("a_linkurl","value",'http://');
    	$objResponse->addAssign("lo","innerHTML",'Link Added');
    	$objResponse->addAssign("lo","style.background",'green');
	}

	return $objResponse;
}

/**** edittags.php ****/
function initEditTag() {
	global $gcdb;
	
	$request = "SELECT  tag_ID,tag_name,tag_description,tag_parent FROM $gcdb->tags ORDER BY tag_id DESC";
	
	$tags = $gcdb->get_results($request);
		
	for($i=0;$i<count($tags);$i++)
	{
		if(isset($tags[$i]))
		{
			$tag = $tags[$i];
			$tag_ID = $tag->tag_ID;
			$tag_name = $tag->tag_name;
			$tag_description = $tag->tag_description;
			$tag_parent = (int)$tag->tag_parent;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$tag_ID<span></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"name$tag_ID\" name=\"name$tag_ID\" value=\"$tag_name\" size=\"40\" /></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"des$tag_ID\" name=\"des$tag_ID\" value=\"$tag_description\" size=\"80\" /></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"parent$tag_ID\" name=\"parent$tag_ID\" value=\"$tag_parent\" size=\"20\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditTag($tag_ID,document.getElementById('name$tag_ID').value,document.getElementById('des$tag_ID').value,document.getElementById('parent$tag_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"No Show\" onclick=\"javascript:xajax_saveNoShowTag($tag_ID);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function saveEditTag($tag_ID,$tag_name,$tag_description,$tag_parent){
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if($tag_name!="")
	{    
	    if(get_option('charset')=='gb2312'){
	       $tag_name = iconv( "UTF-8", "GB2312//IGNORE" , $tag_name);
	       $tag_description = iconv( "UTF-8", "GB2312//IGNORE" , $tag_description);
	    }
	    if($tag_parent!="")
	       $tag_parent=(int)$tag_parent;
	    else
	       $tag_parent=0;
	       
        $request1 = "UPDATE $gcdb->tags SET tag_name='$tag_name',tag_description='$tag_description',tag_parent='$tag_parent' WHERE tag_ID=$tag_ID";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'TAGÃû³Æ²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}

	return $objResponse;
}

function saveNoShowTag($tag_ID){
	global $gcdb;
	$objResponse = new xajaxResponse();
	$request1 = "SELECT COUNT(tag_ID) FROM $gcdb->tags WHERE tag_ID=$tag_ID";
	$num=$gcdb->get_var($request1);
	
	if($num==1)
	{    
    	$request2 = "UPDATE $gcdb->tags SET tag_description='noshow' WHERE tag_ID=$tag_ID";
    	$gcdb->query($request2);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'No Show Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÎÞ·¨Òþ²Ø¸ÃTAG');
    	$objResponse->addAssign("lo","style.background",'#c44');
	}

	return $objResponse;
}

function saveAddTag($tag_name,$tag_description,$tag_parent)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if ($tag_name=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'TAGÃû³Æ²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	else {
	   if(get_option('charset')=='gb2312'){
	       $tag_name = iconv( "UTF-8", "GB2312//IGNORE" , $tag_name);
	       $tag_description = iconv( "UTF-8", "GB2312//IGNORE" , $tag_description);
	   }
	    if($tag_parent!="")
	       $tag_parent=(int)$tag_parent;
	    else
	       $tag_parent=0;
    
    	$request1 = "INSERT INTO $gcdb->tags (tag_name,tag_description,tag_parent) VALUES ('$tag_name','$tag_description','$tag_parent')";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("a_tagname","value",'');
    	$objResponse->addAssign("a_tagdes","value",'');
    	$objResponse->addAssign("a_tagparent","value",'0');
    	$objResponse->addAssign("lo","innerHTML",'Tag Added');
    	$objResponse->addAssign("lo","style.background",'green');
	}

	return $objResponse;
}

/**** editspams.php ****/
function initEditSpam() {
	global $gcdb;
	
	$request = "SELECT  spam_ID,spam_value,spam_type FROM $gcdb->spams ORDER BY spam_ID";
	
	$spams = $gcdb->get_results($request);
	
	for($i=0;$i<count($spams);$i++)
	{
		if(isset($spams[$i]))
		{
			$spam = $spams[$i];
			$spam_ID = $spam->spam_ID;
			$spam_value = $spam->spam_value;
			$spam_type = $spam->spam_type;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$spam_ID<span></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"value$spam_ID\" name=\"value$spam_ID\" value=\"$spam_value\" size=\"100\" /></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"type$spam_ID\" name=\"type$spam_ID\" value=\"$spam_type\" size=\"20\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditSpam($spam_ID,document.getElementById('value$spam_ID').value,document.getElementById('type$spam_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Delete\" onclick=\"javascript:xajax_saveDeleteSpam($spam_ID);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Deleting';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function saveEditSpam($spam_ID,$spam_value,$spam_type){
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if($spam_value!=""&&($spam_type=="name"||$spam_type=="email"||$spam_type=="text"||$spam_type=="ip"))
	{
	   if(get_option('charset')=='gb2312'){
	       $spam_value = iconv( "UTF-8", "GB2312//IGNORE" , $spam_value);
	       $spam_type = iconv( "UTF-8", "GB2312//IGNORE" , $spam_type);
	   }
    	$request1 = "UPDATE $gcdb->spams SET spam_value='$spam_value',spam_type='$spam_type' WHERE spam_ID=$spam_ID";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÊäÈë´íÎó');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}

	return $objResponse;
}

function saveDeleteSpam($spam_ID){
	global $gcdb;
	$objResponse = new xajaxResponse();
	$request1 = "SELECT COUNT(spam_ID) FROM $gcdb->spams WHERE spam_ID=$spam_ID";
	$num=$gcdb->get_var($request1);
	
	if($num==1)
	{    
    	$request2 = "DELETE FROM $gcdb->spams WHERE spam_ID=$spam_ID";
    	$gcdb->query($request2);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Deleted');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÎÞ·¨É¾³ý');
    	$objResponse->addAssign("lo","style.background",'#c44');
	}

	return $objResponse;
}

function saveAddSpam($spam_value,$spam_type)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if ($spam_value=="")
	{
    	$objResponse->addAssign("lo","innerHTML",'Öµ²»ÄÜÎª¿Õ');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	elseif($spam_type!="name"&&$spam_type!="email"&&$spam_type!="text"&&$spam_type!="ip")
	{
    	$objResponse->addAssign("lo","innerHTML",'ÀàÐÍ´íÎó');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	else {
	   if(get_option('charset')=='gb2312'){
	       $spam_value = iconv( "UTF-8", "GB2312//IGNORE" , $spam_value);
	       $spam_type = iconv( "UTF-8", "GB2312//IGNORE" , $spam_type);
	   }
    
    	$request1 = "INSERT INTO $gcdb->spams (spam_value,spam_type) VALUES ('$spam_value','$spam_type')";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("a_spamvalue","value",'');
    	$objResponse->addAssign("a_spamtype","value",'text');
    	$objResponse->addAssign("lo","innerHTML",'Spam Added');
    	$objResponse->addAssign("lo","style.background",'green');
	}

	return $objResponse;
}

/**** editx.php ****/
function initEditX() {
	global $gcdb;
	
	$request = "SELECT  x_ID,post_ID,x_name  FROM $gcdb->x ORDER BY x_ID";
	
	$xs = $gcdb->get_results($request);
	
	for($i=0;$i<count($xs);$i++)
	{
		if(isset($xs[$i]))
		{
			$x = $xs[$i];
			$x_ID = $x->x_ID;
			$post_ID = $x->post_ID;
			$x_name = $x->x_name;
			
			echo("<tr class=\"withover\">");
			echo("<td class=\"count\"><span>$x_ID<span></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"post$x_ID\" name=\"post$x_ID\" value=\"$post_ID\" size=\"15\" /></td>");
			echo("<td class=\"short\"><input type=\"text\" id=\"name$x_ID\" name=\"name$x_ID\" value=\"$x_name\" size=\"50\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Save\" onclick=\"javascript:xajax_saveEditX($x_ID,document.getElementById('post$x_ID').value,document.getElementById('name$x_ID').value);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Saving';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("<td class=\"short\"><input type=\"submit\" value=\"Delete\" onclick=\"javascript:xajax_saveDeleteX($x_ID);javascript:document.getElementById('lo').style.display='block';javascript:document.getElementById('lo').innerHTML='Deleting';javascript:document.getElementById('lo').style.background='#c44';\" /></td>");
			echo("</tr>");
		}
	}
}

function saveEditX($x_ID,$post_ID,$x_name){
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if($post_ID!=""&&is_int($post_ID)&&$x_name!=""&&($x_name!="q"||$x_name!="archive"||$x_name!="search"||$x_name!="about"||$x_name!="links"||$x_name!="tags"||$x_name!="tag"||$x_name!="month"||$x_name!="comment"||$x_name!="feed"))
	{
	   if(get_option('charset')=='gb2312'){
	       $post_ID = iconv( "UTF-8", "GB2312//IGNORE" , $post_ID);
	       $x_name = iconv( "UTF-8", "GB2312//IGNORE" , $x_name);
	   }
    	$request1 = "UPDATE $gcdb->x SET post_ID='$post_ID',x_name='$x_name' WHERE x_ID=$x_ID";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Saved');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÊäÈë´íÎó');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}

	return $objResponse;
}

function saveDeleteX($x_ID){
	global $gcdb;
	$objResponse = new xajaxResponse();
	$request1 = "SELECT COUNT(x_ID) FROM $gcdb->x WHERE x_ID=$x_ID";
	$num=$gcdb->get_var($request1);
	
	if($num==1)
	{    
    	$request2 = "DELETE FROM $gcdb->x WHERE x_ID=$x_ID";
    	$gcdb->query($request2);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("lo","innerHTML",'Deleted');
    	$objResponse->addAssign("lo","style.background",'green');
	}
	else {
    	$objResponse->addAssign("lo","innerHTML",'ÎÞ·¨É¾³ý');
    	$objResponse->addAssign("lo","style.background",'#c44');
	}

	return $objResponse;
}

function saveAddX($post_ID,$x_name)
{
	global $gcdb;
	$objResponse = new xajaxResponse();
	
	if ($post_ID!=""&&is_int($post_ID))
	{
    	$objResponse->addAssign("lo","innerHTML",'Öµ²»ÄÜÎª¿ÕÇÒ±ØÐèÊÇÊý×Ö');
    	$objResponse->addAssign("lo","style.background",'#c44');  
	}
	elseif($x_name==""||($x_name=="q"||$x_name=="archive"||$x_name=="search"||$x_name=="about"||$x_name=="links"||$x_name=="tags"||$x_name=="tag"||$x_name=="month"||$x_name=="comment"||$x_name=="feed"))
	{
    	$objResponse->addAssign("lo","innerHTML",'¹Ø¼ü×Ö´íÎó');
    	$objResponse->addAssign("lo","style.background",'#c44');
	}
	else {
	   if(get_option('charset')=='gb2312'){
	       $post_ID = iconv( "UTF-8", "GB2312//IGNORE" , $post_ID);
	       $x_name = iconv( "UTF-8", "GB2312//IGNORE" , $x_name);
	   }
    
    	$request1 = "INSERT INTO $gcdb->x (post_ID,x_name) VALUES ('$post_ID','$x_name')";
    	$gcdb->query($request1);
    
    	//$objResponse->addAlert("Option'".$option_ID."' - Saved");
    	$objResponse->addAssign("a_x_postid","value",'');
    	$objResponse->addAssign("a_x_name","value",'');
    	$objResponse->addAssign("lo","innerHTML",'X Added');
    	$objResponse->addAssign("lo","style.background",'green');
	}

	return $objResponse;
}

/**** All Pages Template function ****/
function getNavBar() {
    $navBar = '<A href="index.php">Edit Posts</A> | 
                <A href="settings.php">Site Settings</A> | 
                <A href="edituser.php">My Account</A> | 
                <A href="editabout.php">Edit About</A> | 
                <A href="editlinks.php">Edit Links</A> | 
                <A href="edittags.php">Edit Tags</A> | 
                <A href="editspams.php">Spams</A> | 
                <A href="editx.php">X</A> | 
                <A href="logout.php">Sign Out</A>&nbsp;&nbsp;';
    echo $navBar;
}

function getFooterBar() {
    $footerBar = '&copy; 2006 ericfish.com';
    echo $footerBar;
}
?>