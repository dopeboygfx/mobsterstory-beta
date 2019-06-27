<?
include 'header.php';

$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `id` ASC');
$totalmobsters = count($result->fetchAll(PDO::FETCH_NUM));

$result2 = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` WHERE `rmdays` != "0"');
$totalrm = count($result2->fetchAll(PDO::FETCH_NUM));
?>
<thead>
<tr>
<th>World Stats (more will be added soon)</th>
<th></th>
</tr>
</thead>
<tr>
	<td class='textl' width='15%'>Mobsters: <?= $totalmobsters ?></td>
	<td class='textl'>Respected Mobsters: <?= $totalrm ?></td>
</tr>
</table>
</td></tr>
<?
include 'footer.php';
?>
