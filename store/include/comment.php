<?php

$comment_query = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `comment`.`product`,`comment`.`reply_to`,`comment`.`read`, `comment`.`id`,`comment`.`comment`, `comment`.`user`, `comment`.`timestamp`, `comment`.`spam`, (SELECT `temp`.`id` FROM `comment` as `temp` WHERE `temp`.`reply_to` = `comment`.`id` ORDER BY `temp`.`id` DESC LIMIT 1) AS `activeorder` FROM `comment` WHERE `comment`.`product`='".$row->id."' ORDER BY IFNULL(`activeorder`, `comment`.`id`) DESC");

$comment_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if((empty($_POST['agree'])) && (isset($_POST['submit'])) && $error_count > 0 ) {

		foreach ($errors as $error) {

			echo "<div>".$error."</div>";

		}

}

if($user->login_status == 1) { ?>

	<div id="item-list">

		<ul>

			<li class="commentbox">

				<div class="item-list pure-g">

					<div class="right">

						<form class="comment_box" action="" method="post">

							<textarea class="comment_textarea" name="comment"></textarea>

							<input type="checkbox" name="agree">

							<input type="hidden" name="id" value="<?=$row->id?>" >

							<input type="hidden" name="type" value="comment" >

							<input type="hidden" name="page" value="<?=$page?>">

							<input type="submit" name="submit" value="Submit">

						</form>

					</div>

				</div>

			</li>

		</ul>

	</div>

<?php

}

if ($comment_count > 0) {

	$i = 0; ?>

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

								$reply_to = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `comment`.`user` FROM `comment` WHERE `id` = '".$comment_row->reply_to."'"));

								$reply_user = getUser($reply_to->user);

								if($reply_to->user != $comment_row->user) {

									echo '<p class="reply_to">reply to <span class="reply_user">'.$reply_user->username.'</span></p>';

								}

							}

							?>

							<h5 class="comment-name"><?=limit_text($comment_user->username,3)?></h5>

							<span class="comment-date"><?=time_elapsed_string(strtotime($comment_row->timestamp))?></span>	<?php

							$like_count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `rating_comment` WHERE `comment`='".$comment_row->id."'")));

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

										<input type="hidden" name="comment_id" value="<?=$comment_row->id?>" >

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

									echo $comment_row->comment;

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

							<div class="right">

								<form class="comment_box" action="" method="post">

									<textarea class="comment_textarea" name="comment"></textarea>

									<input type="checkbox" name="agree">

									<input type="hidden" name="id" value="<?=$row->id?>">

									<input type="hidden" name="comment_id" value="<?=$comment_row->id?>">

									<input type="hidden" name="type" value="comment" >

									<input type="submit" name="submit" value="Submit">

									<button class="hide" name="comment_<?=$comment_row->id?>">X</button>

								</form>

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
