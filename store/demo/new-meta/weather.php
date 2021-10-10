<?php

$title="Cragglist Weather";

require_once("include/header.php");
require_once("include/search_header.php");
require_once("include/search_nav.php");

?>

	<div id="geo" class="geolocation_data"></div>
	<script type="text/JavaScript" src="<?=$site_url?>/files/js/geo.js?q=1"></script>

	<?php

	require_once('include/footer.php');

	?>

</div>
</div>
</body>
</html>