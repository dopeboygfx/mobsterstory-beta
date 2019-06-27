<?
include 'header.php';

if ($user_class->gang != 0) {
	$gang_class = new Gang($user_class->gang);
	include("gangheaders.php");
	$result = $GLOBALS['pdo']->prepare("SELECT * from `gangs` WHERE `id` = '".$user_class->gang."'");
	$result->execute();
	$worked = $result->fetch(PDO::FETCH_ASSOC);
	?>
<table class="inverted ui five unstackable column small compact table">
<thead>
				<tr>
					<th>Gang Details</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
		
	<td width="15%">Level:&nbsp;</td><td width="35%"><?php echo $gang_class->level; ?></td>
	<td width="20%">Money:&nbsp;</td><td width="35%">$<?php echo prettynum($gang_class->moneyvault); ?></td>
</tr>
<tr>
	<td width="15%">EXP:&nbsp;</td><td width="35%"><?php echo $gang_class->formattedexp; ?></td>
	<td width="15%">Points:&nbsp;</td><td width="35%"><?php echo prettynum($gang_class->pointsvault); ?></td>
</tr>
<tr>
	<td width="15%">Members:&nbsp;</td><td width="35%"><?php echo $gang_class->members; ?>&nbsp;/&nbsp;<?php echo $gang_class->capacity; ?></td>
	<td width="15%">Gang House:&nbsp;</td><td width="35%"><?php echo $gang_class->housenamez; ?> [+<?php echo $gang_class->houseawakez; ?>%]</td>
</tr>
<tr>
	<td width="15%">Tax:&nbsp;</td><td width="35%"><?php echo $gang_class->tax; ?>%</td>
	<td width="15%"></td>
</tr>
</table>
</td></tr>
<?
} else {
	echo Message("You aren't in a gang.");
}
include 'footer.php';
?>