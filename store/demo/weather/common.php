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

function getRealIpAddr() {
		
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		
		$ip=$_SERVER['HTTP_CLIENT_IP'];
		
	}
	
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		
	} 
	else {
		
		$ip=$_SERVER['REMOTE_ADDR'];
		
	}

	return $ip;
}

	
function gethtml( $url, $type ) {

	global $bot, $site_url;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
     	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

     	curl_setopt($ch, CURLOPT_REFERER, "".$site_url." ".$bot."/2.0" );
		
	$body = curl_exec($ch);
	curl_close($ch);

	return $body ;
	
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