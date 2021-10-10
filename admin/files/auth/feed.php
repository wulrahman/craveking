 
<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');
    
    $page=intval($_GET['page']);

    if ($page == 0) {

        $page = 1;

    }

    $limit = 16;
    
    $categorys = str_replace("and", "&", $_GET['category']);
                    
    $categorys = str_replace("-", " ", $categorys);
    
    if(isset($categorys)) {
        
        $count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT COUNT(`id`) FROM `game_feed` WHERE `category`='".$categorys."'")));
        
        if($count > 0) {
            
            $addsql_array[] = "`category`='".$categorys."'";
            
        }   
        else {
            
            $categorys = 'all';
            
        }

    }
    else {
        
        $categorys = 'all';
        
    }
    
    if(isset($_POST['install'])) {
        
        ini_set("memory_limit","70M");
 
        $query = mysqli_query($setting['Lid'],"SELECT * FROM `game_feed` WHERE `id`='".intval($_POST['game_id'])."'");

        $row = mysqli_fetch_object($query);

		$salt = randomurl(
		randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"])
		).randomurl(
		randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"])
		).randomurl(
		randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"])
		).randomurl(
		randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"])
		).randomurl(
		randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"])
		).date('mdYhis', time());
        
        $file = file_get_contents($row->file_url);
        
        $file_url = reset((explode('?', $row->file_url)));
        
        $type_file = substr($file_url, strrpos($file_url, '.') + 1);

        $array_file = array("swf", "unity3d", "dcr", "html");

        if (!in_array($type_file, $array_file)) {

            $type_file = 'swf';

        }
        
        if($type_file == "html") {
            
            $data = url_info($row->file_url);

            $code = closetags(convert_encoding(stripslashes($data['response'])));
            
            $url = $row->file_url;
            
        }
        else {

            $new_file = fopen("../games/games/".$salt.".".$type_file,"wb");

            fwrite($new_file, $file);

            fclose($new_file);
        
            $url = $setting['games_url']."/games/".$salt.".".$type_file;
            
        }
        
        $thumb = file_get_contents($row->thumb_url);
        
        $thumb_url = reset((explode('?', $row->thumb_url)));
        
        $type_image = substr($thumb_url, strrpos($thumb_url, '.') + 1);

        $array_image = array("gif", "jpeg", "png", "jpg");

        if (!in_array($type_image, $array_image)) {

            $type_image = 'png';

        }
        
        $new_thumb = fopen("../games/games/images/".$salt.".".$type_image,"wb");

        fwrite($new_thumb, $thumb);

        fclose($new_thumb);
        
        $thumb_url = $setting['games_url']."/games/images/".$salt.".".$type_image;

        $query = mysqli_query($setting['Lid'],"SELECT SQL_CALC_FOUND_ROWS `id`, `name` FROM `cats` WHERE `name`='".$row->category."'");

        $count = array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT FOUND_ROWS()")));

        if($count == 0) {

            $seo_url = create_seoname($row->category, 0, 'cats');

            mysqli_query($setting['Lid'],"INSERT INTO `cats`(`name`, `seo_url`) VALUES ('".$row->category."', '".$seo_url."');");

            $category->id = mysqli_insert_id($setting['Lid']);

        }
        else {

            $category = mysqli_fetch_object($query);

        }

        mysqli_query($setting['Lid'],"INSERT INTO `games` (`name`, `description`, `url`, `category`, `filetype`, `width`, `height`, `image`, `instructions`, `code`) 
        VALUES ('".mysqli($row->name)."', '".mysqli($row->description)."', '".$url."', '".$category->id."', '".$type_file."', '".mysqli($row->width)."', '".mysqli($row->height)."', '".$thumb_url."', '".mysqli($row->instructions)."', '".mysqli($code)."')");
        
        $new_id = mysqli_insert_id($setting['Lid']);
        
        if($new_id != 0) {

            mysqli_query($setting['Lid'],"UPDATE `game_feed` SET `visible`='2' WHERE `id`='".$row->id."'");
            
        }
        
    }
    
    ?>        

    <div class="category_nav">

        <nav>

            <ul>

                <li><a href="<?=$setting["admin_url"]?>/feed/all">All</a></li>

                <?php

                $category_query = mysqli_query($setting['Lid'],"SELECT COUNT(*) AS `Rows`, `category` FROM `game_feed` GROUP BY `category` ORDER BY `category`");

                while ($category = mysqli_fetch_object($category_query)) {
                    
                    $name = str_replace("&", "and", $category->category);
                    
                    $name = str_replace(" ", "-", $name);

                    echo '<li><a href="'.$setting["admin_url"].'/feed/'.$name.'">'.$category->category.'</a></li>';

                }
                       
                ?>

            </ul>

        </nav>

    </div>

    <main>

        <article id="otherbody">
            
            <div>
                
                <div>

                    <div>

                        <a href="<?=$setting["admin_url"]?>/kongregate">Kongregate</a>

                    </div>

                    <div>

                        <a href="<?=$setting["admin_url"]?>/fog">FOG</a>

                    </div>

                </div>

                <?php
    
                if(count($addsql_array) > 0) {

				    $addsql = " AND ".implode(" AND ", $addsql_array);

			     }

                $query = mysqli_query($setting['Lid'],"SELECT SQL_CALC_FOUND_ROWS * FROM `game_feed` WHERE `visible` = '0' ".$addsql." ORDER BY `id` DESC LIMIT ".(($page-1)*$limit).",".$limit."");

                $count=array_pop(mysqli_fetch_array(mysqli_query($setting['Lid'],"SELECT FOUND_ROWS()")));

                if($count > 0) { 

                ?>

                    <div id="item-list">

                        <ul>

                            <?php

                            while ($row = mysqli_fetch_object($query)) { 

                                $url=$setting["admin_url"]."/admin/?task=edit_game&id=".$row->id;

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

                                         <div class="pure-u-3-24 hide_mobile">

                                            <img src="<?=$row->thumb_url?>" alt="<?=$row->name?>" class="item-thumb">

                                        </div>

                                        <div class="pure-u-16-24 item-list-product"><h5 class="item-name"><?=limit_text($row->name,9)?></h5>

                                            <p class="item-desc">

                                                <?=limit_text(strip_tags(indextext($row->description)),30)?>

                                            </p>

                                        </div>
                                         
                                         <div class="pure-u-5-24 item-list-product">
                                             
                                             <form method="post">
                                                 
                                                 <input type="hidden" name="game_id" value="<?=$row->id?>">
                                                 
                                                 <input type="submit" name="install" value="install">
                                             
                                             </form>

                                        </div>

                                    </div>

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
                    
                            $url = $setting["admin_url"].'/feed/'.$categorys.'/';

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

                    <?php

                }
                else {

                    echo '<div class="errors">No games where found.</div>';

                }

            ?></div>

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