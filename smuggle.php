<?php
//include header (configs, classes, db info)
error_reporting(E_ALL);
include(DIRNAME(__FILE__).'/header.php');
if($user_class->rmdays == 0) {
    echo Message("You dont have RM days.Please visit the UPGRADE Page.");
    include(DIRNAME(__FILE__).'/footer.php');
    exit;
}


//Smuggling Inventory
	$cityID = $user_class->city;

if(!isset($Drugsmuggle))
$Drugsmuggle='';

if(!isset($weapons))
$weapons='';

if(!empty($priceList))
$priceList='';

if(!empty($scruffy_amount))
$scruffy_amount =0;


$DrugStorage = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ? ORDER BY `userid` DESC');
$DrugStorage->execute(array($user_class->id));
$DrugStorage = $DrugStorage->fetchAll(PDO::FETCH_ASSOC);

foreach($DrugStorage as $Dline){
	$DrugStorage2 = $GLOBALS['pdo']->prepare('SELECT * FROM `SmuggleItems` WHERE `id`= ?');
	$DrugStorage2->execute(array($Dline['drugid']));
	$DrugArray2 = $DrugStorage2->fetch(PDO::FETCH_ASSOC);

	$magic_drug_array = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
	$magic_drug_array->execute(array($cityID));
	$fetch_bp = $magic_drug_array->fetch(PDO::FETCH_ASSOC);

		if($DrugArray2['id'] == 26){
			$sellPriceScruffy = $fetch_bp['weedbuy'];
		}
		if($DrugArray2['id'] == 31){
			$sellPriceScruffy = $fetch_bp['cokebuy'];
		}
		if($DrugArray2['id'] == 36){
			$sellPriceScruffy = $fetch_bp['tobaccobuy'];
		}

		if($DrugArray2['id'] == 41){

			$sellPriceScruffy = $fetch_bp['boozebuy'];
		}
		if($DrugArray2['id'] == 46){
			$sellPriceScruffy = $fetch_bp['methbuy'];
		}

	//$price = ($sellPriceScruffy * $Dline['amount']);
	$price = ($Dline['buy_amount'] * $Dline['amount']);
		$sell = ($sellPriceScruffy > 0) ? "
		<br>
		<!----><a class='ui mini red button'href='sellsmuggle.php?ID=".$Dline['drugid']."'>Sell</a><!---->" : "";
		$Drugsmuggle .= "

				<td width='25%'>
				<center>
				<img src='". $DrugArray2['image']."' width='100' height='100' style='border: 0px solid #333333'>
				<br>
				<br>
				". drug_popup($DrugArray2['itemname'], $DrugArray2['id']) ."
				<br>
				Quanity: [x".$Dline['amount']."]<br>
				$". $price ."<br>
					 $sell
				</td>
				";

			}

//start buying drugs
if (isset($_GET['ac']) == "buy" && isset($_GET['buy']) != "") {
$_GET['buy'] = abs(intval($_GET['buy']));

	$resultnew = $GLOBALS['pdo']->prepare('SELECT * from `SmuggleItems` WHERE `id` = ? and `buyable` = "1"');
	$resultnew->execute(array($_GET['buy']));
	$worked = $resultnew->fetch(PDO::FETCH_ASSOC);

	$Scruffyresult = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ?');
	$Scruffyresult->execute(array($user_class->id));
	$Scruffyresult = $Scruffyresult->fetchAll(PDO::FETCH_ASSOC);

	foreach($Scruffyresult as $line){
    $scruffy_amount += $line['amount'];

  $buyPrice2 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
	$buyPrice2->execute(array($cityID));
	$fetch_bp2 = $buyPrice2->fetch(PDO::FETCH_ASSOC);
	if($scruffy_amount >= $user_class->storage) {
			echo Message("You do not have enough storage.");
			exit;
	}
}
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

 if($worked['id'] != ""){




	if ($user_class->money >= $buyPriceScruffy){
    $newmoney = $user_class->money - $buyPriceScruffy;

	$newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id`= ?');
	$newsql->execute(array($newmoney, $user_class->money));

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

	$result = $GLOBALS['pdo']->query('SELECT * FROM `SmuggleItems`');
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	$howmanyitems = 0;
	foreach($result as $line){
		$buyPrice3 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
		$buyPrice3->execute(array($cityID));
		$fetch_bp3 = $buyPrice3->fetch(PDO::FETCH_ASSOC);

		if($line['id'] == 26){

			$buyPriceScruffy3 = $fetch_bp3['weedbuy'];
		}
		if($line['id'] == 31){

			$buyPriceScruffy3 = $fetch_bp3['cokebuy'];
		}
		if($line['id'] == 36){

			$buyPriceScruffy3 = $fetch_bp3['tobaccobuy'];
		}

		if($line['id'] == 41){

			$buyPriceScruffy3 = $fetch_bp3['boozebuy'];
		}
		if($line['id'] == 46){

			$buyPriceScruffy3 = $fetch_bp3['methbuy'];
		}
		if ($line['buyable'] == 1){
		$weapons .= "

						<td width='25%'>
						<center>
						<img src='". $line['image']."' width='100' height='100' style='border: 1px solid #333333'>
						<br>
						<br>
						". drug_popup($line['itemname'], $line['id']) ."
						<br>
						Quantity: [x1]<br>
						$". $buyPriceScruffy3 ."
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


	///smuggle price list
	$result = $GLOBALS['pdo']->query('SELECT * FROM `SmuggleItems`');
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	$scruffyPrice_run4 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
	$scruffyPrice_run4->execute(array($cityID));
	$s_price4 = $scruffyPrice_run4->fetch(PDO::FETCH_ASSOC);

	$howmanyitems = 0;
	foreach($result as $line){
		if($line['id'] == 26){

			$scruffyPrice4 = $s_price4['weedbuy'];
		}
			if($line['id'] == 31){

			$scruffyPrice4 = $s_price4['cokebuy'];
		}
		if($line['id'] == 36){

			$scruffyPrice4 = $s_price4['tobaccobuy'];
		}

		if($line['id'] == 41){

			$scruffyPrice4 = $s_price4['boozebuy'];
		}
			if($line['id'] == 46){

			$scruffyPrice4 = $s_price4['methbuy'];
		}
		//price =

		if ($line['buyable'] == 1){
		$priceList .= "

		<td width='25%' align='center'>

						". drug_popup($line['itemname'], $line['id']) ."   <br>
						Current Price:
						$". $scruffyPrice4 ."<br>

					</td>
		";
		$howmanyitems = $howmanyitems + 1;
			if ($howmanyitems == 3){
				$weapons.= "</tr><tr>";
				$howmanyitems = 0;
			}
		}
	}
	//end smuggle list
if($priceList != ""){
    ?>
<tr><td>
<table class="inverted ui five unstackable column small compact table">
				<tr>
				<? echo $priceList; ?>
				</tr>
			</table>
</td></tr>

 <?php
}
if ($weapons != ""){
 ?>

 	<thead>
    <tr><th>Drug Prices in <!_-cityname-_!> | Price refresh every 5 minutes</th>
  	</tr>
  	</thead>
	</table>

				<table class="inverted ui five unstackable column small compact table">
				<tr>
				<? echo $weapons; ?>
				</tr>
				</td></tr>
				<?
				}



				if ($Drugsmuggle != ""){
 				?>

 				<thead>
 				<tr>
	 			<th>Drug Dealer</th>
  				</tr>
  				</thead>
				</table>

				<table class="inverted ui five unstackable column small compact table">
				<thead>
 				<tr><th>Your Drugs</th>
  				</tr>
  				</thead>
				<tr>
				<? echo $Drugsmuggle; ?>
				</tr>
			</table>
</td></tr>
<?php
}

//include footer (ending html and php)
include(DIRNAME(__FILE__).'/footer.php');
?>
