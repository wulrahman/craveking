<?php

$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `demo`,`name`, `price`, `id`, `version`, `free` FROM `products` WHERE `id`='".intval($_GET['id'])."' AND `products`.`draft` = '0'");

$total_count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($total_count > 0) {

	$row = mysqli_fetch_object($query);

	$title=$row->name;

	require_once('include/header.php');

	require_once('include/main_header.php');

	?>

	<main>

		<article id="iframebody">

			<iframe src="<?=$row->demo?>" frameborder="0" allowfullscreen="">

				<p>Your browser does not support iframes.</p>

			</iframe>

		</article>

		<?php require_once('../common/include/main_footer.php'); ?>

	</main>

	<?php

	require_once("../common/include/footer.php");

}
else {

	require_once('../common/pages/404_require.php');

}

?>
