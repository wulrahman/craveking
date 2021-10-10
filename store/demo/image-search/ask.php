<?php
$start=intval(($page-1)*20);

$ask=gethtml('http://uk.ask.com/pictures?q='.$q.'&page='.$start.'');
preg_match_all('/(<li(?:[^>]*)>(.*?)<a(?:[^>]*)>(.*?)<img(?:[^>]*)+>)/' ,$ask, $ask_match);

if($ask_match) {
	$ask_file = $ask_match[1];
	foreach($ask_file as $image){
		echo $image[1];
		preg_match("@href=[\"']?([^\"]*)[\"']?@i",$image, $link);
		$link=$link[1];
		preg_match("@src=[\"']?([^\"]*)[\"']?@i",$image, $image);
		$image= $image[1];
	}
}
?>