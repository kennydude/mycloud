<?php
include ROOT . "inc/templates/admin.header.php";
?>
	<h1><?php L("Checkout Dashboard"); ?></h1>
	<h4 class="subheader">
		todo
	</h4>
	<hr/>
	<h3><?php L("Products on Sale"); ?> <a href="app.php?app=checkout&action=product" class="small blue button radius"><?php L("Sell new product"); ?></a></h3>
	<?php if(count($products) == 0){ ?>
	<div class="alert-box warning">
		<?php L("You have no products on sale!"); ?>
	</div>
	<?php } else {  ?>
		<table class="full-width">
			<thead>
				<tr><th><?php L("Product Name"); ?></th><th><?php L("Number sold"); ?></th></tr>
			</thead>
			<tbody>
				<?php foreach($products as $product){ ?>
				<tr><td><a href="app.php?app=checkout&action=product&id=<?php echo $product->id; ?>"><?php echo $product->name; ?></a></td>
					<td><?php print_r(R::getCell("SELECT COUNT(id) FROM 'order' WHERE item = ". $product->id)) ?></td></tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } ?>
<?php
include ROOT . "inc/templates/admin.footer.php";
