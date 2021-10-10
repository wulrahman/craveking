<?php

error_reporting(0);

global $setting;

$setting = array();

$setting["sandbox"] = 0;

$setting["robot"] = "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36";

$setting["bot"] = "Cragglist Meta";

$setting["user_icon"] = "";

$setting["google_map_key"] = "AIzaSyDbXt-8JtQv70BXkD8uqSmKCs-m9psDRJs";

$setting["youtube_api"] = "AIzaSyD4NZH_qSU6g72tezl2IuCsgeqawOu_2RE";

$setting["bing_key"] = "xY3FNjJIO8C1v4pG6IzfCTzgoKb8DgZq0lQJ+ABr4AM";

$setting["faroo_key"] = "s@g2KnPVBuu4DbmBWG3rbgO6JMs_";

$setting["webhose_key"] = "eca76abe-7138-435c-9bdf-beb12082d74c";

$setting["worldweatheronline"] = "11f26309fdf5fde5802f33b20d688";

$setting["stopword"] = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');

$setting["alp"] = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";

if($setting["sandbox"] == 1) {

	$setting["domain"] = 'localhost';

	$setting["url"] = "http://".$setting["domain"]."/cragglist";

}
else { 

	$setting["domain"] = "craveking.com";

	$setting["url"]="http://www.".$setting["domain"];

}

$setting["site_url"] = $setting["url"]."";

$setting["search_url"] = $setting["url"]."/store/demo/search";

?>
