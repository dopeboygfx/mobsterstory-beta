<?
include 'nliheader.php';

if(isset($_POST['submit'])){
  $password = sha1($_POST["password"]);

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `login_name` = ?');
	if(!$result->execute(array($_POST['username']))){
		die("Login Name and password not found or not matched");
	}

	$worked = $result->fetch(PDO::FETCH_ASSOC);
  if($worked['verified'] == 0)
  {
  	echo Message('Sorry, your account is not activate. Kindly first activate account and then login.');
  }
  else
  {
  	$user_class = new User($worked['id']);

  	if($worked['password'] == $password){
		$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `ip5` = `ip4`, `ip4` = `ip3`, `ip2` = `ip1`, `ip1` = ? WHERE `id` = ?");
		$result->execute(array($_SERVER['REMOTE_ADDR'],$worked['id']));
		if($user_class->rmdays > 0){
			echo '<meta http-equiv="refresh" content="0;url=index.php">';
		} else {
	   ?>
	<thead>
	<tr>
	<th>Mobster Story</th>
	</tr>
	</thead>
	   <tr><td>
		   <center>
		   <script type="text/javascript"><!--
		   google_ad_client = "pub-0905156377500300";
		   google_ad_width = 336;
		   google_ad_height = 280;
		   google_ad_format = "336x280_as";
		   google_ad_type = "image";
		   //2007-04-06: grpg
		   google_ad_channel = "8497905351";
		   //-->
		   </script>
		   <script type="text/javascript"
			 src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		   </script>
		   <br>
		   <a class='ui mini yellow button'href="index.php">Continue</a>
		   <br>
		   <br>
		   For ad placement, please contact us soon! </center>
		   </td></tr>
	   <?
	   }
			$_SESSION["id"] = $worked['id'];
		die();
	} else {
		echo Message('Sorry, your username and password combination are invalid.');
	}
  }
}
?>
<thead>
<tr>
<th>Login</th>
</tr>
</thead>
</td></tr>
<tr><td>	
<form name='login' method='post' action='login.php'>
  <table width='25%' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr>
      <td width='35%' height='27'><font size='2' face='verdana'>Login Name&nbsp;</font></td>
      <td width='65%'><font size='2' face='verdana'>
        <input class="ui input focus" name='username' type='text' size='22'>
        </font></td>
    </tr>
    <tr>
      <td height='24'><font size='2' face='verdana'>Password&nbsp;</font></td>
      <td><font size='2' face='verdana'>
        <input class="ui input focus" name='password' type='password' size='22'>

        </font></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><font size='2' face='verdana'>
        <input type='submit' name='submit' class='ui mini yellow button' value='Login'>
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