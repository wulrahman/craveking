<?php

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

if($user->admin ==1) {

	$type=$_GET['type'];

	$id=intval($_GET['id']);

	if($type == "home") {

		require_once("auth/home.php");

	}
	else if($type == "members") {

		if($id > 0) {

            require_once("auth/editmember.php");

		}
		else {

			require_once("auth/members.php");

		}

	}
	else if($type == "comments") {

		require_once("auth/comments.php");

	}
	else if($type == "image" && $id > 0) {

		require_once("auth/editimage.php");

	}
	else if($type == "products") {

		if($id > 0) {

            require_once("auth/editproduct.php");

		}
		else {

            require_once("auth/products.php");

		}

	}
	else if($type == "add") {

		require_once("auth/addproduct.php");

	}
	else if($type == "sales") {

		require_once("auth/sales.php");

	}
    else if($type == "topics") {

		require_once("auth/topics.php");

	}
	else if($type == "email") {

		if($id > 0) {

			require_once("auth/viewemail.php");

		}
		else {

			require_once("auth/email.php");

		}

	}
	else if($type == "404"){

		require_once("../common/pages/404.php");

	}
	else {

		require_once("auth/home.php");

	}


}
else {

	require_once("../common/pages/404.php");

}

?>
