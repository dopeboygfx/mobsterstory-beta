<?
include 'header.php';

if ($_POST['submit']) {
	if ($user_class->admin != 1) {
	  echo Message("You are not authorized to be here.");
	  include 'footer.php';
	  die();
	}

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `todo` (`when`, `text`, `status`)"."VALUES (?, ?, ?)');
	$result->execute(array($_POST['when'], $_POST['text'], $_POST['status']));
}
?>
<thead>
<tr>
<th>Updates</th>
</tr>
</thead>
<tr><td>Here you can view what we currently have in the works for GRPG.</td></tr>
<tr><td>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `todo`');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

echo "<table cellpadding='8'><tr><td><b>Date Added</b></td><td><b>Goal</b></td><td><b>Status</b></td></tr>";

foreach($result as $line){
	echo "<tr><td>".$line['when']."</td><td>".$line['text']."</td><td>[".$line['status']."]</td></tr>";
}
echo "</table>";
?>
</td></tr>
<?
if ($user_class->admin == 1){
?>
<thead>
<tr>
<th>Add Item</th>
</tr>
</thead>
<tr><td class="contentcontent">
<form method='post'>
<input type='text' name='when' size='10' maxlength='75' value='<?= $time ?>'> [When]<br />it ou
<input type='text' name='status'   size='10' maxlength='75' value='0%'> [Status]<br/>
<input type='submit' name='submit' value='Add Item'></td></tr>
</form>
</td></tr>
<?
}
include 'footer.php';
?>
