<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$limit = '16';

	$page=intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

	$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `comment`.`product`,`comment`.`reply_to`,`comment`.`read`, `comment`.`id`,`comment`.`comment`, `comment`.`user`, `comment`.`timestamp`, `comment`.`spam`, (SELECT `temp`.`id` FROM `comment` as `temp` WHERE `temp`.`reply_to` = `comment`.`id` ORDER BY `temp`.`id` DESC LIMIT 1) AS `activeorder` FROM `comment` ORDER BY IFNULL(`activeorder`, `comment`.`id`) DESC LIMIT ".$start.", ".$limit."");

	$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	?>

	<main>

		<article id="otherbody">

			<div id="item-list">

				<ul>

					<li class="pure-u-1">

						<div class="item-list item-list-header pure-g">

							<span class="pure-u-3-24 hide_mobile">Icon</span>

							<span class="pure-u-13-24 comment-item-mobile">Comment</span>

							<span class="pure-u-4-24 hide_mobile">Date</span>

							<span class="pure-u-4-24 comment-project-mobile">Product</span>

						</div>

					</li>

					<?php

					$i = 0;

					while ($row = mysqli_fetch_object($query)) {

						$comment_user = getUser($row->user);

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

								$product = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `name` FROM `products` WHERE `id`='".$row->product."'"));

								?>

								<a href="<?=$setting["store_url"]?>/product/<?=$row->product?>"><div class="pure-u-3-24 hide_mobile">

									<?php

									if($comment_user->icon == "") {

										echo '<div class="item-thumb" style="background-color:#'.$comment_user->color.';">'.ucfirst(substr($comment_user->username,0,1)).'</div>';

									}
									else {

										echo '<img class="item-thumb" src="'.$setting["url"].'/common/'.$comment_user->icon.'">';

									}

									?>

									</div>

									<div class="pure-u-13-24 comment-item-mobile">

										<?php

										if($row->reply_to > 0) {

											$reply_to = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `comment`.`user` FROM `comment` WHERE `id` = '".$row->reply_to."'"));

											$reply_user = getUser($reply_to->user);

											echo '<p class="reply_to">reply to <span class="reply_user">'.$reply_user->username.'</span></p>';

										}

										?>

										<h5 class="item-name"><?=limit_text($comment_user->username,3)?></h5>

										<p class="item-desc">

											<?php

											if($row->spam == 1) {

												echo "This comment was marked as spam by our Administrator.";

											}
											else {

												echo $row->comment;

											}

											?>

										</p>

									</div>

									<div class="pure-u-4-24 hide_mobile"><span class="item-date"><?=time_elapsed_string(strtotime($row->timestamp))?></span></div>

									<div class="pure-u-4-24 comment-project-mobile"><span class="product-name"><?=limit_text($product->name,5)?></span></div>

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

					$url = $setting["admin_url"].'/comments/';

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
