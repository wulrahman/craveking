<header>

<div class="main_menu">

	<label class="collapse" for="_1"><img class="search_menu_image" src="<?=$setting["search_url"]?>/files/image/menu_main.png"></label>
	<input id="_1" type="checkbox">
	<div>
		<nav class="main_home_search">
			<ul>
				<?php

				include("include/search_nav.php");

				?>
			</ul>
		</nav>
	</div>
</div>

<form action="<?=$setting["search_url"]?>" class="form-search">
	<a href="<?=$setting["search_url"]?>"><img class="logo-search" src="<?=$setting["search_url"]?>/files/image/cloud_logo.png" alt=""></a>
	<div class="main_suggestion">
	<input type="text" name="q" class="txt-primary" placeholder="search" autofocus autocomplete="off" value="<?=htmlspecialchars($_GET['q'])?>" id="q">
  	<datalist id="suggestions">
  	</datalist>
	</div>
	<input type="hidden" name="tbm" value="<?=htmlspecialchars($_GET['tbm'])?>">
	<input type="image" src="<?=$setting["search_url"]?>/files/image/search.png" name="submit" class="btn-primary">
</form>

<nav class="main_web_search">
	<ul>
		<?php

		include("include/search_nav.php");
		
		?>
	</ul>

</nav></header>
