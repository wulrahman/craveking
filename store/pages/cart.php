<?php

ob_start();

$title="Cart";

require_once('include/header.php');

require_once('include/main_header.php');

$json = $_COOKIE['carts'];

$cart_row->id = intval($_POST['cart_id']);

if(isset($_POST['add_cart'])) {

	$json = add_to_cart($cart_row);

	header("location: ".$setting["url"]."/cart");

}

if(isset($_POST['remove_cart'])) {

	$json = remove_from_cart($cart_row);

	header("location: ".$setting["url"]."/cart");

}

$arrays = json_decode($json, true);

$where='`id`="'.implode($arrays, '" OR `id`="').'" AND';

$total_price = mysqli_query($setting["Lid"],'SELECT SUM(`price`) as `total`, SUM(`oldprice`) as `oldtotal` FROM `products` WHERE '.$where.' `free`="0"');

$total_price = mysqli_fetch_object($total_price);

$query = mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `name`, `price`, `oldprice`, `id`, `free`, `icon`, `description` FROM `products` WHERE '.$where.' `free`="0" ORDER BY `id` DESC');

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

?>

<main>

	<?php

	if ($count > 0) {	?>

		<article id="otherbody">

			<div id="item-list">

				<ul>

					<?php

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

								<div class="pure-u-3-24 hide_mobile">

									<a href="<?=$setting["store_url"]?>/product/<?=$row->id?>">

										<img class="item-thumb" src="<?=$row->icon?>">

									</a>

								</div>

								<div class="pure-u-16-24 item-list-product">

									<a href="<?=$setting["store_url"]?>/product/<?=$row->id?>">

										<h5 class="item-name"><?=limit_text($row->name,9)?></h5>

										<p class="item-desc">

											<?=limit_text(strip_tags(indextext($row->description)),30)?>

										</p>

									</a>

									<div class="status">

										<div class="product-cart-main">

											<form action="<?=$setting["store_url"]?>/cart" method="post">

												<input type="hidden" name="cart_id" value="<?=$row->id?>">

												<?php

												if (!in_array($row->id, $arrays, true)) { ?>

													<img src="<?=$setting["url"]?>/common/files/img/add.png"></img><input type="submit" name="add_cart" value="Add to Cart"></input><?php

												}
												else { ?>

													<img src="<?=$setting["url"]?>/common/files/img/remove.png"></img><input type="submit" name="remove_cart" value="Remove From Cart"></input><?php

												}

												?>
											</form>

										</div>

									</div>

								</div>

								<div class="pure-u-2-24 item-list-price"><span class="item-price"><?=$row->price?></span></div>

								<div class="pure-u-3-24 hide_mobile"><span class="item-type"><?=$type?></span></div>

							</div>

						</li><?php

					}

					?>

				</ul>

				<div class="checkout">

					<div>

						<form method="post" action="<?=$setting["url"]?>/payment">

							<input type="hidden" name="carts" value="1">

							<input type="submit" value="Check Out">

						</form>

					</div><div class="checkout-total">

						<div>

							<?php

							$totalprice = money_format($setting["format_currency"], $total_price->total);
							echo 'Total amount <span class="checkout-price">'.$totalprice.'</span>';

							if($total_price->oldtotal != "" && $total_price->oldtotal > $total_price->total) {

								echo '<del>'.money_format($setting["format_currency"], $total_price->oldtotal).'</del>';

							}

							echo 'quantity '.$count;

							?>

						</div>

					</div>

				</div>

			</div>

		</article>

<?php

	}
	else { ?>

		<article id="otherbody">

			<div>

				<h1>Cart</h1>

				<div>No items have been added to cart, get started by selecting and adding products to your cart.</div>

			</div>

		</article><?php

	}

	require_once("../common/include/main_footer.php");

	?>

</main>

<?php require_once("../common/include/footer.php"); ?>
