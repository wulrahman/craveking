<?php
$title="TB2";
require_once('functions.php'); 
require_once('include/header.php');
$category=mysqli_fetch_object(mysqli_query($Lid, "SELECT `id`, `name` FROM `category` WHERE `name`='".mysqli($_GET['category'])."' "));
$limit = '10';
   $page=intval($_GET["page"]);
   if ($page=="") {
	   $page=1;
   }
$start = ($page-1) * $limit;
$games = mysqli_query($Lid, "SELECT SQL_CALC_FOUND_ROWS `name`, `description`, `id` FROM `games` WHERE `category`='".$category->id."'  ORDER BY `id` DESC LIMIT ".$limit."");
$count=array_pop(mysqli_fetch_row(mysqli_query($Lid,"SELECT FOUND_ROWS()")));
?>
<div class="home_main">
<ul id="home_game_main">
<?php
while($row = mysqli_fetch_object($games)) {
	$image = mysqli_fetch_object(mysqli_query($Lid, "SELECT `url` FROM `images` WHERE `game` = ".$row->id." ORDER BY `order` ASC LIMIT 1"));
	echo '<li>	<a href="play.php?name='.$row->name.'&id='.$row->id.'">
	<img  class="games_thumb" alt='.$row->name.' src="'.$image->url.'"/></a>
	<a href="play.php?name='.$row->name.'&id='.$row->id.'">'.$row->name.'</a></li>';
}
?>
</ul>
<div id="pagination">
<ul>
<?php
$previous=$page-1;
$next=$page+1;
$total = ceil($count / $limit);
$url=$site_url."/category.php?category=".$category->name."&page=";
if ($page > 1) {
	echo '<li><a href="'.$url.$previous.'">&#10092;</a><li>';
} 
for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {
	echo '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
} 
if ($page < $total) {
	echo '<li><a href="'.$url.$next.'">&#10093;</a></li>';
}
?>
</ul>
</div>
<?php require_once('include/footer.php'); ?>
</div>
</div>
</body>
</html>