<?
include 'header.php';

if($_POST['create'] != ""){ // if they are wanting to start a new gang
	$error .= ($user_class->money < 250000) ? "<div>You don't have enough money to start a gang. You need at least ".'$250,000</div>' : $error;
	$error .= ($user_class->gang != 0) ? "<div>You have to leave your gang to start a new gang.</div>" : "";
	$error .= (strlen($_POST['name']) < 3) ? "<div>Your Gang's Name has to be at least 3 characters long.</div>" : "";
	$error .= (strlen($_POST['name']) > 21) ? "<div>Your Gang's Name can only be a max of 20 characters long.</div>" : "";
	$error .= (strlen($_POST['tag']) < 1) ? "<div>Your Gang's Name has to be at least 1 character long.</div>" : "";
	$error .= (strlen($_POST['tag']) > 3) ? "<div>Your Gang's Name can only be a max of 3 characters long.</div>" : "";
	//check if name is taken yet

	$check = $GLOBALS['pdo']->prepare('SELECT * FROM `gangs` WHERE `name` = ?');
	$check->execute(array($_POST['name']));
	$exist = count($check->fetchAll(PDO::FETCH_NUM));

	$error .= ($exist > 0) ? "<div>The Gang Name you chose is already taken.</div>" : "";
	//check if tag is taken yet

	$check = $GLOBALS['pdo']->prepare('SELECT * FROM `gangs` WHERE `tag` = ?');
	$check->execute(array($_POST['tag']));
	$exist = count($check->fetchAll(PDO::FETCH_NUM));

	$error .= ($exist > 0) ? "<div>The tag you chose is already taken.</div>" : "";

	if($error == ""){ // if there are no errors, make the gang
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `gangs` (name, tag, leader) VALUES (?, ?, ?)');
		$result->execute(array($_POST['name'], $_POST['tag'], $user_class->username));

		$newmoney = $user_class->money - 50000; //deduct the cost of the money

		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `gangs` WHERE `leader` = ?');
		$result->execute(array($user_class->username));
		$worked = $result->fetch(PDO::FETCH_ASSOC);

		$gangid = $worked['id'];

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `gang` = ?, `money` = ? WHERE `id` = ?');
		$result->execute(array($gangid, $newmoney, $_SESSION['id']));

		$user_class = new User($_SESSION['id']);
		echo Message("You have successfully created a gang!");
    } else {
    	echo Message($error);
    }
}

if ($user_class->gang == 0) {
?>
<thead>
<tr>
<th>Create Gang</th>
</tr>
</thead>
<tr><td>
		<form method='post'>
		Well, it looks like you haven't join or created a gang yet.<br><br>
		To create a gang it costs $250,000. If you don't have enough, or would like to join someone elses gang, check out the <a href="gang_list.php">Gang List</a> for other gangs to join.<br><br>
		<table width='100%'>

			<tr>
				<td width='15%'>Gang Name:</td>
				<td width='35%'><input class="ui input focus" type='text' name='name' value='' maxlength='20' size='16'></td>
				<td width='15%'>Gang Tag</td>
				<td width='35%'><input class="ui input focus" type='text' name='tag' value='' maxlength='3' size='4'></td>
			</tr>
			<tr>
				<td colspan='4' align='center'><input class="ui mini yellow button" type='submit' name='create' value='Create'></td>

			</tr>
		</table>
		</form>
</td></tr>
<?
}

include 'footer.php';
?>
