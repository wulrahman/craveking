<?php

if($user->admin == 1) {

	mysqli_query($setting["Lid"], "INSERT INTO `products`(`draft`) VALUES ('1')");

	$id = mysqli_insert_id($setting["Lid"]);

	header("location: ".$setting["admin_url"]."/edit/".$id."");

}
else {

	require_once('../common/pages/404.php');

}

?>
