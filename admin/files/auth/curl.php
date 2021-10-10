<?php

ini_set("memory_limit","70M");

$url = 'http://publishers.spilgames.com/en/rss-3?lang=en-US&limit='.$feed_setting['max_feed'].'&format=json';

$data = url_info($url);

$json = json_decode($data['response'], true);

foreach($json["entries"] as $key => $game) {
    
    $array[] = array('hash_id' => $game['id'], 'thumbnail' => urlencode(htmlstring($game['thumbnails']['medium'])), 'file' => urlencode(htmlstring($game['gameUrl'])), 'title' => urlencode(indextext($game['title'])), 'description' => urlencode(indextext($game['description'])), 'width' => urlencode(htmlstring($game['width'])), 'height' => urlencode(htmlstring($game['height'])), 'category' => urlencode(htmlstring($game['category'])), 'source' => 'spil', 'controls' => urlencode(htmlstring($game['controls']))); 

}

$url = 'http://www.freegamesforyourwebsite.com/feeds/games/?category=all&thumb=small&limit='.$feed_setting['max_feed'].'&format=json';

$data = url_info($url);

$json = json_decode($data['response'], true);

foreach($json as $game) {

    $dimensions =  explode('x', mysqli($game['resolution']));

    $array[] = array('hash_id' => $game['id'], 'thumbnail' => urlencode(htmlstring($game['med_thumbnail_url'])), 'file' => urlencode(htmlstring($game['swf_file'])), 'title' => urlencode(indextext($game['title'])), 'description' => urlencode(indextext($game['description'])), 'width' => urlencode(htmlstring($dimensions['0'])), 'height' => urlencode(htmlstring($dimensions['1'])), 'category' => urlencode(htmlstring($game['category'])), 'source' => 'fog', 'controls' => urlencode(htmlstring($game['controls']))); 

}


$url = 'http://www.kongregate.com/games_for_your_site.xml';

$data = url_info($url);

$xml = simplexml_load_string($data['response']);

foreach($xml->game as $game) {	

    $array[] = array('hash_id' => $game->id, 'thumbnail' => urlencode(htmlstring($game->thumbnail)), 'file' => urlencode(htmlstring($game->flash_file)), 'title' => urlencode(indextext($game->title)), 'description' => urlencode(indextext($game->description)), 'width' => urlencode(htmlstring($game->width)), 'height' => urlencode(htmlstring($game->height)), 'category' => urlencode(htmlstring($game->category)), 'source' => 'kongregate', 'controls' => urlencode(htmlstring($game->instructions)), 'author' => urlencode(htmlstring($game->developer_name))); 
        
}

$data = json_decode(json_encode($array));

foreach($data as $value) { 
    
    $count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT COUNT(`id`) FROM `game_feed` WHERE `hash_id`='".mysqli(urldecode($value->hash_id))."' AND `source`='".urldecode($value->source)."'")));

    if ($count == 0) {
        
        $hash_id = mysqli(urldecode($value->hash_id));

        $source = mysqli(urldecode($value->source));

        $width = mysqli(urldecode($value->width));
        
        $height = mysqli(urldecode($value->height));

        $category = mysqli(urldecode($value->category));

        $title = mysqli(urldecode($value->title));

        $description = mysqli(urldecode($value->description));
        
        $controls = mysqli(urldecode($value->controls));

        $author = mysqli(urldecode($value->author));

        $thumbnail = mysqli(urldecode($value->thumbnail));
        
        $file = mysqli(urldecode($value->file));
        
        mysqli_query($setting['Lid'],"INSERT INTO `game_feed` (`hash_id`, `name`, `description`, `thumb_url`, `file_url`, `width`, `height`, `category`, `instructions`, `author`, `source`) VALUES ('".$hash_id."', '".$title."', '".$description."', '".$thumbnail."', '".$file."', '".$width."', '".$height."', '".$category."', '".$controls."', '".$author."', '".$source."')");

    }

}

?>