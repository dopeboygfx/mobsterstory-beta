<?
include 'header.php';

if ($user_class->admin != 1) {
  echo Message("You are not authorized to be here.");
  include 'footer.php';
  die();
}

//referrals section
if ($_GET['givecredit'] != ""){
	$result = $GLOBALS['pdo']->prepare('UPDATE `referrals` SET `credited`="1" WHERE `id` = ?');
	$result->execute(array($_GET['givecredit']));

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `referrals` WHERE `id` = ?');
	$result->execute($_GET['givecredit']);
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $line){
		$cp_user = new User($line['referrer']);
		$newpoints = $cp_user->points + 10;

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `id` = ?');
		$result->execute(array($newpoints, $cp_user->id));

		send_event($cp_user->id, "You have been credited 10 points for referring ".$line['referred'].". Keep up the good work!");
		echo Message("You have accepted the referral.");
	}
}

if ($_GET['denycredit'] != ""){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `referrals` WHERE `id` = ?');
	$result->execute(array($_GET['denycredit']));

	send_event($line['referrer'], "Unfortunately you have recieved no points for referring ".$line['referred'].". This could be a result of many different things, such as you abusing the referral system, or the player you referred only signing up, but never actually playing.");
	echo Message("You have denied the referral.");
}

//jobs section
if ($_GET['deletejob']){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `jobs` WHERE `id` = ?');
	$result->execute(array($_GET['deletejob']));

	echo Message("You have deleted a job.");
	mrefresh("control.php?page=jobs");
	include 'footer.php';
	die();
}

if ($_POST['addjobdb']){
	$result = $GLOBALS['pdo']->prepare('INSERT INTO `jobs` (name, money, strength, defense, speed, level) VALUES (?, ?, ?, ?, ?, ?)');
	$result->execute(array($_POST['name'], $_POST['money'], $_POST['strength'], $_POST['defense'], $_POST['speed'], $_POST['level']));

	echo Message("You have added a job to the database.");
}
if ($_POST['editjobdb']){
	$result = $GLOBALS['pdo']->prepare('UPDATE `jobs` SET `name` = ?, `money` = ?, `strength` = ?, `defense` = ?, `speed` = ?, `level` = ? WHERE `id` = ?');
	$result->execute(array($_POST['name'], $_POST['money'], $_POST['strength'], $_POST['defense'], $_POST['speed'], $_POST['level'], $_POST['id']));

	echo Message("You have edited a job.");
}
//city section
if ($_GET['deletecity']){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `cities` WHERE `id` = ?');
	$result->execute(array($_GET['deletecity']));

	echo Message("You have deleted a city.");
	mrefresh("control.php?page=cities");
	include 'footer.php';
	die();
}
if ($_POST['addcitydb']){
	$result = $GLOBALS['pdo']->prepare('INSERT INTO `cities` (name, levelreq, landleft, landprice, description) VALUES (?, ?, ?, ?, ?)');
	$result->execute(array($_POST['name'], $_POST['levelreq'], $_POST['landleft'], $_POST['landprice'], $_POST['description']));

	echo Message("You have added a city to the database.");
}
if ($_POST['editcitydb']){
	$result = $GLOBALS['pdo']->prepare('UPDATE `cities` SET `name` = ?, `levelreq` = ?, `landleft` = ?, `landprice` = ?, `description` = ? WHERE `id` = ?');
	$result->execute(array($_POST['name'], $_POST['levelreq'], $_POST['landleft'], $_POST['landprice'], $_POST['description'], $_POST['id']));

	echo Message("You have edited a city.");
}
//crime section
if ($_GET['deletecrime']){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `crimes` WHERE `id` = ?');
	$result->execute(array($_GET['deletecrime']));

	echo Message("You have deleted a crime.");
	mrefresh("control.php?page=crimes");
	include 'footer.php';
	die();
}
if ($_POST['addcrimedb']){
	$result = $GLOBALS['pdo']->prepare('INSERT INTO `crimes` (name, nerve, stext, ftext, ctext) VALUES (?, ?, ?, ?, ?)');
	$result->execute(array($_POST['name'], $_POST['nerve'], $_POST['stext'], $_POST['ftext'], $_POST['ctext']));

	echo Message("You have added a crime to the database.");
}
if ($_POST['editcrimedb']){
	$result = $GLOBALS['pdo']->prepare('UPDATE `crimes` SET `name` = ?, `nerve` = ?, `stext` = ?, `ftext` = ?, `ctext` = ? WHERE `id` = ?');
	$result->execute(array($_POST['name'], $_POST['nerve'], $_POST['stext'], $_POST['ftext'], $_POST['ctext'], $_POST['id']));

	echo Message("You have edited a crime.");
}
//items section
if (isset($_POST['additemdb'])){
	$result = $GLOBALS['pdo']->prepare('INSERT INTO `items` (itemname,description, itemtype,cost,image,offense,defense,speed,heal,buyable,level, storage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$result->execute(array($_POST['itemname'], $_POST['description'], $_POST['itemtype'], $_POST['cost'], $_POST['image'], $_POST['offense'], $_POST['defense'], $_POST['speed'], $_POST['heal'], $_POST['buyable'], $_POST['level'], $_POST['storage']));

	echo '<div class="contenthead"> Item Created </div>';
}
if ($_GET['takealluser'] != ""){
	$oldamount = Check_Item($_GET['takeallitem'], $_GET['takealluser']);

	$result = $GLOBALS['pdo']->prepare('DELETE FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
	$result->execute(array($_GET['takealluser'], $_GET['takeallitem']));

 echo Message("That user had ".$oldamount." of those, now they are all gone.");
}
if ($_POST['giveitem'] != ""){
	$oldamount = Check_Item($_POST['itemnumber'], Get_ID($_POST['username']));
	Give_Item($_POST['itemnumber'], Get_ID($_POST['username']), $_POST['itemquantity']);
	$newamount = Check_Item($_POST['itemnumber'], Get_ID($_POST['username']));
	echo Message("That user had ".$oldamount." of those, and now has ".$newamount." of them.");
}
if ($_POST['takeitem'] != ""){
	$oldamount = Check_Item($_POST['itemnumber'], Get_ID($_POST['username']));
	Take_Item($_POST['itemnumber'], Get_ID($_POST['username']), $_POST['itemquantity']);
	$newamount = Check_Item($_POST['itemnumber'], Get_ID($_POST['username']));
	echo Message("That user had ".$oldamount." of those, and now has ".$newamount." of them.");
}
if ($_POST['listitems'] != ""){
	$oldamount = Check_Item($_POST['itemnumber'], Get_ID($_POST['username']));

	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ?');
	$result->execute(array(Get_ID($_POST['username'])));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $line){
		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
		$result2->execute(array($line['itemid']));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		$out.= "<div>".$line['itemid'].".) ".item_popup($worked2['itemname'], $worked2['id']) ." $". $worked2['cost']." Quantity: ".$line['quantity']." <a href='control.php?page=playeritems&takealluser=".Get_ID($_POST['username'])."&takeallitem=".$line['itemid']."'>Take All</a></div>";
	}
	echo Message($_POST['username']."'s Items<br>".$out);
}
if ($_POST['changemessage'] != ""){
	$result = $GLOBALS['pdo']->prepare('UPDATE `serverconfig` SET `messagefromadmin` = ?');
	$result->execute(array($_POST['message']));

	echo Message("You have changed the message from the admin.");
}

if ($_POST['changeserverdown'] != ""){
	$result = $GLOBALS['pdo']->prepare('UPDATE `serverconfig` SET `serverdown` = ?');
	$result->execute(array($_POST['message']));

	echo Message("You have changed the server down text.");
}
if ($_POST['addrmdays'] != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($_POST['username']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$newrmdays = $worked['rmdays'] + $_POST['rmdays'];

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `rmdays` = ? WHERE `username` = ?');
	$result->execute(array($newrmdays, $_POST['username']));

	echo Message("You have added ".$_POST['rmdays']." RM Days to ".$_POST['username'].".");
}
if($_POST['addpoints'] != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($_POSt['username']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$newpoints = $worked['points'] + $_POST['points'];

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `username` = ?');
	$result->execute(array($newpoints, $_POSt['username']));

	echo Message("You have added ".$_POST['points']." points to ".$_POST['username'].".");
}
if ($_POST['addhookers'] != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($_POST['username']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$newhookers = $worked['hookers'] + $_POST['hookers'];

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `hookers` = ? WHERE `username` = ?');
	$result->execute(array($newhookers, $_POST['username']));

	echo Message("You have added ".$_POST['hookers']." hookers to ".$_POST['username'].".");
}
if($_POST['givermgun'] != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($_POST['username']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `inventory` (userid, itemid) VALUES (?, "15")');
	$result->execute(array($worked['id']));

	echo Message("You have given an RM Gun to ".$_POST['username'].".");
}

if ($_POST['givermarmor'] != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($_POST['username']));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `inventory` (userid, itemid) VALUES (?, "16")');
	$result->execute(array($worked['id']));

	echo Message("You have given an RM Armor to ".$_POST['username'].".");
}
if ($_GET['action'] == "deleteallfromip"){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `grpgusers` WHERE `ip` = ?');
	$result->execute(array($_GET['ip']));
}

if(isset($_POST['adminstatus'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 1 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

if(isset($_POST['revokeadminstatus'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 0 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

if(isset($_POST['banplayer'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 5 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

if(isset($_POST['president'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 3 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

if(isset($_POST['impeachpresident'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 0 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

if(isset($_POST['congress'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 4 WHERE username = ?');
		$result->execute(array($user));
	}
}

if(isset($_POST['impeachcongress'])){
	$user = trim($_POST['username']);
	if($user != ""){
		$result = $GLOBALS['pdo']->prepare('UPDATE grpgusers SET admin = 0 WHERE username = ?');
		if(!$result->execute(array($user)))
			die('Failure to Update a player with Admin Status.');
	}
}

?>
	  <thead>
	  <tr>
	  <th>Control Panel</th>
	  </tr>
	  </thead>
<tr><td>Welcome to the control panel. Here you can do just about anything, from giving players items they have paid for with real money, to adding, changing, or deleting jobs, cities, items, etc. <br /><br />Please send any ideas for things that need to be added to the control panel to comments@thegrpg.com <br /><br />If you are experiencing problems with any of the options, try clicking the submit button instead of pressing the enter key.</td></tr>
<?php if($_GET['page'] == "") { ?>
	  <thead>
	  <tr>
	  <th>Change message from the Admin</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<?
$result = $GLOBALS['pdo']->query('SELECT * from `serverconfig`');
$worked = $result->fetch(PDO::FETCH_ASSOC);
?>
<textarea class="ui input" name='message' cols='53' rows='7'><?= $worked['messagefromadmin']; ?></textarea>
<br>
<br>
<input class="ui mini red button" type='submit' name='changemessage' value='Change Message From Admin'>
</form>
</td></tr>
	  <thead>
	  <tr>
	  <th>Change Server Down Text</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<?
$result = $GLOBALS['pdo']->query('SELECT * from `serverconfig`');
$worked = $result->fetch(PDO::FETCH_ASSOC);
?>
<textarea class="ui input" name='message' cols='53' rows='7'><?= $worked['serverdown']; ?></textarea>
<br>
<br>
<input class="ui mini red button" type='submit' name='changeserverdown' value='Change Server Down Text'>
</form>
</td></tr>

<?php } ?>
<?php if ($_GET['page'] == "rmoptions") { ?>
	  <thead>
	  <tr>
	  <th>Add RM Days</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' class="ui input focus" name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui input focus' type='text' class="ui input focus" name='rmdays' size='10' maxlength='75'> [How Many RM Days]<br />
<br>
<input type='submit' class='ui mini blue button' name='addrmdays' value='Add RM days'>
</form>
</td></tr>
	  <thead>
	  <tr>
	  <th>Add Points</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' class="ui input focus" name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui input focus' type='text' class="ui input focus" name='points' size='10' maxlength='75'> [How Many Points]<br />
<br />
<input type='submit' class='ui mini blue button' name='addpoints' value='Give Points'>
</form>
</td></tr>
	  <thead>
	  <tr>
	  <th>Add Hookers</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' class="ui input focus" name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui input focus' type='text' class="ui input focus" name='hookers' size='10' maxlength='75'> [How Many Hookers]<br />
<br />
<input type='submit' class='ui mini blue button' name='addhookers' value='Give Hookers'>
</form>
</td></tr>
	  <thead>
	  <tr>
	  <th>Give RM Gun</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' class="ui input focus" name='username' size='10' maxlength='75'> [Username]<br />
<br />
<input type='submit' class='ui mini blue button' name='givermgun' value='Give RM Gun'>
</form>
</td></tr>
	  <thead>
	  <tr>
	  <th>Give RM Armor</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br />
<input type='submit' class='ui mini blue button' name='givermarmor' value='Give RM Armor'>
</form>
</td></tr>
<?php
}
if ($_GET['page'] == "setplayerstatus") { ?>
	  <thead>
	  <tr>
	  <th>Ban a Player</th>
	  </tr>
	  </thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='username' size='10' maxlength='75'> [Username]<br />
<input class="ui input focus" type='text' name='reason'   size='10' maxlength='75'>[Reason for Banning]<br/>
<br>
<input class="ui mini yellow button"  type='submit' name='banplayer' value='Ban Player'></td></tr>
<thead>
<tr><th>Give Admin Status</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button" type='submit' name='adminstatus' value='Change Admin Status'>
</form>
</td></tr>
<thead>
<tr><th>Revoke Admin Status</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button" type='submit' name='revokeadminstatus' value='Revoke Admin Status'>
</form>
</td></tr>
<thead>
<tr><th>Presidential Election</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button" type='submit' name='president' value='Elect President'>
</form>
</td></tr>
<thead>
<tr><th>Impeach President</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button" type='submit' name='impeachpresident' value='Impeach President'>
</form>
</td></tr>
<thead>
<tr><th>Congretional Election</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button" type='submit' name='congress' value='Elect Congress'>
</form>
</td></tr>
<thead>
<tr><th>Impeach Congress</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='username' size='10' maxlength='75'> [Username]<br />
<br>
<input class="ui mini yellow button"  type='submit' name='impeachcongress' value='Impeach Congressman'>
</form>
</td></tr>
<?
}

if ($_GET['page'] == "playeritems") { ?>
  <thead>
  <tr><th>List of all items</th></tr>
  </thead>
<tr><td>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `items`')->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $line){
	echo "<div>".$line['id'].".) ".item_popup($line['itemname'], $line['id']) ." $". $line['cost']."</div>";
}
?>
</td></tr>
<thead>
<tr><th>Add New Item To Database</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class="ui input focus" type='text' name='itemname' size='10' maxlength='75'> [itemname]<br />
<input class="ui input focus" type='text' name='description' size='10' maxlength='75'> [description]<br />
<input class="ui input focus" type='text' name='itemtype' size='10' maxlength='75'> [Item Type] 1 = Weapon, 2 = Armor, 3 = consumable, 4 = offhand<br />
<input class="ui input focus" type='text' name='cost' size='10' maxlength='75'> [cost]<br />
<input class="ui input focus" type='text' name='image' size='10' maxlength='75'value='images/noimage.png'> [image]<br />
<input class="ui input focus" type='text' name='offense' size='10' maxlength='75'> [offense]<br />
<input class="ui input focus" type='text' name='defense' size='10' maxlength='75'> [defense]<br />
<input class="ui input focus" type='text' name='speed' size='10' maxlength='75'> [speed]<br />
<input class="ui input focus" type='text' name='heal' size='10' maxlength='75'value='0'> [heal]<br />
<input class="ui input focus" type='text' name='buyable' size='10' maxlength='75'value='0'> [buyable]<br />
<input class='ui input focus' type='text' name='level' size='10' maxlength='75' value='0'> [level]<br />
<input class='ui input focus' type='text' name='storage' size='10' maxlength='75' value='0'> [storage]<br />
<input class='ui mini blue button' type='submit' name='additemdb' value='Add Item'></td></tr>
</form>
<thead>
<tr><th>Give Item</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui input focus' type='text' name='itemnumber'   size='10' maxlength='75'> [Item Number]<br/>
<input class='ui input focus' type='text' name='itemquantity'   size='10' maxlength='75'> [Quantity]<br/>
<input class='ui mini blue button' type='submit' name='giveitem' value='Give Items'></td></tr>
</form>
<thead>
<tr><th>Take Item</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui input focus' type='text' name='itemnumber'   size='10' maxlength='75'> [Item Number]<br/>
<input class='ui input focus' type='text' name='itemquantity'   size='10' maxlength='75'> [Quantity]<br/>
<input class='ui mini blue button' type='submit' name='takeitem' value='Take Items'></td></tr>
</form>
<thead>
<tr><th>View A Player's Items</th></tr>
</thead>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='username' size='10' maxlength='75'> [Username]<br />
<input class='ui mini blue button' type='submit' name='listitems' value='List Items'></td></tr>
</form>
<?
}

if ($_GET['page'] == "referrals") { ?>
  <thead>
  <tr><th>Manage Referrals</th></tr>
  </thead>
<tr><td>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `referrals` WHERE `credited`="0"')->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $line){
	echo "<div>".$line['id'].".) ".$line['referred']." was referred by Player ID:". $line['referrer']." (".date(F." ".d.", ".Y." ".g.":".i.":".sa,$line['when']).") <a href='control.php?page=referrals&givecredit=".$line['id']."'>Credit</a> | <a href='control.php?page=referrals&denycredit=".$line['id']."'>Deny</a></div>";
}
?>
</td></tr>
<?
}
if ($_GET['page'] == "crimes") { ?>
  <thead>
  <tr><th>Crimes</th></tr>
  </thead>
	<tr><td>
	<?
	$result = $GLOBALS['pdo']->query('SELECT * FROM `crimes`');
	echo "<table><tr align='center'><td><b>ID</b></td><td><b>Name</b></td><td><b>Nerve</b></td><td><b>Delete</b></td><tr>";

	foreach($result as $line){
		echo "<tr><td>".$line['id'].".)</td><td>".$line['name']."</td><td>". $line['nerve']."</td><td><a href='control.php?page=crimes&deletecrime=".$line['id']."'>[Delete Crime]</a></td></tr>";
	}
	echo "</table>";
	?>
	</td></tr>
	<tr><td class="contenthead">Add New Crime To Database</td></tr>
	<tr><td>
	<form method='post'>
	<input class='ui input focus' type='text' name='name' size='30' maxlength='75'> [name]<br />
	<input class='ui input focus' type='text' name='nerve' size='30' maxlength='75'> [nerve]<br />
	<textarea name='stext' cols='53' rows='7'>Success message</textarea><br />
	<textarea name='ctext' cols='53' rows='7'>Fail message</textarea><br />
	<textarea name='ftext' cols='53' rows='7'>Fail and caught message</textarea><br />
	<input class='ui mini blue button' type='submit' name='addcrimedb' value='Add Crime'></td></tr>
	</form>
	<tr><td class="contenthead">View/Edit A Crime</td></tr>
	<tr><td>
	<form method='post'>
	<input class='ui input focus' type='text' name='crimeid' size='10' maxlength='75'> [Crime ID]<br />
	<input class='ui mini blue button' type='submit' name='vieweditcrime' value='View/Edit Crime'></td></tr>
	<?
	if($_POST['vieweditcrime']){
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `crimes` WHERE `id` = ?');
		$result->execute(array($_POST['crimeid']));
		$worked = $result->fetch(PDO::FETCH_ASSOC);
		?>
		<tr><td class="contenthead">Edit Crime</td></tr>
		<tr><td>
		<form method='post'>
		<input class='ui input focus' type='text' name='name' size='30' maxlength='75' value='<?= $worked['name'] ?>'> [name]<br />
		<input class='ui input focus' type='text' name='nerve' size='30' maxlength='75' value='<?= $worked['nerve'] ?>'> [nerve]<br />
		<textarea name='stext' cols='53' rows='7'><?= $worked['stext'] ?></textarea><br />
		<textarea name='ctext' cols='53' rows='7'><?= $worked['ctext'] ?></textarea><br />
		<textarea name='ftext' cols='53' rows='7'><?= $worked['ftext'] ?></textarea><br />
		<input type="hidden" name="id" value="<?= $worked['id'] ?>">
		<input class='ui mini blue button' type='submit' name='editcrimedb' value='Edit Crime'></td></tr>
		</form>
		<?
	}
}
if ($_GET['page'] == "cities") { ?>
<tr><td class="contenthead">Cities</td></tr>
<tr><td>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `cities`')->fetchAll(PDO::FETCH_ASSOC);
echo "<table cellpadding='4'><tr align='center'><td><b>ID</b></td><td><b>Name</b></td><td><b>Level Req</b></td><td><b>Land Left</b></td><td><b>Land Price</b></td><td><b>Delete</b></td></tr>";
foreach($result as $line){
	echo "<tr><td>".$line['id'].".)</td><td>".$line['name']."</td><td>". $line['levelreq']."</td><td>".$line['landleft']."</td><td>".$line['landprice']."</td><td><a href='control.php?page=cities&deletecity=".$line['id']."'>[Delete City]</a></td></tr>";
}
echo "</table>";
?>
</td></tr>
<tr><td class="contenthead">Add New City To Database</td></tr>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='name' size='30' maxlength='75'> [name]<br />
<input class='ui input focus' type='text' name='levelreq' size='30' maxlength='75'> [level req]<br />
<input class='ui input focus' type='text' name='landleft' size='30' maxlength='75'> [land left]<br />
<input class='ui input focus' type='text' name='landprice' size='30' maxlength='75'> [land price]<br />
<textarea name='description' cols='53' rows='7'>Description goes here...</textarea><br />
<input class='ui mini blue button' type='submit' name='addcitydb' value='Add City'></td></tr>
</form>
<tr><td class="contenthead">View/Edit A City</td></tr>
<tr><td>
<form method='post'>
<input class='ui input focus' type='text' name='cityid' size='10' maxlength='75'> [City ID]<br />
<input class='ui mini blue button' type='submit' name='vieweditcity' value='View/Edit City'></td></tr>
<?
	if($_POST['vieweditcity']){
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
		$result->execute(array($_POST['cityid']));
		$worked = $result->fetch(PDO::FETCH_ASSOC);
		?>
		<tr><td class="contenthead">Edit City</td></tr>
		<tr><td>
		<form method='post'>
		<input class='ui input focus' type='text' name='name' size='30' maxlength='75' value='<?= $worked['name'] ?>'> [name]<br />
		<input class='ui input focus' type='text' name='levelreq' size='30' maxlength='75' value='<?= $worked['levelreq'] ?>'> [level req]<br />
		<input class='ui input focus' type='text' name='landleft' size='30' maxlength='75' value='<?= $worked['landleft'] ?>'> [land left]<br />
		<input class='ui input focus' type='text' name='landprice' size='30' maxlength='75' value='<?= $worked['landprice'] ?>'> [land price]<br />
		<textarea name='description' cols='53' rows='7'><?= $worked['description'] ?></textarea><br />
		<input type="hidden" name="id" value="<?= $worked['id'] ?>">
		<input class='ui mini blue button' type='submit' name='editcitydb' value='Edit City'></td></tr>
		</form>
		<?
	}
}
if ($_GET['page'] == "jobs") { ?>
	<tr><td class="contenthead">Jobs</td></tr>
	<tr><td>
	<?
	$result = $GLOBALS['pdo']->query('SELECT * FROM `jobs`')->fetchAll(PDO::FETCH_ASSOC);
	echo "<table><tr align='center'><td><b>ID</b></td><td><b>Name</b></td><td><b>Money</b></td><td><b>Strength</b></td><td><b>Defense</b></td><td><b>Speed</b></td><td><b>Level</b></td><td><b>Delete</b></td><tr>";
	foreach($result as $line){
		echo "<tr><td>".$line['id'].".)</td><td>".$line['name']."</td><td>". $line['money']."</td><td>".$line['strength']."</td><td>".$line['defense']."</td><td>".$line['speed']."</td><td>".$line['level']."</td><td><a href='control.php?page=jobs&deletejob=".$line['id']."'>[Delete Job]</a></td></tr>";
	}
	echo "</table>";
	?>
	</td></tr>
	<tr><td class="contenthead">Add New Job To Database</td></tr>
	<tr><td>
	<form method='post'>
	<input class='ui input focus' type='text' name='name' size='30' maxlength='75'> [name]<br />
	<input class='ui input focus' type='text' name='money' size='30' maxlength='75'> [money]<br />
	<input class='ui input focus' type='text' name='strength' size='30' maxlength='75'> [strength]<br />
	<input class='ui input focus' type='text' name='defense' size='30' maxlength='75'> [defense]<br />
	<input class='ui input focus' type='text' name='speed' size='30' maxlength='75'> [speed]<br />
	<input class='ui input focus' type='text' name='level' size='30' maxlength='75'> [level]<br />
	<input class='ui mini blue button' type='submit' name='addjobdb' value='Add Job'></td></tr>
	</form>
	<tr><td class="contenthead">View/Edit A Job</td></tr>
	<tr><td>
	<form method='post'>
	<input class='ui input focus' type='text' name='jobid' size='10' maxlength='75'> [Job ID]<br />
	<input class='ui mini blue button' type='submit' name='vieweditjob' value='View/Edit Job'></td></tr>
	<?
	if($_POST['vieweditjob']){
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `jobs` WHERE `id` = ?');
		$result->execute(array($_POST['jobid']));
		$worked = $result->fetch(PDO::FETCH_ASSOC);
		?>
		<tr><td class="contenthead">Edit Job</td></tr>
		<tr><td>
		<form method='post'>
		<input class='ui input focus' type='text' name='name' size='30' maxlength='75' value='<?= $worked['name'] ?>'> [name]<br />
		<input class='ui input focus' type='text' name='money' size='30' maxlength='75' value='<?= $worked['money'] ?>'> [money]<br />
		<input class='ui input focus' type='text' name='strength' size='30' maxlength='75' value='<?= $worked['strength'] ?>'> [strength]<br />
		<input class='ui input focus' type='text' name='defense' size='30' maxlength='75' value='<?= $worked['defense'] ?>'> [defense]<br />
		<input class='ui input focus' type='text' name='speed' size='30' maxlength='75' value='<?= $worked['speed'] ?>'> [speed]<br />
		<input class='ui input focus' type='text' name='level' size='30' maxlength='75' value='<?= $worked['level'] ?>'> [level]<br />
		<input type="hidden" name="id" value="<?= $worked['id'] ?>">
		<input class='ui mini blue button' type='submit' name='editjobdb' value='Edit Job'>
		</form>
		</td></tr>
		<?
	}
}
include 'footer.php';
?>
