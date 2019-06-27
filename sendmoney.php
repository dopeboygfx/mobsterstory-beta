<?php
include 'header.php';
error_reporting(E_ALL);
if($_POST['sendmoney'] != ""){
  $_POST['amount'] = abs(intval($_POST['amount']));
  $money_person = new User($_POST['theirid']);
  $Scruffy_check_mp = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `id` = ?');
  $Scruffy_check_mp->execute(array($money_person->id));
  $Scruffy_num = count($Scruffy_check_mp->fetchAll(PDO::FETCH_NUM));

  if($Scruffy_num == 0) {
    echo "No user found";
    exit;
  }

  if($user_class->money >= $_POST['amount'] && $_POST['amount'] > 0 && $user_class->id != $money_person->id){
    $newmoney = $user_class->money - $_POST['amount'];
    $result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `money` = ? WHERE `id` = ?');
    $result->execute(array($newmoney, $_SESSION['id']));

    $newmoney = $money_person->money + $_POST['amount'];
    $result->execute(array($newmoney, $_POST['theirid']));

    echo "You have successfully transferred $".$_POST['amount']." to ".$money_person->formattedname.".";
    Send_Event($user_points->id, "You have been sent $".$_POST['amount']." from ".$user_class->formattedname);
  } else {
    echo "You don't have enough money to do that!";
  }
}
?>
<thead>
  <tr>
    <th>Send Money</th>
  </tr>
</thead>
</tr><tr>
  <td>
    <form name='login' method='post' action='sendmoney.php'>
      <table border='0' cellpadding='0' cellspacing='0'>

        <td width='35%' height='27'>Amount Of Money</td>
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
          <input class="ui mini green button" type='submit' name='sendmoney' value='Send Money'>
        </td>
      </tr>
    </table>
  </form>
</table>


<?php
include 'footer.php';
?>
