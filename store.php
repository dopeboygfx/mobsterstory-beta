<?
//*********************** The GRPG ***********************
//*$Id: store.php,v 1.3 2007/07/24 02:52:21 cvs Exp $*
//********************************************************

include 'header.php';

if (isset($_GET['buy'])) {

$resultnew = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ? AND `buyable` = "1"');
$resultnew->execute(array($_GET['buy']));
$worked = $resultnew->fetch(PDO::FETCH_ASSOC);

 if($worked['id'] != ""){
    if ($user_class->money >= $worked['cost']){
    $newmoney = $user_class->money - $worked['cost'];

	$newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
	$newsql->execute(array($newmoney, $user_class->id));

    Give_Item($_GET['buy'], $user_class->id);//give the user their item they bought
    echo Message("You have purchased a ".$worked['itemname']);
    } else {
    echo Message("You do not have enough money to buy a ".$worked['itemname']);
    }
  } else {
  echo Message("That isn't a real item.");
  }
}

$result = $GLOBALS['pdo']->query('SELECT * FROM `items`');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

$howmanyitems = 0;
foreach($result as $line){
		if ($line['offense'] > 0 && $line['buyable'] == 1){
		$weapons .= "
						<td width='25%'>
						<center>
						<img src='". $line['image']."' width='100' height='100' style='border: 0px solid #333333'>
						<br>
						<br>
						". item_popup($line['itemname'], $line['id']) ." 
						<br>
						Quanity: [x1]
						$". $line['cost'] ."
						<br>
						<br>
						<a class='ui mini red button' href='store.php?buy=".$line['id']."'>Buy</a>
						</center>
						</td>
		";
		$howmanyitems = $howmanyitems + 1;
			if ($howmanyitems == 3){
				$weapons.= "</tr><tr>";
				$howmanyitems = 0;
			}
		}
	}
if ($weapons != ""){
 ?>
	  <thead>
	  <tr>
	  <th>Weapons</th>
	  <th></th>
	  <th></th>
	  </tr>
	  </thead>
	  <tr>
	  <? echo $weapons; ?>
	  </tr>
	  </table>
	  </td></tr>
<?
}
include 'footer.php'
?>
