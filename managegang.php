<?
include 'header.php';
$gang_class = new Gang($user_class->gang);

if ($gang_class->leader != $user_class->username){
	echo Message("You do not have authorization to be here.");
	include 'footer.php';
	die();
}

if($_GET['dismiss'] != ""){
	if($_GET['dismiss'] != $user_class->id){
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `gang` = "0" WHERE `id` = ?');
		$result->execute(array($_GET['dismiss']));
		echo Message("You have dismissed that user.");
	} else {
		echo Message("You can't kick yourself out of our own gang.");
	}
}
?>
<thead>
  <tr>
    <th><? echo "[". $gang_class->tag . "]" . $gang_class->name; ?></th>
  </tr>
</thead>

<tr><td>
<table width='100%'>
			<tr>
				<td>Mobster</td>
				<td>Action</td>
			</tr>
		<?php
			$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `gang` = ? ORDER BY `exp` DESC');
			$result->execute(array($user_class->gang));
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $line){
				$gang_member = new User($line['id']);
				?>
				<tr>
				<td><?= $gang_member->formattedname; ?></td>
				<td><a class="ui mini red button" href='managegang.php?id=<?= $gang_class->id."&dismiss=".$gang_member->id; ?>'>Kick Out</a></td>
<?
			}
include 'footer.php';
?>
