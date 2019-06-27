<?php
include 'header.php';

if ($_POST['takebet'] != ""){
	$results = $GLOBALS['pdo']->prepare('SELECT * FROM `5050game` WHERE `id`= ?');
	$results->execute(array($_POST['bet_id']));
	$worked = $results->fetch(PDO::FETCH_ASSOC);

	if($worked['owner'] == $user_class->id){
		echo Message("You can't take your own bet.");
		include 'footer.php';
		die();
	}

    $amount = abs(intval($worked['amount']));
 	$user_points = new User($worked['owner']);
	if ($amount > $user_class->money){
		echo Message("You don't have enough money to match their bet.");
	}

	if($amount <= $user_class->money){
		$newmoney = $user_class->money - $amount;

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
		$result->execute(array($newmoney, $user_class->id));


		$user_class = new User($_SESSION['id']);

		$winner = rand(0,1);
		if($winner == 0){ //original poster wins
			echo Message("You have lost.");
			$amount = $amount * 2;
			$newmoney = $user_points->money + $amount;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
			$result->execute(array($newmoney, $user_points->id));

			Send_Event($user_points->id, "You won the $amount dollar bid you placed.");
		} else { //the person who accepted the bid won
			echo "You have won!";
			$amount = $amount * 2;
			$newmoney = $user_class->money + $amount;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
			$result->execute(array($newmoney, $user_class->id));

			Send_Event($user_points->id, "You lost the $amount dollar bid you placed.");
		}

		$result = $GLOBALS['pdo']->prepare("DELETE FROM `5050game` WHERE `id` = ?");
		$result->execute(array($worked['id']));

	}
}

if ($_POST['makebet']){
    $_POST['amount'] = abs(intval($_POST['amount']));
 	if($_POST['amount'] > $user_class->money){
		echo Message("You don't have that much money.");
	}
	if($_POST['amount'] < 1000){
		echo Message("Please enter a valid amount of money.");
	}
	if($_POST['amount'] >= 1000 && $_POST['amount'] <= $user_class->money){
		echo Message("You have added $".$_POST['amount']);

		$result = $GLOBALS['pdo']->prepare("INSERT INTO `5050game` (owner, amount) VALUES (?, ?)");
		$result->execute(array($user_class->id, $_POST['amount']));

		$newmoney = $user_class->money - $_POST['amount'];

		$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?");
		$result->execute(array($newmoney, $user_class->id));

		$user_class = new User($_SESSION['

		']);
	}
}

?>
	 <thead>
	    <tr>
		<th>50/50 Chance Game</th>
  		</tr>
  		</thead>
<tr><td>
This game is simple. 2 people bet the same amount of money, then a winner is randomly picked. The winner recieves all of the money!
</td></tr>
<tr><td>
<form method='post'>
Amount of money to bid. $<input class="ui input focus" type='text' name='amount' size='10' maxlength='20' value='<? echo $user_class->money ?>'> (minimum of $1000 bet)
<br>
<br>
<input type='submit' name='makebet' class='ui blue mini button' value='Make Bet'></form>
<?php

$results = $GLOBALS['pdo']->query("SELECT * FROM `5050game` ORDER BY `amount` DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $line){
	$user_points = new User($line['owner']);
	echo "<form method='post'>";
	echo "<br>".$user_points->formattedname." - $".$line['amount']."<input type='hidden' name='bet_id' value='".$line['id']."'> <input class='ui mini yellow button' type='submit' name='takebet' value='Take Bet'></form>";
}
?>
</td></tr>
<?php
include 'footer.php';
?>
