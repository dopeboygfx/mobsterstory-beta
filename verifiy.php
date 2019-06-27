<?
include 'nliheader.php';

if(isset($_GET['verification_code']) && !empty($_GET['verification_code']) && !empty($_GET['user']
    )){
    
	$result = $GLOBALS['pdo']->prepare('SELECT * FROM `grpgusers` WHERE `email` = ? and `verification_code` = ?');
	if(!$result->execute(array($_GET['user'], $_GET['verification_code']))){
		die("Login Name and password not found or not matched");
	}

	$worked = $result->fetch(PDO::FETCH_ASSOC);
    if(!empty($worked)){
        $verify = $GLOBALS['pdo']->prepare('update `grpgusers` set `verified` = 1 WHERE `id` = ?');
	    $verify->execute(array($_GET['user']));
	    echo Message('Congratulation, You eamil verified successfully. Redirecting to login page in 5 seconds. <meta http-equiv="refresh" content="5;url=login.php">');
    }
}

include 'nlifooter.php';