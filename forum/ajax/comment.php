<?php

require_once("../../setting.php");

require_once("../../portable-utf8.php");

require_once("../../common.php");

$id = intval($_POST['id']);

$comment_id = intval($_POST['comment_id']);
 
$ticket = addslashes(closetags(convert_encoding($_POST['comment_'.$comment_id])));

$subject = htmlstring(convert_encoding(htmlentities($_POST['subject_'.$comment_id])));

$query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`read`, `ticket`.`views` FROM `ticket` WHERE `ticket`.`id`='".$id."' AND `ticket`.`reply`='0' AND `ticket`.`hide`='0' ");

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if ($count > 0) {

	$error_count = 0;

	$row = mysqli_fetch_object($query);

	if($user->login_status == 1 && (empty($_POST['agree']))) {

		$comment_query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`, `ticket`.`subject`, `ticket`.`ticket`, `ticket`.`status`, `ticket`.`id`, `ticket`.`timestamp`, `ticket`.`user`, `ticket`.`read`, `ticket`.`views` FROM `ticket` WHERE `ticket`.`id`='".$comment_id."'");

		$comment_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

		$comment_row = mysqli_fetch_object($comment_query);

		if($user->id == $row->user && $row->read == "0") {

			mysqli_query($setting["Lid"],"UPDATE `ticket` SET  `read` = '1' WHERE `id` ='".$row->id."';");

		}

		if (($user->admin == "1" || $user->id  == $row->user) && $row->status == "1") {

			$key = array( 1 => 'Active', 2 => 'Close');

			if(in_array($status, $key)) {

				$key = array_search($status, $key);

				mysqli_query($setting["Lid"],"UPDATE `ticket` SET `status` = '".$key."' WHERE `id` ='".$row->id."';");

			}

		}

		if (($_POST['type'] == "spam") && ($user->admin == 1)) {

			mysqli_query($setting["Lid"], "UPDATE `ticket` SET `spam` = '1' WHERE `id` = '".$comment_row->id."';");

		}

		if ($_POST['type'] == "like") {

			$topic_like_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `rating_topic` WHERE `user`='".$user->id."' AND `topic`='".$comment_row->id."' LIMIT 1")));

			if($topic_like_count == 0) {

				mysqli_query($setting["Lid"],"INSERT INTO `rating_topic` (`user`, `topic`) VALUES('".$user->id."', '".$comment_row->id."')");

			}

		}

		if ($_POST['type'] == "comment") {

			if((space($_POST['subject_'.$comment_id]) || space($_POST['comment_'.$comment_id])) || (indexpartialtext($_POST['comment_'.$comment_id]) != $_POST['comment_'.$comment_id])) {

				if(space($_POST['subject_'.$comment_id]) || space($_POST['comment_'.$comment_id])) {

					$errors[]="Please fill in all the fields.";

				}

				if(indexpartialtext($_POST['comment_'.$comment_id]) != $_POST['comment_'.$comment_id]) {

					$errors[]="Please remove illegal HTML.";

				}

				$error_count = count($errors);

			}

			$error_count = count($errors);

			if($error_count == 0) {

				$reply_query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`user` FROM `ticket` WHERE `ticket`.`reply_to`='".$comment_row->id."' GROUP BY `ticket`.`user`");

				$reply_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

				mysqli_query($setting["Lid"],"INSERT INTO `ticket` (`reply`, `reply_to`, `user` ,`subject` ,`ticket`, `post`) VALUES ('1', '".$comment_row->id."', '".$user->id."',  '".$subject."',  '".$ticket."', '".$row->id."');");

				$id_new = mysqli_insert_id($setting["Lid"]);

				if($reply_count > 0) {

					while($reply_row = mysqli_fetch_object($reply_query)) {

						if($comment_row->user !== $reply_row->user || $row->user !== $reply_row->user || $user->id !== $reply_row->user) {

							mysqli_query($setting["Lid"],"INSERT INTO `message` (`ip`, `to`, `from`, `forum`, `forum_id`, `reply_id`) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$reply_row->user."', '".$user->id."', '1', '".$row->id."', '".$id_new."');");

						}

					}

				}

				if (($row->user !== $user->id) || ($user->id !== $comment_row->user)) {

					if($row->user == $comment_row->user) {

						mysqli_query($setting["Lid"],"INSERT INTO `message` (`ip`, `to`, `from`, `forum`, `forum_id`, `reply_id`) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$row->user."', '".$user->id."', '1', '".$row->id."', '".$id_new."');");

					}
					else {

						mysqli_query($setting["Lid"],"INSERT INTO `message` (`ip`, `to`, `from`, `forum`, `forum_id`, `reply_id`) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$comment_row->user."', '".$user->id."', '1', '".$row->id."', '".$id_new."');");

						mysqli_query($setting["Lid"],"INSERT INTO `message` (`ip`, `to`, `from`, `forum`, `forum_id`, `reply_id`) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$row->user."', '".$user->id."', '1', '".$row->id."', '".$id_new."');");

					}

				}

			}

		}

	}

	require_once("../include/comment.php");

}

?>
