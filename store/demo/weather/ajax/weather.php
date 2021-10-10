<?php

require_once("../setting.php");

require_once("../common.php");

// Geolocation detection with JavaScript, HTML5 and PHP
// http://locationdetection.mobi/
// Andy Moore
// http://andymoore.info/
//http://www.w3schools.com/html/tryit.asp?filename=tryhtml5_geolocation_map
// this is linkware if you use it please link to me:
// <a href="http://web2txt.co.uk/">Mp3 Downloads</a>

$geodata = geodata(htmlentities(htmlspecialchars(strip_tags($_GET['latlng']))));

list($lat,$long) = explode(',',htmlentities(htmlspecialchars(strip_tags($_GET['latlng']))));

$geodata['latitude'] = $lat;

$geodata['longitude'] = $long;

$geodata['formatted_address'] = $xml->result->formatted_address;

$geodata['accuracy'] = htmlentities(htmlspecialchars(strip_tags($_GET['accuracy'])));

$geodata['altitude'] = htmlentities(htmlspecialchars(strip_tags($_GET['altitude'])));

$geodata['altitude_accuracy'] = htmlentities(htmlspecialchars(strip_tags($_GET['altitude_accuracy'])));

$geodata['directional_heading'] = htmlentities(htmlspecialchars(strip_tags($_GET['heading'])));

$geodata['speed'] = htmlentities(htmlspecialchars(strip_tags($_GET['speed'])));

weather($geodata);

?>