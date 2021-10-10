<?php

$id=intval($_GET[id]);

$data = mysqli_fetch_object(mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `topic`.`name`, `topic`.`description`, `topic`.`id` FROM `topic` WHERE `topic`.`id`='".$id."'"));

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($count > 0) {

		mysqli_query($setting["Lid"],"UPDATE `topic` SET `views` = `views` + 1 WHERE `id`='".$data->id."'");

		$title = $data->name;

		$description = $data->description;

		$limit = '8';

		$page=intval($_GET["page"]);

		if ($page=="") {

			$page=1;

		}

		$start = ($page-1) * $limit;

		$query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`views`, `ticket`.`reply_to`, `ticket`.`post`, `ticket`.`reply`, (SELECT `temp`.`id` FROM `ticket` as `temp` WHERE `temp`.`reply_to` = `ticket`.`id` ORDER BY `temp`.`id` DESC LIMIT 1) AS `activeorder` FROM `ticket` WHERE `ticket`.`reply`='0' AND `ticket`.`topic`='".$data->id."' ORDER BY IFNULL(`activeorder`, `ticket`.`id`) DESC LIMIT ".$start.", ".$limit."");

		$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

		require_once('include/header.php');

		require_once('include/main_header.php');

		?>

		<main>

			<div class="otherbody">

				<?php include("../common/include/ads.php"); ?>

			</div>

			<article id="forumbody">

				<div>

					<?php

					$urls = $setting["forum_url"].'/topic/'.$id.'/';

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

	require_once("../common/pages/404.php");

}

?>
