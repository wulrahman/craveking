<?php

error_reporting(0);

$site_url='http://www.craveking.com/store/demo/image-search';

function gethtml($url) {

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2000);
	curl_setopt($curl, CURLOPT_TIMEOUT, 2000);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	return curl_exec($curl);

	curl_close($curl);

}

?>