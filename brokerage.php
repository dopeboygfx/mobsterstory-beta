<?php
include 'header.php';

if ($_POST['buystocks']){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `stocks` WHERE `id` = ?');
	$result->execute(array($_POST['stocks_id']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

    $price = $worked['cost'];
	$costbefore = $price * $_POST['amount'];
	$firmcut = ceil($costbefore * .1);
 	$totalcost = $costbefore + $firmcut;
	
	if($price < 15){
		echo Message("Due to current market regulations, you can only buy shares of stocks that are selling at $15 or more.");
	}
	if($_POST['amount'] < 1){
		echo Message("Please enter a valid amount of shares to buy.");
	}
	if ($totalcost > $user_class->money){
		echo Message("You don't have enough money.");
	}
	if($_POST['amount'] >= 1 && $totalcost <= $user_class->money && $price > 14){
		echo Message("You have bought ".$_POST['amount']." shares for a total of $".$totalcost." ($".$price." per share X ".$_POST['amount']." shares + $".$firmcut." transaction fee)");
		$newmoney = $user_class->money - $totalcost;

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
		$result->execute(array($newmoney, $user_class->id));

		$user_class = new User($_SESSION['id']);
		Give_Share($_POST['stocks_id'], $user_class->id, $_POST['amount']);
	}
}

?>
<tr><td align="center">
<img src='images/stock market.png' />
</td></tr>
	 	<thead>
	    <tr>
		<th>Brokerage Firm</th>
  		</tr>
  		</thead>
<tr><td>
Welcome! We are here to help further your wealth, so if there is anything we can do, just let us know! Please keep in mind that we will be charging a 10% transaction fee on your stock exchange when you buy or sell. Thanks for being so understanding!
</td></tr>
	 	<thead>
	    <tr>
		<th>Buy Stocks</th>
  		</tr>
  		</thead>
<tr><td>
	<table class="inverted ui five unstackable column small compact table">
		<tr>
			<td width='5%'><b>ID</b></td>
			<td width='35%'><b>Company Name</b></td>
			<td width='25%'><b>Cost per Share</b></td>
			<td width='35%'><b>Buy</b></td>
		</tr>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `stocks` ORDER BY `id` ASC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	echo "<form method='post'>";
	echo "<tr><td width='5%'>".$line['id']."</td><td width='35%'>".$line['company_name']."</td><td width='25%'>$".$line['cost']."</td><td width='35%'><input class='ui input' type='text' name='amount' size='3' maxlength='20' value='".$line['amount']."'><input type='hidden' name='stocks_id' value='".$line['id']."'>&nbsp;<input type='submit' class='ui mini blue button' name='buystocks' value='Buy'></form><br></tr>";
}
?>
	</table>
</td></tr>
<?php
include 'footer.php';
?>
