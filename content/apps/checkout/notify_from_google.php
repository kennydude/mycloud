<?php
// Google notify us through this method


// Check order status

$order = $_POST['serial-number'];

ob_start();

$host = "checkout.google.com";
if($apps_checkout_sandbox)
	$host = "sandbox.google.com/checkout";

$api = "https://$host/api/checkout/v2/reportsForm/Merchant/$apps_checkout_google_id";
$post = "_type=notification-history-request&serial-number=" . $order;

$ch = curl_init ($api);

curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
curl_setopt ($ch, CURLOPT_USERPWD, "$apps_checkout_google_id:$apps_checkout_google_key"); 

$returndata = curl_exec ($ch);
echo "\n---\n";
$information = parseQueryString($returndata);


$type = $information['_type'];
$order_number = $information['google-order-number'];

switch($type){
	case "new-order-notification":
		echo "\nNew Order Notification\n";
		
		// We need to extract our note to know what this order is
		$begin = "<merchant-note>ON:#";
		$end = "</merchant-note>";
		$local_order = urldecode($information['shopping-cart.merchant-private-data']);
		
		$local_order = substr($local_order, strpos($local_order, $begin) + strlen($begin));
		$local_order = substr($local_order, 0, strpos($local_order, $end));

		echo "Local Order Number: '$local_order'\n";
		start_db();
		
		$order = R::load("order", $local_order);
		$order->status = "recv";
		$order->processor_number = $order_number;
		R::store($order);
		// Now our database is up to date!
		echo "Database up to date with order info\n";
		break;
	case "order-state-change-notification":
		echo "Order state change\n";
		echo $information['new-financial-order-state'] . "\n";
		echo $information['new-fulfillment-order-state'] . "\n";
		
		// TODO: Load item information and only do this stage if needed by the type of item
		// For example: Hand made items may be dispatched differently
		if( $information['new-financial-order-state'] == "CHARGEABLE" and $information['new-fulfillment-order-state'] == "NEW"){
			echo "\nOrder is chargable we are going to do that now\n";
			$details = <<<EOF
<charge-and-ship-order xmlns='http://checkout.google.com/schema/2' google-order-number='$order_number'>
</charge-and-ship-order>
EOF;
			$api = "https://$host/api/checkout/v2/request/Merchant/$apps_checkout_google_id";
			$ch = curl_init ($api);

			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $details);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
			curl_setopt ($ch, CURLOPT_USERPWD, "$apps_checkout_google_id:$apps_checkout_google_key"); 

			$returndata = curl_exec ($ch);
			$response = new SimpleXMLElement($returndata);
			print_r($response);
			echo $returndata . "\nResponse from charging\n";
		
			start_db();
			$order = R::findOne("order", "processor_number = ?", array($order_number));
			$order->status = "charging";
			R::store($order); 
		} else if($information['new-financial-order-state'] == "CHARGED"){
			start_db();
			$order = R::findOne("order", "processor_number = ?", array($order_number));
			$order->status = "charged";
			R::store($order); 
		}
	default:
		echo "\nNotification type: $type";
}

$f = ob_get_contents();
ob_end_clean();


file_put_contents("log.txt", $f, FILE_APPEND);
