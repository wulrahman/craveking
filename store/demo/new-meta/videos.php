<?php

$title="Cragglist Video";

if ($_GET['q']) {

	$title .= " - ".$_GET['q'];

}

require_once("include/header.php");
require_once("include/search_header.php");
require_once("include/search_nav.php");
	
$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
if($page=="" or $page==" ") {

	$page="1";

}
	
echo "<script>
var type = 'videos';
var q = '".$q."';
</script>";

echo '<script src="'.$search_url.'/files/js/script.js"></script>
<ol class="result_mains" id="result_mains"></ol>
<div class="animation_image" style="display:none" align="center"><img src="'.$search_url.'/files/image/ajax-loader.gif"></div>
<br>
</div>';

require_once('include/footer.php');

echo '</div>
</body>
</html>';

?>