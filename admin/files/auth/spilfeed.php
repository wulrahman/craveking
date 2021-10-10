<?php

$feed = 'http://publishers.spilgames.com/en/rss-3?lang=en-US&limit='.$feed_setting['max_feed'].'&format=json';

if ($feed_setting['curl'] == 1) {
    
	$data = curl($feed);
    
}
else {
    
	$data = file_get_contents($feed);
    
}

echo 'Downloading feed via '.$get_type.'...<br /><br />';

$out = json_decode($data, true);

$i = 0;

$out = $out["entries"];

foreach($out as $key => $game) {
    
	$count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT COUNT(`id`) FROM `spil` WHERE `spil_id`='".intval($game['id'])."'")));
    
	if ($count == 0) {
        
		mysqli_query($setting['Lid'],"INSERT INTO `spil` (`spil_id`, `name`, `description`, `thumb_url`, `file_url`, `width`, `height`, `category`) VALUES ('".mysqli($game['id'])."', '".mysqli($game['title'])."', '".mysqli($game['description'])."', '".mysqli($game['thumbnails']['medium'])."', '".mysqli($game['gameUrl'])."', '".mysqli($game['width'])."', '".mysqli($game['height'])."', '".mysqli($game['category'])."')");
		
        $i = $i + 1;
        
	}
    
}

?>

<?=$i?> games added to database

<br />

<br />

<div class="mochi_buttons2">
    
    <div class="mochi_button">
        
        <a href="?task=feed#page=1&cat=All">Feed</a>
    
    </div>
    
</div>

<br style="clear:both" />