<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$limit = '8';

	$page = intval($_GET["page"]);

	if ($page=="") {

		$page=1;

	}

	$start = ($page-1) * $limit;

	$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id`, `username`, `icon`, `email`, `timestamp`, `admin`, `color` FROM `users` ORDER BY `id` DESC LIMIT ".$start.", ".$limit."");

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	?>

	<main>

		<article id="otherbody">

			<div id="item-list">

				<ul>

					<li class="pure-u-1">

						<div class="item-list item-list-header pure-g">

							<span class="pure-u-3-24 hide_mobile">Icon</span>

							<span class="pure-u-14-24 member-item-mobile">Item</span>

							<span class="pure-u-4-24 member-date-mobile">Joined</span>

							<span class="pure-u-3-24 member-type-mobile">Type</span>

						</div>

					</li>

					<?php

					while ($row = mysqli_fetch_object($query)) {

						if($row->admin == "1") {

							$row->type ="admin";

						}
						else {

							$row->type ="customer";

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

								<a href="<?=$setting["admin_url"]?>/members/edit/<?=$row->id?>"><div class="pure-u-3-24 hide_mobile">

									<?php

									if($row->icon == "") {

										echo '<div class="item-thumb" style="background-color:#'.$row->color.';">'.ucfirst(substr($row->username,0,1)).'</div>';

									}
									else {

										echo '<img class="item-thumb" src="'.$setting["url"].'/common/'.$row->icon.'">';

									}

									?>

									</div>

									<div class="pure-u-14-24 member-item-mobile"><h5 class="item-name"><?=$row->username?></h5>

										<p class="item-desc">

											<?=$row->email?>

										</p>

									</div>

									<div class="pure-u-4-24 member-date-mobile"><span class="item-date"><?=time_elapsed_string(strtotime($row->timestamp))?></span></div>

									<div class="pure-u-3-24 member-type-mobile"><span class="item-type"><?=$row->type?></span></div>

								</a>

							</div>

						</li>

					<?php

					}

					?>

			 	</ul>


				<div class="pagination">

					<?php

					$previous=$page-1;

					$next = $page+1;

					$total = ceil($count / $limit);

					$url = $setting["admin_url"]."/members/";

					if ($page > 1) {

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
