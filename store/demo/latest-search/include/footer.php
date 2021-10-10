<footer id="footer">
<div id="main_footer">Powered by <a href="https://www.cragglist.com/">Cragglist</a> / Safe <a href="?safe=<?php
		if($_COOKIE[safe]=="1") {
			echo '0';
		}
		else {
			echo '1';
		}
		echo '">';

		if($_COOKIE[safe]=="1") {
			echo 'off';
		}
		else {
			echo 'strict';
		}
		echo '</a>';
		?>
</div>
</footer>