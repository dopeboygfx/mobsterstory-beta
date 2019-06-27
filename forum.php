<?
include 'header.php';
	addBrowser($user_class->id, $user_class->username); 


$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `id` = '".$user_class->id."'");
$result->execute();
$worked = $result->fetch(PDO::FETCH_ASSOC);
if($worked['threadtime'] == 0) {
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."' WHERE `id` = '".$user_class->id."'");
$result->execute();
}

///Check Mail Banned
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type`='forum' AND `id` = '".$user_class->id."'");  
$result->execute();
$worked = $result->fetch(PDO::FETCH_ASSOC);
if ($worked > 0) {
echo Message('&nbsp;You have been forum banned for '.prettynum($worked['days']).' days.');
include 'footer.php';
die();
}
//End Check

//News Stuff
$resultnews = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '1' ORDER BY `lastreply` DESC");
$resultnews->execute();
$workednews = $resultnews->fetch(PDO::FETCH_ASSOC);
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '1' ORDER BY `timesent` DESC");
$resultnews2->execute();
$newsreplies = $resultnews2->rowCount();
$newstopics = $resultnews->rowCount();
$workednews2 = $resultnews2->fetch(PDO::FETCH_ASSOC);
if ($workednews) {
$newslastpost = date(d." ".F." ".Y.", ".g.":".ia,$workednews['lastreply']);
} else {
$newslastpost = "Never";
}

//GC Stuff
$resultgc = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '2' ORDER BY `lastreply` DESC");
$resultgc->execute();
$workedgc = $resultgc->fetch(PDO::FETCH_ASSOC);
$resultgc22 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '2' ORDER BY `timesent` DESC");
$resultgc22->fetch(PDO::FETCH_ASSOC);
$gctopics = $resultgc->rowCount();
$gcreplies = $resultgc22->rowCount();
$workedgc22 = $resultgc22->fetch(PDO::FETCH_ASSOC);
if ($workedgc) {
$gclastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedgc['lastreply']);
} else {
$gclastpost = "Never";
}

//Gang Chat Stuff
$resultgc2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '3' ORDER BY `lastreply` DESC");
$resultgc2->execute();
$workedgc2 = $resultgc2->fetch(PDO::FETCH_ASSOC);
$resultgc3 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '3' ORDER BY `timesent` DESC");
$resultgc3->fetch(PDO::FETCH_ASSOC);
$workedgc3 = $resultgc3->fetch(PDO::FETCH_ASSOC);
$gc2replies = $resultgc3->rowCount();
$gc2topics = $resultgc2->rowCount();
if ($workedgc2) {
$gc2lastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedgc2['lastreply']);
} else {
$gc2lastpost = "Never";
}

//Marketplace Stuff
$resultmp = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '4' ORDER BY `lastreply` DESC");
$resultmp->execute();
$workedmp = $resultmp->fetch(PDO::FETCH_ASSOC);
$resultmp2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '4' ORDER BY `timesent` DESC");
$resultmp2->execute();
$mptopics = $resultmp->rowCount();
$mpreplies = $resultmp2->rowCount();
$workedmp2 = $resultmp2->fetch(PDO::FETCH_ASSOC);
if ($workedmp2) {
$mplastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedmp['lastreply']);
} else {
$mplastpost = "Never";
}

//Competitions Stuff
$resultc = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '5' ORDER BY `lastreply` DESC");
$resultc->execute();
$workedc = $resultc->fetch(PDO::FETCH_ASSOC);
$resultc2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '5' ORDER BY `timesent` DESC");
$resultc2->execute();
$creplies = $resultc2->rowCount();
$ctopics = $resultc->rowCount();
$workedc2 = $resultc2->fetch(PDO::FETCH_ASSOC);
if ($workedc) {
$clastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedc['lastreply']);
} else {
$clastpost = "Never";
}

//Off Topic Stuff
$resultot = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '6' ORDER BY `lastreply` DESC");
$resultot->execute();
$workedot = $resultot->fetch(PDO::FETCH_ASSOC);
$resultot2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '6' ORDER BY `timesent` DESC");
$resultot2->execute();
$ttopics = $resultot->rowCount();
$treplies = $resultot2->rowCount();
$workedot2 = $resultot2->fetch(PDO::FETCH_ASSOC);
if ($workedot) {
$otlastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedot['lastreply']);
} else {
$otlastpost = "Never";
}

//Suggestions Stuff
$results = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '7' ORDER BY `lastreply` DESC");
$results->execute();
$workeds = $results->fetch(PDO::FETCH_ASSOC);
$results2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '7' ORDER BY `timesent` DESC");
$results2->execute();
$sreplies = $results2->rowCount();
$stopics = $results->rowCount();
$workeds2 = $results2->fetch(PDO::FETCH_ASSOC);
if ($workeds) {
$slastpost = date(d." ".F." ".Y.", ".g.":".ia,$workeds['lastreply']);
} else {
$slastpost = "Never";
}

//Help Forum Stuff
$resulth = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '8' ORDER BY `lastreply` DESC");
$resulth->execute();
$workedh = $resulth->fetch(PDO::FETCH_ASSOC);
$resulth2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '8' ORDER BY `timesent` DESC");
$resulth2->execute();
$hreplies = $resulth2->rowCount();
$htopics = $resulth->rowCount();
$workedh2 = $resulth2->fetch(PDO::FETCH_ASSOC);
if ($workedh) {
$hlastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedh['lastreply']);
} else {
$hlastpost = "Never";
}

//Bugs & Errors Stuff
$resultb = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '9' ORDER BY `lastreply` DESC");
$resultb->execute();
$workedb = $resultb->fetch(PDO::FETCH_ASSOC);
$resultb2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '9' ORDER BY `timesent` DESC");
$resultb2->execute();
$btopics = $resultb->rowCount();
$breplies = $resultb2->rowCount();
$workedb2 = $resultb2->fetch(PDO::FETCH_ASSOC);
if ($workedb) {
$blastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedb['lastreply']);
} else {
$blastpost = "Never";
}

//Trades Stuff
$resultt = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '10' ORDER BY `lastreply` DESC");
$resultt->execute();
$workedt = $resultt->fetch(PDO::FETCH_ASSOC);
$resultt2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '10' ORDER BY `timesent` DESC");
$resultt2->execute();
$workedt2 = $resultt2->fetch(PDO::FETCH_ASSOC);
$ttopics = $resultt->rowCount();
$treplies = $resultt2->rowCount();
if ($workedt) {
$tlastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedt['lastreply']);
} else {
$tlastpost = "Never";
}

//Staff Forum
$resultst = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '11' ORDER BY `lastreply` DESC");
$resultst->execute();
$workedst = $resultst->fetch(PDO::FETCH_ASSOC);
$resultst2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '11' ORDER BY `timesent` DESC");
$resultst2->execute();
$workedst2 = $resultst2->fetch(PDO::FETCH_ASSOC);
$sttopics = $resultst->rowCount();
$streplies = $resultst2->rowCount();
if ($workedst) {
$stlastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedst['lastreply']);
} else {
$stlastpost = "Never";
}

//Missing Items
$resultmi = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '12' ORDER BY `lastreply` DESC");
$resultmi->execute();
$workedmi = $resultmi->fetch(PDO::FETCH_ASSOC);
$resultmi2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '12' ORDER BY `timesent` DESC");
$resultmi2->execute();
$workedmi2 = $resultmi2->fetch(PDO::FETCH_ASSOC);

if ($workedmi) {
$milastpost = date(d." ".F." ".Y.", ".g.":".ia,$workedmi['lastreply']);
} else {
$milastpost = "Never";
}

//Pages Stuff

// find out how many rows are in the table 
$result = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `ftopics` WHERE `sectionid` = '".$_GET['id']."'");
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

if($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1 || $user_class->eo == 1) {
//Staff Forum Topics
if ($_GET['id'] == 11) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if ($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1 || $user_class->eo == 1) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('11', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<thead>
<tr> 
<th colspan="4"><a href="forum.php">Forum</a> > Staff Forum</th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '11' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '11' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '11' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '11' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
<td colspan="4">
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
?>
</td>
</table>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
<?php
include("footer.php");
die();
}
}


//Missing Items Topics
if ($_GET['id'] == 12) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('12', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
</table>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > Missing Items</td></th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '12' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '12' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "
<tr>
<td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td>
<td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td>
<td align='center'>".prettynum($line['views'])."</td>
</tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '12' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '12' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "
<tr>
<td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td>
<td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td>
<td align='center'>".prettynum($line['views'])."</td>
</tr>";
}
?>
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
?>
</table>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th>New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table class="inverted ui unstackable column small compact table">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
<?php
include("footer.php");
die();
}


//News Topics
if ($_GET['id'] == 1) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if ($user_class->admin == 1  || $user_class->eo == 1 || $user_class->gm == 1) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('1', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `news` = '1'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > News</th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '1' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '1' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '1' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '1' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
<tr><td>
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
?>

</td></tr>
</table>
<?php if ($user_class->admin == 1  || $user_class->eo == 1 || $user_class->gm == 1) { ?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo ($user_class->id == 1) ? "[center][b]\n[/b][/center]" : $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
}
include("footer.php");
die();
}
//GC Topics
if ($_GET['id'] == 2) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('2', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > General Chat</th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '2' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '2' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '2' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){ 
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '2' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
<tr><td>
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//GC2 Topics
if ($_GET['id'] == 3) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('3', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > Gang Chat</th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '3' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
	$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '3' AND `topicid` = ".$line['forumid']."");
	$resultnews2->execute();
	$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "
<tr>
<td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td>
<td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td>
<td align='center'>".prettynum($line['views'])."</td>
</tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '3' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->execute();
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '3' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "
<tr>
<td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td>
<td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td>
<td align='center'>".prettynum($line['views'])."</td>
</tr>";
}
?>
<tr><td>
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Marketplace Topics
if ($_GET['id'] == 4) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('4', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > Marketplace</th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '4' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '4' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '4' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '4' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
<tr>
   <td>
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
<tr><td>
   <?php
include("footer.php");
die();
}

//Competitions Topics
if ($_GET['id'] == 5) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('5', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
</td></tr>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4"><a href="forum.php">Forum</a> > Competitions</td></th>
</tr>
</thead>
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '5' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '5' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '5' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '5' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
<tr><td>
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
</table>
<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">New Topic</th>
</tr>
</thead>
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Off Topic Topics
if ($_GET['id'] == 6) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('6', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > Off Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
<tr>
<td><b>Topic</b></td><td><b>Starter</b></td><td><b>Replies</b></td><td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '6' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '6' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '6' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '6' AND `topicid` = ".$line['forumid']."");
$resultnewes2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
</table>
<br /><br />
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">New Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Suggestions Topics
if ($_GET['id'] == 7) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('7', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > Suggestions</td></tr>
<tr><td class="contentcontent">
<table width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
<tr>
<td><b>Topic</b></td>
<td><b>Starter</b></td>
<td><b>Replies</b></td>
<td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '7' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '7' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '7' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '7' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
</table>
<br /><br />
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">New Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Help Forum Topics
if ($_GET['id'] == 8) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('8', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > Help Forum</td></tr>
<tr><td class="contentcontent">
<table width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
<tr>
<td><b>Topic</b></td><td><b>Starter</b></td><td><b>Replies</b></td><td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '8' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '8' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '8' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '8' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
</table>
<br /><br />
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">New Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Bugs & Errors Topics
if ($_GET['id'] == 9) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('9', '".$user_class->id."', '".time()."', ?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > Bugs, Errors etc...</td></tr>
<tr><td class="contentcontent">
<table width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
<tr>
<td><b>Topic</b></td><td><b>Starter</b></td><td><b>Replies</b></td><td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '9' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach($res AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '9' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '9' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '9' AND `topicid` = ".$line['forumid']."");
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
</table>
<br /><br />
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">New Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

//Trades Topics
if ($_GET['id'] == 10) { 

if (isset($_POST['newtopic'])) {
if ($_POST['topic'] != "") {
if ($_POST['body'] != "") {
if (strlen($_POST['topic']) < 41) {
$lala = time();
if ($lala >= $user_class->thread + 30) {
$timesent = time();
$_POST['topic'] = str_replace('"', "", $_POST['topic']);
$subject = strip_tags($_POST['topic']);
$subject = nl2br($subject);
$subject = addslashes($subject);
$_POST['body'] = str_replace('"', '', $_POST['body']);
$body = strip_tags($_POST['body']);
$body = nl2br($body);
$body = addslashes($body);
$result= $GLOBALS['pdo']->prepare("INSERT INTO `ftopics` (sectionid, playerid, lastreply, timesent, subject, body)"."VALUES ('10', '".$user_class->id."', '".time()."',?,?,?)");
$result->execute(array($timesent,$subject,$body));
$result= $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `threadtime` = '".time()."', `posts` = posts+1 WHERE `id` = '".$user_class->id."'");
$result->execute();
$_POST['topic'] = "";
$_POST['body'] = "";
echo Message("Your new topic has been submitted.");
} else {
$time = abs(time() - ($user_class->thread + 30));
echo Message("To stop spamming you have to wait ".$time." seconds to post another thread.");
}
} else {
echo Message("Your subject can only be 40 characters in length!");
}
} else {
echo Message("You didn't enter a topic body!");
}
} else {
echo Message("You didn't enter a topic subject!");
}
}
?>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead"><a href="forum.php">Forum</a> > Trades</td></tr>
<tr><td class="contentcontent">
<table width="100%" style="table-layout:fixed; width:100%; overflow:hidden; word-wrap:break-word;">
<tr>
<td><b>Topic</b></td><td><b>Starter</b></td><td><b>Replies</b></td><td><b>Views</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '10' AND `sticky` = '1' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
foreach ($rew AS $line){
$resultnews2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `sectionid` = '10' AND `topicid` = ".$line['forumid']."");
$resultnews2->execute();
$replies = $resultnews2->rowCount();
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a><img src='images/pin.png' width='5%' height='5%' /></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}

$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `sectionid` = '10' AND `sticky` = '0' ORDER BY `lastreply` DESC LIMIT $offset, $rowsperpage");
$result2->execute();
$res2 = $result2->fetchALL(PDO::FETCH_ASSOC);
foreach ($res2 AS $line){
	$resultnews2 = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM `freplies` WHERE `sectionid` = '10' AND `topicid` = ".$line['forumid']."");
	$resultnews2->execute();
	$replies = count($resultnews2->fetch(PDO::FETCH_ASSOC));
$forum_class = new User($line['playerid']);
echo "<tr><td><a href='viewpost.php?id=".$line['forumid']."'>".$line['subject']."</a></td><td align='center'>".$forum_class->formattedname."</td><td align='center'>".prettynum($replies)."</td><td align='center'>".prettynum($line['views'])."</td></tr>";
}
?>
</table>
<br /><br />
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
if ($currentpage != $totalpages) {
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?id={$_GET['id']}&page=$totalpages'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
</td></tr>
<tr><td class="contentspacer"></td></tr><tr><td class="contenthead">New Topic</td></tr>
<tr><td class="contentcontent">
<table width="100%">
<form method="post">
<tr>
<td><b>Topic:</b></td>
<td><input type="text" name="topic" size="87" value="<?php echo $_POST['topic']; ?>" /></td>
</tr>
<tr>
<td><b>Message:</b><br />[<a href="bbcode.php">BBCode</a>]</td>
<td><textarea name="body" cols="66" rows="5"><?php echo $_POST['body']; ?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input type="submit" name="newtopic" value="Add New Topic" /></td>
</tr>
</form>
</table>
<?php
include("footer.php");
die();
}

?>
<thead>
<tr>
<th colspan="4">Forum</th>
</tr>
</thead>
<tr>
<td><b>Forum</b></td>
<td><b>Topics</b></td>
<td><b>Replies</b></td>
<td><b>Last Post</b></td>
</tr>
<tr>
<td><a href="forum.php?id=1">News</a><br />Stay up to date and comment on game news.</td>
<td><?php echo $newstopics; ?></td>
<td><?php echo $newsreplies; ?></td>
<td><?php echo $newslastpost; ?></td>
</tr>

<?php if($user_class->admin == 1  || $user_class->gm == 1 || $user_class->fm == 1 || $user_class->eo == 1) { ?>
<tr>
<td><a href="forum.php?id=11">Staff Forum</a><br />Anything you need to talk about in private to another staff member.</td>
<td><?php echo $sttopics; ?></td>
<td><?php echo $streplies; ?></td>
<td><?php echo $stlastpost; ?></td>
</tr>
<?php } ?>

<tr>
<td><a href="forum.php?id=2">General Chat</a><br />Talk about anything general to Mobster Story here.</td>
<td><?php echo $gctopics; ?></td>
<td><?php echo $gcreplies; ?></td>
<td><?php echo $gclastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=3">Gang Chat</a><br />Talk about and advertise your gang here.</td>
<td><?php echo $gc2topics; ?></td>
<td><?php echo $gc2replies; ?></td>
<td><?php echo $gc2lastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=4">Marketplace</a><br />Post buying and selling needs here.</td>
<td><?php echo $mptopics; ?></td>
<td><?php echo $mpreplies; ?></td>
<td><?php echo $mplastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=5">Competitions</a><br />Hold any competitions you want here.</td>
<td><?php echo $ctopics; ?></td>
<td><?php echo $creplies; ?></td>
<td><?php echo $clastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=6">Off Topic</a><br />Talk about anything not related to Mobster Story here.</td>
<td><?php echo $ottopics; ?></td>
<td><?php echo $otreplies; ?></td>
<td><?php echo $otlastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=7">Suggestions</a><br />Post anything new or improved you would like to see at Mobster Story</td>
<td><?php echo $stopics; ?></td>
<td><?php echo $sreplies; ?></td>
<td><?php echo $slastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=8">Help Forum</a><br />Ask for help here from other members or staff.</td>
<td><?php echo $htopics; ?></td>
<td><?php echo $hreplies; ?></td>
<td><?php echo $hlastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=9">Bugs, Errors etc...</a><br />Report bugs and errors here.</td>
<td><?php echo $btopics; ?></td>
<td><?php echo $breplies; ?></td>
<td><?php echo $blastpost; ?></td>
</tr>

<tr>
<td><a href="forum.php?id=10">Trades</a><br />Post trades you would like completing here.</td>
<td><?php echo $ttopics; ?></td>
<td><?php echo $treplies; ?></td>
<td><?php echo $tlastpost; ?></td>
</tr>
</table>

<table class="inverted ui unstackable column small compact table">
<thead>
<tr>
<th colspan="4">Top 5 Forum Whores</th>
</tr>
</thead>
<tr>
<td><b>Rank</b></td>
<td><b>Mobster</b></td>
<td><b>Posts</b></td>
</tr>
<?php
$result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `admin` = '0' AND `ban/freeze` = '0' ORDER BY `posts` DESC LIMIT 5");
$result->execute();
$res = $result->fetchALL(PDO::FETCH_ASSOC);
$rank = 0;
foreach ($res AS $line){
$whore_class = new User($line['id']);
$rank++;
echo "<tr><td width='5%'>".$rank."</td><td width='45%'>".$whore_class->formattedname."</td><td width='10%'>".$whore_class->posts."</td></tr>";
}
?>
</table>



<?php
include("footer.php");
?>