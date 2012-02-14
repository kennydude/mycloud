<?php
display_template("header", Null);
?>
<h3><?php printf(_("You have been requested to pruchase %s"), $product->name); ?></h3>
<h4><?php printf(_("This will cost %s"), $apps_checkout_currency.get_product_price($product)); ?></h4>

<hr/>

<div class="row">
<div class="six columns">
	<h5><?php L("If you have already bought the item, all you need to do is login"); ?></h5>
	<a href="admin/login.php?returnto=<?php echo urlencode("app.php?app=checkout&action=inapp&product=" . $product->slug); ?>" class="large blue button radius"><?php L("Login"); ?></a>
</div>
<div class="six columns">
	<h5><?php L("Or login and purchase using one of these payment processors"); ?></h5>
	<p>
		<?php foreach($payments as $id => $name) { ?>
	<a href="app.php?app=checkout&action=buy&product=<?php echo $product->id; ?>&processor=<?php echo $id; ?>&returnto=<?php echo urlencode("app.php?app=checkout&action=inapp&product=" . $product->slug); ?>" class="large blue button radius"><?php echo $name; ?></a>
		<?php } ?>
	</p>
</div>
</div>

<?php
display_template("footer", Null);

