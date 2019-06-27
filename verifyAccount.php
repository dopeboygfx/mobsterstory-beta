<?
include 'nliheader.php';

if (isset($_GET['email']) && isset($_GET['code'])) 
{	
	$email = $_GET['email'];
	$code = $_GET['code'];

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `email` = ? and `verification_code` = ?');
	$result->execute(array($email, $code));
	$user_info = $result->fetch(PDO::FETCH_ASSOC);

	if($user_info['id'] > 0 && $user_info['verified'] == 0)
	{
		$verify = $GLOBALS['pdo']->prepare('update `grpgusers` set `verified` = 1 WHERE `email` = ? AND `verification_code` = ?');
	    $verify->execute(array($email, $code));
		
		$message = "Your account has been verified successfully.";
	}
	else if($user_info['id'] > 0 && $user_info['verified'] == 1)
	{
		$message = "Your account is already verified. Kindly login using your username and password.";
	}
	else
	{
		$message = "Sorry! Invalid activation link.";
	}
}

if (isset($message)) 
{
	echo Message($message);
}
?>

</table>
<br>
<center>
<br />&copy; Mobster Story 2018 | Designed By: <a href="http://instagram.com/twolucky.img">Two Lucky</a>
</center>
  </td></tr>
<?
include 'nlifooter.php';
?>