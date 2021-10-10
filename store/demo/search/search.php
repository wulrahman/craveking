<?php

function getKeywordSuggestionsFromGoogle($query) {

    	$suggestions = array(); 
	
	$url = 'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($query);

	$data = url_info($url);

	$data = json_decode($data['response'], true);

    	if ($data !== null) {

        	$suggestions = $data[1];

    	}

    	return $suggestions;
}

function map($location, $zoom) {

	global $setting;

	echo '<script src="http://maps.googleapis.com/maps/api/js?key='.$setting["google_map_key"].'"></script>

    	<script type="text/javascript">

	function initialize() {

  		var mapProp = {
    			center: new google.maps.LatLng('.$location.'),
    			zoom:'.$zoom.',
    			mapTypeId: google.maps.MapTypeId.ROADMAP
  		};

		var map = new google.maps.Map(document.getElementById("mapcanvas"),mapProp);
	}

	google.maps.event.addDomListener(window, \'load\', initialize);

    	</script>';


}

function movie($q) {

	$url = 'http://www.omdbapi.com/?t='.urlencode($q).'&y=&plot=short&r=json';

	$data = url_info($url);

	$details = json_decode($data['response']);

	$return = "";

	if($details->Response=='True') {

		if($details->Poster !='N/A')	{

			$return .= '<a href="http://www.imdb.com/title/'.$details->imdbID.'/"><img src="'.$details->Poster.'"></a><br>';

		}

    		$return .= '<a href="http://www.imdb.com/title/'.$details->imdbID.'/">'.$details->Title.' ('.$details->Year.')</a><br>';

		if($details->Rated !='N/A') {

    			$return .= 'Rated : '.$details->Rated.'<br>';

		}

		if($details->Released !='N/A')	{

   			$return .= 'Released Date : '.$details->Released.'<br>';

		}

		if($details->Runtime !='N/A') {

			$return .= 'Runtime : '.$details->Runtime.'<br>';

		}
    	
		if($details->Genre !='N/A')	{

    			$return .= 'Genre : '.$details->Genre.'<br>';

		}

		if($details->Director !='N/A')	{

    			$return .= 'Director : '.$details->Director.'<br>';

		}
	
		if($details->Writer !='N/A') {

    			$return .= 'Writer : '.$details->Writer.'<br>';

		}

		if($details->Actors !='N/A') {

			$return .= 'Actors : '.$details->Actors.'<br>';

		}
    	
		if($details->Plot !='N/A') {

			$return .= 'Plot : '.$details->Plot.'<br>';

		}
    	
		if($details->Language !='N/A')	{

    			$return .= 'Language : '.$details->Language.'<br>';

		}

		if($details->Country !='N/A') {

    			$return .= 'Country : '.$details->Country.'<br>';

		}

		if($details->Awards !='N/A') {

    			$return .= 'Awards : '.$details->Awards.'<br>';

		}

		if($details->Metascore !='N/A') {

    			$return .= 'Metascore : '.$details->Metascore.'<br>';

		}

		if($details->imdbRating !='N/A') {

    			$return .= 'IMDB Rating : '.$details->imdbRating.'<br>';

		}

		if($details->imdbVotes !='N/A') {

    			$return .= 'IMDB Votes : '.$details->imdbVotes.'<br>';

		}

	}

	return $return;

}

function company_info($q) {

	$url = "https://en.wikipedia.org/w/api.php?action=query&titles=".$q."&format=json&exintro=1&rvsection=0&rvparse=1&prop=revisions&rvprop=content&redirects";

	$data = url_info($url);

	$json = json_decode($data['response']);

	$pageid = key($json->query->pages);

	$info = $json->query->pages->$pageid->revisions[0];

	foreach ($info as $key => $main) {

		$html .= $main;

	}

	$return = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);

	/*preg_match_all('/(<table class="infobox vcard".*?<\/table>)/si', $html, $infobox);

	preg_match_all('/(<div role="note".*?<\/div>)/si', $html, $notes);

	preg_match_all('/(<p>.*?<\/p>)/si', $html, $other);

	$return = implode(" ", $infobox['1']);*/

	if(!space($return)) {

		$pattern = '/(<tr><th scope="row">Coordinates.*?<\/tr>)/si';

		preg_match_all($pattern, $return, $coordinates);

		if(COUNT($coordinates['1']) > 0) {

			preg_match_all('/<span class="latitude">(.*?)<\/span>/si', $coordinates['1']['0'], $latitude);
			
			preg_match_all('/<span class="longitude">(.*?)<\/span>/si', $coordinates['1']['0'], $longitude);

			$location = $latitude['1']['0'].", ".$longitude['1']['0'];

			$latitude = explode(" ", strtolower(preg_replace("/[^a-zA-Z0-9]+/", " ", $latitude['1']['0'])));

			$longitude = explode(" ", strtolower(preg_replace("/[^a-zA-Z0-9]+/", " ", $longitude['1']['0'])));

			$latitude = DMS2Decimal($latitude['0'], $latitude['1'], $latitude['2'], $latitude['3']);

			$longitude = DMS2Decimal($longitude['0'], $longitude['1'], $longitude['2'], $longitude['3']);

			$location = $latitude.", ".$longitude;

			$zoom = 15;

			$map = map_image($location, $zoom);

			$return = str_ireplace($coordinates['1']['0'], $map, $return);

		}

		$tag_array = array('<td', '<table', '<tr', '<tbody', '<th', '</td', '</table', '</tr', '</tbody', '</th', '<div class="row" colspan="2"', '<div class="mbox-small plainlinks sistersitebox"');

		$replace_array = array('<div class="main"', '<div', '<div class="mainrow" ', '<div', '<div class="row"', '</div', '</div', '</div', '</div', '</div', '<div', '<div');

		$return = str_ireplace($tag_array, $replace_array, $return);

	}


	if(space($return)) {

		$return = implode(" ", $other['1']);
				
	}

	if(space($return)) {

		$return = implode(" ", $notes['1']);

	}

	if(space($return)) {

		$return = movie($q);

	}

	$return = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $return);

	return $return;

}

function weather($geodata) {

	global $setting;

	$num_of_days=5;

	$url='http://api2.worldweatheronline.com/free/v2/weather.ashx?key='.$setting["worldweatheronline"].'&q='.$geodata['town_city'].'&num_of_days='.intval($num_of_days).'&format=json';

	$data = url_info($url);

	$json = json_decode($data['response']);

	echo '<div class="weather_body">
	<div class="weather_main">
	<div class="page_title">Weather at '.$json->data->request[0]->query.'</div>
	<div class="weather_results_main">
	<img class="thumbnail_weather_main" src="'.$json->data->current_condition[0]->weatherIconUrl[0]->value.'"></img><div class="weather_info">Current wind speed is '.$json->data->current_condition[0]->windspeedMiles.' mph blowing to '.$json->data->current_condition[0]->winddir16Point.'</br>It\'s a '.
	$json->data->current_condition[0]->weatherDesc[0]->value.' weather today</div></div><ol class="weather_mains">';
	
	foreach ($json->data->weather as $key => $weather) {
		
		echo '<li class="weather_results">
		<img class="thumbnail_weather" src="'.$weather->hourly[0]->weatherIconUrl[0]->value.'"></img>
		<div class="weather_info_li">
		'.$weather->date.' wind speed was '.$weather->hourly[0]->windspeedMiles.' mph blowing to '.$weather->hourly[0]->winddir16Point.'</br>'.
		$weather->hourly[0]->weatherDesc[0]->value.' weather on '.$weather->date.'
		</div>
		</li>';
		
	}
	
	echo "</ol>
	</div>
	</div>";
	
}

function image($q, $page, $limit) {

	global $setting;

	if($page == 0) {

		$limit = 30;

		$start = round((($page-1)*($limit)));

		$url =  'https://api.datamarket.azure.com/Bing/Search/v1/Image?Query=%27'.urlencode($q).'%27&$skip='.$start.'&$format=json&$top='.$limit.'';

		$data = url_info($url, 2, $setting["bing_key"], $setting["bing_key"]);

		$json = json_decode($data['response']);

		foreach ($json->d->results as $result) {

			$array[]=array('image' => $result->Thumbnail->MediaUrl, 'title' => urldecode($result->Title), 'url' => $result->SourceUrl);

		}


	}


	if($page != 0) {

		$limit = 30;

		$start = round((($page-1)*($limit)));

		$url = "http://www.faroo.com/api?q=".urlencode($q)."&start=".$start."&length=".$limit."&src=images&i=true&key=".$setting["faroo_key"]."&f=json";

		$data = url_info($url);

		$json = json_decode($data['response']);

		foreach ($json->results as $result) {

			if($result->iurl != "") {

				$array[]=array('image' => $result->iurl, 'title' => urldecode($result->title), 'url' => $result->url);

			}

        }

	}

	if($page != 0) {

		$limit = 6;

		$start = round((($page-1)*($limit)));

		$url = "http://api.pixplorer.co.uk/image?word=".$q."&amount=".$limit."";

		$data = url_info($url);
	
		$json = json_decode($data['response']);

		foreach ($json->images as $result) {

			$array[]=array('url' => $result->imageurl, 'image' => $result->imageurl, 'title' => "");
	
		}

	}
    
    if($page == 0) {
    
        $start=intval(($page-1)*61);

        $url='http://uk.images.search.yahoo.com/search/images?p='.$q.'&b='.$start;

        $data = url_info($url);

        $file = $data['response'];

        preg_match_all('/(<li(?:[^>]*)><a(?:[^>]*)><img(?:[^>]*)+>)/',$file, $match);

        if($match) {

            $file = $match[1];

            foreach($file as $image) {

                preg_match("@href=[\"']?(.[^>]*)([^\"]*)[\"']?@i",$image, $link);

                $link = "http://images.yahoo.com".$link[1];

                preg_match("@'aria-label=[\"']?(.[^>]*)[\"']?'@i",$image, $title);

                $title = $title[1];

                preg_match("@src=[\"']?(.[^>]*)[\"']?' alt='@i",$image, $image);
                $image = $image[1];

                $array[] = array('url' => $link, 'title' => htmlspecialchars_decode($title), 'image' => $image);

            }


        }
    
    }
    
    $start=intval(($page-1)*20);

    $url='http://www.google.co.uk/search?tbm=isch&q='.$q.'&start='.$start.'&sout=1';
        
    $data = url_info($url);
	
    $file = $data['response'];
    
    preg_match_all('@<td style="width:25%;word-wrap:break-word">(.*?)</td>@i',$file, $google_match);

    if($google_match) {

        $google_file = $google_match[1];

        foreach($google_file as $image) {

            preg_match("@href=[\"']?([^\"]*)[\"']?@i",$image, $link);
            
            $link = "http://www.google.com".$link[1];
            
            preg_match('@</cite><br>(.*?)<br>@i',$image, $title);
            
            $title = $title[1];

            preg_match("@src=[\"']?([^\"]*)[\"']?@i",$image, $image);
            
            $image = $image[1];
            
			$array[] = array('url' => $link, 'title' => htmlspecialchars_decode($title), 'image' => $image);
            

        }

    }
    

	$array = json_decode(json_encode($array));

	if(count($array) < 1){

		echo '<div class="padding_ten">No results where found for '.htmlspecialchars($q).'</div>';

	}

    foreach($array as $value) {
       
        	echo '<li class="image_results">
		<a href="'.htmlstring(urldecode($value->url)).'"><div class="thumbnail" style="background-image:url('.htmlstring(urldecode($value->image)).')" ></div></a>
		</li>';

    }

}

function video($q,$page,$limit) {

	global $setting;
	
	$start=intval(intval($page-1)*$limit);
	
	$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q='.urlencode($q).'&maxResults='.$limit.'&key='.$setting["youtube_api"].'&start-index='.intval($start+1);

	$json = json_decode(file_get_contents($url), ture);
	
	foreach($json['items'] as $result) {

		$url_info='https://www.googleapis.com/youtube/v3/videos?part=statistics&id='.$result['id']['videoId'].'&key='.$setting["youtube_api"];

		$json_info = json_decode(file_get_contents($url_info), ture);

		$title = $result['snippet']['title'];

		$type = $result['id']['kind'];

		$description = $result['snippet']['description'];

		$views = $json_info['items'][0]['statistics']['viewCount'];

		if($type == 'youtube#video') {

			$watch = 'https://www.youtube.com/watch?v='.$result['id']['videoId'];

		}
		else if($type == 'youtube#channel'){
			
			$watch = 'https://www.youtube.com/user/'.$result['snippet']['channelTitle'];

		}

		$thumbnail = $result['snippet']['thumbnails']['default']['url'];

		$results[] = array('url' => htmlstring($watch), 'title' => limit_text(htmlstring($title), 5), 'thumbnail' => htmlstring($thumbnail), 'views' => htmlstring($views), 'description'=> limit_text(htmlstring($description), 24));
		
	}

	$data = json_decode(json_encode($results));

	if(count($data) < 1){

   		echo 'No Video results where found.';

	}
	else {

		echo '<ol class="result_mains">';

		foreach($data as $key => $value) {
		
			echo '<li class="video_results">
        		<a class="thumbnail_video_a" href="'.$value->url.'"><img class="thumbnail" src="'.$value->thumbnail.'" /></a>
       		<div class="video_info"><a class="title" href="'.$value->url.'">'.$value->title.'</a><div class="padding_ten">'.$value->description.'</div>
			<div class="video_views">'.number_format($value->views).'+ views</div></div>
      		</li>';

    		}

		echo '</ol>';

	}

}

function news($q, $page, $limit, $bold, $web = 0) {

	global $setting;

	if($page != 0) {

		$limit = 8;

		if($web == 1) {

			$limit = 1;

		}
		else if($page > 1) {

			$old_limit = $limit;

			$limit = 16;

			
		}

		$start = round((($page)*($limit)) - $old_limit);

		$feedurl = "http://www.faroo.com/api?q=".urlencode($q)."&start=".$start."&length=".$limit."&l=en&src=news&key=".$setting["faroo_key"]."&f=rss";
		
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
	
			preg_match('/(<img(?:[^>]*)+>)/i',$description, $match);

			preg_match("@src=[\"']?([^\"]*)[\"']?@i",$match[0], $image);

			$image = $image[1];

			preg_match("@width=[\"']?([^\"]*)[\"']?@i",$match[0], $width);

			$width = $width[1]."px";

			$description = preg_replace('/<a[^>]*>.*?<\/a>/i', '', $description);

			$array[] = array('image_width' => $width, 'image_url' => urlencode(htmlstring($image)), 'source_url' => urlencode(htmlstring($guid)), 'title' => urlencode(htmlstring($title)), 'summary' => urlencode(indextext($description)), 'publish_date' => urlencode(htmlstring($pubDate)), 'source' => urlencode(htmlstring($value->source)), 'author' => urlencode(htmlstring($author))); 
     		
		}

	}


	if($page == 1 && $web == 0) {

		$limit = 8;
		
		$start = round((($page-1)*($limit)));

		$url = "https://webhose.io/search?token=".$setting["webhose_key"]."&format=json&q=".urlencode($q)."%20language%3A(english)%20performance_score%3A%3E0%20(site_type%3Anews%20OR%20site_type%3Ablogs)&size=".$limit."";

		$data = url_info($url);

		$json = json_decode($data['response']);

		foreach ($json->posts as $result) {

			$array[] = array('image_width' => "194px", 'image_url' => urlencode(htmlstring($result->thread->main_image)), 'source_url' => urlencode(htmlstring($result->url)), 'title' => urlencode(htmlstring($result->title)), 'summary' => urlencode($result->text), 'publish_date' => urlencode(htmlstring($result->published)), 'source' => urlencode(htmlstring(indextext($value->source))), 'author' => urlencode(htmlstring($result->thread->site))); 
     		
    		}

	}

	$data = json_decode(json_encode($array));

	if(count($data) == 0 && $web == 0){

   		echo '<span class="error-primary">No results where found for '.htmlspecialchars($q).'</span>';

	}

	foreach($data as $value) { 

		echo '<li class="news_results">
		<div class="news_info">
		<h3><a href="'.htmlstring(urldecode($value->source_url)).'">'.make_bold(htmlstring(limit_text(urldecode($value->title), 10)), $bold).'</a></h3>
		<div class="news_summary">';

		if(!space($value->image_url)) {

			echo '<a href="'.htmlstring(urldecode($value->source_url)).'"><img src="'.htmlstring(urldecode($value->image_url)).'" width="'.$value->image_width.'"></a>';

		}

		echo make_bold(limit_text(urldecode($value->summary), 28), $bold).'</div>';

		if(!space($value->publish_date)) {

			echo ' <b>Publish</b> '.htmlstring(urldecode($value->publish_date));

		}

		if(!space($value->author)) {

			echo ' <b>Auther</b> '.htmlstring(urldecode($value->author));

		}

		if(!space($value->source)) {

			echo ' <b>Source</b> '.htmlstring($value->source);

		}
		
		echo '</div>
		</li>';
		
    	}
	
}

?>