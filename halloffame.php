<?
include 'header.php';
?>
 	<thead>
    <tr>
	<th>Hall of Fame</th>
  	</tr>
  	</thead>
<tr><td><center><a href="halloffame.php?view=exp">Rank</a> | <a href="halloffame.php?view=strength">Strength</a> | <a href="halloffame.php?view=defense">Defense</a> | <a href="halloffame.php?view=speed">Speed</a> | <a href="halloffame.php?view=money">Money</a> | <a href="halloffame.php?view=points">Points</a></center></td></tr>
<tr><td>
<table class="inverted ui five unstackable column small compact table">
<tr>
	<td>Rank</td>
	<td>Mobster</td>
	<td>Level</td>
	<td>Money</td>
	<td>Gang</td>

	<td align='center'>Online</td>
</tr>
<?
$view = ($_GET['view'] != "") ? $_GET['view'] : 'exp';

if(
	$view !== 'exp'
 && $view !== 'strength'
 && $view !== 'defense'
 && $view !== 'speed'
 && $view !== 'money'
 && $view !== 'points'
){
	$view = 'exp';
}

$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `'.$view.'` DESC LIMIT 50');
$result = $result->fetchAll(PDO::FETCH_ASSOC);
$rank = 0;

foreach($result as $line){
	$rank++;
	$user_hall = new User($line['id']);
	?>
	<tr>
			<td><?= $rank ?></td>
			<td><?= $user_hall->formattedname ?></td>
			<td><?= $user_hall->level ?></td>
			<td>$<?= $user_hall->money ?></td>
			<td><?= $user_hall->formattedgang ?></td>
			<td><?= $user_hall->formattedonline ?></td>
	</tr>
	<?
}
?>

</td></tr>
<?
include 'footer.php';
?>
