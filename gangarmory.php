<?php
include 'header.php';

if($user_class->gang != 0){
	$gang_class = New Gang($user_class->gang);

	if($_GET['buy']){
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `gangarmory` WHERE `id` = ?');
		$result->execute(array($_GET['buy']));
		$worked = $result->fetch(PDO::FETCH_ASSOC);

		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
		$result2->execute(array($worked['itemid']));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);


		$user_item = new User($worked['userid']);

		if ($gang_class->leader != $user_class->username){
			echo Message("You are not the leader of this gang.");
		} else {
			echo Message("You have taken a ".$worked2['itemname'].".");

			$result = $GLOBALS['pdo']->prepare('DELETE FROM `gangarmory` WHERE `id` = ? LIMIT 1');
			$result->execute(array($_GET['buy']));

			Give_Item($worked2['id'], $user_class->id);//give them the item out of the armory
		}
	}


	echo "
	<thead>
		<tr>
			<th>[".$gang_class->tag."]".$gang_class->name." Vault</th>
		</tr>
	</thead>
	<tr>
	<td>Please note that only the gang leader can take items out of the gang armory.</td>
	</tr>
	<tr>
	<thead>
		<tr>
			<th>Vaulted Items</th>
		</tr>
	</thead>
	</tr>
	<tr>
	<td>";

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `gangarmory` WHERE gangid = ? ORDER BY `id` ASC');
	$result->execute(array($user_class->gang));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $line){
		$user_item = new User($line['userid']);

		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id`= ?');
		$result2->execute(array($line['itemid']));
		$worked = $result2->fetch(PDO::FETCH_ASSOC);

		if ($gang_class->leader == $user_class->username){
			$submittext = "<a href='gangarmory.php?buy=".$line['id']."'>Take</a>";
		}
		echo $submittext." ".$worked['itemname']."<br>";
	}
	?>
</td></tr>
<thead>
	<tr>
		<th>Add Item's to Vault</th>
	</tr>
</thead>
<tr><td>
	<?

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? ORDER BY `userid` DESC');
	$result->execute(array($user_class->id));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $line){
		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ? ORDER BY `id` ASC');
		$result2->execute(array($line['itemid']));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		echo $worked2['itemname']." [".$line['quantity']."] <a href='addtoarmory.php?id=".$worked2['id']."'>[Add]</a><br>";
	}
	?>

	<?
	echo "";
} else {
	echo "<td><tr>";
	echo Message("You aren't in a gang.");
}
include 'footer.php';
?>
