<?
if($_GET['key'] != "8XDHhpTHidov4FepFPJO"){
     echo 'nice try batman';
     exit;
     }
     include 'dbcon.php';
include 'classes.php';

$resultgrow = $GLOBALS['pdo']->query('SELECT * FROM `growing`')->fetchAll(PDO::FETCH_ASSOC);

foreach($resultgrow as $line){
	$lost = floor(rand(0, $line['amount'] * 5));
	if ($lost != 0){
		$newamount = $line['cropamount'] - $lost;
		Send_Event($line['userid'], $lost." of your ".$line['croptype']." plants have died. Crop ID:".$line['id']);
	}
	
	$resultgrowupdate = $GLOBALS['pdo']->prepare('UPDATE `growing` SET `cropamount` = ? WHERE `id` = ?');
	$resultgrowupdate->execute(array($newamount, $line['id']));
}

//delete rows that are empty and give back land to owner
$resultgrow = $GLOBALS['pdo']->query('SELECT * FROM `growing`')->fetchAll(PDO::FETCH_ASSOC);
foreach($resultgrow as $line){
	if ($line['cropamount'] == 0){
		Give_Land($line['cityid'], $line['userid'], $line['amount']);
		$result = $GLOBALS['pdo']->prepare('DELETE FROM `growing` WHERE `id` = ?');
		$result->ececute(array($line['id']));
	}
}

//delete rows that are empty and give back land to owner
$resultgrow1 = $GLOBALS['pdo']->query('SELECT * FROM bans')->fetchAll(PDO::FETCH_ASSOC);
foreach($resultgrow1 as $line){
	if ($line['days'] > 1){
		$result = $GLOBALS['pdo']->prepare('UPDATE bans SET  days = days -1  WHERE `id` = ?');
		$result->execute(array($line['id']));
	}else{
	$result = $GLOBALS['pdo']->prepare('DELETE FROM bans WHERE `id` = ?');
		$result->execute(array($line['id']));
	}		
}


//$result2 = mysql_query("DELETE FROM `spylog` WHERE `age` < ".time() - 172800);// clear out old spy log stuff
$GLOBALS['pdo']->query('DELETE FROM `message`');

// Lottery Stuff
$checklotto = $GLOBALS['pdo']->query('SELECT * FROM `lottery`')->fetchAll(PDO::FETCH_ASSOC);
$numlotto = count($checklotto);

$amountlotto = $numlotto * 750;

$offset_result = $GLOBALS['pdo']->query('SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `lottery`');
$offset_row = $offset_result->fetch(PDO::FETCH_OBJ);

$offset = $offset_row->offset;

$worked = $GLOBALS['pdo']->query('SELECT * FROM `lottery` LIMIT '.((int)$offset.',1'));
if(!empty($worked)){
	$worked = $worked->fetch(PDO::FETCH_ASSOC);
	$winner = $worked['userid'];

	$lottery_user = new User($worked['userid']);
	$newmoney = $lottery_user->money + $amountlotto;
	Send_Event($lottery_user->id, "You won the lottery! Congratulations, you won $".$amountlotto);
}

$result2 = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
$result2->execute(array($newmoney, $lottery_user->id));

$GLOBALS['pdo']->query('DELETE FROM `lottery`');

// Lottery Stuff
$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers`')->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $line){
	$updates_user = new User($line['id']);
	$newmoney = $updates_user->money;
	$username = $updates_user->username;
	$newrmdays = $updates_user->rmdays - 1;
	$newrmdays = ($newrmdays < 0) ? 0 : $newrmdays;
	if($newrmdays > 1) {
		$interest = .04;
	} else {
		$interest = .02;
	}
	$newbank = ceil($updates_user->bank + ($updates_user->bank * $interest));
	if($updates_user->job != 0){
		$result_job = $GLOBALS['pdo']->prepare('SELECT * FROM `jobs` WHERE `id` = ?');
		$result_job->execute(array($updates_user->job));
		$worked_job = $result_job->fetch(PDO::FETCH_ASSOC);

		$newmoney = $newmoney + $worked_job['money'];
		Send_Event($updates_user->id, "You earned $".$worked_job['money']." from your job. You now have $".$newmoney);
	}

	// hooker stuff
	if($updates_user->hookers > 0){
		$newmoney = $newmoney + (300 * $updates_user->hookers);
		Send_Event($updates_user->id, "You earned $".($updates_user->hookers * 300)." from your hookers. You now have $".$newmoney);
	}
	//hooker stuff
	$result2 = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ?, `rmdays` = ?, `bank` = ?, `searchdowntown` = "100" WHERE `username` = ?');
	$result2->execute(array($newmoney, $newrmdays, $newbank, $username));
	$result2 = $GLOBALS['pdo']->prepare('TRANCATE rating');
	$result2->execute();

	}
?>
