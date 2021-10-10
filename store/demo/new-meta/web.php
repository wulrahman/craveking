<?php

$title="Cragglist Web";

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
		
echo '<div class="results_container" id="web_results_margin_display_inline">';

web($q,$page,12);

require_once('include/footer.php');

echo '</div>
</div>
</body>
</html>';

?>