<?
include 'header.php';
if($user_class->gang == 0) {
	echo Message('You are not in a gang.');
}else{
if (isset($_POST['submit'])) {
 $error = ($_POST['message'] == "") ? "You need to have a message!" : $error;
 if($error == ""){
    $time = time();

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `gmail` VALUES(?, ?, ?, ?)');
	$result->execute(array($time, $user_class->id, $_POST['message'], $user_class->gang));

    echo Message("You have posted a gang message.");
  } else {
  echo Message($error);
  }
}
?>
	<thead>
    <tr>
	<th width='25%'>Gang Mail</th>
    <th></th>
    <th></th>
    <th></th>
  	</tr>
  	</thead>
	<form method='post'>
		<tr>
		<td width='25%'>Message:</td>
		<td width='25%'>
		<textarea  class="ui input focus" name='message' cols='60' rows='4' ></textarea>
		</td>
		</tr>
		<tr>
		<td width='25%'></td>
		<td width='25%'>
		<input type='submit' class='ui mini red button'name='submit' value='Post'>
		</td>
		</tr>
</form>
</td></tr>

<?

$result = $GLOBALS['pdo']->prepare('SELECT * from `gmail` WHERE gid = ? ORDER BY `when` DESC LIMIT 25');
$result->execute(array($user_class->gang));
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){
$user_ads = New User($row['poster']);
?>
	<tr><td>
	<tr>
	<td width='15%'><b>Poster</b>:</td>
	<td width='45%'><?= $user_ads->formattedname ?></td>
	</tr>
	<tr>
	<td width='100%' colspan='4'><?= $row['message'] ?></td>
	</tr>
	<?
}
?>

</table>


<?
}
include 'footer.php';
?>