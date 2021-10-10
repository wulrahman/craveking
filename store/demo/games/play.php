<?php
require_once('functions.php'); 
$id = intval($_GET['id']);
$name = eregi_replace("`", "",$_GET['name']);
$sql = mysqli_query($Lid,"SELECT * FROM `games` WHERE `id`='".$id."' AND `name`='".$name."'");
if(!$sql) {
	die(mysql_error());
	}
	else if (mysqli_num_rows($sql) <= 0) {
		echo "<p>That record does not exist.</p>";
		}
		else {
			require_once('include/header.php');
			$row = mysqli_fetch_object($sql);
			echo '<div class="game_main">';
			echo '<p>'.$row->name.'</p>' ;
			if($row->type == "html") {
				$content = html($row);
			}
			else if($row->type == "unity3d") {
				$content = unity3d($row);
			}
			else if($row->type == "swf") {
				$content = swf($row);
			}
			else if($row->type == "dcr") {
				$content = dcr($row);
			}
			echo '<div id="game">'.$content.'</div>';
			require_once('include/footer.php');
			echo '</div>';
?>
</div>
</body>
</html>
<?php
}
?>