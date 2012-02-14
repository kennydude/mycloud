<?php
global $blogName, $blogTagline;
// Template authors: You can replace this
// Simply, the admin header is there for quickness and to load foundation
// Required for Admin template: Tell it to not load any of the quick Admin functionality
define("BAREBONES", true);
define("NO_SESSIONS", true);


include ROOT . "inc/templates/header.php";
?>
<div class="header container">
		<div class="row">
			<div class="twelve columns">
				<h1><a href="<?php echo ROOT; ?>"><?php echo $blogName; ?></a></h1>
				<p><?php echo $blogTagline; ?></p>
			</div>
		</div>
</div>
<div class="container">
		<div class="row">
			<div class="eight columns">
