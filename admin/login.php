<?php
// mycloud Login Page

define("PAGE", "login");
define("ROOT", "../");
require("../inc/main.php");

define("PAGE_TITLE", _("Login to administrator"));

function finish_login(){
	if($_GET['returnto']){
		header("Location: " . ROOT . $_GET['returnto']);
	}else{
		header("Location: index.php");
	}
	die("You have been logged in. Transporting you to the dashboard");
}

start_session();

if($_POST['username']){
	echo "meep";
	$user = login_user($_POST['username'], $_POST['password']);
	if($user == false){
		$error = true;
	} else{
		// Login user right now! :D
		$_SESSION['mycloud_loginid'] = $user->id;
		finish_login();
	}
	print_r($user);
	echo "mep";
} else if($_GET['auth'] and $openid_enabled){
	// OpenID Auth
	switch($_GET['auth']){
		case "google":
			$ourl = "https://www.google.com/accounts/o8/id";
			break;
	}
	if($ourl){
		require("../inc/contrib/openid.php");
		$openid = new LightOpenID('localhost');
		if(!$openid->mode) {
			$openid->returnUrl = $public_addr . "/admin/login.php?auth=" . $_GET['auth'];
			$openid->identity = $ourl;
			$openid->required = array('namePerson/friendly');
			header("Location: " . $openid->authUrl());
			exit(0);
		} else if($openid->mode != 'cancel') {
			if($openid->validate()){
				$id = $openid->identity;
				start_db();
				$user = R::findOne('openid', 'url = ?', array($id));
				if(!$user->id){
					session_start();
					$_SESSION['openid_login'] = $id;
					header("Location: register.php?action=openid");
					exit();
				} else{
					// User is logged in
					$user = R::load("user", $user->user);
					$_SESSION['mycloud_loginid'] = $user->id;
					finish_login();
				}
			} else{
				$error = true;
			}
		}
	}
}


require("../inc/templates/admin.login.php");
