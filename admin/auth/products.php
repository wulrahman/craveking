<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$limit = '8';

	$page=intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

	$query=mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `products`.`icon`, `products`.`type`, `products`.`name`, `products`.`oldprice`, `products`.`price`, `products`.`id`, `products`.`free`, `products`.`description`, `products`.`views`, `products`.`icon`, (SELECT `thumb` FROM `image` WHERE `product`=`products`.`id` ORDER BY `order` LIMIT 0, 1) as `thumb` FROM `products` ORDER BY `id` DESC LIMIT '.$start.', '.$limit.'');

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	?>

	<main>

		<article id="otherbody">

			<div id="item-list">

				<ul>

					<li class="pure-u-1">

						<div class="item-list item-list-header pure-g">

							<span class="pure-u-3-24 hide_mobile">Icon</span>

							<span class="pure-u-16-24 product-item-mobile">Product</span>

							<span class="pure-u-2-24 product-price-mobile">Price</span>

							<span class="pure-u-3-24 product-type-mobile">Type</span>

						</div>

					</li>

					<?php

					$i = 0;

					while ($row = mysqli_fetch_object($query)) {

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

								<a href="<?=$setting["admin_url"]?>/edit/<?=$row->id?>"><div class="pure-u-3-24 hide_mobile">

										<img class="item-thumb" src="<?=$row->icon?>">

									</div>

									<div class="pure-u-16-24 product-item-mobile"><h5 class="item-name"><?=limit_text($row->name,9)?></h5>

										<p class="item-desc">

											<?=limit_text(strip_tags(indextext($row->description)),30)?>

										</p>

									</div>

									<div class="pure-u-2-24 product-price-mobile"><span class="item-price"><?=$row->price?></span></div>

									<div class="pure-u-3-24 product-type-mobile"><span class="item-type"><?=$type?></span></div>

								</a>

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

					$url = $setting["admin_url"].'/products/';

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

			<?php require_once("../common/include/main_footer.php"); ?>

		</article>

	</main>

	<?php

	require_once("../common/include/main_footer.php");
}
else {

	require_once('../common/pages/404.php');

}

?>
