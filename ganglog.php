<?
include 'header.php';

if ($user_class->gang != 0) {
	$gang_class = New Gang($user_class->gang);
	echo "
	<thead>
	<tr>
	<th>[".$gang_class->tag."]".$gang_class->name." Defense Log</th>
	</tr>
	</thead>
	<td>";

	$result = $GLOBALS['pdo']->prepare('SELECT * from `ganglog` WHERE `gangid` = ? ORDER BY `timestamp` DESC');
	$result->execute(array($gang_class->id));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $line){
		$attacker = new User($row['attacker']);
		$defender = new User($row['defender']);
		$winner = new User($row['winner']);
		$time = date(F." ".d.", ".Y." ".g.":".i.":".sa,$row['timestamp']);
		echo $attacker->formattedname." attacked ".$defender->formattedname." and ".$winner->formattedname." won - ".$time."<br>";
		}
		echo "";
		} else {
		echo Message("You aren't in a gang.");
		}
		include 'footer.php';
?>
