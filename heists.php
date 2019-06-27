<?php

include(__DIR__.'/header.php');

$res = $GLOBALS['pdo']->query('SELECT * FROM `planning`');
$plan = $res->fetch(PDO::FETCH_ASSOC);

$_GET['action'] = isset($_GET['action']) && ctype_alpha($_GET['action']) ? strtolower(trim($_GET['action'])) : null;
switch($_GET['action']) {
	case 'setup':
		set_up();
		break;
	case 'setuppage':
		set_up_page();
		break;		
}

?>
<table class="inverted ui five unstackable column small compact table">
	<thead>
	<tr>
	<th colspan="3">Heists</th>
	</tr>
	</thead>
			<tr>
			<td>Heist Name</td>
			<td>Members</td>
			</tr>

		<?php

		$res = $GLOBALS['pdo']->query('SELECT * FROM `heists` WHERE `active` = 1');
		if($res === false){
			$res = array();
		}else{
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
			$row = $res[0];
		}

		if(!count($res)){ 
			echo "<tr>
				  <td colspan='2'>There are no active heists <a href='heists.php?action=setuppage'>Go setup</a> a heist with your mofo gang!</td><td>&nbsp;</td>
				  </tr>";
		}else{
			echo "<tr>  
						<td>".$row['heist_name']."</td>  
						<td>".$row['heist_members']."</td>
					</tr>"; 
		}			
		?> 
	</table> 
	<?php

	if(!count($res)){ ?> 
	<?php	
	}
	function set_up_page() {
		global $user_class; 
		if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;  
		} ?>
			<thead>
			<tr>
				<th colspan="4">Setup Heists</th>
			</tr>
			</thead>
					<tr>
					<td>Heist Name</td>
					<td>Members</td>
					<td colspan="2">Power</td>
				</tr> 
				<?php

				$res = $GLOBALS['pdo']->query('SELECT * FROM `heists` WHERE `activated` = 1 ORDER BY `id`');
				$res = $res->fetchAll(PDO::FETCH_ASSOC);

				foreach($res as $row){
				echo "
				<tr>
					<td>".$row['heist_name']."</td>
					<td>".$row['heist_members']."</td>
					<td>".$row['heist_power']."</td>
					<td><a class='ui mini yellow button' href='heists.php?action=setup&ID={$row['id']}'>Set up</a></td>
				</tr>";
				}
			?>
		</table> 
<?php
}
function set_up() { 
	global $plan, $user_class;
	if($user_class->heist == 1) {
		echo Message('You have already done your daily heist today');
		include(__DIR__.'/footer.php');
		exit;  
	}
	$_GET['ID'] = isset($_GET['ID']) && ctype_digit($_GET['ID']) ? abs(intval($_GET['ID'])) : null;

	$res = $GLOBALS['pdo']->prepare('SELECT * FROM `heists` WHERE `id` = ?');
	$res->execute(array($_GET['ID']));
	$heist = $res->fetch(PDO::FETCH_ASSOC);

	if($plan['userid'] == $user_class->id) { 
		echo Message('You already have a heist set up.'); 
		include(__DIR__.'/footer.php');  
		exit;    
	}	  
	else {   
		$power = round($user_class->totalattrib / 100);

		$res = $GLOBALS['pdo']->prepare('INSERT INTO `planning` (`id`,`userid`,`heist_name`,`member_power`,`heist_members`) VALUES (NULL, ?, ?, ?, "1")');
		$res->execute(array($user_class->id, $heist['heist_name'], $power));

		echo Message("You have set up a ".$heist['heist_name']." check it here. <a href='plan.php?action=planningpage'>Planning page</a>");  
	}
}
include(__DIR__.'/footer.php');
?>	
