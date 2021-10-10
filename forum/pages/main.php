<?php

ob_start();

$title="Craveking Forum";

$description="Solving problems as a team, bringing the solution a click closer to you. Helping you to resolve a collection of coding problems, ranging from simple to complex, debugging and fault finding.";

$keywords="discussions, views, cragglist, programming, help, web, PHP, Ruby, CSS, HTML, post, question, Flash, Mysql, XML, Perl, Discuss";

require_once('include/header.php');

require_once('include/main_header.php');

$limit = '12';

$page=intval($_GET["page"]);

if ($page=="") {

	$page=1;

}

$start = ($page-1) * $limit;

$query = mysqli_query($setting["Lid"], "SELECT

SQL_CALC_FOUND_ROWS

`topic`.`name`, `topic`.`description`, `topic`.`id`, `topic`.`views`, `topic`.`color`,

(SELECT COUNT(`id`) FROM `ticket` WHERE `topic`=`topic`.`id`) as `count`

FROM `topic`

WHERE `topic`.`sub`='0'

ORDER BY `topic`.`id` ASC

LIMIT ".$start.", ".$limit."");

$count = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"],"SELECT FOUND_ROWS()")));

if($user->login_status == 1) {

	$ticket = closetags(convert_encoding(stripslashes($_POST['comment'])));

	$subject = htmlstring(convert_encoding(htmlentities($_POST['subject'])));

	$type = intval($_POST['type']);

	if(!(isset($_POST['agree'])) && (isset($_POST['submit']))) {

		if(space($_POST['subject']) || space($_POST['comment']) || $type == 0 ) {

				$errors[]="Please fill in all the above fields.";

		}

		if($user->login_status == 0) {

			$errors[]="Please login to add a ticket.";

		}

		$count_type = array_pop(mysqli_fetch_row(mysqli_query($setting["Lid"], "SELECT COUNT(`id`) FROM `topic` WHERE `id`='".$type."'")));

		if($count_type == 0) {

			$errors[]="Invalid type has be entered.";

		}

		if(indexpartialtext($_POST['comment']) != $_POST['comment']) {

			$errors[]="Please remove illegal HTML.";

		}

		$error_count = count($errors);

	}

}

?>

<main>

	<div class="otherbody">

		<?php include("../common/include/ads.php"); ?>

	</div>

	<article id="forumbody">

		<div>

			<?php

			if($user->login_status == 1) { ?>

				<div class="primaryform">

					<div id="textbox_html">

						<form action="save" method="post">

							<select name="type" class="main_select">

								<?php

								$querytype = mysqli_query($setting["Lid"], "SELECT `name`, `description`, `id`, `views` FROM `topic` ORDER BY `id` ASC ");

								while($rowtype = mysqli_fetch_object($querytype)) {

					  				echo '<option value="'.$rowtype->id.'">'.$rowtype->name.'</option>';

								}

								?>

							</select><input placeholder="Subject" type="text" value="<?=stripslashes($subject)?>" name="subject">

							<textarea placeholder="Ticket" id="noise" class="widgEditor nothing" name="comment"><?=stripslashes($ticket)?></textarea>

							<div class="primary-form-submit"><?php

								if(!isset($_POST['agree']) && isset($_POST['submit'])) {

									if($error_count > 0) {

										foreach ($errors as $error) {

											echo $error."";

										}

									}
									else {
                                        
                                        $ticket = addslashes(closetags(convert_encoding($ticket)));

                                        $subject = htmlstring(convert_encoding(htmlentities($subject)));

										mysqli_query($setting["Lid"], "INSERT INTO `ticket` (`user` ,`subject` ,`ticket`  ,`topic` ) VALUES ('".$user->id."',  '".$subject."',  '".$ticket."', '".$type."');");
                                        
										$id = mysqli_insert_id($setting["Lid"]);

										header("location: ".$setting["forum_url"]."/view/".$id."",  true);

									}

								}


								?>

								<input type="checkbox" name="agree">

								<input type="submit" name="submit" value="Send">

							</div>

						</form>

					</div>

				</div>

				<?php

			}

			if ($count > 0) { ?>

					<div class="pure-u-3-4 mobile_display">

						<div id="item-list">

							<ul>

								<?php

								while($row = mysqli_fetch_object($query)) {

									if($row->color == "") {

										$row->color = random_color();

										mysqli_query($setting["Lid"],"UPDATE `topic` SET `color` = '".$row->color."' WHERE `topic`.`id` = '".$row->id."';");

									}

									?>

									<li class="item-list pure-g category_home">

										<a href="<?=$setting["forum_url"]?>/topic/<?=$row->id?>">

											<div class="topic-item-main">

												<div class="icon pure-u-3-24 hide_mobile">

													<div class="item-thumb" style="background-color:#<?=$row->color?>"><?=ucfirst(substr($row->name,0,1))?></div>

												</div><div class="pure-u-15-24 topic-item-topic">

													<h5  class="topic-item-name-home">

														<?=limit_text($row->name,14)?>

													</h5>

													<div class="topic-item-description"><?=limit_text(strip_tags($row->description),40)?></div>

												</div><div class="pure-u-3-24 topic-item-static">

													<?=$row->count?> <b>TOPIC</b>

												</div><div class="pure-u-3-24 topic-item-static">

													<?=$row->views?> <b>VIEWS</b>

												</div>

											</div>

										</a>

									</li>

								<?php

								}

								?>

							</ul>

							<div class="pagination">

								<?php

								$previous = $page-1;

								$next = $page+1;

								$total = ceil($count / $limit);

								$url = $setting["forum_url"].'/';

								if ($page > 1){

									echo '<li><a href="'.$url.$previous.'">Previous</a></li>';

								}

								for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {

									echo '<li><a href="'.$url.$i.'">'.$i.'</a></li>';

								}

								if ($page < $total){

									echo '<li><a href="'.$url.$next.'">Next</a></li>';

								}

								?>

							</div>

						</div>

					</div><div class="pure-u-1-4 mobile_display">

						<?php

						include('../common/include/ads.php');

						?>

					</div>

			<?php

			}
			else {

					?>

					<div>

						<h1>No post where found.</h1>

					</div><?php

			}
			?>

		</div>

		<?php require_once("../common/include/main_footer.php"); ?>

	</article>

</main>

<?php require_once("../common/include/footer.php"); ?>

<script>
(function(h,e,a,t,m,p) {
m=e.createElement(a);m.async=!0;m.src=t;
p=e.getElementsByTagName(a)[0];p.parentNode.insertBefore(m,p);
})(window,document,'script','https://u.heatmap.it/log.js');
</script>

<script>
	$(function() {
		$('#noise').froalaEditor({toolbarInline: false})
	});
</script>

<!-- Include Editor style. -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css' rel='stylesheet' type='text/css' />

<!-- Include JS file. -->
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/js/froala_editor.min.js'></script>
