<div id="mainnav">

	<div id="logo"><a href="<?=$setting["admin_url"]?>"><img src="<?=$setting["url"]?>/common/files/img/logo.png"></img></a></div>

	<form method="GET" id="search" action="<?=$setting["url"]?>/content">

		<div class="searchshowhim"><div class="searchshow"><input type="search" name="q" autofocus="" placeholder="search" value="<?=htmlspecialchars($_GET['q'])?>"></input></div><input type="image" src="<?=$setting["url"]?>/common/files/img/search.png" name="submit"></input></div>

	</form>

	<div id="navfloatright">

		<nav class="pure-menu custom-restricted-width">

			<ul class="pure-menu-list">

				<li id="navbutton" class="pure-menu-has-children pure-menu-allow-hover">

					<a href="#" id="menuLink1" class="pure-menu-link"><img src="<?=$setting["url"]?>/common/files/img/menu.png"></img></a>

					<ul class="pure-menu-children">

						<?php

						if($user->login_status == 1 && $user->admin == 1) { ?>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/products">Products</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/comments">Comments</a></li>
                        
                            <li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/topics">Topics</a></li>
                        
                            <li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/categories">Catergories</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/members">Members</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/sales">Sales</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/email">Email</a></li>
                        
                        	<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/games">Games</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["admin_url"]?>/feed">Feed</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/setting">Setting</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/logout">Logout</a></li><?php

						}
						else if($user->login_status == 1 && $user->admin == 0) { ?>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/purchased">Purchased</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/setting">Setting</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/logout">Logout</a></li><?php

						}
						else if($user->login_status == 0) { ?>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/login">Login</a></li>

							<li class="pure-menu-item"><a class="pure-menu-link" href="<?=$setting["url"]?>/register">Register</a></li><?php

						}

						?>

					</ul>

				</li>

			</nav>

		</ul>

	</div>

</div>
