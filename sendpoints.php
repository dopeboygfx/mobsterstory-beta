
<?php
include 'header.php';

if($_POST['sendpoints'] != ""){
    $_POST['amount'] = abs(intval($_POST['amount']));
  $money_person = new User($_POST['theirid']);

  if($user_class->points >= $_POST['amount'] && $_POST['amount'] > 0 && $user_class->id != $money_person->id){
	$newpoints = $user_class->points - $_POST['amount'];
	$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `id` = ?');
	$result->execute(array($newpoints, $_SESSION['id']));
	
	$newpoints = $money_person->points + $_POST['amount'];
	$result->execute(array($newpoints, $_POST['theirid']));
	echo "
			<thead>
			<tr>
			<th>Message</th>
			</tr>
			</thead>
			<tr>
			<td>
			You have successfully transferred ".$_POST['amount']." points to ".$money_person->formattedname.".
			</td>
			</tr>	
		 ";
  } else {
	echo "
			<thead>
			<tr>
			<th>Message</th>
			</tr>
			</thead>
			<tr>
			<td>
			You don't have enough points to do that!
			</td>
			</tr>
			</table>";
  } 
}
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Send Points</th>
</tr>
</thead>
</tr><tr>
<td>	
<form name='login' method='post' action='sendpoints.php'>
  <table border='0' cellpadding='0' cellspacing='0'>
      <tr> 
      <td width='35%' height='27'>Amount Of Points</td>
      <td width='65%'>
      <input class="ui input focus" name='amount' type='text' size='22'>
      </td>
      </tr>
      <tr> 
      <td width='35%' height='27'>User ID</td>
      <td width='65%'>
        <input class="ui input focus" name='theirid' type='text' size='22' value='<? echo $person ?>'>
    	</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>
        <input type='submit' class='ui mini green button' name='sendpoints' value='Send Points'>
        </td>
    </tr>
  </table>
</form>
</table>


<?php
include 'footer.php';
?>