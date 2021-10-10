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

                $feed = 'http://www.kongregate.com/games_for_your_site.xml';

                if ($feed_setting['curl'] == 1) {

                    $data = curl($feed);

                }
                else {

                    $data = file_get_contents($feed);

                }

                $xml = simplexml_load_string($data);

                $i = 0;

                foreach($xml->game as $game) {	

                    $count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT COUNT(`id`) FROM `game_feed` WHERE `hash_id`='".mysqli($game->id)."' AND `source` = 'kongregate'")));

                    if ($count == 0) {

                        mysqli_query($setting['Lid'],"INSERT INTO `game_feed` (`hash_id`, `name`, `description`, `thumb_url`, `file_url`, `width`, `height`, `author`, `category`, `instructions`, `source`) VALUES ('".mysqli($game->id)."', '".mysqli($game->title)."', '".mysqli($game->description)."', '".mysqli($game->thumbnail)."', '".mysqli($game->flash_file)."', '".mysqli($game->width)."', '".mysqli($game->height)."', '".mysqli($game->developer_name)."', '".mysqli($game->category)."', '".mysqli($game->instructions)."', 'kongregate')");
                        $i += 1;

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