<?
include 'header.php';
if($user_class->admin == 0){
echo "You are not authorized to be here...";
include 'footer.php';
die();
}

$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `id` DESC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){
	 if ($_POST['newmessage'] != ""){
		$to = $row['id'];
		$from = $user_class->id;
		$timesent = time();
		$subject = strip_tags($_POST['subject']);
		$msgtext = $_POST['msgtext'];

		$checkuser = $GLOBALS['pdo']->prepare('SELECT `id` FROM `grpgusers` WHERE `id` = ?');
		$checkuser->execute(array($to));

		$username_exist = !empty($checkuser->fetch(PDO::FETCH_NUM));
		if($username_exist){
			$result5 = $GLOBALS['pdo']->prepare('INSERT INTO `pms` (`to`, `from`, `timesent`, `subject`, `msgtext`) VALUES (?, ?, ?, ?, ?)');
			$result5->execute(array($to, $from, $timesent, $subject, $msgtext));

		  echo "Message successfully sent to $to";
		} else {
		  echo 'I am sorry but the Username you specified does not exist...';
		}

	}
}
?>
<thead>
	<tr><th>Mass Mail</th></tr>
</thead>
<tr><td>Here you can send a mass mail to every player in the game.</td></tr>
<thead>
	<tr><th>New Message</th></tr>
</thead>
<tr><td>
			<table width='100%'>
				<form method='post'>
				<tr>

					<td width='15%'>Subject:</td>
					<td width='85%'><input class="ui input focus" type='text' name='subject' size='70' maxlength='75' value="MASS MAIL"></td>
				</tr>
				<tr>
					<td width='15%'>Message:</td>
					<td width='85%' colspan='3'><textarea class="ui input focus" name='msgtext' cols='53' rows='7'></textarea></td>
				</tr>

				<tr>
					<td width='100%' colspan='4' align='center'><input class="ui mini green button"type='submit' name='newmessage' value='Send'></td>
				</tr>
				</form>
			</table>
</td></td>

<?

include 'footer.php';
?>
