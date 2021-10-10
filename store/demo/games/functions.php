<?php
error_reporting(0);

// Main site

global $site_url;
//$url="cragglist.uphero.com";
$url="localhost/cragglist/demo/games";
$site_url="http://".$url."";

global $no_reply;
$email_1="@cragglist.com";
$no_reply="no_reply".$email_1.""; 

global $Lid;
//$Lid = mysqli_connect('mysql16.000webhost.com', 'a9208466_shoppin', 'preinstall1', 'a9208466_shoppin');
$Lid = mysqli_connect('localhost', 'root', '', 'game');

function limit_text( $string, $limiter ) {
	
	$count = str_word_count($string, 2);
	$key = array_keys($count);
	
	if (count($count) > $limiter) {
		$string = trim(substr($string, 0, $key[$limiter])).'&#8230;';
	}
	
	return $string;
}

function mysqli( $string ) {
	
	global $Lid;
	return mysqli_real_escape_string($Lid,$string);
	
}

function htmllink($row) {

	preg_match_all ("@<(link|a|img)(.*?)>@i", $row->html, $matches);

	foreach($matches['2'] as $subject) {
		preg_match('@(href|src)=["\'](.*?)["\']@i', $subject, $match);

		if(!empty($match['2']) && strpos($match['2'],"http://") != 'FALSE' && strpos($match['2'],"https://") != 'FALSE' && strpos($match['2'],"mailto") != 'FALSE' ) {
			$old=$match['2'];

			if(substr($match['2'], 0, 2) == '//') {
				$match['2'] = substr($match['2'], 0, -2);
			}

			if (substr($match['2'], -1) == '/') {
				$match['2'] = substr($match['2'], 0, -1);
			}

			$match['2']="games/html/".$row->html_folder."/".$match['2'];
			$links[]=array('old'=>$old,'url'=>$match['2']);
		}

	}
	if (count($links) > 0) {
		foreach($links as $key => $link) {
			$html = str_ireplace($link['old'], $link['url'], $row->html);
		}
	}
	else {
		$html=$row->html;
	}

	return $html;
}


function html($row) {

	$html = '<iframe src="html_game.php?name='.$row->name.'&id='.$row->id.'" id="html_iframe" width="'.$row->width.'" height="'.$row->height.'"></iframe>';
	return $html;
}

function unity3d($row) {

	$content = '<script type=\'text/javascript\' src=\'https://ssl-webplayer.unity3d.com/download_webplayer-3.x/3.0/uo/jquery.min.js\'></script>
	<script type="text/javascript">
	<!--
	var unityObjectUrl = "http://webplayer.unity3d.com/download_webplayer-3.x/3.0/uo/UnityObject2.js";
	if (document.location.protocol == \'https:\')
		unityObjectUrl = unityObjectUrl.replace("http://", "https://ssl-");
		document.write(\'<script type="text\/javascript" src="\' + unityObjectUrl + \'"><\/script>\');
	-->
	</script>
	<script type="text/javascript">
	<!--
		var config = {
			width: '.$row->width.', 
			height: '.$row->height.',
			params: { enableDebugging:"0" }
				
		};
		config.params["disableContextMenu"] = true;
		var u = new UnityObject2(config);
			
		jQuery(function() {

			var $missingScreen = jQuery("#unityPlayer").find(".missing");
			var $brokenScreen = jQuery("#unityPlayer").find(".broken");
			$missingScreen.hide();
			$brokenScreen.hide();

			u.observeProgress(function (progress) {
				switch(progress.pluginStatus) {
					case "broken":
					$brokenScreen.find("a").click(function (e) {
						e.stopPropagation();
						e.preventDefault();
						u.installPlugin();
						return false;
					});
					$brokenScreen.show();
					break;
					case "missing":
					$missingScreen.find("a").click(function (e) {
						e.stopPropagation();
						e.preventDefault();
						u.installPlugin();
						return false;
					});
					$missingScreen.show();
					break;
					case "installed":
					$missingScreen.remove();
					break;
					case "first":
					break;
				}
			});
			u.initPlugin(jQuery("#unityPlayer")[0], "'.$row->unity3d.'");
		});
	-->
	</script>
	<div id="unityPlayer">
		<div class="missing">
			<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
				<img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" />
			</a>
		</div>
	</div>';
	return $content;
}

function swf($row) {

	$html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab" width="'.$row->width.'" height="'.$row->height.'" align="middle">
	<param name="movie" value="'.$row->swf.'">
	<param name="quality" value="high">
	<param name="bgcolor" value="#000000">
	<param name="play" value="true">
	<param name="loop" value="true">
	<param name="wmode" value="direct">
	<param name="scale" value="showall">
	<param name="menu" value="true">
	<param name="devicefont" value="false">
	<param name="salign" value="">
	<param name="allowScriptAccess" value="sameDomain">
	<param name="allowFullScreen" value="true">
	<!--[if !IE]>-->
	<object type="application/x-shockwave-flash" data="'.$row->swf.'" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab" width="'.$row->width.'" height="'.$row->height.'">
	<param name="movie" value="'.$row->swf.'">
	<param name="quality" value="high">
	<param name="bgcolor" value="#000000">
	<param name="play" value="true">
	<param name="loop" value="true">
	<param name="wmode" value="direct">
	<param name="scale" value="showall">
	<param name="menu" value="true">
	<param name="devicefont" value="false">
	<param name="salign" value="">
	<param name="allowScriptAccess" value="sameDomain">
	<param name="allowFullScreen" value="true">
	<!--<![endif]-->
	<a href="http://www.adobe.com/go/getflash">
	<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player">
	</a>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
	</object>';
	return $html;
}
function dcr($row) {
	
	$html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab" id="'.$row->name.'" width="'.$row->width.'" height="'.$row->height.'" VIEWASTEXT>
	<param name="src" value="'.$row->dcr.'">
	<param name="swRemote" value="swSaveEnabled="true" swVolume="true" swRestart="true" swPausePlay="true" swFastForward="true" swContextMenu="true">
	<param name="swStretchStyle" value="fill">
	<param name="bgColor" value="#000000">
	<param name="quality" value="high">
	<param name="allowFullScreen" value="true">
	<embed src="'.$row->dcr.'" bgColor="#000000"  width="'.$row->width.'" height="'.$row->height.'" swRemote="swSaveEnabled="true" swVolume="true" swRestart="true" swPausePlay="true" swFastForward="true" swContextMenu="true" swStretchStyle="fill" type="application/x-director" pluginspage="http://www.adobe.com/go/getflashplayer" allowFullScreen="true">
	</embed>
	</object>';
	return $html;
}
?>