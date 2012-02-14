<?php
display_template("header", Null);
?>
<h3><?php printf(_("You are about to buy %s"), $product->name); ?></h3>
<h5><?php printf(_("This will cost %s"), $apps_checkout_currency.get_product_price($product)); ?></h5>
<p><?php L("To buy the item, please select the payment processor which suits you"); ?></p>

<p>
	<?php foreach($payments as $id => $name) { ?>
<a href="app.php?app=checkout&action=buy&product=<?php echo $product->id; ?>&processor=<?php echo $id; ?>" class="large blue button radius"><?php echo $name; ?></a>
	<?php } ?>
</p>

<?php
display_template("footer", Null);

