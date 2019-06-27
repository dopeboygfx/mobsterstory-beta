<?
include 'header.php';
?>
<tr><td class="contenthead">Refer To Earn Points</td></tr>
<tr><td class="contentcontent">Your Referer Link: http://www.mobsterstory.com/register.php?referer=<? echo $user_class->id; ?><br />UPDATE: You will recieve your points only <i>after</i> we filter out multis. This is due to too many people abusing the referral system. Because we have to do this manually now, this could take anywhere from an hour to 2 days, but rest assured that you will recieve your points.
</td></tr>
<?
echo '<tr><td class="contenthead">Players You Have Referred</td></tr>';

$result = $GLOBALS['pdo']->prepare('SELECT * FROM `referrals` WHERE `referrer` = ?');
$result->execute(array($user_class->id));
$result = $result->fetchAll(PDO::FETCH_ASSOC);

echo '<tr><td class="contentcontent">';
foreach($result as $line){
	$credited = ($line['credited'] == 0) ? "Pending" : "Approved";
	echo "<div>".$line['referred']." - ".$credited."</div>";
}
echo '</td></tr>';
include 'footer.php';
?>
