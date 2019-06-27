<?php

require_once('header.php');

$results = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `lastactive` > ? ORDER BY `lastactive` DESC');
$results->execute(array(time()-86400));
$results = $results->fetchAll(PDO::FETCH_ASSOC);

?>
<thead>
	<tr>
		<th>Users Online In The Last 24 Hours</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
			<?php
			
			foreach($results as $result){
				$user_online = new User($result['id']);
				echo '<div>'.$user_online->formattedname.' '.htmlentities(howlongago($user_online->lastactive)).'</div>';
			}

			?>
		</td>
	</tr>
</tbody>
<?php

require_once('footer.php');

?>
