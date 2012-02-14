<?php
// mycloud homepage

define("PAGE", "index");
define("ROOT", "./");
require("inc/main.php");

define("PAGE_TITLE", $blogName);

switch($home){
	case "posts":
		require("posts.php");
		break;
}
