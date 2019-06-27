<?php
include 'header.php';
?>
<tr><td>
<img src='images/stock market.png' />
</td></tr>
<thead>
<tr>
<th>View Stock Market</th>
</tr>
</thead>
</table>
	<table class="inverted ui five unstackable column small compact table">
		<tr>
			<td width='5%'><b>ID</b></td>
			<td width='70%'><b>Company Name</b></td>
			<td width='25%'><b>Cost per Share</b></td>
		</tr>
<?

$result = $GLOBALS['pdo']->query('SELECT * FROM `stocks` ORDER BY `id` ASC');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	echo "<tr><td width='5%'>".$line['id']."</td><td width='70%'>".$line['company_name']."</td><td width='25%'>$".$line['cost']."</td></tr>";
}
?>
	</table>
</td></tr>
<?php
include 'footer.php';
?>
