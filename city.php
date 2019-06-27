<?php

include 'header.php';

$result = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
$result->execute(array($user_class->city));
$worked = $result->fetch(PDO::FETCH_ASSOC);
?>
<thead>
<tr>
<th><? echo $user_class->cityname; ?></th>
</tr>
</thead>
<tr>
<td ><?= $worked['description'] ?></td>
</tr>
</table>

<table class="inverted ui five unstackable column small compact table">

<thead>
<tr>
<th width='33.3%'>Top Mobsters</th>
<th width='33.3%'>City Drugs



</th>
<th width='33.3%'>Top Gang</th>
</tr>
</thead>
<tr><td>
<!--- start of top 5 member -->

</td></tr>
</table>

<table class="inverted ui five unstackable column small compact table">
	 <thead>
	    <tr>
	    <th>Welcome</th>
	    <th></th>
	    <th></th>
  		</tr>
  		</thead>
		<tr>
			<td valign='top'>
			<b>Shops</b><br>
			<a href="astore.php">Johnny's Armor</a>
			<br><a href="store.php">Weapon Shop</a><br>
			<a href="itemmarket.php">Item Market</a><br>
			<a href="pointmarket.php">Points Market</a><br>
			<a href="spendpoints.php">Point Shop</a><br>
			<a href="pharmacy.php">Pharmacy</a><br />
			<? echo ($user_class->city == 2) ? "<a href='carlot.php'>Sonny's Car Lot</a>" : ""?>
			</td>
			<td width='33.3%' valign='top'>
			<b>Town Hall</b><br>
			<a href="halloffame.php">Hall Of Fame</a><br>
			<a href='worldstats.php'>World Stats</a><br>
			<a href="viewstaff.php">Town Hall</a><br>
			<a href='search.php'>Mobster Search</a><br>
			<a href="citizens.php">Mobsters List</a><br>
			<a href="online.php">Mobsters Online</a><br>
			</td>
			<td width='33.3%' valign='top'>
			<b>Casino</b><br>
			<a href="lottery.php">Lottery</a><br>
			<a href="slots.php">Slot Machine</a><br>
			<a href='5050game.php'>50/50 Game</a><br>
			<a href='#'></a><br>
			<a href='#'></a><br>
			<a href='#'></a><br>
			</td>

		</tr>

<tr>
	<td width='33.3%' valign='top'>
	<b>Your Home</b><br>
	<a href="pms.php">Mailbox
<?php

$checkmail = $GLOBALS['pdo']->prepare('SELECT * FROM `pms` WHERE `to`= ? AND `viewed` = "1"');
$checkmail->execute(array($user_class->username));
$nummsgs = count($checkmail->fetchAll(PDO::FETCH_NUM));
?>
 [<?php echo $nummsgs; ?>]</a><br>
	<a href="events.php">Events
<?php
$checkmail = $GLOBALS['pdo']->prepare('SELECT * FROM `events` WHERE `to` = ? AND `viewed` = "1"');
$checkmail->execute(array($user_class->id));
$numevents = count($checkmail->fetchAll(PDO::FETCH_NUM));
?>
	 [<?php echo $numevents; ?>]</a><br>
	<a href="spylog.php">Spy Log</a><br />
	<a href="inventory.php">Inventory</a><br>
	<a href="refer.php">Referrals</a><br>
	<a href="house.php">Move House</a><br />
	<a href="fields.php">Manage Land</a>
	</td>
	<td width='33.3%' valign='top'>
	<b>Travel</b><br>
	<a href='bus.php'>Bus Station</a><br>
	<a href='drive.php'>Drive</a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	</td>
	<td width='33.3%' valign='top'>
	<b>Downtown</b><br>
	<a href="buydrugs.php">Shady-Looking Stranger</a><br>
	<a href="downtown.php">Search Downtown</a><br>
	<a href="jobs.php">Job Center</a><br>
	<a href = "gang_list.php">Gang List</a><br>
	<a href="<? echo ($user_class->gang == 0) ? "creategang.php" : "gang.php"; ?>">Your Gang</a><br>
	<a href="bank.php">Bank</a><br>
	<a href="realestate.php">Real Estate Agency</a>
	</td>
	</tr>
	<tr>
	<td width='33.3%' valign='top'>
	<b>RM Access</b><br>
	<a href='smuggle.php'>Drug Dealer</a><br>
	<a href='#'></a>Race Track - Soon!<br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	</td>
	<td width='33.3%' valign='top'>
	<b>Car Central</b><br>
	<a href='garage.php'>Your Garage</a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	</td>
	<td width='33.3%' valign='top'>
	<b>Generic Street</b><br />
	<a href='viewstocks.php'>View Stock Market</a><br />
    <a href='brokerage.php'>Brokerage Firm</a><br />
	<a href='portfolio.php'>View Portfolio</a>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	<a href='#'></a><br>
	</td>
</tr>
</table>
<table class="inverted ui unstackable column small compact table">
</td></tr>
<thead>
<tr>
<th>Today's Top Mobster</th>
</tr>
</thead>
<tr><td>
<center>
Todays top mobster is currently <?php echo $hitman->formattedname; ?> with <?php echo prettynum($hitman->todayskills); ?> kills.<br />
100 points go to the top mobster at the end of the day!
</center>
</td></tr>
<thead>
<tr>
<th>Today's Top Leveler</th>
</tr>
</thead>
<tr><td>
<center>
Todays top leveler is currently <?php echo $leveler->formattedname; ?> with <?php echo prettynum($leveler->todaysexp); ?> exp.<br />
1 credit goes to the top leveler at the end of the day!
</center>
</table>
</td></tr>

<?
include 'footer.php';
?>
