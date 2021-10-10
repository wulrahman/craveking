<?php

$q = mysqli($_GET['q']);

$title="Cragglist ".htmlspecialchars($q);

require_once('include/header.php');

require_once('include/main_header.php');

$limit = '9';

$page = intval($_GET["page"]);

if ($page=="") {

	$page = 1;

}

$start = ($page-1) * $limit;

foreach(explode(" ",$q) as $p) {

	if(!space($p)) {

		$match[] = '('.$p.'*)';

	}

}

$matchs = implode(' ', $match);

$query = mysqli_query($setting["Lid"],"SELECT 

SQL_CALC_FOUND_ROWS

`id`, `type` 

FROM 

    (
        SELECT 

            `products`.`id`, '1' AS `type`,

            ((MATCH(`products`.`description`) AGAINST ('".$matchs."' IN BOOLEAN MODE)) + (MATCH(`products`.`name`) AGAINST ('".$matchs."' IN BOOLEAN MODE))) AS `relevance`

            FROM 

                `products`

            WHERE 

            `products`.`draft` = '0' AND `products`.`outdated` = '0'

            HAVING Relevance > 0

    ) AS `table`

ORDER BY `relevance` DESC

LIMIT ".$start.", ".$limit."

");

?>

<main>

	<div class="otherbody">

		<?php require_once("../common/include/ads.php"); ?>

	</div>

	<article id="otherbody">
        
        <div id="item-list">

			<?php

			$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

			if($count == 0) {

			?><div>

					<h1>No results for <b><?=htmlspecialchars($_GET['q'])?></b></h1>

				</div><?php

			}
			else { ?>

				<h1>Results for: <b><?=htmlspecialchars($_GET['q'])?></b></h1>

                <ul>

                    <?php

                    $i = 0;

                    while ($row = mysqli_fetch_object($query)) {

                        if($row->type == 1) {

                            $item_query = mysqli_query($setting["Lid"],"SELECT

                            `products`.`feature`, `products`.`name`, `products`.`price`, `products`.`oldprice`, `products`.`tile`, `products`.`id`, `products`.`free`, `products`.`description`, `products`.`views`, `products`.`icon`,

                            (SELECT `thumb` FROM `image` WHERE `product`=`products`.`id` ORDER BY `order` LIMIT 0, 1) as `thumb`

                            FROM 

                                `products`

                            WHERE 

                                `products`.`id` = '".$row->id."'");

                            $row = mysqli_fetch_object($item_query);

                            if($row->price == 0 || $row->free == 1) {

                                $row->price = "Free";

                            }
                            else {

                                $row->price = money_format($setting["format_currency"], $row->price);

                            }

                            if($row->type == 0 || $row->type == "") {

                                $type = "Download";

                            }
                            else if($row->type == 1) {

                                $type = "Shippable";

                            }
                            else if($row->type == 2) {

                                $type = "Service";

                            }
                            else if($row->type == 3) {

                                $type = "Video";

                            }
                            else if($row->type == 4) {

                                $type = "Article";

                            }

                            ?>

                            <li class="pure-u-1">

                                <?php

                                $i++;

                                if($i % 2 == 0) {

                                    echo '<div class="item-list pure-g item-list-even">';

                                }
                                else {

                                    echo '<div class="item-list pure-g">';

                                }

                                ?>

                                <a href="<?=$setting["store_url"]?>/product/<?=$row->id?>"><div class="pure-u-5-24">

                                        <img class="item-thumb" src="<?=$row->icon?>">

                                    </div>

                                    <div class="pure-u-13-24 item-list-product"><h5 class="item-name"><?=limit_text($row->name,9)?></h5>

                                        <p class="item-desc">

                                            <?=limit_text(strip_tags(indextext($row->description)),30)?>

                                        </p>

                                    </div>

                                    <div class="pure-u-3-24 item-list-price"><span class="item-price"><?=$row->price?></span></div>

                                </a>

                                </div>

                            </li>

                            <?php

                        }
                        else if($row->type == 2) {

                            $item_query = mysqli_query($setting["Lid"],"SELECT

                            `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`reply_to`, `ticket`.`reply`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`views`, `ticket`.`spam`, `ticket`.`post`,

                            (SELECT `icon` FROM `users` WHERE `id` = `ticket`.`user`) as `usericon`

                            FROM

                                `ticket`

                            WHERE

                               `ticket`.`reply`='0'");

                            $row = mysqli_fetch_object($item_query);

                            $id_id = $row->id;

                            if($row->reply == "1") {

                                $id_id = $row->post;

                            }

                            $reply=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT COUNT(`id`) FROM `ticket`  WHERE `reply`='1' AND `reply_to`='".$row->id."'")));

                            $topic_user = getUser($row->user);

                            ?>

                            <li class="item-list pure-g category_home">

                                <a href="<?=$setting["forum_url"]?>/view/<?=$id_id?>">

                                    <div class="topic-item-main">

                                        <div class="icon pure-u-5-24 hide_mobile">

                                            <?php

                                            if($topic_user->icon == "") {

                                                echo '<div class="item-thumb" style="background-color:#'.$topic_user->color.';">'.ucfirst(substr($topic_user->username,0,1)).'</div>';

                                            }
                                            else {

                                                echo '<img class="item-thumb" src="'.$setting["url"].'/common/'.$topic_user->icon.'">';

                                            }

                                            ?>

                                        </div><div class="pure-u-13-24 topic-item-topic">

                                            <?php

                                            if($row->reply_to > 0) {

                                                $reply_to = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `ticket`.`user` FROM `ticket` WHERE `id` = '".$row->reply_to."'"));

                                                $reply_user = getUser($reply_to->user);

                                                if($reply_to->user != $row->user) {

                                                    echo '<p class="reply_to">reply to <span class="reply_user">'.$reply_user->username.'</span></p>';

                                                }

                                            }

                                            ?>

                                            <h5 class="topic-user-name"><?=limit_text($topic_user->username,3)?></h5>

                                            <span class="topic-date"><?=time_elapsed_string(strtotime($row->timestamp))?></span>

                                            <h5 class="topic-item-name">

                                                <?=limit_text($row->subject,9)?>

                                            </h5>

                                            <div class="item-desc"><?=limit_text(strip_tags($row->ticket),30)?></div>

                                        </div><div class="pure-u-3-24 topic-item-static">

                                            <?=$reply?> <b>REPLIES</b>

                                        </div><div class="pure-u-3-24 topic-item-static">

                                            <?=$row->views?> <b>VIEWS</b>

                                        </div>

                                    </div>

                                </a>

                            </li><?php
                        }
                        else if($row->type == 3) {

                            $item_query = mysqli_query($setting['Lid'], "SELECT 

                            `games`.`category`, `games`.`id`, `games`.`name`, `games`.`image`, `games`.`description`

                            FROM 

                                `games` 

                            WHERE 

                                `games`.`published`='1' AND `games`.`id` = '".$row->id."'");

                            $row = mysqli_fetch_object($item_query);

                            ?>

                            <li class="game_itemlist">

                                <?php

                                $i++;

                                if($i % 2 == 0) {

                                    echo '<div class="item-list pure-g item-list-even">';

                                }
                                else {

                                    echo '<div class="item-list pure-g">';

                                }

                                ?>

                                 <a title="<?=$row->description?>" href="http:<?=$setting["games_url"]?>/game/<?=$row->id?>">

                                     <div class="pure-u-5-24">

                                        <img src="<?=$row->image?>" alt="<?=$row->name?>" class="item-thumb">

                                    </div>

                                     <div class="pure-u-13-24 item-list-product"><h5 class="item-name"><?=limit_text($row->name,9)?></h5>

                                        <p class="item-desc">

                                            <?=limit_text(strip_tags(indextext($row->description)),30)?>

                                        </p>

                                    </div>

                                </a>

                                </div>

                            </li><?php

                        }
                        else if($row->type == 4) {

                            $item_query = mysqli_query($setting['Lid'], "SELECT 

                            `news`.`title`, `news`.`id`, `news`.`thumb_url`,  `news`.`source_url`, `news`.`description`

                            FROM 

                                `news` 

                            WHERE 

                                `news`.`id` = '".$row->id."'");

                            $row = mysqli_fetch_object($item_query);

                            ?>

                            <li class="game_itemlist">

                                <?php

                                $i++;

                                if($i % 2 == 0) {

                                    echo '<div class="item-list pure-g item-list-even">';

                                }
                                else {

                                    echo '<div class="item-list pure-g">';

                                }

                                ?>

                                 <a href="<?=$row->source_url?>">

                                     <div class="pure-u-5-24">

                                        <img src="<?=$setting['main_url']?>/main/<?=$row->thumb_url?>" alt="<?=$row->name?>" class="item-thumb">

                                    </div>

                                     <div class="pure-u-13-24 item-list-product"><h5 class="item-name"><?=limit_text($row->title,9)?></h5>

                                        <p class="item-desc">

                                            <?=limit_text(strip_tags(indextext($row->description)),30)?>

                                        </p>

                                    </div>

                                </a>

                                </div>

                            </li><?php

                        }

                    }

                    ?>

                </ul>

                <div class="pagination">

                    <?php

                    $previous = $page-1;

                    $next = $page+1;

                    $total = ceil($count / $limit);

                    $url =   $setting["url"].'/content?q='.urlencode($q).'&page=';

                    if ($page > 1){

                        echo '<li><a href="'.$url.$previous.'">Previous</a></li>';

                    }

                    for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {

                        echo '<li><a href="'.$url.$i.'">'.$i.'</a></li>';

                    }

                    if ($page < $total){

                        echo '<li><a href="'.$url.$next.'">Next</a></li>';

                    }

                    ?>

                </div><?php

            }

            ?>

        </div>

        <?php require_once("include/main_footer.php"); ?>

	</article>

</main>

<?php require_once("include/footer.php"); ?>