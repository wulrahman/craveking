<?php
$start=intval(($page-1)*36);

$bing=gethtml('http://www.bing.com/images/search?q='.$q.'&first='.$start.'&go=&qs=n&form=QBIR');
preg_match_all('/(<div class="dg_u"(?:[^>]*)+><a(?:[^>]*)><img(?:[^>]*)+>)/i',$bing, $bing_match);

if($bing_match) {
	$bing_file = $bing_match[1];
	foreach($bing_file as $image){
		preg_match("@t3=[\"']?([^\"]*)[\"']@i",$image, $link);
		preg_match("@t1=[\"']?([^\"]*)[\"']@i",$image, $title);
		preg_match("@src2=[\"']?([^\"]*)[\"']?@i",$image, $image);
		$arrays[]=array('url'=>'http://'.$link[1],'src'=>$image[1],'type'=>"bing", 'title'=>$title[1]);
	}
}
?>