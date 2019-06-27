<?

include 'header.php';

//Update the user logged in
$update  = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `firstlogin` = 0");
$update->execute();
//Check Mail Banned
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type`='mail' AND `id` = '".$user_class->id."'");  
$result->execute();
$check = $result->fetch(PDO::FETCH_ASSOC);
if ($check > 0) {
echo Message('&nbsp;You have been mail banned for '.prettynum($check['days']).' days.');
include 'footer.php';
die();
}
//End Check

if($_GET['delete'] != ""){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `pms` WHERE `id` = ?');
	$result->execute(array($_GET['delete']));
	echo Message("Message Deleted!");
}

if($_GET['deleteall'] == "true"){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `pms` WHERE `to` = ?');
	$result->execute(array($user_class->id));
	echo Message("Message Deleted!");
}

if($_POST['newmessage'] != ""){
	$to = abs(intval($_POST['to']));
	$from = $user_class->id;
	$timesent = time();
	$subject = strip_tags($_POST['subject']);
	$msgtext = strip_tags($_POST['msgtext']);

	$checkuser = $GLOBALS['pdo']->prepare('SELECT `username` FROM `grpgusers` WHERE `id` = ?');
	$checkuser->execute(array($to));
	$username_exist = count($checkuser->fetch(PDO::FETCH_NUM));

	if($username_exist > 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `pms` (`to`, `from`, `timesent`, `subject`, `msgtext`) VALUES (?, ?, ?, ?, ?)');
		$result->execute(array($to, $from, $timesent, $subject, $msgtext));
		echo Message("Message successfully sent to $to");
	}else{
		echo Message('I am sorry but the Username you specified does not exist...');
	}
}

?>
<thead>
	<tr>
		<th>Mailbox</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
</thead>
<tr>
	<td>Time Recieved</td>
	<td>Subject</td>
	<td>From</td>
	<td>Viewed</td>
	<td>Delete</td>
</tr>

<?
$result = $GLOBALS['pdo']->query('SELECT * from `pms` ORDER BY `timesent` DESC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){
	if($row['to'] == $user_class->id){
		$from_user_class = new User($row['from']);
		$subject = ($row['subject'] == "") ? "No Subject" : $row['subject'];

		if($row['viewed']=="1"){
			$viewed="No";
		}else{
			$viewed="Yes";
		}

		echo "

		<tr>

		<td>".date(F." ".d.", ".Y." ".g.":".i.":".sa,$row['timesent'])."</td>
		<td><a href='viewpm.php?id=".$row['id']."'>".$subject."</a></td>
		<td>".$from_user_class->formattedname."</td>
		<td>".$viewed."</td>
		<td><a href='pms.php?delete=".$row['id']."'>Delete</a></td>
		</tr>

		";

	}
}

if($_GET['reply'] != ""){
	$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `pms` WHERE `id` = ?');
	$result2->execute(array($_GET['id']));
	$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

	$Topeople = $worked2['from'];
	$from_user_class = new User($worked2['from']);
}

if($_GET['to'] != ""){
	$Topeople = $_GET['to'];
}

?>

<center><a href='pms.php?deleteall=true'>Delete All PMs In Your Inbox</a></center>

</table>

<table class="inverted ui five unstackable column small compact table">
	<thead>
		<tr>
			<th>New Messages</th>
			<th></th>
		</tr>
	</thead>
	<form method='post'>
		<tr>
			<td width='15%'>Send To:</td>
			<td width='85%'><input class="ui input focus" type='text' name='to' value='<?php echo $Topeople; ?>' size='10' maxlength='75'> [user ID]
			</tr>
			<tr>
				<td width='15%'>Subject:</td>
				<td width='85%'><input class="ui input focus" type='text' name='subject' size='70' maxlength='75' value='<? echo ($_GET['reply'] != "") ? "Re: ".$worked2['subject'] : "";  ?>'></td>
			</tr>
			<tr>
				<td width='15%'>Message:</td>
				<td width='85%' colspan='3'><textarea class="ui input" name='msgtext' cols='53' rows='7'><? echo ($_GET['reply'] != "") ? " \n -------- \n ".$worked2['msgtext'] : "";  ?></textarea></td>
			</tr>
			<tr>
				<td width='100%' colspan='4' align='center'><input type='submit' class='ui mini blue button' name='newmessage' value='Send'></td>

			</tr>

		</form>

	</table>

</td></tr>

<?



include 'footer.php';

?>
