<?php

if($user->admin == 1) {
    
    $id = intval($_GET['id']);

	$row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id`, `type`, `name`, `description`, `url`, `category`, `published`, `width`, `height`, `image`, `filetype`, `instructions`, `featured`, `code`, `stage3d` FROM `games` WHERE `id`='".$id."'"));

	$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count > 0) {
        
        require_once('include/header.php');
        
        require_once('include/main_header.php');
        
        require_once('include/main_nav.php');

        if(isset($_POST['submit']) || isset($_POST['publish'])) {
            
			$description = addslashes(closetags(convert_encoding($_POST['description'])));
            
            $instructions = addslashes(closetags(convert_encoding($_POST['instructions'])));

			$name = htmlstring(convert_encoding(htmlentities($_POST['name'])));
            
            $code = addslashes(closetags(convert_encoding($_POST['code'])));
            
			$category = intval($_POST['category']);

			$published = intval($_POST['published']);
            
            $featured = intval($_POST['featured']);
            
            $width = $_POST['width'];
            
            $type = $_POST['type'];

			$height = $_POST['height'];
            
            $image = $row->image;
            
            $url = $row->url;
            
            if($row->type == 1) {
                
                $url = $_POST['url'];
                
            }
            
            if(isset($_FILES["file"]) || (!isset($_POST['url']) && !isset($_POST['html']))) {
        
                if ($_FILES["file"]["error"] > 0) {

                   $errors_array[] ="Return Code: ".$_FILES["file"]["error"];

                }
                else {

                    $game = "../games/games/".$_FILES["file"]["name"];
                        
                    $filename = basename($_FILES['file']['name']);

                    $ext = substr($filename, strrpos($filename, '.') + 1);

                    if (file_exists($game)) {

                        $errors_array[] = $_FILES["file"]["name"]." already exists.";

                    }
                    else if ((strpos($ext, "php") !== false) || $ext == 'aspx' || $ext == 'py' || $ext == 'htaccess') {

                        $errors_array[] = 'Uploading PHP files disabled';

                    }
                    else {
                        
                        $url = $setting['url']."/games/games/".$_FILES["file"]["name"];

                        move_uploaded_file($_FILES["file"]["tmp_name"], $game);

                    }

                }

            }
            
            if(isset($_FILES["image"])) {
        
                if ($_FILES["image"]["error"] > 0) {

                   $error[] ="Return Code: ".$_FILES["image"]["error"];

                }
                else {

                    $thumb = "../games/games/images/".$_FILES["image"]["name"];
                        
                    $filename = basename($_FILES['image']['name']);

                    $ext = substr($filename, strrpos($filename, '.') + 1);

                    if (file_exists($thumb)) {

                        $errors_array[] = $_FILES["image"]["name"]." already exists.";

                    }
                    else if ((strpos($ext, "php") !== false) || $ext == 'aspx' || $ext == 'py' || $ext == 'htaccess') {

                        $errors_array[] = 'Uploading PHP files disabled';

                    }
                    else {
                        
                        $image = $setting['url']."/games/games/images/".$_FILES["image"]["name"];

                        move_uploaded_file($_FILES["image"]["tmp_name"], $thumb);

                    }

                }

            }
            
            $filetype = substr($url, strrpos($url, '.') + 1);
            
            if($type == 2) {
                
                $filetype = "code";
                
            }
            
            $category = mysqli_fetch_object(mysqli_query($setting['Lid'],"SELECT `id` FROM `cats` WHERE `id` = '".$category."'"));
            
            mysqli_query($setting['Lid'],"UPDATE `games` SET `name`='".$name."', `description`='".$description."', `url`='".$url."', `category`= '".$category->id."', `category_parent`='".$category->parent_id."', `width`='".$width."', `height`='".$height."', `image`='".$image."', `published`='".$published."', `featured`='".$featured."', `filetype`='".$filetype."', `type`='".$type."', `instructions`='".$instructions."', `code` = '".$code."' WHERE `id`='".$row->id."'");
        
        }
        
        $row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id`, `type`, `name`, `description`, `url`, `category`, `category_parent`, `published`, `width`, `height`, `image`, `filetype`, `instructions`, `featured`, `code`, `stage3d` FROM `games` WHERE `id`='".$id."'"));

		?>

        <main>

            <article>

                <div id="form-main">

                    <form method="post" enctype="multipart/form-data">

                        <div class="main-form">

                            <label>Game name</label>

                            <input name="name" type="text" value="<?=$row->name?>"/>

                            <label>Description</label>

                            <textarea name="description"><?=$row->description?></textarea>

                            <label>Instructions</label>

                            <textarea name="instructions"><?=$row->instructions?></textarea>
                            
                            <?php
                            
                            if($row->type == 2) {
                                
                                echo '<label>Code</label>
                                <textarea name="code" class="widgEditor nothing" id="noise" >'.stripslashes(closetags(convert_encoding($row->code))).'</textarea>';
                            
                            }
                            else if($row->type == 1){
                            
                                echo '<label>Url</label>
                                <input name="url" type="text" value="'.$row->url.'"/>';
                            
                            }
                            else if($row->type == 0) {
                            
                            ?>                     
                                <label>File</label>

                                <div id="game-upload" class="game-file">

                                    <div id="game-file">

                                            <div id="game-upload" class="game-file">

                                                <div class="game-upload-file">

                                                    <input class="inputfile" name="file" id="file" type="file">

                                                    <label for="file"><span class="fa fa-download icon" aria-hidden="true"></span>

                                                    <span>Upload File</span></label>

                                                </div>

                                            </div>

                                     </div>

                                </div>
                            
                            <?php
                            
                            }
                            
                            ?>

                            <label>Image</label>

                            <div id="game-upload" class="game-image">

                                <div id="game-image">

                                        <div id="game-upload" class="game-image">

                                            <div class="game-upload-image">

                                                <input class="inputfile" name="image" id="image" type="file">

                                                <label for="image"><span class="fa fa-download icon" aria-hidden="true"></span>
                                                
                                                <span>Upload Image</span></label>

                                            </div>
                                            
                                        </div>

                                 </div>

                            </div>

                            <label>Dimensions</label>

                            <div>

                                <input name="width" type="text" value="<?=$row->width?>" size="3" /> x 

                                <input name="height" value="<?=$row->height?>" type="text" size="3" /> 

                            </div>

                            <label>Category</label>

                            <select name="category">
                                
                                <?php

                                $category_query = mysqli_query($setting['Lid'], "SELECT * FROM `cats` ORDER BY `cat_order` ASC");
    
                                while($category_row = mysqli_fetch_object($category_query)) {

                                    echo '<option value="'.$category_row->id.'"';

                                    if ($category_row->id == $row->category) {

                                        echo ' selected';

                                    }

                                    echo '>'.$category_row->name.'</option>'; 

                               }
                                
                                ?>

                            </select>

                            <label>Type</label>

                            <select name="type">
                                
                                <?php 
                                
                                $array_type = array(0 => 'file', 1 => 'url', 2 => 'code');
        
                                foreach($array_type as $key => $types) {
                                    
                                    $selected = "";
                                    
                                    if($row->type == $key) {
                                        
                                       $selected = 'selected';
                                        
                                    }
                                    
                                    echo '<option value="'.$key.'" '.$selected.'>'.$types.'</option>';
                                
                                }
    
                                ?>
                                
                            </select>

                            <label>Published</label>
                            
                            <?php
                            
                            if($row->published == 1) {
                            
                                $published_checked = "checked";
                            
                            }
        
                            ?>

                            <input type="hidden" name="published" value="0">
                            
                            <input type="checkbox" <?=$published_checked?> name="published"  value="1">
                                  
                            <label>Featured</label>

                            <?php
                            
                            if($row->featured == 1) {
                            
                                $featured_checked = "checked";
                            
                            }
        
                            ?>
                            
                            <input type="hidden" name="featured" value="0">
                            
                            <input type="checkbox" <?=$featured_checked?> name="featured" value="1">

                        </div>

                        <div class="form-content-footer pure-g">

                            <div class="pure-u-1-2"></div>

                            <div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Update"></div>

                        </div>

                    </form>

                </div>

                <?php require_once("../common/include/main_footer.php");?>

           </article>

        </main>

        <script type="text/javascript" src="<?=$setting['url']?>/common/files/js/editor.js"></script>

    <?php
        
    }
    
	require_once("../common/include/main_footer.php");
    
}
else {

	require_once('../common/pages/404.php');

}

?>