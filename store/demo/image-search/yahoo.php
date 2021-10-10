<?php
$start=intval(($page-1)*61);

$yahoo=gethtml('http://uk.images.search.yahoo.com/search/images?p='.$q.'&b='.$start.'');
preg_match_all('/(<li(?:[^>]*)><a(?:[^>]*)><img(?:[^>]*)+>)/',$yahoo, $yahoo_match);

if ($yahoo_match) {
	$yahoo_file = $yahoo_match[1];
	foreach($yahoo_file as $image){
	preg_match("@href=[\"']?(.[^>]*)([^\"]*)[\"']?@i",$image, $link);
	preg_match("@'aria-label=[\"']?(.[^>]*)([^\"]*)[\"']?'@i",$link[1], $title);
	preg_match("@src=[\"']?(.[^>]*)([^\"]*)[\"']?@i",$image, $image);
	$arrays[]=array('url'=>'http://images.yahoo.com'.$link[1],'src'=>$image[1],'type'=>"yahoo", 'title'=>htmlspecialchars_decode($title[1]));
	}
}
?>