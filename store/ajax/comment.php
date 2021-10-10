<?php

require_once("../../setting.php");

require_once("../../portable-utf8.php");

require_once("../../common.php");

$id = intval($_POST['id']);

$comment_id = intval($_POST["comment_id"]);

$query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id` FROM `products` WHERE `id`='".$id."'");

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($count > 0) {

	$error_count = 0;

	$row = mysqli_fetch_object($query);

	if($user->login_status == 1 && (empty($_POST['agree']))) {

		$comment_query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `id` FROM `comment` WHERE `id`='".$comment_id."'");

		$comment_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

		if ($comment_count > 0) {

			$comment_row = mysqli_fetch_object($comment_query);

			if (($user->admin == 1) && ($_POST['type'] == "spam")) {

				mysqli_query($setting["Lid"], "UPDATE `comment` SET `comment`.`spam` = '1' WHERE `comment`.`id` = '".$comment_row->id."'");

			}
			else if ($_POST['type'] == "like") {

				$comment_like_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `rating_comment` WHERE `user`='".$user->id."' AND `comment`='".$comment_row->id."' LIMIT 1")));

				if($comment_like_count == 0) {

					mysqli_query($setting["Lid"],"INSERT INTO `rating_comment` (`user`, `comment`) VALUES('".$user->id."', '".$comment_row->id."')");

				}

			}

		}
		if ($_POST['type'] == "comment") {

			if(space($_POST['comment'])) {

				$errors[]="Please fill in textarea.";

			}
			else {

				if($count == 0) {

					$comment_row->id = 0;

				}

				$error_count = intval(count($errors));

				if(isset($error_count) && $error_count == 0) {

					$comment = htmlstring(convert_encoding(htmlentities($_POST['comment'])));

					mysqli_query($setting["Lid"],"INSERT INTO `comment` (`user`, `comment`, `product`, `reply_to`) VALUES('".$user->id."', '".$comment."', '".$row->id."', '".$comment_row->id."')");

				}

			}

		}

	}

	require_once("../include/comment.php");

}

?>
