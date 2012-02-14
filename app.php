<?php
define("PAGE", $_GET['app']);
define("ROOT", "./");
require("./inc/main.php");

$app = $_GET['app'];

if(file_exists("./content/apps/$app/front.php")){
	require("./content/apps/$app/front.php");
} else{
	do_404();
	die("Application frontend does not exist");
}
