<?php

setcookie("username", "", time()-60*60*24*100, "/");

setcookie("userid", "", time()-60*60*24*100, "/");

setcookie("code", "", time()-60*60*24*100, "/");

setcookie("username", "", time()-60*60*24*100, "/" ,".".$setting["doamin"]);

setcookie("userid", "", time()-60*60*24*100, "/" ,".".$setting["domain"]);

setcookie("code", "", time()-60*60*24*100, "/" ,".".$setting["domain"]);

session_destroy();

header("location: ".$setting["url"]."/");

?>
