<?php
error_reporting(E_ALL);
include 'header.php';

//GET = get a prperty from the URL (travel)
//check if it is set
if(isset($_GET['travel']) && $_GET['travel'] != "") {
$_GET['travel'] = abs(intval($_GET['travel']));
if($user_class->money< 501){
echo Message("You do not have enough money for the bus");
include('footer.php');
exit;
}
//check if user is in same city as GET['travel']
if($user_class->city == $_GET['travel']) {
//if the user is in the same city, call them stupid and exit.
echo Message("You're already in this city. Stupid.");
// exit; = stop script from running past this point.
include('footer.php');
exit;
}
//else IE: if the users city is different do this:
else {
//update the database with new city where the user id is who pressed the button.
$update = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `city` = ?, money = money - 500 WHERE `id` = ?');
$update->execute(array($_GET['travel'], $user_class->id));
echo Message("You have traveled by bus and have arrived at your destination");
//do we charge them money? no idea.. its your game
//check if they are carrying drugs
$checkDrugs = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ?');
$checkDrugs->execute(array($user_class->id));

//count the rows return from $checkDrugs
$countDrugs = count($checkDrugs->fetchAll(PDO::FETCH_NUM));

//check they are rows. 
if($countDrugs > 0) {
//if they have drugs on them.
$rand = mt_rand(1,100);
//$rand = 5;
if($rand > 65) {
//send user to jail! for 20mins
//update database here
$update = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `jail` = 1200 WHERE `id` = ?');
$update->execute(array($user_class->id));
echo Message("You have been caught smuggling items. you have been sent to jail for 20 minutes.");
$removedrugs = $update = $GLOBALS['pdo']->prepare('DELETE FROM `drugstorage` WHERE `userid` = ?');
$removedrugs->execute(array($user_class->id));
}
else {
//sell drugs
echo Message("Remember you got some drugs you can sell in the city. Just be careful there's cops!");
}
}

}
} else {


?>
<thead>
<tr>
<th>Bus Station</th>
</tr>
</thead>
<tr>
<td>
<center>
<img src="../images/bust.png"></div><br>
Tired of <?= $user_class->cityname ?>? You're more than welcome to travel to a new city at a cost.	
</center>
</td>
</tr>
</tr>
<?

$result = $GLOBALS['pdo']->query('SELECT * FROM `cities` ORDER BY `levelreq` ASC');
echo '
<tr><td>';
foreach($result->fetchAll(PDO::FETCH_ASSOC) as $line){
echo "
<div class='ui inverted segment'>
  <p>
<div>".$line['name'] . " - Level Required: ".$line['levelreq']." <a href='bus.php?travel=".$line['id']."'>Buy Ticket</a></div>
</p>
</div>";
}
echo '</td></tr>';
}
include 'footer.php'
?>