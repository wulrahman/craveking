<?php

ob_start();

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

$q = mysqli($_GET['q']);

$action = mysqli($_GET['action']);

if(!space($q) || $action == "search") {

	require_once('pages/search.php');

}
else if($action  == "logout") {

	require_once("auth/logout.php");

}
else if($user->login_status == 1 && $action == "setting") {

	require_once("auth/setting.php");

}
else if($action  == "login") {

	require_once("auth/login.php");

}
else if($action  == "register") {

	require_once("auth/register.php");

}
else if($action  == "forgot") {

	require_once("auth/forgot.php");

}
else if($action  == "validate") {

	require_once("auth/validate.php");

}
else if($action == "privacy") {

    require_once('pages/privacy.php');

}
else if($action == "tos") {

    require_once('pages/tos.php');

}
else if($action == "ddos") {

    require_once('pages/ddos.php');

}
else if($action == "redirect") {

    require_once('pages/redirect.php');

}
else {

    require_once('pages/404.php');

}

?>
