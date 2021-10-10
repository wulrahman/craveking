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

$qw=preg_replace("/[^A-Za-z0-9 ]/", "", $q);

if(isset($_GET['type'])) {

	$type=$_GET['type'];

}
else {

	$type="";

}

if($type == "image" || $type =="video" || $type =="news" || $type =="weather") {

	$button=ucfirst($type);

} 
else {

	$button="Web";

}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?=$site_url?>/files/image/favicon.ico" type="image/x-icon">
<title>Meta Search - Cragglist.com</title>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type='text/javascript'>
window.acpObj= {
        acp_searchbox_id: "search",              /* id of the search input tag    */
        acp_search_form_id: "search-form",             /* id of the search form tag     */
        acp_suggestions: "8",                     /* Number of suggestions         */
        acp_api: "http://api.autocompleteplus.com" /* AutoComplete+ web service API */
   };
</script>
<script type='text/javascript' src="http://api.autocompleteplus.com/js/acp.v1.57a.js"></script>
</head>
<body>
<nav>
<ul>
<?php
$nav=array("Web","Image","Video","News","Weather");

foreach($nav as $i) {
	echo '<li><a href="'.$site_url.'/?q='.$q.'&type='.urlencode(strtolower($i)).'">'.$i.'</a></li>';
}

?>
</ul>
  </nav>