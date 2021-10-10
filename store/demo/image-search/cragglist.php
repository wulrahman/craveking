<?php

$cragglist=gethtml('https://www.cragglist.com/test/search/?q='.$q.'&limit=40&page='.$page.'');

$data = json_decode($cragglist);

foreach ($data as $result) {

	$arrays[]=array('url'=>$result->found,'src'=>$result->src,'type'=>"cragglist", 'title'=>$result->alt);

}

?>