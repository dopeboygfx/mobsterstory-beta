<?php

include 'header.php';

if($_GET['action']=="quit"){
	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `job` = "0" WHERE `id` = ?');
	$result->execute(array($_SESSION['id']));
	$user_class = new User($_SESSION['id']);
}

if($_GET['take'] != ''){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `jobs` WHERE `id` = ?');
	$result->execute(array($_GET['take']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	if(($worked['level'] > $user_class->level)||($worked['strength'] > $user_class->strength)||($worked['defense'] > $user_class->defense)||($worked['speed'] > $user_class->speed)) {
		$error = "You don't have the needed skills or level to take this job.<br>";
	}

	if ($worked['name'] == ""){
		$error = "That's not a real job.";
	}

	if($error == ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `job` = ? WHERE `id` = ?');
		$result->execute(array($_GET['take'], $_SESSION['id']));
		$user_class = new User($_SESSION['id']);
	}else{
		echo Message($error);
	}
}

if($user_class->job != 0){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `jobs` WHERE `id` = ? ORDER BY `money`');
	$result->execute(array($user_class->job));
	$worked = $result->fetch(PDO::FETCH_ASSOC);
	echo "
	<thead>
	<tr><th>Current Job</th></tr>
	</thead>
	<tr><td>You are currently a ".$worked['name']."<br>You make $".$worked['money']." a day.<br><br><a class='ui mini red button' href='jobs.php?action=quit'>Quit Job</a></td></tr>";
}
?>

<thead>
	 <tr><th>Job Centre</th></tr>
</thead>
<tr><td>
<table width='100%'>
		<tr>
			<td>Job</td>
			<td>Requirements</td>
			<td>Daily Payment</td>
			<td>Apply For Job</td>
		</tr>
<?php

$result = $GLOBALS['pdo']->query('SELECT * FROM `jobs` ORDER BY `money` ASC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
			echo "
			<tr>
				<td width='25%'>".$line['name']."</td>

				<td width='35%'>
					Strength: ".$line['strength']."<br>
					Defense:  ".$line['defense']."<br>
					Speed:  ".$line['speed']."<br>
					Level:  ".$line['level']."<br>
				</td>
				<td width='20%'>$".$line['money']."</td>
				<td>
			";

				if($line['id'] > $user_class->job){
					echo "<a class='ui mini blue button' href='jobs.php?take=".$line['id']."'>Take Job</a>";
				}

			echo "</td></tr>";
	}

?>

		</table></div>
</td></tr>

<?

include 'footer.php';

?>
