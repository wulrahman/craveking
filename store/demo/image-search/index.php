<?php
require_once('function.php');
$start=0;

$q=urlencode($_GET['q']);
$source=$_GET['source'];

$title="Cragglist image ";
$q = eregi_replace("`", "",$_GET['q']);

if (isset($_GET["q"]))

$title .= "- ".$q."";
require_once('files/include/header.php');

$limit="10";
$page=intval($_GET['page']);

if ($page == 0 || $page =="" ) {
	$page=1;
}

if($q=='') {

	echo '<div id="searchbox_home">
	<img src="'.$site_url.'/files/image/logo.png" class="home_logo" alt="logo" border="0"/>
	<form method="get" action="">
	<input name="q" type="text" value="'.urldecode($q).'" placeholder="search.." id="q"><select name="source">';

	$menus=array('all', 'yahoo', 'google', 'bing', 'cragglist'); 
	foreach ($menus as $menu) {
 		echo '<option value="'.$menu.'">'.$menu.'</option>';
	}

	echo '</select><input type="submit" class="btn submit" value="Search">
	</form>
	</div>';

echo '<div class="footer_home">';
require_once('files/include/footer.php');
echo '</div>';

}
else {
	echo '<header id="searchbox_result">
	<form method="get" action="">
	<a href="'.$site_url.'/">
	<img src="'.$site_url.'/files/image/logo.png" class="result_logo" alt="logo" border="0"/>
	</a>
	<input name="q" type="text" value="'.urldecode($q).'" placeholder="search.." id="q"><select name="source">';

	$menus=array('all', 'yahoo', 'google', 'bing', 'cragglist'); 

	foreach ($menus as $menu) {

 		echo '<option value="'.$menu.'" ';

		if ($menu == $source) {
			
			echo 'selected';

		}

		echo '>'.$menu.'</option>';

	}

	echo '</select><input type="submit" class="btn submit" value="Search">
	</form>
	</header>';

$q=urlencode($q);

if($source=="yahoo") {
	include('yahoo.php');
}
if($source=="cragglist") {
	include('cragglist.php');
}
if($source=="google") {
	include('google.php');
}
if($source=="bing") {
	include('bing.php');
}

if($source=="" or $source=="all") {
	include('yahoo.php');
	include('google.php');
	include('bing.php');
	include('cragglist.php');
}

echo '<div class="result_main" id="container">
	<ul class="result">';
foreach ($arrays as $array => $data )  {
	echo '<li class="item"><a href="'.$data['url'].'"><img src="'.$data['src'].'"></a></li>';
}
echo '</ul>';

echo '<div id="pagination">
<ul>';

$count="100";
$previous=$page-1;
$next=$page+1;
$total = ceil($count / $limit);
$url=$site_url."/?q=".$q."&source=".$source."&page=";

if ($page > 1) {
	echo '<li><a href="'.$url.$previous.'">&#10092;</a><li>';
} 
for ($i = max(1, $page - 5); $i <= min($page + 5, $total); $i++) {
	echo '<li><a href="'.$url.$i.'">'.$i.'</a></li>';
} 
if ($page < $total) {
	echo '<li><a href="'.$url.$next.'">&#10093;</a></li>';
}

echo '</ul>
</div>
</div>';

echo '<div class="footer_result">';
require_once('files/include/footer.php');
echo '</div>';

}
?>
</body>
</html>