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
			$row = mysqli_fetch_object($sql);
			if($row->type == "html") {
				echo $content = htmllink($row);
			}
}
?>