<?php

require_once('common.php');

require_once('setting.php');

require_once('files/include/header.php');

if(isset($_GET['q'])) {
	
	$q=stripslashes(preg_replace('/\s\s+/', ' ', $_GET['q']));
	
}
else {
	
	$q="";
	
}

?>

<div class="navigation_results">
<form class="search-form" id="search-form">
<input type="search" class="input-text" name="q" value="<?=$q?>" id="search" autofocus autocomplete="off"><input type="submit" class="g-button" value="Search" /></form>
</div>

<?php

$url = 'http://www.omdbapi.com/?t='.urlencode($q).'&y=&plot=short&r=json';

$details = json_decode(gethtml($url, ""));

if($details->Response=='True') {

	echo '<div class="info_web-primary">';

	if($details->Poster !='N/A')	{

		echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/"><div class="main_image" style="background-image:url('.$details->Poster.');"></div></a>';

	}

	//Print the movie information
    	echo '<div class="main_info">';

	$rating = "";

	if($details->Rated !='N/A')	{

		$rating = $details->Rated;

	}

	echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/"><h3>'.$details->Title.' ('.$details->Year.') </h3></a><div class="rating">'.$rating.'</div>';

	echo '<div class="spacer"></div>';

	if($details->imdbRating !='N/A') {

    		echo '<b>'.$details->imdbRating.'</b> / 10';

	}

	if($details->imdbVotes !='N/A') {

    		echo ' from '.$details->imdbVotes.' users';

	}

	echo '<div class="spacer"></div>';
    	
	if($details->Plot !='N/A') {

		echo $details->Plot.'<div class="spacer"></div>';

	}
	
	if($details->Writer !='N/A') {

    		echo $details->Writer.'<div class="spacer"></div>';

	}
    	
	if($details->Genre !='N/A')	{

    		echo 'Genre '.$details->Genre.'<div class="spacer"></div>';

	}

	if($details->Released !='N/A')	{

   		echo $details->Released.', ';

	}

	if($details->Runtime !='N/A') {

		echo $details->Runtime;

	}

	echo '<div class="spacer"></div>';

	if($details->Director !='N/A')	{

    		echo 'Director '.$details->Director.'; ';

	}

	if($details->Actors !='N/A') {

		echo 'Cast '.$details->Actors;

	}

	echo '<div class="spacer"></div>';

	if($details->Language !='N/A')	{

    		echo 'Language '.$details->Language.'<div class="spacer"></div>';

	}

	if($details->Country !='N/A') {

    		echo 'Country '.$details->Country.'<div class="spacer"></div>';

	}

	if($details->Awards !='N/A') {

    		echo 'Awards '.$details->Awards.'<div class="spacer"></div>';

	}

	if($details->Metascore !='N/A') {

    		echo 'Metascore '.$details->Metascore.'<div class="spacer"></div>';

	}

	echo '</div></div>';

}

require_once('files/include/footer.php');

?>