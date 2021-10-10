<?php

$title="Cragglist Search";

require_once("include/header.php");

?>

<div class="main_home">
    
    <div class="main_menu">

        <label class="collapse" for="_1"><img class="menu_image" src="<?=$setting["search_url"]?>/files/image/menu_main.png"></label>
        <input id="_1" type="checkbox">
        <div>
            <nav class="main_home_search">
                <ul>
                    <?php

                    require_once("include/search_nav.php");

                    include("include/sub_user_menu.php");

                    ?>
                </ul>
            </nav>
        </div>
    </div>

    <form action="<?=$setting["search_url"]?>" class="form-primary">
        <img class="logo-primary" src="<?=$setting["search_url"]?>/files/image/cloud_logo.png" alt="">
        <div class="main_suggestion">
        <input type="text" name="q" class="txt-primary" placeholder="search" autofocus autocomplete="off" value="<?=htmlspecialchars($_GET['q'])?>" id="q">
        <datalist class="home_suggestion" id="suggestions">
        </datalist>
        </div>
        <input type="hidden" name="tbm" value="<?=htmlspecialchars($_GET['tbm'])?>">
        <input type="image" src="<?=$setting["search_url"]?>/files/image/search.png" name="submit" class="btn-primary">
    </form>

    <?php

    require_once("include/footer.php");

    ?>
    
</div>

</body>
</html>
