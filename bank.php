<?php
include 'header.php';

if($_POST['deposit'] != ""){
  $amount = abs(intval($_POST['damount']));
  if ($amount > $user_class->money) {
    echo Message("You do not have that much money.");
  }
  if ($amount < 1){
    echo Message("Please enter a valid amount.");
  }
  if ($amount <= $user_class->money && $amount > 0) {
    echo Message("Money deposited.");
    $newbank = $amount + $user_class->bank;
    $newmoney = $user_class->money - $amount;

    $result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `bank` = ?, `money` = ? WHERE `id` = ?');
    $result->execute(array($newbank, $newmoney, $_SESSION['id']));
    $user_class = new User($_SESSION['id']);
  }
}

if($_POST['withdraw'] != ""){
  $amount = abs(intval($_POST['wamount']));
  if ($amount > $user_class->bank) {
    echo Message("You do not have that much money in the bank.");
  }
  if ($amount < 1){
    echo Message("Please enter a valid amount.");
  }
  if ($amount <= $user_class->bank && $amount > 0) {
    echo Message("Money withdrawn.");
    $newbank = $user_class->bank - $amount;
    $newmoney = $user_class->money + $amount;

    $result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `bank` = ?, `money` = ? WHERE `id` = ?');
    $result->execute(array($newbank, $newmoney, $_SESSION['id']));
    $user_class = new User($_SESSION['id']);
  }
}

if($_GET['open'] == "new"){
  if($user_class->money >= 5000 && $user_class->bank == 0){
    $newmoney = $user_class->money - 5000;
    $result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `whichbank` = 1, `money` = ? WHERE `id` = ?');
    $result->execute(array($newmoney, $_SESSION['id']));
    $user_class = new User($_SESSION['id']);
    echo'
    <thead>
    <tr>
    <th>Bank</th>
    </tr>
    </thead>
    <tr><td>
    You have created a bank account. Click below to visit the bank to make your first deposit.
    <br>
    <br>
    <a class="ui mini green button" href="bank.php"> Visit Bank </a>
    </td>
    </tr>';
  }
  else {
    echo'
    <tr><td>Bank</td></tr>
    <tr><td>
    You dont have enough money.
    click <a href="bank.php" /> Here </a> to return.
    </tr>';
  }
  include(DIRNAME(__FILE__).'/footer.php');
  exit;
}

if($user_class->rmdays > 1) {
  $interest = .04;
} else {
  $interest = .02;
}
$interest = ceil($user_class->bank * $interest);

?>
<thead>
  <tr>
    <th>Bank</th>
  </tr>
</thead>
<!-----
<tr>
  <td style="align:center">
  <img src="../images/bank.png"></div>
</td>
</tr>
------>
<? if($user_class->whichbank != 0){ ?>
  <tr>
    <td>
      Welcome to the city bank <? echo $user_class->formattedname; ?>

<br>
<br>
      Account Balance: $<? echo $user_class->bank ?><br><?php echo "You will make $".$interest." from interest next rollover."; ?><br><br>
      <form method='post'><input class="ui input focus" type='text' name='wamount' value='<? echo $user_class->bank ?>' size='10' maxlength='20'> &nbsp;
        <input type='submit' class='ui button mini red' name='withdraw' value='Withdraw'></form><br><br>
        <form method='post'><input class="ui input focus" type='text' name='damount' value='<? echo $user_class->money ?>' size='10' maxlength='20'> &nbsp;
          <input type='submit' class='ui button mini green' name='deposit' value='Deposit'></form>


        </td>
      </tr>
    <? } else { ?>
      <tr><td>
        You do not currently have an account with us. Would you like to open one for $5,000?
        <br>
        <br>
        <a class="ui mini green button" href="bank.php?open=new">Yes</a>
        </td></tr>
      <? } ?>
      <?php
      include 'footer.php';
      ?>
