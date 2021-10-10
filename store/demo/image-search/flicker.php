<?php

$start=intval(($page-1)*20);

$flicker=gethtml('https://www.flickr.com/search/?q'.$q.'&page='.$start.'');

preg_match_all('/class="dg_u" class="ui-display ui-display-tile  " data-layout-element="display" style="">(?:[^>]*)</figure>/i',$flicker, $flicker_match);

if($flicker_match) {

	$flicker_file = $flicker_match[1];

	foreach($flicker_file as $image){

		preg_match("@href=[\"']?([^\"]*)[\"']?@i",$image, $link);
		$link ="https://www.flickr.com".$link[1]."sizes/l/";
		preg_match("@src=[\"']?([^\"]*)[\"']?@i",$image, $image);
		$image= $image[1];

	}

}

?>