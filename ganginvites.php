<?
include 'header.php';

if ($_GET['accept'] != ""){
		$gang_class = new Gang($_GET['accept']);

		$checkuser = $GLOBALS['pdo']->prepare('SELECT `username` FROM `ganginvites` WHERE `username` = ? AND `gangid` = ?');
		$checkuser->execute(array($user_class->username, $_GET['accept']));
		$username_exist = !empty($checkuser->fetch(PDO::FETCH_NUM));

		if($username_exist){
			$result = $GLOBALS['pdo']->prepare('DELETE FROM `ganginvites` WHERE `username` = ?');
			$result->execute(array($user_class->username));

			$newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `gang` = ? WHERE `id` = ?');
			$newsql->execute(array($gang_class->id, $user_class->id));

			echo Message("You have joined.");
		}
}

if ($_GET['delete'] != ""){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `ganginvites` WHERE `username` = ? AND `gangid` = ?');
	$result->execute(array($user_class->username, $_GET['delete']));
	echo Message("You have declined that offer.");
}
?>
<thead>
<tr><th>Gang Invites</th></tr>
</thead>
<?

$result = $GLOBALS['pdo']->prepare('SELECT * FROM `ganginvites` WHERE `username` = ?');
$result->execute(array($user_class->username));
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	$invite_class = New Gang($line['gangid']);
	echo "<tr><td>".$invite_class->formattedname." - <a href='ganginvites.php?accept=".$invite_class->id."'>Accept</a>- <a href='ganginvites.php?delete=".$invite_class->id."'>Decline</a></td></tr>";
}

include 'footer.php';
?>
