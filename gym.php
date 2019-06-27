<?
include 'header.php';

if($user_class->hospital > 0){
	echo Message("You can't train at the gym if you are in the hospital.");
	include 'footer.php';
	exit;
}

if ($_POST['train'] != "") {
$_POST['energy'] = abs(intval($_POST['energy']));
 if ($_POST['energy'] > $user_class->energy){
  echo Message("You don't have that much energy. <a href='gym.php' /> Back </a>");
  include "footer.php";
  exit;
}

if ($_POST['energy'] < 1){
  echo Message("Please enter a valid amount.");
  include "footer.php";
  die();
}
	if($_POST['type'] == 1){ // strength
		$newstrength = $user_class->strength + floor($_POST['energy'] * ($user_class->awake / 100));

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `strength` = ? WHERE `id` = ?');
		$result->execute(array($newstrength, $user_class->id));

		echo Message("You trained with ".$_POST['energy']." energy and recieved ".floor($_POST['energy'] * ($user_class->awake / 100))." strength.");
	}elseif($_POST['type'] == 2){ // defense
		$newdefense = $user_class->defense + floor($_POST['energy'] * ($user_class->awake / 100));

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `defense` = ? WHERE `id` = ?');
		$result->execute(array($newdefense, $user_class->id));

		echo Message("You trained with ".$_POST['energy']." energy and recieved ".floor($_POST['energy'] * ($user_class->awake / 100))." defense.");
	}elseif($_POST['type'] == 3){ // speed
		$newspeed = $user_class->speed + floor($_POST['energy'] * ($user_class->awake / 100));

		$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `speed` = ? WHERE `id` = ?');
		$result->execute(array($newspeed, $user_class->id));

		echo Message("You trained with ".$_POST['energy']." energy and recieved ".floor($_POST['energy'] * ($user_class->awake / 100))." speed.");
	}

$newawake = $user_class->awake - (2 * $_POST['energy']);
if ($newawake <0 ){
	$newawake = 0;
}
$newenergy = $user_class->energy - $_POST['energy'];

$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `awake` = ?, `energy` = ? WHERE `id` = ?');
$result->execute(array($newawake, $newenergy, $user_class->id));

$user_class = new User($_SESSION['id']);
}
?>
	<thead>
    <tr>
	<th>Gym</th>
	<th style="width: 50%"></th>
  	</tr>
  	</thead>
	<form method='post'>
	<tr><td>
	You can currently train <?php echo $user_class->energy; ?> times.</td></tr>
	<tr><td>
	<input class="ui input focus" type='text' name='energy' value='<?php echo $user_class->energy ?>' size='5' maxlength='5'>&nbsp;
	<br>
	<br>
	<div class="ui form">
	<select class="field" name='type'>
		<option value='1'>Strength</option>
		<option value='2'>Defense</option>
		<option value='3'>Speed</option>
	</select>
	</div>
	<br>
	<br>
	<input type='submit' name='train' class='ui mini blue button' value='Train'><br>
	</form>
	<br>
	</tr>
	</td>
	</tr>
	</table>
	<table class="inverted ui unstackable column small compact table">
	<thead>
    <tr><th>Attributes</th>
    <th></th>
    <th></th>
    <th></th>
  	</tr></thead>
	<tr>
		<td width='15%'>Strength:</td>
		<td width='35%'><?php echo $user_class->strength; ?></td>
		<td width='15%'>Defense:</td>
		<td width='35%'><?php echo $user_class->defense; ?></td>
	</tr>
	<tr>
		<td width='15%'>Speed:</td>
		<td width='35%'><?php echo $user_class->speed; ?></td>
		<td width='15%'>Total:</td>
		<td width='35%'><?php echo $user_class->totalattrib; ?></td>
	</tr>
	</table>
	</td></tr>
<?
include 'footer.php';
?>
