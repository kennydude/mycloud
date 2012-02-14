<?php
include ROOT . "inc/templates/admin.header.php";

function s($in){
	global $product;
	if($in == $product->purchase_type){
		echo ' selected="selected"';
	}
}
?>
	<h1><?php L("Checkout Product Editor"); ?></h1>
	<?php if($_GET['updated']) { ?>
	<div class="alert-box success">
		<?php L("Product was updated!"); ?>
	</div>
	<?php } ?>
	<form method="post" enctype="multipart/form-data" action="app.php?app=checkout&action=product&id=<?php echo $_GET['id']; ?>">
		<input type="text" class="input-text full-width super-oversize" name="name" value="<?php echo $product->name; ?>" placeholder="<?php L("Product Name"); ?>" />
		<input type="text" class="input-text full-width super-oversize" name="slug" value="<?php echo $product->slug; ?>" placeholder="<?php L("Internal Name"); ?>" />
		<div class="row">
			<div class="six columns">
				<input type="text" class="input-text full-width" name="price" value="<?php echo $product->price;?>" placeholder="<?php L("Normal price"); ?>" />
			</div>
			<div class="six columns">
				<input type="text" class="input-text full-width" name="sale_price" value="<?php echo $product->sale_price;?>" placeholder="<?php L("Sale price (if on sale)"); ?>" />
			</div>
		</div>
		<textarea name="description" class="input-text full-width" rows=10 placeholder="<?php L("Description"); ?>"><?php echo $product->description; ?></textarea>
		<fieldset>
			<h5><?php L("Type of purchase"); ?></h5>
			<p><?php L("Additional options may appear when you select a different type"); ?></p>
			<select name="purchase_type">
				<option<?php s("api"); ?> value="api"><?php L("API Controlled"); ?></option>
			</select>
		</fieldset>
		<label for="uploader"><?php L("Product Image"); ?></label>
		<?php
			include ROOT . "inc/media.php";
			image_uploader($product->image);
		?>
		<br/></br/>
		<button type="submit" class="large blue button radius"><?php L("Save"); ?></button>
	</form>
<?php
include ROOT . "inc/templates/admin.footer.php";
