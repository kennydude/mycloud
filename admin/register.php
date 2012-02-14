<?php
// mycloud Login Page

define("PAGE", "register");
define("ROOT", "../");
require("../inc/main.php");

define("PAGE_TITLE", _("Register"));

start_session();

if($_GET['action'] == "openid" && $_POST){
	if( $openid_enabled = true ) {
		$user = login_user($_POST['username'], $_POST['password']);
		if($user == false){
			$error = true;
		} else{
			$id = $_SESSION['openid_login'];
			$oid = R::dispense("openid");
			$oid->url = $id;
			$oid->user = $user->id;
			R::store($oid);

			header("Location: login.php");
		}
	} else{
		die("hackery is going on. OpenID is disabled right now");
	}
}

require("../inc/templates/admin.register.php");
