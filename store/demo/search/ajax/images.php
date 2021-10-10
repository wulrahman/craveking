<?php 

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

require_once("../search.php");

if(isset($_POST['q'])) {
	
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_POST['q']));
	
}
else {
	
	$q="";
	
}

if(isset($q)) {

	if(empty($_POST['page'])){

		$page = 1;

	}
	else{

		$page = intval($_POST['page']);

	}

	image($q,$page,61);
	
}

?>