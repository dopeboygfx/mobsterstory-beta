<?
//*********************** The GRPG ***********************
//*$Id: events.php,v 1.2 2007/07/22 07:40:50 cvs Exp $*
//********Remasted X Semantics UI - Dopeboy (TwoLucky LLC)************

include 'header.php';

if ($_GET['deleteall'] != ""){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `events` WHERE `to` = ?');
	$result->execute(array($user_class->id));
	echo Message("All your events have been deleted.");
}

$result2 = $GLOBALS['pdo']->prepare('UPDATE `events` SET `viewed` = "2" WHERE `to` = ?');
$result2->execute(array($user_class->id));

if ($_POST['delete'] != ""){
	$result = $GLOBALS['pdo']->prepare('DELETE FROM `events` WHERE `id` = ?');
	$result->execute(array($_POST['event_id']));
	echo Message("Event Deleted!");
}
?>
<thead>
	<tr>
		<th>Event Log</th>
	</tr>
</thead>
<tr>
	<td><a href='events.php?deleteall=true'>Delete All My Events</a></td>
	<?

	$result = $GLOBALS['pdo']->query('SELECT * from `events` ORDER BY `timesent` DESC');
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $row){
		if($row['to'] == $user_class->id){
			echo "
		  <tr><td>Recieved: ".date(F." ".d.", ".Y." ".g.":".i.":".sa,$row['timesent'])."</td></tr>
			<tr><td>Event:&nbsp;&nbsp;".wordwrap($row['text'], 100, "\n", 1)."</td></tr>
			<form method='post'><input type='hidden' name='event_id' value='".$row['id']."'>
			<tr><td><input type='submit' class=' ui mini red button'name='delete' value='Delete'></td></tr>
			</form>
			";
		}
	}
	?>
</table>
<?

include 'footer.php';
?>
