<?php

$comment_query = mysqli_query($setting["Lid"], "SELECT SQL_CALC_FOUND_ROWS `ticket`.`spam`,`ticket`.`status`, `ticket`.`topic`, `ticket`.`ticket`, `ticket`.`subject`, `ticket`.`timestamp`, `ticket`.`id`, `ticket`.`user`, `ticket`.`read`, `ticket`.`reply_to`, (SELECT `temp`.`id` FROM `ticket` as `temp` WHERE `temp`.`reply_to` = `ticket`.`id` ORDER BY `temp`.`id` DESC LIMIT 1) AS `activeorder`  FROM `ticket` WHERE `ticket`.`post`='".$row->id."' AND `ticket`.`reply` = '1' ORDER BY IFNULL(`activeorder`, `ticket`.`id`) DESC");

$comment_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if($user->login_status == 1) { ?>

	<div id="item-list">

		<ul>

			<li class="commentbox">

				<div class="item-list pure-g">

					<div class="right" id="noiseWidgContainer">

						<div id="textbox_html">

							<form class="comment_box" action="save" method="post">

								<input placeholder="Subject" type="text" value="<?php

								if($error_count > 0 && $_POST['type'] == "comment" && $comment_id == $row->id) {

									echo stripslashes(htmlstring(convert_encoding(htmlentities($_POST['subject_'.$row->id]))));

								}
								else {

									echo "Re :".stripslashes($row->subject);

								}

								?>" name="subject_<?=$row->id?>">

								<textarea placeholder="Ticket" id="noise" class="comment_textarea" name="comment_<?=$row->id?>"><?php

									if($error_count > 0 && $_POST['type'] == "comment"  && $comment_id == $row->id) {

										echo closetags(convert_encoding(stripslashes($_POST['comment_'.$row->id])));

									}

								?></textarea>

								<?php

								if((empty($_POST['agree'])) && $error_count > 0  && $comment_id == $row->id) {

										foreach ($errors as $error) {

											echo "<div>".$error."</div>";

										}

								}

								?>

								<input type="checkbox" name="agree">

								<input type="hidden" name="id" value="<?=$row->id?>" >

								<input type="hidden" name="comment_id" value="<?=$row->id?>" >

								<input type="hidden" name="type" value="comment">

								<input type="hidden" name="page" value="<?=$page?>">

								<input type="submit" name="submit" value="Submit">

							</form>

							<script>
								$(function() {
									$('#noise').froalaEditor()
								});
							</script>

						</div>

					</div>

				</div>

			</li>

		</ul>

	</div>

<?php

}

if ($comment_count > 0) {

	$i = 0;

	 ?>

	<div id="item-list">

		<ul>

			<?php

			while ($comment_row = mysqli_fetch_object($comment_query)) {

					$comment_user = getUser($comment_row->user);

					?>

					<li>

						<?php

						$i++;

						if($i % 2 == 0) {

							echo '<div class="item-list pure-g item-list-even">';

						}
						else {

							echo '<div class="item-list pure-g">';

						}

						?>

						<div class="pure-u-1-8 hide_mobile">

							<?php

							if($comment_user->icon == "") {

								echo '<div class="item-thumb" style="background-color:#'.$comment_user->color.';">'.ucfirst(substr($comment_user->username,0,1)).'</div>';

							}
							else {

								echo '<img class="item-thumb" src="'.$setting["url"].'/common/'.$comment_user->icon.'">';

							}

							?>

						</div>

						<div class="pure-u-7-8 mobile_display">

							<?php

							if($comment_row->reply_to > 0) {

								$reply_to = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `ticket`.`user` FROM `ticket` WHERE `ticket`.`id`='".$comment_row->reply_to.""));

								$reply_user = getUser($reply_to->user);

								if($reply_to->user != $comment_row->user) {

									echo '<p class="reply_to">reply to <span class="reply_user">'.$reply_user->username.'</span></p>';

								}

							}

							?>

							<h5 class="comment-name"><?=limit_text($comment_user->username,3)?></h5>

							<span class="comment-date"><?=time_elapsed_string(strtotime($comment_row->timestamp))?></span>	<?php

							$like_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `rating_topic` WHERE `topic`='".$comment_row->id."'")));

							?>

							<div class="comment-like">

								<form action="" class="like_box" method="post">

									<input type="image" src="<?=$setting["url"]?>/common/files/img/like_button.png" name="like_submit" value="Submit">

									<input type="hidden" name="id" value="<?=$row->id?>">

									<input type="hidden" name="type" value="like">

									<input type="hidden" name="comment_id" value="<?=$comment_row->id?>" >

									<input type="checkbox" name="agree">

								</form>

								<?=$like_count?>

							</div>

							<?php

							if($user->admin == 1 && $user->login_status == 1) { ?>

								<div class="comment-spam">

									<form class="spam_box" action="" method="post">

										<input type="hidden" name="id" value="<?=$row->id?>" >

										<input type="hidden" name="type" value="spam" >

										<input type="submit" name="spam" value="spam">

										<input type="hidden" name="comment_id" value="<?=$comment_row->id?>">

										<input type="checkbox" name="agree">

									</form>

								</div>

							<?php

							}

							if($user->login_status == 1) { ?>

								<button class="show" name="comment_<?=$comment_row->id?>">Reply</button><?php

							} ?>

							<p class="item-desc">

								<?php

								if($comment_row->spam == 1) {

									echo "This comment was marked as spam by our Administrator.";

								}
								else {

									echo $comment_row->ticket;

								}

								?>

							</p>

						</div>

					</div>

				</li>

				<?php

				if($user->login_status == 1) { ?>

					<li id="comment_<?=$comment_row->id?>" class="comment-reply comment_box_reply">

						<div class="item-list pure-g">

							<div class="right" id="noiseWidgContainer">

								<div id="textbox_html">

									<form class="comment_box" action="save" method="post">

										<input placeholder="Subject" type="text" value="<?php

										if($error_count > 0 && $_POST['type'] == "comment" && $comment_id == $comment_row->id) {

											echo stripslashes(htmlstring(convert_encoding(htmlentities($_POST['subject_'.$comment_row->id]))));

										}
										else {

											echo "Re :".stripslashes($comment_row->subject);

										}

										?>" name="subject_<?=$comment_row->id?>">

										<textarea placeholder="Ticket" id="noise_<?=$comment_row->id?>" class="comment_textarea" name="comment_<?=$comment_row->id?>"><?php

											if($error_count > 0 && $_POST['type'] == "comment" && $comment_id == $comment_row->id) {

												echo closetags(convert_encoding(stripslashes($_POST['comment_'.$comment_row->id])));

											}

										?></textarea>

										<script>
										  $(function() {
										    $('#noise_<?=$comment_row->id?>').froalaEditor()
										  });
										</script>

										<?php

										if((empty($_POST['agree'])) && $error_count > 0  && $comment_id == $comment_row->id) {

												foreach ($errors as $error) {

													echo "<div>".$error."</div>";

												}

										}

										?>

										<input type="checkbox" name="agree">

										<input type="hidden" name="id" value="<?=$row->id?>">

										<input type="hidden" name="comment_id" value="<?=$comment_row->id?>">

										<input type="hidden" name="type" value="comment">

										<input type="submit" name="submit" value="Submit">

										<button class="hide" name="comment_<?=$comment_row->id?>">X</button>

									</form>

								</div>

							</div>

						</div>

					</li>

				<?php

				}

			}

			?>

		</ul>

	</div>

<?php

}
?>

<script>
    $(".hide").click(function(){
      var name = this.name;
        $("#" + name).hide();
    });
    $('.show').click(function(){
        var name = this.name;
        $("#" + name).show();
    });
	request_post("comment_box");
	request_post("like_box");
	request_post("spam_box");
</script>

<!-- Include Editor style. -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css' rel='stylesheet' type='text/css' />

<!-- Include JS file. -->
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/js/froala_editor.min.js'></script>
