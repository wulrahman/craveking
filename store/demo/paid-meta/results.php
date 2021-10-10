<div class="navigation_results">
<a href="<?=$site_url?>/"><img class="logo_results" src="<?=$site_url?>/files/image/logo_main.png" alt="logo"></a>
<form onsubmit="document.getElementById('suggest').style.display='none';" autocomplete="off" class="search-form" id="search-form">
<input type="search" class="input-text" name="q" value="<?=$q?>" id="search" autofocus autocomplete="off" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" lang="en" onkeyup="window.acpObj.ac.s(event,this);" onkeydown="window.acpObj.ac.s_enter(event,this);"><input type="hidden" name="type" value="<?=$type?>"><input type="submit" class="g-button" value="<?=$button?>" /></form>
</div>

<?php	
	
$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
if($page=="" or $page==" ") {

	$page="1";

}

$qe=urlencode($q);
$qs=urlencode($qw);
		
if($type=="image" || $type=="video") {

	if($type =="image") {

		image($qs,$page,21);

	}
	else if($type =="video") {

		video($qs,$page,21);

	}

	pagination('?q='.$qe.'&type='.$type,$page);

}
else {

	echo '<div class="results_container">';

	if($type =="news") {

		news($qs,$page,21);

	}
	else {

		web($qs,$page,12);
		pagination('?q='.$qe.'&type='.$type,$page);

	}

	echo'</div>';

}

require_once("files/include/footer.php");

?>