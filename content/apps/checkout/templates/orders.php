<?php
display_template("header", Null);
?>
<h1><?php L("Orders"); ?></h1>
<table>
	<thead>
		<tr>
			<th><?php L("Product orded"); ?></th>
			<th><?php L("Status"); ?></th>
			<th><?php L("Payment Information"); ?></th>
			<th><?php L("Additional Information"); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($orders as $order){ ?>
			<tr>
				<td><a href="app.php?app=checkout&action=order&order=<?php echo $order->id; ?>"><?php
$product = R::load("product", $order->item);
if(!$product->id){
	L("No longer existant product");
} else{
	echo $product->name;
}
?></a></td>
				<td><?php
switch($order['status']){
	case "recv":
		L("Order is received and is processing"); break;
	case "invalid":
		L("Invalid (Technical Error, contact support)"); break;
	case "waiting":
		L("Waiting"); break;
	case "charging":
		L("Charging..."); break;
	case "charged":
		L("Charged"); break;
}
?></td><td>
<?php
switch($order['processor']){
	case "google":
		L("Processed by Google"); break;
}
?>
</td><td>
<?php
if($product->purchase_type){
	switch($product->purchase_type){
		case "api":
			L("The item will appear to other applications"); break;
	}
}
?>
</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php
display_template("footer", Null);

