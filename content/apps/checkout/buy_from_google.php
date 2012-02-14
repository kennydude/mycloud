<?php
// Purchase via Google Checkout

// Create a tempoary order #id
start_db();
$order = R::dispense("order");
$order->status = "invalid";
R::store($order);

$order_number = $order->id;

if(!$return_to)
	$return_to = "$public_addr/app.php?app=checkout&action=orders";

$delivery_methods = array(
	"api" => "<digital-content>
            <display-disposition>PESSIMISTIC</display-disposition>
            <description>
              ". _("Please continue back to our website to finish the order") ."
            </description>
			<url>".htmlspecialchars($return_to)."</url>
          </digital-content>"
);

$product_name = $product->name;
$product_description = $product->description;
$currency = $symbol_to_text[$apps_checkout_currency];
$price = get_product_price($product);

$delivery = $delivery_methods[$product->purchase_type];

$cart = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">
	<shopping-cart>
		<items>
			<item>
			<item-name>${product_name}</item-name>
			<item-description>$product_description</item-description>
			<unit-price currency="$currency">$price</unit-price>
			<quantity>1</quantity>
			$delivery
			</item>
		</items>
		<merchant-private-data>
		   <merchant-note>ON:#$order_number</merchant-note>
		</merchant-private-data>
	</shopping-cart>
	<checkout-flow-support>
		<merchant-checkout-flow-support/>
	</checkout-flow-support>
</checkout-shopping-cart>
EOF;

$host = "checkout.google.com";
if($apps_checkout_sandbox)
	$host = "sandbox.google.com/checkout";


// okay so we need to post to google
// record serial number in user storage
// then implement the return

// POSTING! :D
$urltopost = "https://$host/api/checkout/v2/merchantCheckout/Merchant/$apps_checkout_google_id";
$ch = curl_init ($urltopost);

curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $cart);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
curl_setopt ($ch, CURLOPT_USERPWD, "$apps_checkout_google_id:$apps_checkout_google_key"); 

$returndata = curl_exec ($ch);

$response = new SimpleXMLElement($returndata);

if($response->{'error-message'}){
	if($apps_checkout_sandbox)
		echo "<br/>" . $response->{'error-message'};
	die("Tempoary error with Google Checkout. Sorry!");
} else{
	$self = get_self();
	
	$order->user = $self->id;
	$order->processor = "google";
	$order->processor_number = "" . $response['serial-number'];
	$order->status = "waiting";
	$order->item = $product->id;
	R::store($order);
	
	header("Location: " . $response->{'redirect-url'});
	die("Sending you to Google...");
}

