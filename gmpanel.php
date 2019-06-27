<?php
include 'gmheader.php';

//Delete Post
if(isset($_POST['deletepost'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `postid` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("DELETE FROM `freplies` WHERE `postid` = ?");
$result->execute(array($_POST['postid']));
echo Message("The reported post was deleted.");
StaffLog($user_class->id, "[-_USERID_-] has deleted a post. Reported by: [-_USERID2_-].", $worked['reporter']);
}

//Un-report Post
if(isset($_POST['unreport'])) {
$result = $GLOBALS['pdo']->prepare("UPDATE `freplies` SET `reported` = '0', `reporter` = '0' WHERE `postid` = ?");
$result->execute(array($_POST['postid']));
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `postid` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
echo Message("The post was un-reported.");
StaffLog($user_class->id, "[-_USERID_-] has un-reported a post. Reported by: [-_USERID2_-].", $worked['reporter']);
}

//Delete Thread
if(isset($_POST['deletetopic'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("DELETE FROM `ftopics` WHERE `forumid` = ?");
$result->execute(array($_POST['postid']));
$result = $GLOBALS['pdo']->prepare("DELETE FROM `freplies` WHERE `topicid` = ?");
$result->execute(array($_POST['postid']));
echo Message("The reported topic was deleted.");
StaffLog($user_class->id, "[-_USERID_-] has deleted a topic. Reported by: [-_USERID2_-].", $worked['reporter']);
}

//Un-report Thread
if(isset($_POST['unreporttopic'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
echo Message("The topic was un-reported.");
StaffLog($user_class->id, "[-_USERID_-] has un-reported a thread. Reported by: [-_USERID2_-].", $worked['reporter']);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `reported` = '0', `reporter` = '0' WHERE `forumid` = ?");
$result->execute(array($_POST['postid']));
}

//Un-report Profile
if(isset($_POST['unreportprofile'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
echo Message("The profile was un-reported.");
StaffLog($user_class->id, "[-_USERID_-] has un-reported a profile. Reported by: [-_USERID2_-].", $worked['reporter']);
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `reported` = '0', `reporter` = '0' WHERE `id` = ?");
$result->execute(array($_POST['postid']));
}

//Un-report Mail
if(isset($_POST['unreportmail'])) {
$result = $GLOBALS['pdo']->prepare("UPDATE `maillog` SET `reported` = '0' WHERE `id` = ?");
$result->execute(array($_POST['postid']));
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `maillog` WHERE `id` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
echo Message("The mail was un-reported.");
StaffLog($user_class->id, "[-_USERID_-] has un-reported a personal message. Reported by: [-_USERID2_-].", $worked['to']);
}

if($_GET['page'] == "fbans") {
?>

	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Forum Banned List</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Days</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Banned By</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'forum' ORDER BY `days` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
	if(count($res) > 0) {
		foreach($res AS $line){
	$banned_user = new User($line['id']);
	$banned_by = new User($line['bannedby']);
	echo '<tr><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_user->formattedname.'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="20%">'.prettynum($line['days']).'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_by->formattedname.'</td></tr>';
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />Noone is currently forum banned.";
	}
	?>
	</td></tr>

<?php 
include 'footer.php';
die();
} 

if($_GET['page'] == "bans") {
?>

	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Game Banned List</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Days</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Banned By</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'perm' ORDER BY `days` DESC");
	$result->execute();
	$res = $result->fetch(PDO::FETCH_ASSOC);
	if($res) {
		$fr = $result->fetchALL(PDO::FETCH_ASSOC);
		foreach ($res AS $line){
	$banned_user = new User($line['id']);
	$banned_by = new User($line['bannedby']);
	echo '<tr><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_user->formattedname.'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="20%">'.prettynum($line['days']).'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_by->formattedname.'</td></tr>';
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />Noone is currently game banned.";
	}
	?>
	</td></tr>

<?php 
include 'footer.php';
die();
} 

if($_GET['page'] == "mbans") {
?>

	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Mail Banned List</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Days</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Banned By</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'mail' ORDER BY `days` DESC");
	$result->execute();
	$res = $result->fetch(PDO::FETCH_ASSOC);
	if(count($res)) {
		$re = $result->fetchALL(PDO::FETCH_ASSOC);
		foreach ($re AS $line){
	$banned_user = new User($line['id']);
	$banned_by = new User($line['bannedby']);
	echo '<tr><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_user->formattedname.'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="20%">'.prettynum($line['days']).'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_by->formattedname.'</td></tr>';
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />Noone is currently mail banned.";
	}
	?>
	</td></tr>

<?php 
include 'footer.php';
die();
} 

if($_GET['page'] == "qa") {
?>

	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Quick Ad bans List</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Days</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Banned By</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'quicka' ORDER BY `days` DESC");
	$result->execute();
	$res = $result->fetch(PDO::FETCH_ASSOC);
	if($res) {
		$re = $result->fetchALL(PDO::FETCH_ASSOC);
		foreach ($re AS $line){
	$banned_user = new User($line['id']);
	$banned_by = new User($line['bannedby']);
	echo '<tr><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_user->formattedname.'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="20%">'.prettynum($line['days']).'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_by->formattedname.'</td></tr>';
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />Noone is currently banned.";
	}
	?>
	</td></tr>

<?php 
include 'footer.php';
die();
} 

if($_GET['page'] == "freeze") {
?>

	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Frozen Accounts List</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Days</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Banned By</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'freeze' ORDER BY `days` DESC");
	$result->execute();
	$res = $result->fetch(PDO::FETCH_ASSOC);
	if($re) {
		foreach ($re AS $line){
	$banned_user = new User($line['id']);
	$banned_by = new User($line['bannedby']);
	echo '<tr><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_user->formattedname.'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="20%">'.prettynum($line['days']).'</td><td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444;" width="40%">'.$banned_by->formattedname.'</td></tr>';
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />Noone is currently frozen.";
	}
	?>
	</td></tr>

<?php 
include 'footer.php';
die();
} 
if($_GET['page'] == "reportpost") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Reported Posts</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Post</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Reported By</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Delete Post</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Un-Report</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `reported` = '1' ORDER BY `timesent` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$output = BBCodeParse(strip_tags($line['body']));
	$reported_by = new User($line['reporter']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='45%'>".$output."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='25%'>".$reported_by->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='15%'><form method='post'><input type='hidden' name='postid' value='".$line['postid']."' /><input type='submit' name='deletepost' value='Delete' /></form></td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='15%'><form method='post'><input type='hidden' name='postid' value='".$line['postid']."' /><input type='submit' name='unreport' value='Un-Report' /></form></td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are currently no reported posts.";
	}
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "reportthread") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Reported Threads</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Thread</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Reported By</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Delete Post</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Un-Report</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `reported` = '1' ORDER BY `lastreply` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach ($res AS $line){
	$reported_by = new User($line['reporter']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='45%'><a href='viewpost.php?id=".$line['forumid']."' target='_blank'>".$line['subject']."</a></td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='25%'>".$reported_by->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='15%'><form method='post'><input type='hidden' name='postid' value='".$line['forumid']."' /><input type='submit' name='deletetopic' value='Delete' /></form></td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='15%'><form method='post'><input type='hidden' name='postid' value='".$line['forumid']."' /><input type='submit' name='unreporttopic' value='Un-Report' /></form></td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are currently no reported threads.";
	}
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "reportprofile") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Reported Profiles</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Profile</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Reported By</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Un-Report</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `reported` = '1' ORDER BY `id` ASC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
			$reported_by = new User($line['reporter']);
	$user = new User($line['id']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='40%'>".$user->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='40%'>".$reported_by->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='20%'><form method='post'><input type='hidden' name='postid' value='".$line['id']."' /><input type='submit' name='unreportprofile' value='Un-Report' /></form></td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are currently no reported profiles.";
	}
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "maillog") {

if(isset($_GET['filter'])) {

	$to = $_GET['to'];
	$from = $_GET['from'];
	
	if($to != "" && $from != "") { //Both Fields
		$sql = " WHERE `to` = '".$to."' AND `from` = '".$from."'";
	} else if($to == "" && $from != "") { //Just From
		$sql = " WHERE `from` = '".$from."'";
	} else if($to != "" && $from == "") { //Just To
		$sql = " WHERE `to` = '".$to."'";
	}
	
	$pages = "&to=$to&from=$from&filter=Filter";

}

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `maillog`".$sql."");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Mail Log</td></tr>
    <tr><td class="contentcontent">
    <table width="47%" cellspacing="0" border="0" align="center">
    <tr>
    <form method="get">
    <td width="6%"><b>To:</b></td><td width="10%"><input type="text" name="to" value="<?php echo $_GET['to']; ?>" size="4" /></td>
    <td width="5%">
    <td width="6%"><b>From:</b></td><td width="10%"><input type="text" name="from" value="<?php echo $_GET['from']; ?>" size="4" /></td>
    <td width="10%"><input type="hidden" name="page" value="maillog" /><input type="submit" name="filter" value="Filter" />
    </form>
    </tr>
    </table>
    <br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Mail</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>To</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>From</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `maillog`".$sql." ORDER BY `timesent` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$to = new User($line['to']);
	$from = new User($line['from']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='40%'><a href='gmpm.php?id=".$line['id']."' target='_blank'>".$line['subject']."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".$to->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".$from->formattedname."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no mail logs.";
	}
	echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1".$pages."'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x".$pages."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages".$pages."'>>></a> ";
} // end if
/****** end build pagination links ******/

    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "stafflog" && $user_class->admin == 1 ) {

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `staff_logs`");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Staff Logs</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Description</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time Logged</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `staff_logs` ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
	if($res){
	foreach ($res AS $line){
	$player = new User($line['player']);
	$player2 = new User($line['extra']);
	$text = str_replace("[-_USERID_-]", $player->formattedname, $line['text']);
	$text = str_replace("[-_USERID2_-]", $player2->formattedname, $text);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='82%'>".$text."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='18%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no staff logs.";
	}
	echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/

    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "attlog") {

if(isset($_GET['filter2'])) {

	$to = $_GET['att'];
	$from = $_GET['def'];
	
	if($to != "" && $from != "") { //Both Fields
		$sql = " WHERE `attacker` = '".$to."' AND `defender` = '".$from."'";
	} else if($to == "" && $from != "") { //Just From
		$sql = " WHERE `defender` = '".$from."'";
	} else if($to != "" && $from == "") { //Just To
		$sql = " WHERE `attacker` = '".$to."'";
	}
	
	$pages = "&att=$to&def=$from&filter2=Filter";

}

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `attacklog`".$sql."");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Attack Log</td></tr>
    <tr><td class="contentcontent">
    <table width="58%" cellspacing="0" border="0" align="center">
    <tr>
    <form method="get">
    <td width="9%"><b>Attacker:</b></td><td width="10%"><input type="text" name="att" value="<?php echo $_GET['att']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="6%"><b>Defender:</b></td><td width="10%"><input type="text" name="def" value="<?php echo $_GET['def']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="10%"><input type="hidden" name="page" value="attlog" /><input type="submit" name="filter2" value="Filter" />
    </form>
    </tr>
    </table>
    <br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Attacker</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Defender</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Winner</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Exp</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Money</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `attacklog`".$sql." ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$attacker = new User($line['attacker']);
	$defender = new User($line['defender']);
	$winner = new User($line['winner']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='22%'>".$attacker->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='22%'>".$defender->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='22%'>".$winner->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='9%'>".prettynum($line['exp'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='9%'>$".prettynum($line['money'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='16%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no attack logs.";
	}
		echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1".$pages."'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x".$pages."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages".$pages."'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}


if($_GET['page'] == "marketlog1") {

if(isset($_GET['filter3'])) {

	$to = $_GET['owner'];
	
	if($to != "") { //Just To
		$sql = " WHERE `owner` = '".$to."'";
	}
	
	$pages = "&owner=$tofilter3=Filter";

}

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `addptmarketlog`".$sql."");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Point Market Log [Added Points]</td></tr>
    <tr><td class="contentcontent">
    <table width="36%" cellspacing="0" border="0" align="center">
    <tr>
    <form method="get">
    <td width="9%"><b>Adder/Owner:</b></td><td width="10%"><input type="text" name="owner" value="<?php echo $_GET['owner']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="10%"><input type="hidden" name="page" value="marketlog1" /><input type="submit" name="filter3" value="Filter" />
    </form>
    </tr>
    </table>
    <br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Owner</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Amount</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Price [each]</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `addptmarketlog`".$sql." ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$owner = new User($line['owner']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".$owner->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='17%'>".prettynum($line['amount'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='17%'>$".prettynum($line['price'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='36%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no added point market logs.";
	}
		echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1".$pages."'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x".$pages."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages".$pages."'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "marketlog2") {

if(isset($_GET['filter3'])) {

	$to = $_GET['buyer'];
	$from = $_GET['owner'];
	
	if($to != "" && $from != "") { //Both Fields
		$sql = " WHERE `buyer` = '".$to."' AND `owner` = '".$from."'";
	} else if($to == "" && $from != "") { //Just From
		$sql = " WHERE `owner` = '".$from."'";
	} else if($to != "" && $from == "") { //Just To
		$sql = " WHERE `buyer` = '".$to."'";
	}
	
	$pages = "&buyer=$to&owner=$from&filter3=Filter";

}


//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `buyptmarketlog`".$sql."");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Point Market Log [Bought Points]</td></tr>
    <tr><td class="contentcontent">
    <table width="58%" cellspacing="0" border="0" align="center">
    <tr>
    <form method="get">
    <td width="9%"><b>Adder/Owner:</b></td><td width="10%"><input type="text" name="owner" value="<?php echo $_GET['owner']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="9%"><b>Buyer:</b></td><td width="10%"><input type="text" name="buyer" value="<?php echo $_GET['buyer']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="10%"><input type="hidden" name="page" value="marketlog2" /><input type="submit" name="filter3" value="Filter" />
    </form>
    </tr>
    </table>
    <br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Owner</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Buyer</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Amount</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Price [each]</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `buyptmarketlog`".$sql." ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach ($res AS $line){
	$owner = new User($line['owner']);
	$buyer = new User($line['buyer']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='26%'>".$owner->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='26%'>".$buyer->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='7%'>".prettynum($line['amount'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='10%'>$".prettynum($line['price'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='31%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no bought point market logs.";
	}
		echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1".$pages."'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x".$pages."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages".$pages."'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}



if($_GET['page'] == "marketlog3") {

if(isset($_GET['filter3'])) {

	$to = $_GET['owner'];
	
	if($to != "") { //Just To
		$sql = " WHERE `owner` = '".$to."'";
	}
	
	$pages = "&owner=$tofilter3=Filter";

}

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `removeptmarketlog`".$sql."");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Point Market Log [Removed Points]</td></tr>
    <tr><td class="contentcontent">
    <table width="36%" cellspacing="0" border="0" align="center">
    <tr>
    <form method="get">
    <td width="9%"><b>Adder/Owner:</b></td><td width="10%"><input type="text" name="owner" value="<?php echo $_GET['owner']; ?>" size="4" /></td>
    <td width="6%"></td>
    <td width="10%"><input type="hidden" name="page" value="marketlog3" /><input type="submit" name="filter3" value="Filter" />
    </form>
    </tr>
    </table>
    <br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Owner</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Amount</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Price [each]</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `removeptmarketlog`".$sql." ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
			$owner = new User($line['owner']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".$owner->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='17%'>".prettynum($line['amount'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='17%'>$".prettynum($line['price'])."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='36%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no removed point market logs.";
	}
		echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=1".$pages."'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$x".$pages."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&pnum=$totalpages".$pages."'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}


if(isset($_GET['player']) && $_GET['page'] == "eventlog") {

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `eventslog` WHERE `to` = '".$_GET['player']."'");
$result->execute();
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

$event = new User($_GET['player']);
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><?php echo $event->formattedname; ?>'s Events</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Description</b></td>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Recieved</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `eventslog` WHERE `to` = ? ORDER BY `timesent` DESC LIMIT $offset, $rowsperpage");
    $result->execute(array($_GET['player']));
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
	if($res) {
		foreach($res AS $line){
	$extra_user = new User($line['extra']);
	$extra_gang = new Gang($line['extra']);
	$text = str_replace('[-_USERID_-]', $extra_user->formattedname, $line['text']);
	$text = str_replace('[-_GANGID_-]', $extra_gang->nobanner, $text);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='68%'>".$text."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='32%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timesent'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no event logs.";
	}
			echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum=1&&player={$_GET['player']}'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum={$x}&&player={$_GET['player']}'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum={$totalpages}&&player={$_GET['player']}'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "eventlog") {
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Search Player Events</td></tr>
<tr><td class="contentcontent">
    <table width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <form method="get">
    <tr>
	<td width="15%"><b>Player ID:</b></td>
	<td width="85%">
    <input type="text" name="player" value="<?php echo $_GET['player']; ?>" />
    <input type="hidden" name="page" value="eventlog" />
    </td>
    </tr>
    <tr>
	<td width="15%">&nbsp;</td>
	<td width="85%"><input type="submit" value="Search Events" /></td>
    </tr>
    </form>
    </table>
</td></tr>

<?php
include 'footer.php';
die();
}

if(isset($_GET['player']) && $_GET['page'] == "vaultlog") {

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `vlog` WHERE `userid` = ?");
$result->execute(array($_GET['player']));
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 30;

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['pnum']) && is_numeric($_GET['pnum'])) {
   // cast var as int
   $currentpage = (int) $_GET['pnum'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if

// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;

$event = new User($_GET['player']);
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><?php echo $event->formattedname; ?>'s Vault Log</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Description</b></td>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Recieved</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `vlog` WHERE `userid` = ? ORDER BY `timestamp` DESC LIMIT $offset, $rowsperpage");
    $result->execute(array($_GET['player']));
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
   if($res) {
	   foreach($res AS $line){
	$extra_user = new User($line['userid']);
	$text = str_replace('[-_USERID_-]', $extra_user->formattedname, $line['text']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$text."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no vault logs.";
	}
			echo "<br />";
	
	/******  build the pagination links ******/
// range of num links to show
$range = 30;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum=1&&player={$_GET['player']}'><<</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum={$x}&&player={$_GET['player']}'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?page={$_GET['page']}&&pnum={$totalpages}&&player={$_GET['player']}'>>></a> ";
} // end if
/****** end build pagination links ******/
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "vaultlog") {
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Search Players Vault Log</td></tr>
<tr><td class="contentcontent">
    <table width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <form method="get">
    <tr>
	<td width="15%"><b>Player ID:</b></td>
	<td width="85%">
    <input type="text" name="player" value="<?php echo $_GET['player']; ?>" />
    <input type="hidden" name="page" value="vaultlog" />
    </td>
    </tr>
    <tr>
	<td width="15%">&nbsp;</td>
	<td width="85%"><input type="submit" value="Search Vault Logs" /></td>
    </tr>
    </form>
    </table>
</td></tr>

<?php
include 'footer.php';
die();
}

if(isset($_POST['searchtra'])) {

$event = new User($_POST['id']);
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><?php echo $event->formattedname; ?>'s Sent Transfers</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Transfer</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `transferlog` WHERE `from` = ? ORDER BY `timestamp` DESC");
	$result->execute(array($_POST['id']));
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$to = new User($line['to']);
	$from = new User($line['from']);
	if($line['item'] > 0) { //Item recieved
	$ritem = $GLOBALS['pdo']->prepare("SELECT * FROM `items` WHERE `id` = '".$line['item']."'");
	$ritem->execute();
	$item = $ritem->fetch(PDO::FETCH_ASSOC);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have sent a ".item_popup($item['itemname'], $item['id'])." to ".$to->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['money'] > 0) { //Money recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have sent $".prettynum($line['money'])." to ".$to->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['points'] > 0) { //Points recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have sent ".prettynum($line['points'])." points to ".$to->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['credits'] > 0) { //Credits recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have sent ".prettynum($line['credits'])." credits to ".$to->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no sent transfer logs.";
	}
			echo "<br />";
    ?>
    </td></tr>
    
    
    	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><?php echo $event->formattedname; ?>'s Recieved Transfers</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
	<td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Transfer</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `transferlog` WHERE `to` = ? ORDER BY `timestamp` DESC");
	$result->execute(array($_POST['id']));
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$to = new User($line['to']);
	$from = new User($line['from']);
	if($line['item'] > 0) { //Item recieved
	$ritem = $GLOBALS['pdo']->prepare("SELECT * FROM `items` WHERE `id` = '".$line['item']."'");
	$ritem->execute();
	$item = $ritem->fetch(PDO::FETCH_ASSOC);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have been sent a ".item_popup($item['itemname'], $item['id'])." from ".$from->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['money'] > 0) { //Money recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have been sent $".prettynum($line['money'])." from ".$from->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['points'] > 0) { //Points recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have been sent ".prettynum($line['points'])." points from ".$from->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['credits'] > 0) { //Points recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>You have been sent ".prettynum($line['credits'])." credits from ".$from->formattedname.".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no recieved transfer logs.";
	}
			echo "<br />";
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "tralog") {
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Search Player Transfers</td></tr>
<tr><td class="contentcontent">
    <table style="width:100%; overflow:hidden; word-wrap:break-word;">
    <form method="post">
    <tr>
	<td width="15%"><b>Player ID:</b></td>
	<td width="85%"><input type="text" name="id" value="<?php echo $_GET['player']; ?>" /></td>
    </tr>
    <tr>
	<td width="15%">&nbsp;</td>
	<td width="85%"><input type="submit" name="searchtra" value="Search Transfers" /></td>
    </tr>
    </form>
    </table>
</td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "reportmail") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Reported Mail</td></tr>
    <tr><td class="contentcontent">
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Mail</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Reported By</b></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Un-Report</b></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `maillog` WHERE `reported` = '1' ORDER BY `timesent` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$reported_by = new User($line['to']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='40%'><a href='gmpm.php?id=".$line['id']."' target='_blank'>".$line['subject']."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='40%'>".$reported_by->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='20%'><form method='post'><input type='hidden' name='postid' value='".$line['id']."' /><input type='submit' name='unreportmail' value='Un-Report' /></form></td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There is currently no reported mail.";
	}
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "transfercheck") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">IP Transfer Checks</td></tr>
    <tr><td class="contentcontent">
    <i>Below are transfers that occur under the same IP, which suggests that the user may be multi-accounting. Freeze the user for 3 days and then contact an Admin.</i><br /><br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Transfer</B></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</B></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `transferlog` WHERE `toip` = `fromip` ORDER BY `timestamp` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
    if($res) {
		foreach($res AS $line){
	$to = new User($line['to']);
	$from = new User($line['from']);
	if($line['item'] > 0) { //Item recieved
	$ritem = $GLOBALS['pdo']->prepare("SELECT * FROM `items` WHERE `id` = '".$line['item']."'");
	$ritem->execute();
	$item = $ritem->fetch(PDO::FETCH_ASSOC);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$to->formattedname." [<font color='red'>".$line['toip']."</font>] has been sent a ".item_popup($item['itemname'], $item['id'])." from ".$from->formattedname." [<font color='red'>".$line['fromip']."</font>].</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['money'] > 0) { //Money recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$to->formattedname." [<font color='red'>".$line['toip']."</font>] has been sent  $".prettynum($line['money'])." from ".$from->formattedname." [<font color='red'>".$line['fromip']."</font>].</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['points'] > 0) { //Points recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$to->formattedname." [<font color='red'>".$line['toip']."</font>] has been sent ".prettynum($line['points'])." points from ".$from->formattedname." [<font color='red'>".$line['fromip']."</font>].</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	} else if($line['credits'] > 0) { //Points recieved
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$to->formattedname." [<font color='red'>".$line['toip']."</font>] has been sent ".prettynum($line['credits'])." credits from ".$from->formattedname." [<font color='red'>".$line['fromip']."</font>].</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no transfers on the same ip.";
	}
			echo "<br />";
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "5050check") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">50/50 Checks</td></tr>
    <tr><td class="contentcontent">
    <i>Below are 50/50 game logs that are played between two accounts on the same IP address. If any of these are found, the user is to be froze for 3 days and an admin is to be notified ASAP.</i><br /><br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>50/50 Log</B></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Time</B></td>
    </tr>
    <?php
	//Cash 50/50
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `cash5050log` WHERE `betterip` = `matcherip` ORDER BY `timestamp` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
	$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `pts5050log` WHERE `betterip` = `matcherip` ORDER BY `timestamp` DESC");
	$result2->execute();
	$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
    if($res || $res2) {
		foreach($res AS $line){
	$matcher = new User($line['matcher']);
	$better = new User($line['better']);
	$winner = new User($line['winner']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$matcher->formattedname." [<font color='red'>".$line['matcherip']."</font>] has matched a cash 50/50 created by ".$better->formattedname." [<font color='red'>".$line['betterip']."</font>]. The winner was ".$winner->formattedname.". They won $".prettynum($line['amount']).".</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line['timestamp'])."</td></tr>";
	}
	foreach($res2 AS $line2){
	$matcher2 = new User($line2['matcher']);
	$better2 = new User($line2['better']);
	$winner2 = new User($line2['winner']);
	echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='70%'>".$matcher2->formattedname." [<font color='red'>".$line2['matcherip']."</font>] has matched a pts 50/50 created by ".$better2->formattedname." [<font color='red'>".$line2['betterip']."</font>]. The winner was ".$winner2->formattedname.". They won ".prettynum($line2['amount'])." points.</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='30%'>".date(d." ".F." ".Y.", ".g.":".ia,$line2['timestamp'])."</td></tr>";
	}
	echo "</table>";
	} else {
	echo "</table>";
	echo "<br />There are no 50/50's on the same ip.";
	}
	echo "<br />";
    ?>
    </td></tr>

<?php
include 'footer.php';
die();
}

if($_GET['page'] == "accountcheck") {
?>
	<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">IP Account Checks</td></tr>
    <tr><td class="contentcontent">
    <i>Below are accounts that are being played on under the same IP. Check to see if the account is allowed on the IP, in the player notes. If not, freeze the user for 3 days and contact NATAS.</i><br /><br />
    <table style="border-left: 1px solid #444444; border-top: 1px solid #444444;" cellspacing="0" cellpadding="2" width="100%" style="width:100%; overflow:hidden; word-wrap:break-word;" cellpadding="2">
    <tr>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>Player</B></td>
    <td style="border-right: 1px solid #444444; border-bottom: 1px solid #444444; background-color: #222222;"><b>IP</B></td>
    </tr>
    <?php
	$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` ORDER BY `ip`, `lastactive` DESC");
	$result->execute();
	$res = $result->fetchALL(PDO::FETCH_ASSOC);
	foreach ($res AS $line){
	$result2 = $GLOBALS['pdo']->prepare("SELECT count(*) FROM `grpgusers` WHERE `ip` = '".$line['ip']."' AND `ip` != '' ORDER BY `ip` DESC");
	$result2->execute();
	$worked2 = $result2->fetchALL(PDO::FETCH_ASSOC);
		if($worked2['id'] > 1) {
			$ip_user = new User($line['id']);
			echo "<tr><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='50%'>".$ip_user->formattedname."</td><td style='border-right: 1px solid #444444; border-bottom: 1px solid #444444;' width='50%'>".$ip_user->ip."</td>";
		}
	}
    ?>
    </table>
    </td></tr>

<?php
include 'footer.php';
die();
}
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">GM Guide</td></tr>
    <tr><td class="contentcontent">
    
    <center>
    <i>This guide was written by NATAS. If you have any questions or wish to add something to it, don't hesitate to mail me.</i>
    </center>
    <br /><br /><hr width="95%" /><br />
    <b><font color="#EE0000">Offense System</font></b>
    <br /><br />
    <i>The following part of the guide states which punishment should be placed for each offense made in the game. If you are unsure of the one that should be placed please message an Admin with a detailed explanation.</i><br /><br />
    <i>All bannings and warnings should be noted in the Player Notes section of their profile. This should include the punishment, the date and your username.</i>
    <br /><br />
    <b><u>Hidden Links</u></b>
    <br />
    Hidden links are known as url's that are hidden with a misleading title. For example if i was to show this: <a href="http://mafia-silent-mafia.com/profiles.php?id=1&rate=up" target="_blank">Free Stuff!</a> then thats classed as a hidden link. This is because i said you can get free stuff, however the link rates me up. For this offense the following actions should apply. For a first offense, a formal warning should be placed. For a second offense, a 3 day ban of the communication used to perform the offense should be placed. For a third offense, a 14 day ban of the communication used to perform the offense should be placed. And for a fourth offense, a permanent ban of the communication used to perform the offense should be placed (just set the days to 999,999).
    <br /><br />
    <b><u>Advertising</u></b>
    <br />
    Advertising is known as a player posting a link to their website or game. This could be done in the form of a link, an image or even just plain text. For this offense the following actions should be made. For a first offense, a formal warning should be placed. For a second offense, a 14 day ban of the communication used to perform the offense should be placed. For a third offense, a permanent ban of the communication used to perform the offense should be placed (just set the days to 999,999).
    <br /><br />
    <b><u>Spamming</u></b>
    <br />
    Spamming is known as a player purposely repeating the same post or thread. For example if i was to create five consecutive threads about the same thing it would be classed as spamming. For this offense the following actions should be placed. For a first offense, a 1 day ban of the communication used to perform the offense should be placed, for a second offense, a 14 day ban of the communication used to perform the offense should be placed. And for a third offense, a permanent ban of the communication used to perform the offense should be placed (just set the days to 999,999).
    <br /><br />
    <b><u>Verbal Abuse</u></b>
    <br />
    Verbal abuse is known as a player saying rude or inappripriate statements to another. This is usually in the form of extreme language. Depending on the seriousness of the abuse being caused the following actions should take place. For a small abusive statement, a formal warning should be placed and an appropriate apology to be written to the target. For an extreme abusive statement, the player should be banned of the communication used to perform the offense for 3 days. For a second offense, the player should be banned of the communication used to perform the offense for 14 days. And for a third offense a permanent ban of the communication used to perform the offense should be placed (just set the days to 999,999). 
    <br /><br />
    <b><u>Multi-accounting</u></b>
    <br />
    Multi-accounting is known as a player having more than one account. They would usually do this to get extra stuff on one account and then trade it over to the other. However they would usually be on the same IP so it will be logged in the IP checks. If someone is caught of munti-account then a permanent ban of the game should be placed. If just a suspicion of multi-accounting is seen, then you should freeze the user for 3 days and message an Admin ASAP.
    <br /><br />
    <b><u>Under Age Players</u></b>
    <br />
    The Terms of Service state that you must be over the age of 14 to play Mafia Warfare. If a player is caught of being under the age limit then a permanent ban of the game should be placed. If a suspicion is seen then that user should be frozen for 3 days and message an Admin ASAP.

</td></tr>

<?php
include 'footer.php';
?>