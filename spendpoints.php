<?php
include 'header.php';
if ($_GET['spend'] == "energy"){
        if($user_class->energy == $user_class->maxenergy) {
            echo Message('You already have max Energy.');
            include(DIRNAME(__FILE__).'/footer.php');
            exit;
        }
	if($user_class->points > 9){
		$newpoints = $user_class->points - 10;
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `energy` = ?, `points` = ? WHERE `id` = ?');
		$result->execute(array($user_class->maxenergy, $newpoints, $_SESSION['id']));
		echo Message("You spent 10 points and refilled your energy.");
	}else{
		echo Message("You don't have enough points, you fool!.");
	}
}

if ($_GET['spend'] == "nerve"){
      if($user_class->nerve == $user_class->maxnerve) {
            echo Message('You already have max Nerve.');
            include(DIRNAME(__FILE__).'/footer.php');
            exit;
        }
	if($user_class->points > 9) {
		$newpoints = $user_class->points - 10;
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `nerve` = ?, `points` = ? WHERE `id` = ?');
		$result->execute(array($user_class->maxnerve, $newpoints, $_SESSION['id']));
		echo Message("You spent 10 points and refilled your nerve.");
	} else {
		echo Message("You don't have enough points, silly buns.");
	}
}

if ($_GET['spend'] == "searchdowntown"){
      if($user_class->searchdowntown ==100) {
            echo Message('You already have max search.');
            include(DIRNAME(__FILE__).'/footer.php');
            exit;
        }
	if($user_class->points > 9) {
		$newpoints = $user_class->points - 10;
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `searchdowntown` = 100, `points` = ? WHERE `id` = ?');
		$result->execute(array($newpoints, $_SESSION['id']));
		echo Message("You spent 10 points and refilled your search downtown.");
	} else {
		echo Message("You don't have enough points, silly buns.");
	}
}
?>
<thead>
<tr>
<th>Point Shop</th>
</tr>
</thead>
<tr><td>
Welcome to the Point Shop, here you can spend your points on various things.</td></tr>
<tr><td>
<table>
		<tr>
			<td><a href='spendpoints.php?spend=energy'>Refill Energy</a></td>
			<td> - 10 Points</td>

		</tr>
		<tr>
			<td><a href='spendpoints.php?spend=nerve'>Refill Nerve</a></td>
			<td> - 10 Points</td>
		</tr>
				<tr>
			<td><a href='spendpoints.php?spend=searchdowntown'>Search Downtown reset</a></td>
			<td> - 10 Points</td>
		</tr>
</table>
</td></tr>
<?php
include 'footer.php';
?>
