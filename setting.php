<?php

error_reporting(0);

global $setting;

$setting = array();


// Development or published

$setting["sandbox"] = 1;

if($setting["sandbox"] == 1) {

	$setting["domain"] = 'localhost/craveking';

	$setting["url"] = "http://".$setting["domain"]."";

}
else {

	$setting["domain"] = "craveking.com";

	$setting["url"]="http://www.".$setting["domain"];

}

if($setting["sandbox"] == 1) {

	$setting["Lid"] = mysqli_connect('localhost', 'root', '', 'cragglist');
    
    $setting["crawler_Lid"] = mysqli_connect('localhost', 'root', '', 'cragglist_crawler');


}
else {

	$setting["Lid"] = mysqli_connect('localhost', 'cravnzsb_main', 'Q#EE{6!CFc$n', 'cravnzsb_main');
    
    $setting["crawler_Lid"] = mysqli_connect('cragglist.com.mysql', 'cragglist_com', 'LK2ZUZ7g', 'cragglist_crawler');

}


// Currency setting

if (!function_exists('money_format')) {

	$setting["location"] = "US";

	$setting["format_currency"] = '%n';

}
else {

	$setting["location"] = "en_US";

	$setting["format_currency"] = '%(#10n';

}

setlocale(LC_MONETARY, $setting["location"]);


// Html to array

$html_to_array = new DOMDocument();



// Api keys

$setting["google_map_key"] = "AIzaSyDbXt-8JtQv70BXkD8uqSmKCs-m9psDRJs";

$setting["youtube_api"] = "AIzaSyD4NZH_qSU6g72tezl2IuCsgeqawOu_2RE";

$setting["bing_key"] = "xY3FNjJIO8C1v4pG6IzfCTzgoKb8DgZq0lQJ+ABr4AM";

$setting["faroo_key"] = "s@g2KnPVBuu4DbmBWG3rbgO6JMs_";

$setting["webhose_key"] = "eca76abe-7138-435c-9bdf-beb12082d74c";

$setting["worldweatheronline"] = "11f26309fdf5fde5802f33b20d688";

$setting['bighugelabs_api'] = "a611429c2f07a85ae9f35902a7a8b6d1";


// Stop words

$setting['stopword'] = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');



// Robot setting

$setting["robot"] = "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36";

$setting["bot"] = "Cragglist";

$setting["icon"] = "";


// Usercustom image

$user_thumb["image_dir"] = "auth/thumb/";

$user_thumb["temp_dir"] = $user_thumb["image_dir"]."temp/";

$user_thumb["thumb_dir"] = $user_thumb["image_dir"]."thumb/";

$user_thumb["maxwidth"] = 200;

$user_thumb["maxheight"] = 150;


// Default image

$setting["getmaxwidth"] = 1;

$setting["getmaxheight"] = 1;

$setting["image_dir"] = "auth/thumb/";

$setting["temp_dir"] = $setting["image_dir"]."temp/";

$setting["thumb_dir"] = $setting["image_dir"]."thumb/";

$setting["dir_sub"] = "";

$setting["maxwidth"] = 200;

$setting["maxheight"]  = 150;


// Alphabet

$setting["alp"] = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";


// Allowed image types

$setting["typeset"] = 'jpeg';

$setting["image_types"] = array(

	'png' => 'image/png',
	'jpe' => 'image/jpeg',
	'jpeg' => 'image/jpeg',
	'jpg' => 'image/jpeg',
	'gif' => 'image/gif',
	'bmp' => 'image/bmp',
	'ico' => 'image/vnd.microsoft.icon',
	'tiff' => 'image/tiff',
	'tif' => 'image/tiff',
	'svg' => 'image/svg+xml',
	'svgz' => 'image/svg+xml'

);

// Destinations

$setting["store_url"] = $setting["url"]."";

$setting["main_url"] = $setting["url"]."";

$setting["forum_url"] = $setting["url"]."/forum";

$setting["admin_url"] = $setting["url"]."/admin";


// Email information

$setting["email_1"] = "@".$setting["domain"];

$setting["no_reply"] = "no_reply".$setting["email_1"];

$setting["contactemail"] = "contact".$setting["email_1"];

?>