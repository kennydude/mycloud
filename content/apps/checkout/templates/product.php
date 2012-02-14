<?php
display_template("header", Null);
?>
<h1><?php echo $product->name; ?> <?php product_price($product); ?></h1>
<p><?php echo $product->description; ?></p>
<?php
	include ROOT . "inc/media.php";
	display_images($product->image, 1, 140, 140);
?>
<p><a href="app.php?app=checkout&action=buy&product=<?php echo $product->id; ?>" class="large blue button radius"><?php L("Buy now"); ?></a></p>
<?php
display_template("footer", Null);

