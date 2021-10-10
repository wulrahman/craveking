<?php

function geodata($latlng) {

	$geo = 'http://www.maps.google.com/maps/api/geocode/xml?latlng='.$latlng.'&sensor=true';

	$xml = simplexml_load_file($geo);

	foreach($xml->result->address_component as $component){

		if($component->type=='street_address'){

			$geodata['precise_address'] = $component->long_name;

		}

		if($component->type=='natural_feature'){

			$geodata['natural_feature'] = $component->long_name;

		}

		if($component->type=='airport'){

			$geodata['airport'] = $component->long_name;

		}

		if($component->type=='park'){

			$geodata['park'] = $component->long_name;

		}

		if($component->type=='point_of_interest'){

			$geodata['point_of_interest'] = $component->long_name;

		}

		if($component->type=='premise'){

			$geodata['named_location'] = $component->long_name;

		}

		if($component->type=='street_number'){

			$geodata['house_number'] = $component->long_name;

		}

		if($component->type=='route'){

			$geodata['street'] = $component->long_name;

		}

		if($component->type=='locality'){

			$geodata['town_city'] = $component->long_name;

		}

		if($component->type=='administrative_area_level_3'){

			$geodata['district_region'] = $component->long_name;

		}

		if($component->type=='neighborhood'){

			$geodata['neighborhood'] = $component->long_name;

		}

		if($component->type=='colloquial_area'){

			$geodata['locally_known_as'] = $component->long_name;

		}

		if($component->type=='administrative_area_level_2'){

			$geodata['county_state'] = $component->long_name;

		}

		if($component->type=='postal_code'){

			$geodata['postcode'] = $component->long_name;

		}

		if($component->type=='country'){

			$geodata['country'] = $component->long_name;

		}

	}

	$geodata['google_api_src'] = $geo;

	return $geodata;

}

function movie($q) {

	$url = 'http://www.omdbapi.com/?t='.urlencode($q).'&y=&plot=short&r=json';

	$details = json_decode(gethtml($url, ""));

	if($details->Response=='True') {

		echo '<div class="web_side_bar_test">';

		if($details->Poster !='N/A')	{

			echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/"><img src="'.$details->Poster.'"></a><br>';

		}

		//Print the movie information
    		echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/">'.$details->Title.' ('.$details->Year.')</a><br>';

		if($details->Rated !='N/A') {

    			echo 'Rated : '.$details->Rated.'<br>';

		}

		if($details->Released !='N/A')	{

   			echo 'Released Date : '.$details->Released.'<br>';

		}

		if($details->Runtime !='N/A') {

			echo 'Runtime : '.$details->Runtime.'<br>';

		}
    	
		if($details->Genre !='N/A')	{

    			echo 'Genre : '.$details->Genre.'<br>';

		}

		if($details->Director !='N/A')	{

    			echo 'Director : '.$details->Director.'<br>';

		}
	
		if($details->Writer !='N/A') {

    			echo 'Writer : '.$details->Writer.'<br>';

		}

		if($details->Actors !='N/A') {

			echo 'Actors : '.$details->Actors.'<br>';

		}
    	
		if($details->Plot !='N/A') {

			echo 'Plot : '.$details->Plot.'<br>';

		}
    	
		if($details->Language !='N/A')	{

    			echo 'Language : '.$details->Language.'<br>';

		}

		if($details->Country !='N/A') {

    			echo 'Country : '.$details->Country.'<br>';

		}

		if($details->Awards !='N/A') {

    			echo 'Awards : '.$details->Awards.'<br>';

		}

		if($details->Metascore !='N/A') {

    			echo 'Metascore : '.$details->Metascore.'<br>';

		}

		if($details->imdbRating !='N/A') {

    			echo 'IMDB Rating : '.$details->imdbRating.'<br>';

		}

		if($details->imdbVotes !='N/A') {

    			echo 'IMDB Votes : '.$details->imdbVotes.'<br>';

		}

		echo '</div>';

	}

}

function web($q, $page, $limit) {

	global $search_url, $bing_key;

	foreach(explode(" ",$q) as $p) {

		if(!space($p)) {

			if(strlen($p) > 1 ) {

				$bold[] = $p;

			}

		}

	}

	$start = round((($page-1)*($limit)));

	$url =  'https://api.datamarket.azure.com/Bing/Search/v1/Web?Query=%27'.urlencode($q).'%27&$skip='.$start.'&$format=json&$top='.$limit.'';

	$json = json_decode(gethtml($url, 1, $bing_key, $bing_key));

	foreach ($json->d->results as $result) {

		$array[]=array('abstract' => urldecode($result->Description), 'title' => urldecode($result->Title), 'url' => $result->Url, 'show' => $result->DisplayUrl);

	}

	$array = json_decode(json_encode($array));
	
	echo '<ol>';

	if(count($array) < 1){

   		echo 'No Web results where found.';

	}

	foreach ($array as $key => $main) {

		if(!space($main->abstract) || !space($main->url)|| !space($main->title)) {
		
			echo '<li class="results">
			<h3><a href="'.$main->url.'">'.closetags(make_bold(indextext($main->title), $bold)).'</a></h3>
			<cite>'.show_url($main->show).'</cite></br>
			<span class="st">'.closetags(make_bold(indextext($main->abstract), $bold)).'</span>
			</li>';

			$similar = main_domain($main->show);

			$return = similar_results($array, $key, $main);

			$array = $return['result'];

			if(COUNT($array) > 0) {

				echo '<div class="inline_web_main">';

			}

			foreach ($return['similar'] as $key => $similar) {

				if(!space($similar->abstract) || !space($similar->url)|| !space($similar->title)) {

					echo '<li class="inline_results results">
					<h3><a href="'.$similar->url.'">'.closetags(make_bold(limit_text(indextext($similar->title), 4), $bold)).'</a></h3>
					<span class="st">'.closetags(make_bold(limit_text(indextext($similar->abstract), 18), $bold)).'</span>
					</li>';

				}

			}

			if(COUNT($array) > 0) {

				echo '</div>';

			}

		}

    	}

    	echo '</ol>';

	movie($q);

	$previous=$page-1;

	$next=$page+1;

	$total_pages="100";

	echo '<div class="pagination">';

	$pageurl=$search_url."/?type=web&q=".urlencode($q);

	if ($page > 1) {

		echo '<a class="page-button" href="'.$pageurl.'&page='.$previous.'">Previous</a>';
		
	}
			
	for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {

		echo '<a class="page-button" href="'.$pageurl.'&page='.$i.'">'.$i.'</a>';

	}

	echo ' <a class="page-button" href="'.$pageurl.'&page='.$next.'">Next</a>
	</div>';

	echo '</ol>';

}

function image($q,$page,$limit) {

	global $bing_key;

	$limit = 30;

	$start = round((($page-1)*($limit)));

	$url =  'https://api.datamarket.azure.com/Bing/Search/v1/Image?Query=%27'.urlencode($q).'%27&$skip='.$start.'&$format=json&$top='.$limit.'';

	$json = json_decode(gethtml($url, 1, $bing_key, $bing_key));

	foreach ($json->d->results as $result) {

		$array[]=array('image' => $result->Thumbnail->MediaUrl, 'title' => urldecode($result->Title), 'url' => $result->SourceUrl);

	}


	$data = json_decode(json_encode($array));

	if(count($data) < 1){

    		echo 'No Image results where found.';

	}

	echo '<ol class="result_mains">';

    	foreach($data as $value) {
       
        	echo'<li class="image_results">
		<a href="'.htmlstring(urldecode($value->url)).'"><div class="thumbnail" style="background-image:url('.htmlstring(urldecode($value->image)).')" ></div></a>
		</li>';

    	}

	echo '</ol>';

}

	
function video($q,$page,$limit) {

	global $youtube_api;
	
	$start=intval(intval($page-1)*$limit);
	
	$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q='.urlencode($q).'&maxResults='.$limit.'&key='.$youtube_api.'&start-index='.intval($start+1);

	$json = json_decode(file_get_contents($url), ture);

	if(count($json['items']) < 1){

   		echo 'No Video results where found.';

	}
	
	foreach($json['items'] as $result) {

		$url_info='https://www.googleapis.com/youtube/v3/videos?part=statistics&id='.$result['id']['videoId'].'&key='.$youtube_api;

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

		$results[] = array('url' => htmlstring($watch), 'title' => htmlstring($title), 'thumbnail' => htmlstring($thumbnail), 'views' => htmlstring($views), 'description'=> htmlstring($description));
		
	}

	$data = json_decode(json_encode($results));

	if(count($data) < 1){

   		echo 'No Video results where found.';

	}

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

function news($q,$page,$limit) {

	global $search_url, $faroo_key;

	$start=intval(intval($page-1)*$limit) + $limit;

	$feedurl = "http://www.faroo.com/api?q=".urlencode($q)."&start=".$start."&length=".$limit."&l=en&src=news&key=".$faroo_key."&f=rss";
		
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

	$data = json_decode(json_encode($results));

	if(count($data) < 1){

   		echo 'No News results where found.';

	}

	echo '<ol>';	

	foreach($data as $value) { 

		echo '<li class="news_results">
		<div class="news_info">
		<h3><a href="'.htmlstring(urldecode($value->source_url)).'">'.htmlstring(urldecode($value->title)).'</a></h3>
		<div class="news_summary">'.urldecode($value->summary).'</div>
		<b>Publish</b> '.htmlstring(urldecode($value->publish_date)).' <b>Auther</b> '.htmlstring(urldecode($value->author)).' <b>Source</b> '.htmlstring($value->source).'
		</div>
		</li>';
		
    	}
	
	echo '</ol>';

	$previous=$page-1;

	$next=$page+1;

	$total_pages="100";

	echo '<div class="pagination">';

	$pageurl=$search_url."/?type=web&q=".urlencode($q);

	if ($page > 1) {

		echo '<a class="page-button" href="'.$pageurl.'&page='.$previous.'">Previous</a>';
		
	}
			
	for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {

		echo '<a class="page-button" href="'.$pageurl.'&page='.$i.'">'.$i.'</a>';

	}

	echo ' <a class="page-button" href="'.$pageurl.'&page='.$next.'">Next</a>
	</div>';

	echo '</ol>';

}
	
function weather($geodata) {

	global $worldweatheronline;

	$num_of_days=5;

	$url='http://api2.worldweatheronline.com/free/v2/weather.ashx?key='.$worldweatheronline.'&q='.$geodata['town_city'].'&num_of_days='.intval($num_of_days).'&format=json';

	$json=json_decode(gethtml($url));

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

?>