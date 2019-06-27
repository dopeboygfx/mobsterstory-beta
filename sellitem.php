<?

include 'header.php';

if ($_GET['id'] == ""){
	echo Message("No item picked.");
	include 'footer.php';
	die();
}

$howmany = Check_Item($_GET['id'], $user_class->id);//check how many they have

$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
$result2->execute(array($_GET['id']));
$worked = $result2->fetch(PDO::FETCH_ASSOC);

$price = $worked['cost'] * .60;

if ($_GET['confirm'] == "true"){ //if they confirm they want to sell it
	$error = ($howmany == 0) ? "You don't have any of those." : $error;

	if (isset($error)){
		echo Message($error);
		include 'footer.php';
		die();
	}

	$newmoney = $user_class->money + $price;

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
	$result->execute(array($newmoney, $_SESSION['id']));

	Take_Item($_GET['id'], $user_class->id);
	echo Message("You have sold a ".$worked['itemname']." for $".$price.".");

	include 'footer.php';
	die();
}

?>

<thead>
<tr>
<th>Sell Items</th>
</tr>
</thead>

<tr><td>
<?= "Are you sure that you want to sell ".$worked['itemname']." for $".$price."?<br><br><a class='ui mini green button' href='sellitem.php?id=".$_GET['id']."&confirm=true'>Yes</a>"; ?>
</td></tr>

<?

include 'footer.php';

?>
