<?php

include 'header.php';

if ($user_class->gang != 0) {
	$gang_class = New Gang($user_class->gang);

	if($_POST['deposit'] != ""){
		$amount = abs(intval($_POST['damount']));

		if ($amount > $user_class->points) {
			echo Message("You do not have that much points.");
		}

		if ($amount < 1){
			echo Message("Please enter a valid amount.");
		}

		if ($amount <= $user_class->points && $amount > 0) {
			echo Message("points deposited.");

			$newpointsg = $amount + $gang_class->points;
			$newpoints = $user_class->points - $amount;

			$user_class = new User($_SESSION['id']);
			$gang_class = New Gang($user_class->gang);

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `id`= ?');
			$result->execute(array($newpoints, $_SESSION['id']));

			$result = $GLOBALS['pdo']->prepare('UPDATE `gangs` SET `points` = ? WHERE `id` = ?');
			$result->execute(array($newpointsg, $gang_class->id));
		}
	}

	if($_POST['withdraw'] != "" && $gang_class->leader == $user_class->username){
		$amount = abs(intval($_POST['wamount']));
		if ($amount > $gang_class->points) {
			echo Message("You do not have that much points in the bank.");
		}

		if ($amount < 1){
			echo Message("Please enter a valid amount.");
		}

		if ($amount <= $gang_class->points && $amount > 0) {
			echo Message("points withdrawn.");

			$user_class = new User($_SESSION['id']);
			$gang_class = New Gang($user_class->gang);

			$newpointsg = $gang_class->points - $amount;
			$newpoints = $user_class->points + $amount;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `id` = ?');
			$result->execute(array($newpoints, $_SESSION['id']));

			$result = $GLOBALS['pdo']->preapre('UPDATE `gangs` SET `points` = ? WHERE `id` = ?');
			$result->execute(array($newpointsg, $gang_class->id));
		}
	}

	echo "
	<thead>
	<tr>
	<td>[".$gang_class->tag."]".$gang_class->name." points</td>
	</tr>
	<tr>
	<td>";
?>

 Welcome to the gang points. There is currently  $<? echo $gang_class->points ?> in the gang points.<br><br>

 			<?

			if ($gang_class->leader == $user_class->username){

			?>

			<form method='post'><input class="ui input focus" type='text' name='wamount' value='<? echo $gang_class->points ?>' size='10' maxlength='20'> &nbsp;

			<input class="ui mini yellow button" type='submit' name='withdraw' value='Withdraw'></form><br><br>

			<?

			}

			?>

			<form method='post'><input class="ui input focus" type='text' name='damount' value='<? echo $user_class->points ?>' size='10' maxlength='20'> &nbsp;

			<input class="ui mini green button" type='submit' name='deposit' value='Deposit'></form>

	  </td></tr>

<?

	echo "<td><tr>";

} else {

	echo Message("You aren't in a gang.");

}

include 'footer.php';

?>
