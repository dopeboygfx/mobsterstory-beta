<?
include 'header.php';

if ($_GET['pull'] == "lever"){
	if ($user_class->money < 100){
		echo Message("You don't have enough money to play slots.");
	} else {
		$newmoney = $user_class->money - 100;
		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
		$result->execute(array($newmoney, $user_class->id));

		$user_class = new User($_SESSION['id']);

		$slot[1] = "<img src='images/7.png'>";
		$slot[2] = "<img src='images/bar.png'>";
		$slot[3] = "<img src='images/cherries.png'>";

		$slot1 = rand(1,3);
		$slot2 = rand(1,3);
		$slot3 = rand(1,3);

		echo '<tr><td class="contenthead">Spin Results</td></tr><tr><td class="contentcontent" align="center">';
		echo $slot[$slot1];
		echo $slot[$slot2];
		echo $slot[$slot3];
		echo "</td></tr>";

		if($slot1 == $slot2 && $slot2 == $slot3){
			$newmoney = $user_class->money + 1000;
			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
			$result->execute(array($newmoney, $user_class->id));

			$user_class = new User($_SESSION['id']);
			echo Message("Congratulations, you have won $900!");
		} else {
			echo Message("You didn't win anything, sorry.");
		}
	}
}
?>
<thead>
<tr>
<th>Slot Machines</th>
</tr>
</thead>
<tr><td>
So, you fancy a try at the slot machine? Well, it just $100 a pull, so have at it.
<br><br>
<a class="ui mini yellow button" href="slots.php?pull=lever"><font color='white'>Pull Lever</font></a>
</br>
</td></tr>
<?
include 'footer.php';
?>
