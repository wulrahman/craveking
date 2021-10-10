<?php

if($user->admin == 1) {

	mysqli_query($setting["Lid"], "INSERT INTO `games`(`published`) VALUES ('0')");

	$id = mysqli_insert_id($setting["Lid"]);

	header("location: ".$setting["admin_url"]."/editgame/".$id."");

}
else {

	require_once('../common/pages/404.php');

}

?>
