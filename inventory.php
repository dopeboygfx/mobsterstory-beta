<?
//`itemtype`='3' AND
error_reporting(E_ALL);
include 'header.php';

if(isset($_GET['use'])){
	if($_GET['use'] == 14){ //if they are trying to use an awake pill
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? AND `itemid` = "14"');
		$result->execute(array($user_class->id));
		$howmany = count($result->fetchAll(PDO::FETCH_NUM));
		if($howmany > 0){
			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `awake` = ? WHERE `id` = ?');
			$result->execute(array($user_class->maxawake, $_SESSION['id']));

			Take_Item(14, $user_class->id);//take away an awake pill
			echo Message("You popped an Awake pill.");
		}
	}//if user is trying to use an item

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
	$result->execute(array($user_class->id, $_GET['use']));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);
	$howmany = count($result);

	$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
	$result2->execute(array($_GET['use']));
	$worked = $result2->fetch(PDO::FETCH_ASSOC);

	if ($howmany > 0) {
		$itemTake = $_GET['use'];
		if($worked['heal'] > 0) {

			$newhp = ceil(floor($user_class->maxhp / 100 * $worked['heal']) + $user_class->hp);
			if($newhp >= $user_class->maxhp) {
				$newhp = $user_class->maxhp;
			}

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `hp` = ? WHERE `id` = ?');
			$result->execute(array($newhp, $_SESSION['id']));

			Take_Item($itemTake, $user_class->id);//take away an awake pill
			echo Message("You used an item.");
		}
		if($worked['hosp'] > 0) {
			if($user_class->hospital <= 0) {
				echo Message('Youre not in the hospital.');
				exit;
			}
			$newhosp = $user_class->hospital - $worked['hosp'];
			if($newhosp <= 0) {
				$newhosp = 0;
			}

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `hospital` = ? WHERE `id` = ?');
			$result->execute(array($newhosp, $_SESSION['id']));

			Take_Item($itemTake, $user_class->id);//take away an awake pill
			echo Message("You used an item.");
		}

	}
}
?>
<thead>
	<tr>
		<th>Your Inventory</th>
	</tr>
</thead>
<tr><td>Everything you have collected.</td></tr>
<thead>
	<tr>
		<th>Equipped</th>
	</tr>
</thead>
<tr><td>
	<table class="inverted ui five unstackable column small compact table">
		<tr>
			<td width='33%'>
				<center>
					<? if ($user_class->eqweapon != 0){?>
						<img src='<?= $user_class->weaponimg ?>' width='100' height='100' style='border: 0px solid #333333'>
						<br>
						<br>
						<?= item_popup($user_class->weaponname, $user_class->eqweapon) ?>
						<br>
						<br>
						<a class="ui red  mini button" href='equip.php?unequip=weapon'><font color="white">Unequip Weapon</font></a>
					<? } else {
						echo "You don't have a weapon equipped.";
					}
					?>
				</td>
				<td width='33%'>
					<center>
						<? if ($user_class->eqarmor != 0){?>
							<img src='<?= $user_class->armorimg ?>' width='100' height='100' style='border: 0px solid #333333'>
							<br>
							<br>
							<?= item_popup($user_class->armorname, $user_class->eqarmor) ?>
							<br>
							<br>
							<a class="ui red  mini button" href='equip.php?unequip=armor'><font color="white">Unequip Armor</font></a>
						<? } else {
							echo "You don't have any armor equipped.";
						}
						?>
					</td>
					<td width='33%'>
						<center>
							<? if ($user_class->eqoffhand != 0){?>
								<img src='<?= $user_class->offhandimg ?>' width='100' height='100' style='border: 0px solid #333333'>
								<br>
								<br>
								<?= item_popup($user_class->offhandname, $user_class->eqoffhand) ?>
								<br>
								<br>
								<a class="ui red  mini button" href='equip.php?unequip=offhand'><font color="white">Unequip Off-Hand</font></a>
							<? } else {
								echo "You don't have any off hand weapon equipped.";
							}
							?>
						</td>

					</tr>
				</table>
			</td></tr>
			<?

			$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? ORDER BY `userid` DESC');
			$result->execute(array($user_class->id));
			$result = $result->fetchAll(PDO::FETCH_ASSOC);

			foreach($result as $line){
				$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
				$result2->execute(array($line['itemid']));
				$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

				if ($worked2['itemtype'] == 1){
					$sell = ($worked2['cost'] > 0) ? "<a href='sellitem.php?id=".$worked2['id']."'>Sell |</a>" : "";
					$weapons .= "

					<td width='25%'>
					<center>
					<img src='". $worked2['image']."' width='100' height='100' style='border: 0px solid #333333'>
					<br>
					<br>
					". item_popup($worked2['itemname'], $worked2['id']) ."
					<br>
					Quantity: [x".$line['quantity']."]
					<br>
					$". $worked2['cost'] ."
					<br>
					<br>
					$sell <a href='putonmarket.php?id=".$worked2['id']."'>Market |</a> <a href='senditem.php?id=".$worked2['id']."'>Send |</a> <a href='equip.php?eq=weapon&id=".$worked2['id']."'>Equip Weapon</a>
					</td>
					";
				}
				if ($worked2['itemtype'] == 4){
					$sell = ($worked2['cost'] > 0) ? "<a href='sellitem.php?id=".$worked2['id']."'>Sell |</a>" : "";
					$offhand .= "

					<td width='25%'>
					<center>
					<img src='". $worked2['image']."' width='100' height='100' style='border: 0px solid #333333'>
					<br>
					<br>
					". item_popup($worked2['itemname'], $worked2['id']) ."
					<br>
					[x".$line['quantity']."]
					<br>
					$". $worked2['cost'] ."
					<br>
					<br>
					$sell <a href='putonmarket.php?id=".$worked2['id']."'>Market |</a> <a href='senditem.php?id=".$worked2['id']."'>Send |</a> <a href='equip.php?eq=offhand&id=".$worked2['id']."'>Equip Off-Hand</a>
					</td>
					";
				}

				if ($worked2['itemtype'] == 2){
					$sell = ($worked2['cost'] > 0) ? "<a href='sellitem.php?id=".$worked2['id']."'>Sell |</a>" : "";
					$armor .= "

					<td width='25%'>
					<center>
					<img src='". $worked2['image']."' width='100' height='100' style='border: 0px solid #333333'>
					<br>
					<br>
					". item_popup($worked2['itemname'], $worked2['id']) ."
					<br>
					Quantity: [x".$line['quantity']."]
					<br>
					$". $worked2['cost'] ."
					<br>
					<br>
					$sell <a href='putonmarket.php?id=".$worked2['id']."'>Market |</a> <a href='senditem.php?id=".$worked2['id']."'>Send |</a> <a href='equip.php?eq=armor&id=".$worked2['id']."'>Equip</a>
					</td>
					";
				}

				if ($worked2['itemtype'] == 3){
					$misc .= "

					<td width='25%'>
					<center>
					<img src='". $worked2['image']."' width='100' height='100' style='border: 0px solid #333333'>
					<br>
					<br>
					". item_popup($worked2['itemname'], $worked2['id']) ."
					<br>
					Quanity: [x".$line['quantity']."]
					<br>
					<br>
					<a href='inventory.php?use=".$worked2['id']."'>Use |</a> <a href='putonmarket.php?id=".$worked2['id']."'>Market |</a> <a href='senditem.php?id=".$worked2['id']."'>Send</a>
					</td>
					";
				}

			}

			//Start Drug Storage


			$DrugStorage = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ? ORDER BY `userid` DESC');
			$DrugStorage->execute(array($user_class->id));
			$DrugStorage = $DrugStorage->fetchAll(PDO::FETCH_ASSOC);

			foreach($DrugStorage as $Dline){
				$DrugStorage2 = $GLOBALS['pdo']->prepare('SELECT * FROM `SmuggleItems` WHERE `id` = ?');
				$DrugStorage2->execute(array($Dline['drugid']));
				$DrugArray2 = $DrugStorage2->fetch(PDO::FETCH_ASSOC);

				$sell = ($DrugArray2['buy_amount'] > 0) ? "<a href='sellitem.php?id=".$DrugArray2['id']."'>Sell |</a>" : "";
				$Drugsmuggle .= "
				<td width='25%'>
				<center>
				<img src='". $DrugArray2['image']."' width='100' height='100' style='border: 0px solid #333333'>
				<br>
				<br>
				". drug_popup($DrugArray2['itemname'], $DrugArray2['id']) ."
				<br>
				Quantity: [x".$Dline['amount']."]
				<br>
				$". ($Dline['buy_amount'] * $Dline['amount']) ."
				<br>
				<br>
				$sell
				<a href='putonmarket.php?id=".$DrugArray2['id']."'>
				Market |</a><a href='senditem.php?id=".$DrugArray2['id']."'>
				Send</a>
				</td>
				";
			}


			//end drug storage


			//check for drugs
			if ($user_class->cocaine != 0){
				$drugs .= "

				<td width='25%' align='center'>

				<img src='images/noimage.png' width='100' height='100' style='border: 1px solid #333333'><br>
				Cocaine [x".$user_class->cocaine."]<br>
				$0<br>
				<a href='drugs.php?use=cocaine'>[Use]</a>
				</td>
				";
			}
			if ($user_class->nodoze != 0){
				$drugs .= "

				<td width='25%' align='center'>

				<img src='images/noimage.png' width='100' height='100' style='border: 1px solid #333333'><br>
				No-Doze [x".$user_class->nodoze."]<br>
				$0<br>
				<a href='drugs.php?use=nodoze'>[Use]</a>
				</td>
				";
			}
			if ($user_class->genericsteroids != 0){
				$drugs .= "

				<td width='25%' align='center'>

				<img src='images/noimage.png' width='100' height='100' style='border: 1px solid #333333'><br>
				Generic Steroids [x".$user_class->genericsteroids."]<br>
				$0<br>
				<a href='drugs.php?use=genericsteroids'>[Use]</a>
				</td>
				";
			}
			//check for drugs
			if ($weapons != ""){
				?>
				<thead>
					<tr>
						<th>Weapons</th>
					</tr>
				</thead>
				<tr><td>
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $weapons; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}
			if ($armor != ""){
				?>
				<thead>
					<tr>
						<th>Armor</th>
					</tr>
				</thead>
				<tr><td>
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $armor; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}
			if ($offhand != ""){
				?>
				<thead>
					<tr>
						<th>Off-Hand</th>
					</tr>
				</thead>
				<tr><td>
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $offhand; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}

			if ($misc != ""){
				?>
				<thead>
					<tr>
						<th>Misc.</th>
					</tr>
				</thead>
				<tr><td>
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $misc; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}
			if ($drugs != ""){
				?>
				<thead>
					<tr>
						<th>Drugs</th>
					</tr>
				</thead>
				<tr><td>
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $drugs; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}
			if ($Drugsmuggle != ""){
				?>
				<thead>
					<tr>
						<th>Drugs</th>
					</tr>
				</thead>
				<tr><td >
					<table class="inverted ui five unstackable column small compact table">
						<tr>
							<? echo $Drugsmuggle; ?>
						</tr>
					</table>
				</td></tr>
				<?
			}
			include 'footer.php'
			?>
