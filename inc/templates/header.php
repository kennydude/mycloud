<?php /** Admin header */
if(!defined("PAGE_TITLE")){
	define("PAGE_TITLE", "Untitled");
}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta charset="utf-8">
		<title><?php echo PAGE_TITLE; ?></title>
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<link href="<?php echo ROOT; ?>/inc/templates/css/foundation.css" rel="stylesheet" />
		<link href="<?php echo ROOT; ?>/inc/templates/css/style.css" rel="stylesheet" />

		<!-- Le fav and touch icons --
		<link rel="shortcut icon" type="image/x-icon" href="assets/ico/favicon.ico">
		<link rel="apple-touch-icon" href="assets/ico/bootstrap-apple-57x57.png">
		<link rel="apple-touch-icon" sizes="72x72" href="assets/ico/bootstrap-apple-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="assets/ico/bootstrap-apple-114x114.png">-->
	</head>

	<body>
<?php
if(!defined("NO_SESSIONS")){
	if(is_logged_in()){

	} else{
		
	}
}
?>
<?php if(!defined("BAREBONES")){ ?>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
<?php } ?>				
