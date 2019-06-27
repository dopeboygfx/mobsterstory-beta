<?php
include 'header.php';
$gang_class = New Gang($user_class->gang);
if($_GET['buy'] != ''){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
	$result->execute(array($_GET['buy']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

    $cost = $worked['price_cash'];
	$costp = $worked['price_points'];
	if($gang_class->house != 0){
		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
		$result2->execute(array($gang_class->house));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		$cost = $cost - ($worked2['price_cash'] * .75);
		$costp = $costp - ($worked2['price_points'] * .75);
		echo Message('You have sold your gang house for 75% of its value. that will go towards the new house.');
	}

	if($cost > $gang_class->vault && $costp > $gang_class->points){
		echo Message("You don't have enough to buy that house.");
	}

	if($cost <= $gang_class->vault && $costp <= $gang_class->points && $worked['name'] != ""){
		$newmoney = $gang_class->vault - $cost;
		$newpoints = $gang_class->points - $costp;

		$result = $GLOBALS['pdo']->prepare('UPDATE `gangs` SET `house` = ?, `vault` = ?, `points` = ? WHERE `id` = ?');
		$result->execute(array($_GET['buy'], $newmoney, $newpoints, $gang_class->id));

		echo Message("You have purchased and moved into ".$worked['name'].".");
		$user_class = new User($_SESSION['id']);
	}

	if($worked['name'] == ""){
		echo Message("That's not a real house.");
	}
}

if($_GET['action'] == "sell") {
	if($gang_class->house != 0){
		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
		$result2->execute(array($gang_class->house));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		$cost = $cost - ($worked2['price_cash'] * .75);
		$costp = $costp - ($worked2['price_points'] * .75);
		echo Message('You have sold your gang house for 75% of its value. t');
	}

	$newmoney = $gang_class->vault + $cost;
	$newpoints = $gang_class->points + $costp;

	$result = $GLOBALS['pdo']->prepare('UPDATE `gangs` SET `house` = 0, `vault` = ?, `points` = ? WHERE `id` = ?');
	$result->execute(array($newmoney, $newpoints, $gang_class->id));
}
?>
<tr><td class="contenthead">Gang House Upgrades</td></tr>
<?
if($gang_class->house > 0){
	echo "
		<thead>
		<tr>
		<td><a href='ganghouse.php?action=sell'>Sell Your gang House</a></td>
		</tr>
		</head>";
}
?>
<tr><td>
<table>
	<tr>
		<td width='20%'><b>Name</b></td>
		<td width='10%'><b>Awake</b></td>
		<td width='15%'><b>Cost Cash</b></td>
		<td width='15%'><b>Cost Points</b></td>
		<td width='15%'><b>Storage</b></td>
		<td width='15%'><b>Move</b></td>
	</tr>

<?php
$result = $GLOBALS['pdo']->query('SELECT * FROM `ganghouse` ORDER BY `id` ASC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	echo "<tr><td>".$line['name']."</td><td>".$line['bonus_awake']."</td><td>\$".$line['price_cash']."</td><td>";
	echo $line['price_points'];
	echo "</td> <td>";
	echo $line['storage'];
	echo "</td> <td>";

	if($line['id'] > $gang_class->house){
		echo "<a class='ui mini blue button' href='ganghouse.php?buy=".$line['id']."'>Move In</a>";
	}

	echo "</td></tr>";
}
?>
</table>
</td></tr>
<?php
include 'footer.php';
?>
