<?php
include 'header.php';
echo "
	<tr>
	<th width='25%' class='contenthead'>Image</th>
	<th width='50%' class='contenthead'>Text</th>
	<th width='25%' class='contenthead'>Completed</th>
	</tr>";

$result = $GLOBALS['pdo']->prepare('SELECT * FROM achievements ORDER BY `id` ASC');
$result->execute();
$result = $result->fetchAll(PDO::FETCH_ASSOC);

	foreach($result AS $r){
	$res = $GLOBALS['pdo']->prepare("SELECT * FROM `achievement` WHERE `a_id` = ? AND `u_id` = ?");
	$res->execute(array($r['id'],$user_class->id));
	$worked = $res->fetch(PDO::FETCH_ASSOC);
	if ($worked['a_id']){
		$complete = '<font color="green">Finished</font>';
	}else{
		$complete = '<font color="green">Finished</font>';
	}
	$r['text'] = str_replace('[value]', number_format($r['value']), $r['text']);
	echo "<tr>
		<td><img src='".$r['image']."' /></td>
		<td>".$r['text']."</td>
		<td>".$complete."</td>
	      </tr>";
	}




echo "</table><br /><br />";
include 'footer.php';
?>
