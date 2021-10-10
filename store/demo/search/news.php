<?php

$title="Cragglist News";

if ($_GET['q']) {

	$title .= " - ".$_GET['q'];

}

require_once("include/header.php");

require_once("include/search_header.php");

echo '<div class="news-primary">';

$common = extractCommonWords($q, 100);

foreach(explode(" ",$q) as $p) {

	if(!space($p)) {

		$check[] = $p;

		if(strlen($p) > 1 ) {

			$bold[] = $p;

		}

	}

}

news($q, $page, 10, $bold);

$pervious=$page-1;

$next=$page+1;

$total="100";

echo '<ul class="pagination-primary">';

$url='?q='.urlencode($q).'&tbm='.urlencode($_GET['tbm']).'&lang=en';

if ($page > 1){

	echo '<li><a href="'.$url.'&page='.$pervious.'">Previous</a></li>';
		
}
			
for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {

	echo '<li><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>';

}

echo '<li><a href="'.$url.'&page='.$next.'">Next</a></li>
</ul>';

echo '</div>';

require_once('include/footer.php');

echo '</body>
</html>';

?>