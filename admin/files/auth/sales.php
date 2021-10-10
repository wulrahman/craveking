<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$limit = '10';

	$page = intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

	$query = mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `id`, `read`, `txnid`, `product`, `paid`, `status`, `user`, `amount`, `timestamp` FROM `purchase` WHERE (`txn_type`="cart" OR `txn_type`="") ORDER BY `read` ASC, `id` DESC LIMIT '.$start.', '.$limit.'');

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	?>

	<main>

		<article id="otherbody">

			<?php

			if ($count > 0) {	?>

				<div id="item-list">

					<ul>

						<li class="pure-u-1">

							<div class="item-list item-list-header pure-g">

								<span class="pure-u-3-24 hide_mobile">Icon</span>

								<span class="pure-u-8-24 sale-item-mobile">Item</span>

								<span class="pure-u-6-24 hide_mobile">Ref</span>

								<span class="pure-u-3-24 sale-price-mobile">Price</span>

								<span class="pure-u-3-24 sale-status-mobile">Status</span>

							</div>

						</li>

						<?php

						while ($row = mysqli_fetch_object($query)) {

							$sale_user = getUser($row->user);

							?>

							<a href="https://history.paypal.com/webscr?cmd=_history-details-from-hub&id=<?=$row->txnid?>">

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

											<?php

											if($sale_user->icon == "") {

												echo '<div class="item-thumb" style="background-color:#'.$sale_user->color.';">'.ucfirst(substr($sale_user->username,0,1)).'</div>';

											}
											else {

												echo '<img class="item-thumb" src="'.$setting["url"].'/common/'.$sale_user->icon.'">';

											}

											?>

										</div>

										<div class="pure-u-8-24 sale-item-mobile">

											<h5 class="item-name"><?=limit_text($sale_user->username,3)?></h5>

											<span class="item-date"><?=time_elapsed_string(strtotime($row->timestamp))?></span>

										</div>

										<div class="pure-u-6-24 hide_mobile"><span class="item-reference"><a href="https://history.paypal.com/webscr?cmd=_history-details-from-hub&id=<?=$row->txnid?>"><?=$row->txnid?></a></span></div>

										<div class="pure-u-3-24 sale-price-mobile"><span class="item-price">$<?=$row->amount?></div>

										<div class="pure-u-3-24 sale-status-mobile"><span class="item-status"><?=$row->status?></span></div>

									</div>

								</li>

							</a>

						<?php

						}

						?>

					</ul>

					<?php

					$previous=$page-1;

					$next=$page+1;

					$total = ceil($count / $limit);

					$url = $setting["admin_url"]."/sales/";

					?>

					<div class="pagination">

						<?php

						if ($page > 1) {

							echo '<li><a href="'.$url.$previous.'">Previous</a></li>';

						}

						for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {

							echo '<li><a href="'.$url.$i.'">'.$i.'</a></li>';

						}

						if ($page < $total) {

							echo '<li><a href="'.$url.$next.'">Next</a></li>';

						}

						?>

					</pagination>

				<?php

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
