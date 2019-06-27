<?
include 'dbcon.php';

$result = $GLOBALS['pdo']->query('SELECT * FROM `stocks`');
$result = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $line){
	$amount = rand (strlen($line['cost']) * -1, strlen($line['cost']));
	$newamount = $line['cost'] + $amount;
	if ($newamount < 1){
		$newamount = 1;
	}

	$result2 = $GLOBALS['pdo']->prepare('UPDATE `stocks` SET `cost` = ? WHERE `id` = ?');
	$result2->execute(array($newamount, $line['id']));
}

?>
