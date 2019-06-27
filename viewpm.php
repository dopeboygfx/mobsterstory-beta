<?
include 'header.php';
?>
	  <thead>
	  <tr><th>Mailbox</th></tr>
	  </thead>
	  <form method='post'>
<tr><td>
<table class="inverted ui unstackable column small compact table">
<?

$result = $GLOBALS['pdo']->prepare('SELECT * FROM `pms` WHERE `id` = ?');
$result->execute(array($_GET['id']));
$row = $result->fetch(PDO::FETCH_ASSOC);

$from_user_class = new User($row['from']);
if ($_GET['id'] != ""){
    if ($row['to'] == $user_class->id) {
    echo "
						<tr>
							<td>Subject:</td>
							<td>".$row['subject']."</td>
							<td>Sender:</td>
							<td>".$from_user_class->formattedname."</td>
						</tr>
						<tr>
							<td>Recieved:</td>
							<td>".date(F." ".d.", ".Y." ".g.":".i.":".sa,$row['timesent'])."</td>
						</tr>
						<tr>

							<td colspan='3'>Message:<br><br>".wordwrap($row['msgtext'], 100, "\n", 1)."
					    	</td>
						</tr>
						<tr>
						<td><a href='pms.php?delete=".$row['id']."'>Delete</a> | <a href='pms.php?reply=".$from_user_class->id."&id=".$_GET['id']."'>Reply</a></td>
						</tr>
						<tr>
							<td><a href='pms.php'>Back To Mailbox</a></td>
						</tr>

";
	$result2 = $GLOBALS['pdo']->prepare('UPDATE `pms` SET `viewed` = "2" WHERE `id` = ?');
	$result2->execute(array($row['id']));

	}
}
?>

</table>
</td></tr>
<?
include 'footer.php';
?>
