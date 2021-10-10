<?php

$nav=array("Web","Images","Videos","News","Weather");

echo '<nav class="nav_header_search">
<ul>';

foreach($nav as $i) {

		echo '<li><a href="'.$search_url.'/?q='.urlencode($q).'&type='.urlencode(strtolower($i)).'">'.$i.'</a></li>';

}

echo '</ul>
</nav>';
?>