<?php

require_once("../setting.php");
require_once("../common.php");

$type=mysqli($_GET[type]);

function news_api( $q, $page, $limit ) {
	
	$start=intval(intval($page-1)*$limit) + $limit;

	$feedurl = "http://www.faroo.com/api?q=".urlencode($q)."&start=".$start."&length=".$limit."&l=en&src=news&f=rss";
		
	$feed = new DOMDocument();
	$feed->load($feedurl);

	$items = $feed->getElementsByTagName('channel')->item(0)->getElementsByTagName('item');

	foreach($items as $item) {

  		$title = $item->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
   		$description = $item->getElementsByTagName('description')->item(0)->firstChild->nodeValue;
		$description = str_replace("<br/>", "", $description);
   		$pubDate = $item->getElementsByTagName('pubDate')->item(0)->firstChild->nodeValue;
   		$guid = $item->getElementsByTagName('guid')->item(0)->firstChild->nodeValue;
		$author = $item->getElementsByTagName('author')->item(0)->firstChild->nodeValue;
   		$pubDate = $item->getElementsByTagName('pubDate')->item(0)->firstChild->nodeValue;

   
		$results[] = array('source_url' => urlencode(htmlstring($guid)), 'title' => urlencode(htmlstring($title)), 'summary' => urlencode($description), 'publish_date' => urlencode(htmlstring($pubDate)), 'source' => urlencode(htmlstring($value->source)), 'author' => urlencode(htmlstring($author))); 
     
	}

	if(count($results) < 1) {
		
    		echo 'No Web results where found.';
			
	}
			
	return json_encode($results);
	
}

function web_api( $q, $page, $limit ) {

	foreach(explode(" ",$q) as $p) {

		if(!space($p)) {

			$check[] = $p;

			if(strlen($p) > 1 ) {

				$bold[] = $p;

			}

		}

	}

	if(count($results) < 1) {

		$url = 'https://www.cragglist.com/crawler/?q='.urlencode($q).'&limit='.$limit.'&page='.$page.'&deep_search=';

		$json = json_decode(file_get_contents($url));

		foreach ($json->results as $result) {

			$results[] = array('visibleUrl' => $result->showurl, 'url' => $result->url, 'title' => $result->title, 'abstract' => $result->abstract);

        }

			
	}

	if(count($results) < 1) {
		
    		echo 'No Web results where found.';
			
	}


	return json_encode($results);

}


if($type=="web") {

	$results = web_api(urlencode(mysqli($_GET[q])), intval($_GET[page]), intval($_GET[limit]));

}
else if($type=="news") {

	$results = news_api(urlencode(mysqli($_GET[q])), intval($_GET[page]), intval($_GET[limit]));

}

print_r($results);

mysqli_close($setting['Lid']);

?>