<?
include 'header.php';

if ($_GET['accept'] != ""){

	echo Message("You have accepted the relationship request.");
	$get2 = $GLOBALS['pdo']->prepare("SELECT * FROM `rel_requests` WHERE `id` = ?");
	$get2->execute(array($_GET['accept']));
	$get = $get2->fetch(PDO::FETCH_ASSOC);
	Send_Event($get['from'], $user_class->username ." has accepted your relationship request.", $user_class->id);
	$res1 = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `relationship` = '".$get['status']."', `relplayer` = ? WHERE `id` = ?");
	$res1->execute(array($get['from'], $user_class->id));
	$res1 = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `relationship` = '".$get['status']."', `relplayer` = ? WHERE `id` = ?");
	$res1->execute(array($user_class->id,$get['from']));
	$result = $GLOBALS['pdo']->prepare("DELETE FROM `rel_requests` WHERE `reqid` = ? AND `player` = ?");
	$result->execute(array($_GET['accept'], $user_class->id));

	}

if ($_GET['decline'] != ""){

	$get2 = $GLOBALS['pdo']->prepare("SELECT * FROM `rel_requests` WHERE `id` = ?");
	$get2->execute(array($_GET['accept']));
	$get = $get2->fetch(PDO::FETCH_ASSOC);
	Send_Event($get['from'], $user_class->username ." has declined your relationship request.", $user_class->id);
	echo Message("You have declined the request.");
	$result = $GLOBALS['pdo']->prepare("DELETE FROM `rel_requests` WHERE `reqid` = ? AND `player` = ?");
	$result->execute(array($_GET['decline'], $user_class->id));

}
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Relationship Requests</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<tr>
<td><b>Player</b></td>
<td><b>Type</b></td>
<td><b>Accept</b></td>
<td><b>Decline</b></td>
<td><b>Time Sent</b></td>
</tr>
<?

$result = $GLOBALS['pdo']->prepare("SELECT * FROM `rel_requests` WHERE `player` = ?");
$result->execute(array($user_class->id));
$result = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($result AS $line){
	$from = new User($line['from']);
	
	if($line['status'] == 1) {
		$type = "Dating";
	} else if($line['status'] == 2) {
		$type = "Engaged";
	} else if($line['status'] == 3) {
		$type = "Married";
	}

	echo "<tr><td width='30%'>".$from->formattedname."</td><td width='13.3%'>".$type."</td><td width='13.3%'><a href='rel_requests.php?accept=".$line['id']."'>Accept</a></td><td width='13.3%'><a href='rel_requests.php?decline=".$line['id']."'>Decline</a></td><td width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";

}
?>

</table>
</td>
</tr>

<?php
include 'footer.php';
?>