<?php
// posts view

if(!defined("PAGE")){
	define("PAGE", "posts");
	define("ROOT", "./");
	require("inc/main.php");
}

// get criteria
$page = $_GET['page'];
// TODO: Add a lot more criteria

$posts = get_posts();

display_template("posts", $posts);
