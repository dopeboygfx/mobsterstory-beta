
<?
error_reporting(E_ALL);
/*
$color[0] = "#333";
$color[1] = "#ddd";
$color[2] = "#444";
$color[3] = "#ffffff";
$color[4] = "#111";
$color[5] = "#000000";
$color[6] = "#666666";
$color[7] = "#FFFF00";
$color[8] = "#1E1E1E";
$color[9] = "#ffcc00";
$color[10] = "#4d75a0";
$color[11] = "#7d9fc4";
$color[12] = "#FFFF33";
*/
session_start();

if (!isset($_SESSION['id'])){

	include('home.php');

	die();

}

include 'dbcon.php';

include 'classes.php';

include 'updates.php';

include('codeparser.php');

if ($_GET['action'] == "logout"){

	session_destroy();

	die('You have been logged out.<meta http-equiv="refresh" content="0;url=index.php">');

}



function microtime_float()

{

	$time = microtime();

	return (double)substr( $time, 11 ) + (double)substr( $time, 0, 8 );

}



microtime_float();

$starttime = microtime_float();



$user_class = new User($_SESSION['id']);
		  
if($user_class->admin != 1 && $user_class->gm != 1 && $user_class->fm != 1) {
message("You are not allowed here.");
include("footer.php");
die();
}
$resultban = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'freeze' AND id = ".$user_class->id); 
$resultban->execute();
$workedban = $resultban->fetch(PDO::FETCH_ASSOC);

$resultban2 = $GLOBALS['pdo']->prepare("SELECT * FROM `bans` WHERE `type` = 'perm' AND id = ".$user_class->id); 
$resultban2->execute();
$workedban2 = $resultban2->fetch(PDO::FETCH_ASSOC);
if ($workedban['id']) {
	session_destroy();
	die('<meta http-equiv="refresh" content="0;url=index.php">');
}

if ($workedban2['id']) {
	session_destroy();
	die('<meta http-equiv="refresh" content="0;url=index.php">');
}
/* Check heat -- put in jail if caught */
 $heatnum = $user_class->heat;
 switch ($heatnum)
 {
    case 1:
        $heatprob = .05;
        break;
    case 2:
        $heatprob = .1;
        break;
    case 3:
        $heatprob = .15;
        break;
    case 4:
        $heatprob = .2;
        break;
    case 5:
        $heatprob = .25;
        break;
    default:
        $heatprob = 0;
 }
 $heatrand = rand(1, 100);
 if(($heatrand * $heatprob) > 9)
 {
      $result = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `jail` = '1200', `heat` = '0' WHERE `id` = '".$user_class->id."'");
	  $result->execute();
      Send_Event($user_class->id, "The police have tracked you down due to your heat level and you were caught! You were sent off to prison for 20 minutes.");
      echo "<center>UH OH! The police finally got you. You were wanted with your heat level. You were sent off to prison for 20 minutes.</center>";
 }
 
$achievements = $GLOBALS['pdo']->prepare("SELECT * FROM `achievements` ORDER BY `id` ASC");
$row = $achievements->fetchAll(PDO::FETCH_ASSOC);
foreach ($row AS $r){

	$res = $GLOBALS['pdo']->prepare("SELECT * FROM `achievement` WHERE `a_id` = ? AND `u_id` = ?");
	$res->execute(array($r['id'],$user_class->id));
	$worked = $res->feth(PDO::FETCH_ASSOC);
	if ($worked['a_id']){
		if($user_class->$r['type'] >= $r['value']) {

		$points = $r['points'];
		Send_Event($user_class->id, 'You have completed an achievement and gained '.$points.' as your reward!');

		$qu = $GLOBALS['pdo']->prepare("UPDATE `grpgusers` SET `points` = `points` + ? WHERE `id` = ?");
		$qu->execute(array($points,$user_class->id));
		$qy = $GLOBALS['pdo']->prepare("INSERT INTO `achievement2` (id, a_id, u_id) VALUES ('', ?,?)");
        $qy->execute(array($r['id'], $user_class->id));
		}
		}

	}

// get style info
$cresult = $GLOBALS['pdo']->prepare('SELECT `value` FROM `styles` WHERE `style` = ?');
$cresult->execute(array($user_class->style));

$i = 0;
foreach($cresult as $line){
	$color[$i] = $line['value'];
	$i++;
}
//get style info

$result = $GLOBALS['pdo']->query('SELECT * FROM `serverconfig`');
$worked = $result->fetch(PDO::FETCH_ASSOC);

if($worked['serverdown'] != "" && $user_class->admin != 1){
	die("<h1><font color='red'>SERVER DOWN<br><br>".$worked['serverdown']."</font></h1>");
}

$time = date("F d, Y g:i:sa", time());

$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `lastactive` = ?, `ip` = ? WHERE `id` = ?');
$result->execute(array(time(), $_SERVER['REMOTE_ADDR'], $_SESSION['id']));

function callback($buffer){
	$user_class = new User($_SESSION['id']);

	$checkhosp = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` WHERE `hospital`!="0"');
	if(!empty($checkhosp))
	$nummsgs = count($checkhosp->fetchAll(PDO::FETCH_ASSOC));
	else
	$nummsgs = 0;

	$hospital = "[".$nummsgs."]";

	$checkjail = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` WHERE `jail` != "0"');
	if(!empty($checkjail))
	$nummsgs = count($checkjail->fetchAll(PDO::FETCH_ASSOC));
	else
	$nummsgs = 0;

	$jail = "[".$nummsgs."]";

	$checkmail = $GLOBALS['pdo']->prepare('SELECT * FROM `pms` WHERE `to` = ? AND `viewed`="1"');
	$checkmail->execute(array($user_class->id));
	$nummsgs = count($checkmail->fetchAll(PDO::FETCH_ASSOC));

	$mail = "[".$nummsgs."]";

	$checkevent = $GLOBALS['pdo']->prepare('SELECT * FROM `events` WHERE `to` = ? AND `viewed` = "1"');
	$checkevent->execute(array($user_class->id));
	$numevents = count($checkevent->fetchAll(PDO::FETCH_ASSOC));

	$events = "[".$numevents."]";
	//Scruffy level system!
	$user_class = new User($_SESSION['id']);
	if($user_class->exp >= $user_class->maxexp){
		$query = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `level` = ?+1, exp = 0 WHERE `id` = ?');
		$query->execute(array($user_class->level, $_SESSION['id']));
	}

	$result = $GLOBALS['pdo']->prepare('SELECT * from `effects` WHERE `userid` = ?');
	$result->execute(array($user_class->id));
	$result = $result->fetchAll(PDO::FETCH_ASSOC);

	if(!empty($result)){
		$effects = '<div class="headbox">Current Effects</div>';
		foreach($result as $line){
			$effects .= '<a class="leftmenu" href="effects.php?view='.$line['effect'].'">'.$line['effect']." (".floor($line['timeleft']).")".'</a></ul><br />';
		}
	}

	$out = $buffer;
	$out = str_replace("<!_-money-_!>", $user_class->money, $out);
	$out = str_replace("<!_-formhp-_!>", $user_class->formattedhp, $out);
	$out = str_replace("<!_-hpperc-_!>", $user_class->hppercent, $out);
	$out = str_replace("<!_-formenergy-_!>", $user_class->formattedenergy, $out);
	$out = str_replace("<!_-energyperc-_!>", $user_class->energypercent, $out);
	$out = str_replace("<!_-formawake-_!>", $user_class->formattedawake, $out);
	$out = str_replace("<!_-awakeperc-_!>", $user_class->awakepercent, $out);
	$out = str_replace("<!_-formnerve-_!>", $user_class->formattednerve, $out);
	$out = str_replace("<!_-nerveperc-_!>", $user_class->nervepercent, $out);
	$out = str_replace("<!_-points-_!>", $user_class->points, $out);
	$out = str_replace("<!_-level-_!>", $user_class->level, $out);
	$out = str_replace("<!_-hospital-_!>", $hospital, $out);
	$out = str_replace("<!_-jail-_!>", $jail, $out);
	$out = str_replace("<!_-mail-_!>", $mail, $out);
	$out = str_replace("<!_-events-_!>", $events, $out);
	// - timeout til error resolved // $out = str_replace("<!_-effects-_!>", $effects, $out);
	$out = str_replace("<!_-cityname-_!>", $user_class->cityname, $out);
	$out = str_replace("<!_-formexp-_!>", $user_class->formattedexp, $out);
	$out = str_replace("<!_-expperc-_!>", $user_class->exppercent, $out);


	return $out;
}

ob_start("callback");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>

	<title>Mobster Story | <!_-cityname-_!></title>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<link rel="stylesheet" type="text/css" href="../dist/semantic.css">
	<link rel="stylesheet" type="text/css" href="homepage.css">

	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
	<script src="../dist/semantic.js"></script>
	<script src="homepage.js"></script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script src="/phpfreechat-2.1.0/client/lib/jquery-1.8.2.min.js" type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" href="/phpfreechat-2.1.0/client/themes/default/jquery.phpfreechat.min.css" />
	<script src="/phpfreechat-2.1.0/client/jquery.phpfreechat.min.js" type="text/javascript"></script>


	<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-6219849657514051",
		enable_page_level_ads: true
	});
    </script>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136811775-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-136811775-1');
    </script>
    
<meta name="description" content="Free Online Mafia Text-Based Game Online. No downloads. Climb ranks, complete missions, smuggle good and run your
gang to top of the chart. Become Godfather!">
<meta name="keywords" content="
mafia, mafia game, мафия, mafia online, la mafia, vfabz, cosa nostra, borsellino, mafia the game, messina denaro, мафія, american mafia, camorra, mafia 1, mafia games, mafia rank, matteo messina denaro, miccichè, yakuza, салатерия, mafia party game, интертоп, арбер, palermo sicilia, mafia slang, pizza mafia, game mafia, john gambino, kansas city mafia, mafia card game, mafia ranks, mafia terms, 마피아, how to play mafia, tromba d'aria, no mafia, mafia names, mafia la, mafia 4, gang, mafia code, mafia structure, mafia pizza, the mob, mafia ranking, mafia iii, mafia hierarchy, mafia 3, don mafia, mafia in america, mafia ii, mafia game cards, mafia 2, costa nostra, capo mafia, al capone, mafia family, kansas city mob, italian mafia, fortnite, infinity war, whatsapp, tommy hilfiger, the nun, sushi, mac miller, levis, fallout 76, elon musk, avengers infinity war, amazon, fifa 19, hm, instagram, news, nike, youtube, ">



<style type="text/css">
.leftmenu {
	margin-left: 8px;
	background-color : <?= $color[0] ?>;
	border : 1px solid <?= $color[2] ?>;
	color : <?= $color[3] ?>;
	display : block;

	padding-top : 2px;

	padding-right : 2px;

	padding-bottom : 2px;

	padding-left : 2px;

	border-style : solid;

	border-top-width : 0;

	border-right-width : 1px;

	border-bottom-width : 1px;

	border-left-width : 1px;

	width : 145px;

	font-weight : normal;

	text-align : left;

}

.gap { line-height: 3px; }



.topbar { width: 98%;

	margin-left: 8px;

	margin-right: 8px;

	margin-top: 8px;

	background-color: <?= $color[0] ?>;

	border: 1px solid <?= $color[2] ?>;

	padding: 3px;

	color: <?= $color[1] ?>;

	font-weight: bold;

	font-size: 11px; }







	.content { width: 100%;

		padding: 0px; }



		.contenthead { background-color: <?= $color[4] ?>;

			border: 1px solid <?= $color[2] ?>;

			padding: 5px;

			color: <?= $color[1] ?>;

			font-weight: bold;

			font-size: 12px; }



			.contentcontent { background-color: <?= $color[0] ?>;

				border: 1px solid <?= $color[2] ?>;

				padding: 3px;

				color: <?= $color[1] ?>;

				font-size: 11px; }





				.bar_a{

					width: 100px;

					border: 1px solid <?= $color[5] ?>;

					background-color:<?= $color[6] ?>;

				}

				.bar_b{

					font-size: 10px;

					background-color:<?= $color[7] ?>;

				}





				body {

					margin: auto;

					border: 0px solid <?= $color[10] ?>;

					width: 870px;

					background-color: <?= $color[5] ?>;

					font-family : Arial, Helvetica, sans-serif;

					font-weight : normal;

					background-image: url(../images/bg.png);

				}





				h1 , h2 , h3 , h4 , h5 , h6 {

					font-family : "Trebuchet MS", Verdana, "Lucida Sans", Arial, Geneva, Helvetica, Helv, "Myriad Web", Syntax, sans-serif;

					font-weight : normal;

				}



				.head, .headbox , .dynabox , a.leftmenu , a.topmenu {

					margin-left: 8px;

					font-weight : bold;

					text-decoration : none;

					font-size : 80%;

					font-family : Verdana, "Lucida Sans", Arial, Geneva, Helvetica, Helv, "Myriad Web", Syntax, sans-serif;

				}



				a 																	{color : <?= $color[5] ?>; }

				.body a:hover, .dynabox .headbox a:hover							{color : <?= $color[3] ?>; }



				.pos0														{background-color : <?= $color[3] ?>; color : <?= $color[5] ?>; }

				.pos1 {background-color : <?= $color[8] ?>;}

				.mainbox , .dynabox , a.leftmenu:link , a.leftmenu:visited 	{background-color : <?= $color[0] ?>; border : 1px solid <?= $color[2] ?>; color : <?= $color[3] ?>; }

				.pos2 , .topnav , a.leftmenu:hover 									{background-color : <?= $color[0] ?>; color : <?= $color[9] ?>; border : 1px solid <?= $color[2] ?>; }





				.neg0 																{background-color : <?= $color[5] ?>; }

				.neg1 , a.topmenu:hover												{background-color : <?= $color[10] ?>; color : <?= $color[3] ?>; border : <?= $color[5] ?>; }

				.neg2 , .headbox , a.topmenu:link , a.topmenu:visited 				{background-color : <?= $color[11] ?>; color : <?= $color[3] ?>; border : <?= $color[5] ?>; }



				a.leftmenu:link {

					display : block;

					padding-top : 2px;

					padding-right : 2px;

					padding-bottom : 2px;

					padding-left : 2px;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 1px;

					border-bottom-width : 1px;

					border-left-width : 1px;

					width : 145px;

					font-weight : normal;

					text-align : left;

				}



				a.leftmenu:hover {

					display : block;

					padding-top : 2px;

					padding-right : 2px;

					padding-bottom : 2px;

					padding-left : 2px;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 1px;

					border-bottom-width : 1px;

					border-left-width : 1px;

					width : 145px;

					font-weight : normal;

					text-align : left;

				}



				a.leftmenu:visited {

					display : block;

					padding-top : 2px;

					padding-right : 2px;

					padding-bottom : 2px;

					padding-left : 2px;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 1px;

					border-bottom-width : 1px;

					border-left-width : 1px;

					width : 145px;

					font-weight : normal;

					text-align : left;

				}



				a.topmenu:link {

					display : inline;

					padding-top : 5px;

					padding-right : 0;

					padding-bottom : 5px;

					padding-left : 0;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 0;

					border-bottom-width : 0;

					border-left-width : 1px;

					text-align : center;

				}



				a.topmenu:hover {

					background-color : <?= $color[10] ?>;

					display : inline;

					padding-top : 5px;

					padding-right : 0;

					padding-bottom : 5px;

					padding-left : 0;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 0;

					border-bottom-width : 0;

					border-left-width : 1px;

					text-align : center;

				}



				a.topmenu:visited {

					display : inline;

					padding-top : 5px;

					padding-right : 0;

					padding-bottom : 5px;

					padding-left : 0;

					border-style : solid;

					border-top-width : 0;

					border-right-width : 0;

					border-bottom-width : 0;

					border-left-width : 1px;

					text-align : center;

				}



				.headbox {



					background-color: <?= $color[4] ?>;

					border: 1px solid <?= $color[2] ?>;

					padding: 5px;

					color: <?= $color[1] ?>;



					display : block;

					padding: 5;

					width : 139px;

					text-align : left;

				}



				.topbox {

					margin-left: 8px;

					margin-right: 8px;

					margin-top: 8px;

					color: <?= $color[3] ?>;

					border: 1px solid <?= $color[2] ?>;

					background-color: <?= $color[11] ?>;

					height : 95px;



					padding-right : 5px;

					padding-bottom : 0;

					padding-top: 5px

				}



				.topnav {

					border : solid ;

					border-width : 0 1px 1px;

					padding-top : 3px;

					padding-bottom : 0;

				}



				.mainbox {

					border: none;
					background-color: <?= $color[8] ?>;

					border-width : 1px 0 1px 1px;

					padding-top : 5px;

					padding-left : 5px;

					padding-right : 5px;

					padding-bottom : 5px;

				}



				.mainbox p a {

					font-weight : bold;

					font-size : 90%;

				}



				.dynabox {

					border: 1px solid <?= $color[0] ?>;

					text-align : center;

				}



				.dynabox .headbox {

					border-style : dashed;

					border-top-style : solid;

					border-right-width : 0;

					border-left-width : 0;

					padding-top : 3px;

					padding-left : 0;

					padding-right : 0;

					padding-bottom : 3px;

				}



				.dynacontent {

					padding-top : 3px;

					padding-left : 5px;

					padding-right : 5px;

					padding-bottom : 3px;

					text-align : left;

					font-size : 70%;

					font-weight : normal;

				}



				a{

					color: <?= $color[9] ?>;

				}

				a:hover{

					color: <?= $color[12] ?>;

				}


				.style1 {color: #BDBDBD}
				</style>
			</head>
			<body>


				<!-- new design top menu />--->
				<div class="inverted ui mini menu">
					<div class="header item">Welcome, <? echo $user_class->formattedname; ?></div>
					<a class="item">TOS <i class="help circle icon"></i></a>
					<a href='vote.php' class="item">Vote <i class="line chart icon"></i></a>
					<a class="item">Updates <i class="announcement icon"></i></a>
					<div class="right menu">
						<div class="ui inverted menu"><a class="item">Server Time: <?= $time; ?></a></div>

					</div>
				</div>
		<!-- new design top menu end />--->
				<!-- new design top box />--->
				<div class="ui grid container">
					<div class="eight wide column">
						<img src="https://mobsterstory.com/images/logo.png" alt="Mobster Story">
					</div>

					<div class="six wide column">
						<table class="inverted ui five unstackable column small compact table">
							<tbody>
								<tr>
	<br />
	<!---------
   <span style="color:gold; font-weight:bold;"><i>Heat:</i></span>
   <?php
   	$count = 0;
   	if($user_class->heat == 0) {
   		for($i = 0; $i < 5; $i++) {
   			echo "<img src='images/wanted_star_off.png' alt='empty star'>";
   		}
   	}
   	elseif($user_class->heat == 1) {
   		echo "<img src='images/wanted_star_on.png' alt='empty star'>";
   		while($count < 4) {
   			echo "<img src='images/wanted_star_off.png' alt='full star'>";
   			$count++;
   		}
   	}
   	elseif($user_class->heat == 2) {
   		echo "<img src='images/wanted_star_on.png' alt='empty star'>";
   		echo "<img src='images/wanted_star_on.png' alt='empty star'>";
   		while($count < 3) {
   			echo "<img src='images/wanted_star_off.png' alt='full star'>";
   			$count++;
   		}
   	}
   	elseif($user_class->heat == 3) {
   		while($count < 3) {
   			echo "<img src='images/wanted_star_on.png' alt='full star'>";
   			$count++;
   		}
   		echo "<img src='images/wanted_star_off.png' alt='empty star'>";
   		echo "<img src='images/wanted_star_off.png' alt='empty star'>";
   	}
   	elseif($user_class->heat == 4) {
   	   	while($count < 4) {
   			echo "<img src='images/wanted_star_on.png' alt='full star'>";
   			$count++;
   		}
   		echo "<img src='images/wanted_star_off.png' alt='empty star'>";
   	}
   	elseif($user_class->heat == 5) {
   	   	for($i = 0; $i < 5; $i++) {
   			echo "<img src='images/wanted_star_on.png' alt='full star'>";
   		}
   	}
	 ?>
	 -->
  </td>
									<td>[ID: <? echo $user_class->id; ?>] <? echo $user_class->formattedname; ?></td>
									<td><a href="pms.php">Mailbox <!_-mail-_!></a></td>
								</tr>
								<tr>
									<td>Level: <!_-level-_!></td>
									<td><a href="events.php">Events <!_-events-_!></a></td>
								</tr>
								<tr>
									<td>Money: $<?php echo number_format($user_class->money); ?> <a href = "sendmoney.php">[Send]</a> <br /></td>
									<td><a href="inventory.php">Inventory</a></td>
								</tr>
								<tr>
									<td>Points: <font color="sky blue"><!_-points-_!></font> <a href = "sendpoints.php">[Send]</a></td>
									<td><a href="rmstore.php">RM Store</a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<table class="ui unstackable inverted table">
					<tbody>
						<tr>
							<td>
								<div class="ui inverted progress tiny red">
									<div class="bar" style="width: <!_-hpperc-_!>%;" title="<!_-formhp-_!>">
										<div class="progress"></div>
									</div>
									<div class="label">Health: <!_-formhp-_!></div>
								</div>
							</div>		</td>
							<td>
								<div class="ui inverted progress tiny yellow">
									<div class="bar" style="width: <!_-energyperc-_!>%;" title="<!_-formenergy-_!>">
										<div class="progress"></div>
									</div>
									<div class="label"><a title = 'Refill this bar' href='spendpoints.php?spend=energy'>Energy</a> <!_-formenergy-_!></div>
								</div>
							</td>
							<td>
								<div class="ui inverted progress tiny yellow">
									<div class="bar" style="width: <!_-awakeperc-_!>%;" title="<!_-formawake-_!>">
										<div class=" progress"></div>
									</div>
									<div class="label">Awake: <!_-formawake-_!></div>
								</div>
							</td>
							<td>
								<div class="ui inverted progress tiny yellow">
									<div class="bar" style="width: <!_-nerveperc-_!>%;" title="<!_-formnerve-_!>">
										<div class="progress"></div>
									</div>
									<div class="label"><a title = 'Refill this bar' href='spendpoints.php?spend=nerve'>Nerve:</a> <!_-formnerve-_!></div>
								</div>
							</td>
							<td>
								<div class="ui inverted progress tiny blue">
									<div class="bar" style="width: <!_-expperc-_!>%;" title="<!_-formexp-_!>">
										<div class="progress"></div>
									</div>
									<div class="label">EXP: <!_-formexp-_!></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- new design top box end />--->
				<table class="ui unstackable inverted table">
					<tbody>
						<tr>
							<td>
								<?

								$result = $GLOBALS['pdo']->query('SELECT * from `serverconfig`');
								$worked = $result->fetch(PDO::FETCH_ASSOC);

								$messagetext = str_replace("^","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$worked['messagefromadmin']);
								echo "<marquee scrollamount='3'>".$messagetext."</marquee>";
								?>

							</td>
						</tr>
					</tbody>
				</table>

				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" width="100">
							<div>

								<?= ($user_class->admin == 1) ? '
								<div class="ui small inverted vertical menu">
								  <div class="item">
								    <div class="header">Control Panel</div>
								    <div class="menu">
								      <a class="item" href="control.php">Marquee/Maintenance</a>
								      <a class="item" href="control.php?page=rmoptions">RM Options</a>
											<a class="item" href="control.php?page=setplayerstatus">Player Options</a>
											<a class="item" href="massmail.php">Mass Mail</a>
											<a class="item" href="control.php?page=referrals">Manage Referrals</a>
											<a class="item" href="devlog.php">Development Log</a>
								    </div>
								  </div>
								  <div class="item">
								    <div class="header">Game Modification</div>
								    <div class="menu">
								      <a class="item" href="control.php?page=crimes">Manage Crimes</a>
								      <a class="item" href="control.php?page=cities">Manage Cities</a>
								      <a class="item" href="control.php?page=jobs">Manage Jobs</a>
											<a class="item" href="control.php?page=playeritems">Manage Items</a>
								    </div>
								  </div>

								  </div>
								</div>
								' : "" ?>


								<div class="ui small inverted vertical menu" width="80px;">
									<div class="item">
										<div class="header">Navigator</div>
										<div class="menu">
										<div>
			<?php
			$result1 = $GLOBALS['pdo']->prepare("SELECT * FROM `ftopics` WHERE `reported` = '1'");
			$result1->execute();
			$threads = $result1->rowCount();
			$result2 = $GLOBALS['pdo']->prepare("SELECT * FROM `freplies` WHERE `reported` = '1'");
			$result2->execute();
			$posts = $result2->rowCount();
			?>
            
             <div class="headbox">Back</div>
             <a class="leftmenu" href="index.php"><img src="images/point.png" width=16 height=16 border=0> Back to Game</a>
             <div class="space"></div>
             
          	 <div class="headbox">Guide</div>
             <a class="leftmenu" href="fmpanel.php"><img src="images/point.png" width=16 height=16 border=0> FM Guide</a>
         	 <div class="space"></div>
             
             <div class="headbox">Bans</div>
             <a class="leftmenu" href="fmpanel.php?page=bans"><img src="images/point.png" width=16 height=16 border=0> Forum Bans</a>
             <div class="space"></div>
             
             <div class="headbox">Forum Reports</div>
			 <a class="leftmenu" href="fmpanel.php?page=reportthread"><img src="images/point.png" width=16 height=16 border=0> Threads [<?php echo $threads; ?>]</a>
             <a class="leftmenu" href="fmpanel.php?page=reportpost"><img src="images/point.png" width=16 height=16 border=0> Posts [<?php echo $posts; ?>]</a>
             <div class="space"></div>

         
          </td>

											</div>




											<!_-effects-_!>
											<!--<div class="headbox">Advertisement</div>
											<?
											$randnum = rand(1,3);
											$advert = ($randnum == 1) ? "images/rmgunad.png" : $advert;
											$link = ($randnum == 1) ? "rmstore.php" : $link;
											$advert = ($randnum == 2) ? "images/rmad.PNG" : $advert;
											$link = ($randnum == 2) ? "rmstore.php" : $link;
											$advert = ($randnum == 3) ? "images/bobscarlot.png" : $advert;
											$link = ($randnum == 3) ? "carlot.php" : $link;
											?>
											<a class="leftmenu" href="<?= $link ?>"><img style="border:none;" src='<?= $advert ?>' /></a>
										</div> -->

									</td>

									<td valign="top">
										<table width="100%">
											<tr>
												<td width="12"></td>
												<td>

													<table class="red inverted ui five unstackable column small compact table">

														<?php


														//echo ($user_class->admin == 1) ? "Admin Toolbar<br>"."<a href='" . "http://bourbanlegends.com/grpg" . $_SERVER['PHP_SELF']."?hackthegibson=iamgayandwantfullhealth'>Full Health</a>"." | <a href='" . "http://bourbanlegends.com/grpg" . $_SERVER['PHP_SELF']."?hackthegibson=iamgayandwantfullnerve'>Full Nerve</a>" . " | <a href='" . "http://bourbanlegends.com/grpg" . $_SERVER['PHP_SELF']."?hackthegibson=iamgayandwantoutofthehospital'>Get Out Of Hospital</a>" . " | <a href='" . "http://bourbanlegends.com/grpg" . $_SERVER['PHP_SELF']."?hackthegibson=iamgayandwantfullenergy'>Full Energy</a>" : "";


														//echo ($user_class->admin > 0) ? "<tr><td class='contenthead'>DJ Toolbar</td></tr><tr><td class='contentcontent' align='center'><a href='staff.php?radio=on'>Turn Radio On</a> | <a href='staff.php?radio=off'>Turn Radio Off</a> | <a href='staff.php?random=person'>Pick A Random Player</a></td></tr>" : "";

														$result = $GLOBALS['pdo']->prepare('SELECT * FROM `ganginvites` WHERE `username` = ?');
														$result->execute(array($user_class->username));
														$result = $result->fetchAll(PDO::FETCH_ASSOC);

														//$invites_exist = mysql_num_rows($result);

														if(count($result) > 0){
															$invite_class = New Gang($line['gangid']);
															echo "
															<tr>
															<td>You have new gang invitatations <a href='ganginvites.php'>View Gang Invites</a></td>
															</tr>";
														}
														/*	New player tutorial.
														if ($user_class->level == 1){
														echo Message("It looks like you are a new player. Please check out the <a href='http://bourbanlegends.com/wiki/index.php?title=Newb_Tutorial'>New Player Tutorial</a>");
													} */

													if ($user_class->jail > 0){
														echo "
														<thead>
														<tr>
														<th>Jail: You are currenty in jail for " . floor($user_class->jail / 60) . " more minutes.</th>
														</tr>
														</thead>
														";

													}
													if ($user_class->hospital > 0){
														echo "
														<thead>
														<tr>
														<td>Hospital: You are in the hospital for " . floor($user_class->hospital / 60) . " more minutes.</td>
														</tr>
														</thead>
														";
													}
													?>
												</table>
												<table class="inverted ui five unstackable column small compact table">
