<?php

$title="Cragglist News";

if ($_GET['q']) {

	$title .= " - ".$_GET['q'];

}

require_once("include/header.php");
require_once("include/search_header.php");
require_once("include/search_nav.php");

echo '<div class="results_container">';

$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
if($page=="" or $page==" ") {

	$page="1";

}

news($q,$page,21);

echo '</div>';

require_once('include/footer.php');

echo '</div>
</div>
</body>
</html>';

?>