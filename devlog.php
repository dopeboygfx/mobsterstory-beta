<?php
error_reporting(E_ALL);
include(DIRNAME(__FILE__).'/header.php');
?>
			<thead>
			<tr><th>Developer Log</th></tr>
			</thead>

    <?php
    if($user_class->admin == 1){
			echo' ';
			echo "<form action='devlog.php' method='post'>
			<tr>
			<td width='15%'>Title: <input class='ui input focus' type='text' value='' name='title'> </td>
			</tr>
			<tr>
			<td width='15%'>Body: <textarea class='ui input focus' name='body' cols='60' rows='4' ></textarea></td>
			</tr>
			<tr>
			<td>Type:	<input class='ui input focus' type='text' value='' name='type'></td>
			</tr>
			<tr>
			<td>If Admin, 1. If Dev, 2:	<input class='ui input focus' type='text' value='' name='admin'></td>
			</tr>
			<tr>
			<td>
			<input class='ui mini red button' type='submit' name='submit' value='submit'>

			</td>
			</tr>
			</form>


			";

		if(isset($_POST['submit']) && $_POST['submit'] != NULL) {
			if (empty($_POST['title'])){
	echo Message("You can not enter a blank title");
}else{
			$query = $GLOBALS['pdo']->prepare('INSERT INTO `devlog` (`id`, `title`, `body`, `type`, `admin`) VALUES (NULL, ?, ?, ?, ?)');
			$query->execute(array($_POST['title'], $_POST['body'], $_POST['type'], $_POST['admin']));
			echo Message("Developer log updated.");
		}
}
		$result = $GLOBALS['pdo']->query('SELECT * from `devlog` WHERE admin = 1 ORDER BY `id` DESC');
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $row){
		?>

		<table class="inverted ui five unstackable column small compact table">
			<tr>
				<td width='15%'><b>Title</b>:</td>
				<td width='45%'><?= $row['title']; ?></td>
			</tr>
			<tr>
				<td width='15%'><b>Type</b>: </td>
				<td width='45%'><?= $row['type']; ?> </td>
				</tr>

			<tr>
				<td width='15%'><b>Update</b>: </td>
				<td width='75%' colspan='4'><?= $row['body'] ?></td>

			</tr>
		</table>

		<?php
		}
	}

	$result = $GLOBALS['pdo']->query('SELECT * from `devlog` WHERE admin = 0 ORDER BY `id`');
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $row){
		?>

		<table class="inverted ui five unstackable column small compact table">
			<tr>
				<td width='15%'><b>Title</b>:</td>
				<td width='45%'><?= $row['title']; ?></td>
			</tr>
			<tr>
				<td width='15%'><b>Type</b>: </td>
				<td width='45%'><?= $row['type']; ?> </td>
				</tr>

			<tr>
				<td width='15%'><b>Update</b>: </td>
				<td width='75%' colspan='4'><?= $row['body'] ?></td>

			</tr>
		</table>

<?
	}
?>


<?
include 'footer.php';
?>
