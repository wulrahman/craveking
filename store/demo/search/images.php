<?php

$title="Cragglist Images";

if ($_GET['q']) { 

	$title .= " - ".$_GET['q']; 

}

require_once("include/header.php");

require_once("include/search_header.php");
		
$common = extractCommonWords($q, 100);

foreach(explode(" ",$q) as $p) {

	if(!space($p)) {

		$check[] = $p;

		if(strlen($p) > 1 ) {

			$bold[] = $p;

		}

	}

}

?>

<script src="<?=$setting["search_url"]?>/files/js/jquery.js?q=1"></script>
<script src="<?=$setting["search_url"]?>/files/js/script.js?q=1"></script>
<script>
var type="images";
var q="<?=$q?>";
var b = "<?=$setting["search_url"]?>/ajax/" + type + ".php";
var c = "image-primary";

</script>
<ol class="image-primary" id="image-primary"></ol>
<div class="animation_image" style="display:none" align="center">
<img src="<?=$setting["search_url"]?>/files/image/ajax-loader.gif">
</div>

<?php

require_once('include/footer.php');

?>

</body>
</html>
