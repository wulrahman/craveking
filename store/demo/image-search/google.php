<?php

$start=intval(($page-1)*20);

$google=gethtml('http://www.google.co.uk/search?tbm=isch&q='.$q.'&start='.$start.'&sout=1');
preg_match_all('@<td style="width:25%;word-wrap:break-word">(.*?)</td>@i',$google, $google_match);

if($google_match) {

	$google_file = $google_match[1];

	foreach($google_file as $image) {

		preg_match("@href=[\"']?([^\"]*)[\"']?@i",$image, $link);
		preg_match('@</cite><br>(.*?)<br>@i',$image, $title);
		preg_match("@src=[\"']?([^\"]*)[\"']?@i",$image, $image);
		$arrays[]=array('url'=>"http://www.google.com".$link[1],'src'=>$image[1],'type'=>"google", 'title'=>$title[1]);

	}

}

?>