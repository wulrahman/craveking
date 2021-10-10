<?php

$id=intval($_GET['id']);

if($user->admin ==1) {

	$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `image` ,`thumb` ,`width` ,`height` ,`id` ,`product`, `order` FROM `image` WHERE `id`='".$id."'");

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count > 0) {

   	if(isset($_POST['submit'])) {

			$image = $_POST['image'];

			$thumb = $_POST['thumb'];

			$width = $_POST['width'];

			$height = $_POST['height'];

			$order = $_POST['order'];

			$product = $_POST['product'];

			mysqli_query($setting["Lid"],"UPDATE `image` SET `image`='".$image."',`thumb`='".$thumb."',`width`='".$width."',`height`='".$height."',`order`='".$order."',`product`='".intval($product)."' WHERE `id`='".$id."'");

		}

		if(isset($_POST['delete'])) {

			$delete = mysqli_fetch_object($query);

			mysqli_query($setting["Lid"],"DELETE FROM `image` WHERE `id` = '".$id."'");

			unlink($setting["dir_sub"].$delete->image);
			unline($setting["dir_sub"].$delete->thumb);

			header('location: '.$setting["admin_url"].'/edit/'.$delete->product.'');

		}

		$row = mysqli_fetch_object($query);

		require_once('include/header.php');

		require_once('include/main_header.php');

		require_once('include/main_nav.php');

		?>

		<main>

			<article id="mainbody">

				<div id="form-main">

    			<form method="post" enctype="multipart/form-data">

						<div class="main-form">

							<a href="<?=$setting["admin_url"]?>/edit/<?=$row->product?>">Go back</a>

							<div>

								<div class="div_image" style="background-image:url(../../<?=htmlentities($row->image)?>);"></div>

							</div>

							<label>Image</label>

							<input type="text" name="image" value="<?=htmlentities($row->image)?>"></input>

							<label>Thumb</label>

							<input type="text" name="thumb" value="<?=htmlentities($row->thumb)?>"></input>

							<div>

								<label>Width</label>

								<input type="number" name="width" value="<?=htmlentities($row->width)?>"></input>

							</div>

							<div>

								<label>Height</label>

								<input type="number" name="height" value="<?=htmlentities($row->height)?>"></input>

							</div>

							<div>

								<label>Order</label>

								<input type="number" name="order" value="<?=htmlentities($row->order)?>"></input>

							</div>

							<div>

								<label>Product</label>

								<input type="number" name="product" value="<?=htmlentities($row->product)?>">

							</div>


						</div>

						<div class="form-content-footer  pure-g">

								<div class="pure-u-1-2"></div>

								<div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Update"></input> <input name="delete" type="submit" class="secondary-button pure-button" value="Delete"></input></div>

						</div>

					</form>

				</div>

				<?php require_once("../common/include/main_footer.php"); ?>

			</article>

		</main>

		<?php

		require_once("../common/include/main_footer.php");
		
		}


	}
	else {

		require_once("../common/pages/404.php");

	}
?>
