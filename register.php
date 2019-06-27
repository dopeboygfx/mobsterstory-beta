<?
include 'nliheader.php';

if (isset($_POST['submit'])) {

  $username = strip_tags($_POST["newname"]);
  $loginname = strip_tags($_POST['username']);
  $signuptime = time();
  $password = sha1($_POST["newpass"]);
  $password2 = sha1($_POST["newpassagain"]);
  $email = $_POST["email"];
  
	$checkuser = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ? OR `login_name` = ?');
	$checkuser->execute(array($username, $username));
	$username_exist = !empty($checkuser->fetch(PDO::FETCH_NUM));

	$checkUserEmail = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `email` = ?');
	$checkUserEmail->execute(array($email));
	$userEmailExist = !empty($checkUserEmail->fetch(PDO::FETCH_NUM));

  if($username_exist > 0){
    $message .= "<div>I'm sorry but the username you chose has already been taken.  Please pick another one.</div>";
  }
  if($userEmailExist > 0){
    $message .= "<div>Sorry! you are already registered with this email address.</div>";
  }
  if(strlen($username) < 4 or strlen($username) > 20){
    $message .= "<div>The username you chose has " . strlen($username) . " characters. You need to have between 4 and 20 characters.</div>";
  }
  if(strlen($password) < 4 or strlen($username) > 20){
    $message .= "<div>The password you chose has " . strlen($password) . " characters. You need to have between 4 and 20 characters.</div>";
  }
  if($password != $password2){
    $message .= "<div>Your passwords don't match. Please try again.</div>";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message .= "<div>The e-mail address you entered was invalid.</div>";
  }

  //insert the values
  if (!isset($message)){
    
	$verifyCode = md5( rand(0,10000) );

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `grpgusers` (ip, login_name, username, password, email, signuptime, lastactive, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $result->execute(array($_SERVER['REMOTE_ADDR'], $username, $username, $password, $email, $signuptime, $signuptime, $verifyCode));
	
	//*************** email sending code ************//
	$subject = 'Account Verification';
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Additional headers
	$headers .= 'From: Mobster Story <noreply@mobsterstory.com>' . "\r\n";
	
	$msg = "<p>Dear $loginname,</p>

				<p>You have successfully created your Account with the Mobster Story.</p>

				<p>In order to activate your account, please click the following link:<br />
					<a href='https://mobsterstory.com/verifyAccount.php?email=".$email."&code=".$verifyCode."'>https://mobsterstory.com/verifyAccount.php?email=".$email."&code=".$verifyCode."</a>
				</p>

				<p>If you cannot access the above link, please copy and paste the URL into your web browser.</p>

				<p>Regards,<br />
				Team Mobster Story</p>";
	
	mail($email, $subject, $msg, $headers);
	//*************** email sending code ************//
	
    echo Message('Your account has been created successfully! Activation link has been sent to your email address. Kindly check your email and activate your account.');

	if($_POST['referer'] != ""){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `referrals` (`when`, `referrer`, `referred`) VALUES (?, ?, ?)');
		$result->execute(array($signuptime, $_POST['refer'], $username));
	}
    
	die();
  }
}
?>
<?
if (isset($message)) {
echo Message($message);
}

if(isset($_GET['referer']))
$refer = $_GET['referer'];
else
$refer='';
?>
 	<thead>
    <tr>
	<th>Register</th>
  	</tr>
  	</thead>
	<tr>
    <td>
  <table width='28%' border='0' align='center' cellpadding='0' cellspacing='0'>
<form name='register' method='post' action='register.php'>
    <tr>
      <td height='26'><font size='2' face='verdana'>Username</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='text' name='newname'>
        </font></td>
    </tr>
    <tr>
      <td height='28'><font size='2' face='verdana'>Password</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='password' name='newpass'>
        </font></td>
    </tr>
    <tr>
      <td height='28'><font size='2' face='verdana'>Confirm Password</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='password' name='newpassagain'>
        </font></td>
    </tr>
    <tr>
      <td height='26'><font size='2' face='verdana'>Email address</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" type='text' name='email'>
        </font></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><font size='2' face='verdana'>
      <input type='hidden' name='referer' value='<? echo $refer ?>'>
        <input type='submit' class='ui mini yellow button' name='submit' value='Register'>
        </font></td>
    </tr>
  </table>
  </form>
<br>
<center>
<br />&copy; Mobster Story 2018 | Designed By: <a href="http://instagram.com/twolucky.img">Two Lucky</a>
</center>
  </td></tr>
<?
include 'nlifooter.php';
?>