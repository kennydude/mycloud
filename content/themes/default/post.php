<?php
if(!defined("INLINE")){
	include "header.php";
}
?>
<h2><?php echo $context->title; ?></h2>
<h4 class="subheader"><?php echo $context->excerpt; ?></h4>
<?php if(!defined("INLINE")){
	?>
<div class="content">
<?php echo $context->body; ?>
</div>
comments etc
	<?php
	include "footer.php";
} else{
?>
	<a href="post.php?id=<?php echo $context->id; ?>"><?php L("Continue reading..."); ?></a>
	<hr/>
<?php
}

