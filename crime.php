<?php
include 'header.php';

$error = ($user_class->jail > 0) ? "You can't do crimes if you are in jail." : $error;
$error = ($user_class->hospital > 0) ? "You can't do crimes if you are in the hospital." : $error;

if (isset($error)){
	echo Message($error);
	include 'footer.php';
	die();
}

$crime = abs(intval($_GET['id']));

if ($crime != ""){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `crimes` WHERE `id` = ?');
	$result->execute(array($crime));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$count = $GLOBALS['pdo']->query('SELECT * FROM `crimes`');
	$check_crimes = count($count->fetchAll(PDO::FETCH_NUM));

	if($_GET['id'] > $check_crimes) {
		 echo Message("Failed");
		 include(DIRNAME(__FILE__).'/footer.php');
		 exit;
	}

	$nerve = $worked['nerve'];
	$name = $worked['name'];
	$stext = '[[We currently do not have a success message for this crime :( You can help  us by submitting your idea for a message in the crime section of the forums!]]';
	$ctext = ' ';
	$ftext = '[[We currently do not have a failure message for this crime :( You can help  us by submitting your idea for a message in the crime section of the forums!]]';
	$stexta = explode("^",$worked['stext']);
	$stext = ($stexta[0] != "") ? $stexta[array_rand($stexta)] : $stext;
	$ctexta = explode("^",$worked['ctext']);
	$ctext = ($ctexta[0] != "") ? $ctexta[array_rand($ctexta)] : $ctext;
	$ftexta = explode("^",$worked['ftext']);
	$ftext = ($ftexta[0] != "") ? $ftexta[array_rand($ftexta)] : $ftext;

	$chance = rand(1,(100 * $nerve - ($user_class->speed / 35)));
	// get the crimes here

	$money = (25 * $nerve) + 15 * ($nerve - 1);
	$exp = $money;

	if ($user_class->nerve >= $nerve) {
		if($chance <= 75) {
			echo Message($stext."
			<br>
			<br>
			<div class='ui small green label'>+
			".$exp." EXP</div>
			<div class='ui small green label'>+
			$".$money."</div>
			</font>
			<br>
			<br>
			<a class='ui mini red button' href='crime.php?id=".$crime."'><font color='white'>Retry</font></a>

			<a class='ui mini blue button' href='crime.php'><font color='white'>Back</font></a></button>");
			$exp = $exp + $user_class->exp;
			$crimesucceeded = 1 + $user_class->crimesucceeded;
			$crimemoney = $money + $user_class->crimemoney;
			$money = $money + $user_class->money;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `exp` = ?, `crimesucceeded` = ?, `crimemoney` = ?, `money` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($exp, $crimesucceeded, $crimemoney, $money, $nerve, $_SESSION['id']));
		}elseif ($chance >= 150) {
			echo Message($ctext."
			<div class='ui error message'>
  		<div class='header'>You we're caught and sent to jail " . $crime * 10 . " minutes you mofo.</div>
  		<ul class='list'>
    	<li>You must be busted by another player or do your time</li>
  		</ul>
			</div>
			");
			$crimefailed = 1 + $user_class->crimefailed;
			$jail = 1200;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `crimefailed` = ?, `jail` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($crimefailed, $jail, $nerve, $_SESSION['id']));
		}else{
			echo Message($ftext."
			<br>
			<br>
			<a class='ui mini red button' href='crime.php?id=".$crime."'>Retry</a> <a class='ui mini blue button' href='crime.php'>Back</a>");

			$crimefailed = 1 + $user_class->crimefailed;
			$nerve = $user_class->nerve - $nerve;

			$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `crimefailed` = ?, `nerve` = ? WHERE `id` = ?');
			$result->execute(array($crimefailed, $nerve, $_SESSION['id']));
		}
	} else {
		echo Message("You don't have enough nerve for that crime.");
	}
	include 'footer.php';
	die();
}
?>

 <thead>
    <tr>
	<th colspan="3">Crime</th>
  	</tr>
 </thead>
 		<!-----
		<tr>
				<td colspan="3">
				<img style="align:center" src="../images/crimes.png"></div>
				</td>
		</tr>
		<tr>
			<td width='50%'><b>Name</b></td>
			<td width='25%'><b>Nerve</b></td>
			<td width='25%'><b>Action</b></td>
		</tr>
		------>
<?
$result = $GLOBALS['pdo']->query('SELECT * FROM `crimes` ORDER BY `nerve` ASC')->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $line){
	echo "<tr><td width='50%'>".$line['name']."</td><td width='25%'><i class='yellow fire icon'></i>".$line['nerve']."</td><td width='25%'><a class='ui mini red button' href='crime.php?id=".$line['id']."'><font color='white'>Commit</font></a></td></tr>";
}
?>
	</table>
</td></tr>
<?php
include 'footer.php';
?>
