<?php

ob_start();

$id = intval($_GET['id']);

$query=mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `type`, `outdated`, `icon`, `name`, `price`, `oldprice`, `size`, `version`, `description`, `id`, `free`, `views`, `downloads` FROM `products` WHERE `id`='".intval($_GET['id'])."' AND `draft`='0'");

$total_count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($total_count > 0) {

	$row = mysqli_fetch_object($query);

	mysqli_query($setting["Lid"],"UPDATE `products` SET `views` = `views` + 1 WHERE `id`='".$row->id."'");

	$title=$row->name;

	$keywords = extractCommonWords($row->description, 20);

	$keywords = implode(',', array_keys($keywords));

	require_once('include/header.php');
    
    require_once('include/main_header.php');

	if($user->login_status == 1) {

		$purchased = mysqli_fetch_object(mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS * FROM(SELECT `product`, `paid`, `status` FROM `purchase` WHERE `user`="'.$user->id.'" AND `product`="'.$row->id.'" ORDER BY `id` DESC) as inv GROUP BY `product`'));

	}
	if ($row->free =="1") {

		$displayprice = "Free";

	}
	else {

		$displayprice = money_format($setting["format_currency"], $row->price);

	}

    if($row->type == 3 || $row->type == 4) { ?>

        <script>
            var id = "<?=$row->id?>";
            var url_post = "<?=$setting["store_url"]?>/ajax/comment.php";
        </script>
        <script src="<?=$setting["url"]?>/common/files/js/form.js?q=7"></script><?php
        
    }
    
    ?>

	<main>

        <div class="hero-background">
        
            <div>

                <img class="strips" src="<?=$setting["store_url"]?>/store/files/images/strips.png">

            </div>
        
            <div class="landing-page">

                <div class="landing-page-image_main mobile_display">

                    <?php

                    $landing_thumb = mysqli_fetch_object(mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `thumb`,`image`, `id` FROM `image` WHERE `product`="'.$row->id.'" ORDER BY `order` ASC LIMIT 0, 1'));

                    ?>
                    <div class="thumb_main" style="background-image:url(<?=$setting['url']?>/admin/<?=$landing_thumb->image?>)"></div>

                </div>

                <div><img class="mouse" src="<?=$setting["store_url"]?>/store/files/images/mouse.svg"></div>

            </div>

        </div>
        
        <div id="pricing" class="pricing-background">

            <h2 class="pricing-section-header light text-center">PRICING</h2>

            <h4 class=" pricing-section-sub text-center light">Our super affordable pricing</h4>

            <div class="pricing-table row">

                <div>

                    <div class="mid-plan">

                        <h4 class="plan-cost bold"><?=$row->price?></h4>

                        <div class="plan-price-div text-center">

                            <div class="choose-plan-div">
                                
                                <div>

                                    <?php

                                    if($row->type == 0 || $row->type == 1 || $row->type == 2) {

                                        ?><div class="product-viewright">

                                            <div class="main-viewright">

                                                <div class="product-buttons"><?php

                                                    if($row->type == 0) { ?>

                                                        <div class="product-demo">

                                                            <a alt="demo" href="<?=$setting["store_url"]?>/demo/<?=$row->id?>">

                                                                <img src="<?=$setting["url"]?>/common/files/img/preview.png">Demo

                                                            </a>

                                                        </div><?php

                                                    }

                                                    if($row->type == 0 && ($row->free=="1" && $user->login_status == 1 || $user->admin == "1" || $purchased->paid == "1")){ ?>

                                                        <div class="product-download">

                                                            <a alt="download" href="<?=$setting["store_url"]?>/download/<?=$row->id?>">

                                                                <img src="<?=$setting["url"]?>/common/files/img/download.png">Download

                                                            </a>

                                                        </div><?php

                                                    }
                                                    else if($row->type == 0 && $row->free=="1") { ?>

                                                        <div class="product-download">

                                                            <a alt="download" href="<?=$setting["url"]?>/login">

                                                                <img src="<?=$setting["url"]?>/common/files/img/download.png">Download

                                                            </a>

                                                        </div><?php

                                                    }
                                                    else if($row->type == 0 || $row->type == 1 || $row->type == 2) {

                                                        if($row->free=="0") {

                                                            $json = $_COOKIE['carts'];

                                                            if(isset($_POST[add_cart])) {

                                                                $json = add_to_cart($row);

                                                                header("location: ".$setting["url"]."/product/".$row->id."");

                                                            }

                                                            if(isset($_POST["remove_cart"])) {

                                                                $json = remove_from_cart($row);

                                                                header("location: ".$setting["url"]."/product/".$row->id."");

                                                            }

                                                            $arrays = json_decode($json, true); ?>

                                                            <div class="product-cart">

                                                                <form action="<?=$setting["store_url"]?>/product/<?=$row->id?>" method="post">

                                                                    <input type="hidden" name="cart_id" value="<?=$row->id?>"><?php

                                                                    if (!in_array($row->id, $arrays, true)) { ?>

                                                                        <img src="<?=$setting["url"]?>/common/files/img/add.png"></img><input type="submit" name="add_cart" value="Add to Cart"></input><?php

                                                                    }
                                                                    else { ?>

                                                                        <img src="<?=$setting["url"]?>/common/files/img/remove.png"></img><input type="submit" name="remove_cart" value="Remove From Cart"></input><?php

                                                                    }

                                                                    ?>

                                                                </form>

                                                            </div>

                                                            <?php

                                                        }

                                                    }

                                                ?></div>

                                            </div>

                                        </div><?php

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div id="features" class="features-section">

            <div class="features-container row">

                <h2 class="features-headline light">FEATURES</h2>

                <div class="pure-u-1-3 feature mobile_display">

                    <div class="feature-icon ">

                        <img class="feature-img" src="<?=$setting["store_url"]?>/store/files/images/responsive.svg">

                    </div>

                    <h5 class="feature-head-text feature-no-display"> 

                        FULLY RESPONSIVE

                    </h5>

                    <p class="feature-subtext light"> 

                        Looks amazing on any device: smartphone, tablet, laptop and desktop.

                    </p>

                </div><div class="pure-u-1-3 feature mobile_display">

                    <div class="feature-icon feature-display-mid">

                        <img class="feature-img" src="<?=$setting["store_url"]?>/store/files/images/customizable.svg">

                    </div>

                    <h5 class="feature-head-text feature-display-mid"> 

                        CUSTOMIZABLE 

                    </h5>

                    <p class="feature-subtext light feature-display-mid"> 

                        Change the colors, pictures or any of the sections to suit your needs.

                    </p>

                </div><div class="pure-u-1-3 feature mobile_display">

                    <div class="feature-icon feature-display-last">

                        <img class="bullet-img" src="<?=$setting["store_url"]?>/store/files/images/design.svg">

                    </div>

                    <h5 class="feature-head-text feature-display-last"> 

                        SLICK AND BEAUTIFUL DESIGN 

                    </h5>

                    <p class="feature-subtext light feature-display-last"> 

                        Trendy and fresh design, fits any website.

                    </p>

                </div>

            </div>

        </div>

        <div class="logos-section hide_mobile">

            <img class="logos" src="<?=$setting["store_url"]?>/store/files/images/logos.png"/>

        </div>

		<article class="product-view-top-main">

			<div class="product-main-image">

				<div id="product-viewmain">

					<div class="pure-u-3-4 mobile_display">

						<div class="product-description">

							<?php

							echo stripslashes($row->description);

							if ($row->outdated =="1") { ?>

								<p>This script has been deprecated and will no longer receive updated, nevertheless, the script will continue to remain online for download. Please note, the script may not function as it was intended to.</p><?php

							}

							?>

						</div>
                        
                        <?php
    
                        if($row->type == 3 || $row->type == 4) { ?>

                            <div>

                                <div id="comment" class="comment">

                                    <?php require_once("include/comment.php"); ?>

                                </div>

                            </div><?php
        
                        }
    
                        ?>

					</div><div class="pure-u-1-4 mobile_display">

						<div id="product-ads">

							<?php include("../common/include/ads.php"); ?>

						</div>

					</div>

				</div>

			</div>

		</article>

		<?php

		require_once("../common/include/main_footer.php"); ?>

	</main>

	<?php

	require_once("../common/include/footer.php");

}
else {

	require_once('../common/pages/404_require.php');

}

?>
