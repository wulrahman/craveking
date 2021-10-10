<?php

$limit = '8';

$page = intval($_GET["page"]);

$status = $_POST["status"];

$id = intval($_GET['id']);

if ($page=="") {

	$page=1;

}

$start = ($page-1) * $limit;

$query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`read`, `ticket`.`views` FROM `ticket` WHERE `ticket`.`id`='".$id."' AND `ticket`.`reply`='0' AND `ticket`.`hide`='0' ");

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($count > 0) {

	$row = mysqli_fetch_object($query);

	mysqli_query($setting["Lid"],"UPDATE `ticket` SET `views` = `views` + 1 WHERE `id`='".$row->id."'");

	$title = $row->subject;

	$keywords = extractCommonWords($row->ticket, 20);

	$keywords = implode(',', array_keys($keywords));

	require_once('include/header.php');

	require_once('include/main_header.php');

	?>

	<main>

		<div id="product-ads">

			<?php include("../common/include/ads.php"); ?>

		</div>

		<article id="forumbody">

			<div>

				<div class="forum-view-header">

					<h1><?=limit_text($row->subject,8)?></h1>

				</div>

				<div id="product-viewmain">

					<div class="forum-view-description">

						<?php

						if($row->spam == 1) {

							echo "This comment was marked as spam by our Administrator.";

						}
						else {

							echo closetags($row->ticket);

						}

						?>

					</div>

					<div class="forum-view-status">

						<?php

						if (($user->admin == "1" || $row->id  == $data->user) && $row->status=="1") {?>

							<div class="close">

								<form action="" class="status-box" method="post">

									<input type="submit" value="Close" name="status">

									<input type="hidden" name="id" value="<?=$row->id?>">

									<input type="hidden" name="type" value="status">

									<input type="hidden" name="comment_id" value="<?=$row->id?>">

								</form>

							</div><?php
						}
						else if ($row->status=="1") {

							echo "Active ";

						}
						else if ($row->status=="2") {

							echo "Closed ";

						}

						$reply = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`read` FROM `ticket` WHERE `ticket`.`reply_to`='".$row->id."' AND `ticket`.`hide`='0' AND `ticket`.`reply`='1'");

						$total_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

						$like_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `rating_topic` WHERE `topic`='".$row->id."'")));

						?>

						<div class="forum-view-username">by <?=username($row->user)?></div>

						<div class="forum_view-timestamp"><?=time_elapsed_string(strtotime($row->timestamp))?></div>

						<div class="forum-view-views"><?=$row->views?> views</div>

						<div class="forum-view-replies"><?=$total_count?> replys</div>

						<div class="forum-view-like">

							<form action="" class="like_box" method="post">

								<input type="image" src="<?=$setting["url"]?>/common/files/img/like_button.png" name="like_submit" value="Submit">

								<input type="hidden" name="id" value="<?=$row->id?>">

								<input type="hidden" name="comment_id" value="<?=$row->id?>">

								<input type="hidden" name="type" value="like">

								<input type="checkbox" name="agree">

							</form>

							<?=$like_count?>

						</div>

						<?php

						if($user->admin == 1 && $user->login_status == 1) {

						?>

							<div class="spam">

								<form action="" class="spam_box" method="post">

									<input type="hidden" name="id" value="<?=$row->id?>" >

									<input type="submit" name="spam" value="spam">

									<input type="hidden" name="type" value="spam">

									<input type="hidden" name="comment_id" value="<?=$row->id?>">

									<input type="checkbox" name="agree">

								</form>

							</div>

						<?php

						}

						?>

					</div>

					<script>
						var id = "<?=$row->id?>";
						var url_post = "<?=$setting["url"]?>/forum/ajax/comment.php";
					</script>

					<script src="<?=$setting["url"]?>/common/files/js/form.js?q=7"></script>

					<div class="pure-u-3-4 mobile_display">

						<div id="comment" class="comment">

							<?php require_once("include/comment.php"); ?>

						</div>

					</div><div class="pure-u-1-4 mobile_display">

						<div id="product-ads">

							<?php include("../common/include/ads.php"); ?>

						</div>

					</div>

				</div>

			</div>

		</article>

		<?php require_once("../common/include/main_footer.php"); ?>

	</main>

	<?php

	require_once("../common/include/footer.php");

}
else {

	require_once("../common/pages/404.php");

}

?>
