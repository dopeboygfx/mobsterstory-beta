<?
include 'header.php';

if($_GET['remove'] != "") {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `id` = ? AND `playerid` = ?");
$result->execute(array($_GET['remove']),$user_class->id);
$worked = $result->fetch(PDO::FETCH_ASSOC);

if($worked['id'] != "") {
$user = new User($worked['contactid']);
$delete = $GLOBALS['pdo']->prepare("DELETE FROM `contactlist` WHERE `id` = ?");
$delete->execute(array($_GET['remove']));
echo Message("You have successfully removed ".$user->formattedname." from your contact list.");
} else {
	echo Message("That contact doesn't exist.");
}
}

if($_GET['edit'] != "") {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `id` = ? AND `playerid` = ?");
$result->execute(array($_POST['edit'], $user_class->id));
$worked = $resut->fetch(PDO::FETCH_ASSOC);

if($worked['id'] != "") {
if($worked['type'] == 1) {
	$select1 = " selected='true'";
} else {
	$select2 = " selected='true'";
}
$user = new User($worked['contactid']);
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Edit Contact</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post" action="contactlist.php">
<tr>
	<td><b>Contact:</b></td>
    <td><?php echo $user->formattedname; ?></td>
</tr>
<tr>
	<td><b>Type:</b></td>
    <td>
    <select name="type">
    <option value="1"<?php echo $select1; ?>>Friend</option>
    <option value="2"<?php echo $select2; ?>>Enemy</option>
    </select>
    </td>
</tr>
<tr>
	<td><b>Notes:</b></td>
    <td><input type="text" name="notes" size="60" maxlength="60" value="<?php echo $worked['notes']; ?>" /> max 60 characters.</td>
</tr>
<tr>
	<td>&nbsp;</td>
    <td><input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>" /><input type="submit" name="sedit" value="Edit Contact" /></td>
</form>
</table>
</td></tr>
<?php
} else {
	echo Message("That contact doesn't exist.");
}
}

if($_POST['id'] != "" && isset($_POST['sedit'])) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `id` = ? AND `playerid` = ?");
$result->execute(array($_POST['id'], $user_class->id));
$worked = $result->fetch(PDO::FETCH_ASSOC);

if($worked['id'] != "") {
$user = new User($worked['contactid']);
$_POST['notes'] = str_replace('"', '', $_POST['notes']);
$delete = $GLOBALS['pdo']->prepare("UPDATE `contactlist` SET `notes` = ?, `type` = ? WHERE `id` = ?");
$delte->execute(array($_POST['notes'], $_POST['type'], $_POST['id']));
echo Message("You have successfully edited your contact list.");
$_GET['edit'] = "";
} else {
	echo Message("That contact doesn't exist.");
}
}
?>

	<table class="inverted ui five unstackable column small compact table">
	<thead>
	    <tr>
	    <th colspan="6">Contact List</th>
  		</tr>
  		</thead>

    <td><b>ID</b></td>
    <td><b>Username</b></td>
    <td><b>Online</b></td>
    <td><b>Type</b></td>
    <td><b>Notes</b></td>
    <td><b>Actions</b></td>
    </tr>
<?
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `contactlist` WHERE `playerid` = ? ORDER BY `id` ASC");
$result->execute(array($user_class->id));
$result = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($result AS $line){
	$contact_class = new User($line['contactid']);
	if ($line['type'] == 1) {
	$type = "Friend";
	} else {
	$type = "Enemy";
	}
	echo "<tr><td width='8%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'>".$contact_class->id."</td><td width='25%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'>".$contact_class->formattedname."</td><td width='15%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'>".$contact_class->formattedonline."</td><td width='10%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'>".$type."</td><td width='32%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'><span style='font-size: 11px;'>".$line['notes']."</font></td><td width='10%' style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;'><a href='contactlist.php?remove=".$line['id']."'>Remove</a><br /><a href='contactlist.php?edit=".$line['id']."'>Edit</a></td></tr>";
	}

include 'footer.php';
?>