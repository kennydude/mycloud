<?php
define("PAGE", "post");
define("ROOT", "./");
require("inc/main.php");

start_db();
$post = R::load("post", $_GET['id']);

if(!$post->id){
	do_404();
	display_template("404", null);
} else{
	display_template("post", $post);
}
