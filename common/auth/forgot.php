<?php

$title="Forgot password";

require_once('include/header.php');

require_once('include/main_header.php');

if (isset($_POST['submit'])) {

	$errors = array(); $email = $_POST["email"]; $username = $_POST["username"];

	$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id`,`email`,`username` FROM `users` WHERE `email`='".$email."' AND `username`='".$username."'");

	$row = mysqli_fetch_object($query);

	$count=array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if ($count == 0) {

		$errors[]="Invalid details were supplied.";

	}

	$error_count=count($errors);

	if($error_count == 0) {

		$errors[] = "Your new password has been sent to your email address.";

		$subject =   $setting["url"]." Password Reset";

		$salt = randomurl(randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]));

		$new_password = randomurl(randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]));

		$password = md5($salt.$new_password);

		mysqli_query($setting["Lid"],"UPDATE `users` SET `password`='".$password."', `salt`='".$salt."' WHERE `id`='".$row->id."' ");

		$message .= '<img src="'.$setting["url"].'/files/image/cragglist_logo.png" alt="Cragglist logo" style="padding:10px;">';
		$message .= '<div style="padding:10px;">Your password has been reset, These are you new login information.</div>';
		$message .= '<table rules="all" style="border-color: #666;margin:10px;" cellpadding="10">';
		$message .= "<tr style='background: #eee;'><td><strong>Username:</strong> </td><td>".$row->username."</td></tr>";
		$message .= "<tr style='background: #eee;'><td><strong>Password:</strong> </td><td>".$new_password."</td></tr>";
		$message .= "</table>";

		email_system($row->email, $subject, $message);

		$error_count=count($errors);

	}

}

?>

<main>

	<article id="otherbody">

		<div id="form-main">

			<form method="post" enctype="multipart/form-data">

				<div class="main-form">

					<label>Username</label>
					<input name="username" type="text" value="<?=htmlentities($username)?>" placeholder="username">

					<label>Email</label>
					<input name="email" type="email" value="<?=htmlentities($email)?>" placeholder="example@vlul.co.uk">

					<?php

					if(isset($_POST['submit']) && $error_count > 0) {

						foreach ($errors as $error) {

							echo "<div>".$error."</div>";

						}

					}

					?>

				</div>

				<div class="form-content-footer  pure-g">

						<div class="pure-u-1-2"></div>

						<div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Reset"></input></div>

				</div>

			</form>

		</div>

		<?php require_once("include/main_footer.php"); ?>

	</article>

</main>

<?php require_once("include/footer.php"); ?>
