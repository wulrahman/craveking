<?php

//https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/formbasics/

if($setting["sandbox"] == 1) {

	$paypal['business'] = "waheedpay2@hotmail.co.uk";

}
else {

	$paypal['business'] = "payment@cragglist.com";

}

$paypal['site_url'] = $setting["url"];

//$paypal[image_url]="";

$paypal['success_url'] = $setting["url"]."/paypal/success.php";

$paypal['cancel_url'] = $setting["url"]."/paypal/cancelled.php";

$paypal['notify_url'] = $setting["url"]."/paypal/ipn.php";

$paypal['return_method'] = "1"; //1=GET 2=POST

$paypal['currency_code'] = "USD"; //[USD,GBP,JPY,CAD,EUR]

$paypal['lc'] = "GB";

$paypal['post_method'] = "fso"; //fso=fsockopen(); curl=curl command line libCurl=php compiled with libCurl support

//$paypal['curl_location'] = "/usr/local/bin/curl";

$paypal['bn'] = "toolkit-php";

if(isset($_POST[carts])) {

	$paypal['cmd']="_cart";

}
else {

	$paypal['cmd']="_xclick";

}

//Payment Page Settings

$paypal['display_comment'] = "0"; //0=yes 1=no

$paypal['comment_header'] = "Comments";

$paypal['continue_button_text'] = "Continue >>";

$paypal['background_color'] = ""; //""=white 1=black

$paypal['display_shipping_address'] = ""; //""=yes 1=no

$paypal['display_comment'] = "1"; //""=yes 1=no

//Product Settings

$paypal['edit_quantity'] = ""; //1=yes ""=no

$paypal['tax'] = $_POST['tax'];

if(isset($_POST['carts'])) {

	$json = $_COOKIE['carts'];

	$arrays = json_decode($json, true);

	foreach($arrays as $key => $value) {

		$query = mysqli_query($setting["Lid"],'SELECT `name`, `price`, `id` FROM `products` WHERE `id`="'.$value.'" AND `free`="0"');

		$row = mysqli_fetch_object($query);

		$key = intval($key+1);

		$paypal['item_name_'.$key] = $row->name;

		$paypal['item_number_'.$key] = $row->id;

		$paypal['amount_'.$key] = $row->price;

		$paypal['quantity_'.$key] = "1";


	}

	$where='`id`="'.implode($arrays, '" OR `id`="').'" AND';

	$total_price = mysqli_query($setting["Lid"],'SELECT SUM(`price`) as `total` FROM `products` WHERE '.$where.' `free`="0"');

	$total_price = mysqli_fetch_object($total_price);

	$paypal['shopping_url'] = $setting["url"];

	$paypal['upload'] = "1";

}
else {

	$paypal['quantity'] = $_POST['quantity'];

	$paypal['invoice'] = $_POST['invoice'];

	$paypal['item_name'] = $row->name;

	$paypal['amount'] = $row->price;

	$paypal['item_number'] = $row->id;

}

//User Information

$paypal['custom'] = $user->id;

$paypal['firstname'] = $user->firstname;

$paypal['lastname'] = $user->lastname;

$paypal['address1'] = $user->street;

$paypal['city'] = $user->city;

$paypal['state'] = $user->state;

$paypal['zip'] = $user->zip;

$paypal['email'] = $user->email;

//Shipping and Taxes

$paypal['shipping_amount'] = $_POST['shipping_amount'];

$paypal['shipping_amount_per_item'] = "";

$paypal['handling_amount'] = "";

?>
