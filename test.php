<?
include 'header.php';

if ($user_class->gang == 0){
	echo Message("You aren't in a gang.");
	include 'footer.php';
	die();
}

$gang_class = new Gang($user_class->gang);

include("gangheaders.php");
?>

<table class="inverted ui five unstackable column small compact table">
<thead>
				<tr>
					<th><? echo "[". $gang_class->tag . "]&nbsp;" . $gang_class->name; ?></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
		<td width="100%">
<?php echo ($gang_class->description != "") ? "<br /><br />" : ""; ?>
<?php
$string = strip_tags($gang_class->description); 
$output = BBCodeParse($string); 
echo $output;
?>
</td></tr>
</table>
</td></tr>

<?
include 'footer.php';
?>