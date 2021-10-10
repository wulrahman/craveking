<?php

ob_start();

$query = mysqli_query($setting["Lid"],'SELECT `name`, `product`, `file_name`, `mime`, `free`, `id` FROM `products` WHERE `id`="'.intval($_GET['id']).'"');

$row = mysqli_fetch_object($query);

if ($row->free=="1" && $user->login_status == 1) {

	$ok ="1";

}
else {

	if($user->login_status == 1) {

		$purchase = mysqli_fetch_object(mysqli_query($setting["Lid"],'SELECT SQL_CALC_FOUND_ROWS * FROM(SELECT `product`, `paid`, `status` FROM `purchase` WHERE `user`="'.$user->id.'" AND `product`="'.$row->id.'" ORDER BY `id` DESC) as inv GROUP BY `product`'));

		if ($purchase->paid == 1 || $user->admin == "1") {

			$ok ="1";

		}

	}

}

if ($ok =="1") {

	mysqli_query($setting["Lid"],"UPDATE `products` SET `downloads` = `downloads` + 1 WHERE `id`='".$row->id."'");

	$out = $row->product;

	$filename = tempnam('/tmp', 'cre');
	$fp = fopen($filename, 'w');

	fwrite($fp, $out);
	fclose($fp);

	header("Content-Type: ".$row->mime);
	header("Content-Disposition: attachment; filename=".urlencode($row->file_name).".".$row->mime);
	header("Content-Length: ".filesize($filename));

	unlink($filename);

	echo $out;

}
else {

	header("Location: ".$setting["url"]."/login");

}

?>
