<?php

$title="Successfully processed";

require_once("../setting.php");

require_once("../portable-utf8.php");

require_once("../common.php");

require_once('../common/include/header.php');

require_once('../common/include/main_header.php');

setcookie("carts", "", time()-60*60*24*100, "/");

setcookie("carts", "", time()-60*60*24*100, "/" ,".".$setting["url"]);

?>

<main>

	<article id="otherbody">

		<div>

			<h1>Successfully processed</h1>

			<div>Thank you! Your order has been successfully processed. Your order is instantly accessible and downloadable via the user dashboard.</div>

		</div>

	</article>

	<?php require_once("../common/include/main_footer.php"); ?>

</main>

<?php require_once("../common/include/footer.php"); ?>
