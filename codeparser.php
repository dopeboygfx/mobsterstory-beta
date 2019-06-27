<?php
include("dbcon.php");

function BBCodeParse($str){

$str = str_replace("[url]http://www.", "[url]http://", $str);
$str = str_replace("[url]http://www2.", "[url]http://", $str);
$str = str_replace("[url]www.", "[url]http://", $str);
$str = str_replace("[url]www2.", "[url]http://", $str);

   // The array of regex patterns to look for
   $format_search =  array(
      '#\[b\](.*?)\[/b\]#is', // Bold ([b]text[/b]
	  '#\[center\](.*?)\[/center\]#is', // Center ([center]text[/center]
      '#\[i\](.*?)\[/i\]#is', // Italics ([i]text[/i]
      '#\[u\](.*?)\[/u\]#is', // Underline ([u]text[/u])
      '#\[s\](.*?)\[/s\]#is', // Strikethrough ([s]text[/s])
      '#\[color=(.*?)\](.*?)\[/color\]#is', // Font color ([color=#00F]text[/color])
      '#\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]#i', // Hyperlink with descriptive text ([url=http://url]text[/url])
      '#\[url\]((?:ftp|https?)://.*?)\[/url\]#i', // Hyperlink ([url]http://url[/url])
      '#\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]#i', // Image ([img]http://url_to_image[/img]
	  '#\[youtube\](.*?)\[/youtube\]#is', // Youtube [youtube]CODE[/youtube]
	  '#\[mp3\](.*?)\[/mp3\]#is', // MP3 player [mp3]URL[/mp3]
      '#\[size=(.*?)\](.*?)\[/size\]#is', // Font color ([color=#00F]text[/color])
	  '#\[me\]#is' // Formatted username ([me])

	  
   );
   // The matching array of strings to replace matches with
   $format_replace = array(
      '<b>$1</b>',
	  '<center>$1</center>',
      '<i>$1</i>',
      '<u>$1</u>',
      '<s>$1</s>',
      '<font color="$1">$2</font>',
      '<a href="$1">$2</a>',
      '<a href="$1">$1</a>',
      '<img src="$1" alt="" border="0" />',
	  '<object width="500" height="375"><param name="movie" value="http://www.youtube.com/v/$1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/$1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="500" height="375"></embed></object>',
	  '',
	  '<font size="$1">$2</font>',
	  $user_class->formattedname
   );

   
   $smiley_search = array(
      ':smile:',
	  ':sad:',
	  ':grin:',
	  ':shock:',
	  ':hmm:',
	  ':tongue:',
	  ':yay:',
	  ':haha:',
	  ':zzz:',
	  ':star:',
	  ':angry:',
	  ':evil:',
	  ':angel:',
	  ':confused:',
	  ':eek:',
	  ':cool:',
	  ':shifty:',
	  ':huh:',
	  ':love:',
	  ':wink:',
	  ':P',
	  ':)',
	  ':(',
	  ':-)',
	  ':-(',
	  ';)',
	  ':D'
	);
	
	$smiley_replace = array(
	  '<img src="smileys/smile.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/frown.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/teeth.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/suprise.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/worry.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/tongue.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/anime.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/laugh.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/sleepy.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/star.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/angry.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/evil.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/saint.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/confuse.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/neutral.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/cool.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/shifty.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/boggle.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/heart.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/wink.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/tongue.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/smile.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/frown.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/smile.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/frown.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/wink.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />',
	  '<img src="smileys/teeth.gif" width="16" height="16" border="0" style="vertical-align: bottom;" alt="" />'
	);
	  
   // Perform the actual conversion
   $str = preg_replace($format_search, $format_replace, $str);
   $str = preg_replace_callback("/\[user\](.*)\[\/user\]/", "displayInfo('\\1')", $str);
   $str = preg_replace_callback("/\[user2\](.*)\[\/user2\]/", "displayInfo2('\\1')", $str);
   $str = str_replace($smiley_search, $smiley_replace, $str);
   // Convert line breaks in the <br /> tag
   $str = nl2br($str);
   return $str;
}

function MP3Parse($str2){
   
   $result = mysql_query("SELECT * FROM `grpgusers` WHERE `id` = '".$_SESSION['id']."'");
	$worked = mysql_fetch_array($result);
   
   $search =  array(
	  '#\[mp3\]((?:ftp|https?)://.*?)\[/mp3\]#i' // Hyperlink ([url]http://url[/url])
	  
   );
   
   // The matching array of strings to replace matches with
   $replace = array('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="30" height="30" id="player"><param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="http://mafia-warfare.com/player.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#111111" /><param name="FlashVars" value="mp3=$1&vol='.$worked['volume'].'" /><embed src="http://mafia-warfare.com/player.swf" quality="high" bgcolor="#111111" width="30" height="30" name="player" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" FlashVars="mp3=$1&vol='.$worked['volume'].'" /></object>');
	  
   // Perform the actual conversion
   $str2 = preg_replace($search, $replace, $str2);
   // Convert line breaks in the <br /> tag
   return $str2;
}


?>