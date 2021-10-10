<?php

$title="Login";

require_once('include/header.php');

require_once('include/main_header.php');

$redirect = $_POST['redirect'];

if(!isset($_POST['redirect'])) {
    
    if($_SERVER['HTTP_REFERER'] != "") {
        
        $redirect = $_SERVER['HTTP_REFERER'];
        
    }
    else {
        
        $redirect = $setting["url"]."/";
        
    }
    
}

if ((empty($_POST['agree'])) && (isset($_POST["submit"]))) {

	session_start();

	$errors = array();

	if ((!$_POST['username']) || (!$_POST['password'])) {

		$errors[]="Incorrect login details have been entered.";

	}
	else {

		$username = htmlspecialchars($_POST['username']); $password_old = $_POST['password'];

		$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS * FROM `users` WHERE `username`='".$username."'");

		$user_exist = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

		if ($user_exist > 0) {

			$row = mysqli_fetch_object($query);

			$password = md5($row->salt.$password_old);

			if($password == $row->password) {

				if($row->banned == 1) {

					$errors[]= 'Your account has been blocked for violating one of our term of use and for misusing our site.';

				}
				else if ($row->active == 0){

					$errors[] = 'Your account inactive, please validate your account via the validation email.';
					$errors[] = 'Final step: just before you can start using you account, you must verify your email.';
					$errors[] = 'Please check your inbox for a validation email.';

					$hash = randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]);

					mysqli_query($setting["Lid"],"UPDATE `users` SET `hash` = '".$hash."' WHERE `id` = '".$row->id."'");

					$subject = $setting["url"]." Account Valification";

					$message .= '<img src="'.$setting["url"].'/files/image/cragglist_logo.png" alt="Cragglist logo" style="padding:10px;">';
					$message .= '<div style="padding:10px;">Thanks for signing up!</br>';
					$message .= 'Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.</br>';
					$message .= '</br>Please click this link to activate your account:</br>';
					$message .= '<a href="'.$setting["url"].'/verify/'.$row->id.'/'.$hash.'">'.$setting["url"].'/verify/'.$row->id.'/'.$hash.'</a></div>';

					email_system($row->email, $subject, $message);

				}
				else {

					$salt = randomurl(randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]).randomurl($setting["alp"]));

					$password = md5($salt.$password_old);

					mysqli_query($setting["Lid"],"UPDATE `users` SET `password` = '".$password."', `salt` = '".$salt."' WHERE `id` = '".$row->id."'");

					if (isset($_POST['remember'])) {

						setcookie("username", $row->username, time()+60*60*24*100, "/");

						setcookie("userid", $row->id, time()+60*60*24*100, "/");

						setcookie("code", $password, time()+60*60*24*100, "/");

						setcookie("username", $row->username, time()+60*60*24*100, "/", ".".$setting["domain"]);

						setcookie("userid", $row->id, time()+60*60*24*100, "/", ".".$setting["domain"]);

						setcookie("code", $password, time()+60*60*24*100, "/", ".".$setting["domain"]);

					}
					else {

						setcookie("username", $row->username, 0, "/");

						setcookie("userid", $row->id, 0, "/");

						setcookie("code", $password, 0, "/");

						setcookie("username", $row->username, 0, "/", ".".$setting["domain"]);

						setcookie("userid", $row->id, 0, "/" ,".".$setting["domain"]);

						setcookie("code", $password, 0, "/", ".".$setting["domain"]);

					}

					header("Location: ".$redirect."");

				}

			}
			else {

				$errors[]="Incorrect login details have been entered.";

			}

		}
		else {

			$errors[]="Incorrect login details have been entered.";

		}

	}
    
    $error_count = count($errors);

}

?>

<main>

	<article id="otherbody">

		<div id="form-main">

			<form method="post" enctype="multipart/form-data">

				<div class="main-form">

					<label>Username</label>
					<input name="username" value="<?=htmlentities($username)?>" type="text" placeholder="Guest">

					<label>Password</label>
					<input name="password" type="password" placeholder="********">

					<input type="checkbox" name="agree">

					<input type="checkbox" name="remember"><label>Remember me</label>

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

				    <div class="form-content-controls pure-u-1-2"><input name="submit" type="submit" class="secondary-button pure-button" value="Login"></input></div>

				</div>
            
                <input name="redirect" type="hidden" value="<?=$redirect?>">

			</form>

		</div>

		<?php require_once("include/main_footer.php"); ?>

	</article>

</main>

<?php require_once("include/footer.php"); ?>
