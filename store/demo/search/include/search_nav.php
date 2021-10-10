<?php

$main_nav_nav=array(1 => "web","isch","vid","nws", "weather");

$nav=array(1 => "Web","Images","Videos","News", "Weather");

foreach($nav as $key => $i) {

	echo '<li><a href="'.$setting["search_url"].'/?q='.urlencode($q).'&tbm='.$main_nav_nav[$key].'">'.$i.'</a></li>';
	
}

?>