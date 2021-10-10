<?php

$title="Cragglist Webs";

if ($_GET['q']) {

	$title .= " - ".$_GET['q'];

}

require_once("include/header.php");
require_once("include/search_header.php");

echo '<div class="web-primary">';

?>

<script>
  (function() {
    var cx = '002784741030688977417:7gxj0gfxnk0';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>

<?php

echo '</div>';

$rightside = company_info($q);

if(!space($rightside)) {

	echo '<div class="info_web-primary">'.$rightside.'</div>';

}

require_once('include/footer.php');

echo '</body>
</html>';

?>