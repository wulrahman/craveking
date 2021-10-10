<?php

$title="Topic";

if($user->admin == 1) {

    $limit = '16';

    $page=intval($_GET["page"]);

    if ($page=="") {

        $page=1;

    }

    $start = ($page-1) * $limit;

    $query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`views`, `ticket`.`reply_to`, `ticket`.`reply`, (SELECT `icon` FROM `users` WHERE `id` = `ticket`.`user`) as `usericon`, (SELECT `temp`.`id` FROM `ticket` as `temp` WHERE `temp`.`reply_to` = `ticket`.`id` ORDER BY `temp`.`id` DESC LIMIT 1) AS `activeorder` FROM `ticket` WHERE `ticket`.`reply`='0' ORDER BY IFNULL(`activeorder`, `ticket`.`id`) DESC LIMIT ".$start.", ".$limit."");

    $count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

    require_once('include/header.php');

    require_once('include/main_header.php');

    ?>

    <main>
        
        <article id="forumbody">

            <div>

                <?php

                $urls = $setting["url"].'/topics/'.$id.'/';

                require_once('include/main_forum.php');

                ?>

            </div>

            <?php require_once("../common/include/main_footer.php");?>

        </article>

    </main>

    <?php

    require_once('../common/include/footer.php');

    
}
else {

    require_once("../common/pages/404.php");

}
    
?>
