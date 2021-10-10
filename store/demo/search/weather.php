<?php

$title="Cragglist Weather";

require_once("include/header.php");
require_once("include/search_header.php");

?>

<div class="home_page_main">

	<div id="geo" class="geolocation_data"></div>
	<script type="text/JavaScript" src="<?=$setting["search_url"]?>/files/js/geo.js?q=1"></script>

	<?php

	require_once('include/footer.php');

	?>

</div>

</div>
</div>
</body>
</html>