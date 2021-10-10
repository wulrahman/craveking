<?php

function randomurl( $alphabet ) {
	
	// Remember to declare $pass as an array
	$pass = array();
	
	// Put the length -1 in cache
	$alphaLength = strlen($alphabet) - 1;
	
	for ($i = 0; $i < 8; $i++) {
		
		$new = rand(0, $alphaLength);
		$pass[] = $alphabet[$new];
		
	}
	
	// Turn the array into a string
	return implode($pass);
	
}

function make_bold($string, $arrays) {

	$strings=explode(' ', $string);

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

function indextext($string) {

	$array = array('@<script[^>]*?>.*?<\/script>@si',
	'@<style[^>]*?>.*?<\/style>@si',
	'@<nav[^>]*?>.*?<\/nav>@si',
	'@<header[^>]*?>.*?<\/header>@si',
	'@<footer[^>]*?>.*?<\/footer>@si',
	'@<![\s\S]*?--[ \t\n\r]*>@si',
	'/&[a-z]{1,6};/',
	'/&nbsp;/',
	'@\s\s+@',
	'@\s+@',
	'@<!--sphider_noindex-->.*?<!--\/sphider_noindex-->@si',
	'@<!--.*?-->@si',
	'@<link rel[^<>]*?>@si'
	);

	$string = preg_replace($array, ' ', $string);  

	$string = strip_tags(preg_replace(array('/<\/[\w ]+>/', '/<[\w ]+>/'), '\\0 ', $string));

	$string = preg_replace($array, ' ', $string);  

	return $string;

}

function closetags($html) {

    $html_new = $html;

    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result1);
    preg_match_all ( "#</([a-z]+)>#iU", $html, $result2);

    $results_start = $result1[1];
    $results_end = $result2[1];

    foreach($results_start AS $startag){

        if(!in_array($startag, $results_end)){

            $html_new = str_replace('<'.$startag.'>', '', $html_new);

        }

    }

    foreach($results_end AS $endtag){

        if(!in_array($endtag, $results_start)){

            $html_new = str_replace('</'.$endtag.'>', '', $html_new);

        }

    }

    return $html_new;

}

function remove_duplicates($array, $result, $key, $rurl, $rtitle, $rabstract) {

	foreach ($array as $keys => $match) {

		if($keys == $key) {
			
		}
		else {

			$url = string_compare($result->url, $match->url);

			$title = string_compare($result->title, $match->title);

			$abstract = string_compare($result->abstract, $match->abstract);

			if($url > $rurl || $title > $rtitle || $abstract > $rabstract) {

				unset($array[$keys]);

			}

		}

    	}

	return $array;

}

function similar_results($array, $key, $main) {

	$main = main_domain($main->show); 
             
	foreach ($array as $keys => $match) {

		if ($key != $keys) {

			$similar = main_domain($match->show);

			if($main['main'] == $similar['main']){

				$result[$keys] = $array[$keys];

				unset($array[$keys]);

			}
	
		}	

	}

	foreach ($result as $keys => $match) {

		$result = remove_duplicates($result, $match, $keys, "1.5", "0.9", "1.5");

	}

	$return['similar'] = array_filter($result);

	$return['result'] = $array;

	return $return;

}

function string_compare($match, $string) {

    	$lmatch = strlen($match);

    	$lstring = strlen($string);
 
	$count = $i = 0;

    	while ($i < $lmatch) {

        	$char = substr($match, $i, 1);

        	if (strpos($string, $char) !== FALSE) {

            	$segment = $segment.$char;

            	if (strpos($string, $segment) !== FALSE) {

                	$difference = $lmatch - (abs(intval($i-strlen($segment)+1)-intval(strpos($string, $segment))));

                	$array[$count] = array('score' => ((($difference) * strlen($segment)) / ($lstring * $lmatch)));

				$i++;

            	}
      		else {

                 	$segment = '';
                 	$count++;
             	}
         	}
         	else {

             	$segment = '';
            	$count++;
			$i++;

         	}

     	}

     return array_sum(array_column($array, 'score'));

}

function vowel_count ($string) {

	return preg_match_all('/[aeiou]/i', $string, $match);

}

function main_domain ($url) {

	$parse = parse_url($url);

	$main_url = $parse['host'];

	if (substr($main_url,0 , 4) == 'www.') {

		$main_url = substr($main_url, 4);

	}

	$domain = explode(".", $main_url);

	$count = count($domain);

	if ($count == "4") {

		unset($domain[$count-4]);

		$count = count($domain);

	}

	$tld = $domain[$count-1];

	$extension = $tld;

	if($count == 3) {

		$array['sub_main'] = $domain[$count-2];

		if ($array['sub_main'] == "") {

			$main_url = $domain[$count-2];

		}
		else {

			if (vowel_count($array['sub_main']) > vowel_count($domain[$count-3])) {

				$extension = $domain[$count-3].".".$tld;

				$main_url = $array['sub_main'];

			}
			else {

				$extension = $array['sub_main'].".".$tld;

				$main_url = $domain[$count-3];

			}
			
		}

	}
	else if ($count == 2) {

		$main_url = $domain[$count-2];

	}

	$array['main'] = $main_url;

	$array['extension'] = $extension;

	return $array;

}

function show_url($url) {
	
	$showurl = limit_text(limiter($url, 8, array("-","/","=")),8);

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
	return str_ireplace($replace,$to, $html);
	
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

function gethtml($url, $login = 0, $username = 0, $password = 0) {

	global $robot;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

	curl_setopt($curl, CURLOPT_VERBOSE, 1);

	curl_setopt($curl, CURLOPT_USERAGENT, $robot);

	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2000);

	curl_setopt($curl, CURLOPT_TIMEOUT, 2000);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	if($login == 1) {

		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  
		curl_setopt($curl, CURLOPT_USERPWD,  "".$username.":".$password."");

	}

	return curl_exec($curl);

	curl_close($curl);

}

function limiter($string, $limit, $arrays) {

	foreach($arrays as $array) {

		$string=implode($array,array_splice(explode($array,$string),0,$limit));

	}

	return $string;
}

?>
