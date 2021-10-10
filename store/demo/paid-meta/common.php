<?php

error_reporting(0);

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

function pagination($url,$page) {

	$previous=$page-1;

	$next=$page+1;

	$total_pages="100";

	echo '<div class="pagination">';
			
	if ($page > 1) {

		echo '<a class="page-button" href="'.$url.'&page='.$previous.'">Previous</a>';

	}

	for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {

		echo '<a class="page-button" href="'.$url.'&page='.$i.'">'.$i.'</a>';

	}

	echo ' <a class="page-button" href="'.$url.'&page='.$next.'">Next</a>
	</div>';

}

?>