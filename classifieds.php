<?
include 'header.php';
if (isset($_POST['submit'])) {
 $cost = (strlen($_POST['title']) + strlen($_POST['message'])) * 50;
 $error = ($cost > $user_class->money) ? "You don't have enough money for that!" : $error;
 $error = ($_POST['title'] == "") ? "You need to have a title!" :  $error;
 $error = ($_POST['message'] == "") ? "You need to have a message!" : $error;
 if($error == ""){
    $newmoney = $user_class->money - $cost;
    $time = time();

	$newsql = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
	$newsql->execute(array($newmoney, $user_class->id));

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `ads` VALUES(?, ?, ?, ?)');
	$result->execute(array($time, $user_class->id, $_POST['title'], $_POST['message']));

    echo Message("You have posted a classified ad for $".$cost);
  } else {
  echo Message($error);
  }
}
?>
 	<thead>
    <tr><th>Classified Ads</th>
  	</tr>
  	</thead>
<tr>
<td>
Here you can post any thing your heart desires. Careful though, as it costs $50 per character in the title and in the message.
</td>
</tr>
<tr>
<td>
<form method='post'>
<table class="inverted ui five unstackable column small compact table">
	<tr>
		<td width='25%'>Title:</td>
		<td width='25%'>
		<input class="ui input focus" type='text' name='title'  size='40' maxlength='100'>
		</td>
	</tr>

	<tr>

		<td width='25%'>Message:</td>
		<td width='25%'>
		<textarea class="ui input focus" name='message' cols='60' rows='4' ></textarea>
		</td>
	</tr>

	<tr>
		<td width='25%'></td>
		<td width='25%'>
		<input type='submit' class='ui mini red button'name='submit' value='Post'>
		</td>
	</tr>
</table>
</form>
</td></tr>

<?

$result = $GLOBALS['pdo']->query("SELECT * from `ads` ORDER BY `when` DESC LIMIT 10");
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){
$user_ads = New User($row['poster']);
?>
<tr><td>
<table class="inverted ui five unstackable column small compact table">
	<tr>
		<td width='15%'><b>Title</b>:</td>
		<td width='45%'><?= $row['title']; ?></td>
		<td width='15%'><b>Poster</b>:</td>
		<td width='45%'><?= $user_ads->formattedname ?></td>
	</tr>

	<tr>
		<td width='100%' colspan='4'><?= $row['message'] ?></td>

	</tr>
</table>
</td></tr>
<?
}
?>


<?
include 'footer.php';
?>
