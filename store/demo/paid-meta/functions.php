<?php

require_once('setting.php');

require_once('common.php');

function web($q,$page,$limit) {

	$url="https://www.cragglist.com/api/?q=".$q."&page=".$page."&limit=".$limit."&type=web";

	$data = json_decode(gethtml($url, ""));

	if(count($data) < 1){

   		echo 'No Web results where found.';

	}
	echo '<ol>';

    	foreach ($data as $result) {     
             
        	echo'<li class="results">
		<h3><a href="'.urldecode($result->url).'">'.urldecode($result->title).'</a></h3>
		<cite>'.urldecode($result->visibleUrl).'</cite></br>
		<span class="st">'.urldecode($result->abstract).'</span>
		</li>';

    	}

	echo '</ol>';

}

	
function image($q,$page,$limit) {

	$url="http://cragglist.com/api/?q=".$q."&page=".$page."&limit=".$limit."&type=image";

	$data = json_decode(gethtml($url, ""));

	if(count($data) < 1){

    		echo 'No Image results where found.';

	}

	echo '<ol class="result_mains">';

    	foreach($data as $value) {
       
        	echo'<li class="video_results">
		<a href="'.htmlstring(urldecode($value->url)).'"><img class="thumbnail" src="'.htmlstring(urldecode($value->image)).'" ></a>
		<a class="title" href="'.htmlstring(urldecode($value->url)).'">'.urldecode($value->title).'</a></li>';

    	}

	echo '</ol>';
}

	
function video($q,$page,$limit) {

	$url="https://www.cragglist.com/api/?q=".$q."&page=".$page."&limit=".$limit."&type=video";

	$data = json_decode(gethtml($url, ""));

	if(count($data) < 1){

   		echo 'No Video results where found.';

	}

	echo '<ol class="result_mains">';

	foreach($data as $value) {

		echo '<li class="video_results">
        	<a href="'.htmlstring(urldecode($value->url)).'"><img class="thumbnail" src="'.htmlstring(urldecode($value->thumbnail)).'" /></a></br>
       	<a class="title" href="'.htmlstring(urldecode($value->url)).'">'.urldecode($value->title).'</a>
		<div class="video_views">'.number_format(htmlstring(urldecode($value->views))).'+ views</div>
      	</li>';

    	}

	echo '</ol>';

}

function news($q,$page,$limit) {

	$url="https://www.cragglist.com/api/?q=".$q."&page=".$page."&limit=".$limit."&type=news";
	$data = json_decode(gethtml($url, ""));

	if(count($data) < 1){

   		echo 'No News results where found.';

	}

	echo '<ol>';	

	foreach($data as $value) {  
         	
		echo '<li class="news_results">
		<div class="news_info">
		<h3><a href="'.htmlstring(urldecode($value->source_url)).'">'.htmlstring(urldecode($value->title)).'</a></h3>
		<div class="news_summary">'.htmlstring(urldecode($value->summary)).'</div>
		<b>Publish</b> '.htmlstring(urldecode($value->publish_date)).' <b>Auther</b> '.htmlstring(urldecode($value->author)).' <b>Source</b> '.htmlstring($value->source).'
		</div>
		</li>';
		
    	}
	
	echo '</ol>';

}
	
function weather() {

	$ip=getRealIpAddr();
	
	$ipaddress=explode(',', $ip);
	
	$ipdetails = json_decode(file_get_contents("https://freegeoip.net/json/".$ipaddress['0']),true);

	global $worldweatheronline;

	$num_of_days=5;

	$timezone = $ipdetails['time_zone'];
	
	$timezone = explode("/", $timezone);

	$url='http://api2.worldweatheronline.com/free/v2/weather.ashx?key='.$worldweatheronline.'&q='.$timezone['1'].'&num_of_days='.intval($num_of_days).'&format=json';

	$json=json_decode(gethtml($url,""));

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