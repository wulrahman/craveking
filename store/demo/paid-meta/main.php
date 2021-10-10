<div class="navigation_home">
<a href="<?=$site_url?>/"><img class="logo_home" src="<?=$site_url?>/files/image/logo_main.png" alt="logo"></a>
<form onsubmit="document.getElementById('suggest').style.display='none';" autocomplete="off" class="search-form" id="search-form">
<input type="search" class="input-text" name="q" id="search"  autofocus  autocomplete="off"  x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" lang="en" onkeyup="window.acpObj.ac.s(event,this);" onkeydown="window.acpObj.ac.s_enter(event,this);"><input type="submit" class="g-button" value="<?=$button?>" /><input type="hidden" name="type" value="<?=$type?>"></form>
</div>
<div class="footer_home">

<?php require_once("files/include/footer.php"); ?>

</div>