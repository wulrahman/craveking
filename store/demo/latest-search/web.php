<?php

$title="Cragglist Web";

if ($_GET['q']) {

	$title .= " - ".$_GET['q'];

}

require_once("include/header.php");
require_once("include/search_header.php");
require_once("include/search_nav.php");
	

$page = preg_replace('/[^-0-9]/', '', $_GET['page']);
		
if($page=="" or $page==" ") {

	$page="1";

}

$qs=urlencode($q);
		
echo '<div class="results_container" id="web_results_margin_display_inline">';

web($qs,$page,12);

$url = 'http://www.omdbapi.com/?t='.$qs.'&y=&plot=short&r=json';

$details = json_decode(gethtml($url, ""));

if($details->Response=='True') {

	echo '<div class="web_side_bar_test">';

	if($details->Poster !='N/A')	{

		echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/"><img src="'.$details->Poster.'"></a><br>';

	}

	//Print the movie information
    	echo '<a href="http://www.imdb.com/title/'.$details->imdbID.'/">'.$details->Title.' ('.$details->Year.')</a><br>';

	if($details->Rated !='N/A') {

    		echo 'Rated : '.$details->Rated.'<br>';

	}

	if($details->Released !='N/A')	{

   		echo 'Released Date : '.$details->Released.'<br>';

	}

	if($details->Runtime !='N/A') {

		echo 'Runtime : '.$details->Runtime.'<br>';

	}
    	
	if($details->Genre !='N/A')	{

    		echo 'Genre : '.$details->Genre.'<br>';

	}

	if($details->Director !='N/A')	{

    		echo 'Director : '.$details->Director.'<br>';

	}
	
	if($details->Writer !='N/A') {

    		echo 'Writer : '.$details->Writer.'<br>';

	}

	if($details->Actors !='N/A') {

		echo 'Actors : '.$details->Actors.'<br>';

	}
    	
	if($details->Plot !='N/A') {

		echo 'Plot : '.$details->Plot.'<br>';

	}
    	
	if($details->Language !='N/A')	{

    		echo 'Language : '.$details->Language.'<br>';

	}

	if($details->Country !='N/A') {

    		echo 'Country : '.$details->Country.'<br>';

	}

	if($details->Awards !='N/A') {

    		echo 'Awards : '.$details->Awards.'<br>';

	}

	if($details->Metascore !='N/A') {

    		echo 'Metascore : '.$details->Metascore.'<br>';

	}

	if($details->imdbRating !='N/A') {

    		echo 'IMDB Rating : '.$details->imdbRating.'<br>';

	}

	if($details->imdbVotes !='N/A') {

    		echo 'IMDB Votes : '.$details->imdbVotes.'<br>';

	}

	echo '</div>';

}

require_once('include/footer.php');

echo '</div>
</div>
</body>
</html>';

?>