<?php
include 'header.php';

if ($_POST['sellstocks']){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `stocks` WHERE `id` = ?');
	$result->execute(array($_POST['stocks_id']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

    $price = $worked['cost'];
	$costbefore = $price * $_POST['amount'];
	$firmcut = ceil($costbefore * .1);
 	$totalcost = $costbefore - $firmcut;
	
	$error = ($_POST['amount'] < 1) ? "Please enter a valid amount of shares to sell." : $error;
	$error = (Check_Share($worked['id'], $user_class->id) < $_POST['amount']) ? "You don't own that many shares." : $error;
	
	if(isset($error)){
		echo Message($error);
		include 'footer.php';
		die();
	}

	echo Message("You have sold ".$_POST['amount']." shares for a total of $".$totalcost." ($".$price." per share X ".$_POST['amount']." shares - $".$firmcut." transaction fee)");
	$newmoney = $user_class->money + $totalcost;

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
	$result->execute(array($newmoney, $user_class->id));

	$user_class = new User($_SESSION['id']);
	Take_Share($_POST['stocks_id'], $user_class->id, $_POST['amount']);
}

?>
<tr><td>
<img src='images/stock market.png' />
</td></tr>
<thead>
<tr>
<th>Your Portfolio</th>
</tr>
</thead>
<tr><td>
Here you can view, compare, and sell your shares.
</td></tr>
</table>
	<table class="inverted ui five unstackable column small compact table">
		<thead>
<tr>
<th>View Stock</th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>

		<tr>
			<td width='35%'><b>Company Name</b></td>
			<td width='20%'><b>Cost per Share</b></td>
			<td width='10%'><b># Held</b></td>
			<td width='15%'><b>Total Value</b></td>
			<td width='20%'><b>Sell</b></td>
		</tr>
<?
$result = $GLOBALS['pdo']->prepare('SELECT * FROM `shares` WHERE `userid` = ? ORDER BY `userid` ASC');
$result->execute(array($user_class->id));
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `stocks` WHERE `id` = ?');
	$result2->execute(array($line['companyid']));
	$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

	echo "<form method='post'>";
	echo "<tr><td width='35%'>".$worked2['company_name']."</td><td width='20%'>$".$worked2['cost']."</td><td width='10%'>".$line['amount']."</td><td width='15%'>$".$line['amount'] * $worked2['cost']."</td><td width='20%'><input class='ui input' type='text' name='amount' size='3' maxlength='20' value='".$line['amount']."'><input type='hidden' name='stocks_id' value='".$line['companyid']."'>&nbsp;<input type='submit' class='ui mini blue button' name='sellstocks' value='Sell'></form><br></tr>";
}
?>
	</table>
</td></tr>
<?php
include 'footer.php';
?>
