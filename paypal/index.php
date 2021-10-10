<?php

ob_start();

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

if($user->login_status == 1) {
    
    if($setting["sandbox"] == 1) {

        $url = "https://www.sandbox.paypal.com/cgi-bin/webscr?";

    }
    else {

        $url = "https://www.paypal.com/cgi-bin/webscr?";

    }
                
	if(isset($_POST['carts'])) {

		require_once("config.php"); ?>

            <form action="<?=$url?>" name="paypalform" method="post">
                    
                <?php
                
                foreach($paypal as $key => $value) {

				    echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';

				}
                
                ?>
                    
            </form>

            <script type="text/javascript"> 
                window.onload=function(){
                    document.forms['paypalform'].submit();
                }
            </script>

        <?php

	}
	else {

		$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `name`, `price`, `id`, `free` FROM `products` WHERE `id`='".intval($_POST['item_number'])."'");

		$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

		if ($count > 0) {

			$row = mysqli_fetch_object($query);

			require_once("config.php");

			mysqli_free_result($query);

			if($row->free !== "1") { ?>

                <form action="<?=$url?>" name="paypalform" method="post">
                    
                    <?php
                
                    foreach($paypal as $key => $value) {

					   echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';

				    }
                
                    ?>
                    
                </form>

                <script type="text/javascript"> 
                    window.onload=function(){
                        document.forms['paypalform'].submit();
                    }
                </script>

                <?php

			}

		}

	}

}
else {

	header('location: '.$setting["url"].'/login');

}

?>
