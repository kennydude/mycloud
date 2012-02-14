<?php
// Admin functions for mycloud checkout

switch($_GET['action']){
	case "product":
		start_db();
		
		if($_GET['id']){
			$product = R::load("product", $_GET['id']);
		}
		
		if($_POST){
			require(ROOT . "inc/media.php");
			if(!$_GET['id']){
				$product = R::dispense("product");
			}
			$product->name = $_POST['name'];
			$product->price = $_POST['price'];
			$product->description = $_POST['description'];
			$product->image = image_uploader_handler($product->image);
			$product->sale_price = $_POST['sale_price'];
			$product->purchase_type = $_POST['purchase_type'];
			$product->slug = $_POST['slug'];
			
			R::store($product);
			header("Location: app.php?app=checkout&action=product&id=" . $product->id . "&updated=true");
			exit;
		}
		define("PAGE_TITLE", _("Checkout Product Editor"));
		require("templates/editor.php");
		break;
	default:
		start_db();
		
		$products = R::find("product", "1 LIMIT 0,30");		
		
		define("PAGE_TITLE", _("Checkout Dashboard"));
		require("templates/dashboard.php");
}
