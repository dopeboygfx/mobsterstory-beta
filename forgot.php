<?php
include 'nliheader.php';
if ($_POST['submit']){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `email` = ?');
	$result->execute(array($_POST['email']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);


	if(!empty($worked)){
		$email_to = $worked['email'];
		$email_subject = "Your Account Info For GRPG";
		$email_body = "This message has been sent to you because you requested your GRPG account info. If you didn't do that, disregard this e-mail. \nUsername:".$worked['username']." Password:".$worked['password'];
		
		if(mail($email_to, $email_subject, $email_body)){
			echo Message("Your username and password have been sent.");
		} else {
			echo Message("Fail.");
		}
	} else {
		echo Message("An account with that e-mail does not exist.");
	}
}
?>
 	<thead>
    <tr>
	<th>Account Recovery</th>
  	</tr>
  	</thead>
	<tr>
    <td>
	  	<table class="inverted ui five unstackable column small compact table">
		<tr>
		<td style="font-size: 13px;"><p>Enter your e-mail address below, and your username and password will automatically be sent to your inbox. Don't forget to check your junk/bulk/			spam folder if it doesn't arrive in your inbox.
		<form method='post'>
		<input class="ui input focus" type="text" name="email"> </br>
		</br><input class="ui mini yellow button" type="submit" name="submit" value="Send Info">
		</form>

		</p>	
		</td>
<?
include 'nlifooter.php';
?>