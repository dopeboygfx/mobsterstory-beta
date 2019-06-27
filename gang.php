<?
include 'header.php';

if ($user_class->gang == 0){
	echo Message("You are not in a gang.");
	include 'footer.php';
	die();
}

$gang_class = New Gang($user_class->gang);

if ($_GET['action'] == "leave"){
	if ($gang_class->leader != $user_class->username){
		$newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `gang` = "0" WHERE `id` = ?');
		$newsql->execute(array($user_class->id));
		echo Message("You have left your gang.");
	} else {
		echo Message("You can't leave a gang if you are a leader.");
	}
}

$nmembers = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `id` = ?');
$nmembers->execute(array($gang_class->id));
$num_mem = count($nmembers->fetchAll(PDO::FETCH_ASSOC));

$ghouse2 = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
$ghouse2->execute(array($gang_class->house));
$ghouse_run = $ghouse2->fetch(PDO::FETCH_ASSOC);
?>

<thead>
	<tr>
		<th>Your Gang</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
</thead>
<tr><td><?echo htmlentities($gang_class->description); ?>Desc.</td></tr>
<tr>
	<td width='33%' align='center'><? echo "[". $gang_class->tag . "]" . $gang_class->name; ?></td>
	<td width='33%' align='center'>Level: <? echo $gang_class->level; ?></td>
	<td width='33%' align='center'>EXP: <? echo $gang_class->exp; ?></td>
</tr>
<tr>
	<td width='25%' align='center'>Members: <? echo $num_mem; ?></td>
	<td width='50%' align='center'>House: <? echo $ghouse_run['name']; ?> [<? echo $ghouse_run['bonus_awake']; ?>]</td>
	<td width='25%' align='center'>Heists: WIP</td>
</tr>
<tr>
	<td width='50%' align='center'>Money: <? echo $gang_class->vault; ?></td>
	<td width='50%' align='center'>Points: <? echo $gang_class->points; ?></td>
</tr>
</table>

<?
if ($gang_class->leader == $user_class->username){
	?>
	<table class="inverted ui five unstackable column small compact table">
		<thead>
			<tr>
				<th>Gang Management</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tr>
			<td width='33%' align='center'><a href='invite.php'>Invite Player</a></td>
			<td width='33%' align='center'><a href='managegang.php'>Manage Gang Members</a></td>
			<td width='33%' align='center'><a href='changedesc.php'>Change Gang Message</a></td>
		</tr>
	</table>
	<?
}
?>

<table class="inverted ui five unstackable column small compact table">
	<thead>
		<tr>
			<th>Gang Actions</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tr>
		<td width='25%' align='center'><a href='viewgang.php?id=<?= $gang_class->id ?>'>View Gang</a></td>
		<td width='25%' align='center'><a href='gang.php?action=leave'>Leave Gang</a></td>
		<td width='25%' align='center'><a href='ganglog.php'>Defense Log</a></td>
		<td width='25%' align='center'><a href='gangvault.php'>Vault</a></td>
	</tr>
	<tr>
		<td width='25%' align='center'><a href='gangarmory.php'>Armory</a></td>
		<td width='25%' align='center'><a href='plan.php?action=planningpage'>Planning Page</a>
			<td width='25%' align='center'><a href='plan.php?action=invitedmembers'>Invited Players</a>
				<td width='25%' align='center'><a href='gangpoints.php'>Points Vault</a></td>
			</tr>
		</table>

		<table class="inverted ui five unstackable column small compact table">
			<thead>
				<tr>
					<th>Gang Heist</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
				<td width='33%' align='center'><a href='heists.php'>Heists</a>
					<td width='33%' align='center'><a href='plan.php?action=planningpage'>Planning Page</a>
						<td width='33%' align='center'><a href='plan.php?action=invitedmembers'>Invited Players</a>
						</tr>
					</table>

					<table class="inverted ui five unstackable column small compact table">
						<thead>
							<tr>
								<th>Gang House</th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tr>
							<td width='33%' align='center'><a href='ganghouse.php'> Gang House </a></td>
							<td width='33%' align='center'><? echo $gang_class->bonus_awake ?></td>
						</tr>
					</table>
				</td></tr>
				<?
				include 'footer.php';
				?>
