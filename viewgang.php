<?
include 'header.php';
$_GET['id'] = abs(intval($_GET['id']));
if($_GET['id'] != ""){ // if there is an ID for the gang
		//display that gangs stuff
		$gang_class = New Gang($_GET['id']);
		if($gang_class->id == ""){
			echo Message("Non existant gang.");
			include 'footer.php';
			die();
		}
?>
	 <thead>
	    <tr>
		<th>Gang Details</th>		
		<th> </th>
		<th> </th>
		<th> </th>
  		</tr>
  		</thead>
		<tr>
			<td><b>Name: <?php echo "[". $gang_class->tag . "]".$gang_class->formattedname; ?></td>
			<td><b>Level: <?php echo $gang_class->level; ?></b></td>
			<td><b>Exp: <?php echo $gang_class->exp; ?></td>
			<td>Apply For Gang</td>
		</tr>
		</table>
		<table class="inverted ui five unstackable column small compact table">
		<thead>
	    <tr>
		<th><? echo "[". $gang_class->tag . "]" . $gang_class->name; ?></th>
	    <th></th>
	    <th></th>
	    <th></th>
  		</tr>
  		</thead>
			<tr>
				<td>Rank</td>
				<td>Mobster</td>
				<td>Level</td>
				<td>Status</td>
			</tr>
		<?php

		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `gang` = ? ORDER BY `exp` DESC');
		$result->execute(array($_GET['id']));
		$result = $result->fetchAll(PDO::FETCH_ASSOC);
		$rank = 0;

		foreach($result as $line){
				$gang_member = new User($line['id']);
				$rank = $rank +1;
				?>
				<tr>
				<td><?= $rank; ?></td>
				<td><?= $gang_member->formattedname; ?></td>
				<td><?= $gang_member->level; ?></td>
				<td><?= $gang_member->formattedonline; ?></td>
		<?
		}
		?>
		</table>
		<td></tr>
		<?
	
		echo "</td></tr>";
} else {
	echo Message("No gang selected.");
	include 'footer.php';
	die();

}
include 'footer.php';
?>
