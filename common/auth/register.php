<?php

$title="Register";

require_once('include/header.php');

require_once('include/main_header.php');

if((empty($_POST['agree'])) && (isset($_POST['submit']))) {

	$errors = array();

	$username = htmlspecialchars($_POST['username']); $password = $_POST['password']; $confirm_password = $_POST['confirm_password']; $email = str_replace(" ","",$_POST['email']);

	if($confirm_password !== $password) {

		$errors[]="passwords don't match";

	}

	mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `email` FROM `users` WHERE `email`='".$email."'");

	$email_count=array_pop(mysqli_fetch_array(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if($email_count > 0) {

		$errors[]="email already exists.";

	}
	else if(!validate_email($email)) {

		$errors[]="please insert a valid email.";

	}

	mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `username` FROM `users` WHERE `username`='".$username."'");

	$username_count=array_pop(mysqli_fetch_array(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

	if($username_count > 0) {

		$errors[]="username already exists.";

	}
	else {

		$username_valid = preg_match('/^[A-Za-z \-][A-Za-z0-9 \-]*(?:_[A-Za-z0-9 ]+)*$/', $username);

		if(($username_valid == false)) {

			$errors[]="you must enter a valid alphanumeric username";

		}

	}

	if((!$username) || (!$email) || (!$password) || (!$confirm_password)) {

		$errors[]="please make sure that you have correctly filled in all of the fields.";

	}

	$error_count = count($errors);

	if($error_count == 0) {

		$subject = $setting["site_url"]." Account Valification";

		$errors[] = 'Your account has been successfully created.';
		$errors[] = 'Final step: just before you can start using you account, you must verify your email.';
		$errors[] = 'Please check your inbox for a validation email.';

		$salt = randomurl(randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]));

		$salt_password = md5($salt.$password);

		$hash = randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]);

		mysqli_query($setting["Lid"],"INSERT INTO `users` (`username`, `password`, `email`, `salt`, `hash`) VALUES('".$username."', '".$salt_password."', '".$email."', '".$salt."', '".$hash."')");

		$id = mysqli_insert_id($setting["Lid"]);

		$message .= '<img src="'.$setting["url"].'/files/image/cragglist_logo.png" alt="Cragglist logo" style="padding:10px;">';
		$message .= '<div style="padding:10px;">Thanks for signing up!</br>';
		$message .= 'Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.</br>';
		$message .= '</br>Please click this link to activate your account:</br>';
		$message .= '<a href="'.$setting["url"].'/verify/'.$id.'/'.$hash.'">'.$setting["url"].'/verify/'.$id.'/'.$hash.'</a></div>';

		email_system($email, $subject, $message);

		$error_count = count($errors);

	}

}

?>

<main>

	<article id="otherbody">

		<div id="form-main">

			<form method="post" enctype="multipart/form-data">

				<div class="main-form">

						<label>Username</label>
						<input name="username" type="text" value="<?=htmlentities($username)?>" placeholder="Guest">

						<label>Email</label>
						<input name="email" type="email" value="<?=htmlentities($email)?>" placeholder="example<?=htmlentities($email_1)?>">

						<label>Password</label>
						<input name="password" type="password" value="<?=htmlentities($password)?>" placeholder="********">

						<label>Confirm Password</label>
						<input name="confirm_password" type="password" value="" placeholder="********">

						<div>By submiting this form you agree to our terms of use.</div>

						<?php

						if((empty($_POST['agree'])) && (isset($_POST['submit'])) && $error_count > 0) {

							foreach ($errors as $error) {

								echo "<div>".$error."</div>";

							}

						}

						?>

					</div>

					<div class="form-content-footer  pure-g">

							<div class="pure-u-1-2"><a href="<?=$setting["url"]?>/forgot">forgot password!</a></div>

							<div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Register"></input></div>

					</div>

				</form>

			</div>

		<?php require_once("include/main_footer.php"); ?>

	</article>

</main>

<?php require_once("include/footer.php"); ?>
