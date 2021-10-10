<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$limit = '16';

	$page=intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

    $query = mysqli_query($setting["Lid"], "SELECT

    SQL_CALC_FOUND_ROWS

    `topic`.`name`, `topic`.`description`, `topic`.`id`, `topic`.`views`, `topic`.`color`,

    (SELECT COUNT(`id`) FROM `ticket` WHERE `topic`=`topic`.`id`) as `count`

    FROM `topic`

    WHERE `topic`.`sub`='0'

    ORDER BY `topic`.`id` ASC

    LIMIT ".$start.", ".$limit."");

    $count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

    ?>

    <main>

        <article id="forumbody">
            
            <div>

                <?php

                if ($count > 0) { ?>

                        <div class="mobile_display">

                            <div id="item-list">

                                <ul>

                                    <?php

                                    while($row = mysqli_fetch_object($query)) {

                                        if($row->color == "") {

                                            $row->color = random_color();

                                            mysqli_query($setting["Lid"],"UPDATE `topic` SET `color` = '".$row->color."' WHERE `topic`.`id` = '".$row->id."';");

                                        }

                                        ?>

                                        <li class="item-list pure-g category_home">

                                            <a href="<?=$setting["admin_url"]?>/category/<?=$row->id?>">

                                                <div class="topic-item-main">

                                                    <div class="icon pure-u-3-24 hide_mobile">

                                                        <div class="item-thumb" style="background-color:#<?=$row->color?>"><?=ucfirst(substr($row->name,0,1))?></div>

                                                    </div><div class="pure-u-15-24 topic-item-topic">

                                                        <h5  class="topic-item-name-home">

                                                            <?=limit_text($row->name,9)?>

                                                        </h5>

                                                        <div class="topic-item-description"><?=limit_text(strip_tags($row->description),30)?></div>

                                                    </div><div class="pure-u-3-24 topic-item-static">

                                                        <?=$row->count?> <b>TOPIC</b>

                                                    </div><div class="pure-u-3-24 topic-item-static">

                                                        <?=$row->views?> <b>VIEWS</b>

                                                    </div>

                                                </div>

                                            </a>

                                        </li>

                                    <?php

                                    }

                                    ?>

                                </ul>

                                <div class="pagination">

                                    <?php

                                    $previous = $page-1;

                                    $next = $page+1;

                                    $total = ceil($count / $limit);

                                    $url = $setting["admin_url"].'/categories/';

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

                                </div>

                            </div>

                        </div>

                <?php

                }
                else {

                        ?>

                        <div>

                            <h1>No post where found.</h1>

                        </div><?php

                }
                ?>

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