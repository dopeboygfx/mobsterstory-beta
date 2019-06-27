<?php

include 'header.php';

if ($user_class->gang != 0) {
$gang_class = New Gang($user_class->gang);

if($_POST['deposit'] != ""){
$amount = abs(intval($_POST['damount']));

if ($amount > $user_class->money) {
echo Message("You do not have that much money.");
}

if ($amount < 1){
echo Message("Please enter a valid amount.");
}

if ($amount <= $user_class->money && $amount > 0) {
echo Message("Money deposited.");

$newvault = $amount + $gang_class->vault;
$newmoney = $user_class->money - $amount;

$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
$result->execute(array($newmoney, $_SESSION['id']));

$result = $GLOBALS['pdo']->prepare('UPDATE `gangs` SET `vault` = ? WHERE `id` = ?');
$result->execute(array($newvault, $gang_class->id));

$user_class = new User($_SESSION['id']);
$gang_class = New Gang($user_class->gang);
}
}

if($_POST['withdraw'] != "" && $gang_class->leader == $user_class->username){
$amount = abs(intval($_POST['wamount']));
if ($amount > $gang_class->vault) {
echo Message("You do not have that much money in the bank.");
}

if ($amount < 1){
echo Message("Please enter a valid amount.");
}

if ($amount <= $gang_class->vault && $amount > 0) {
echo Message("Money withdrawn.");

$newvault = $gang_class->vault - $amount;
$newmoney = $user_class->money + $amount;

$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id`= ?');
$result->execute(array($newmoney, $_SESSION['id']));

$result = $GLOBALS['pdo']->prepare('UPDATE `gangs` SET `vault` = ? WHERE `id` = ? ');
$result->execute(array($newvault, $gang_class->id));

$user_class = new User($_SESSION['id']);
$gang_class = New Gang($user_class->gang);
}
}

echo "
<thead>
<tr>
<td>[".$gang_class->tag."]".$gang_class->name." Vault</td>
</tr>
</head>
<tr><td>";

?>

Welcome to the gang vault. There is currently  $<? echo $gang_class->vault ?> in the gang vault.<br><br>
<?
if ($gang_class->leader == $user_class->username){

?>
<form method='post'><input class="ui input focus" type='text' name='wamount' value='<? echo $gang_class->vault ?>' size='10' maxlength='20'> &nbsp;
<input class="ui mini yellow button" type='submit' name='withdraw' value='Withdraw'></form><br><br>

<?

}

?>
<form method='post'><input class="ui input focus" type='text' name='damount' value='<? echo $user_class->money ?>' size='10' maxlength='20'> &nbsp;
<input class="ui mini green button" type='submit' name='deposit' value='Deposit'></form>
</td></tr>
<?

echo "<td><tr>";
} else {
echo Message("You aren't in a gang.");
}
include 'footer.php';

?>
