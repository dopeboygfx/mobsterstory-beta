<?
include 'header.php';

if (isset($_POST['submit'])) {
	if (url_exists(strip_tags($_POST['avatar'])) == 0 && strip_tags($_POST['avatar']) != "")
		{
		$message.= "<div>Your avatar link appears to be broken. Please check it in your browser.</div>";
		}
	
$_POST['avatar'] = str_replace('"', '', $_POST['avatar']);
	$_POST['avatar'] = str_replace('[IMG]', '', $_POST['avatar']);
	$_POST['avatar'] = str_replace('[/IMG]', '', $_POST['avatar']);
	$_POST['avatar'] = str_replace('[img]', '', $_POST['avatar']);
	$_POST['avatar'] = str_replace('[/img]', '', $_POST['avatar']);
	$avatar = strip_tags($_POST["avatar"]);
	$avatar = addslashes($avatar);
    $_POST['quote'] = str_replace('"', '', $_POST['quote']);
	$quote = strip_tags($_POST["quote"]);
	$quote = addslashes($quote);
  $quote = $_POST["quote"];
  //insert the values
  if (!isset($message)){
  	$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `avatar` = ?, `quote` = ? WHERE `id` = ?");
	$result->execute(array($avatar, $quote, $user_class->id));
    echo Message('Your preferences have been saved.');
	include('footer.php');
	die();
  }
}
?>
<?
if (isset($message)) {
echo Message($message);
}
?>
<thead>
  <tr>
    <th>Preferences</th>
  </tr>
</thead>
<tr><td>
<form name='login' method='post'>
  <table width='50%' border='0' align='center' cellpadding='0' cellspacing='0'>
  	<tr>
      <td height='28'><font size='2' face='verdana'>Avatar Image Location&nbsp;&nbsp;&nbsp;</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='text' name='avatar' value='<?= $user_class->avatar ?>'>
        </font></td>
    </tr>
    <tr>
    <tr>
      <td height='28' align="right"><font size='2' face='verdana'>Quote&nbsp;&nbsp;&nbsp;</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='text' name='quote' value='<?= $user_class->quote ?>'>
        </font></td>
    </tr>
      <td>&nbsp;</td>
      <td><font size='2' face='verdana'>
        <input class="ui mini yellow button" type='submit' name='submit' value='Save Preferences'>
        </font></td>
    </tr>
</table>
</form>
<?
include 'footer.php';
?>
