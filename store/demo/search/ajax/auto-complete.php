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

$array = getKeywordSuggestionsFromGoogle($q);

echo json_encode($array);

?>