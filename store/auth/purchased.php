<?php

$title="Purchased";

if($user->login_status == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	$limit = '5';

	$page=intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

	$query = mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS * FROM( SELECT `product`, `paid`, `status` FROM `purchase` WHERE `user`="'.$user->id.'" ORDER BY `id` DESC ) as `inv` GROUP BY `product` LIMIT '.$start.', '.$limit.'');

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	?>

	<main>

		<?php

		if ($count > 0) {

			$i = 0;

			?>

			<article id="otherbody">

				<div id="item-list">

					<ul>

						<?php

						while ($row = mysqli_fetch_object($query)) {

							$query_product=mysqli_query($setting["Lid"],'SELECT `icon`, `name`, `price`, `size`, `version`, `description`, `id`, `file_name` FROM `products` WHERE `id`="'.$row->product.'"');

							$product = mysqli_fetch_object($query_product);

							if($product->price == 0 || $product->free == 1) {

								$product->price = "Free";

							}
							else {

								$product->price = money_format($setting["format_currency"], $product->price);

							}

							if($product->type == 0 || $product->type == "") {

								$type = "Download";

							}
							else if($product->type == 1) {

								$type = "Shippable";

							}
							else if($product->type == 2) {

								$type = "Service";

							}
							else if($product->type == 3) {

								$type = "Video";

							}
							else if($product->type == 4) {

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

									<a href="<?=$setting["store_url"]?>/product/<?=$product->id?>"><div class="pure-u-3-24 hide_mobile">

											<img class="item-thumb" src="<?=$product->icon?>">

										</div>

										<div class="pure-u-16-24 item-list-product"><h5 class="item-name"><?=limit_text($product->name,9)?></h5>

											<p class="item-desc">

												<?=limit_text(strip_tags(indextext($product->description)),30)?>

											</p>

											<div class="status">

												<?php

												if ($row->paid == 1) {

													echo '<a alt="download" href="'.$setting["store_url"].'/download/'.$product->id.'">

													<img src="'.$setting["url"].'/common/files/img/download.png">Download

													</a>';

												}
												else {

													echo ' <a href="#">'.$row->status.'</a>';

												}

												?>

											</div>

										</div>

										<div class="pure-u-2-24 item-list-price"><span class="item-price"><?=$product->price?></span></div>

										<div class="pure-u-3-24 hide_mobile"><span class="item-type"><?=$type?></span></div>

									</a>

								</div>

							</li><?php

						}

						?>

					</ul>

					<div class="pagination">

						<?php

						$previous = $page-1;

						$next = $page+1;

						$total = ceil($count / $limit);

						$url = $setting["store_url"].'/products/';

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

			</article>

		<?php

		}
		else { ?>

			<article id="otherbody">

				<div>

					<h1>No purchases</h1>

					<div>All your purchases will show and can be download from hear. </div>

				</div>

			</article><?php

		}

		require_once("../common/include/main_footer.php"); ?>

	</main>

	<?php

	require_once("../common/include/footer.php");

}
else {

	header('location: '.$setting["url"].'/login');

}

?>
