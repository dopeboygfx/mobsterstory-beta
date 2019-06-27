<?php
error_reporting(E_ALL);
include(__DIR__.'/header.php');

$_POST['id'] = isset($_POST['id']) && ctype_digit($_POST['id']) ? abs(intval($_POST['id'])) : null;
$_GET['action'] = isset($_GET['action']) && ctype_alpha($_GET['action']) ? strtolower(trim($_GET['action'])) : null;
switch($_GET['action']) {
	case 'invitedmembers':
		invited_members();
		break;
	case 'planningpage':
		planning_page();
		break;
	case 'begin':
		begin_plans();
		break;
}
if(isset($_POST['invite'])) {
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;
	}

	$res = $GLOBALS['pdo']->query('SELECT * FROM `heist_invites`');
	$row = array();
	if(!empty($res))
		$row = $res->fetch(PDO::FETCH_ASSOC);

	$res2 = $GLOBALS['pdo']->query('SELECT * FROM `planning`');
	$row2 = array();
	if(!empty($res2))
		$row2 = $res2->fetch(PDO::FETCH_ASSOC);

	$invited = new User($_POST['id']);
	if($row2['invited1'] == $invited->id) {
		echo Message(''.$invited->formattedname.' is already in the heist.');
		include(__DIR__.'/footer.php');
		exit;
	} else if($row2['invited2'] == $invited->id) {
		echo Message(''.$invited->formattedname.' is already in the heist.');
		include(__DIR__.'/footer.php');
		exit;
	} else if($row2['invited3'] == $invited->id) {
		echo Message(''.$invited->formattedname.' is already in the heist.');
		include(__DIR__.'/footer.php');
		exit;
	} else if($row2['invited4'] == $invited->id) {
		echo Message(''.$invited->formattedname.' is already in the heist.');
		include(__DIR__.'/footer.php');
		exit;
	} else if($row2['invited5'] == $invited->id) {
		echo Message(''.$invited->formattedname.' is already in the heist.');
		include(__DIR__.'/footer.php');
		exit; 
	} 
	if($row2['invited1'] && $row2['invited2'] && $row2['invited3'] && $row2['invited4'] && $row2['invited5']) {
		echo Message('The heist is full.');
		include(__DIR__.'/footer.php');
		exit;  
	}
	if($user_class->id == $_POST['id']) {
		echo Message('You cant invite yourself to a robbery');
		include('footer.php');
		exit;
	}
	if(empty($_POST['id'])) {
		echo Message('Invalid Id.');
	} else if($_POST['id'] != $row['invited_userid']) {
		echo Message(''.$invited->formattedname.' has been invited to the robbery');

		$query = $GLOBALS['pdo']->prepare('INSERT INTO `heist_invites` VALUES(NULL, ?, ?, ?)');
		$query->execute(array($_POST['id']. $row2['heist_name'], $row2['userid']));

		Send_Event($invited->id, "You have been invited to a heist <a href=\'plan.php?yes\'>Yes</a>|<a href=\'plan.php?no\'>No</a>.");
	} else {
		echo Message('This user has already been invited.');
	}	
}

if(isset($_GET['yes'])) {
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__ . '/footer.php');
		exit;
	}

	$res = $GLOBALS['pdo']->prepare('SELECT * FROM `heist_invites` WHERE `invited_userid` = ?');
	if(!$res->execute(array($user_class->id)))
		die('error');

	$row = $rws->fetch(PDO::FETCH_ASSOC);

	$invited = new User($row['invited_userid']);
	if(!mysql_num_rows($res)) {
		echo Message('Invalid input');
		include(__DIR__ . '/footer.php');
		exit;
	}

	$res2 = $GLOBALS['pdo']->prepare('SELECT * FROM `planning` WHERE `userid` = ?');
	if(!$res2->execute(array($row['owner'])))
		die('error');

	if(!mysql_num_rows($res2)) {
		echo Message("That heist is not currently in planning");
		include(__DIR__ . '/footer.php');
		exit;
	}

	$row2 = $res->fetch(PDO::FETCH_ASSOC);
	$owner = new User($row2['userid']);

	$res3 = $GLOBALS['pdo']->query('SELECT * FROM `heists`');
	if(empty($res3))
		die('error');

	$row3 = $res3->fetch(PDO::FETCH_ASSOC);

	if($invited->totalattrib < 100) {
		echo Message('You need to have a total of 100 in total stats before you can join a heist.');
		include(__DIR__ . '/footer.php');
		exit;
	} 
	$userpower = round($user_class->totalattrib / 100);
	if($row2['invited1'] && $row2['invited2'] && $row2['invited3'] && $row2['invited4'] && $row2['invited5']) {
		echo Message('This heist is full.<br /> <a href="index.php">Go home<a/>');
		include(__DIR__ . '/footer.php');
		exit;
	}	
	if($user_class->id == $row['invited_userid']){
		$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `heist_members` = `heist_members` + 1, `member_power` = `member_power` + ?');
		$result->execute(array($userpower));

		$result = $GLOBALS['pdo']->prepare('DELETE FROM `heist_invites` WHERE `invited_userid` = ?');
		$result->execute(array($invited->id));

		if(!$row2['invited1'])
			$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `invited1` = ?');
		else if(!$row2['invited2'])
			$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `invited2` = ?');
		else if(!$row2['invited3'])
			$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `invited3` = ?');
		else if(!$row2['invited4'])
			$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `invited4` = ?');
		else if(!$row2['invited5'])
			$result = $GLOBALS['pdo']->prepare('UPDATE `planning` SET `invited5` = ?');

		if($result !== (bool)$result){
			if(!$result->execute(array($invited->id)))
				die('error');
		}

		echo Message('You have joined the heist');
		include(__DIR__ . '/footer.php');
		exit;
	} else {
		echo Message('You are not part of a heist invite.');
		include(__DIR__ . '/footer.php');
		exit;
	}
}	

if(isset($_GET['no'])) {
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;
	}

	$res = $GLOBALS['pdo']->prepare('SELECT * FROM `heist_invites` WHERE `invited_userid` = ?');
	$res->execute(array($user_class->id));
	$row = $res->fetch(PDO::FETCH_ASSOC);

	if($user_class->id == $row['invited_userid']) {
		echo Message('You have declined the Heist Invite.');

		$res = $GLOBALS['pdo']->prepare('DELETE FROM `heist_invites` WHERE `invited_userid` = ?');
		$res->execute(array($user_class->id));
	}else{
		echo Message('You are not part of a heist invite.');
		include(__DIR__.'/footer.php');
		exit;
	}
}

function invited_members() {
	global $user_class;
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;
	}

	$res2 = $GLOBALS['pdo']->query('SELECT * FROM `planning`');
	$row2 = array();
	if(!empty($res2))
		$row2 = $res2->fetch(PDO::FETCH_ASSOC);

	if($row2['userid'] != $user_class->id) {
		echo Message('This page is for people with active heists.');
		exit;
	}
?>
<tr><td class="contenthead">Invite List</td></tr>
<tr><td class="contentcontent"> 
	<table width="75%"> 
		<tr style='background:gray'>		
			<th>Member</th>			
		</tr>		
		<?php 

		$res = $GLOBALS['pdo']->prepare('SELECT * FROM `heist_invites` WHERE `owner` = ?');
		$res->execute(array($user_class->id));
		$res = $res->fetchAll(PDO::FETCH_ASSOC);

		foreach($res as $row){
			$invites = new User($row['invited_userid']);
			echo "<tr> 
					<td>".$invites->formattedname."</td>
						</tr>		";
		}		 	
}	 	 
		?> 
		</table>	 
	</td></tr> 
<?php	

function planning_page() {
	global $user_class;
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;
	} 

	$res = $GLOBALS['pdo']->prepare('SELECT * FROM `planning` WHERE `userid` = ?');
	$res->execute(array($user_class->id));
	$row = $res->fetch(PDO::FETCH_ASSOC);

	if($row['userid'] != $user_class->id) {
		echo Message('You dont have any heists in planning.');
		include(__DIR__.'/footer.php');
		exit;
	}
	?>
	<thead>
	<tr><th colspan="4">Planning Page</th></tr>
	</thead>
						<tr>	
							<td>Heist</td>
							<td colspan="2">Members</td>
						</tr>
						<tr>
							<td><?php echo $row['heist_name'];?></td>
							<td><?php echo $row['heist_members'];?></td>
							<td><a href="plan.php?action=begin">Start Heist</a></td>
						</tr>
					</table>
				</td> 
			</tr>
		</table>	
	</td></tr>
	<tr><td class='contenthead'>Invite Section</td></tr>
	<tr><td class='contentcontent'>
		<form method="post"/>
			[Userid]<input type="text" name="id"/> 
			<input type="submit" name="invite" value="send invite">
		</form>
	</td></tr>
<?php
}	

function begin_plans() {
	global $user_class;
	
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;
	} ?> 
		<?php	

		$res = $GLOBALS['pdo']->query('SELECT * FROM `planning`');
		$row = array();
		if(!empty($res))
			$row = $res->fetch(PDO::FETCH_ASSOC);

		$invited = new User($row['invited1']);
		$invited2 = new User($row['invited2']);
		$invited3 = new User($row['invited3']);
		$invited4 = new User($row['invited4']);
		$invited5 = new User($row['invited5']);
		$owner = new User($row['userid']);

		$res2 = $GLOBALS['pdo']->query('SELECT * FROM `heists`');
		$row2 = array();
		if(!empty($res2))
			$row2 = $res2->fetch(PDO::FETCH_ASSOC);

		$itemcheck = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ?');
		$itemcheck->execute(array($owner->id));
		$done = $itemcheck->fetch(PDO::FETCH_ASSOC);

		$itemcheck = $GLOBALS['pdo']->prepare('SELECT `itemid` FROM `inventory` WHERE `userid` = ?');

		if($row['invited1'] != 0) {
			$itemcheck->execute(array($invited->id));
			$done2 = $itemcheck->fetch(PDO::FETCH_ASSOC);
		} else if($row['invited2'] != 0) {
			$itemcheck->execute(array($invited2->id));
			$done3 = $itemcheck->fetch(PDO::FETCH_ASSOC);
		} else if($row['invited3'] != 0) {
			$itemcheck->execute(array($invited3->id));
			$done4 = $itemcheck->fetch(PDO::FETCH_ASSOC);
		} else if($row['invited4'] != 0) {
			$itemcheck->execute(array($invited4->id));
			$done5 = $itemcheck->fetch(PDO::FETCH_ASSOC);
		} else if($row['invited5'] != 0) {
			$itemcheck->execute(array($invited5->id));
			$done6 = $itemcheck->fetch(PDO::FETCH_ASSOC);
		}
	
		/* Armor checks end */
		if($row['userid'] != $owner->id) {
			echo Message('Only the owner can start the robbery.');
			include(__DIR__.'/footer.php');
			exit;
		}
		if($row['heist_members'] < $row3['heist_members']) {
			echo Message('You dont have enough memebers');
			include(__DIR__.'/footer.php');
			exit;
		} 
		if($row['member_power'] >= $row2['heist_power']) {
			 $payout = rand($row2['minpayout'],$row2['maxpayout']);
			 echo Message('The heist was a success you earned $'.number_format($payout));

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = `money` + ?,`heist` = "1" WHERE `id` = ?');
			$result->execute(array($payout, $owner->id));

			$result2 = $GLOBALS['pdo']->prepare('DELETE FROM `planning` WHERE `userid` = ?');
			$result2->execute(array($owner->id));

			 if($row['invited1'] != 0) {
			 	$result->execute(array($payout, $invited->id));
			 	Send_Event($row['invited1'], "The heist is complete You earned $".number_format($payout));
			 } else if($row['invited2'] != 0) {
			 	$result->execute(array($payout, $invited2->id));
			 	Send_Event($row['invited2'], "The heist is complete You earned $".number_format($payout));
			 } else if($row['invited3'] != 0) {
			 	$result->execute(array($payout, $invited3->id));
			 	Send_Event($row['invited3'], "The heist is complete You earned $".number_format($payout));
			 } else if($row['invited4'] != 0) {
			 	$result->execute(array($payout, $invited4->id));
			 	Send_Event($row['invited4'], "The heist is complete You earned $".number_format($payout));
			 } else if($row['invited5'] != 0) {
			 	$result->execute(array($payout, $invited5->id));
			 	Send_Event($row['invited5'], "The heist is complete You earned $".number_format($payout));
			 }
		}		 
		else {
			$randommin = rand(1,2);
			$randommax = rand(2,4);
			$jailtime = mt_rand(600,2400);

			$result = $GLOBALS['pdo']->prepare('UPDATE `heists` SET `minpayout` = `minpayout` + ?,`maxpayout` = `maxpayout` + ? ');
			$result->execute(array($randommin, $randommax));

			/* ok time to send everyone to jail */ 
			echo Message('After all your planning you have failed the heist.');

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `jail` = ?, `heist` = "1" WHERE `id` = ?');
			$result->execute(array($jailtime, $owner->id));

			$result2 = $GLOBALS['pdo']->prepare('DELETE FROM `planning` WHERE `userid` = ?');
			$result2->execute(array($owner->id));

			if($row['invited1'] != 0) {
				$result->execute(array($jailtime, $invited->id));
			 	Send_Event($row['invited1'], "The heist failed you have been sent to jail.");
			 } else if($row['invited2'] != 0) {
				$result->execute(array($jailtime, $invited2->id));
			 	Send_Event($row['invited2'], "The heist failed you have been sent to jail.");
			 } else if($row['invited3'] != 0) {
				$result->execute(array($jailtime, $invited3->id));
			 	Send_Event($row['invited3'], "The heist failed you have been sent to jail.");
			 } else if($row['invited4'] != 0) {
				$result->execute(array($jailtime, $invited4->id));
			 	Send_Event($row['invited4'], "The heist failed you have been sent to jail.");
			 } else if($row['invited5'] != 0) {
				$result->execute(array($jailtime, $invited5->id));
			 	Send_Event($row['invited5'], "The heist failed you have been sent to jail.");
			 } 
		} 
}
		?>
<?php		
include(__DIR__.'/footer.php');
?>
