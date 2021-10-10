<?php
$title="TB2";
require_once('functions.php'); 
require_once('include/header.php');
if ($_GET[category] !== "all" || isset($_GET[category]) ) {
$category=mysqli_fetch_object(mysqli_query($Lid, "SELECT `id`, `name` FROM `category` WHERE `name`='".mysqli($_GET['category'])."' "));
$category_count=array_pop(mysqli_fetch_row(mysqli_query($Lid,"SELECT FOUND_ROWS()")));
if ($category_count > 0) {
	$array[] =" `category`='".$category->id."'";
}
}
$limit = '10';
$page=intval($_GET["page"]);
if ($page=="") {
	$page=1;
}
$q=mysqli($_GET[q]);
if (isset($_GET[q])) {
	foreach(array_filter(explode(" ",$q)) as $p) {
		$new[]=$p=preg_replace("/[^A-Za-z0-9\s]/", "", $p);
		$qs[]='+'.$p;
	}

	$qs=mysqli(implode("",$qs));

	$array[] =" ((3.0 * (MATCH(`name`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) + (2.0 * (MATCH(`description`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) + (0.5 * (MATCH(`instructions`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) )";
}

if (count($array) > 0) {
	$where=" WHERE".implode(" AND",$array);
} else {
	$where ="";
}

$start = ($page-1) * $limit;
$games = mysqli_query($Lid, "SELECT SQL_CALC_FOUND_ROWS `name`, `description`, `id`, ((3.0 * (MATCH(`name`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) + (2.0 * (MATCH(`description`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) + (0.5 * (MATCH(`instructions`) AGAINST ('".$qs."*' IN BOOLEAN MODE))) ) AS relevance FROM `games`".$where." ORDER BY `id` DESC LIMIT ".$limit."");
$count=array_pop(mysqli_fetch_row(mysqli_query($Lid,"SELECT FOUND_ROWS()")));
?>
<div class="home_main">
<ul id="home_game_main">
<?php
while($row = mysqli_fetch_object($games)) {
	$image = mysqli_fetch_object(mysqli_query($Lid, "SELECT `url` FROM `images` WHERE `game` = ".$row->id." ORDER BY `order` ASC LIMIT 1"));
	echo '<li>	<a href="play.php?name='.$row->name.'&id='.$row->id.'">
	<img  class="games_thumb" alt='.$row->name.' src="'.$image->url.'"/></a>
	<a class="game_name" href="play.php?name='.$row->name.'&id='.$row->id.'">'.$row->name.'</a></li>';
}
?>
</ul>
<div id="pagination">
<ul>
<?php
$previous=$page-1;
$next=$page+1;
$total = ceil($count / $limit);
$url=$site_url."/search.php?category=".$category->name."&q=".urlencode($q)."&page=";
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