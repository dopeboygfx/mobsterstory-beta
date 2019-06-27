<?
include 'header.php';
?>
<thead>
<tr>
<th>Your Garage</th>
</tr>
</thead>
<tr><td>Here is where you keep all of your sweet rides.</td></tr>
<?
$result = $GLOBALS['pdo']->prepare('SELECT * FROM `cars` WHERE `userid` = ? ORDER BY `userid` DESC');
$result->execute(array($user_class->id));
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `carlot` WHERE `id` = ?');
	$result2->execute(array($line['carid']));
	$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		$cars .= "

		<td width='25%' align='center'>

		<img src='". $worked2['image']."' width='100' height='100' style='border: 1px solid #333333'><br>
		". car_popup($worked2['name'], $line['carid']) ."
		</td>
		";
	}


if ($cars != ""){
 ?>
 <thead>
 <tr>
 <th></th>
 </tr>
 </thead>
<tr><td>
<table width='100%'>
				<tr>
				<? echo $cars; ?>
				</tr>
			</table>
</td></tr>
<?
}
include 'footer.php'
?>
