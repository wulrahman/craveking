<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');
    
    ?>

    <main>

        <article id="otherbody">

            <div>
        
                <?php

                $feed = 'http://www.freegamesforyourwebsite.com/feeds/games/?category=all&thumb=small&limit='.$feed_setting['max_feed'].'&format=json';

                if ($feed_setting['curl'] == 1) {

                    $data = curl($feed);

                } 
                else {

                    $data = file_get_contents($feed);

                }

                $out = json_decode($data, true);

                $i = 0;

                foreach($out as $game) {

                    $count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT COUNT(`id`) FROM `game_feed` WHERE `hash_id`='".mysqli($game['id'])."' AND `source`='fog'")));

                    if ($count == 0) {

                        $category = mysqli($game['category']);

                        $dimensions =  explode('x', mysqli($game['resolution']));

                        mysqli_query($setting['Lid'],"INSERT INTO `game_feed` (`hash_id`, `name`, `description`, `thumb_url`, `file_url`, `width`, `height`, `category`, `instructions`, `tags`, `source`) VALUES ('".mysqli($game['id'])."', '".mysqli($game['title'])."', '".mysqli($game['description'])."', '".mysqli($game['med_thumbnail_url'])."', '".mysqli($game['swf_file'])."', '".$dimensions['0']."', '".$dimensions['1']."', '".$category."', '".mysqli($game['controls'])."', '".$tags."', 'fog')");
                        $i = $i + 1;

                    }

                }

                ?>

                <?=$i?> games added to database

            </div>

            <?php require_once("../common/include/main_footer.php");?>

	   </article>
    
    </main>

    <?php
    
	require_once("../common/include/main_footer.php");
    
}
else {

	require_once('../common/pages/404.php');

}

?>
