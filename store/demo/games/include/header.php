<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<title><?=$title?></title>
<meta name="description" content="<?=$description?>">
<meta name="keywords" content="<?=$keywords?>">
<link href="<?=$site_url?>/files/css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="<?=$site_url?>/files/css/style.css" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?=$site_url?>/files/js/script.js"></script>
<script type='text/javascript' src="http://api.autocompleteplus.com/js/acp.v1.57a.js"></script>
</head>
<body>
<header id="main_header">
	<a href="<?=$site_url?>/"><img id="logo" src="<?=$site_url?>/files/image/cragglist_logo.png" alt=""></a><form id="form1" name="form1" method="get" action="search.php"><input type="text" value="<?=$_GET[q]?>" placeholder="e.g Pool" name="q" autofocus="" autocomplete="off" value="" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" lang="en" id="header_input" /><input type="submit" name="button" id="header_search" value=" " /><input type="hidden" name="category" value="<?=$_GET[category]?>" /></form>
	<ul id="main_category">
	<li><a href="search.php?category=all">All</a></li>
	<?php
	$category=mysqli_query($Lid,"SELECT `name` FROM `category` ORDER BY `order` ASC");
	while($row = mysqli_fetch_object($category)){
		echo '<li><a href="search.php?category='.strtolower($row->name).'">'.$row->name.'</a></li>';
	}
	?>
	</ul>
</header>
<div id="main_body">

