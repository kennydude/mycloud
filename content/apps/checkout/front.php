<?php
// Frontend for checkout

$symbol_to_text = array(
	"$" => "USD",
	"£" => "GBP"
);

function product_price($product){
	global $apps_checkout_currency;
	if($product->sale_price){
		echo "<span class=\"red radius label\">"._("ON SALE: ").$apps_checkout_currency.$product->sale_price._(" WAS ")."<strike>".$apps_checkout_currency.$product->price."</strike></span>";
	} else{
		echo $apps_checkout_currency . $product->price;
	}
}

function get_product_price($product){
	if($product->sale_price)
		return $product->sale_price;
	return $product->price;
}

function get_processors(){
	global $apps_checkout_google_enabled, $apps_checkout_paypal_enabled;
	$r = array();
	if($apps_checkout_google_enabled){
		$r["google"] = _("Google Checkout");
	} if($apps_checkout_paypal_enabled){
		$r["paypal"] = _("PayPal");
	}
	return $r;
}

function nvp($details){
	$o = '';
	foreach($details as $key => $value){
		$o .= $key . '=' . urlencode($value) . '&';
	}
	return substr($o, 0, -1);
}

switch($_GET['action']){
	case "inapp":
		// In app flow!
		start_db();
		$product = R::findOne("product", " slug = ?", array($_GET['product']));
		if(!$product->id){
			do_404(); die("Can't buy non-existant product");
		}

		if(is_logged_in()){
			$self = get_self();
			$order = R::findOne("order", " item = ? AND user = ?", array($product->id, $self->id));
			if($order->id){
				header("Location: inapp://fin?order=" . $order->id);
				die("Thanks! The application should automatically shut this page now");
			}
		}

		$payments = get_processors();
		include "templates/inapp.php";
		break;
	case "api":
		header("Content-type: application/json");
		switch($_GET['apiaction']){
			case "check":
				if($_GET['order']){
					start_db();
					$order = R::load("order", $_GET['order']);
					if(!$order->id){
						 echo '{ "status" : "bad", "error" : "Order not found" } ';
					} else{
						$product = R::load("product", $order->item);
						echo '{ "status" : "good", "order" : { "status" : "'.$order->status.'", "product_slug" : "'.$product->slug.'" } }';
					}
				} else{ echo '{ "status" : "bad", "error" : "No order selected" } '; }
				break;
			default:
				echo '{ "status" : "good" }'; break;
		}
		break;
	case "notify-google":
		// Google are notifying us of something
		include "notify_from_google.php";
		break;
	case "orders":
		require_login("app.php?app=checkout&action=orders");

		$self = get_self();
		$orders = R::find('order', " user = ? LIMIT 0, 10", array($self->id));
		
		define("PAGE_TITLE", _("Orders"));
		include "templates/orders.php";
		break;
	case "buy":
		start_db();
		$product = R::load("product", $_GET['product']);
		if(!$product->id){
			do_404(); die("Can't buy non-existant product");
		}

		// Login is required so we can track purchases
		require_login("app.php?app=checkout&action=buy&product=" . $product->id);
		$return_to = $public_addr . $_GET['returnto'];
	
		switch($_GET['processor']){
			case "paypal":
				if(!$apps_checkout_paypal_enabled){ die("Payment option not availalbe"); }
				include "buy_from_paypal.php";
				break;
			case "google":
				if(!$apps_checkout_google_enabled){ die("Payment option not availalbe"); }
				include "buy_from_google.php";
				break;
			default:
				$payments = get_processors();
				define("PAGE_TITLE", sprintf(_("You are about to buy %s"), $product->name));
				include "templates/prebuy.php";
		}
		break;
	case "product":
		start_db();
		$product = R::load("product", $_GET['product']);
		if(!$product->id){
			do_404(); die("No such product");
		}
		define("PAGE_TITLE", $product->name);
		include "templates/product.php";
		break;
	default:
		define("PAGE_TITLE", _("Shop"));
		start_db();
		$products = R::find("product", "1 LIMIT 0,30");	
		include "templates/front.php";
		break;
}
