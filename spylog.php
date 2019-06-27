<?
include 'header.php';
?>
 <thead>
    <tr>
	<th>Spy Log</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
  	</tr>
 </thead>
<tr>
	<td>Username</td>
	<td>Strength</td>
	<td>Defense</td>
	<td>Speed</td>
	<td>Bank</td>
	<td>Points</td>
</tr>
<?

$result = $GLOBALS['pdo']->prepare('SELECT * from `spylog` WHERE `id` = ? ORDER BY age DESC LIMIT 0,25');
$result->execute(array($user_class->id));
$result = $result->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $line){
	if ($line['defense'] == -1){
		$line['defense'] = "Failed";
	}
	if ($line['speed'] == -1){
		$line['speed'] = "Failed";
	}
	if ($line['bank'] == -1){
		$line['bank'] = "Failed";
	}
	if ($line['strength'] == -1){
		$line['strength'] = "Failed";
	}
	if ($line['points'] == -1){
		$line['points'] = "Failed";
	}

	$profile_class = new User($line['spyid']);
	$out .= " <tr> <td>". $profile_class->formattedname ."</td> <td>" .prettynum($line['strength']). "</td> <td>" .prettynum($line['defense']). "</td> <td>" .prettynum($line['speed']). "</td> <td>".prettynum($line['bank'],1)."</td> <td>".prettynum($line['points'])."</td> </tr>";

}
echo $out;

include 'footer.php';
?>


	
