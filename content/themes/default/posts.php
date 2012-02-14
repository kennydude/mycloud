<?php
include "header.php"; ?>
<?php
if(PAGE == "index"){
	frontpage_stuff();
}

define("INLINE", "true");
if(count($context) == 0){
	?>
	<div class="alert-box error">
		<?php L("There are no posts for your selection"); ?>
	</div>
	<?php
} else{
	define("THEMEDIR", $themedir);
	function p($context){
		include THEMEDIR . "post.php";
	}
	
	foreach($context as $post){ 
		p($post);
	}
}
?>
<?php include "footer.php"; ?>
