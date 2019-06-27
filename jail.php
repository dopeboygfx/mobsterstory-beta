<?
include 'header.php';

$jailbreak = $_GET['jailbreak'];
if ($jailbreak != ""){

$jailed_person = new User($jailbreak);
if ($jailed_person->formattedname == ""){
	echo Message("That person does not exist.");
	include 'footer.php';
	die();
}
	if($jailed_person->id == $user_class->id) {
			echo Message('You cant bust yourself from prison.');
			include(DIRNAME(__FILE__).'/footer.php');
			exit;
	}
if ($jailed_person->jail == "0"){
	echo Message("That person is not in jail.");
	include 'footer.php';
	die();
}
	$chance = rand(1,(100 * $crime - ($user_class->speed / 25)));
	$money = rand($jailed_person->level * 10, 500);
	$exp = rand($jailed_person->level * 10, 500);
	$nerve = 10;
	if ($user_class->nerve >= $nerve) {
		if($chance <= 75) {
			echo Message("Success! You receive ".$exp." exp and $".$money);
			$exp = $exp + $user_class->exp;
			$crimesucceeded = 1 + $user_class->crimesucceeded;
			$crimemoney = $money + $user_class->crimemoney;
			$money = $money + $user_class->money;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `exp` = ?, busts = bust +1,`crimesucceeded` = ?, `crimemoney` = ?, `money` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($exp, $crimesucceeded, $crimemoney, $money, $nerve, $_SESSION['id']));

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `jail` = "0" WHERE `id` = ?');
			$result->execute(array($jailed_person->id));

			//send even to that person
			Send_Event($jailed_person->id, "You have been busted out of jail by ".$user_class->username);
		}elseif ($chance >= 150) {
			echo Message("You were caught. You were hauled off to jail for " . 200 . " minutes.");
			$crimefailed = 1 + $user_class->crimefailed;
			$jail = 10800;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `crimefailed` = ?, `jail` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($craimefailed, $jail, $nerve, $_SESSION['id']));
		}else{
			echo Message("You failed.");
			$crimefailed = 1 + $user_class->crimefailed;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `crimefailed` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($crimefailed, $nerve, $_SESSION['id']));
		}
	} else {
		echo Message("You don't have enough nerve for that crime.");
	}
	include 'footer.php';
	die();
}
?>
	 <thead>
	    <tr>
	    <th>Jail</th>
	    <th></th>
	    <th></th>
	    </tr>
	    </thead>
		<tr>
		<td>Mobster</td>
		<td>Time Left</td>
		<td>Actions [Requires 10 nerve]</td>
		</tr>
			<?

		$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `jail` DESC');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $line){
			$secondsago = time()-$line['lastactive'];
			$user_jail = new User($line['id']);
			if(floor($user_jail->jail / 60) != 1){
				$plural = "s";
			}
			if($user_jail->jail != 0){
				echo "<tr><td>".$user_jail->formattedname."</td><td>".floor($user_jail->jail / 60)." minute".$plural."</td><td><a class'ui yellow button' href = 'jail.php?jailbreak=".$user_jail->id."'>Break Out</a></td></tr>";
			}
		}
	?>
</table>
<?
include 'footer.php';
?>
