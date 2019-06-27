<?

//Updates

$updates_sql = $GLOBALS['pdo']->query('SELECT * FROM `updates` WHERE `name` = "trevor"');
if(!empty($updates_sql)){
	$updates_sql = $updates_sql->fetchAll(PDO::FETCH_ASSOC);

	foreach($updates_sql as $line){
		$update = $line['lastdone'];
	}

	$timesinceupdate = time() - $update;

	if ($timesinceupdate>=300) {

		$num_updates = floor($timesinceupdate / 300);

		//stock market stuff
		$result = $GLOBALS['pdo']->query('SELECT * FROM `stocks`');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $line){
			$amount = rand (strlen($line['cost']) * -1, strlen($line['cost']));
			$newamount = $line['cost'] + $amount;
			if ($newamount < 1){
				$newamount = 1;
			}

			$result2 = $GLOBALS['pdo']->prepare('UPDATE `stocks` SET `cost` = ? WHERE `id` = ?');
			$result2->execute(array($newamount, $line['id']));
		}
		
		//smuggle stuff	
		$smuggle = $GLOBALS['pdo']->query('SELECT * FROM `SmuggleItems`');
		$smuggle = $smuggle->fetchAll(PDO::FETCH_ASSOC);

		foreach($smuggle as $smuggleArr){
			$random_min = mt_rand(250, 550);
			$random_max = mt_rand(1000, 9999);
			$randomSmuggle1 = mt_rand($random_min, $random_max);
			$randomSmuggle2 = mt_rand($random_min, $random_max);
			$randomSmuggle3 = mt_rand($random_min, $random_max);
			$randomSmuggle4 = mt_rand($random_min, $random_max);
			$randomSmuggle5 = mt_rand($random_min, $random_max);

			$smuggleUpdate = $GLOBALS['pdo']->prepare('UPDATE `SmuggleItems` SET `sell1` = ?,`sell2` = ?, `sell3` = ? `sell4` = ?, `sell5` = ? WHERE `id` = ?');
			$smuggleUpdate->execute(array($randomSmuggle1, $randomSmuggle2, $randomSmuggle3, $randomSmuggle4, $randomSmuggle5, $smuggleArr['id']));
		}
		
		//scruffy drug cost here
		$smuggle2 = $GLOBALS['pdo']->query('SELECT * FROM `SmuggleItems`');
		$smuggle2 = $smuggle2->fetchAll(PDO::FETCH_ASSOC);
		foreach($smuggle2 as $smuggleArr2){
			if($smuggleArr2['location'] == 1) {
						$cost = $smuggleArr2['sell1'];
				}
				if($smuggleArr2['location'] == 2) {
							$cost = $smuggleArr2['sell2'];
				}
				if($smuggleArr2['location'] == 3) {
							$cost = $smuggleArr2['sell3'];
				}
				if($smuggleArr2['location'] == 4) {
							$cost = $smuggleArr2['sell4'];
				}
				if($smuggleArr2['location'] == 5) {
							$cost = $smuggleArr2['sell5'];
				}

				$smuggleUpdate2 = $GLOBALS['pdo']->prepare('UPDATE `SmuggleItems` SET `cost` = ? WHERE `id` = ?');
				$smuggleUpdate2->execute(array($cost, $smuggleArr2['id']));
		}
		//stock market stuff

		$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers`');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $line){
			$updates_user = new User($line['id']);

			if ($updates_user->rmdays > 0) {
				$multiplier = 2;
			} else {
				$multiplier = 1;
			}

			$username = $updates_user->username;
			$newawake = $updates_user->awake + (5 * $num_updates) * $multiplier;
			$newawake = ($newawake > $updates_user->maxawake) ? $updates_user->maxawake : $newawake;
			$newhp = $updates_user->hp + (10 * $num_updates) * $multiplier;
			$newhp = ($newhp > $updates_user->maxhp) ? $updates_user->maxhp : $newhp;
			$newenergy = $updates_user->energy + (2 * $num_updates) * $multiplier;
			$newenergy = ($newenergy > $updates_user->maxenergy) ? $updates_user->maxenergy : $newenergy;
			$newnerve = $updates_user->nerve + (2 * $num_updates) * $multiplier;
			$newnerve = ($newnerve > $updates_user->maxnerve) ? $updates_user->maxnerve : $newnerve;

			$result2 = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `awake` = ?, `energy` = ?, `nerve` = ?, `hp` = ? WHERE `username` = ?');
			$result2->execute(array($newawake, $newenergy, $newnerve, $newhp, $username));
		}

		//update the timer and db

		$thetime = time();

		$result2 = $GLOBALS['pdo']->prepare('UPDATE `updates` SET `lastdone` = ? WHERE `name` = "trevor"');
		$result2->execute(array($thetime));

		$leftovertime = $timesinceupdate - (floor($timesinceupdate / 300) * 300);
		if ($leftovertime>0) {
			$newupdate =  time() - $leftovertime;

			$setleftovertime = $GLOBALS['pdo']->prepare('UPDATE `updates` SET `lastdone` = ? WHERE `name` = "trevor"');
			$setleftovertime->execute(array($newupdate));
		}
	}
}


$updates_sql = $GLOBALS['pdo']->query('SELECT * FROM `updates` WHERE `name` = "hospital"');
if(!empty($updates_sql)){
	$updates_sql = $updates_sql->fetchAll(PDO::FETCH_ASSOC);

	foreach($updates_sql as $line){
		$update = $line['lastdone'];
	}

	$timesinceupdate = time() - $update;

	if($timesinceupdate>=60){
		$num_updates = floor($timesinceupdate / 60);

		$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers`');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		//DO STUFF

		foreach($result as $line){
			$result_user = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `id` = ?');
			$result_user->execute(array($line['id']));
			$updates_user = $result_user->fetch(PDO::FETCH_ASSOC);

			$newhospital = $updates_user['hospital'] - (60 * $num_updates);
			$newhospital = ($newhospital < 0) ? 0 : $newhospital;
			$newjail = $updates_user['jail'] - (60 * $num_updates);
			$newjail = ($newjail < 0) ? 0 : $newjail;

			$result2 = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `hospital` = ?, `jail` = ? WHERE `id` = ?');
			$result2->execute(array($newhospital, $newjail, $line['id']));
		}

		$result = $GLOBALS['pdo']->query('SELECT * FROM `effects`');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $line){
			if($line['timeleft'] > 0){
				$newamount = $line['timeleft'] - (1 * $num_updates);

				$result2 = $GLOBALS['pdo']->prepare('UPDATE `effects` SET `timeleft` = ? WHERE `id` = ?');
				$result2->execute(array($newamount, $line['id']));
			}
		}

		$GLOBALS['pdo']->query('DELETE FROM `effects` WHERE `timeleft` < 1');

		//update the timer and db

		$thetime = time();

		$result2 = $GLOBALS['pdo']->prepare('UPDATE `updates` SET `lastdone` = ? WHERE `name` = "hospital"');
		$result2->execute(array($thetime));

		$leftovertime = $timesinceupdate - (floor($timesinceupdate / 60) * 60);

		if($leftovertime>0){
			$newupdate =  time() - $leftovertime;

			$setleftovertime = $GLOBALS['pdo']->prepare('UPDATE `updates` SET `lastdone` = ? WHERE `name` = "hospital"');
			$setleftovertime->execute(array($newupdate));
		}
	}
}

?>
