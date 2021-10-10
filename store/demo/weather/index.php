<?php

	require_once("setting.php");

	require_once("common.php");

	require_once("include/header.php");

	echo '<div class="main_weather">';

	?>

	<div id="geo" class="geolocation_data"></div>
	<script type="text/JavaScript" src="<?=$site_url?>/files/js/geo.js?q=1"></script>

	<?php

	require_once('include/footer.php');

?>

</div>
</body>
</html>