<?php
function sidebar(){
	?>
	<div class="panel">
		<h5><?php L("Syntax"); ?></h5>
		<p>Exa</p>
	</div>
	<?php
}

include "admin.header.php";

$form->render();

if($_GET['updated']){
	?>
<div class="alert-box success">
	<?php L("Post was updated"); ?>
</div>
	<?php
}
?>
<!--
	old
<form method="post" class="nice" action="post.php?id=<?php echo $_GET['id']; ?>">
<input type="text" name="title" class="super-oversize input-text" placeholder="<?php L("Title"); ?>" value="<?php echo $post->title; ?>" />

<textarea rows=3 class="full-width large" name="excerpt" placeholder="<?php L("Excerpt"); ?>"><?php echo $post->excerpt; ?></textarea>
<textarea rows=10 class="full-width" name="body" placeholder="<?php L("Full body"); ?>"><?php echo $post->body; ?></textarea>

<input type="text" name="publish" class="input-text full-width" placeholder="<?php L('Date to publish'); ?>" />

<input type="text" name="tags" class="input-text full-width" placeholder="<?php L("comma, seperated, tags"); ?>" value="<?php echo @implode(", ", R::tag($post)); ?>" />


<button type="submit" class="large blue button radius"><?php L("Save"); ?></button>
</form>
-->
<?php
include "admin.footer.php";
