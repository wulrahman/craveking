<div id="main_body">
<header id="header">
<?php

$nav=array("Web","Images","Videos","News","Weather");
		
echo '<form id="header_form" action="'.$search_url.'/" method="get">
<a href="'.$site_url.'"><img id="logo" src="'.$site_url.'/files/image/new_cragglist_search_logo_main.png" alt=""/></a>
<div id="main_search">
<input name="q" type="search" placeholder="Search" autofocus id="header_search" autocomplete="off" value="'.$q.'" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" lang="en"><input type="hidden" name="type" value="'.$type.'"><input type="image" src="'.$site_url.'/files/image/search-icon-white-one-md.png" name="submit" value="Go!">
</div>
</form>';

?>
</header>
<div id="main_content">
