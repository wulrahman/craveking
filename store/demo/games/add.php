 <script type='text/javascript' src='http://code.jquery.com/jquery-1.5.2.js'></script>
<script type='text/javascript'>
	<!-- Begin
function FileType( fileName, fileTypes ) {
if (!fileName) return;

dots = fileName.split(".")
//get the part AFTER the LAST period.
fileType = "." + dots[dots.length-1];

return (fileTypes.join(".").indexOf(fileType) != -1) ?
('') : 
alert("Please only upload files that end in types: \n\n" + (fileTypes.join(" .")) + "\n\nPlease select a new file and try again.");
return false;
}
// -->
</script><? 
$game_name = eregi_replace("`", "",$_POST['game_name']);
$game_description = eregi_replace("`", "",$_POST['game_description']);
$game_instructions = eregi_replace("`", "",$_POST['game_instructions']);
$game_flash = eregi_replace("`", "",$_POST['game_flash']);
$game_date = $_POST['game_date'];
$game_image = eregi_replace("`", "",$_POST['game_image']);
$game_time = $_POST['game_time'];
$game_width = preg_replace("/[^0-9]/", "",$_POST['game_width']);
$game_height = preg_replace("/[^0-9]/", "",$_POST['game_height']);
$random = substr(number_format(time() * rand(),0,'',''),0,10);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Game's <? if (isset($_POST["game_name"])) echo"- ".$_POST["game_name"]."";?></title>
<META NAME="Author" CONTENT="waheed-ul rahman, waheedpay@hotmail.co.uk">
</head>
<body>
<fieldset style="padding:10px; width:400px; margin:0px auto;">

  <LEGEND>Toabigo - Game's - Add a game</LEGEND>
  <p><form id="form" name="form" method="post" action="" enctype="multipart/form-data">
<table width="100%" border="0">
  <tr>
    <td><label for="textfield">Name :</label></td>
    <td>
    <input type="text" value="<? echo $game_name;?>" style="font-size:18px" size="34" name="game_name" id="game_name" /></td>
  </tr>
  <tr>
    <td><label for="textfield">Description:</label></td>
    <td><textarea name="game_description" cols="26" rows="5" id="game_description" style="font-size:18px"><? echo $game_description;?></textarea></td>
  </tr>
  <tr>
    <td><label for="textfield">Instructions:</label></td>
    <td><textarea name="game_instructions" cols="26" rows="5" id="game_instructions" style="font-size:18px"><? echo $game_instructions;?></textarea></td>
  </tr>
  <tr>
    <td><label for="textfield">Image</label></td>
    <td><input type="url" style="font-size:18px" size="34" name="game_image" id="game_image" /></td>
  </tr>
  <tr>
    <td><label for="textfield">Flash :</label></td>
    <td><input type="url" style="font-size:18px" size="34" name="game_flash" id="game_flash" /></td>
  </tr>
  <tr>
    <td><label for="textfield">Width :</label></td>
    <td>
    <input type="number" value="<? echo $game_width;?>" style="font-size:18px" size="34" name="game_width" id="game_width" /></td>
  </tr>
  <tr>
    <td><label for="textfield">Height :</label></td>
    <td>
    <input type="number" value="<? echo $game_height;?>" style="font-size:18px" size="34" name="game_height" id="game_height" /></td>
  </tr>
  <tr>
    <td></td>
    <td>
    <input name="game_date" type="hidden" value="<? echo date("y-m-d", time()); ?>" />
    <input name="game_time" type="hidden" value="<? echo date("G:i:s", time()); ?>" />
    <input type="submit" name="button" id="button" onClick="FileType(this.form.game_image.value, ['.gif', 'jpg', 'png', 'jpeg']); FileType(this.form.game_flash.value, ['.swf']);" value="Submit" /></td>
  </tr>
</table>
  </form></p>
<?php
//Identify and calculate Value
if($_POST['button'])
{
if($game_name == ""){ echo "Game name is empty"; exit();}
else if($game_description == ""){ echo "Game description is empty"; exit();}
else if($game_instructions == ""){ echo "Game instruction is empty"; exit();}
else if($game_flash == ""){ echo "Game flash is empty"; exit();}
else if($game_image == ""){ echo "Game image is empty"; exit();}
else if($game_width == ""){ echo "Game width is empty"; exit();}
else if($game_height == ""){ echo "Game height is empty"; exit();}
include('connect.php');
if (isset($_POST["button"]))
{
$sql="INSERT INTO games (game_name, game_description, game_flash , game_date, game_image, game_time, game_width, game_height, game_instructions)
VALUES ('$game_name','$game_description','$game_flash','$game_date','$game_image','$game_time','$game_width','$game_height','$game_instructions')";

if (!mysql_query($sql))
  {
  die('Error: ' . mysql_error());
  }
echo "Your game has been added";
}
}
?>

<p><?php 
 $time = time () ; 
 //This line gets the current time off the server

 $year= date("Y",$time); 
 //This line formats it to display just the year

 echo "&copy; " . $year . " Cragglist Inc. All rights reserved";
 //this line prints out the copyright date range, you need to edit 2012 to be your opening year
 ?></p>
 </fieldset>
 
</body>
</html>