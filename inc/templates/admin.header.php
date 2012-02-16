<?php
define("BAREBONES", true);
include "header.php";

function a($in){
	if(PAGE == $in){ echo ' class="active"'; }
}
function s($in){
	if(SUBPAGE == $in){ echo ' class="active"'; }
}

function n(){
	global $apps;
	?>
<dd>
	<a href="index.php"<?php a("ad_dash"); ?>><?php L("Dashboard"); ?></a>
</dd>
<dd><a href="posts.php"<?php a("ad_posts"); ?>><?php L("Posts"); ?></a></dd>  	
	<?php
	foreach($apps as $key => $value){
?><dd><a href="app.php?app=<?php echo $key; ?>"<?php a($key); ?>><?php echo $value; ?></a></dd> <?php
	}
}
?>
<div class="header container">
		<div class="row">
			<div class="twelve columns">
				<a href="<?php echo ROOT; ?>">&lt; <?php L("Homepage"); ?></a>
				<h1><a href="<?php echo ROOT; ?>admin"><?php L("Administrator"); ?></a></h1>
			</div>
		</div>
</div>
<div class="container">
	<div class="row">
		<div class="two columns">
			<dl class="tabs show-on-phones">
				<?php n(); ?>
			</dl>
			<dl class="nice vertical tabs hide-on-phones">
				<?php n(); ?>
			</dl>
			<?php if(function_exists("sidebar")) { sidebar(); } ?>
		</div>
		<div class="ten columns">
			<?php
				global $admin_tabs;
				if($admin_tabs){
			?>
			<dl class="nice sub-nav">
				<?php foreach($admin_tabs as $url => $v){ ?>
				<dd<?php s($v['key']); ?>><a href="<?php echo $url; ?>"><?php echo $v['name']; ?></a></dd>
				<?php } ?>
			</dl>
			<?php } ?>
