<?php

require_once("../setting.php");

require_once("../common.php");

require_once("../portable-utf8.php");

$q = mysqli($_GET['q']);

$action = mysqli($_GET['action']);

if($user->login_status == 1 && ($action == "purchased" || $action == "download" )) {

	if($action == "purchased") {

	    require_once('auth/purchased.php');

	}
	else if($action == "download") {

	    require_once('auth/download.php');

	}
	else {

		header("location: ".$setting["store_url"]."/");

	}

}
else if($action == "product") {

    require_once('pages/product.php');

}
else if($action == "demo") {

    require_once('pages/demo.php');

}
else if($action == "cart") {

    require_once('pages/cart.php');

}
else if($action == "cart") {

    require_once('pages/cart.php');

}
else {

	require_once('pages/main.php');

}

?>
