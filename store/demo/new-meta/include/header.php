<?php

if (isset($_GET['safe']) && $_GET['safe'] == '0') {
	
	setcookie("safe", "", time()-60*60*24*100, "/");
	
}
else if (isset($_GET['safe']) && $_GET['safe'] == '1') {
	
	setcookie("safe", "1", time()+60*60*24*100, "/");
	
}

if(isset($_GET['q'])) {
	
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));
	
}
else {
	
	$q="";
	
}

if(isset($_GET['type'])) {
	
	$type=$_GET['type'];
	
}
else {
	
	$type="";
	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1,width=device-width">
<link rel="shortcut icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<title><?=$title?></title>
<meta name="description" content="<?=$description?>">
<meta name="keywords" content="<?=$keywords?>">
<link href="<?=$search_url?>/files/css/style.css?=5" rel="stylesheet">
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
var siteurl="<?=$site_url?>";var searchurl="<?=$search_url?>";
</script>
<script type='text/javascript' src="//api.autocompleteplus.com/js/acp.v1.57a.js"></script>
</head>

<body>