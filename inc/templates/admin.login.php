<?php

include "header.php";

?>
<div class="returnto padding-top no-mobile">
<a href="<?php echo ROOT; ?>">&lt; <?php L("Homepage"); ?></a>
</div>
<div class="center">
<h1><?php L("Please login to continue"); ?></h1>
<?php
if( $openid_enabled = true ) {
	?>
	<h4><?php L("You can instantly sign in or join with these accounts"); ?></h4>
	<p>
		<a href="login.php?auth=google" class="button large radius"><?php L("Google"); ?></a>
	</p>
	<?php
}

?>
<form method="post" class="mountbox" action="login.php?returnto=<?php echo rawurlencode($_GET['returnto']); ?>">
	<?php if($error){ ?>
		<div class="alert-box error">
			<?php L("Your login details are wrong"); ?>
		</div>
	<?php }
	include "admin.loginbox.php";
	 ?>
</form>
</div>
<?php

include "footer.php";

?>
