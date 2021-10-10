<?php

if($user->admin == 1) {

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

    $id = intval($_GET['id']);

	$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `name` ,`description` FROM `topic` WHERE `id`='".$id."'");
	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count > 0) {

   		if(isset($_POST['submit'])) {

			$description = $_POST['description'];

			$name = $_POST['name'];

			mysqli_query($setting["Lid"],"UPDATE `topic` SET `name`='".$name."',`description`='".$description."' WHERE `id`='".$id."'");

		}

		$row = mysqli_fetch_object($query);

		?>

		<main>

			<article id="mainbody">

				<div id="form-main">

				    <form method="post" enctype="multipart/form-data">
                        
                        <div class="main-form">

                            <label>Name</label>
                            <input type="text" name="name" value="<?=htmlentities($row->name)?>"></input>

                            <label>Description</label>
                            <textarea name="description"><?=htmlentities($row->description)?></textarea>

				        </div>

				        <div class="form-content-footer  pure-g">

				            <div class="pure-u-1-2"></div>

				            <div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Update"></input></div>

				        </div>

				    </form>

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
}
else {

	require_once('../common/pages/404.php');

}

?>
