<?
error_reporting(E_ALL);
include 'header.php';
$_GET['id'] = abs(intval($_GET['id']));
if ($_GET['unequip'] == "weapon" && $user_class->eqweapon != 0){
	Give_Item($user_class->eqweapon, $user_class->id);

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqweapon` = "0" WHERE `id` = ?');
	$result->execute(array($_SESSION['id']));

	echo Message("You have unequipped your weapon.");
	mrefresh("inventory.php");
	include 'footer.php';
	die();
}
if ($_GET['unequip'] == "offhand" && $user_class->eqoffhand != 0){
	Give_Item($user_class->eqoffhand, $user_class->id);

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqoffhand` = "0", storage = "0" WHERE `id` = ?');
	$result->execute(array($_SESSION['id']));

	$scruffy_result = $GLOBALS['pdo']->prepare('DELETE FROM `drugstorage` WHERE `userid` = ?');
	$scruffy_result->execute(array($_SESSION['id']));

	echo Message("You have unequipped your off hand weapon. All drugs stored in your offhand have been dumped.");
	mrefresh("inventory.php");
	include 'footer.php';
	die();
}

if ($_GET['unequip'] == "armor" && $user_class->eqarmor != 0){
	Give_Item($user_class->eqarmor, $user_class->id);

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqarmor` = "0" WHERE `id` = ?');
	$result->execute(array($_SESSION['id']));

	echo Message("You have unequipped your armor.");
	mrefresh("inventory.php");
	include 'footer.php';
	die();
}	

if ($_GET['id'] == ""){
	echo Message("No item picked.");
	include 'footer.php';
	die();
}	

$howmany = Check_Item($_GET['id'], $user_class->id);//check how many they have

$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
$result2->execute(array($_GET['id']));
$worked = $result2->fetch(PDO::FETCH_ASSOC);

$error = ($howmany == 0) ? "You don't have any of those." : $error;
$error = ($worked['level'] > $user_class->level) ? "You aren't high enough level to use this." : $error;
	
if (isset($error)){
	echo Message($error);
	include 'footer.php';
	die();
}

Take_Item($_GET['id'], $user_class->id);

$runItem = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ?');
$runItem->execute(array($_GET['id']));
$ria = $runItem->fetch(PDO::FETCH_ASSOC);

if ($_GET['eq'] == "weapon" && $ria['itemtype'] == 1){
	if($user_class->eqweapon != 0){
		Give_Item($user_class->eqweapon, $user_class->id);
	}

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqweapon` = ? WHERE `id` = ?');
	$result->execute(array($_GET['id'], $_SESSION['id']));

	echo Message("You have succesfully equipped a weapon.");
	mrefresh("inventory.php");
}
if ($_GET['eq'] == "armor" && $ria['itemtype'] == 2){
	if($user_class->eqarmor != 0){
		Give_Item($user_class->eqarmor, $user_class->id);
	}

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqarmor` = ? WHERE `id` = ?');
	$result->execute(array($_GET['id'], $_SESSION['id']));

	echo Message("You have succesfully equipped armor.");
	mrefresh("inventory.php");
}

if ($_GET['eq'] == "offhand" && $ria['itemtype'] == 4){
	if($user_class->eqoffhand != 0){
		Give_Item($user_class->eqoffhand, $user_class->id);
	}

	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `eqoffhand` = ?, storage = ? WHERE `id` = ?');
	$result->execute(array($_GET['id'], $ria['storage'], $_SESSION['id']));

	echo Message("You have succesfully equipped an off hand weapon.");
	mrefresh("inventory.php");
}

include 'footer.php';
?>
