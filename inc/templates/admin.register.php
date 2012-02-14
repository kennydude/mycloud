<?php

include "header.php";

?>
<div class="returnto padding-top no-mobile">
<a href="<?php echo ROOT; ?>admin/login.php">&lt; <?php L("Login Page"); ?></a>
</div>
<div class="center">
	<h1><?php L("Register"); ?></h1>
</div>
<div class="container">
<div class="row">
	<?php if($_GET['action'] == 'openid'){
		?>
		<div class="four columns">
			<?php global $error; if($error){ ?>
				<div class="alert-box error">
					<?php L("Your login details are wrong"); ?>
				</div>
			<?php } ?>
			<h3><?php L("Already have an account here?"); ?></h3>
			<p>
				<?php L("You can link your OpenID login and your mycloud accounts easily!"); ?>
			</p>
			<form action="register.php?action=openid" method="post">
				<?php include "admin.loginbox.php"; ?>
			</form>
		</div>
	<?php } ?>
	<div class="eight columns">
		<form action="register.php" method="post">
				TODO
		</form>
	</div>
</div>
</div>
<?php
include "footer.php";
