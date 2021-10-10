<?php

if(isset($_GET['q'])) {

	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));

}
else {

	$q="";

}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<title>Movie - Cragglist.com</title>
<meta name="author" content="cragglist.com">
<meta name="description" content="free calculator/meta search engine salution for every one easy an da quick instulation">
<meta name="keywords" content="calculator,meta search engine, free salution, all-in one calculation, fast and easy instulation">
<link href="<?=$site_url?>/files/css/style.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>