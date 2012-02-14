<?php
// mycloud Admin editor

define("PAGE", "ad_posts");
define("ROOT", "../");
require("../inc/main.php");

define("PAGE_TITLE", _("Posts"));

start_db();
require_admin();

require("../inc/forms.php");

if($_GET['id']){
	$post = R::load('post', $_GET['id']);
}

$form = new BeanForm($post);
$form->add_field( new Field("title", _("Title"), "text", array("super-oversize") ) );
$form->add_field( new TextArea("excerpt", _("Excerpt"), 3, array("large")));
$form->add_field( new TextArea("body", _("Body"), 20 ) );

$form->section(_("Additional"));
$form->section_property("display", "sidebar");
$form->add_field( new DateTimeField("published", _("Date to Publish") ) );
$form->add_field( new TagField("tags", _("Tags")) );

$form->done();

/*
if($_POST){
	if(!$_GET['id']){
		$post = R::dispense('post');
	}
	
	$post->title = $_POST['title'];
	$post->excerpt = $_POST['excerpt'];
	$post->body = $_POST['body'];
	$post->published = mktime(); // TODO
	
	R::store($post);
	$tags = R::tag($post);
	R::untag($post, $tags);
	R::tag($post, array_trim(explode(",", $_POST['tags'])) );
	
	header("Location: post.php?id=".$post->id."&updated=true");
	die("Redirecting...");
}
*/

require("../inc/templates/admin.editor.php");
