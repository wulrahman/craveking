<?php

if($user->admin == 1) {

	ini_set('memory_limit', '1024M');

	ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

	$id = intval($_GET['id']);

	require_once('include/header.php');

	require_once('include/main_header.php');

	require_once('include/main_nav.php');

	$row = mysqli_fetch_object(mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `subject`, `timestamp`, `id`, `content` FROM `emails` WHERE `id`='".$id."'"));

	$email_data = mysqli_query($setting["Lid"],"SELECT SQL_CALC_FOUND_ROWS `subject`, `timestamp`, `id`, `content` FROM `emails`");

	?>

	<main>

		<article id="mainbody">

			<div id="email-main">

				<div id="layout">

					<div id="list" class="pure-u-1-4">

						<?php

						while ($list = mysqli_fetch_object($email_data)) { ?>

									<a href="<?=$setting["admin_url"]?>/email/<?=$list->id?>">

										<div class="email-item email-item-selected pure-g">

		                    <div class="pure-u-1">

		                        <h5 class="email-name"><?=stripslashes(limit_text($list->subject,4))?></h5>

		                        <h4 class="email-date"><?=time_elapsed_string(strtotime($list->timestamp))?></h4>

													  <p class="email-desc">

		                            <?=stripslashes(limit_text(strip_tags(indextext($list->content)),13))?>

		                        </p>

		                    </div>

		                </div>

									</a>

							<?php

						}

						?>

					</div>

					<div id="main" class="pure-u-1">

						<div id="form-main">

							<form method="post" enctype="multipart/form-data">

								<div class="email-content">

									<div class="email-content-header pure-g">

											<div class="pure-u-1">

													<h1 class="email-content-title"><input type="text" name="subject" value="<?=stripslashes($row->subject)?>"></input></h1>

											</div>

									</div>

									<div class="email-content-body">

										<div class="message">

											<?=stripslashes($row->content)?>

										</div>

									</div>

								</div>

							</form>

						</div>

					</div>

				</div>

			</div>

			<script type="text/javascript" src="<?=$setting['url']?>/files/js/editor.js"></script>

			<?php require_once("../common/include/main_footer.php"); ?>

		</article>

	</main>

	<?php

	require_once("../common/include/main_footer.php");
}
else {

	require_once('../common/pages/404.php');

}

?>
