<?php

$title="Setting";

if($user->admin == 1) {

	$id = intval($_GET['id']);

	$row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS * FROM `users` WHERE `id`='".$id."'"));

	$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count > 0) {

		ini_set('memory_limit', '1024M');

		ini_set('max_execution_time', 300);

		$custom["image_dir"] = "auth/thumb/";

		$custom["temp_dir"] = $custom["image_dir"]."temp/";

		$custom["thumb_dir"] = $custom["image_dir"]."thumb/";

		$custom["maxwidth"] = 200;

		$custom["maxheight"] = 150;

		require_once('include/header.php');

		require_once('include/main_header.php');

		require_once('include/main_nav.php');

		if (isset($_POST['submit'])) {

			$errors = array();

			$email = $_POST["email"];

			$password = $_POST["password"];

			$street = $_POST["street"];

			$city = $_POST["city"];

			$state = $_POST["state"];

			$zip = $_POST["zip"];

			$country = $_POST["country"];

			$banned = $_POST["banned"];

			if($email !== $row->email) {

				mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `email` FROM `users` WHERE `email`='".$email."'");

				$email_count=array_pop(mysqli_fetch_array(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

				if($email_count > 0) {

					$errors[]="email already exists.";

				}


				if (!validate_email($email)) {

					$errors[]="please insert a valid email.";

	  			}

			}

			if(!$email) {

				$errors[]="please make sure that you have correctly filled in all of the fields.";

			}


			if($password !== "") {

				$salt = randomurl(randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]));

				$password = md5($salt . $password);

				$passwordsalt = "`password`='".$password."', `salt`='".$salt."', ";

			}

			if(isset($_FILES["thumb"])) {

				$array_image = getthumbimage($_FILES["thumb"]["name"], $_FILES['thumb']['tmp_name'], $_FILES["thumb"]["size"], $custom);

				$errors = array_merge($array_image['error'], $errors);

				if(count($array_image['error']) == 0) {

					if($row->icon !== 0) {

						unlink($row->icon);

					}

					mysqli_query($setting["Lid"],"UPDATE `users` SET `icon`='".$custom["thumb_dir"].$array_image['thumb']."' WHERE `id`='".$row->id."';");

				}

			}

			$error_count = count($errors);

		}
        
        if(isset($_POST['submit'])) {

			if($errors_count == 0) {
        
                mysqli_query($setting["Lid"],"UPDATE `users` SET ".$passwordsalt."`city`='".$city."',`banned`='".$banned."',`email`='".$email."',`state`='".$state."',`street`='".$street."',`zip`='".$zip."',`country`='".$country."' WHERE `id`='".$row->id."' ");
                
            }
            
        }
        
        $row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS * FROM `users` WHERE `id`='".$id."'"));

		if($row->icon == "") {

			$row->icon = "files/image/profile_default.png";

		}

		?>

		<main>

			<article id="mainbody">

				<div id="form-main">

					<form method="post" enctype="multipart/form-data">

							<?php

							if(isset($_POST['thumb']) && $error_count > 0) {

								foreach ($array_image['error'] as $error) {

									echo "<div>".$error."</div>";

								}

							}

							?>

							<div id="thumb-upload" class="">

								<div class="thumb-upload-item">

									<input class="inputfile" name="thumb" id="thumb" type="file">

									<label for="thumb"><span class="fa fa-download icon" aria-hidden="true"></span><span>Upload thumb</span></label>

								</div>

							</div>

							<div class="main-form">

								<label>Street</label>

								<input name="street" type="text" value="<?=htmlentities($row->street)?>" placeholder="Street">

								<label>City</label>

								<input name="city" type="text" value="<?=htmlentities($row->city)?>" placeholder="City">

								<label>State</label>

								<input name="state" type="text" value="<?=htmlentities($row->state)?>" placeholder="State">

								<label>Zip</label>

								<input name="zip" type="text" value="<?=htmlentities($row->zip)?>" placeholder="Zip">

								<label>Country</label>

									<?php

									$array=country();

									?>

									<select name="country">

										<?php

										foreach ($array as $key => $item ) {
                                            
                                            $selected = "";
                                            
                                            if($key == $row->country) {
                                                
                                                $selected = "selected";
                                                
                                            }
                                            

											echo '<option value="'.$key.'" '.$selected.'>'.$item.'</option>';

										}

										?>

									</select>

									<label>Email</label>

									<input name="email" type="email" value="<?=htmlentities($row->email)?>" placeholder="example<?=htmlentities($email_1)?>">

									<label>Password</label>

									<input name="password" type="password" value="" placeholder="********">

									<?php

									$checked = "";

									if ($row->banned==1) {

										$checked = " checked";

									}

									?>

									<label>Banned</label>

									<input type="hidden" name="banned" value="0">

									<input name="banned" type="checkbox" value="1"<?=$checked?>>

									<?php

									if(isset($_POST['submit'])) {

										if($errors_count > 0) {

											foreach ($errors as $errors) {

												echo "<div>".$errors."</div>";

											}

										}
										else {

											echo "<div>Your account details have been successfully updated.</div>";

										}

									}

									?>

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
