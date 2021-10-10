<?php

$title="Craveking";

$description="Don't compromise with quality, we provide high-quality and adaptable meta search scripts; web, video, image, etc. Making building a website that much easier.";

$keywords="craveking, calculator, script, search, meta search engine, HTML, engine, meta, locate, ajax, free";

require_once('include/header.php');

$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `products`.`feature`, `products`.`name`, `products`.`oldprice`, `products`.`price`, `products`.`tile`, `products`.`id`, `products`.`free`, `products`.`description`, `products`.`views`, `products`.`icon`,

(SELECT `thumb` FROM `image` WHERE `product`=`products`.`id` ORDER BY `order` LIMIT 0, 1) as `thumb`

FROM `products` WHERE `products`.`draft` = '0' AND `products`.`outdated` = '0' AND `products`.`feature` = '1' ORDER BY `products`.`orderfeature` ASC LIMIT 1, 4");

$landing_query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `products`.`feature`, `products`.`name`, `products`.`oldprice`, `products`.`price`, `products`.`tile`, `products`.`id`, `products`.`free`, `products`.`description`, `products`.`views`, `products`.`icon`,

(SELECT `thumb` FROM `image` WHERE `product`=`products`.`id` ORDER BY `order` LIMIT 0, 1) as `thumb`

FROM `products` WHERE `products`.`draft` = '0' AND `products`.`outdated` = '0' AND `products`.`feature` = '1' ORDER BY `products`.`orderfeature` ASC LIMIT 0, 1");

$landing_row = mysqli_fetch_object($landing_query);

?>

<main>
    
    <div class="hero-background">
        
        <div>
            
            <img class="strips" src="<?=$setting["store_url"]?>/store/files/images/strips.png">
            
        </div>
        
        <div class="landing-page">

            <div class="pure-u-1-2 landing-page-description mobile_display">

                <h1 class="landing-page-title-main">

                    <?=limit_text(strip_tags(indextext($landing_row->name)),8)?>

                </h1>

                <div class="landing-page-description-main ">

                    <?=limit_text(strip_tags(indextext($landing_row->description)),60)?>

                </div>

                <div class="landing-lage-more">

                    <div class="landing-lage-more-main">

                        <a href="<?=$setting["store_url"]?>/product/<?=$landing_row->id?>">More..</a>

                    </div>

                </div>

            </div><div class="pure-u-1-2 landing-page-image hide_mobile">

                <a href="<?=$setting["store_url"]?>/product/<?=$landing_row->id?>">

                    <?php

                    $landing_thumb = mysqli_fetch_object(mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `thumb`,`image`, `id` FROM `image` WHERE `product`="'.$landing_row->id.'" ORDER BY `order` ASC LIMIT 0, 1'));

                    ?>
                    <div class="thumb" style="background-image:url(<?=$setting['url']?>/admin/<?=$landing_thumb->image?>)"></div>

                </a>

            </div>
            
            <div><img class="mouse" src="<?=$setting["store_url"]?>/store/files/images/mouse.svg"></div>

        </div>
    

    </div>
    
    <?php
    
    require_once('include/main_header.php');
                         
    ?>

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
    
    <div id="pricing" class="pricing-background">

        <h2 class="pricing-section-header light text-center">PRICING</h2>
        
        <h4 class=" pricing-section-sub text-center light">Our super affordable pricing</h4>

        <div class="pricing-table row">

            <div>
                
                <div class="mid-plan">
                    
                    <h4 class="plan-cost bold"><?=$landing_row->price?></h4>
                    
                    <div class="plan-price-div text-center">
                        
                        <div class="choose-plan-div">
                            
                            <a class="plan-btn light" href="<?=$setting["store_url"]?>/product/<?=$landing_row->id?>">
                                
                                Get Started
                                
                            </a>
                            
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>

        </div>

    </div>

	<?php require_once("../common/include/main_footer.php"); ?>

</main

<?php require_once("../common/include/footer.php"); ?>

<script>
	(function(h,e,a,t,m,p) {
	m=e.createElement(a);m.async=!0;m.src=t;
	p=e.getElementsByTagName(a)[0];p.parentNode.insertBefore(m,p);
	})(window,document,'script','https://u.heatmap.it/log.js');
</script>
