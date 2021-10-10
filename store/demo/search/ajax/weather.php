<?php

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

require_once("../search.php");

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