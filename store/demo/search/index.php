<?php

require_once("setting.php");

require_once("portable-utf8.php");

require_once("common.php");

require_once("search.php");

if(empty($_GET['page'])) {

	$page = 1;

}
else{

	$page = intval($_GET['page']);

}

if(isset($_GET['q'])) {

	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));

}
else {

	$q="";

}

$type=$_GET['tbm'];

if(str_replace(" ","",$q)=="") {

	if($type =="weather") {

		require_once("weather.php");

	}
	else {

		require_once("home.php");

	}

}
else {

	if($type=="isch") {

		require_once("images.php");

	}
	else if($type=="vid") {

		require_once("videos.php");

	}
	else if($type =="nws") {

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
<script>
(function(h,e,a,t,m,p) {
m=e.createElement(a);m.async=!0;m.src=t;
p=e.getElementsByTagName(a)[0];p.parentNode.insertBefore(m,p);
})(window,document,'script','https://u.heatmap.it/log.js');
</script>
