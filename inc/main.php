<?php
// mycloud main

function L($in){
	echo $in;
}

if(!file_exists(ROOT . "config.php")){
	require(ROOT . "inc/templates/noconfig.php");
	die("");
}

global $blogName, $theme, $home;
require(ROOT . "config.php");
require(ROOT . "inc/date.php");

function do_404(){
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
}

function login_user($username, $password){
	start_db();
	global $salt;
	$user = R::findOne("user", "username = ?", array(strtolower($username)));
	if(!$user){
		return false;
	} else{
		if(sha1($user->hash. "-$salt-".$password) == $user->password){
			return $user;
		} else{ return false; }
	}
}
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") { $pageURL .= "s";}
	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
	}
	
	$g = false;
	foreach($_GET as $k => $v){
		if($g == false){ $pageURL .= "?"; $g = true; }
		$pageURL .= $k . "=" . $v . "&";
	}

	if($g == true){
		$pageURL = substr($pageURL, 0, -1);
	}

	return $pageURL;
}

function get_posts(){
	start_db();
	return R::find("post", "1 ORDER BY published DESC LIMIT 0, 10");
}

function display_template($name, $context){
	global $theme;
	$themedir = ROOT . "content/themes/$theme/";
	require(ROOT . "content/themes/$theme/$name.php");
}

function frontpage_stuff(){
	display_template("frontpage", null);
}

$session_started = false;
/**
 * Just starts the session safely :)
 */
function start_session(){
	global $session_started;
	if(!$session_started)
		session_start();
}

function start_db(){
	global $dbType, $dbConnectDetails, $dbUser, $dbPassword, $dbStarted;
	if(!$dbStarted){
		require_once(ROOT . "inc/rb.php");
		R::setup("$dbType:$dbConnectDetails", $dbUser, $dbPassword);
		$dbStarted = true;
	}
}

function is_logged_in(){
	start_session();
	return $_SESSION['mycloud_loginid'];
}

function require_login($redir_back = ''){
	if(!is_logged_in()){
		if($redir_back != '')
			$redir_back = "?returnto=" . rawurlencode($redir_back);
		header("Location: " . ROOT . "admin/login.php$redir_back");
		die("You are not logged in. You will be redirected to the login page");
	}
}
function get_self(){
	global $self;
	if(!$self){
		start_db();
		$self = R::load("user", $_SESSION['mycloud_loginid']);
	}
	return $self;
}

function require_admin(){
	require_login();
	start_db();
	$self = get_self();
	if(!$self->admin){
		die("Not admin");
	}
}

function _trim_value(&$value) { 
    if (is_string($value)) 
        $value = trim($value); 
} 

function array_trim($arr) { 
    array_walk($arr, '_trim_value'); 
    return array_filter($arr); 
} 

function parseQueryString($str) { 
    $op = array(); 
    $pairs = explode("&", $str); 
    foreach ($pairs as $pair) { 
        list($k, $v) = array_map("urldecode", explode("=", $pair)); 
        $op[$k] = $v; 
    } 
    return $op; 
} 

// Scripts
$registered_scripts = array();
function register_script($name, $url, $requires = array()){
	global $registered_scripts;
	$registered_scripts[$name] = array(
		"name" => $name,
		"url" => $url,
		"requires" => $requires
	);
}

// Register the scripts we have on-hand
register_script("jquery", ROOT . "inc/templates/js/jquery.js");

$scripts = array();
function request_script($name){
	global $registered_scripts, $scripts;
	$scripts[$name] = $registered_scripts[$name];
}

function output_scripts(){
	global $scripts, $registered_scripts;
	$outputed = array();
	foreach($scripts as $script){
		foreach($script['requires'] as $req){
			if(!in_array($req, $outputed)){
				echo "<script type='text/javascript' src='".$registered_scripts[$req]["url"]."'></script>";
				$outputed[] = $req;
			}
		}
		if(!in_array($script['name'], $outputed)){
			echo "<script type='text/javascript' src='".$script["url"]."'></script>";
			$outputed[] = $req;
		}
	}
}