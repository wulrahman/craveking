<?php

$title="Cragglist";

if ($_GET['q']) { 

	$title .= " - ".$_GET['q']; 

}

require_once("include/header.php");

$json = $_COOKIE['carts'];

$arrays = json_decode($json, true);

$count_cart = count($arrays);

echo '<div class="search_home_search">';

$nav=array("Web","Images","Videos","News","Weather");

echo '<nav class="nav_header_search search_home_nav_with_color">
<ul>';

foreach($nav as $i) {

		echo '<li><a href="'.$search_url.'/?q='.$q.'&type='.urlencode(strtolower($i)).'">'.$i.'</a></li>';

}

echo '</ul>
</nav>';

?>
<div id="main_body">
<header id="header">
<?php

echo '<form id="header_form" action="'.$search_url.'/" method="get">
<a href="'.$site_url.'"><img id="logo" src="'.$site_url.'/files/image/new_cragglist_search_logo_main.png" alt=""/></a>
<div id="main_search">
<input name="q" type="search" placeholder="Search" autofocus id="header_search" autocomplete="off" value="'.$q.'" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" lang="en"><input type="hidden" name="type" value="'.$type.'"><input type="image" src="'.$site_url.'/files/image/search-icon-white-one-md.png" name="submit" value="Go!">
</div>
</form>';

?>

</header>
<div id="main_content">

<?php

require_once('include/footer.php');

echo '</div>
</div>
</div>
</body>
</html>';

?>