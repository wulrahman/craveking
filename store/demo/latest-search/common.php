<?php

function limit_text( $string, $limiter ) {
	
	$count = str_word_count($string, 2);
	$key = array_keys($count);
	
	if (count($count) > $limiter) {

		$string = trim(substr($string, 0, $key[$limiter])).'&#8230;';

	}
	
	return $string;

}

function limiter($string, $limit, $arrays) {

	foreach($arrays as $array) {

		$string=implode($array,array_splice(explode($array,$string),0,$limit));

	}

	return $string;
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

?>
