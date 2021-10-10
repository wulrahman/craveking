<?php 

require_once("../setting.php");
require_once("../common.php");
require_once("../search.php");

if(isset($_POST['q'])) {
	
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_POST['q']));
	
}
else {
	
	$q="";
	
}

if(isset($q)) {

	// now have some fun with the results...
   	$page = intval($_POST['page']);

	$q=preg_replace("/[^A-Za-z0-9 ]/", "", $q);
	
	if($page=="" or $page==" ") {

			$page="1";

	}

 	image($q,$page,61);
}

?>