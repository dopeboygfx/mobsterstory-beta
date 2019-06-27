
<?
include 'header.php';

$posttime2 = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = '".$user_class->id."'");
$posttime2->execute();
$posttime = $posttime2->fetch(PDO::FETCH_ASSOC);
if($posttime['posttime'] == 0) {
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `posttime` = '".time()."' WHERE `id` = '".$user_class->id."'");
$result->execute();
}

//Check Mail Banned
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type`='forum' AND `id` = '".$user_class->id."'");  
$result->execute();
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($worked['id'] > 0) {
echo Message('&nbsp;You have been forum banned for '.prettynum($worked['days']).' days.');
include 'footer.php';
die();
}
//End Check

//Edit Topic
if(isset($_POST['submit'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$_POST['subject'] = str_replace('"', '', $_POST['subject']);
$subject = strip_tags($_POST['subject']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);

$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `subject` = ?, `body` = ? WHERE `forumid` = ?");
$result->execute(array($subject,$body,$_GET['id']));
echo Message("You have successfully edited this topic.");
StaffLog($user_class->id, "[-_USERID_-] has edited a topic by [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}

//Reply
if(isset($_POST['reply'])) {
$bodycheck = str_replace(" ", "", $_POST['body']);
if($bodycheck != "") {
$result = $GLOBALS['pdo']->prepare("SELECT * from `ftopics` WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($worked['locked'] != 1) {
if(time() >= ($posttime['posttime'] + 30)) {
$sectionid = $worked['sectionid'];
$topicid = $_GET['id'];
$playerid = $user_class->id;
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$timesent = time();

$result = $GLOBALS['pdo']->prepare("INSERT INTO `freplies` (`sectionid`, `playerid`, `timesent`, `topicid`, `body`)"."VALUES (?, ?, ?,?,?)");
$result->execute(array($setionid,$playerid,$timesent,$topicid,$body));
print_r($result->errorInfo());
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `lastreply` = '".time()."' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `posttime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
echo Message("You have added a reply.");
} else {
$time = abs(time() - ($posttime['posttime'] + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post again.");
}
}
} else {
echo Message("You didn't enter anything.");
}
}

//Report Topic
if(isset($_POST['reporttopic'])) {

$type = "Reported Topic";
$playerid = $user_class->id;
$reported = $_POST['forumid'];

$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `reported` = '1', `reporter` = '".$user_class->id."' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `fm` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
while($line = $result->fetch(PDO::FETCH_ASSOC)) {
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a topic. <a href='fmpanel.php?page=reportthread'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
if ($sent = 0) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `gm` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a topic. <a href='fmpanel.php?page=reportthread'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
} else {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `admin` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a topic. <a href='fmpanel.php?page=reportthread'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
}
echo Message("You have reported this topic.");
}

//Report Post
if(isset($_POST['reportpost'])) {

$type = "Reported Post";
$playerid = $user_class->id;
$reported = $_POST['postid'];

$result = $GLOBALS['pdo']->prepare("UPDATE `freplies` SET `reported` = '1', `reporter` = '".$user_class->id."' WHERE `postid` = ?");
$result->execute(array($reported));
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `fm` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a post. <a href='fmpanel.php?page=reportpost'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
if ($sent = 0) {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `gm` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a post. <a href='fmpanel.php?page=reportpost'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
} else {
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `admin` = '1' ORDER BY `lastactive` DESC LIMIT 1");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$secondsago = time()-$line['lastactive'];
if ($secondsago<=600) {
$user_online = new User($line['id']);
Send_Event($line['id'], "[-_USERID_-] has reported a post. <a href='fmpanel.php?page=reportpost'>Goto FM Panel</a>.", $user_class->id);
$sent = 1;
}
}
}
echo Message("You have reported this post.");
}

//Delete Post
if($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1) {
//Delete Post
if(isset($_POST['delete'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `postid` = ?");
$result1->execute(array($_POST['postid']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("DELETE FROM `freplies` WHERE `postid` = ?");
$result->execute(array($_POST['postid']));
echo Message("The selected post was deleted.");
StaffLog($user_class->id, "[-_USERID_-] has deleted a post. Post by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Topic</a>.", $worked['playerid']);
}

//Delete Topic
if(isset($_POST['deletetopic'])) {
$rsection = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$rsection->execute(array($_GET['id']));
$section = $rsection->fetch(PDO::FETCH_ASSOC);
echo Message("The topic you requested was deleted.<br /><br /><a href='forum.php?id=".$section['sectionid']."'>Go Back</a>");
$result = $GLOBALS['pdo']->prepare("DELETE FROM `ftopics` WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$result2 = $GLOBALS['pdo']->prepare("DELETE FROM `freplies` WHERE `topicid` = ?");
$result2->execute(array($_GET['id']));
StaffLog($user_class->id, "[-_USERID_-] has deleted a topic. Created by: [-_USERID2_-].", $section['playerid']);
include("footer.php");
die();
}

//Lock Topic
if(isset($_POST['lock'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `locked` = '1' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
echo Message("You have locked this topic.");
StaffLog($user_class->id, "[-_USERID_-] has locked a topic. Created by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}

//Sticky Topic
if(isset($_POST['sticky'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `sticky` = '1' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
echo Message("You have stickied this topic.");
StaffLog($user_class->id, "[-_USERID_-] has stickied a topic. Created by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}

//Unlock Topic
if(isset($_POST['unlock'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `locked` = '0' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
echo Message("You have unlocked this topic.");
StaffLog($user_class->id, "[-_USERID_-] has unlocked a topic. Created by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}

//Unsticky Topic
if(isset($_POST['unsticky'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `sticky` = '0' WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
echo Message("You have unstickied this topic.");
StaffLog($user_class->id, "[-_USERID_-] has stickied a topic. Created by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}
}

//Move Topic
if(isset($_POST['movetopic'])) {
$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$result1->execute(array($_GET['id']));
$worked = $result1->fetch(PDO::FETCH_ASSOC);
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `sectionid` = '".$_POST['section']."' WHERE `forumid` = '".$_GET['id']."'");
$result->execute(array($_GET['id']));
echo Message("You have moved this topic.");
StaffLog($user_class->id, "[-_USERID_-] has moved a topic. Created by: [-_USERID2_-]. <a href='viewpost.php?id=".$_GET['id']."' target='_blank'>View Here</a>.", $worked['playerid']);
}

$result = $GLOBALS['pdo']->prepare("SELECT * from `ftopics` WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$worked = $result->fetch(PDO::FETCH_ASSOC);

$ticket_class = new User($worked['playerid']);

if ($ticket_class->avatar != "" || $ticket_class->avatar != null) {
$avatar = $ticket_class->avatar;
} else {
$avatar = "/images/noavatar.png";
}

if ($_GET['id'] != $worked['forumid'])  {
echo Message("This topic could not be found in our database, sorry.");
include("footer.php");
die();
}

//Add Views
$result = $GLOBALS['pdo']->prepare("SELECT * from `ftopics` WHERE `forumid` = ?");
$result->execute(array($_GET['id']));
$worked = $result->fetch(PDO::FETCH_ASSOC);
$views = $worked['views'] + 1;
$result = $GLOBALS['pdo']->prepare("UPDATE `ftopics` SET `views` = ? WHERE `forumid` = ?");
$result->execute(array($views,$_GET['id']));

//End Views

if ($worked['sectionid'] == 1) {
$forum = "<a href='forum.php?id=1'>News</a>";
} else if ($worked['sectionid'] == 2) {
$forum = "<a href='forum.php?id=2'>General Chat</a>";
} else if ($worked['sectionid'] == 3) {
$forum = "<a href='forum.php?id=3'>Gang Chat</a>";
} else if ($worked['sectionid'] == 4) {
$forum = "<a href='forum.php?id=4'>Marketplace</a>";
} else if ($worked['sectionid'] == 5) {
$forum = "<a href='forum.php?id=5'>Competitions</a>";
} else if ($worked['sectionid'] == 6) {
$forum = "<a href='forum.php?id=6'>Off Topic</a>";
} else if ($worked['sectionid'] == 7) {
$forum = "<a href='forum.php?id=7'>Suggestions</a>";
} else if ($worked['sectionid'] == 8) {
$forum = "<a href='forum.php?id=8'>Help Forum</a>";
} else if ($worked['sectionid'] == 9) {
$forum = "<a href='forum.php?id=9'>Bugs, Errors etc...</a>";
} else if ($worked['sectionid'] == 10) {
$forum = "<a href='forum.php?id=10'>Trades</a>";
} else if ($worked['sectionid'] == 11) {
$forum = "<a href='forum.php?id=11'>Staff Forum</a>";
}  else if ($worked['sectionid'] == 12) {
$forum = "<a href='forum.php?id=12'>Missing Items</a>";
}
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > <?php echo $forum; echo " > ".$worked['subject']; ?></td></tr>
<tr><td class="contentcontent">
<table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #222222; table-layout:fixed; width:100%; word-wrap:break-word;">

<tr>
<td width="20%" bgcolor="#030303" align="center" valign="top"><?php echo date(d." ".F." ".Y.", ".g.":".ia,$worked['timesent']) ?><br /><br /><a href='profiles.php?id=<?php echo $ticket_class->id; ?>'><img src="<?php echo $avatar; ?>" height="100" width="100" style="border:1px solid #222222" /></a><br /><?php echo $ticket_class->formattedname; ?><br /><br />Posts:&nbsp;<?php echo prettynum($ticket_class->posts); if ($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1) { } else if ($worked['reported'] == 1) { ?><form method="post"><input type="submit" name="reporttopic" disabled="disabled" value="Reported" /></form><?php } else { ?><br /><form method="post"><input type="hidden" name="forumid" value="<?php echo $_GET['id']; ?>" /><input type="submit" name="reporttopic" value="Report Topic" /></form><?php } ?></td>
<td width="80%" bgcolor="#090909" valign="top"><?php echo BBCodeParse(strip_tags($worked['body'])); ?></td>
</tr>
</table>

</td></tr>

<?php if ($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1) { ?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Edit Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td width="12%"><b>Subject:</b></td>
<td width="80%"><input type="text" name="subject" size="50" value="<?php echo $worked['subject']; ?>" /></td>
</tr>
<tr>
<td width="12%"><b>Message:</b></td>
<td width="80%"><textarea name="body" cols="66" rows="5"><?php echo strip_tags($worked['body']); ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td align="center"><input type="submit" name="submit" value="Edit Topic" /></td>
<?php if ($worked['locked'] == 0) { ?>
<td align="center"><input type="submit" name="lock" value="Lock Topic" /></td><?php } else { ?>
<td align="center"><input type="submit" name="unlock" value="Unlock Topic" /></td>
<?php } ?>
<?php if ($worked['sticky'] == 0) { ?>
<td align="center"><input type="submit" name="sticky" value="Sticky Topic" /></td><?php } else { ?>
<td align="center"><input type="submit" name="unsticky" value="Unsticky Topic" /></td>
<?php } ?>
<td align="center"><input type="submit" name="deletetopic" value="Delete Topic" /></td>
</tr>
</table>
<table width="55%" align="center">
<tr><td height="10px"></td></tr>
<tr>
<td width="15%" align="center"><b>Move Topic:</b></td>
<td width="40%" align="center">
<?php
$results = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `forumid` = ?");
$results->execute(array($_GET['id']));
$workeds = $results->fetch(PDO::FETCH_ASSOC);

$sectionid = $workeds['sectionid'];
switch ($sectionid) {
	case 2:
		$selected2 = " selected='selected'";
		break;
	case 3:
		$selected3 = " selected='selected'";
		break;
	case 4:
		$selected4 = " selected='selected'";
		break;
	case 5:
		$selected5 = " selected='selected'";
		break;
	case 6:
		$selected6 = " selected='selected'";
		break;
	case 7:
		$selected7 = " selected='selected'";
		break;
	case 8:
		$selected8 = " selected='selected'";
		break;
	case 9:
		$selected9 = " selected='selected'";
		break;
	case 10:
		$selected10 = " selected='selected'";
		break;
	case 12:
		$selected12 = " selected='selected'";
		break;
}
?>
<form method="post">
<select name="section">
<option value="2"<?php echo $selected2; ?>>General Chat</option>
<option value="3"<?php echo $selected3; ?>>Gang Chat</option>
<option value="4"<?php echo $selected4; ?>>Marketplace</option>
<option value="5"<?php echo $selected5; ?>>Competitions</option>
<option value="6"<?php echo $selected6; ?>>Off Topic</option>
<option value="7"<?php echo $selected7; ?>>Suggestions</option>
<option value="8"<?php echo $selected8; ?>>Help Forum</option>
<option value="9"<?php echo $selected9; ?>>Bugs, Errors etc...</option>
<option value="10"<?php echo $selected10; ?>>Trades</option>
<option value="10"<?php echo $selected12; ?>>Missing Items</option>
</select>
&nbsp;&nbsp;
<input type="submit" name="movetopic" value="Move Topic" />
</form>
</td>
</tr>
</form>
</table>
</td></tr>
<?php } 

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `freplies` WHERE `topicid` = ?");
$result->execute(array($_GET['id']));
$r = $result->fetch(PDO::FETCH_ASSOC);
$numrows = $r[0];

// number of rows to show per page
$rowsperpage = 10;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
if ($totalpages <= 0) {
$totalpages = 1;
} else {
$totalpages = ceil($numrows / $rowsperpage);
}

// get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
   // cast var as int
   $currentpage = (int) $_GET['page'];
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

$resultrows = $GLOBALS['pdo']->prepare("SELECT * from `freplies` WHERE `topicid` = ?");
$resultrows->execute(array($_GET['id']));
$workedrows = $resultrows->rowCount();
if ($workedrows > 0) {
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Replies</td></tr>
<tr><td class="contentcontent">
<?php
/******  build the pagination links ******/
// range of num links to show
$range = 2;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=1'><<</a> ";
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
         echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$x'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
echo "<br /><br />";
$result123 = $GLOBALS['pdo']->prepare("SELECT * from `freplies` WHERE `topicid` = ? ORDER BY `timesent` ASC LIMIT $offset, $rowsperpage");
$result123->execute(array($_GET['id']));
$res = $result123->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $row){ 

$reply_class = new User($row['playerid']);

if ($reply_class->avatar != "" || $reply_class->avatar != null) {
$avatar = $reply_class->avatar;
} else {
$avatar = "/images/noavatar.png";
}

?>

<table width="100%" cellpadding="5" cellspacing="0" style="border:1px solid #222222; table-layout:fixed; width:100%; overflow:visible; word-wrap:break-word;">
<tr>
<td width="20%" bgcolor="#030303" align="center" valign="top"><?php echo date(d." ".F." ".Y.", ".g.":".ia,$row['timesent']) ?><br /><br /><a href='profiles.php?id=<?php echo $reply_class->id; ?>'><img src="<?php echo $avatar; ?>" height="100" width="100" style="border:1px solid #222222" /></a><br /><?php echo $reply_class->formattedname; ?><br /><br />Posts:&nbsp;<?php echo prettynum($reply_class->posts); ?><br /><?php if ($user_class->admin == 1  || $user_class->gm ==1 || $user_class->fm ==1) { ?><form method="post"><input type="hidden" name="postid" value="<?php echo $row['postid']; ?>" /><input type="submit" name="delete" value="Delete Post" /></form><?php } else if ($row['reported'] == 1) { ?><form method="post"><input type="submit" name="reportpost" value="Reported" disabled="disabled" /></form><?php } else { ?><form method="post"><input type="hidden" name="postid" value="<?php echo $row['postid']; ?>"<input type="submit" name="reportpost" value="Report Post" /></form><?php } ?></td>
<td width="80%" bgcolor="#090909" valign="top"><?php echo BBCodeParse(strip_tags($row['body'])); ?></td>
</tr>
</table>
<table><tr><td></td></tr></table>
<?php
}
echo "<br />";
/******  build the pagination links ******/
// range of num links to show
$range = 2;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=1'><<</a> ";
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
         echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$x'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage < $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<?php
}
$result22 = $GLOBALS['pdo']->prepare("SELECT * from `ftopics` WHERE `forumid` = ?");
$result22->execute(array($_GET['id']));
$worked22 = $result22->fetch(PDO::FETCH_ASSOC);
if ($worked22['locked'] != 1) { ?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Add Reply</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td width="12%"><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td width="80%"><textarea name="body" cols="66" rows="5"></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td align="center"><input type="submit" name="reply" value="Add Reply" /></td>
</tr>
</form>
</table>
<?php
} else {
?>

<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">Add Reply</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td align="center">This topic has been locked.</td>
</tr>
</form>
</table>

<?php
}
include("footer.php");
?>