<?php

require_once("setting.php");
require_once("common.php");
require_once("search.php");

if(isset($_GET['q'])) {
	
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));
	
}
else {
	
	$q="";
	
}

$type=$_GET['type'];

if(str_replace(" ","",$q)=="") {

	if($type =="weather") {

		require_once("weather.php");

	} 
	else {

		require_once("home.php");

	}
} 
else {

	if($type=="images") {

		require_once("images.php");

	}
	else if($type=="videos") {

		require_once("videos.php");

	}
	else if($type =="news") {

		require_once("news.php");

	}
	else if($type =="weather") {

		require_once("weather.php");

	}
	else if($type =="web") {

		require_once("web.php");

	}
	else {

		require_once("web.php");

	}

}

?>