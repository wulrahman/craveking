<?php

$title="Messages";

if($user->login_status == 1) {

	$limit = '8';

	$page = intval($_GET["page"]);

	if ($page=="") {

		$page = 1;

	}

	$start = ($page-1) * $limit;

	$query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`views`, `ticket`.`reply_to`, `ticket`.`post`, `ticket`.`reply`, (SELECT `icon` FROM `users` WHERE `id` = `ticket`.`user`) as `usericon` FROM `ticket`, `message` WHERE `ticket`.`id`=`message`.`reply_id` AND `message`.`to`='".$user->id."' AND `ticket`.`hide`='0' AND `ticket`.`user` != '".$user->id."' ORDER BY `message`.`id` DESC LIMIT ".$start.", ".$limit."");

	$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	require_once('include/header.php');

	require_once('include/main_header.php');

	?>

	<main>

		<div class="otherbody">

			<?php require_once("../common/include/ads.php"); ?>

		</div>

		<article id="forumbody">

			<div>

				<?php

				require_once('include/main_forum.php');

				?>

			</div>

			<?php require_once("../common/include/main_footer.php"); ?>

		</article>

	</main>

	<?php

	require_once('../common/include/footer.php');

}
else {

	header("location: ".$setting["url"]."/login");

}

?>
