<?
include 'header.php';

$gang_class = new Gang($user_class->gang);

if ($gang_class->leader != $user_class->username){
	echo Message("You do not have authorization to be here.");
	include 'footer.php';
	die();
}

if ($_POST['invite'] != ""){
	$to = $_POST['username'];
	$gang = $user_class->gang;

	$checkuser = $GLOBALS['pdo']->prepare('SELECT `username` FROM `grpgusers` WHERE `username` = ?');
	$checkuser->execute(array($to));
	$username_exist = !empty($checkuser->fetch(PDO::FETCH_NUM));

	$checkuser2 = $GLOBALS['pdo']->prepare('SELECT `username` FROM `ganginvites` WHERE `username` = ? AND `gangid` = ?');
	$checkuser2->execute(array($to, $gang_class->id));
	$username_exist2 = !empty($checkuser2->fetch(PDO::FETCH_NUM));

	if ($username_exist2 != 0){
		echo Message('That user has already been invited.');
	}

	if($username_exist > 0 && $username_exist2 == 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `ganginvites` (`username`, `gangid`) VALUES (?, ?)');
		$result->execute(array($to, $gang));
		echo Message("$to has been invited.");
	}

	if ($username_exist == 0){
		echo Message('You entered a non-existant username.');
	}

}
?>
<thead>
	<tr>
		<th>Invite User To <?= $gang_class->name; ?></th>
	</tr>
</thead>
<form method="post">
	<tr><td>Invite User: <input class="ui input focus" type='text' name='username' size='15'>
		<br>
		<br>
		<input class="ui mini blue button" type='submit' name='invite' value='Invite'></td>
	</tr>
</form>
<?
include 'footer.php';
?>
