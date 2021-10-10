<?php

function space($string) {

	$patterns = array('1' => '/\s\s+/i', '2' => '/[^a-zA-Z0-9 -]/', '3' => '/[^[:alpha:]]/', '4' => '/[^a-zA-Z]+/');

	$string = preg_replace($patterns, ' ', $string);

	if($string == "") {

		return true;

	}
	else if(str_ireplace(" ","",preg_replace('/\s+/', '', $string))=="") {

		return true;

	}
	else {

		return false;

	}

}

function htmlstring( $html ) {

	$replace=array('<','>');
    
	$to=array('&lt;','&gt;');
    
	return htmlspecialchars(str_ireplace($replace,$to, $html));

}

function limit_text( $string, $limiter ) {

	$count = str_word_count($string, 2);

	$key = array_keys($count);

	$length = strlen($string);

	$word_count = str_word_count($string);

	$ratio = $length/$word_count;

	if($ratio != 0) {

		$new_word_count = $length/$ratio;

		$difference = $word_count/$new_word_count;

		$limiters = round($difference * $limiter);

	}

	if($limiters < $limiter) {

		$limiter = $limiters;

	}

	if (count($count) > $limiter) {

		$string = trim(substr($string, 0, $key[$limiter])).'&#8230;';

	}

	return $string;

}

function indextext($string) {

	$array = array('@<script[^>]*?>.*?<\/script>@si',
	'@<noscript[^>]*?>.*?<\/noscript>@si',
	'@<header[^>]*?>.*?<\/header>@si',
	'@<nav[^>]*?>.*?<\/nav>@si',
	'@<style[^>]*?>.*?<\/style>@si',
	'@<link rel[^<>]*?>@si',
	'@<footer[^>]*?>.*?<\/footer>@si',
	'@<![\s\S]*?--[ \t\n\r]*>@si',
	'/&[a-z]{1,6};/',
	'/&nbsp;/',
	'@\s\s+@',
	'@\s+@',
	'@<!--sphider_noindex-->.*?<!--\/sphider_noindex-->@si',
	'@<!--.*?-->@si',
	'/(<|>)\1{2}/si',
	'@<head[^>]*?>.*?<\/head>@si'
	);

	$string = indexpartialtext(preg_replace($array, ' ', $string));

	$string = preg_replace('#<a.*?>(.*?)<\/a>#i', '\1', $string);

	$string = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si", '<$1$2>', $string);

	$string = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>([a-z0-9']{1,100})<\/([a-z][a-z0-9]*)[^>]*?(\/?)>/is", ' ', $string);

	$string = strip_tags(preg_replace(array('/<\/[\w ]+>/', '/<[\w ]+>/'), '\\0 ', $string));

	$string = preg_replace($array, ' ', $string);

	$string = trim(replaceWhitespace($string));

	return $string;

}

function randomurl( $alphabet ) {

	$pass = array();

	$alphaLength = strlen($alphabet) - 1;

	for ($i = 0; $i < 8; $i++) {

		$new = rand(0, $alphaLength);

		$pass[] = $alphabet[$new];

	}

	return implode($pass);

}

function indexpartialtext($string) {

	$tags = "<a><h1><h2><h3><h4><h5><h6><h7><h8><h9><p><article><b><br><div><ul><ol><li><img><i><em><code><pre><strong><del><figure><figcaption><hr>";

	$string = trim(strip_tags($string, $tags));

	return $string;

}

function replaceWhitespace($string) {

	$array = array("  ", " \t", " \r", " \n", "\t\t", "\t ", "\t\r", "\t\n", "\r\r", "\r ", "\r\t", "\r\n", "\n\n", "\n ", "\n\t", "\n\r");

	foreach ($array as $key => $replacement) {

		$string = str_replace($replacement, $replacement[0], $string);

	}

  return trim($string);

}

function limiter($string, $limit, $arrays) {

	foreach($arrays as $array) {

		$string = implode($array,array_splice(explode($array,$string),0,$limit));

	}

	return $string;
}

function extractCommonWords($string, $count){

      $stopWords = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');
      $string = preg_replace('/\s\s+/i', '', $string);
    
      $string = trim($string);
    
      $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string);
    
      $string = strtolower($string); // make it lowercase
    
      preg_match_all('/\b.*?\b/i', $string, $matchWords);
    
      $matchWords = $matchWords[0];

      foreach ( $matchWords as $key=>$item ) {

          	if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {

             	unset($matchWords[$key]);

          	}

      }

      $wordCountArr = array();

      if ( is_array($matchWords) ) {

          	foreach ( $matchWords as $key => $val ) {

     			$val = strtolower($val);

              	if ( isset($wordCountArr[$val]) ) {

               		$wordCountArr[$val]++;

              	}
			else {

          			$wordCountArr[$val] = 1;

           	}

       	}

	}

	arsort($wordCountArr);

	$wordCountArr = array_slice($wordCountArr, 0, $count);

	return $wordCountArr;

}

function url_info($url, $type = 0, $username = "", $password = "") {

	$curl = curl_init();

	global $setting, $html_to_array;

	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";

  	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";

  	$header[] = "Cache-Control: max-age=0";

  	$header[] = "Connection: keep-alive";

  	$header[] = "Keep-Alive: 300";

  	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";

  	$header[] = "Accept-Language: en-us,en;q=0.5";

 	$header[] = "Pragma: ";

	curl_setopt($curl, CURLOPT_BINARYTRANSFER,1);

	curl_setopt($curl, CURLOPT_AUTOREFERER, false);

	curl_setopt($curl, CURLOPT_REFERER, 'http://google.com');

	if($type == 2) {

		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt($curl, CURLOPT_USERPWD,  "".$username.":".$password."");

	}

	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');

	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

	curl_setopt($curl, CURLOPT_URL, $url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

	curl_setopt($curl, CURLOPT_VERBOSE, 1);

	curl_setopt($curl, CURLOPT_USERAGENT, ''.$setting["robot"].'');

	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 400);

	curl_setopt($curl, CURLOPT_TIMEOUT, 400);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$text = curl_exec($curl);

	$array['response'] = $text;

	$array['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$array['error'] = curl_error($curl);

	if (curl_getinfo($curl, CURLINFO_EFFECTIVE_URL) != $url) {

		$url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

	}

	$array['url'] = $url;

	curl_close($curl);

	if($type == 1) {

		$array['response'] = convert_encoding($array['response']);

		$html_to_array->loadHTML($array['response']);

		$html_array_path = new \DOMXpath($html_to_array);

	}

	if($array['response'] === false) {

		trigger_error(curl_error($curl));

	}

	return $array;

}

function make_bold($string, $arrays) {

	$strings = explode(' ', $string);

	$alphabet = ".\+*?[^]$(){}=!<>|:-";

	$random = randomurl( $alphabet ).randomurl( $alphabet ).randomurl( $alphabet );

	$random_two = randomurl( $alphabet ).randomurl( $alphabet ).randomurl( $alphabet );

	foreach($arrays as $array) {

		$array = preg_quote($array);

		foreach($strings as $key => $stringe) {

			preg_match("@(".$array.")@siU", $stringe, $found);

			foreach ($found as $replace) {

				$replace = preg_quote($replace);

				if (!strpos($replace,$random_two) && !strpos($replace,$random)) {

					$match = str_ireplace($replace, $random_two.$replace.$random, $stringe);

					$strings[$key] = $match;

				}

			}

		}

	}

	$string = implode(' ', $strings);

	$string = str_ireplace(array($random_two,$random), array("<b>","</b>"), $string);

	return $string;

}

function show_url($url) {

	$showurl = limit_text(limiter($url, 6, array("-","/","=")),6);

	if (substr($showurl,0 , 8) == 'https://' && substr($showurl, 0, 12 ) == 'https://www.') {

		$showurl = substr($showurl, 8);

	}
	else if (substr($showurl,0 , 7) == 'http://' && substr($showurl,0 , 11) == 'http://www.') {

		$showurl = substr($showurl, 7);

	}
	else if (substr($showurl,0 , 7) == 'http://') {

		$showurl = substr($showurl, 7);

	}

	return $showurl;

}

function geodata($latlng) {

	$geo = 'http://www.maps.google.com/maps/api/geocode/xml?latlng='.$latlng.'&sensor=true';

	$xml = simplexml_load_file($geo);

	$geodata = array();

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

function map_image($location, $zoom) {

	return '<img src="http://maps.google.com/maps/api/staticmap?center='.$location.'&zoom='.$zoom.'&size=250x150&maptype=roadmap&&sensor=true" width="250" height="150" alt="'.$geodata['formatted_address'].'"/>';

}

?>
