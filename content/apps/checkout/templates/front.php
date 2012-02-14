<?php
display_template("header", Null);
?>
<h1><?php L("Shop"); ?></h1>
<?php if(count($products) == 0){ ?>
<div class="alert-box warning">
	<?php L("There are no products on sale!"); ?>
</div>
<?php } else{ ?>
	<ul class="block-grid mobile four-up">
	<?php
	foreach($products as $product){
	?>
		<li><p>
			<a href="app.php?app=checkout&action=product&product=<?php echo $product->id; ?>">
				<?php
					include_once ROOT . "inc/media.php";
					display_images($product->image, 1, 140, 140);
				?>
				<?php echo $product->name; ?>
				<?php product_price($product); ?>
			</a>
		</p></li>
	<?php
	}
	?>
	</ul>
<?php
}
display_template("footer", Null);

