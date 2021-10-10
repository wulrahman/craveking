<?php

header('HTTP/1.0 404 Not Found');

$title="(404) Error !";

require_once('../common/include/header.php');

require_once('../common/include/main_header.php');

?>

<main>

	<article id="otherbody">

		<div>

			<h1>404 That's an error</h1>

			<div>The requested URL <?=$_SERVER['REQUEST_URI']?> was not found on this server. That's all we know.</div>

		</div>

	</article>

	<?php require_once("../common/include/main_footer.php"); ?>

</main>

<?php require_once("../common/include/footer.php"); ?>
