<?php

function gethtml( $url, $type ) {

	global $bot, $search_url;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
     	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

     	curl_setopt($ch, CURLOPT_REFERER, "".$search_url." ".$bot."/2.0" );
		
	$body = curl_exec($ch);
	curl_close($ch);

	return $body ;
	
}

?>
