<?php

$sale = intval($_GET['sale']);

if($user->admin == 1) {

	$custom["dir_sub"] = "";

	$custom["image_dir"] = "thumb/";

	$custom["temp_dir"] = $custom["image_dir"]."temp/";

	$custom["thumb_dir"] = $custom["image_dir"]."thumb/";

	$custom["maxwidth"] = 2000;

	$custom["maxheight"] = 1000;

	ini_set('memory_limit', '1024M');

	ini_set('max_execution_time', 300);

	$id = intval($_GET['id']);

	$row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `description` ,`product` ,`name` ,`id` ,`type`, `free`, `oldprice`, `price`, `demo`, `version`, `views`, `downloads`, `feature`, `orderfeature`, `icon`, `outdated`, `draft` FROM `products` WHERE `id`='".$id."'"));

	$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count > 0) {

		require_once('include/header.php');

		require_once('include/main_header.php');

		require_once('include/main_nav.php');

		if(isset($_POST['submit']) || isset($_POST['publish']) || isset($_POST['outdated'])) {

			$price = $_POST['price'];

			$description = addslashes(closetags(convert_encoding($_POST['description'])));

			$name = htmlstring(convert_encoding(htmlentities($_POST['name'])));

			$type = intval($_POST['type']);

			$version = $_POST['version'];

			$free = intval($_POST['free']);

			$demo = $_POST['demo'];

			$oldprice = $_POST['oldprice'];

			$feature = intval($_POST['feature']);

			$orderfeature = intval($_POST['orderfeature']);

			$icon = $_POST['icon'];

			if($feature == 0) {

				$orderfeature = 0;

			}

			if(isset($_POST['publish'])) {

				if($row->draft == 0) {

					$addsql_array[] = "`draft`='1'";

				}
				else {

					$addsql_array[]= "`draft`='0'";

				}

			}

			if(isset($_POST['outdated'])) {

				if($row->outdated == 0) {

					$addsql_array[] = "`outdated`='1'";

				}
				else {

					$addsql_array[] = "`outdated`='0'";

				}

			}

			if(count($addsql_array) > 0) {

				$addsql = ", ".implode(", ", $addsql_array);

			}

			mysqli_query($setting["Lid"],"UPDATE `products` SET `name`='".$name."',`oldprice`='".$oldprice."',`price`='".$price."',`description`='".$description."',`type`='".$type."',`version`='".$version."',`free`='".$free."',`demo`='".$demo."', `feature`='".$feature."', `orderfeature`='".$orderfeature."', `icon`='".$icon."' ".$addsql." WHERE `id`='".$row->id."'");


			if(empty($_FILES["product"]["name"])) {

			}
			else {

				$array =array( 1 =>"zip", "x-rar-compressed", "x-gtar", "x-apple-diskimage", "x-tar", "x-7z-compressed", "x-gzip", "vnd.android.package-archive" ,"x-dar", "x-par2");

				$size = $_FILES["product"]["size"];

				$type = pathinfo($_FILES['product']['name'], PATHINFO_EXTENSION);

				$file = file_get_contents($_FILES["product"]["tmp_name"]);

				$name = explode('.',$_FILES["product"]["name"]);

				$stmt = mysqli_prepare($setting["Lid"], "UPDATE `products` SET `product`=?,`mime`=?,`file_name`=?,`size`=? WHERE `id`='".$row->id."'");

				mysqli_stmt_bind_param($stmt, 'ssss', $file, $type, $name[0], $size);

				mysqli_stmt_execute($stmt);

				mysqli_stmt_close($stmt);

			}

			foreach($_FILES['images']['tmp_name'] as $key => $tmp_name) {

				$array_image = getthumbimage($_FILES["images"]["name"][$key], $_FILES['images']['tmp_name'][$key], $_FILES["images"]["size"][$key], 0, $custom);

				$error_count=count($array_image['error']);

				if($error_count == 0) {

					mysqli_query($setting["Lid"],"INSERT INTO `image` (`image` ,`thumb` ,`width` ,`height` ,`product`) VALUES ('".$custom["thumb_dir"].$array_image['thumb']."',  '".$custom["thumb_dir"].$array_image['thumb']."',  '".$array_image['height']."',  '".$array_image['width']."', '".$row->id."');");

				}

			}

		}

		$row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT `description`, `product` ,`name` ,`id` ,`type`, `free`, `oldprice`, `price`, `demo`, `version`, `views`, `downloads`, `feature`, `orderfeature`, `icon`, `outdated`, `draft` FROM `products` WHERE `id`='".$row->id."'"));

		?>

		<main>

			<article id="mainbody">

				<div id="product-main">

					<form method="post" enctype="multipart/form-data">

						<div class="product-content pure-u-18-24 mobile_display">

							<div class="product-main">

									<h1 class="product-content-title"><input type="text" name="name" value="<?=stripslashes(htmlentities($row->name))?>"></input></h1>

									<?php

									if($row->type == 0) {	 ?>

										<div id="product-upload" class="">

											<div class="product-upload-item">

												<input class="inputfile" name="product" id="product" type="file" >

												<label for="product"><span class="fa fa-download icon" aria-hidden="true"></span><span>Upload Product</span></label>

											</div>

										</div><?php

									}

									if($row->type == 0 || $row->type == 1 || $row->type == 2) { ?>

										<div id="product-upload" class="product-image">

											<div id="product-image">

												<ul>

													<div id="product-upload" class="product-image">

														<div class="product-upload-image">

															<input class="inputfile" name="images[]" multiple="" id="images" type="file" data-multiple-caption="{count} files selected">

															<label for="images"><span class="fa fa-download icon" aria-hidden="true"></span><span>Upload Images</span></label>

														</div>

													</div>

													<?php

													$thumbs = mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS `thumb`,`image`, `id` FROM `image` WHERE `product`="'.$row->id.'" ORDER BY `order` ASC');

													$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

													if ($count > 0) {

															while ($thumb = mysqli_fetch_object($thumbs)) { ?>

																    <li class="pure-u-1-6">

																			<a href="<?=$setting["admin_url"]?>/image/edit/<?=$thumb->id?>"><div style="background-image:url(../<?=$thumb->image?>);">
																			</div></a>

													     			</li><?php

															}

													}

													?>

												</ul>

											 </div>

										</div><?php

									}

									?>
									<div class="product-content-body">

											<textarea id="noise" class="widgEditor nothing" id="description" name="description">
												<?=stripslashes(htmlentities($row->description))?>
											</textarea>

									</div>

							</div>

						</div><div class="product-setting pure-u-6-24 mobile_display">

							<div class="setting-main">

									<?php

									if($row->type == 0) { ?>

										<div>

											<label>Demo</label>
                                            
											<input type="text" name="demo" value="<?=htmlentities($row->demo)?>">

										</div>

									<?php

									} ?>

									<div>

										<label>Type</label>

										<select name="type">

											<?php

											$array = array(0 => "Download", 1 => "Shippable", 2 => "Service", 3 => "Video", 4 => "Article");

											foreach ($array as $key => $item ) {

												echo $item;

												$selected = "";

												if($key == $row->type) {

													$selected = " selected";

												}

												echo '<option value="'.$key.'"'.$selected.'> ' .$item.'</option>';

											}

											?>

										</select>

									</div>

									<?php

									if($row->type == 0 || $row->type == 1 || $row->type == 2) { ?>

										<div>

											<label>Version</label>

											<input type="number" min="0" step="any" name="version" value="<?=htmlentities($row->version)?>">

										</div>

										<div>

											<label>Old Price</label>

											<input type="text" min="0" step="any" name="oldprice" value="<?=htmlentities($row->oldprice)?>">

										</div>

										<div>

											<label>Price</label>

											<input type="text" min="0" step="any" name="price" value="<?=htmlentities($row->price)?>">

										</div>

									<?php

									} ?>

									<div>

										<label>Icon</label>

										<input type="text" name="icon" value="<?=htmlentities($row->icon)?>">

									</div>

									<?php

									if($row->type == 0 || $row->type == 1 || $row->type == 2) {

										$checked = "";

										if ($row->feature==1) {

											$checked = " checked"; ?>

											<div>

												<label>Order</label>

												<input type="number" min="0" step="any" name="orderfeature" value="<?=htmlentities($row->orderfeature)?>">

											</div><?php

										}	?>

										<div class="pure-u-1-2">

											<label>Feature

												<input type="hidden" name="feature" value="0" step="any">

												<input name="feature" type="checkbox" value="1"<?=$checked?>>

											</label>

										</div><div class="pure-u-1-2">

											<?php

											$checked = "";

											if ($row->free==1) {

												$checked = " checked";

											} ?>

											<label>Free

												<input type="hidden" name="free" value="0">

												<input name="free" type="checkbox" value="1"<?=$checked?>>

											</label>

										</div>

									<?php

									} ?>

								</div>

						</div>

						<div class="product-content-footer pure-g">

								<div class="pure-u-1-3"><input type="submit" class="secondary-button pure-button" value="Back"></input></div>

								<div class="product-content-controls pure-u-2-3"><input name="submit" type="submit" class="secondary-button pure-button" value="Save"></input> <input name="outdated" type="submit" class="secondary-button pure-button" value="Deprecate"></input> <input name="submit" type="submit" class="secondary-button pure-button" value="Publish"></input></div>

						</div>

					</form>

				</div>

				<?php require_once("../common/include/main_footer.php"); ?>

			</article>

		</main>

		<script type="text/javascript" src="<?=$setting['url']?>/common/files/js/editor.js"></script>

		<?php

	}
	else {

		require_once('../common/pages/404.php');

	}

}
else {

	require_once('../common/pages/404.php');

}

?>
