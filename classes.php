<?php
function url_exists($url) {
	if(@file_get_contents($url,0,NULL,0,1)) {
		return 1;
	} else {
		return 0;
	}
}
function Gang_Event ($id, $text, $extra="0"){

  $timesent = time();
  
  $text = addslashes($text);

  $result= $GLOBALS['pdo']->prepare("INSERT INTO `gangevents` (`gang`, `timesent`, `text`, `extra`)".

  "VALUES (?,?,?,?)");
$result->execute(array($id,$timesent,$text,$extra));
}

function addBrowser($userid, $name)
{
	//addBrowser("$user_class->id", "$user_class->username");
	$get_class = new User($userid);

	$query = $GLOBALS['pdo']->prepare("SELECT `id` FROM `forum_browsers` WHERE `userid` = $userid");
	$query->execute();
	$check = $query->fetch(PDO::FETCH_ASSOC);

	if($check['id'] == "")
	{
		$result = $GLOBALS['pdo']->prepare("INSERT INTO `forum_browsers` (userid, name)
			VALUES (\"$userid\", \"$name\") ");
	$result->execute();
	}
}


function Check_Invent($userid) {

$result = $GLOBALS['pdo']->prepare("SELECT * FROM `inventory` WHERE `userid`='".$userid."'");
$result->execute();
$invent = 0;
$fe = $result->fetch(PDO::FETCH_ASSOC);
foreach ($fe AS $line){
$invent = $invent + $line['quantity'];
}

return $invent;

}
function StaffLog($id, $text, $extra="0") {
	$time = time();
	$text = addslashes($text);
	$result = $GLOBALS['pdo']->prepare("INSERT INTO `staff_logs` (`player`, `text`, `timestamp`, `extra`) VALUES (?,?,?,?)");	
	$result->execute(array($id,$text,$time,$extra));
}

function Get_ID($username){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `username` = ?');
	$result->execute(array($username));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	return $worked['id'];
}

function mrefresh($url, $time = "1")
                {
                echo '<meta http-equiv="refresh" content="' . $time . ';url=' . $url . '">';
                }
function car_popup($text, $id)
                {
                return "<a href='#' onclick=\"javascript:window.open( 'cardesc.php?id=" . $id . "', '60', 'left = 20, top = 20, width = 400, height = 400, toolbar = 0, resizable = 0, scrollbars=1' );\">" . $text . "</a>";
                }
function item_popup($text, $id)
                {
                return "<a href='#' onclick=\"javascript:window.open( 'description.php?id=" . $id . "', '60', 'left = 20, top = 20, width = 400, height = 400, toolbar = 0, resizable = 0, scrollbars=1' );\">" . $text . "</a>";
                }
function drug_popup($text, $id)
                {
                return "<a href='#' onclick=\"javascript:window.open( 'drugdesc.php?id=" . $id . "', '60', 'left = 20, top = 20, width = 400, height = 400, toolbar = 0, resizable = 0, scrollbars=1' );\">" . $text . "</a>";
                }
				function Relationship_Req($player, $type, $from) {
	$time = time();
	$result = $GLOBALS['pdo']->prepare("INSERT INTO `rel_requests` (`player`, `from`, `status`, `timestamp`) VALUES ('".$player."', '".$from."', '".$type."', '".$time."')");	
	$result->execute();
	}

function prettynum($num, $dollar = "0")
                {
                // Basic send a number or string to this and it will add commas. If you want a dollar sign added to the
                // front and it is a number add a 1 for the 2nd variable.
                // Example prettynum(123452838,1)  will return $123,452,838 take out the ,1 and it looses the dollar sign.
                $out = strrev((string) preg_replace('/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev($num)));
                if ($dollar && is_numeric($num))
                                {
                                $out = "$" . $out;
                                }
                return $out;
                }
function Check_Item($itemid, $userid){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
	$result->execute(array($userid, $itemid));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	if($worked['quantity'] > 0){
		return $worked['quantity'];
	}else{
		return 0;
	}
}
function Check_Land($city, $userid){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `land` WHERE `userid` = ? AND `city` = ?');
	$result->execute(array($userid, $city));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	if($worked['quantity'] > 0){
		return $worked['quantity'];
	}else{
		return 0;
	}
}

//userid    companyid    howmany
function Give_Share($stock, $userid, $quantity = "1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `shares` WHERE `userid` = ? AND `companyid` = ?');
	$result->execute(array($userid, $stock));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist == 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO shares (companyid, userid, amount) VALUES (?, ?, ?)');
		$result->execute(array($stock, $userid, $quantity));
	}else{
		$quantity = $quantity + $worked['amount'];
		$result = $GLOBALS['pdo']->preapre('UPDATE `shares` SET `amount` = ? WHERE `userid` = ? AND `companyid` = ?');
		$result->execute(array($quantity, $userid, $stock));
	}
}

function Take_Share($stock, $userid, $quantity = "1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `shares` WHERE `userid` = ? AND `companyid` = ?');
	$result->execute(array($userid, $stock));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist != 0){
		$quantity = $worked['amount'] - $quantity;

		if($quantity > 0){
			$result = $GLOBALS['pdo']->prepare('UPDATE `shares` SET `amount` = ? WHERE `userid` = ? AND `companyid` = ?');
			$result->execute(array($quantity, $userid, $stock));
		}else{
			$result = $GLOBALS['pdo']->prepare('DELETE FROM `shares` WHERE `userid` = ? AND `companyid` = ?');
			$result->execute(array($userid, $stock));
		}
	}
}

function Check_Share($stock, $userid){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `shares` WHERE `userid` = ? AND `companyid` = ?');
	$result->execute(array($userid, $stock));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	if($worked['amount'] > 0){
		return $worked['amount'];
	}else{
		return 0;
	}
}
class GangRank {
 function GangRank($rank) {
  $result = $GLOBALS['pdo']->prepare("SELECT * FROM `ranks` WHERE `id`=?");
  $result->execute($rank);
  $field = $result->fetch(PDO::FETCH_ASSOC);

  $this->id = $field['id'];
  $this->gang = $field['gang'];
  $this->title = $field['title'];
  $this->members = $field['members'];
  $this->crime = $field['crime'];
  $this->vault = $field['vault'];
  $this->ranks = $field['ranks'];
  $this->massmail = $field['massmail'];
  $this->applications = $field['applications'];
  $this->appearance = $field['appearance'];
  $this->invite = $field['invite'];
  $this->houses = $field['houses'];
  $this->upgrade = $field['upgrade'];
  $this->gforum = $field['gforum'];
  $this->polls = $field['polls'];
 }
}


function Give_Land($city, $userid, $quantity = "1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `land` WHERE `userid` = ? AND `city` = ?');
	$result->execute(array($userid, $city));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($wotrked);
	$worked = $worked[0];

	if($itemexist == 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `land` (`city`, `userid`, `amount`) VALUES (?, ?, ?)');
		$result->execute(array($city, $userid, $quantity));
	}else{
		$quantity = $quantity + $worked['amount'];

		$result = $GLOBALS['pdo']->prepare('UPDATE `land` SET `amount` = ? WHERE `userid` = ? AND `city` = ?');
		$result->execute(array($quantity, $userid, $city));
	}
}

function Take_Land($city, $userid, $quantity = "1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `land` WHERE `userid` = ? AND `city` = ?');
	$result->execute(array($userid, $city));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist != 0){
		$quantity = $worked['amount'] - $quantity;
		if ($quantity > 0){
			$result = $GLOBALS['pdo']->prepare('UPDATE `land` SET `amount` = ? WHERE `userid` = ? AND `city` = ?');
			$result->execute(array($quantity, $userid, $city));
		}else{
			$result = $GLOBALS['pdo']->prepare('DELETE FROM `land` WHERE `userid` = ? AND `city` = ?');
			$result->execute(array($userid, $city));
		}
	}
}

function Give_Item($itemid, $userid, $quantity="1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
	$result->execute(array($userid, $itemid));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist == 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `inventory` (`itemid`, `userid`, `quantity`) VALUES (?, ?, ?)');
		$result->execute(array($itemid, $userid, $quantity));
	} else {
		$quantity = $quantity + $worked['quantity'];

		$result = $GLOBALS['pdo']->prepare('UPDATE `inventory` SET `quantity` = ? WHERE `userid` = ? AND `itemid` = ?');
		$result->execute(array($quantity, $userid, $itemid));
	}
}

function Take_Item($itemid, $userid, $quantity="1"){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
	$result->execute(array($userid, $itemid));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist != 0){
		$quantity = $worked['quantity'] - $quantity;
		if($quantity > 0){
			$result = $GLOBALS['pdo']->prepare('UPDATE `inventory` SET `quantity` = ? WHERE `userid` = ? AND `itemid` = ?');
			$result->execute(array($quantity, $userid, $itemid));
		}else{
			$result = $GLOBALS['pdo']->prepare('DELETE FROM `inventory` WHERE `userid` = ? AND `itemid` = ?');
			$result->execute(array($userid, $itemid));
		}
	}
}

function Give_Drug($itemid, $userid, $quantity, $startingcity, $price){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ? AND `drugid` = ?');
	$result->execute(array($userid, $itemid));
	$worked = $result->fetchAll(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist == 0){
		$result = $GLOBALS['pdo']->prepare('INSERT INTO `drugstorage` (`drugid`, `userid`, `amount`, `startingcity`,buy_amount) VALUES (?, ?, ?, ?,?)');
		$result->execute(array($itemid, $userid, $quantity, $startingcity, $price));
	}else{
		$quantity = $quantity + $worked['amount'];

		$result = $GLOBALS['pdo']->prepare('UPDATE `drugstorage` SET `amount` = ? WHERE `userid` = ? AND `drugid` = ?');
		$result->execute(array($quantity, $userid, $itemid));
	}
}

function Take_Drug($itemid, $userid, $quantity){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `drugstorage` WHERE `userid` = ? AND `drugid` = ?');
	$result->execute(array($userid, $itemid));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	$itemexist = count($worked);
	$worked = $worked[0];

	if($itemexist != 0){
		$quantity = $worked['amount'] - $quantity;
		if ($quantity > 0){
			$result = $GLOBALS['pdo']->prepare('UPDATE `drugstorage` SET `amount` = ? WHERE `userid` = ? AND `drugid` = ?');
			$result->execute(array($quantity, $userid, $itemid));
		}else{
			$result = $GLOBALS['pdo']->prepare('DELETE FROM `drugstorage` WHERE `userid` = ? AND `drugid` = ?');
			$result->execute(array($userid, $itemid));
		}
	}
}

function Message($text)
                {
                return '
		        <thead>
			    <tr>
				<th>Important Message</th>
		  		</tr>
		  		</thead>

<tr><td>' . $text . '</td></tr>		  		</table>
		<table class="inverted ui five unstackable column small compact table">

';
                }
function Send_Event($id, $text){
	$timesent = time();

	$result = $GLOBALS['pdo']->prepare('INSERT INTO `events` (`to`, `timesent`, `text`) VALUES (?, ?, ?)');
	$result->execute(array($id, $timesent, $text));
	  $result= $GLOBALS['pdo']->prepare("INSERT INTO `eventslog` (`to`, `timesent`, `text`) VALUES (?,?,?)");
$result->execute(array($id, $timesent, $text));
}

function Is_User_Banned($id){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `bans` WHERE `id` = ?');
	$result->execute(array($id));
	return count($result->fetchAll(PDO::FETCH_ASSOC));
}

function Why_Is_User_Banned($id){
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `bans` WHERE `id` = ?');
	$result->execute(array($id));
	$worked = $result->fetch(PDO::FETCH_ASSOC);

	return $worked['reason'];
}

function Radio_Status(){
	$worked = $GLOBALS['pdo']->query('SELECT * FROM `serverconfig`')->fetch(PDO::FETCH_ASSOC);
	return $worked['radio'];
}
function GangExperience($L) {
  $a=0;
  $end=0;

  for($x=1; $x<$L; $x++) {
    $a += round($x+2000*pow(4, ($x/190)));
  }


if ($x > 349){
    $a += round($x+2500*pow(4, ($x/190)));
}

if ($x > 478){
    $a += round($x+3500*pow(6, ($x/130)));
}

if ($x > 499){
    $a += round($x+10000*pow(6, ($x/120)));
}
  return round($a/4);
}


function howlongago($ts)
                {
                $ts = time() - $ts;
                if ($ts < 1)
                // <1 second
                                return "seconds";
                elseif ($ts == 1)
                // <1 second
                                return $ts . " second";
                elseif ($ts < 60)
                // <1 minute
                                return $ts . " seconds";
                elseif ($ts < 120)
                // 1 minute
                                return "1 minute";
                elseif ($ts < 60 * 60)
                // <1 hour
                                return floor($ts / 60) . " minutes";
                elseif ($ts < 60 * 60 * 2)
                // <2 hour
                                return "1 hour";
                elseif ($ts < 60 * 60 * 24)
                // <24 hours = 1 day
                                return floor($ts / (60 * 60)) . " hours";
                elseif ($ts < 60 * 60 * 24 * 2)
                // <2 days
                                return "1 day";
                elseif ($ts < (60 * 60 * 24 * 7))
                // <7 days = 1 week
                                return floor($ts / (60 * 60 * 24)) . " days";
                elseif ($ts < 60 * 60 * 24 * 30.5)
                // <30.5 days ~  1 month
                                return floor($ts / (60 * 60 * 24 * 7)) . " weeks";
                elseif ($ts < 60 * 60 * 24 * 365)
                // <365 days = 1 year
                                return floor($ts / (60 * 60 * 24 * 30.5)) . " months";
                else
                // more than 1 year
                                return floor($ts / (60 * 60 * 24 * 365)) . " years";
                }
;
function howlongtil($ts)
                {
                $ts = $ts - time();
                if ($ts < 1)
                // <1 second
                                return " NOW";
                elseif ($ts == 1)
                // <1 second
                                return $ts . " second";
                elseif ($ts < 60)
                // <1 minute
                                return $ts . " seconds";
                elseif ($ts < 120)
                // 1 minute
                                return "1 minute";
                elseif ($ts < 60 * 60)
                // <1 hour
                                return floor($ts / 60) . " minutes";
                elseif ($ts < 60 * 60 * 2)
                // <2 hour
                                return "1 hour";
                elseif ($ts < 60 * 60 * 24)
                // <24 hours = 1 day
                                return floor($ts / (60 * 60)) . " hours";
                elseif ($ts < 60 * 60 * 24 * 2)
                // <2 days
                                return "1 day";
                elseif ($ts < (60 * 60 * 24 * 7))
                // <7 days = 1 week
                                return floor($ts / (60 * 60 * 24)) . " days";
                elseif ($ts < 60 * 60 * 24 * 30.5)
                // <30.5 days ~  1 month
                                return floor($ts / (60 * 60 * 24 * 7)) . " weeks";
                elseif ($ts < 60 * 60 * 24 * 365)
                // <365 days = 1 year
                                return floor($ts / (60 * 60 * 24 * 30.5)) . " months";
                else
                // more than 1 year
                                return floor($ts / (60 * 60 * 24 * 365)) . " years";
                }
;
//level 2 - 500
//level 3 - 1500
//level 4 - 3500
//level 5 - 6000
function experience($L)
                {
                $a   = 0;
                $end = 0;
                for ($x = 1; $x < $L; $x++)
                                {
                                $a += floor($x + 1500 * pow(4, ($x / 7)));
                                }
                return floor($a / 4);
                }
//
function Get_The_Level($exp)
                {
                $a   = 0;
                $end = 0;
                for ($x = 1; ($end == 0 && $x < 100); $x++)
                                {
                                $a += floor($x + 1500 * pow(4, ($x / 7)));
                                if ($exp >= floor($a / 4))
                                                {
                                                }
                                else
                                                {
                                                return $x;
                                                $end = 1;
                                                }
                                }
                }
// level exp needed
function Get_Max_Exp($L)
                {
                $exp_needed_define = (int) (($L + 3) * ($L + 4) * ($L + 5) * 7.2);
                return $exp_needed_define;
                }

class User_Stats{
	function User_Stats($wutever){
		$result = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `username` ASC')->fetchAll(PDO::FETCH_ASSOC);
		$result3 = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers` ORDER BY `username` ASC')->fetchAll(PDO::FETCH_ASSOC);

		foreach($result as $line){
			$secondsago = time() - $line['lastactive'];
			if($secondsago <= 900){
				$this->playersloggedin++;
			}
		}

		foreach($result3 as $line3){
			$secondsago = time() - $line3['lastactive'];
			if ($secondsago <= 86400){
				$this->playersonlineinlastday++;
			}
		}

		$result2 = $GLOBALS['pdo']->query('SELECT * FROM `grpgusers`');
		$this->playerstotal = count($result2->fetchAll(PDO::FETCH_NUM));
	}
}

class Gang{
	function Gang($id){
		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `gangs` WHERE `id` = ?');
		$result->execute(array($id));
		$worked = $result->fetch(PDO::FETCH_ASSOC);

		$gangcheck = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `gang` = ?');
		$gangcheck->execute(array($id));

		$this->members = count($gangcheck->fetchAll(PDO::FETCH_NUM));

		$ganghouse = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
		$ganghouse->execute(array($worked['house']));
		$ghouse = $ganghouse->fetch(PDO::FETCH_ASSOC);

		$this->id            = $worked['id'];
		$this->name          = $worked['name'];
		$this->formattedname = "<a href='viewgang.php?id=" . $worked['id'] . "'>" . $worked['name'] . "</a>";
		$this->description   = $worked['description'];
		$this->leader        = $worked['leader'];
		$this->tag           = $worked['tag'];
		$this->exp           = $worked['exp'];
		$this->moneyvault           = $worked['moneyvault'];
		$this->pointsvault           = $worked['pointsvault'];
		$this->level         = Get_The_Level($this->exp);

	$this->houses = $worked['ghouse'];
	
	$ghouselala = $GLOBALS['pdo']->prepare("SELECT * FROM `ghouses` WHERE `id` = '".$this->houses."'");
$ghouselala->execute();
	$ganghouse = $ghouselala->fetch(PDO::FETCH_ASSOC);
	
	$this->housenamez = $ganghouse['name'];
	
	$this->housenamez = ($this->housenamez == "") ? "None" : $this->housenamez;
	
	$this->houseawakez = $ganghouse['awake'];
	
	$this->houseawakez = ($this->houseawakez == "") ? "0" : $this->houseawakez;
	
	$this->housecost = $ganghouse['cost'];
	
		$this->vault         = $worked['vault'];
		$this->points        = $worked['points'];
		$this->house         = $worked['house'];
		$this->bonus_awake   = $ghouse['bonus_awake'];
		$this->storage       = $ghouse['storage'];
		$this->housename     = $ghouse['name'];
		$this->tax = $worked['tax'];
		$this->capacity = $worked['capacity'];
		
    $this->maxexp = GangExperience($this->level +1);


	$this->exppercent = ($this->exp == 0) ? 0 : floor(($this->exp / $this->maxexp) * 100);

	$this->formattedexp = prettynum($this->exp)." / ".prettynum($this->maxexp)." [".$this->exppercent."%]";
	
	if ($this->exp >= $this->maxexp && $this->exp > 0){
         $newlvl = $this->level + 1;
	     $expleft = $this->exp - $this->maxexp;
         $result2 = $GLOBALS['pdo']->prepare("UPDATE `gangs` SET `level`='".$newlvl."', `exp` = '".$expleft."' WHERE `id`='".$this->id."'");
		 $result2->execute();
		 Gang_Event($this->id, "Your gang has just gained a level!");
		 $result = $GLOBALS['pdo']->prepare("SELECT * FROM `grpgusers` WHERE `gang` = '".$this->id."'");
		 $res = $result->fetch(PDO::FETCH_ASSOC);
		 foreach ($res AS $line){
		 	Send_Event($line['id'], "Your gang has just gained a level!");
		 }
    }
	}
}

class User{
	function User($id){

		$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `id` = ?');
		$result->execute(array($id));
		$worked = $result->fetch(PDO::FETCH_ASSOC);

		$result2 = $GLOBALS['pdo']->prepare('SELECT * FROM `gangs` WHERE `id` = ?');
		$result2->execute(array($worked['gang']));
		$worked2 = $result2->fetch(PDO::FETCH_ASSOC);

		$ganghouse = $GLOBALS['pdo']->prepare('SELECT * FROM `ganghouse` WHERE `id` = ?');
		$ganghouse->execute(array($worked2['house']));
		$worked_g = $ganghouse->fetch(PDO::FETCH_ASSOC);

		$result3 = $GLOBALS['pdo']->prepare('SELECT * FROM `cities` WHERE `id` = ?');
		$result3->execute(array($worked['city']));
		$worked3 = $result3->fetch(PDO::FETCH_ASSOC);

		$result4 = $GLOBALS['pdo']->prepare('SELECT * FROM `houses` WHERE `id` = ?');
		$result4->execute(array($worked['house']));
		$worked4 = $result4->fetch(PDO::FETCH_ASSOC);

		$result5 = $GLOBALS['pdo']->prepare('SELECT * FROM `inventory` WHERE `userid` = ? ORDER BY `userid` DESC');
		$result5->execute(array($id));

		$checkcocaine = $GLOBALS['pdo']->prepare('SELECT * FROM `effects` WHERE `userid` = ? AND `effect` = "Cocaine"');
		$checkcocaine->execute(array($id));

		$cocaine = count($checkcocaine->fetchAll(PDO::FETCH_NUM));

		$speedbonus           = ($cocaine > 0) ? (floor($worked['speed'] * .30)) : 0;
		$this->bonus_awake    = $worked_g['bonus_awake'];
		$this->weaponoffense  = 0;
		$this->weapondefense  = 0;
		$this->weaponspeed    = 0;
		$this->weaponname     = "fists";
		$this->armoroffense   = 0;
		$this->armordefense   = 0;
		$this->armorspeed     = 0;
		$this->offhandoffense = 0;
		$this->offhanddefense = 0;
		$this->offhandspeed   = 0;

		if ($worked["eqweapon"] != 0){
			$result6 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ? LIMIT 1');
			$result6->execute(array($worked['eqweapon']));
			$worked6 = $result6->fetch(PDO::FETCH_ASSOC);

			$this->eqweapon      = $worked6['id'];
			$this->weaponoffense = $worked6['offense'];
			$this->weapondefense = $worked6['defense'];
			$this->weaponname    = $worked6['itemname'];
			$this->weaponimg     = $worked6['image'];
			$this->weaponspeed   = $worked6['speed'];
			$weaponAttBonus      = $worked['strength'] / 100 * $this->weaponoffense;
			$weaponDefBonus      = $worked['defense'] / 100 * $this->weapondefense;
			$weaponSpdBonus      = $worked['speed'] / 100 * $this->weaponspeed;
		}

		if ($worked["eqarmor"] != 0){
			$result6 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ? LIMIT 1');
			$result6->execute(array($worked['eqarmor']));
			$worked6 = $result6->fetch(PDO::FETCH_ASSOC);

			$this->eqarmor      = $worked6['id'];
			$this->armoroffense = $worked6['offense'];
			$this->armordefense = $worked6['defense'];
			$this->armorname    = $worked6['itemname'];
			$this->armorimg     = $worked6['image'];
			$this->armorspeed   = $worked6['speed'];
			$armorAttBonus      = $worked['strength'] / 100 * $this->armoroffense;
			$armorDefBonus      = $worked['defense'] / 100 * $this->armordefense;
			$armorSpdBonus      = $worked['speed'] / 100 * $this->armorspeed;
		}

		if ($worked["eqoffhand"] != 0){
			$result6 = $GLOBALS['pdo']->prepare('SELECT * FROM `items` WHERE `id` = ? LIMIT 1');
			$result6->execute(array($worked['eqoffhand']));
			$worked6 = $result6->fetch(PDO::FETCH_ASSOC);

			$this->eqoffhand      = $worked6['id'];
			$this->offhandoffense = $worked6['offense'];
			$this->offhanddefense = $worked6['defense'];
			$this->offhandname    = $worked6['itemname'];
			$this->offhandimg     = $worked6['image'];
			$this->offhandspeed   = $worked6['speed'];
			$this->offhandStorage = $worked6['storage'];
			$offhandAttBonus      = $worked['strength'] / 100 * $this->offhandoffense;
			$offhandDefBonus      = $worked['defense'] / 100 * $this->offhanddefense;
			$offhandSpdBonus      = $worked['speed'] / 100 * $this->offhandspeed;
		}

		$this->id                  = $worked['id'];
		$this->ip                  = $worked['ip'];
		$this->style               = ($worked['style'] > 0) ? $worked['style'] : "1";
		$this->speedbonus          = $speedbonus;
		$this->storage 			   = $worked['storage'] + $worked_g['storage'];
		$this->username            = $worked['username'];
		$this->marijuana           = $worked['marijuana'];
		$this->potseeds            = $worked['potseeds'];
		$this->cocaine             = $worked['cocaine'];
		$this->nodoze              = $worked['nodoze'];
		$this->genericsteroids     = $worked['genericsteroids'];
		$this->hookers             = $worked['hookers'];
		$this->exp                 = $worked['exp'];
		$this->level               = $worked['level'];
		$this->maxexp              = Get_Max_Exp($this->level);
		$this->exppercent          = ($this->exp == 0) ? 0 : floor(($this->exp / $this->maxexp) * 100);
		$this->formattedexp        = $this->exp . " / " . $this->maxexp . " [" . $this->exppercent . "%]";
		$this->money               = $worked['money'];
		$this->bank                = $worked['bank'];
		$this->whichbank           = $worked['whichbank'];
		$this->hp                  = $worked['hp'];
		$this->maxhp               = $this->level * 50;
		$this->hppercent           = floor(($this->hp / $this->maxhp) * 100);
		$this->formattedhp         = $this->hp . " / " . $this->maxhp . " [" . $this->hppercent . "%]";
		$this->energy              = $worked['energy'];
		$this->maxenergy           = 9 + $this->level;
		$this->heat                = $worked['heat'];
		$this->energypercent       = floor(($this->energy / $this->maxenergy) * 100);
		$this->formattedenergy     = $this->energy . " / " . $this->maxenergy . " [" . $this->energypercent . "%]";
		$this->nerve               = $worked['nerve'];
		$this->maxnerve            = 4 + $this->level;
		$this->nervepercent        = floor(($this->nerve / $this->maxnerve) * 100);
		$this->formattednerve      = $this->nerve . " / " . $this->maxnerve . " [" . $this->nervepercent . "%]";
		$this->workexp             = $worked['workexp'];
		$this->strength            = $worked['strength'];
		$this->defense             = $worked['defense'];
		$this->speed               = $worked['speed'];
		$this->sig               = $worked['sig'];
		$this->totalattrib         = $this->speed + $this->strength + $this->defense;
		$this->battlewon           = $worked['battlewon'];
		$this->battlelost          = $worked['battlelost'];
		$this->battletotal         = $this->battlewon + $this->battlelost;
		$this->battlemoney         = $worked['battlemoney'];
		$this->crimesucceeded      = $worked['crimesucceeded'];
		$this->crimefailed         = $worked['crimefailed'];
		$this->crimetotal          = $this->crimesucceeded + $this->crimefailed;
		$this->crimemoney          = $worked['crimemoney'];
		$this->relationship        = $worked['relationship'];
		$this->relplayer        = $worked['relplayer'];
		$this->lastactive          = $worked['lastactive'];
		$this->age                 = howlongago($worked['signuptime']);
		$this->formattedlastactive = howlongago($this->lastactive) . " ago";
		$this->points              = $worked['points'];
		$this->rating              = $worked['rating'];
		$this->busts              = $worked['busts'];
		$this->caught              = $worked['caught'];
		$this->gender              = $worked['gender'];
		$this->rmdays              = $worked['rmdays'];
		$this->signuptime          = $worked['signuptime'];
		$this->lastactive          = $worked['lastactive'];
		$this->house               = $worked['house'];
		$this->housename           = ($worked4['name'] == "") ? "Homeless" : $worked4['name'];
		$this->houseawake          = ($worked4['name'] == "") ? 100 : $worked4['awake'];
		$this->awake               = $worked['awake'];
		$this->maxawake            = $this->houseawake + $this->bonus_awake;
		$this->awakepercent        = floor(($this->awake / $this->maxawake) * 100);
		$this->formattedawake      = $this->awake . " / " . $this->maxawake . " [" . $this->awakepercent . "%]";
		$this->email               = $worked['email'];
		$this->house               = $worked['house'];
		$this->admin               = $worked['admin'];
		$this->quote               = $worked['quote'];
		$this->avatar              = $worked['avatar'];
		$this->gang                = $worked['gang'];
		$this->gangname            = $worked2['name'];
		$this->gangleader          = $worked2['leader'];
		$this->gangtag             = $worked2['tag'];
		$this->gangdescription     = $worked2['description'];
		$this->formattedgang       = "<a href='viewgang.php?id=" . $this->gang . "'>" . $this->gangname . "</a>";
		$this->city                = $worked['city'];
		$this->cityname            = $worked3['name'];
		$this->jail                = $worked['jail'];
		$this->job                 = $worked['job'];
		$this->hospital            = $worked['hospital'];
		$this->searchdowntown      = $worked['searchdowntown'];
		
	//Badges
	if($this->level >= 300) {
	$this->badge1 = "<img src='images/lvl300.gif' title='Level Up: Get to level 300' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->level >= 100) {
	$this->badge1 = "<img src='images/lvl100.gif' title='Level Up: Get to level 100' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->level >= 25) {
	$this->badge1 = "<img src='images/lvl25.gif' title='Level Up: Get to level 25' width='50' height='50' />";
	$this->badge = 1;
	}
	
	if($this->crimesucceeded >= 7500) {
	$this->badge2 = "<img src='images/crime3.gif' title='Elite Criminal: Successfully complete 7,500 crimes' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->crimesucceeded >= 1000) {
	$this->badge2 = "<img src='images/crime2.gif' title='Elite Criminal: Successfully complete 1,000 crimes' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->crimesucceeded >= 500) {
	$this->badge2 = "<img src='images/crime1.gif' title='Elite Criminal: Successfully complete 500 crimes' width='50' height='50' />";
	$this->badge = 1;
	}
	
	if($this->battlewon >= 10000) {
	$this->badge4 = "<img src='images/kills3.gif' title='Master Hitman: Win 10,000 battles' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->battlewon >= 2000) {
	$this->badge4 = "<img src='images/kills2.gif' title='Master Hitman: Win 2,000 battles' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->battlewon >= 500) {
	$this->badge4 = "<img src='images/kills1.gif' title='Master Hitman: Win 500 battles' width='50' height='50' />";
	$this->badge = 1;
	}
	
	if($this->battlelost >= 10000) {
	$this->badge5 = "<img src='images/deaths3.gif' title='Popular Target: Lose 10,000 battles' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->battlelost >= 2000) {
	$this->badge5 = "<img src='images/deaths2.gif' title='Popular Target: Lose 2,000 battles' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->battlelost >= 500) {
	$this->badge5 = "<img src='images/deaths1.gif' title='Popular Target: Lose 500 battles' width='50' height='50' />";
	$this->badge = 1;
	}
	
	if($this->bank >= 1000000000) {
	$this->badge6 = "<img src='images/banked3.gif' title='Pro Banker: Bank $1,000,000,000' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->bank >= 100000000) {
	$this->badge6 = "<img src='images/banked2.gif' title='Pro Banker: Bank $100,000,000' width='50' height='50' />";
	$this->badge = 1;
	} else if ($this->bank >= 10000000) {
	$this->badge6 = "<img src='images/10mil.png' title='Pro Banker: Bank $10,000,000' width='50' height='50' />";
	$this->badge = 1;
	}
	
		$this->moddedstrength      = $this->strength + $weaponAttBonus + $armorAttBonus + $offhandAttBonus;
		$this->moddeddefense       = $this->defense + $weaponDefBonus + $armorDefBonus + $offhandDefBonus;
		$this->moddedspeed         = $this->speed + $weaponSpdBonus + $armorSpdBonus + $offhandSpdBonus;

                                if ($this->gang != 0)
                                                {
                                                $this->formattedname .= "<a href='viewgang.php?id=" . $this->gang . "'";
                                                $this->formattedname .= ($this->gangleader == $this->username) ? " title='Gang Leader'>[<b>" . $this->gangtag . "</b>]</a>" : ">[" . $this->gangtag . "]</a>";

															$worked_g['storage'] = 0;

                                                }
                                if ($this->rmdays != 0)
                                                {
                                                $this->type = "Respected Mobster";
                                                $whichfont  = "yellow";
                                                }
                                else
                                                {
                                                $this->type = "Regular Mobster";
                                                }
                                if ($this->admin == 1)
                                                {
                                                $this->type = "Admin";
                                                $whichfont  = "red";
                                                }
                                if ($this->admin == 2)
                                                {
                                                $this->type = "Staff";
                                                }
                                if ($this->admin == 3)
                                                {
                                                $this->type = "Pre
        ent";
                                                $whichfont  = "red";
                                                }
                                if ($this->admin == 4)
                                                {
                                                $this->type = "Congress";
                                                $whichfont  = "red";
                                                }
                                if ($this->rmdays > 0)
                                                {
                                                $this->formattedname .= "<b><a title='Respected Mobster [" . $this->rmdays . " RM Days Left]' href='profiles.php?id=" . $this->id . "'><font color = '" . $whichfont . "'>" . $this->username . "</a></font></b>";
                                                }
                                elseif ($this->admin != 0)
                                                {
                                                $this->formattedname .= "<b><a href='profiles.php?id=" . $this->id . "'><font color = '" . $whichfont . "'>" . $this->username . "</a></font></b>";
                                                }
                                else
                                                {
                                                $this->formattedname .= "<a href='profiles.php?id=" . $this->id . "'><font color = '" . $whichfont . "'>" . $this->username . "</a></font>";
                                                }
                                if (time() - $this->lastactive < 300)
                                                {
                                                $this->formattedonline = "<font style='color:green;padding:2px;font-weight:bold;'>[online]</font>";
                                                }
                                else
                                                {
                                                $this->formattedonline = "<font style='color:red;padding:2px;font-weight:bold;'>[offline]</font>";
                                                }
                                }
                }
?>
