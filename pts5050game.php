<?php
/* 12-18-2013 - G7470 Programming - Updated to make the winning ratios equal. */
include 'header.php';


if (isset($_POST['takebet'])){



	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `pts5050game` WHERE `id`=? ");
	$result->execute(array($_POST['bet_id']));
    $worked = $result->fetch(PDO::FETCH_ASSOC);
	if ($worked['owner'] == $user_class->id) {
		echo Message("You can't take your own bet.");
		include 'footer.php';
		die();
	}
    $orgamount = $worked['amount'];

    $amount = $worked['amount'];

 	$user_points = new User($worked['owner']);



	if ($amount > $user_class->points){

		echo Message("You don't have enough points to match their bet.");

	}

	if($amount <= $user_class->points){

		$newmoney = $user_class->points - $amount;

		$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = '".$newmoney."' WHERE `id`='".$user_class->id."'");
		$result->execute();
		$winner = rand(0,1);

		if($winner == 0){ //original poster wins

			echo Message("You have lost the bet.");

			$amount = round($orgamount * 1.90);

			$newmoney = $user_points->points + $amount;

			$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = '".$newmoney."' WHERE `id`='".$user_points->id."'");
            $result->execute();
		
			$result = $GLOBALS['pdo']->prepare("INSERT INTO `pts5050log` (`betterip`, `matcherip`, `winner`, `better`, `matcher`, `amount`, `timestamp`)"."VALUES ('".$user_points->ip."', '".$user_class->ip."', '".$user_points->id."', '".$user_points->id."', '".$user_class->id."', '".$amount."', '".time()."')");
			$result->execute();
		    
			

			Send_Event($user_points->id, "You won the ".prettynum($orgamount)." points bet you placed!");

		} else { //the person who accepted the bid won

			$amount = round($orgamount * 1.90);

			echo Message("You have won the bet and gained " . prettynum($amount,1) . " points!");

			$newmoney = $user_class->points + $amount;

			$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = '".$newmoney."' WHERE `id`='".$user_class->id."'");
		    $result->execute();
		
			

			$result = $GLOBALS['pdo']->prepare("INSERT INTO `pts5050log` (`betterip`, `matcherip`, `winner`, `better`, `matcher`, `amount`, `timestamp`)"."VALUES ('".$user_points->ip."', '".$user_class->ip."', '".$user_points->id."', '".$user_points->id."', '".$user_class->id."', '".$amount."', '".time()."')");
		    $result->execute();
			Send_Event($user_points->id, "You lost the ".prettynum($orgamount)." points bet you placed!");

		}

		$result = $GLOBALS['pdo']->prepare("DELETE FROM `pts5050game` WHERE `id`='".$worked['id']."'");
	    $result->execute();
	}
}
if ($_POST['makebet']){
	//Validate Points
	$notallowed = array('$', '-', '_', '+', '=', '<', '>');

	$_POST['amount'] = str_replace($notallowed, "", $_POST['amount']);

	//End



 	if($_POST['amount'] > $user_class->points){

		echo Message("You don't have that many points.");

	}

	if($_POST['amount'] < 10){

		echo Message("You have to bet at least 10 points.");

	}

	if($_POST['amount'] > 1000){

		echo Message("The maximum you can bet is 1,000 points.");

	}

	if($_POST['amount'] >= 10 && $_POST['amount'] <= $user_class->points && $_POST['amount'] <= 1000){

		echo Message("You have added a ".prettynum($_POST['amount'])." points bet.");

		$result = $GLOBALS['pdo']->prepare("INSERT INTO `pts5050game` (`owner`, `amount`)"."VALUES ('".$user_class->id."', ?)");
	    $result->execute(array($_POST['amount']));
		
		$newmoney = $user_class->points - $_POST['amount'];

		$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = '".$newmoney."' WHERE `id`='".$user_class->id."'");
	    $result->execute();
	}

}



?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Points 50/50 Chance Game</td></tr>

<tr><td class="contentcontent">

This game is simple. 2 people bet the same amount of points, then a winner is randomly picked. The winner recieves 90% of the points and we take the rest!

<br /><br /><form method='post'>

Amount of points to bid:&nbsp;
<input type='text' onKeyPress="return numbersonly(this, event)" name='amount' size='10' maxlength='20' /> points (Min: 10 points&nbsp;&nbsp;Max: 1,000 points)<br>

<input type='submit' name='makebet' value='Make Bet'></form>

</td></tr>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Current Bets</td></tr>

<tr><td class="contentcontent">

<table width='100%'>

<tr>

	<td><b>Better</b></td>

	<td><b>Amount</b></td>

	<td><b>Bet</b></td>

</tr>

<?php

$result = $GLOBALS['pdo']->prepare("SELECT * FROM `pts5050game` ORDER BY `amount` DESC");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
	$user_points = new User($line['owner']);

	echo "<form method='post'>";

	echo "<tr><td>".$user_points->formattedname."</td><td>".prettynum($line['amount'])."</td><input type='hidden' name='bet_id' value='".$line['id']."' /><td><input type='submit' name='takebet' value='Take Bet' /></form></td></tr>";

}

?>

</td></tr>

<?php

include 'footer.php';

?>