<?php

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

$raw_post_data = file_get_contents('php://input');

$raw_post_array = explode('&', $raw_post_data);

$myPost = array();

foreach ($raw_post_array as $keyval) {

	$keyval = explode ('=', $keyval);

	if (count($keyval) == 2) {

		$myPost[$keyval[0]] = urldecode($keyval[1]);

	}

}

$req = 'cmd=_notify-validate';

if(function_exists('get_magic_quotes_gpc')) {

	$get_magic_quotes_exists = true;

}

foreach ($myPost as $key => $value) {

	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {

		$value = urlencode(stripslashes($value));

	}
	else {

		$value = urlencode($value);

	}

	$req .= "&$key=$value";

}

if($setting["sandbox"]==1) {

	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

}
else {

	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";

}

$data['item_name'] = $_POST['item_name'];
$data['item_number'] 	= $_POST['item_number'];
$data['payment_status'] = $_POST['payment_status'];
$data['payment_amount'] = $_POST['mc_gross'];
$data['payment_currency'] = $_POST['mc_currency'];
$data['txn_id']	= $_POST['txn_id'];
$data['receiver_email'] = $_POST['receiver_email'];
$data['payer_email'] = $_POST['payer_email'];
$data['custom'] = $_POST['custom'];
$data['txn_type'] = $_POST['txn_type'];
$data['num_cart_items'] = $_POST['num_cart_items'];

$ch = curl_init($paypal_url);

if ($ch == FALSE) {

	return FALSE;

}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if($setting["sandbox"]==1) {

	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

}

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$res = curl_exec($ch);
 
if (curl_errno($ch) != 0) {

	curl_close($ch);
	exit;

}
else {

	curl_close($ch);

}

$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));

if (strcmp($res, "VERIFIED") == 0) {

	$paid="0";

	if($data['payment_status']=="Completed") {

		$paid="1";

	}
	
	if($data['txn_type']=="cart") {

		$cart="1";

	}
	
	if ($cart == 1) {

		for ($i=1; $i<=$data['num_cart_items']; $i++) {

			mysqli_query($setting["Lid"], "INSERT INTO `purchase` (`txnid`, `amount`, `user`, `status`, `product`, `paid`, `txn_type`, `cart`) VALUES (
			'".$data['txn_id']."' ,'".$_POST['mc_gross_'.$i]."' 
			,'".$data['custom']."' ,'".$data['payment_status']."' 
			,'".$_POST['item_number'.$i]."', '".$paid."'
			,'".$data['txn_type']."', '".$cart."')");

		}

	}
	else {

		mysqli_query($setting["Lid"], "INSERT INTO `purchase` (`txnid`, `amount`, `user`, `status`, `product`, `paid`, `txn_type`, `cart`) VALUES (
		'".$data['txn_id']."' ,'".$data['payment_amount']."' 
		,'".$data['custom']."' ,'".$data['payment_status']."' 
		,'".$data['item_number']."', '".$paid."'
		,'".$data['txn_type']."', '".$cart."')");

	}

    	mail('waheedpay@hotmail.co.uk', 'Verified IPN', "".http_build_query($_POST)."");

}
else if (strcmp ($res, "INVALID") == 0) { 

	mail('waheedpay@hotmail.co.uk', 'Invalid IPN', "".http_build_query($_POST)."");

}
?>