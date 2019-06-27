<?php

include(__DIR__.'/header.php');

$_POST['heist_members'] = isset($_POST['heist_members']) && ctype_digit($_POST['heist_members']) ? abs(intval($_POST['heist_members'])) : null;
$_POST['heist_power'] = isset($_POST['heist_power']) && ctype_digit($_POST['heist_power']) ? abs(intval($_POST['heist_power'])) : null;
$_POST['heist_minpay'] = isset($_POST['heist_minpay']) && ctype_digit($_POST['heist_minpay']) ? abs(intval($_POST['heist_minpay'])) : null;
$_POST['heist_maxpay'] = isset($_POST['heist_maxpay']) && ctype_digit($_POST['heist_maxpay']) ? abs(intval($_POST['heist_maxpay'])) : null;
$_POST['active'] = isset($_POST['active']) && ctype_digit($_POST['active']) ? abs(intval($_POST['active'])) : null;
$_POST['weap'] = isset($_POST['weap']) && ctype_digit($_POST['weap']) ? abs(intval($_POST['weap'])) : null;
$_POST['armor'] = isset($_POST['armor']) && ctype_digit($_POST['armor']) ? abs(intval($_POST['armor'])) : null;
if(!$user_class->admin) {
	echo Message('What you doing here???.'); 
	include(__DIR__.'/footer.php');
	exit;
}
if(isset($_POST['create_heist'])) {
	$res = $GLOBALS['pdo']->query('SELECT * FROM `heists` WHERE `activated` = 1');
	$row = $res->fetch(PDO::FETCH_ASSOC);

	if(!is_numeric($_POST['heist_members'])) {
		echo Message('Heist members needs to a number.');
		include(__DIR__.'/footer.php');
		exit; 
	} 
	if(!is_numeric($_POST['heist_power'])) {
		echo Message('Heist power needs to be a number.');
		include(__DIR__.'/footer.php'); 
		exit; 
	}
	if(!is_numeric($_POST['heist_minpay'])) {
		echo Message('Heist min payout needs to a number.'); 
		include(__DIR__.'/footer.php');
		exit;  
	} 
	if(!is_numeric($_POST['heist_maxpay'])) {
		echo Message('Heist max payout needs to be a number.'); 
		include(__DIR__.'/footer.php');
		exit; 
	}
	if(empty($_POST['heist_minpay'])) { 
		echo Message('Invalid min payout.');
		include(__DIR__.'/footer.php');
		exit;
	}
	if(empty($_POST['heist_maxpay'])) {
		echo Message('Invalid max payout.'); 
		include(__DIR__.'/footer.php');
		exit;  
	}
	if(empty($_POST['heist_members'])) {
		echo Message('you must enter amount of members.');
		include(__DIR__.'/footer.php');
		exit;
	}
	if(empty($_POST['heist_power'])) {
		echo Message('You must enter a power.'); 
		include(__DIR__.'/footer.php');
		exit;  
	}
	if(empty($_POST['heist_name'])) {
		echo Message('You must enter a valid name.'); 
		include(__DIR__.'/footer.php');
		exit;   
	}
	if(empty($_POST['weap'])) {
		echo Message('You must enter a valid weapon id.'); 
		include(__DIR__.'/footer.php');
		exit;   
	}
	if(empty($_POST['armor'])) {
		echo Message('You must enter a valid armor id.'); 
		include(__DIR__.'/footer.php');
		exit;   
	}
	if($_POST['heist_name'] == $row['heist_name']) { 
		echo Message('You already have a heist with this name please choose another.');
		include(__DIR__.'/footer.php'); 
		exit; 
		
	} 
	if($_POST['heist_members'] > 5) { 
		echo Message('you cant put over 5 members sorry.');
		include(__DIR__.'/footer.php');  
		exit;	 
	}
	if($_POST['active'] > 1) { 
		echo Message('Active needs to be set to 0 for cant be robbed and 1 to be able to rob it .');
		include(__DIR__.'/footer.php');  
		exit;	 
	}
	if(!$row) {
		echo Message('You dont have any heists active create one.');
	}
	
	echo Message('You have created '.$_POST['heist_name'].'.'); 

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `heists` VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?)');
	$result->execute(array($_POST['heist_name'], $_POST['heist_power'], $_POST['heist_members'], $_POST['active'], $_POST['heist_minpay'], $_POST['heist_maxpay'], $_POST['weap'], $_POST['armor']));
}
?>

<tr><td class='contenthead'>Heists Admin Panel</td></tr>
<tr><td class='contentcontent'>
	<table width='100%'> 
		<tr>
			
			<form method="post">  
			<table width='50%'> 
				<tr>
					<td>Heist Name: <input type="text" name="heist_name" />	</td>
				</tr>
				<tr>
					<td>Power: <input type="text" name="heist_power" /></td>
				</tr>
				<tr>
					<td>Members: <input type="text" name="heist_members" /></td>
				</tr>
				<tr>
					<td>Minimum Payout: <input type="text" name="heist_minpay" /></td>
				</tr>
				<tr>
					<td>Maximum Payout: <input type="text" name="heist_maxpay" /></td>
				</tr>
				<tr>
					<td>Weapon Required [id]: <input type="text" name="weap" /></td>
				</tr>
				<tr>
					<td>armor Required[id]: <input type="text" name="armor" /></td>
				</tr>
				<tr>
					<td>Active: <input type="text" name="active" /> [1 = yes][0 = no]</td>
				</tr>
				
				<tr>
					<td><input type="submit" name="create_heist" value="Create Heist" /></td>
				</tr>
			</table>
			</form>
		</tr>
	</table>
</td></tr>
