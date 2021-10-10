<footer>Powered by <a href="https://www.cragglist.com/">Cragglist</a> / Safe <a href="?safe=<?php if($_COOKIE[safe]=="1") {
		echo '0';
		} else {
		echo '1';
		} ?>"><?php if($_COOKIE[safe]=="1") {
		echo 'off';
		} else {
		echo 'strict';
		} ?></a>
</footer>