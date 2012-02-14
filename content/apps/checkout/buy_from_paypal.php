<?php

if(!$return_to)
	$return_to = "$public_addr/app.php?app=checkout&action=orders";

$details = array(
	"METHOD" => "SetExpressCheckout",
	"PAYMENTREQUEST_0_AMT" => get_product_price($product),
	"PAYMENTREQUEST_0_CURRENCYCODE" => $symbol_to_text[$apps_checkout_currency],
	"RETURNURL" => $return_to,
	"CANCELURL" => $return_to,
	"PAYMENTREQUEST_0_CUSTOM" => "ORDER NO",
	"VERSION" => "63.0",

	"USER" => $apps_checkout_paypal_user,
	"PWD" => $apps_checkout_paypal_password,
	"SIGNATURE" => $apps_checkout_paypal_signature
);

if($product->purchase_type == "api"){
	$details["NOSHIPPING"] = 1;
	$details["REQCONFIRMSHIPPING"] = 0;
}

$post = nvp($details);

$host = "checkout.google.com";
if($apps_checkout_sandbox)
	$host = "api-3t.sandbox.paypal.com/nvp";

$urltopost = "https://$host";
$ch = curl_init ($urltopost);

curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt ($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
//curl_setopt ($ch, CURLOPT_USERPWD, "$apps_checkout_google_id:$apps_checkout_google_key"); 

$returndata = curl_exec ($ch);
$r = parseQueryString($returndata);

header("Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=" . $r['TOKEN']);

print_r($details);

echo curl_getinfo($ch, CURLINFO_HTTP_CODE);


