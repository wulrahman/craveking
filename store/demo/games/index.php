<?php
$title="TB2";
require_once('functions.php'); 
require_once('include/header.php');
$limit = '10';
$games = mysqli_query($Lid, "SELECT `name`, `description`, `id` FROM `games` ORDER BY `id` DESC LIMIT ".$limit."");
?>
<div class="home_main">
<ul id="home_game_main">
<?php
while($row = mysqli_fetch_object($games)) {
	$image = mysqli_fetch_object(mysqli_query($Lid, "SELECT `url` FROM `images` WHERE `game` = ".$row->id." ORDER BY `order` ASC LIMIT 1"));
	echo '<li>	<a href="play.php?name='.$row->name.'&id='.$row->id.'">
	<img class="games_thumb" alt='.$row->name.' src="'.$image->url.'"/></a>
	<a class="game_name" href="play.php?name='.$row->name.'&id='.$row->id.'">'.$row->name.'</a></li>';
}
?>
</ul>
<?php require_once('include/footer.php'); ?>
</div>
</div>
</body>
</html>