<?php

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

	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

		<head>

			<meta charset="utf-8">

			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

			<meta name="viewport" content="initial-scale=1, maximum-scale=1,width=device-width">

			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

			<link rel="shortcut icon" href="<?=$setting["search_url"]?>/files/image/favicon.ico" type="image/x-icon">

			<link rel="icon" href="<?=$setting["search_url"]?>/files/image/favicon.ico" type="image/x-icon">

			<link rel="stylesheet" href="<?=$setting["search_url"]?>/files/css/main.css?q=8">

			<title><?=$title?></title>

			<meta name="description" content="<?=$description?>">

			<meta name="keywords" content="<?=$keywords?>">

			<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  
			<script src="<?=$setting["search_url"]?>/files/js/auto-complete.js?q=2"></script>

			<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->

			<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->

			<script>

  				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  				ga('create', 'UA-40564340-2', 'auto');
  				ga('send', 'pageview');
			
			</script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-0418246918555141",
    enable_page_level_ads: true
  });
</script>

		</head>

	<body>

		<div id="bodymain">