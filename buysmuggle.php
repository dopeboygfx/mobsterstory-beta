<?

include 'header.php';
	$cityID = $user_class->city;
if ($user_class->hospital > 0){
    echo Message('You\'re in hospital. Come back later');
    include(DIRNAME(__FILE__).'/footer.php');
    exit;
}
if($user_class->jail > 0){
    echo Message('You\'re in jail. Come back later');
    include(DIRNAME(__FILE__).'/footer.php');
    exit;
}

if(!isset($scruffy_amount))
$scruffy_amount =0;
if (isset($_GET['buy'])) {
$_GET['buy'] = abs(intval($_GET['buy']));

$resultnew = $GLOBALS['pdo']->prepare('SELECT * from `SmuggleItems` WHERE `id` = ? and `buyable` = 1');
$resultnew->execute(array($_GET['buy']));
$worked = $resultnew->fetch(PDO::FETCH_ASSOC);

	//check amount in drug storage, deny if full.
	$Scruffyresult = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ?');
	$Scruffyresult->execute(array($user_class->id));

	foreach($Scruffyresult->fetchAll(PDO::FETCH_ASSOC) as $line){
		$scruffy_amount += $line['amount'];
		$buyPrice2 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
		$buyPrice2->execute(array($cityID));
		$fetch_bp2 = $buyPrice2->fetch(PDO::FETCH_ASSOC);

		
	}
if($scruffy_amount >= $user_class->storage) {
				echo Message("You do not have enough storage.");
		}else{
	$Scruffyresult_count = count($Scruffyresult->fetchAll(PDO::FETCH_NUM));
	if($Scruffyresult_count <= 0){
		$buyPrice3 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
		$buyPrice3->execute(array($cityID));
		$fetch_bp2 = $buyPrice3->fetch(PDO::FETCH_ASSOC);
	}


 if($worked['id'] != ""){
			if($worked['id'] == 26){

			$buyPriceScruffy = $fetch_bp2['weedbuy'];
		}
		if($worked['id'] == 31){

			$buyPriceScruffy = $fetch_bp2['cokebuy'];
		}
		if($worked['id'] == 36){

			$buyPriceScruffy = $fetch_bp2['tobaccobuy'];
		}

		if($worked['id'] == 41){

			$buyPriceScruffy = $fetch_bp2['boozebuy'];
		}
		if($worked['id'] == 46){

			$buyPriceScruffy = $fetch_bp2['methbuy'];
		}


    if ($user_class->money >= $buyPriceScruffy){
    $newmoney = $user_class->money - $buyPriceScruffy;
    $newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id`= ?');
    $newsql->execute(array($newmoney, $user_class->id));
	$quantity = "1";

    Give_Drug($_GET['buy'], $user_class->id, $quantity, $user_class->city, $buyPriceScruffy);//give the user their item they bought
    echo Message("You have purchased a ".$worked['itemname']);
    } else {
    echo Message("You do not have enough money to buy a ".$worked['itemname']);
    }
  } else {
  echo Message("That isn't a real item.");
  }
}

if(!isset($weapons))
$weapons ='';
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `SmuggleItems` WHERE `location`= ?');
	$result->execute(array($user_class->city));
	$howmanyitems = 0;
	foreach($result->fetchAll(PDO::FETCH_ASSOC) as $line){

		if ($line['buyable'] == 1){
		$weapons .= "

						<td width='25%'>
						<center>
						<img src='". $line['image']."' width='100' height='100' style='border: 1px solid #333333'>
						<br>
						<br>
						". item_popup($line['itemname'], $line['id']) ."
						<br>
						Quantity: [x1]<br>
						$". $line['cost'] ."
						<br>
						<br>
						<a class='ui mini green button' href='buysmuggle.php?buy=".$line['id']."'>Buy</a>
						</td>
		";
		$howmanyitems = $howmanyitems + 1;
			if ($howmanyitems == 3){
				$weapons.= "</tr><tr>";
				$howmanyitems = 0;
			}
		}
	}
if ($weapons != ""){
 ?>
 	<thead>
    <tr>
	<th>Drug Deal</th>
  	</tr>
  	</thead>
				<tr>
				<? echo $weapons; ?>
				</tr>
			</table>
</td></tr>
<?
}
}
include 'footer.php'
?>
