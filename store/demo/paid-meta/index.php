<?php

require_once("functions.php");
require_once("files/include/header.php");

// now have some fun with the results...
if($type =="weather") {

	require_once('weather.php');

}
else if(str_replace(" ","",$qw)=="") { 
	
	require_once('main.php');

}

else if(isset($q)) {

	require_once('results.php');	

}
?>
</body>
</html>