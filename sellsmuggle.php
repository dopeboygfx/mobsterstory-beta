<?php

include(DIRNAME(__FILE__).'/header.php');
if($user_class->hosp > 0){
    echo Message('You\'re in hospital. Come back later');
    include(DIRNAME(__FILE__).'/footer.php');
    exit;
}
if($user_class->jail > 0){
    echo Message('You\'re in jail. Come back later');
    include(DIRNAME(__FILE__).'/footer.php');
    exit;
}
$scruffy_sale_id = abs(intval($_GET['ID']));
$_POST['amount'] = abs(intval($_POST['amount']));
$myID = $user_class->id;
$cityID = $user_class->city;

$Get_Smuggle_INV = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `drugid` = ? AND `userid` = ? LIMIT 1');
$Get_Smuggle_INV->execute(array($_GET['ID'], $myID));
$run = $Get_Smuggle_INV->fetchAll(PDO::FETCH_ASSOC);
$Count_INV = count($run);
$run = $run[0];

$Get_Smuggle_ITM = $GLOBALS['pdo']->prepare('SELECT * FROM `SmuggleItems` WHERE `id` = ? LIMIT 1');
$Get_Smuggle_ITM->execute(array($_GET['ID']));
$run2 = $Get_Smuggle_ITM->fetch(PDO::FETCH_ASSOC);

$amountToSell = $_POST['amount'];
if(isset($_POST['submit'])) {
	if($Count_INV == 0) {
		echo Message('You don\'t have any of them.');
		include(DIRNAME(__FILE__).'/footer.php');
		exit;
	}
	if($_POST['amount'] > $run['amount']) {
		echo Message('You don\'t have that many.');
		include(DIRNAME(__FILE__).'/footer.php');
		exit;
	}

	$buyPrice3 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
	$buyPrice3->execute(array($cityID));
	$fetch_bp3 = $buyPrice3->fetch(PDO::FETCH_ASSOC);
	//new price scruffy
		if($run2['id'] == 26){

			$buyPriceScruffy3 = $fetch_bp3['weedbuy'];
		}
		if($run2['id'] == 31){

			$buyPriceScruffy3 = $fetch_bp3['cokebuy'];
		}
		if($run2['id'] == 36){

			$buyPriceScruffy3 = $fetch_bp3['tobaccobuy'];
		}
		
		if($run2['id'] == 41){

			$buyPriceScruffy3 = $fetch_bp3['boozebuy'];
		}
		if($run2['id'] == 46){

			$buyPriceScruffy3 = $fetch_bp3['methbuy'];
		}

	$price = $buyPriceScruffy3 * $_POST['amount'];
	$drugIDSell = $_GET['ID'];
	Take_Drug($drugIDSell, $myID, $amountToSell);

	$query = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = `money` + ? WHERE `id` = ?');
	$query->execute(array($price, $myID));

	echo Message('You have sold your smuggled item.');
} else {
	echo "
 	<thead>
    <tr>
	<th>Sell Drugs</th>
  	</tr>
  	</thead>
  	
  	<tr><td>
Enter how many {$run2['itemname']} you want to sell. You have <font color='green'>{$run['amount']}</font> to sell.</td></tr>
<tr><td>
<table>
		<form action='sellsmuggle.php?ID={$_GET['ID']}' method='post'>
			Quantity: <input class='ui input focus' type='text' name='amount' value='' />
			<br />
						<br />
			<input type='submit' class='ui mini green button' value='Sell Weed ' name='submit'/>
		</form>

</table>

  	
   		";
    }

include(DIRNAME(__FILE__).'/footer.php');
