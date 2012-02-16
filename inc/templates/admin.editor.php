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
<?php
include "admin.footer.php";
