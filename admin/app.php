<?php
define("PAGE", $_GET['app']);
define("ROOT", "../");
require("../inc/main.php");

require_admin();

$app = $_GET['app'];

if(file_exists("../content/apps/$app/admin.php")){
	require("../content/apps/$app/admin.php");
} else{
	do_404();
	die("Application admin does not exist");
}
