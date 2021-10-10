<?php

require_once("../setting.php");

require_once("../common.php");

require_once("../portable-utf8.php");

$q = mysqli($_GET['q']);

$action = mysqli($_GET['action']);

if($user->login_status == 1 && ($action == "my_post" || $action == "replys" )) {

	if($action == "my_post") {

	    require_once('auth/my_post.php');

	}
	else if($action == "replys") {

	    require_once('auth/replys.php');

	}
	else {

		header("location: ".$setting["url"]."/");

	}

}
else if($action == "view") {

    require_once('pages/view.php');

}
else if($action == "topic") {

    require_once('pages/topic.php');

}
else {

	require_once('pages/main.php');

}

?>
