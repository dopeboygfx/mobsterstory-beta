<?php
/* 01-16-2014 - G7470 Programming in association with Steve Modifications -  Added text checks to make input more secure. */
/* 03-20-2019 - Two Lucky Design inplementation of Semantics UI Framework. */
include 'header.php';

$_GET['id'] = isset($_GET['id']) && ctype_digit($_GET['id']) ? abs(@intval($_GET['id'])) : $user_class->id;

if (isset($_POST['avatar'])) {
$_POST['signature'] = str_replace('"', '', $_POST['signature']);
$signature = strip_tags($_POST["signature"]);
$signature = addslashes($signature);
$_POST['username'] = str_replace('"', '', $_POST['username']);
$username = $_POST["username"];
$username = addslashes($username);
$email = strip_tags($_POST['email']);
$email = addslashes($email);
$_POST['avatar'] = str_replace('"', '', $_POST['avatar']);
$_POST['avatar'] = str_replace('[IMG]', '', $_POST['avatar']);
$_POST['avatar'] = str_replace('[/IMG]', '', $_POST['avatar']);
$_POST['avatar'] = str_replace('[img]', '', $_POST['avatar']);
$_POST['avatar'] = str_replace('[/img]', '', $_POST['avatar']);
$avatar = strip_tags($_POST["avatar"]);
$avatar = addslashes($avatar);
$_POST['quote'] = str_replace('"', '', $_POST['quote']);
$quote = strip_tags($_POST["quote"]);
$quote = addslashes($quote);
$gender = $_POST["gender"];
$music = $_POST['music'];
$volume = $_POST['volume'];
if (strlen($username) < 3 or strlen($username) > 16) {
$message .= "<div>The name you chose has " . strlen($username) . " characters. You need to have between 3 and 16 characters.</div>";
}

if (url_exists(strip_tags($_POST['avatar'])) == 0 && strip_tags($_POST['avatar']) != "") {
$message .= "<div>Your avatar link appears to be broken. Please check it in your browser.</div>";
}

if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
$message .= "<div>Your new email address is invalid.</div>";
}

if ($message != "") {
echo Message($message);
} else {
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `avatar`=?, `quote`=?, `gender`=?, `username`=?, `sig`=?, `email` = ? WHERE `id`=?");
$result->execute(array(
$avatar,
$quote,
$gender,
$username,
$signature,
$email,
$_GET['id']
));
print_r($result->errorInfo());
echo Message('You have edited this players prefrences.');
}
}

$_POST['days'] = isset($_POST['days']) && ctype_digit($_POST['days']) ? abs(@intval($_POST['days'])) : null;
$profile_class = new User($_GET['id']);
$check1 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$check1->execute(array(
$_GET['id']
));
$check1 = $check1->fetchALL(PDO::FETCH_ASSOC);

if (count($check1) < 1) {
echo Message("This player doesn't exist.");
} else {

// Get last 5 IPs

if (isset($_POST['5ips'])) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$result->execute(array(
$profile_class->id
));
$ips = $result->fetch(PDO::FETCH_ASSOC);
$i1 = $ips['ip1'];
$i2 = $ips['ip2'];
$i3 = $ips['ip3'];
$i4 = $ips['ip4'];
$i5 = $ips['ip5'];
echo Message("The last 5 IPs this account was visited from (in order of latest first): $i1, $i2, $i3, $i4, $i5");
}

// Change Flag/Tag

if (isset($_POST['changeflag'])) {
if ($user_class->admin == 1 || $user_class->gm == 1) {
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `tag` = ? WHERE `id` = ?");
$result->execute(array(
$_POST['flag'],
$profile_class->id
));
echo Message("You have successfully changed " . $profile_class->formattedname . "'s profile flag. Click <a href='profiles.php?id={$_GET['id']}'>here</a> to refresh the page.");
}
}

// Report Profile

if (isset($_POST['report'])) {
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `reported` = '1', `reporter` = ? WHERE `id` = ?");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have successfully reported " . $profile_class->formattedname . "'s profile.");
}

// Rate Up

if ($_GET['rate'] == "up") {
if ($_GET['id'] != $user_class->id) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `rating` WHERE `user` = ? AND `rater` = ?");
$result->execute(array(
$profile_class->id,
$user_class->id
));
$result = $result->fetchALL(PDO::FETCH_ASSOC);
$worked = count($result);
$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$result2->execute(array(
$profile_class->id
));
$worked2 = $result2->fetch(PDO::FETCH_ASSOC);
if ($worked == 0) {
$rating = $worked2['rating'] + 1;
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `rating` = ? WHERE `id` = ?");
$result->execute(array(
$rating,
$profile_class->id
));
$result = $GLOBALS['pdo']->prepare("INSERT INTO `rating` (user, rater)" . "VALUES (?, ?)");
$result->execute(array(
$profile_class->id,
$user_class->id
));
echo Message("You have rated " . $profile_class->formattedname . " up.");
} else {
echo Message("You have already rated " . $profile_class->formattedname . " today.");
}
} else {
echo Message("You can't rate yourself!");
}
}

// Rate Down

if ($_GET['rate'] == "down") {
if ($_GET['id'] != $user_class->id) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `rating` WHERE `user` = ? AND `rater` = ?");
$result->execute(array(
$profile_class->id,
$user_class->id
));
$result = $result->fetchALL(PDO::FETCH_ASSOC);
$worked = count($result);
$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$result2->execute(array(
$profile_class->id
));
$worked2 = $result2->fetch(PDO::FETCH_ASSOC);
if ($worked == 0) {
$rating = $worked2['rating'] - 1;
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `rating` = ? WHERE `id` = ?");
$result->execute(array(
$rating,
$profile_class->id
));
$result = $GLOBALS['pdo']->prepare("INSERT INTO `rating` (user, rater)" . "VALUES (?, ?)");
$result->execute(array(
$profile_class->id,
$user_class->id
));
echo Message("You have rated " . $profile_class->formattedname . " up.");
} else {
echo Message("You have already rated " . $profile_class->formattedname . " today.");
}
} else {
echo Message("You can't rate yourself!");
}
}

// Add Ignore

if ($_GET['contact'] == "ignore") {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ignorelist` WHERE `blocker`=? AND `blocked` = ?");
$result->execute(array(
$user_class->id,
$profile_class->id
));
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($profile_class->admin == 1 || $profile_class->gm == 1 || $profile_class->fm == 1) {
echo Message("You cant put a member of staff on your ignore list!");
include('footer.php');

exit;
}

// Remove Contact

if ($profile_class->id == $worked['blocked']) {
$result = $GLOBALS['pdo']->prepare("DELETE FROM `ignorelist` WHERE `blocker`= ? AND `blocked`=?");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have removed " . $profile_class->formattedname . " from your ignore list.");
} else {
if ($worked['blocked'] == $profile_class->id) {
echo Message("" . $profile_class->formattedname . " is already on your ignore list!");
} else {

// Add Contact

$result = $GLOBALS['pdo']->prepare("INSERT INTO `ignorelist` (blocker, blocked)" . "VALUES (?, ?)");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have added " . $profile_class->formattedname . " to your ignore list.");
}
}
}

// Friends List

if ($_GET['contact'] == "friend") {
$result3 = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid`=? AND `contactid` = ?");
$result3->execute(array(
$user_class->id,
$profile_class->id
));
$worked3 = $result3->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid`=? AND `contactid` = ? AND `type` = '1'");
$result->execute(array(
$user_class->id,
$profile_class->id
));
$worked = $result->fetch(PDO::FETCH_ASSOC);

// Remove Contact

if ($profile_class->id == $worked['contactid']) {
$result = $GLOBALS['pdo']->prepare("DELETE FROM `contactlist` WHERE `playerid`=? AND `contactid`=?");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have removed " . $profile_class->formattedname . " from your friends list.");
} else {
if ($worked3['type'] == 2) {
echo Message("" . $profile_class->formattedname . " is already your enemy!");
} else {

// Add Contact

$result = $GLOBALS['pdo']->prepare("INSERT INTO `contactlist` (playerid, contactid, type)" . "VALUES (?, ?,'1')");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have added " . $profile_class->formattedname . " to your friends list.");
}
}
}

// Friends List

if ($_GET['contact'] == "enemy") {
$result3 = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid`=? AND `contactid` = ?");
$result3->execute(array(
$user_class->id,
$profile_class->id
));
$worked3 = $result3->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid`=? AND `contactid` = ? AND `type` = '2'");
$result->execute(array(
$user_class->id,
$profile_class->id
));
$worked = $result->fetch(PDO::FETCH_ASSOC);

// Remove Contact

if ($profile_class->id == $worked['contactid']) {
$result = $GLOBALS['pdo']->prepare("DELETE FROM `contactlist` WHERE `playerid`=? AND `contactid`=?");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have removed " . $profile_class->formattedname . " from your enemy list.");
} else {
if ($worked3['type'] == 1) {
echo Message("" . $profile_class->formattedname . " is already your friend!");
} else {

// Add Contact

$result = $GLOBALS['pdo']->prepare("INSERT INTO `contactlist` (playerid, contactid, type)" . "VALUES (?, ?,'2')");
$result->execute(array(
$user_class->id,
$profile_class->id
));
echo Message("You have added " . $profile_class->formattedname . " to your enemy list.");
}
}
}

if ($user_class->admin == 1 || $user_class->gm == 1 || $user_class->fm == 1) {
if (isset($_POST['addcpoints'])) {
$point_user = new User($_POST['id']);
$newpoints = $point_user->points + $_POST['points'];
$update = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = ? WHERE `id` = ?");
$update->execute(array(
$newpoints,
$point_user->id
));
echo Message("You have added a " . prettynum($_POST['points']) . " points pack to " . $point_user->formattedname . ".");
Send_Event($point_user->id, "You have been credited a " . prettynum($_POST['points']) . " points pack.", $point_user->id);
}

if (isset($_POST['cbank'])) {
$point_user = new User($_POST['id']);
$newpoints = $point_user->bank + $_POST['bank'];
$update = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `bank` = ? WHERE `id` = ?");
$update->execute(array(
$newpoints,
$point_user->id
));
echo Message("You have successfully added $" . prettynum($_POST['bank']) . " to this persons bank.");
Send_Event($point_user->id, "$" . prettynum($_POST['bank']) . " has been added to your bank.", $point_user->id);
}

if (isset($_POST['addcredits'])) {
$point_user = new User($_POST['id']);
$newcredits = $point_user->credits + $_POST['credits'];
$update = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `credits` = ? WHERE `id` = ?");
$update->execute(array(
$newcredits,
$point_user->id
));
echo Message("You have added " . prettynum($_POST['credits']) . " credits to " . $point_user->formattedname . ".");
Send_Event($point_user->id, "You have been credited " . prettynum($_POST['credits']) . " credits.", $point_user->id);
}
}

if ($_POST['addnotes'] != "") {
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `notes` = ? WHERE `id`=?");
$result->execute(array(
$_POST['notes'],
$_POST['id']
));
echo Message("You have edited the Player Notes for " . $profile_class->formattedname . ".");

    StaffLog($user_class->id, "[-_USERID_-] has changed the player notes for [-_USERID2_-].", $profile_class->id);
}

if (isset($_POST['adminstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `admin` = 1 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given Admin access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokeadminstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `admin` = 0 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken Admin access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['gmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `gm` = 1 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given GM access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokegmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `gm` = 0 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken GM access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['ststatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `gm` = '1', `st` = '1' WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given SG access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokeststatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `gm` = '0', `st` = '0' WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken SG access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['fmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `fm` = 1 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given FM access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokefmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `fm` = 0 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken FM access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['cmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `cm` = 1 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given CM access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokecmstatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `cm` = 0 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken CM access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['eostatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `eo` = 1 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have given EO access to " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['revokeeostatus'])) {
$user = $_POST['id'];
if ($user != "") {
$query = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `eo` = 0 WHERE `id` = ?");
$query->execute(array(
$user
));
echo Message("You have taken EO access from " . $profile_class->formattedname . ".");
}
}

if (isset($_POST['permban'])) {
if ($profile_class->admin == 1) {
echo Message("You can't ban an admin!");
} else {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`= ? AND `type` = 'perm'");
$result->execute(array(
$_POST['id']
));
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($worked['id'] == $profile_class->id) {
echo Message("That user is already banned.");
} else
if ($_POST['days'] < 1) {
echo Message("You need to specify how many days to ban the user for.");
} else {
$newdays = $_POST['days'];
$type = "perm";
$result = $GLOBALS['pdo']->prepare("INSERT INTO `bans` (bannedby, id, type, days)" . "VALUES (?, ?,?,?)");
$result->execute(array(
$user_class->id,
$_POSR['id'],
$type,
$newdays
));
$result2 = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `ban/freeze` = '1' WHERE `id` = ?");
$result2->execute(array(
$_POST['id']
));

   StaffLog($user_class->id, "[-_USERID_-] has banned [-_USERID2_-] from the game for ".prettynum($newdays)." days.", $profile_class->id);
// Front End Start Here
echo "
<tr><td>
</td></tr>
<tr><td>Important Message</td></tr><tr>
<td>
You have banned " . $profile_class->formattedname . " from the game.
</td></tr>";
}
}
}

?>


<style type="text/css">
a:link {
text-decoration: none;
}

a:visited {
text-decoration: none;
}

a:hover {
text-decoration: none;
}

a:active {
text-decoration: none;
}
</style>


<thead>
<tr>
<th>Profile</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<?php
if ($profile_class->protag != "0") {
?>
<?php

}

?>
<tr>
<?php
if ($profile_class->avatar !== "") {
$avatar = $profile_class->avatar;
} else {
$avatar = "images/noavatar.png";
}

if ($profile_class->quote !== "") {
$quote = $profile_class->quote;
} else {
$quote = "I am teh one without awesomeness quote.";
}

?>

<td><a href='profiles.php?id=<?php
echo $profile_class->id;
?>'><img height="100" width="100" style="border:1px solid #000000" src="<?php echo $avatar
?>"></a></td>
<td colspan="2"><b>Quote: </b><?php echo $quote; ?></td>
<td><b>Rating: </b> <?php
echo $profile_class->rating;
?><br />
[<a href="profiles.php?id=<?php
echo $profile_class->id;
?>&rate=up">up</a> : <a href="profiles.php?id=<?php
echo $profile_class->id;
?>&rate=down">down</a>]
</td>

</tr>

</td>
</tr>

<tr>
<td><b>Name</b>:</td>
<td><?php
echo $profile_class->formattedname;
?></td>

<td><b>HP</b>:</td>
<td><?php
echo prettynum($profile_class->formattedhp);
?></td>
</tr>

<tr>
<td><b>Type</b>:</td>
<td><?php
echo $profile_class->type;
?></td>

<td><b>Crimes</b>:</td>
<td><?php
echo prettynum($profile_class->crimesucceeded);
?></td>
</tr>

<tr>
<td><b>Gender</b>:</td>
<td><?php
echo $profile_class->gender;
?></td>

<td><b>Forum Posts</b>:</td>
<td><?php
echo prettynum($profile_class->posts);
?></td>
</tr>

<tr>
<td><b>Level</b>:</td>
<td><?php
echo $profile_class->level;
?></td>

<td><b>Money</b>:</td>
<td>$<?php
echo prettynum($profile_class->money);
?></td>
</tr>

<tr>
<td><b>Prison Busts</b>:</td>
<td><?php
echo prettynum($profile_class->busts);
?></td>

<td><b>Prison Caught</b>:</td>
<td><?php
echo prettynum($profile_class->caught);
?></td>
</tr>

<tr>
<td><b>Kills</b>:</td>
<td><?php
echo prettynum($profile_class->battlewon);
?></td>

<td><b>Deaths</b>:</td>
<td><?php
echo prettynum($profile_class->battlelost);
?></td>
</tr>

<tr>
<td><b>Age</b>:</td>
<td><?php
echo $profile_class->age;
?></td>

<td><b>Last Active</b>:</td>
<td><?php
echo ($profile_class->lastactive != 0) ? $profile_class->formattedlastactive : "Never";
?></td>
</tr>

<tr>
<td><b>Online</b>:</td>
<td><?php
echo $profile_class->formattedonline;
?></td>

<td><b>Gang</b>:</td>
<td>
<?php
if ($profile_class->gang != 0) {
echo $profile_class->formattedgang;
} else {
echo "None";
}

?>
</td>
</tr>

<tr>
<td><b>City</b>:</td>
<td><a href="bus.php"><?php
echo $profile_class->cityname;
?></a></td>

<td><b>House</b>:</td>
<td><a href="house.php"><?php
echo $profile_class->housename;
?></a></td>
</tr>

<tr>
<?php
$result222 = $GLOBALS['pdo']->prepare("SELECT * FROM `referrals` WHERE `referred`=?");
$result222->execute(array(
$_GET['id']
));
$worked222 = $result222->fetch(PDO::FETCH_ASSOC);
$refer_id = new User($worked222['referrer']);
$refer_exists = count($worked222);
if ($refer_exists > 0) {
$refer = $refer_id->formattedname;
} else {
$refer = "Nobody";
}

?>
<td><b>Referred By</b>:</td>
<td><?php
echo $refer;
?></td>

<td><b>Relationship</b>:</td>
<td><?php
if ($profile_class->relationship == 0) {
echo "Single/None";
} else
if ($profile_class->relationship == 1) {
$rel_user = new User($profile_class->relplayer);
echo "Dating " . $rel_user->formattedname2;
} else
if ($profile_class->relationship == 2) {
$rel_user = new User($profile_class->relplayer);
echo "Engaged to " . $rel_user->formattedname2;
} else
if ($profile_class->relationship == 3) {
$rel_user = new User($profile_class->relplayer);
echo "Married to " . $rel_user->formattedname2;
}

?></td>
</tr>
</table>

<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ? ");
$result->execute(array(
$_GET['id']
));
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($worked['reported'] == 1) {
$report = '
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th><input class="ui mini red button" type="submit" name="report" disabled="true" value="Reported" /></th>
</tr>
</thead>

';
} else {
$report = '
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>
<center>
<input class="ui mini red button" type="submit" name="report" value="Report Profile" />
</center>
</th>
</tr>
</thead>
';
}

echo ($worked['id'] != $user_class->id) ? $report : "";
?>
</td>
</tr>

<?php
if ($user_class->id != $profile_class->id) {
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th width="50px">Actions</th>
<th width="50px"></th>
<th width="50px"></th>
<th width="50px"></th>
</tr>
</thead>
<tr>
<td><a href='pms.php?view=new&to=<?php
echo $profile_class->id;
?>'>Message</a></td>
<td><a href='attack.php?attack=<?php
echo $profile_class->id;
?>'>Attack</a></td>
<td><a href='mug.php?mug=<?php
echo $profile_class->id;
?>'>Mug</a></td>
<td><a href='spy.php?id=<?php
echo $profile_class->id;
?>'>Spy</a></td>
</tr>
<tr>
<td><a href='sendmoney.php?person=<?php
echo $profile_class->id;
?>'>Send Money</a></td>
<td><a href='sendpoints.php?person=<?php
echo $profile_class->id;
?>'>Send Points</a></td>
<td><a href='sendcredits.php?person=<?php
echo $profile_class->id;
?>'>Send Credits</a></td>

<?php
$resultlala = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid` = ? AND `type` = '1'");
$resultlala->execute(array(
$user_class->id
));
$workedlala = $resultlala->fetch(PDO::FETCH_ASSOC);
if ($workedlala['contactid'] == $profile_class->id) {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=friend'>Remove Friend</a>
</td>
<?php

} else {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=friend'>Add Friend</a>
</td>

<?php

}

?>
</tr>

<tr><?php
$resultlala = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid` = ? AND `type` = '2'");
$resultlala->execute(array(
$user_class->id
));
$workedlala = $resultlala->fetch(PDO::FETCH_ASSOC);
if ($workedlala['contactid'] == $profile_class->id) {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=enemy'>Remove Enemy</a>
</td>
<?php

} else {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=enemy'>Add Enemy</a>
</td>
<?php
$resulthaha = $GLOBALS['pdo']->prepare("SELECT * FROM `ignorelist` WHERE `blocker` = ?");
$resulthaha->execute(array(
$user_class->id
));
$workedhaha = $resulthaha->fetch(PDO::FETCH_ASSOC);
}

if ($workedhaha['blocked'] == $profile_class->id) {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=ignore'>Remove Ignore</a>
</td>
<?php

} else {
?>
<td>
<a href='profiles.php?id=<?php
echo $profile_class->id;
?>&contact=ignore'>Add Ignore</a>
</td>
<?php

}

if ($user_class->relplayer != $profile_class->id) {
?>

<td><a href='relationship.php?action=new&player=<?php
echo $profile_class->id;
?>'>Request Relationship</a></td>

<?php

} else {
?>

<td><a href='relationship.php?action=end&player=<?php
echo $profile_class->id;
?>'>End Relationship</a></td>

<?php

}
}
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>



<td>
	<a href='gmpanel.php?page=eventlog&player=<?php
echo $profile_class->id;
?>'>View Events</a>
</td>

</tr>

<tr>
<td>
	<a href='gmpanel.php?page=tralog&player=<?php
echo $profile_class->id;
?>'>View Transfers</a>
</td>
<td>
	<a href='gmpanel.php?page=vaultlog&player=<?php
echo $profile_class->id;
?>'>View Vault Log</a>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<?php

} else {
?>

<td>&nbsp;</td>
</tr>

<?php

}
}
?>
<?php
if ($profile_class->badge != 0) {
?>

</table>

<!-- Area has been commented out due to broken HTML/CSS 3/26/2019 Resolution TBD
<table class="inverted ui unstackable column small compact table">

<thead>
<tr>
<th>Achievements</th>
<th></th>
</tr>
</thead>
<tr>
<?php
echo ($profile_class->badge1 != "") ? "<td>" . $profile_class->badge1 . "</td>" : "";
?>
<?php
echo ($profile_class->badge2 != "") ? "<td>" . $profile_class->badge2 . "</td>" : "";
?>
<?php
echo ($profile_class->badge3 != "") ? "<td>" . $profile_class->badge3 . "</td>" : "";
?>
<?php
echo ($profile_class->badge4 != "") ? "<td>" . $profile_class->badge4 . "</td>" : "";
?>
<?php
echo ($profile_class->badge5 != "") ? "<td>" . $profile_class->badge5 . "</td>" : "";
?>
<?php
echo ($profile_class->badge6 != "") ? "<td>" . $profile_class->badge6 . "</td>" : "";
?>
<?php
echo ($profile_class->badge7 != "") ? "<td>" . $profile_class->badge7 . "</td>" : "";
?>
<?php
echo ($profile_class->badge8 != "") ? "<td>" . $profile_class->badge8 . "</td>" : "";
?>
<?php
echo ($profile_class->badge9 != "") ? "<td>" . $profile_class->badge9 . "</td>" : "";
?>
<?php
echo ($profile_class->badge10 != "") ? "<td>" . $profile_class->badge10 . "</td>" : "";
?>
<?php
echo ($profile_class->badge11 != "") ? "<td>" . $profile_class->badge11 . "</td>" : "";
?>
</tr>
</table>

<?php

}

?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Signature</th>
</tr>
</thead>
<tr>
<td>
<?php
echo strip_tags($profile_class->sig);
?>
</td>
</tr>
</table>
---->
<?php
if ($user_class->admin == 1 || $user_class->st == 1) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$result->execute(array(
$_GET['id']
));
$worked = $result->fetch(PDO::FETCH_ASSOC);
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Change Role</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<?php
if ($worked['admin'] == 0 && $worked['gm'] == 0 && $worked['fm'] == 0 && $worked['cm'] == 0 && $worked['eo'] == 0) {
?>
<tr>
<form method="post">
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td align="center">
<input class="ui mini red button" type="submit" name="adminstatus" value="Make Admin" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="gmstatus" value="Make Game Mod" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="fmstatus" value="Make Forum Mod" />
</td>
</tr>
<tr>
<td align="center">
<input class="ui mini red button" type="submit" name="cmstatus" value="Make Chat Mod" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="eostatus" value="Make Event Organiser" />
</td>
<td align="center">
<input class="ui mini red button"  type="submit" name="ststatus" value="Make Sergeant" />
</td>
</form>
<?php

} else {
?>
<form method="post">
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td align="center">
<input class="ui mini red button" type="submit" name="revokeadminstatus" value="Revoke Admin" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="revokegmstatus" value="Revoke Game Mod" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="revokefmstatus" value="Revoke Forum Mod" />
</td>
</tr>
<tr>
<td align="center">
<input class="ui mini red button" type="submit" name="revokecmstatus" value="Revoke Chat Mod" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="revokeeostatus" value="Revoke Event Organiser" />
</td>
<td align="center">
<input class="ui mini red button" type="submit" name="revokeststatus" value="Revoke Sergeant" />
</td>
</form>
</tr>
</table>
<?php

}

?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Add As Staff</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tr>
<form method="post">
<td>Change Bank:</td>
<td><input type="text" class="ui input" name="bank" size="20" value="0" /></td>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><input class="ui mini red button" type="submit" name="cbank" value="Change Bank" /></td>
</form>
</tr>

<tr>
<form method="post">
<td>Add Points:</td>
<td><input type="text" class="ui input" name="points" size="20" /></td>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><input class="ui mini red button" type="submit" name="addcpoints" value="Add Points" /></td>
</form>
</tr>

<tr>
<form method="post">
<td>Add Credits: </td>
<td><input type="text" class="ui input" name="credits" size="20" /></td>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><input class="ui mini red button" type="submit" name="addcredits" value="Add Credits" /></td>
</form>
</tr>
</table>
<?php
if ($user_class->admin == 1 || $user_class->gm == 1 || $user_class->fm == 1) {
if ($profile_class->admin != 1 || $user_class->id != $profile_class->id) {
$type1 = "perm";
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`=? AND `type`=?");
$result1->execute(array(
$profile_class->id,
$type1
));
$worked1 = $result1->fetch(PDO::FETCH_ASSOC);
$type2 = "forum";
$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`=? AND `type`=?");
$result2->execute(array(
$profile_class->id,
$type2
));
$worked2 = $result2->fetch(PDO::FETCH_ASSOC);
$type3 = "freeze";
$result3 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`=? AND `type`=?");
$result3->execute(array(
$profile_class->id,
$type3
));
$worked3 = $result3->fetch(PDO::FETCH_ASSOC);
$type4 = "mail";
$result4 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`=? AND `type`=?");
$result4->execute(array(
$profile_class->id,
$type4
));
$worked4 = $result4->fetch(PDO::FETCH_ASSOC);
$type7 = "quicka";
$result7 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `id`= ? AND `type`=? ");
$result7->execute(array(
$profile_class->id,
$type7
));
$worked7 = $result7->fetch(PDO::FETCH_ASSOC);
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Ban Option</th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<?php
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>
<tr>
<form method="post">
<td>[Game Ban]&nbsp;</td>
<td>Days:&nbsp;</td>
<td><?php
if ($worked1['days'] >= 1) {
?><input type="text" class="ui input" name="days" DISABLED value="<?php
echo $worked1['days'];
?>" />
</td><?php

} else {
?><input type="text" class="ui input" name="days" /><?php

}

?>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><?php
if ($worked1['days'] == 0) {
?><input class="ui mini red button" type="submit" name="permban" value="Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="permban" value="Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked1['days'] >= 1) {
?><input class="ui mini red button"type="submit" name="unpermban" value="Un-Ban" /><?php

} else {
?><input class="ui mini red button"type="submit" name="unpermban" value="Un-Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked1['days'] >= 1) {
?>[Banned - <?php
echo prettynum($worked1['days']);
?> days] <?php

}

?></td>
</form>
<?php

}

?>

<?php
if ($user_class->admin == 1 || $user_class->gm == 1 || $user_class->fm == 1) {
?>
<tr>
<form method="post">
<td>[Forum Ban]&nbsp;</td>
<td>Days:&nbsp;</td>
<td><?php
if ($worked2['days'] >= 1) {
?><input type="text" class="ui input" name="days" DISABLED value="<?php
echo $worked2['days'];
?>" /></td><?php

} else {
?><input type="text" class="ui input" name="days" /><?php

}

?>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><?php
if ($worked2['days'] == 0) {
?><input class="ui mini red button" type="submit" name="forumban" value="Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="forumban" value="Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked2['days'] >= 1) {
?><input class="ui mini red button" type="submit" name="unforumban" value="Un-Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="unforumban" value="Un-Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked2['days'] >= 1) {
?>[Banned - <?php
echo prettynum($worked2['days']);
?> days] <?php

}

?></td>
</form>
</td>
</tr>
<?php

}

?>

<?php
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>
<tr>
<form method="post">
<td>[Mail Ban]&nbsp;</td>
<td>Days:&nbsp;</td>
<td><?php
if ($worked4['days'] >= 1) {
?><input type="text" class="ui input" name="days" DISABLED value="<?php
echo $worked4['days'];
?>" /></td><?php

} else {
?><input type="text" class="ui input" name="days" /><?php

}

?>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><?php
if ($worked4['days'] == 0) {
?><input class="ui mini red button" type="submit" name="mailban" value="Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="mailban" value="Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked4['days'] >= 1) {
?><input class="ui mini red button" type="submit" name="unmailban" value="Un-Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="unmailban" value="Un-Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked4['days'] >= 1) {
?>[Banned - <?php
echo prettynum($worked4['days']);
?> days] <?php

}

?></td>
</form>
</td>
</tr>
<?php

}

?>
<?php
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>
<tr>
<form method="post">
<td>[Quick Ads Ban]&nbsp;</td>
<td>Days:&nbsp;</td>
<td><?php
if ($worked7['days'] >= 1) {
?><input type="text" class="ui input" name="days" DISABLED value="<?php
echo $worked7['days'];
?>" /></td><?php

} else {
?><input type="text" class="ui input" name="days" /><?php

}

?>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><?php
if ($worked7['days'] == 0) {
?><input class="ui mini red button" type="submit" name="qaban" value="Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="qaban" value="Ban" DISABLED id="qaban" />
<?php

}

?></td>
<td><?php
if ($worked7['days'] >= 1) {
?><input class="ui mini red button" type="submit" name="unqaban" value="Un-Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="unqaban" value="Un-Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked7['days'] >= 1) {
?>[Banned - <?php
echo prettynum($worked7['days']);
?> days] <?php

}

?></td>
</form>
<?php

}

?>

<?php
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>
<tr>
<form method="post">
<td>[Freeze Acct]&nbsp;</td>
<td>Days:&nbsp;</td>
<td><?php
if ($worked3['days'] >= 1) {
?><input type="text" class="ui input" name="days" DISABLED value="<?php
echo $worked3['days'];
?>" /></td><?php

} else {
?><input type="text" class="ui input" name="days" /><?php

}

?>
<td><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /></td>
<td><?php
if ($worked3['days'] == 0) {
?><input class="ui mini red button" type="submit" name="freeze" value="Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="freeze" value="Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked3['days'] >= 1) {
?><input class="ui mini red button" type="submit" name="unfreeze" value="Un-Ban" /><?php

} else {
?><input class="ui mini red button" type="submit" name="unfreeze" value="Un-Ban" DISABLED /><?php

}

?></td>
<td><?php
if ($worked3['days'] >= 1) {
?>[Banned - <?php
echo prettynum($worked3['days']);
?> days] <?php

}

?></td>
</form>
</td>
</tr>
<?php

}

?>
</table>
<?php

}
}

?>
<?php
if ($user_class->admin == 1 || $user_class->gm == 1) {
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Add/Change Flag</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<form method="post">
<tr>
<td>
<select name="flag">
<option value="0">None</option>
<option value="scammer-tag.gif">Scammer Flag</option>
<option value="liar-tag.gif">Liar Flag</option>
<option value="american.gif">american Flag</option>
<option value="romanian.gif">romania Flag</option>
<option value="badass.gif">badass flag</option>
</select>
</td>
<td>
<input class="ui mini red button" type="submit" name="changeflag" value="Add/Change Flag" />
</td>
</tr>
</form>
</table>

<?php

}

?>

<?php
if ($user_class->admin == 1) {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>General Information</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tr>
<td width='15%'>Name:</td>
<td><a href='profiles.php?id=<?php
echo $profile_class->id;
?>'><?php
echo $profile_class->formattedname;
?></a></td>
<td width='15%'>HP:</td>

<td><?php
echo prettynum($profile_class->formattedhp);
?></td>
</tr>

<tr>
<td width='15%'>Level:</td>
<td><?php
echo $profile_class->level;
?></td>
<td width='15%'>Energy:</td>
<td><?php
echo prettynum($profile_class->formattedenergy);
?></td>

</tr>

<tr>
<td width='15%'>Money:</td>
<td>$<?php
echo prettynum($profile_class->money);
/*money_format('%(#10n', $user_class->money);*/
?></td>
<td width='15%'>Awake:</td>
<td><?php
echo prettynum($profile_class->formattedawake);
?></td>
</tr>

<tr>
<td width='15%'>Bank:</td>
<td>$<?php
echo prettynum($profile_class->bank);
/*money_format('%(#10n', $user_class->bank);*/
?></td>
<td width='15%'>Nerve:</td>
<td><?php
echo prettynum($profile_class->formattednerve);
?></td>
</tr>

<tr>
<td width='15%'>EXP:</td>
<td><?php
echo prettynum($profile_class->formattedexp);
?></td>
<td width='15%'>Work EXP:</td>
<td><?php
echo prettynum($profile_class->workexp);
?></td>
</tr>
<tr>
<td width='15%'>Marijuana:</td>
<td><?php
echo prettynum($user_class->marijuana);
?></td>
<td width='15%'>Points:</td>
<td><?php
echo prettynum($profile_class->points);
?></td>
</tr>
<tr>
<td width='15%'>IP:</td>
<td><?php
echo $profile_class->ip;
?></td>
<td width='15%'>Email:</td>
<td><?php
echo $profile_class->email;
?></td>
</tr>
<tr>
<td width='15%'>Credits:</td>
<td><?php
echo $profile_class->credits;
?></td>
<td width='15%'></td>
<td></td>
</tr>
<tr>
<td width='15%'>&nbsp;</td>
</tr>
<tr>
<td width='15%'>Strength:</td>
<td><?php
echo prettynum($profile_class->strength);
?></td>

<td width='15%'>Defense:</td>
<td><?php
echo prettynum($profile_class->defense);
?></td>
</tr>
<tr>
<td width='15%'>Speed:</td>
<td><?php
echo prettynum($profile_class->speed);
?></td>
<td width='15%'>Total:</td>

<td><?php
echo prettynum($profile_class->totalattrib);
?></td>
</tr>
<tr>
<td width='15%'>&nbsp;</td>
</tr>
<tr>
<td width='15%'>Strength:</td>
<td><?php
echo prettynum($profile_class->moddedstrength);
?></td>

<td width='15%'>Defense:</td>
<td><?php
echo prettynum($profile_class->moddeddefense);
?></td>
</tr>
<tr>
<td width='15%'>Speed:</td>
<td><?php
echo prettynum($profile_class->moddedspeed);
?></td>
<td width='15%'>Total:</td>

<td><?php
echo prettynum($profile_class->moddedtotalattrib);
?></td>
</tr>
</table>

<?php

}

if ($user_class->admin == 1) {
$invent = Check_Invent($profile_class->id);
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Your Inventory</th>
</tr>
</thead>
<tr>
<td>
<center>
Below is a list of all the items you have collected.<br /><br />
<b>Inventory Usage:</b>&nbsp;<?php
echo $invent;
?>/<?php
echo $profile_class->invent;
?>
</center>
</td>
</tr>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Equipped</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tr>
<td width='33.3%'>
<?php
if ($profile_class->eqweapon != 0) {
?>
<img src='<?php echo $profile_class->weaponimg
?>' width='100' height='100' style='border: 1px solid #000000'><br />
<?php echo item_popup($profile_class->weaponname, $profile_class->eqweapon) ?><br />
<?php

} else {
echo "You don't have a weapon equipped.";
}

?>
</td>
<td width='33.3%'>
<?php
if ($profile_class->eqarmor != 0) {
?>
<img src='<?php echo $profile_class->armorimg
?>' width='100' height='100' style='border: 1px solid #000000'><br />
<?php echo item_popup($profile_class->armorname, $profile_class->eqarmor) ?><br />
<?php

} else {
echo "You don't have any armor equipped.";
}

?>
</td>
<td width='33.3%'>
<?php
if ($profile_class->eqshoes != 0) {
?>
<img src='<?php echo $profile_class->shoesimg
?>' width='100' height='100' style='border: 1px solid #000000'><br />
<?php echo item_popup($profile_class->shoesname, $profile_class->eqshoes) ?><br />
<?php

} else {
echo "You don't have any shoes equipped.";
}

?>
</td>
</tr>
</table>


<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `inventory` WHERE `userid` = ? ORDER BY `userid` DESC");
$result->execute(array(
$profile_class->id
));
$li = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($li as $line) {
$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `items` WHERE `id`=?");
$result2->execute(array(
$line['itemid']
));
$worked2 = $result2->fetch(PDO::FETCH_ASSOC);
if ($worked2['offense'] > 0 && $worked2['rare'] == 0) {
$weapons .= "

<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
$" . prettynum($worked2['cost']) . "<br />
</td>
";
$howmanyitems = $howmanyitems + 1;
if ($howmanyitems == 4) {
$weapons .= "</tr><tr height='15'></tr><tr>";
$howmanyitems = 0;
}
}

if ($worked2['defense'] > 0 && $worked2['rare'] == 0) {
$armor .= "

<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
$" . prettynum($worked2['cost']) . "<br />
</td>
";
$howmanyitems2 = $howmanyitems2 + 1;
if ($howmanyitems2 == 4) {
$armor .= "</tr><tr height='15'></tr><tr>";
$howmanyitems2 = 0;
}
}

if ($worked2['speed'] > 0 && $worked2['rare'] == 0) {
$shoes .= "

<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
$" . prettynum($worked2['cost']) . "<br />
</td>
";
$howmanyitems3 = $howmanyitems3 + 1;
if ($howmanyitems3 == 4) {
$shoes .= "</tr><tr height='15'></tr><tr>";
$howmanyitems3 = 0;
}
}

if ($worked2['petupgrades'] != 0) {
$pets .= "

<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
</td>
";
$howmanyitems5 = $howmanyitems5 + 1;
if ($howmanyitems5 == 4) {
$pets .= "</tr><tr height='15'></tr><tr>";
$howmanyitems5 = 0;
}
}

if ($worked2['rare'] == 1) {
$rares .= "

<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
</td>
";
$howmanyitems6 = $howmanyitems6 + 1;
if ($howmanyitems6 == 4) {
$rares .= "</tr><tr height='15'></tr><tr>";
$howmanyitems6 = 0;
}
}

$result = $GLOBALS['pdo']->prepare("SELECT * FROM `gang_loans` WHERE `to` = ? ORDER BY `to` DESC");
$result->execute(array(
$profile_class->id
));
$lin = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($lin as $line) {
$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `items` WHERE `id`=?");
$result2->execute(array(
$line['item']
));
$worked2 = $result2->fetch(PDO::FETCH_ASSOC);
if ($worked2['offense'] > 0 || $worked2['defense'] > 0 || $worked2['speed'] > 0) {
$loaned .= "
<td>

<img src='" . $worked2['image'] . "' width='100' height='100' style='border: 1px solid #000000'><br />
" . item_popup($worked2['itemname'], $worked2['id']) . " [x" . $line['quantity'] . "]<br />
$" . prettynum($worked2['cost']) . "<br />
</td>
";
$howmanyitems = $howmanyitems + 1;
if ($howmanyitems == 4) {
$loaned .= "</tr><tr height='15'></tr><tr>";
$howmanyitems = 0;
}
}
}

if ($weapons != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Weapons</th>
</tr>
</thead>
<tr>
<?php
echo $weapons;
?>
</tr>
</table>
<?php

}

if ($armor != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Armor</th>
</tr>
</thead>
<tr>
<?php
echo $armor;
?>
</tr>
</table>
</td>
</tr>
<?php

}

if ($shoes != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Shoes</th>
<th></th>
</tr>
</thead>
<tr>
<?php
echo $shoes;
?>
</tr>
</table>
</td>
</tr>
<?php

}

if ($loaned != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Items Loaned From Gang</th>
</tr>
</thead>
<tr>
<?php
echo $loaned;
?>
</tr>
</table>
</td>
</tr>
<?php

}

if ($rares != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Rare Items</th>
</tr>
</thead>
<tr>
<?php
echo $rares;
?>
</tr>
</table>
</td>
</tr>
<?php

}

if ($drugs != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Drugs</th>
</tr>
</thead>
<tr>
<?php
echo $drugs;
?>
</tr>
</table>
<?php

}

if ($pets != "") {
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Pet Items</th>
</tr>
</thead>
<tr>
<?php
echo $pets;
?>
</tr>
</table>
<?php

}
}
}

if ($user_class->admin == 1 || $user_class->gm == 1 || $user_class->fm == 1) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id`=?");
$result1->execute(array(
$profile_class->id
));
$worked1 = $result1->fetch(PDO::FETCH_ASSOC);
$notes = $worked1['notes'];
?>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Players Notes</th>
</tr>
</thead>
<form method="post">
<tr>
<td align="center"><textarea name="notes" class="ui input" cols="76" rows="6"><?php
echo $notes;
?></textarea></td>
</tr>
<tr>
<td align="center"><input type="hidden" name="id" value="<?php
echo $profile_class->id;
?>" /><input class="ui mini red button" type="submit" name="addnotes" value="Add Notes" /><br> <input class="ui mini red button" type="submit" name="5ips" value="Get last 5 IPs" />
</td>
</tr>
</form>
</table>

<?php

}

if ($user_class->admin > 0) {
if ($profile_class->volume == 10) {
$selected1 = ' selected="true"';
} else
if ($profile_class->volume == 20) {
$selected2 = ' selected="true"';
} else
if ($profile_class->volume == 30) {
$selected3 = ' selected="true"';
} else
if ($profile_class->volume == 40) {
$selected4 = ' selected="true"';
} else
if ($profile_class->volume == 50) {
$selected5 = ' selected="true"';
} else
if ($profile_class->volume == 60) {
$selected6 = ' selected="true"';
} else
if ($profile_class->volume == 70) {
$selected7 = ' selected="true"';
} else
if ($profile_class->volume == 80) {
$selected8 = ' selected="true"';
} else
if ($profile_class->volume == 90) {
$selected9 = ' selected="true"';
} else
if ($profile_class->volume == 100) {
$selected10 = ' selected="true"';
}

?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>Account Preference</th>
</tr>
</thead>
<form method='post' action="test.php?id=<?php
echo $_GET['id']; ?>">
<tr>
<td><b>Name:</b></td>
<td>
<input type='text' class="ui input" name='username' value="<?php
echo $profile_class->username; ?>" maxlength="16">&nbsp;<span style="font-size:10px;">Will NOT change your login name.</span>
</font>
</td>
</tr>
<tr>
<td><b>Email:</b></td>
<td>
<input type='text' class="ui input" name='email' value="<?php
echo strip_tags($profile_class->email); ?>" size="30">
</td>
</tr>
<tr>
<td><b>Avatar:</b></td>
<td>
<input type='text' class="ui input" name='avatar' size="40" value="<?php
echo strip_tags(addslashes($profile_class->avatar)); ?>">&nbsp;<span style="font-size:10px;">Upload avatar to <a href="http://tinypic.com" title="Tinypic Image Hosting" target="_blank">Tinypic</a>.</span>
</td>
</tr>
<tr>
<tr>
<td><b>Quote:</b></td>
<td>
<input type='text' class="ui input" name='quote' size="85" maxlength="300" value="<?php
echo strip_tags($profile_class->quote); ?>">
</td>
</tr>
<tr>
<td><b>Music:</b></td>
<td>
<?php
if ($profile_class->promusic == 1) {
echo '<select name="music" >
<option value="1" selected="true">Yes</option>
<option value="0">No</option>
</select>';
} else
if ($profile_class->promusic == 0) {
echo '<select name="music" >
<option value="1">Yes</option>
<option value="0" selected="true">No</option>
</select>';
}

?>
</td>
</tr>
<tr>
<td><b>Volume:</b></td>
<td>
<?php
echo '<select name="volume" >
<option value="10"' . $selected1 . '>10%</option>
<option value="20"' . $selected2 . '>20%</option>
<option value="30"' . $selected3 . '>30%</option>
<option value="40"' . $selected4 . '>40%</option>
<option value="50"' . $selected5 . '>50%</option>
<option value="60"' . $selected6 . '>60%</option>
<option value="70"' . $selected7 . '>70%</option>
<option value="80"' . $selected8 . '>80%</option>
<option value="90"' . $selected9 . '>90%</option>
<option value="100"' . $selected10 . '>100%</option>
</select>';
?>
</td>
</tr>
<tr>
<td><b>Gender:</b></td>
<td>
<?php
if ($profile_class->gender == "Male") {
echo '<select name="gender" >
<option value="Male" selected="true">Male</option>
<option value="Female">Female</option>
</select>';
} else
if ($profile_class->gender == "Female") {
echo '<select name="gender" >
<option value="Male">Male</option>
<option value="Female" selected="true">Female</option>
</select>';
}

?>
</td>
</tr>
<tr>
<td><b>Signature:</b></td>
<td>
<textarea type='text' class="ui input" name='signature' cols='64' rows='6'><?php
echo strip_tags($profile_class->sig); ?></textarea>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
<input class="ui mini red button" type='submit' name='submits' id="submits" value='Save Preferences' />
</td>
</tr>
</table>
</form>

<?php

}
}



include 'footer.php';

?>
